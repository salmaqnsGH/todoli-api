<?php

namespace App\Models;

use App\Traits\HasActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin Builder
 *
 * @property int $id
 * @property int $project_id
 * @property int $user_id
 * @property int $priority_id
 * @property int $status_id
 * @property string $name
 * @property string|null $objective
 * @property string|null $description
 * @property string|null $additional_notes
 * @property Carbon|null $due_date
 * @property string|null $image
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Project $project
 * @property-read TaskPriority $priority
 * @property-read TaskStatus $status
 * @property-read TaskComment[] $comments
 * @property-read Activity[] $activities
 */
class Task extends Model
{
    use HasActivity, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'project_id',
        'user_id',
        'priority_id',
        'status_id',
        'name',
        'objective',
        'description',
        'additional_notes',
        'due_date',
        'image',
    ];

    /**
     * The attributes that are casted.
     *
     * @var list<string>
     */
    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(TaskPriority::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(TaskStatus::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class, 'task_id');
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'model');
    }
}
