<?php

namespace App\Services\Api\User;

use App\Http\Requests\Api\User\UpdatePasswordRequest;
use App\Http\Requests\Api\User\UpdateProfileRequest;
use App\Http\Requests\AppRequest;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function getProfile()
    {
        /** @var \Illuminate\Contracts\Auth\Access\Authorizable */
        $currentUser = Auth::user();

        return User::with('organization')->findOrFail($currentUser->id);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        /** @var \Illuminate\Contracts\Auth\Access\Authorizable */
        $currentUser = Auth::user();

        $user = User::findOrFail($currentUser->id);

        $user->update($request->validated());

        return $user;
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        /** @var \Illuminate\Contracts\Auth\Access\Authorizable */
        $currentUser = Auth::user();

        // Check if current password matches
        if (! Hash::check($request->current_password, $currentUser->password)) {
            $response = jsonresBadRequest($request, 'The provided current password is incorrect');

            throw new HttpResponseException($response);
        }

        // Update password
        $currentUser->update([
            'password' => Hash::make($request->new_password),
        ]);

        return $currentUser;
    }
}
