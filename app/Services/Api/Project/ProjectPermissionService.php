<?php

namespace App\Services\Api\Project;

use App\Constants\Permission;
use App\Http\Requests\Api\Project\AddUserProjectPermissionRequest;
use App\Http\Requests\Api\Project\UpdateUserProjectPermissionRequest;
use App\Http\Requests\AppRequest;
use App\Models\ProjectPermission;
use App\Models\User;

class ProjectPermissionService
{
    public function getUserProjectPermissions(AppRequest $request)
    {
        $projectId = $request->getId();
        $userId = $request->getProjectUserId();

        $permissions = ProjectPermission::with('permission')
            ->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->get()
            ->pluck('permission');

        // Find default permissions in model_has_permissions table if $permissions is empty
        if (is_empty_col($permissions)) {
            $user = User::find($userId);
            $permissions = $user->roles->map(function ($role) {
                return $role->permissions;
            })->flatten()->unique('id');
        }

        return $permissions;
    }

    public function updateUserProjectPermissions(UpdateUserProjectPermissionRequest $request)
    {
        return null;
    }

    public function add(?AddUserProjectPermissionRequest $request = null, ?array $data = null)
    {
        if (is_not_null($data)) {
            return ProjectPermission::create($data);
        }

        return ProjectPermission::create($request->validated());
    }

    public function addDefaultRoleOwnerPermissions(int $projectId, int $userId)
    {
        $permissions = [];
        $defaultPermissions = Permission::getDefaultUserOwnerPermissions();

        foreach ($defaultPermissions as $key => $permissionId) {
            $permissions[] = $this->add(null, [
                'project_id' => $projectId,
                'user_id' => $userId,
                'permission_id' => $permissionId,
            ]);
        }

        return $permissions;
    }
}
