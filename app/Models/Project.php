<?php

namespace App\Models;

use App\Constants\ProjectRoleType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin Builder
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $role_type
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
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'description', 'role_type'];

    public function owner(): User
    {
        return $this->all_members()
            ->wherePivot('role_type', ProjectRoleType::OWNER)
            ->first();
    }

    // Members including owner
    public function all_members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_members')
            ->withPivot('role_type')
            ->withTimestamps();
    }

    // Members excluding owner
    public function team_members()
    {
        return $this->all_members()
            ->wherePivot('role_type', ProjectRoleType::TEAM_MEMBER);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'project_id');
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'model');
    }
}
