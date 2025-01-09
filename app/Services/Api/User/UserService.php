<?php

namespace App\Services\Api\User;

use App\Models\User;

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
}
