<?php

namespace App\Http\Controllers\Api\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Project\AddProjectMemberRequest;
use App\Http\Requests\AppRequest;
use App\Services\Api\Project\ProjectMemberService;

class ProjectMemberController extends Controller
{
    public function __construct(
        protected ProjectMemberService $projectMemberService,
    ) {}

    public function getMembers(AppRequest $request)
    {
        $projectId = $request->getId();
        $result = $this->projectMemberService->getMembersByProjectId($projectId);

        return jsonresSuccess($request, 'Success get list data', $result);
    }

    public function add(AddProjectMemberRequest $request)
    {
        $result = $this->projectMemberService->add($request);

        return jsonresCreated($request, 'Success create data', $result);
    }

    public function softRemove(AppRequest $request)
    {
        $result = $this->projectMemberService->softRemove($request);

        return jsonresSuccess($request, 'Data is deleted', $result);
    }
}
