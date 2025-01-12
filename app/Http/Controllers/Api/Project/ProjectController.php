<?php

namespace App\Http\Controllers\Api\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Project\CreateProjectRequest;
use App\Http\Requests\Api\Project\UpdateProjectRequest;
use App\Services\Api\Project\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $projectService,
    ) {}

    public function getPaginatedList(GetPaginatedListRequest $request)
    {
        // TODO implement this
        $result = $this->projectService->getPaginatedList($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function getDetail(Request $request)
    {
        // TODO implement this
        $result = $this->projectService->getDetail($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function create(CreateProjectRequest $request)
    {
        // TODO implement this
        $result = $this->projectService->create($request);

        return jsonresCreated($request, 'OK', []);
    }

    public function update(UpdateProjectRequest $request)
    {
        // TODO implement this
        $result = $this->projectService->update($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function softDelete(Request $request)
    {
        // TODO implement this
        $result = $this->projectService->softDelete($request);

        return jsonresSuccess($request, 'OK', []);
    }
}
