<?php

namespace App\Services\Api\Project;

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
    protected function getPaginationBaseQuery(GetPaginatedListRequest $request): Builder
    {
        return Project::with('owner')
            ->select('id', 'user_id', 'name', 'description', 'created_at');
    }

    protected function getPaginationAllowedSortFields(): array
    {
        return ['id', 'user_id', 'name', 'created_at', 'updated_at'];
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

    public function getDetail(AppRequest $request)
    {
        return Project::with('owner', 'members')->findOrFail($request->getId());
    }

    public function create(CreateProjectRequest $request)
    {
        $validatedRequest = $request->validated();
        $data = $validatedRequest;
        $data['user_id'] = Auth::id();

        return Project::create($data);
    }

    public function update(UpdateProjectRequest $request)
    {
        $project = Project::findOrFail($request->getId());

        $project->update($request->validated());

        return $project;
    }

    public function softDelete(AppRequest $request)
    {
        $project = Project::findOrFail($request->getId());

        $project->delete();

        return $project;
    }
}
