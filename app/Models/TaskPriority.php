<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin Builder
 *
 * @property int $id
 * @property string $name
 * @property string $color
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Task[] $tasks
 * @property-read Activity[] $activities
 */
class TaskPriority extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'color'];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'priority_id');
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'model');
    }
}
