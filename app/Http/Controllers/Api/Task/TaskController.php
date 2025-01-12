<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Task\AssignTaskUserRequest;
use App\Http\Requests\Api\Task\CreateTaskRequest;
use App\Http\Requests\Api\Task\SetTaskPriorityRequest;
use App\Http\Requests\Api\Task\SetTaskStatusRequest;
use App\Http\Requests\Api\Task\UpdateTaskRequest;
use App\Services\Api\Task\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService,
    ) {}

    public function getPaginatedList(GetPaginatedListRequest $request)
    {
        // TODO implement this
        $result = $this->taskService->getPaginatedList($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function getDetail(Request $request)
    {
        // TODO implement this
        $result = $this->taskService->getDetail($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function create(CreateTaskRequest $request)
    {
        // TODO implement this
        $result = $this->taskService->create($request);

        return jsonresCreated($request, 'OK', []);
    }

    public function update(UpdateTaskRequest $request)
    {
        // TODO implement this
        $result = $this->taskService->update($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function softDelete(Request $request)
    {
        // TODO implement this
        $result = $this->taskService->softDelete($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function assignUser(AssignTaskUserRequest $request)
    {
        // TODO implement this
        $result = $this->taskService->assignUser($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function setStatus(SetTaskStatusRequest $request)
    {
        // TODO implement this
        $result = $this->taskService->setStatus($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function setPriority(SetTaskPriorityRequest $request)
    {
        // TODO implement this
        $result = $this->taskService->setPriority($request);

        return jsonresSuccess($request, 'OK', []);
    }
}
