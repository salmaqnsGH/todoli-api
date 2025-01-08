<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property int $user_id
 * @property int $action
 * @property array|null $data_changes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model $model
 * @property-read User $user
 */
class Activity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'model_type',
        'model_id',
        'user_id',
        'action',
        'data_changes',
    ];

    protected $casts = [
        'data_changes' => 'array',
        'action' => 'integer',
    ];

    /**
     * Get the owning model.
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
