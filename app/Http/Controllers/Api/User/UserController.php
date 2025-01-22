<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UpdatePasswordRequest;
use App\Http\Requests\Api\User\UpdateProfileRequest;
use App\Http\Requests\AppRequest;
use App\Services\Api\User\UserService;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
    ) {}

    public function getProfile(AppRequest $request)
    {
        $result = $this->userService->getProfile();

        return jsonresSuccess($request, 'Success get data', $result);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $this->userService->updateProfile($request);

        return jsonresSuccess($request, 'Success update data', $user);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $result = $this->userService->updatePassword($request);

        return jsonresSuccess($request, 'Success update password', $result);
    }
}
