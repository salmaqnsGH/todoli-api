<?php

namespace App\Services\Api\Organization;

use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Organization\CreateOrganizationRequest;
use App\Http\Requests\Api\Organization\UpdateOrganizationRequest;
use Illuminate\Http\Request;

class OrganizationService
{
    public function getPaginatedList(GetPaginatedListRequest $request)
    {
        return null;
    }

    public function getDetail(Request $request)
    {
        return null;
    }

    public function create(CreateOrganizationRequest $request)
    {
        return null;
    }

    public function update(UpdateOrganizationRequest $request)
    {
        return null;
    }

    public function softDelete(Request $request)
    {
        return null;
    }
}
