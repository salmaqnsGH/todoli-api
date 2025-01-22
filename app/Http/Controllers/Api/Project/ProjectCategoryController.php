<?php

namespace App\Http\Controllers\Api\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Project\CreateProjectCategoryRequest;
use App\Http\Requests\Api\Project\UpdateProjectCategoryRequest;
use App\Http\Requests\AppRequest;
use App\Services\Api\Project\ProjectCategoryService;

class ProjectCategoryController extends Controller
{
    public function __construct(
        protected ProjectCategoryService $projectCategoryService,
    ) {}

    public function getPaginatedList(GetPaginatedListRequest $request)
    {
        $result = $this->projectCategoryService->getPaginatedList($request);

        return jsonresSuccess($request, 'Success get list data', $result);
    }

    public function getDetail(AppRequest $request)
    {
        $result = $this->projectCategoryService->getDetail($request);

        return jsonresSuccess($request, 'Success get data', $result);
    }

    public function create(CreateProjectCategoryRequest $request)
    {
        $result = $this->projectCategoryService->create($request);

        return jsonresCreated($request, 'Success create data', $result);
    }

    public function update(UpdateProjectCategoryRequest $request)
    {
        $result = $this->projectCategoryService->update($request);

        return jsonresSuccess($request, 'Success update data', $result);
    }

    public function softDelete(AppRequest $request)
    {
        $result = $this->projectCategoryService->softDelete($request);

        return jsonresSuccess($request, 'Data is deleted', $result);
    }
}
