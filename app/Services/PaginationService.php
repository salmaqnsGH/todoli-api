<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use App\Http\Requests\Api\GetPaginatedListRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

abstract class PaginationService
{
    abstract protected function getPaginationBaseQuery(): Builder;

    abstract protected function getPaginationAllowedSortFields(): array;

    abstract protected function applyPaginationSearch(Builder $query, string $search): void;

    abstract protected function applyPaginationFilters(Builder $query, array $parsedFilters): void;

    abstract protected function applyPaginationSorting(Builder $query, string $sortField, string $sortDirection): void;

    public function getPaginatedList(GetPaginatedListRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'sort_field' => [
                'sometimes',
                'string',
                'in:' . implode(',', $this->getPaginationAllowedSortFields())
            ],
            'sort_direction' => 'sometimes|string|in:asc,desc',
        ]);

        if ($validator->fails()) {
            $response = jsonresBadRequest($request, 'Invalid data', $validator->errors());

            throw new HttpResponseException($response);
        }

        $search = $request->input('search');
        $filters = $request->input('filters');
        $sortField = $request->input('sort_field', 'updated_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $page = is_numeric($request->input('page')) ? (int) $request->input('page') : 1;
        $perPage = is_numeric($request->input('per_page')) ? (int) $request->input('per_page') : 0;

        $query = $this->getPaginationBaseQuery();

        if ($perPage <= 0) {
            $perPage = $query->count();
        }

        if ($search) {
            $this->applyPaginationSearch($query, $search);
        }

        if ($filters) {
            $parsedFilters = $this->parseFilters($filters);
            $this->applyPaginationFilters($query, $parsedFilters);
        }

        if ($sortField) {
            $this->applyPaginationSorting($query, $sortField, $sortDirection);
        }

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        return $query->paginate($perPage);
    }

    protected function parseFilters(string $filters): array
    {
        $filters = explode(',', $filters);
        $parsedFilters = [];

        foreach ($filters as $filter) {
            [$key, $value] = explode(':', $filter);
            $parsedFilters[$key] = $value;
        }

        return $parsedFilters;
    }
}
