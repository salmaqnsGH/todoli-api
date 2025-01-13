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
use Illuminate\Support\Str;

/**
 * @mixin Builder
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $user_id
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
    protected $fillable = ['name', 'description', 'user_id'];

    protected $appends = ['short_hash'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Members excluding owner
    public function members()
    {
        return $this->hasManyThrough(
            User::class,
            ProjectMember::class,
            'project_id',  // Foreign key on project_members table
            'id',          // Foreign key on users table
            'id',          // Local key on projects table
            'user_id'      // Local key on project_members table
        )->where('project_members.role_id', UserRole::USER_MEMBER);
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
}
