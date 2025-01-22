<?php

namespace App\Services\Api\Project;

use App\Constants\Permission;
use App\Constants\Value;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Project\CreateProjectRequest;
use App\Http\Requests\Api\Project\UpdateProjectRequest;
use App\Http\Requests\AppRequest;
use App\Models\Project;
use App\Services\PaginationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ProjectService extends PaginationService
{
    protected function getBaseQuery(): Builder
    {
        /** @var \Illuminate\Contracts\Auth\Access\Authorizable */
        $currentUser = Auth::user();
        if ($currentUser->can(pn(Permission::PROJECT_VIEW))) {
            return Project::query();
        }

        return Project::with(['category', 'all_members'])
            ->whereHas('all_members', function ($query) use ($currentUser) {
                $query->where('user_id', $currentUser->id);
            })
            ->without('all_members');  // This removes the relationship from loading;
    }

    protected function getPaginationBaseQuery(GetPaginatedListRequest $request): Builder
    {
        return $this->getBaseQuery()->with('owner')
            ->select('id', 'category_id', 'user_id', 'name', 'description', 'image', 'created_at');
    }

    protected function getPaginationAllowedSortFields(): array
    {
        return ['id', 'category_id', 'user_id', 'name', 'created_at', 'updated_at'];
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
                case 'category_id':
                    $query->where('category_id', $value);
                    break;
                case 'user_id':
                    $query->where('user_id', $value);
                    break;
            }
        }
    }

    protected function applyPaginationSorting(Builder $query, string $sortField, string $sortDirection): void
    {
        $query->orderBy($sortField, $sortDirection);
    }

    public function getDetail(?AppRequest $request, ?int $id = null)
    {
        if (is_null($id)) {
            $id = $request->getProjectId();
        }

        return $this->getBaseQuery()->with(['category', 'owner', 'members'])->findOrFail($id);
    }

    public function create(CreateProjectRequest $request)
    {
        $validatedRequest = $request->validated();
        $data = $validatedRequest;
        $data['user_id'] = Auth::id();
        info($data);

        return Project::create($data);
    }

    public function update(UpdateProjectRequest $request)
    {
        $project = $this->getBaseQuery()->findOrFail($request->getProjectId());

        $project->update($request->validated());

        return $project;
    }

    public function softDelete(AppRequest $request)
    {
        $project = $this->getBaseQuery()->findOrFail($request->getProjectId());

        $project->delete();

        return $project;
    }
}
