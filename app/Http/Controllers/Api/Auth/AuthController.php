<?php

namespace App\Http\Controllers\Api\Auth;

use App\Constants\ImageStorageFolder;
use App\Constants\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ForgotPasswordRequest;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\ResendVerificationEmailRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Http\Requests\AppRequest;
use App\Services\Api\Auth\ForgotAccountService;
use App\Services\Api\Auth\LoginService;
use App\Services\Api\Auth\LogoutService;
use App\Services\Api\Auth\RegisterService;
use App\Services\Api\ImageService;
use App\Services\Api\Organization\OrganizationService;
use App\Services\Api\User\UserService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct(
        protected RegisterService $registerService,
        protected LoginService $loginService,
        protected LogoutService $logoutService,
        protected ForgotAccountService $forgotAccountService,
        protected UserService $userService,
        protected ImageService $imageService,
        protected OrganizationService $organizationService,
    ) {}

    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();

            $validatedRequest = $request->validated();
            $organizationNameRequest = $validatedRequest['organization_name'];

            $organization = $this->organizationService->getByNameWithoutFail($organizationNameRequest);
            if (empty($organization)) {
                $data = ['name' => $organizationNameRequest];
                $organization = $this->organizationService->create(null, $data);
            }

            $validatedRequest['organization_id'] = $organization->id;
            $user = $this->registerService->createUser($validatedRequest);

            $this->registerService->assignRole($user, UserRole::USER_MEMBER);

            if ($request->hasFile('image')) {
                $profileImagePath = $this->imageService->store($request->file('image'), ImageStorageFolder::USER, $user->id);
                $user->image = $profileImagePath;
                $user->save();
            }

            // Send verification email
            $user->sendEmailVerificationNotification();

            $responseData = [
                'user' => $user,
            ];

            DB::commit();

            return jsonresCreated($request, 'Success sign up', $responseData);
        } catch (\Exception $e) {
            if ($profileImagePath) {
                $this->imageService->delete($profileImagePath);
            }
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

    public function logout(AppRequest $request)
    {
        $this->logoutService->deleteAccessToken($request);

        $this->logoutService->pruneUserExpiredTokens($request);

        return jsonresSuccess($request, 'Success sign out');
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return jsonresSuccess($request, 'An email is sent');
        }

        return jsonresServerError($request, 'Internal server error');
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return jsonresSuccess($request, 'Password successfully updated');
        }

        return jsonresServerError($request, 'Internal server error');
    }

    /**
     * Handle the email verification request
     */
    public function verifyAccount(AppRequest $request)
    {
        $user = $this->userService->getById($request);

        if (! hash_equals(
            (string) $request->route('hash'),
            sha1($user->getEmailForVerification())
        )) {
            return jsonresBadRequest($request, 'Invalid verification link');
        }

        if ($user->hasVerifiedEmail()) {
            return jsonresSuccess($request, 'Email already verified');
        }

        $user->markEmailAsVerified();

        event(new Verified($user));

        return jsonresSuccess($request, 'Email verified successfully');
    }

    /**
     * Resend verification email
     */
    public function resendEmailVerificationAccount(ResendVerificationEmailRequest $request)
    {
        $user = $this->userService->getByEmail($request->email);

        if ($user->hasVerifiedEmail()) {
            return jsonresSuccess($request, 'Email already verified');
        }

        $user->sendEmailVerificationNotification();

        return jsonresSuccess($request, 'Verification link sent');
    }
}
