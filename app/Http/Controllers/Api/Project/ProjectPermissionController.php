<?php

namespace App\Http\Controllers\Api\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Project\UpdateUserProjectPermissionRequest;
use App\Services\Api\Project\ProjectPermissionService;
use Illuminate\Http\Request;

class ProjectPermissionController extends Controller
{
    public function __construct(
        protected ProjectPermissionService $projectPermissionService,
    ) {}

    public function getUserProjectPermissions(Request $request)
    {
        // TODO implement this
        $result = $this->projectPermissionService->getUserProjectPermissions($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function updateUserProjectPermissions(UpdateUserProjectPermissionRequest $request)
    {
        // TODO implement this
        $result = $this->projectPermissionService->getUserProjectPermissions($request);

        return jsonresSuccess($request, 'OK', []);
    }
}
