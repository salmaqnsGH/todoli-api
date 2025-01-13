<?php

namespace App\Services\Api\Task;

use App\Constants\Value;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Task\CreateTaskCommentRequest;
use App\Http\Requests\Api\Task\UpdateTaskCommentRequest;
use App\Http\Requests\AppRequest;
use App\Models\TaskComment;
use App\Services\PaginationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TaskCommentService extends PaginationService
{
    protected function getPaginationBaseQuery(GetPaginatedListRequest $request): Builder
    {
        return TaskComment::select('id', 'content');
    }

    protected function getPaginationAllowedSortFields(): array
    {
        return ['id', 'created_at', 'updated_at'];
    }

    protected function applyPaginationSearch(Builder $query, string $search): void
    {
        $query->where(function ($q) use ($search) {
            $q->where('content', 'LIKE', "%{$search}%");
        });
    }

    protected function applyPaginationFilters(Builder $query, array $parsedFilters): void
    {
        foreach ($parsedFilters as $field => $value) {
            if (strtolower($value) === Value::ALL) {
                continue;
            }

            switch ($field) {
                case 'id':
                    $query->where('id', $value);
                    break;
            }
        }
    }

    protected function applyPaginationSorting(Builder $query, string $sortField, string $sortDirection): void
    {
        $query->orderBy($sortField, $sortDirection);
    }

    public function getDetail(AppRequest $request)
    {
        return TaskComment::where('task_id', $request->getTaskId())
            ->where('id', $request->getTaskCommentId())
            ->firstOrFail();
    }

    public function create(CreateTaskCommentRequest $request)
    {
        $validatedRequest = $request->validated();
        $data = $validatedRequest;
        $userId = Auth::id();
        $taskId = $request->getTaskId();
        $data['user_id'] = $userId;
        $data['task_id'] = $taskId;

        return TaskComment::create($data);
    }

    public function update(UpdateTaskCommentRequest $request)
    {
        $taskComment = TaskComment::where('task_id', $request->getTaskId())
            ->where('id', $request->getTaskCommentId())
            ->firstOrFail();

        $taskComment->update($request->validated());

        return $taskComment;
    }

    public function softDelete(AppRequest $request)
    {
        $taskComment = TaskComment::where('task_id', $request->getTaskId())
            ->where('id', $request->getTaskCommentId())
            ->firstOrFail();

        $taskComment->delete();

        return $taskComment;
    }
}
