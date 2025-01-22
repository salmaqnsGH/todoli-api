<?php

namespace App\Models;

use App\Constants\UserRole;
use App\Traits\HasActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @mixin Builder
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string|null $description
 * @property int $user_id
 * @property string|null $image
 * @property string|null $image_url
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read User $owner
 * @property-read User[] $all_members
 * @property-read User[] $team_members
 * @property-read Task[] $tasks
 * @property-read Activity[] $activities
 */
class Project extends Model
{
    use HasActivity, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'description', 'user_id', 'category_id', 'image'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'image',
        'deleted_at',
    ];

    /**
     * The attributes that are appended to the result.
     *
     * @var list<string>
     */
    protected $appends = ['short_hash', 'image_url'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($project) {
            // Soft delete related models
            $project->project_members()->delete();
            $project->project_permissions()->delete();
            $project->members()->delete();
            $project->tasks()->delete();
            $project->activities()->delete();
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'category_id');
    }

    public function project_members(): HasMany
    {
        return $this->hasMany(ProjectMember::class, 'project_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Members including owner
    public function all_members()
    {
        return $this->hasManyThrough(
            User::class,
            ProjectMember::class,
            'project_id',  // Foreign key on project_members table
            'id',          // Foreign key on users table
            'id',          // Local key on projects table
            'user_id'      // Local key on project_members table
        );
    }

    // Members excluding owner
    public function members()
    {
        return $this->all_members()->where('project_members.role_id', UserRole::USER_MEMBER);
    }

    // All users who are not members of this project
    public function non_members()
    {
        return User::whereNotIn('id', function ($query) {
            $query->select('user_id')
                ->from('project_members')
                ->where('project_id', $this->id);
        });
    }

    public function project_permissions(): HasMany
    {
        return $this->hasMany(ProjectPermission::class, 'project_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'project_id');
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'model');
    }

    public function getShortHashAttribute(): string
    {
        $input = $this->id.'_'.$this->name;

        return Str::substr(
            md5($input),
            0,
            8
        );
    }

    public function getImageUrlAttribute(): ?string
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter */
        $disk = Storage::disk('public');

        return $this->image
            ? $disk->url($this->image)
            : null;
    }
}
