<?php

namespace App\Models;

use App\Traits\HasActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin Builder
 *
 * @property int $id
 * @property int $task_id
 * @property string|null $image
 * @property string|null $image_url
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Task $task
 * @property-read Activity[] $activities
 */
class TaskImage extends Model
{
    use HasActivity, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['image'];

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
    protected $appends = ['image_url'];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'model');
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
