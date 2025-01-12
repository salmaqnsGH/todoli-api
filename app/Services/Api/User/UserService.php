<?php

namespace App\Services\Api\User;

use App\Http\Requests\Api\User\UpdatePasswordRequest;
use App\Http\Requests\Api\User\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserService
{
    private function getActiveQuery()
    {
        return User::whereNull('deleted_at');
    }

    public function getByEmail(string $email)
    {
        return $this->getActiveQuery()
            ->where('email', $email)
            ->firstOrFail();
    }

    public function isEmailVerified(User $user)
    {
        return is_not_null($user->email_verified_at);
    }

    public function getProfile(Request $request)
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
