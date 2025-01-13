<?php

namespace App\Services\Api\Task;

use App\Constants\Value;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Task\CreateTaskStatusRequest;
use App\Http\Requests\Api\Task\UpdateTaskStatusRequest;
use App\Http\Requests\AppRequest;
use App\Models\TaskStatus;
use App\Services\PaginationService;
use Illuminate\Database\Eloquent\Builder;

class TaskStatusService extends PaginationService
{
    protected function getPaginationBaseQuery(GetPaginatedListRequest $request): Builder
    {
        return TaskStatus::select('id', 'name');
    }

    protected function getPaginationAllowedSortFields(): array
    {
        return ['id', 'name', 'created_at', 'updated_at'];
    }

    protected function applyPaginationSearch(Builder $query, string $search): void
    {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%");
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
        return TaskStatus::findOrFail($request->getId());
    }

    public function create(CreateTaskStatusRequest $request)
    {
        return TaskStatus::create($request->validated());
    }

    public function update(UpdateTaskStatusRequest $request)
    {
        $taskStatus = TaskStatus::findOrFail($request->getId());

        $taskStatus->update($request->validated());

        return $taskStatus;
    }

    public function softDelete(AppRequest $request)
    {
        $taskStatus = TaskStatus::findOrFail($request->getId());

        $taskStatus->delete();

        return $taskStatus;
    }
}
