<?php

namespace App\Http\Controllers\Api\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Project\AddProjectMemberRequest;
use App\Services\Api\Project\ProjectMemberService;
use Illuminate\Http\Request;

class ProjectMemberController extends Controller
{
    public function __construct(
        protected ProjectMemberService $projectMemberService,
    ) {}

    public function getPaginatedList(GetPaginatedListRequest $request)
    {
        // TODO implement this
        $result = $this->projectMemberService->getPaginatedList($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function add(AddProjectMemberRequest $request)
    {
        // TODO implement this
        $result = $this->projectMemberService->add($request);

        return jsonresCreated($request, 'OK', []);
    }

    public function softRemove(Request $request)
    {
        // TODO implement this
        $result = $this->projectMemberService->softRemove($request);

        return jsonresSuccess($request, 'OK', []);
    }
}
