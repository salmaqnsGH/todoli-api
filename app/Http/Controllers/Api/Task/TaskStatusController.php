<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Task\CreateTaskStatusRequest;
use App\Http\Requests\Api\Task\UpdateTaskStatusRequest;
use App\Http\Requests\AppRequest;
use App\Services\Api\Task\TaskStatusService;

class TaskStatusController extends Controller
{
    public function __construct(
        protected TaskStatusService $taskStatusService,
    ) {}

    public function getPaginatedList(GetPaginatedListRequest $request)
    {
        $result = $this->taskStatusService->getPaginatedList($request);

        return jsonresSuccess($request, 'Success get list data', $result);
    }

    public function getDetail(AppRequest $request)
    {
        $result = $this->taskStatusService->getDetail($request);

        return jsonresSuccess($request, 'Success get data', $result);
    }

    public function create(CreateTaskStatusRequest $request)
    {
        $result = $this->taskStatusService->create($request);

        return jsonresCreated($request, 'Success create data', $result);
    }

    public function update(UpdateTaskStatusRequest $request)
    {
        $result = $this->taskStatusService->update($request);

        return jsonresSuccess($request, 'Success create data', $result);
    }

    public function softDelete(AppRequest $request)
    {
        $result = $this->taskStatusService->softDelete($request);

        return jsonresSuccess($request, 'Data is deleted', $result);
    }
}
