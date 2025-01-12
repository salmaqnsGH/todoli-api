<?php

namespace App\Services\Api\Task;

use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Task\AssignTaskUserRequest;
use App\Http\Requests\Api\Task\CreateTaskRequest;
use App\Http\Requests\Api\Task\SetTaskPriorityRequest;
use App\Http\Requests\Api\Task\SetTaskStatusRequest;
use App\Http\Requests\Api\Task\UpdateTaskRequest;
use Illuminate\Http\Request;

class TaskService
{
    public function getPaginatedList(GetPaginatedListRequest $request)
    {
        return null;
    }

    public function getDetail(Request $request)
    {
        return null;
    }

    public function create(CreateTaskRequest $request)
    {
        return null;
    }

    public function update(UpdateTaskRequest $request)
    {
        return null;
    }

    public function softDelete(Request $request)
    {
        return null;
    }

    public function assignUser(AssignTaskUserRequest $request)
    {
        return null;
    }

    public function setStatus(SetTaskStatusRequest $request)
    {
        return null;
    }

    public function setPriority(SetTaskPriorityRequest $request)
    {
        return null;
    }
}
