<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Task\CreateTaskPriorityRequest;
use App\Http\Requests\Api\Task\UpdateTaskPriorityRequest;
use App\Http\Requests\AppRequest;
use App\Services\Api\Task\TaskPriorityService;

class TaskPriorityController extends Controller
{
    public function __construct(
        protected TaskPriorityService $taskPriorityService,
    ) {}

    public function getPaginatedList(GetPaginatedListRequest $request)
    {
        $result = $this->taskPriorityService->getPaginatedList($request);

        return jsonresSuccess($request, 'Success get list data', $result);
    }

    public function getDetail(AppRequest $request)
    {
        $result = $this->taskPriorityService->getDetail($request);

        return jsonresSuccess($request, 'Success get data', $result);
    }

    public function create(CreateTaskPriorityRequest $request)
    {
        $result = $this->taskPriorityService->create($request);

        return jsonresCreated($request, 'Success create data', $result);
    }

    public function update(UpdateTaskPriorityRequest $request)
    {
        $result = $this->taskPriorityService->update($request);

        return jsonresSuccess($request, 'Success update data', $result);
    }

    public function softDelete(AppRequest $request)
    {
        $result = $this->taskPriorityService->softDelete($request);

        return jsonresSuccess($request, 'Data is deleted', $result);
    }
}
