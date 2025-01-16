<?php

namespace App\Services\Api\User;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserProfileImageService
{
    public function store(?UploadedFile $image): ?string
    {
        if (!$image) {
            return null;
        }

        try {
            // Generate a unique name for the image
            $fileName = Str::uuid() . '.' . $image->getClientOriginalExtension();

            // Store the image in the public disk under profile-images directory
            return $image->storeAs(
                'users/profile-images',
                $fileName,
                'public'
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            try {
                Storage::disk('public')->delete($path);
            } catch (\Exception $e) {
                throw $e;
            }
        }
    }
}
