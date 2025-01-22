<?php

namespace App\Services\Api;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    public function store(?UploadedFile $image, string $imageStorageFolder, mixed $uniqueId): ?string
    {
        if (! $image) {
            return null;
        }

        try {
            // Get original name without extension
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

            // Create safe string from original name
            $safeFileName = Str::slug($originalName);

            // Combine with unique ID and extension
            $fileName = $uniqueId.'_'.$safeFileName.'.'.$image->getClientOriginalExtension();

            // Store the image in the public disk under profile-images directory
            return $image->storeAs(
                $imageStorageFolder,
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
