<?php

namespace App\Traits;

use App\Constants\ActivityAction;
use App\Mail\Activity\ActivityLogMail;
use App\Models\Activity;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
                        'new' => $value,
                    ]];
                })
                ->toArray();

            $model->logActivityAndNotify($model, ActivityAction::ADD, $changes);
        });

        static::updated(function ($model) {
            $changes = collect($model->getChanges())
                ->except(['created_at', 'updated_at', 'deleted_at'])
                ->mapWithKeys(function ($newValue, $field) use ($model) {
                    return [$field => [
                        'old' => $model->getOriginal($field),
                        'new' => $newValue,
                    ]];
                })
                ->toArray();

            if (! empty($changes)) {
                $model->logActivityAndNotify($model, ActivityAction::UPDATE, $changes);
            }
        });

        static::deleted(function ($model) {
            $original = collect($model->getOriginal())
                ->except(['created_at', 'updated_at', 'deleted_at'])
                ->mapWithKeys(function ($value, $field) {
                    return [$field => [
                        'old' => $value,
                        'new' => null,
                    ]];
                })
                ->toArray();

            $model->logActivityAndNotify($model, ActivityAction::DELETE, $original);
        });

        static::restored(function ($model) {
            $changes = collect($model->getAttributes())
                ->except(['created_at', 'updated_at', 'deleted_at'])
                ->mapWithKeys(function ($value, $field) {
                    return [$field => [
                        'old' => null,
                        'new' => $value,
                    ]];
                })
                ->toArray();

            $model->logActivityAndNotify($model, ActivityAction::RESTORE, $changes);
        });

        static::forceDeleted(function ($model) {
            $original = collect($model->getOriginal())
                ->except(['created_at', 'updated_at', 'deleted_at'])
                ->mapWithKeys(function ($value, $field) {
                    return [$field => [
                        'old' => $value,
                        'new' => null,
                    ]];
                })
                ->toArray();

            $model->logActivityAndNotify($model, ActivityAction::DELETE, $original);
        });
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'model');
    }

    private function logActivityAndNotify($model, $action, $changes)
    {
        $activity = $model->activities()->create([
            'user_id' => Auth::id(),
            'action' => $action,
            'data_changes' => $changes,
        ]);

        // Send email notification
        try {
            $user = Auth::user();
            $modelName = class_basename($model);

            // TODO change to actual emails TO
            Mail::to('admin@example.com')
                ->queue(new ActivityLogMail(
                    $user,
                    $action,
                    $changes,
                    $modelName,
                    Carbon::now()
                ));
        } catch (\Exception $e) {
            Log::error('Failed to send activity log email: '.$e->getMessage());
        }

        return $activity;
    }
}
