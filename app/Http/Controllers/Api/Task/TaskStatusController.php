<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Task\CreateTaskStatusRequest;
use App\Http\Requests\Api\Task\UpdateTaskStatusRequest;
use App\Services\Api\Task\TaskStatusService;
use Illuminate\Http\Request;

class TaskStatusController extends Controller
{
    public function __construct(
        protected TaskStatusService $taskStatusService,
    ) {}

    public function getPaginatedList(GetPaginatedListRequest $request)
    {
        // TODO implement this
        $result = $this->taskStatusService->getPaginatedList($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function getDetail(Request $request)
    {
        // TODO implement this
        $result = $this->taskStatusService->getDetail($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function create(CreateTaskStatusRequest $request)
    {
        // TODO implement this
        $result = $this->taskStatusService->create($request);

        return jsonresCreated($request, 'OK', []);
    }

    public function update(UpdateTaskStatusRequest $request)
    {
        // TODO implement this
        $result = $this->taskStatusService->update($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function softDelete(Request $request)
    {
        // TODO implement this
        $result = $this->taskStatusService->softDelete($request);

        return jsonresSuccess($request, 'OK', []);
    }
}
