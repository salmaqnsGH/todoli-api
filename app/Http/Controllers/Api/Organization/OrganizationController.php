<?php

namespace App\Http\Controllers\Api\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Organization\CreateOrganizationRequest;
use App\Http\Requests\Api\Organization\UpdateOrganizationRequest;
use App\Services\Api\Organization\OrganizationService;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function __construct(
        protected OrganizationService $organizationService,
    ) {}

    public function getPaginatedList(GetPaginatedListRequest $request)
    {
        // TODO implement this
        $result = $this->organizationService->getPaginatedList($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function getDetail(Request $request)
    {
        // TODO implement this
        $result = $this->organizationService->getDetail($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function create(CreateOrganizationRequest $request)
    {
        // TODO implement this
        $result = $this->organizationService->create($request);

        return jsonresCreated($request, 'OK', []);
    }

    public function update(UpdateOrganizationRequest $request)
    {
        // TODO implement this
        $result = $this->organizationService->update($request);

        return jsonresSuccess($request, 'OK', []);
    }

    public function softDelete(Request $request)
    {
        // TODO implement this
        $result = $this->organizationService->softDelete($request);

        return jsonresSuccess($request, 'OK', []);
    }
}
