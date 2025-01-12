<?php

namespace App\Models;

use App\Traits\HasActivity;
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
class TaskStatus extends Model
{
    use SoftDeletes, HasActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'color'];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'status_id');
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'model');
    }
}
