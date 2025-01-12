<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UpdatePasswordRequest;
use App\Http\Requests\Api\User\UpdateProfileRequest;
use App\Services\Api\User\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
    ) {}

    public function getProfile(Request $request)
    {
        // TODO implement this
        $result = $this->userService->getProfile($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        // TODO implement this
        $result = $this->userService->updateProfile($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        // TODO implement this
        $result = $this->userService->updatePassword($request);

        return jsonresSuccess($request, 'OK', []);
    }
}
