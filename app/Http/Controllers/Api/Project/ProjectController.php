<?php

namespace App\Http\Controllers\Api\Project;

use App\Constants\ImageStorageFolder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Project\CreateProjectRequest;
use App\Http\Requests\Api\Project\UpdateProjectRequest;
use App\Http\Requests\AppRequest;
use App\Services\Api\ImageService;
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
        protected ImageService $imageService,
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

            if ($request->hasFile('image')) {
                $projectImagePath = $this->imageService->store($request->file('image'), ImageStorageFolder::PROJECT, $project->id);
                $project->image = $projectImagePath;
                $project->save();
            }

            $project['all_members'] = $members;

            DB::commit();

            return jsonresCreated($request, 'Success create data', $project);
        } catch (\Exception $e) {
            if ($projectImagePath) {
                $this->imageService->delete($projectImagePath);
            }
            DB::rollback();
            throw $e;
        }
    }

    public function update(UpdateProjectRequest $request)
    {
        $projectImagePath = null; // Initialize for potential cleanup
        $oldProjectImagePath = null; // Store old image path for cleanup

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

            if ($request->hasFile('image')) {
                // Store old image path for deletion
                $oldProjectImagePath = $project->image;

                $projectImagePath = $this->imageService->store(
                    $request->file('image'),
                    ImageStorageFolder::PROJECT,
                    $project->id
                );
                $project->image = $projectImagePath;
                $project->save();

                // Don't need to remove old image if filename is exactly the same
                if ($oldProjectImagePath == $projectImagePath) {
                    $oldProjectImagePath = null;
                }
            }

            $project['members'] = $members;

            DB::commit();

            return jsonresSuccess($request, 'Success update data', $project);
        } catch (\Exception $e) {
            if ($projectImagePath) {
                $this->imageService->delete($projectImagePath);
            }
            DB::rollback();
            throw $e;
        } finally {
            if ($oldProjectImagePath) {
                $this->imageService->delete($oldProjectImagePath);
            }
        }
    }

    public function softDelete(AppRequest $request)
    {
        $result = $this->projectService->softDelete($request);

        return jsonresSuccess($request, 'Data is deleted', $result);
    }
}
