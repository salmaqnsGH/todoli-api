<?php

namespace App\Traits;

use App\Constants\ActivityAction;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

// TODO send notification email
trait HasActivity
{
    protected static function bootHasActivity()
    {
        static::created(function ($model) {
            $changes = collect($model->getAttributes())
                ->except(['created_at', 'updated_at', 'deleted_at'])
                ->mapWithKeys(function ($value, $field) {
                    return [$field => [
                        'old' => null,
                        'new' => $value
                    ]];
                })
                ->toArray();

            $model->activities()->create([
                'user_id' => Auth::id(),
                'action' => ActivityAction::ADD,
                'data_changes' => $changes
            ]);
        });

        static::updated(function ($model) {
            $changes = collect($model->getChanges())
                ->except(['created_at', 'updated_at', 'deleted_at'])
                ->mapWithKeys(function ($newValue, $field) use ($model) {
                    return [$field => [
                        'old' => $model->getOriginal($field),
                        'new' => $newValue
                    ]];
                })
                ->toArray();

            if (!empty($changes)) {
                $model->activities()->create([
                    'user_id' => Auth::id(),
                    'action' => ActivityAction::UPDATE,
                    'data_changes' => $changes
                ]);
            }
        });

        static::deleted(function ($model) {
            $original = collect($model->getOriginal())
                ->except(['created_at', 'updated_at', 'deleted_at'])
                ->mapWithKeys(function ($value, $field) {
                    return [$field => [
                        'old' => $value,
                        'new' => null
                    ]];
                })
                ->toArray();

            $model->activities()->create([
                'user_id' => Auth::id(),
                'action' => ActivityAction::DELETE,
                'data_changes' => $original
            ]);
        });

        static::restored(function ($model) {
            $changes = collect($model->getAttributes())
                ->except(['created_at', 'updated_at', 'deleted_at'])
                ->mapWithKeys(function ($value, $field) {
                    return [$field => [
                        'old' => null,
                        'new' => $value
                    ]];
                })
                ->toArray();

            $model->activities()->create([
                'user_id' => Auth::id(),
                'action' => ActivityAction::RESTORE,
                'data_changes' => $changes
            ]);
        });

        static::forceDeleted(function ($model) {
            $original = collect($model->getOriginal())
                ->except(['created_at', 'updated_at', 'deleted_at'])
                ->mapWithKeys(function ($value, $field) {
                    return [$field => [
                        'old' => $value,
                        'new' => null
                    ]];
                })
                ->toArray();

            $model->activities()->create([
                'user_id' => Auth::id(),
                'action' => ActivityAction::DELETE,
                'data_changes' => $original
            ]);
        });
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'model');
    }
}
