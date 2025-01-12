<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    public function hasPermission(User $user, string $permissionName, ?int $projectId = null): bool
    {
        // First check global permissions through Spatie
        if ($user->hasPermissionTo($permissionName)) {
            return true;
        }

        // If no project context, stop here
        if (! $projectId) {
            return false;
        }

        // Check project-specific permissions
        $permissionId = Permission::where('name', $permissionName)->value('id');

        return $user->project_permissions()
            ->where('project_id', $projectId)
            ->where('permission_id', $permissionId)
            ->whereNull('deleted_at')
            ->exists();
    }
}
