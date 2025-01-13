<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Task\AssignTaskUserRequest;
use App\Http\Requests\Api\Task\CreateTaskRequest;
use App\Http\Requests\Api\Task\SetTaskPriorityRequest;
use App\Http\Requests\Api\Task\SetTaskStatusRequest;
use App\Http\Requests\Api\Task\UpdateTaskRequest;
use App\Http\Requests\AppRequest;
use App\Services\Api\Task\TaskService;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService,
    ) {}

    public function getPaginatedList(GetPaginatedListRequest $request)
    {
        $result = $this->taskService->getPaginatedList($request);

        return jsonresSuccess($request, 'Success get list data', $result);
    }

    public function getDetail(AppRequest $request)
    {
        $result = $this->taskService->getDetail($request);

        return jsonresSuccess($request, 'Success get data', $result);
    }

    public function create(CreateTaskRequest $request)
    {
        $result = $this->taskService->create($request);

        return jsonresCreated($request, 'Success create data', $result);
    }

    public function update(UpdateTaskRequest $request)
    {
        $result = $this->taskService->update($request);

        return jsonresSuccess($request, 'Success update data', $result);
    }

    public function softDelete(AppRequest $request)
    {
        $result = $this->taskService->softDelete($request);

        return jsonresSuccess($request, 'Data is deleted', $result);
    }

    public function assignUser(AssignTaskUserRequest $request)
    {
        $result = $this->taskService->assignUser($request);

        return jsonresSuccess($request, 'Task user is assigned', $result);
    }

    public function setStatus(SetTaskStatusRequest $request)
    {
        $result = $this->taskService->setStatus($request);

        return jsonresSuccess($request, 'Task status is set', $result);
    }

    public function setPriority(SetTaskPriorityRequest $request)
    {
        $result = $this->taskService->setPriority($request);

        return jsonresSuccess($request, 'Task priority is set', $result);
    }
}
