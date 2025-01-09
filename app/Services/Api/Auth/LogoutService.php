<?php

namespace App\Services\Api\Auth;

use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class LogoutService
{
    public function deleteAccessToken(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function pruneUserExpiredTokens(Request $request): void
    {
        $userId = $request->user()->id;
        $expiration = config('sanctum.expiration');

        if ($expiration) {
            PersonalAccessToken::where('tokenable_id', $userId)
                ->where('created_at', '<', now()->subMinutes($expiration))
                ->delete();
        }
    }

    public function pruneAllExpired(): void
    {
        $expiration = config('sanctum.expiration');

        if ($expiration) {
            PersonalAccessToken::where('created_at', '<', now()
                ->subMinutes($expiration))
                ->delete();
        }
    }
}
