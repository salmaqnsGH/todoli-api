<?php

namespace App\Http\Controllers\Api\Auth;

use App\Constants\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Services\Api\Auth\LoginService;
use App\Services\Api\Auth\LogoutService;
use App\Services\Api\Auth\RegisterService;
use App\Services\Api\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function __construct(
        protected RegisterService $registerService,
        protected LoginService $loginService,
        protected LogoutService $logoutService,
        protected UserService $userService,
    ) {}

    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = $this->registerService->createUser($request->validated());

            $this->registerService->assignRole($user, UserRole::USER_MEMBER);

            // TODO implement saving image profile

            $responseData = [
                'user' => $user,
            ];

            DB::commit();

            return jsonresCreated($request, 'Success sign up', $responseData);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function login(LoginRequest $request)
    {
        if (is_not($this->loginService->isValidCredential($request))) {
            return jsonresUnauthorized($request, 'Incorrect email or password');
        }

        $user = $this->userService->getByEmail($request->email);
        if (is_not($this->userService->isEmailVerified($user))) {
            return jsonresUnauthorized($request, 'Account is not verified');
        }

        $token = $this->loginService->createToken($user);

        $responseData = [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];

        return jsonresSuccess($request, 'Success sign in', $responseData);
    }

    public function logout(Request $request)
    {
        $this->logoutService->deleteAccessToken($request);

        $this->logoutService->pruneUserExpiredTokens($request);

        return jsonresSuccess($request, 'Success sign out');
    }
}
