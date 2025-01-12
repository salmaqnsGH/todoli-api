<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Task\CreateTaskPriorityRequest;
use App\Http\Requests\Api\Task\UpdateTaskPriorityRequest;
use App\Services\Api\Task\TaskPriorityService;
use Illuminate\Http\Request;

class TaskPriorityController extends Controller
{
    public function __construct(
        protected TaskPriorityService $taskPriorityService,
    ) {}

    public function getPaginatedList(GetPaginatedListRequest $request)
    {
        // TODO implement this
        $result = $this->taskPriorityService->getPaginatedList($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function getDetail(Request $request)
    {
        // TODO implement this
        $result = $this->taskPriorityService->getDetail($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function create(CreateTaskPriorityRequest $request)
    {
        // TODO implement this
        $result = $this->taskPriorityService->create($request);

        return jsonresCreated($request, 'OK', []);
    }

    public function update(UpdateTaskPriorityRequest $request)
    {
        // TODO implement this
        $result = $this->taskPriorityService->update($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function softDelete(Request $request)
    {
        // TODO implement this
        $result = $this->taskPriorityService->softDelete($request);

        return jsonresSuccess($request, 'OK', []);
    }
}
