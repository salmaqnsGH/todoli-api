<?php

namespace App\Models;

use App\Traits\HasActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role;

/**
 * @mixin Builder
 *
 * @property int $id
 * @property int $project_id
 * @property int $user_id
 * @property int $role_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Project $project
 * @property-read User $user
 * @property-read Role $role
 * @property-read Activity[] $activities
 */
class ProjectMember extends Model
{
    use HasActivity, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['project_id', 'user_id', 'role_id'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'model');
    }
}
