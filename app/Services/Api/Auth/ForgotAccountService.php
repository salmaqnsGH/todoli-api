<?php

namespace App\Services\Api\Auth;

use App\Http\Requests\Api\Auth\ForgotAccountRequest;

class ForgotAccountService
{
    public function findByEmail(ForgotAccountRequest $request)
    {
        return null;
    }

    public function sendEmailForUpdatePassword() {}
}
