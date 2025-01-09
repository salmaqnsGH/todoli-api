<?php

namespace App\Services\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginService
{
    public function isValidCredential(Request $request)
    {
        return Auth::attempt($request->only('email', 'password'));
    }

    public function createToken(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }
}
