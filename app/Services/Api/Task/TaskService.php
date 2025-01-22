<?php

namespace App\Services\Api\Task;

use App\Constants\Permission;
use App\Constants\Value;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Task\AssignTaskUserRequest;
use App\Http\Requests\Api\Task\CreateTaskRequest;
use App\Http\Requests\Api\Task\SetTaskPriorityRequest;
use App\Http\Requests\Api\Task\SetTaskStatusRequest;
use App\Http\Requests\Api\Task\UpdateTaskRequest;
use App\Http\Requests\AppRequest;
use App\Models\Task;
use App\Services\PaginationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class TaskService extends PaginationService
{
    protected function getBaseQuery(): Builder
    {
        /** @var \Illuminate\Contracts\Auth\Access\Authorizable */
        $currentUser = Auth::user();

        return Task::with(['project', 'priority', 'status', 'images'])
            ->whereHas('project.all_members', function ($query) use ($currentUser) {
                $query->where('user_id', $currentUser->id);
            })
            ->without('project');  // This removes the relationship from loading;
    }

    protected function getPaginationBaseQuery(GetPaginatedListRequest $request): Builder
    {
        $projectId = $request->getProjectId();

        return $this->getBaseQuery()
            ->where('project_id', $projectId)
            ->select('id', 'project_id', 'user_id', 'status_id', 'priority_id', 'name', 'objective', 'description', 'additional_notes', 'due_date');
    }

    protected function getPaginationAllowedSortFields(): array
    {
        return ['id', 'name', 'due_date', 'status_id', 'priority_id', 'created_at', 'updated_at'];
    }

    protected function applyPaginationSearch(Builder $query, string $search): void
    {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('objective', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%")
                ->orWhere('additional_notes', 'LIKE', "%{$search}%")
                ->orWhere('due_date', 'LIKE', "%{$search}%");
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
                case 'priority_id':
                    $query->where('priority_id', $value);
                    break;
                case 'status_id':
                    $query->where('status_id', $value);
                    break;
                case 'due_date':
                    $query->where('due_date', $value);
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
        return $this->getBaseQuery()
            ->where('project_id', $request->getProjectId())
            ->where('id', $request->getTaskId())
            ->firstOrFail();
    }

    public function create(CreateTaskRequest $request)
    {
        $validatedRequest = $request->validated();
        $data = $validatedRequest;
        $projectId = $request->getProjectId();
        $data['project_id'] = $projectId;

        return Task::create($data);
    }

    public function update(UpdateTaskRequest $request)
    {
        $task = $this->getBaseQuery()
            ->where('project_id', $request->getProjectId())
            ->where('id', $request->getTaskId())
            ->firstOrFail();

        $task->update($request->validated());

        return $task;
    }

    public function softDelete(AppRequest $request)
    {
        $task = $this->getBaseQuery()
            ->where('project_id', $request->getProjectId())
            ->where('id', $request->getTaskId())
            ->firstOrFail();

        $task->delete();

        return $task;
    }

    public function assignUser(AssignTaskUserRequest $request)
    {
        $validatedRequest = $request->validated();
        $userId = $validatedRequest['user_id'];

        $task = $this->getBaseQuery()
            ->where('project_id', $request->getProjectId())
            ->where('id', $request->getTaskId())
            ->firstOrFail();

        $members = $task->project->all_members->pluck('id')->toArray();
        if (is_not(in_array($userId, $members))) {
            $response = jsonresNotFound($request, 'User not found');

            throw new HttpResponseException($response);
        }

        $task->update(['user_id' => $userId]);

        return $task;
    }

    public function setStatus(SetTaskStatusRequest $request)
    {
        $validatedRequest = $request->validated();
        $statusId = $validatedRequest['status_id'];

        $task = $this->getBaseQuery()
            ->where('project_id', $request->getProjectId())
            ->where('id', $request->getTaskId())
            ->firstOrFail();

        /** @var \Illuminate\Contracts\Auth\Access\Authorizable */
        $currentUser = Auth::user();
        if (is_not($currentUser->can(pn(Permission::TASK_STATUS_SET_OTHER))) && is_not($currentUser->id === $task->user_id)) {
            $response = jsonresForbidden($request, 'Forbidden user is not allowed here');

            throw new HttpResponseException($response);
        }

        $task->update(['status_id' => $statusId]);

        return $task;
    }

    public function setPriority(SetTaskPriorityRequest $request)
    {
        $validatedRequest = $request->validated();
        $priorityId = $validatedRequest['priority_id'];

        $task = $this->getBaseQuery()
            ->where('project_id', $request->getProjectId())
            ->where('id', $request->getTaskId())
            ->firstOrFail();

        $task->update(['priority_id' => $priorityId]);

        return $task;
    }
}
