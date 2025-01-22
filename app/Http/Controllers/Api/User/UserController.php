<?php

namespace App\Http\Controllers\Api\User;

use App\Constants\ImageStorageFolder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UpdatePasswordRequest;
use App\Http\Requests\Api\User\UpdateProfileRequest;
use App\Http\Requests\AppRequest;
use App\Services\Api\ImageService;
use App\Services\Api\User\UserService;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected ImageService $imageService,
    ) {}

    public function getProfile(AppRequest $request)
    {
        $result = $this->userService->getProfile();

        return jsonresSuccess($request, 'Success get data', $result);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $profileImagePath = null; // Initialize for potential cleanup
        $oldProfileImagePath = null; // Store old image path for cleanup

        try {
            DB::beginTransaction();
            $user = $this->userService->updateProfile($request);

            if ($request->hasFile('image')) {
                // Store old image path for deletion
                $oldProfileImagePath = $user->image;

                $profileImagePath = $this->imageService->store($request->file('image'), ImageStorageFolder::USER, $user->id);
                $user->image = $profileImagePath;
                $user->save();

                // Don't need to remove old image if filename is exactly the same
                if ($oldProfileImagePath == $profileImagePath) {
                    $oldProfileImagePath = null;
                }
            }

            DB::commit();

            return jsonresSuccess($request, 'Success update data', $user);
        } catch (\Exception $e) {
            if ($profileImagePath) {
                $this->imageService->delete($profileImagePath);
            }
            DB::rollback();
            throw $e;
        } finally {
            if ($oldProfileImagePath) {
                $this->imageService->delete($oldProfileImagePath);
            }
        }
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $result = $this->userService->updatePassword($request);

        return jsonresSuccess($request, 'Success update password', $result);
    }
}
