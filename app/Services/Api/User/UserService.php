<?php

namespace App\Services\Api\User;

use App\Http\Requests\Api\User\UpdatePasswordRequest;
use App\Http\Requests\Api\User\UpdateProfileRequest;
use App\Http\Requests\AppRequest;
use App\Models\User;

class UserService
{
    public function getById(AppRequest $request)
    {
        return User::findOrFail($request->getId());
    }

    public function getByEmail(string $email)
    {
        return User::where('email', $email)
            ->firstOrFail();
    }

    public function isEmailVerified(User $user)
    {
        return is_not_null($user->email_verified_at);
    }

    public function getProfile(AppRequest $request)
    {
        return null;
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        return null;
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        return null;
    }
}
