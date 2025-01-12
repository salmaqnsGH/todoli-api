<?php

namespace App\Models;

use App\Traits\HasActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin Builder
 *
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Activity[] $activities
 */
class ProjectCategory extends Model
{
    use SoftDeletes, HasActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name'];

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'model');
    }
}
