<?php

namespace App\Http\Controllers\Api\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Project\CreateProjectRequest;
use App\Http\Requests\Api\Project\UpdateProjectRequest;
use App\Http\Requests\AppRequest;
use App\Services\Api\Project\ProjectMemberService;
use App\Services\Api\Project\ProjectPermissionService;
use App\Services\Api\Project\ProjectService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $projectService,
        protected ProjectMemberService $projectMemberService,
        protected ProjectPermissionService $projectPermissionService,
    ) {}

    public function getPaginatedList(GetPaginatedListRequest $request)
    {
        $result = $this->projectService->getPaginatedList($request);

        return jsonresSuccess($request, 'Success get list data', $result);
    }

    public function getDetail(AppRequest $request)
    {
        $result = $this->projectService->getDetail($request);

        return jsonresSuccess($request, 'Success get data', $result);
    }

    public function create(CreateProjectRequest $request)
    {
        try {
            DB::beginTransaction();

            $project = $this->projectService->create($request);

            $currentUserId = Auth::id();
            $projectId = $project->id;
            $validatedRequest = $request->validated();
            $members = collect([
                $this->projectMemberService->addOwner($projectId, $currentUserId),
            ])->merge(
                $this->projectMemberService->addMultipleMembers($projectId, $validatedRequest['user_ids'])
            );
            $this->projectPermissionService->addDefaultRoleOwnerPermissions($projectId, $currentUserId);

            $responseData = [
                'project' => $project,
                'members' => $members,
            ];

            DB::commit();

            return jsonresCreated($request, 'Success create data', $responseData);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(UpdateProjectRequest $request)
    {
        try {
            DB::beginTransaction();

            $project = $this->projectService->update($request);

            $projectId = $project->id;
            $validatedRequest = $request->validated();
            $requestUserIds = $validatedRequest['user_ids'];
            if (isset($requestUserIds) && is_not_empty_col($requestUserIds)) {
                $members = $this->projectMemberService->updateMultipleMembers($projectId, $requestUserIds);
            } else {
                $members = $this->projectMemberService->getMembersByProjectId($projectId);
            }

            $responseData = [
                'project' => $project,
                'members' => $members,
            ];

            DB::commit();

            return jsonresSuccess($request, 'Success update data', $responseData);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function softDelete(AppRequest $request)
    {
        $result = $this->projectService->softDelete($request);

        return jsonresSuccess($request, 'Data is deleted', $result);
    }
}
