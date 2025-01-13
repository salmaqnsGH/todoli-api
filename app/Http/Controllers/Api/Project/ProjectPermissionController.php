<?php

namespace App\Http\Controllers\Api\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Project\UpdateUserProjectPermissionRequest;
use App\Http\Requests\AppRequest;
use App\Services\Api\Project\ProjectPermissionService;

class ProjectPermissionController extends Controller
{
    public function __construct(
        protected ProjectPermissionService $projectPermissionService,
    ) {}

    public function getUserProjectPermissions(AppRequest $request)
    {
        $result = $this->projectPermissionService->getUserProjectPermissions($request);

        return jsonresSuccess($request, 'Success get list data', $result);
    }

    public function updateUserProjectPermissions(UpdateUserProjectPermissionRequest $request)
    {
        // TODO implement this
        $result = $this->projectPermissionService->updateUserProjectPermissions($request);

        return jsonresSuccess($request, 'OK', []);
    }
}
