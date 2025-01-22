<?php

namespace App\Services\Api\Project;

use App\Constants\Value;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Project\CreateProjectCategoryRequest;
use App\Http\Requests\Api\Project\UpdateProjectCategoryRequest;
use App\Http\Requests\AppRequest;
use App\Models\ProjectCategory;
use App\Services\PaginationService;
use Illuminate\Database\Eloquent\Builder;

class ProjectCategoryService extends PaginationService
{
    protected function getPaginationBaseQuery(GetPaginatedListRequest $request): Builder
    {
        return ProjectCategory::select('id', 'name');
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
        return ProjectCategory::findOrFail($request->getId());
    }

    public function create(CreateProjectCategoryRequest $request)
    {
        return ProjectCategory::create($request->validated());
    }

    public function update(UpdateProjectCategoryRequest $request)
    {
        $projectCategory = ProjectCategory::findOrFail($request->getId());

        $projectCategory->update($request->validated());

        return $projectCategory;
    }

    public function softDelete(AppRequest $request)
    {
        $projectCategory = ProjectCategory::findOrFail($request->getId());

        $projectCategory->delete();

        return $projectCategory;
    }
}
