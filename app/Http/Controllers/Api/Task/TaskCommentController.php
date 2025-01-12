<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Task\CreateTaskCommentRequest;
use App\Http\Requests\Api\Task\UpdateTaskCommentRequest;
use App\Services\Api\Task\TaskCommentService;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
    public function __construct(
        protected TaskCommentService $taskCommentService,
    ) {}

    public function getPaginatedList(GetPaginatedListRequest $request)
    {
        // TODO implement this
        $result = $this->taskCommentService->getPaginatedList($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function getDetail(Request $request)
    {
        // TODO implement this
        $result = $this->taskCommentService->getDetail($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function create(CreateTaskCommentRequest $request)
    {
        // TODO implement this
        $result = $this->taskCommentService->create($request);

        return jsonresCreated($request, 'OK', []);
    }

    public function update(UpdateTaskCommentRequest $request)
    {
        // TODO implement this
        $result = $this->taskCommentService->update($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function softDelete(Request $request)
    {
        // TODO implement this
        $result = $this->taskCommentService->softDelete($request);

        return jsonresSuccess($request, 'OK', []);
    }
}
