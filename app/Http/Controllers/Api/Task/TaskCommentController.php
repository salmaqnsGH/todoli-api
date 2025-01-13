<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Task\CreateTaskCommentRequest;
use App\Http\Requests\Api\Task\UpdateTaskCommentRequest;
use App\Http\Requests\AppRequest;
use App\Services\Api\Task\TaskCommentService;

class TaskCommentController extends Controller
{
    public function __construct(
        protected TaskCommentService $taskCommentService,
    ) {}

    public function getPaginatedList(GetPaginatedListRequest $request)
    {
        $result = $this->taskCommentService->getPaginatedList($request);

        return jsonresSuccess($request, 'Success get list data', $result);
    }

    public function getDetail(AppRequest $request)
    {
        $result = $this->taskCommentService->getDetail($request);

        return jsonresSuccess($request, 'Success get data', $result);
    }

    public function create(CreateTaskCommentRequest $request)
    {
        $result = $this->taskCommentService->create($request);

        return jsonresCreated($request, 'Success create data', $result);
    }

    public function update(UpdateTaskCommentRequest $request)
    {
        $result = $this->taskCommentService->update($request);

        return jsonresSuccess($request, 'Success update data', $result);
    }

    public function softDelete(AppRequest $request)
    {
        $result = $this->taskCommentService->softDelete($request);

        return jsonresSuccess($request, 'Data is deleted', $result);
    }
}
