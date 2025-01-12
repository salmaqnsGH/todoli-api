<?php

namespace App\Services\Api\Task;

use App\Constants\Value;
use App\Http\Requests\Api\Task\CreateTaskPriorityRequest;
use App\Http\Requests\Api\Task\UpdateTaskPriorityRequest;
use App\Http\Requests\AppRequest;
use App\Models\TaskPriority;
use App\Services\PaginationService;
use Illuminate\Database\Eloquent\Builder;

class TaskPriorityService extends PaginationService
{
    protected function getPaginationBaseQuery(): Builder
    {
        return TaskPriority::select('id', 'name');
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
        return TaskPriority::findOrFail($request->getId());
    }

    public function create(CreateTaskPriorityRequest $request)
    {
        return TaskPriority::create($request->validated());
    }

    public function update(UpdateTaskPriorityRequest $request)
    {
        $taskPriority = TaskPriority::findOrFail($request->getId());

        $taskPriority->update($request->validated());

        return $taskPriority;
    }

    public function softDelete(AppRequest $request)
    {
        $taskPriority = TaskPriority::findOrFail($request->getId());

        $taskPriority->delete();

        return $taskPriority;
    }
}
