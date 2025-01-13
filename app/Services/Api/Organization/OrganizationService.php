<?php

namespace App\Services\Api\Organization;

use App\Constants\Value;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Organization\CreateOrganizationRequest;
use App\Http\Requests\Api\Organization\UpdateOrganizationRequest;
use App\Http\Requests\AppRequest;
use App\Models\Organization;
use App\Services\PaginationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class OrganizationService extends PaginationService
{
    protected function getPaginationBaseQuery(GetPaginatedListRequest $request): Builder
    {
        return Organization::select('id', 'name');
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

    public function getDetail(Request $request)
    {
        return Organization::findOrFail($request->getId());
    }

    public function getByNameWithoutFail(string $name)
    {
        return Organization::where('name', $name)->first();
    }

    public function create(?CreateOrganizationRequest $request = null, ?array $data = null)
    {
        if (is_not_null($data)) {
            return Organization::create($data);
        }

        return Organization::create($request->validated());
    }

    public function update(UpdateOrganizationRequest $request)
    {
        $organization = Organization::findOrFail($request->getId());

        $organization->update($request->validated());

        return $organization;
    }

    public function softDelete(AppRequest $request)
    {
        $organization = Organization::findOrFail($request->getId());

        $organization->delete();

        return $organization;
    }
}
