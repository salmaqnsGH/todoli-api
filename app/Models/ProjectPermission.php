<?php

namespace App\Models;

use App\Traits\HasActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Permission;

/**
 * @mixin Builder
 *
 * @property int $id
 * @property int $project_id
 * @property int $user_id
 * @property int $permission_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Project $project
 * @property-read User $user
 * @property-read Permission $permission
 * @property-read Activity[] $activities
 */
class ProjectPermission extends Model
{
    use SoftDeletes, HasActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['project_id', 'user_id', 'permission_id'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'model');
    }
}
