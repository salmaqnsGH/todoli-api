<?php

namespace App\Http\Controllers\Api\Activity;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Services\Api\Activity\ActivityService;

class ActivityController extends Controller
{
    public function __construct(
        protected ActivityService $activityService,
    ) {}

    public function getUserActivityPaginatedList(GetPaginatedListRequest $request)
    {
        // TODO implement this
        $result = $this->activityService->getUserActivityPaginatedList($request);

        return jsonresSuccess($request, 'OK', []);
    }
}
