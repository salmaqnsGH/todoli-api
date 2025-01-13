<?php

namespace App\Http\Controllers\Api\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Organization\CreateOrganizationRequest;
use App\Http\Requests\Api\Organization\UpdateOrganizationRequest;
use App\Http\Requests\AppRequest;
use App\Services\Api\Organization\OrganizationService;

class OrganizationController extends Controller
{
    public function __construct(
        protected OrganizationService $organizationService,
    ) {}

    public function getPaginatedList(GetPaginatedListRequest $request)
    {
        $result = $this->organizationService->getPaginatedList($request);

        return jsonresSuccess($request, 'Success get list data', $result);
    }

    public function getDetail(AppRequest $request)
    {
        $result = $this->organizationService->getDetail($request);

        return jsonresSuccess($request, 'Success get data', $result);
    }

    public function create(CreateOrganizationRequest $request)
    {
        $result = $this->organizationService->create($request);

        return jsonresCreated($request, 'Success create data', $result);
    }

    public function update(UpdateOrganizationRequest $request)
    {
        $result = $this->organizationService->update($request);

        return jsonresSuccess($request, 'Success update data', $result);
    }

    public function softDelete(AppRequest $request)
    {
        $result = $this->organizationService->softDelete($request);

        return jsonresSuccess($request, 'Data is deleted', $result);
    }
}
