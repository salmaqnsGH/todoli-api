<?php

namespace App\Services\Api\Project;

use App\Constants\UserRole;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Project\AddProjectMemberRequest;
use App\Http\Requests\AppRequest;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\ProjectPermission;
use App\Services\PaginationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class ProjectMemberService extends PaginationService
{
    protected function getPaginationBaseQuery(GetPaginatedListRequest $request): Builder
    {
        /** @var \Illuminate\Contracts\Auth\Access\Authorizable */
        $currentUser = Auth::user();
        $projectId = $request->getProjectId();
        $filters = $request->input('filters');
        $parsedFilters = $this->parseFilters($filters);
        $isMember = $parsedFilters['is_member'] ?? true;

        $project = Project::with(['category', 'all_members'])
            ->whereHas('all_members', function ($query) use ($currentUser) {
                $query->where('user_id', $currentUser->id);
            })
            ->without('all_members')->findOrFail($projectId);

        $columns = [
            'users.id',
            'users.organization_id',
            'users.username',
            'users.first_name',
            'users.last_name',
            'users.email',
            'users.created_at',
            'users.updated_at',
        ];

        if ($isMember) {
            return $project->members()->getQuery()->select($columns);
        }

        return $project->non_members()->select($columns);
    }

    protected function getPaginationAllowedSortFields(): array
    {
        return ['id', 'organization_id', 'username', 'first_name', 'last_name', 'email', 'created_at', 'updated_at'];
    }

    protected function applyPaginationSearch(Builder $query, string $search): void
    {
        $query->where(function ($q) use ($search) {
            $q->where('username', 'LIKE', "%{$search}%")
                ->orWhere('first_name', 'LIKE', "%{$search}%")
                ->orWhere('last_name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
        });
    }

    protected function applyPaginationFilters(Builder $query, array $parsedFilters): void
    {
        foreach ($parsedFilters as $field => $value) {
            switch ($field) {
                case 'id':
                    $query->where('users.id', $value);
                    break;
                case 'role':
                    $query->where('users.organization_id', $value);
                    break;
            }
        }
    }

    protected function applyPaginationSorting(Builder $query, string $sortField, string $sortDirection): void
    {
        $query->orderBy($sortField, $sortDirection);
    }

    public function getMembersByProjectId(int $id)
    {
        return Project::findOrFail($id)
            ->members()
            ->get();
    }

    public function add(?AddProjectMemberRequest $request = null, ?array $data = null)
    {
        if (is_not_null($data)) {
            $userId = $data['user_id'];
            $projectId = $data['project_id'];
        } else {
            $data = $request->validated();
            $userId = $data['user_id'];
            $projectId = $request->getProjectId();
            $data['project_id'] = $projectId;
        }

        // Handle additions - first try to restore soft deleted records
        $existingProjectMember = ProjectMember::withTrashed()
            ->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->first();

        if ($existingProjectMember) {
            $existingProjectMember->restore();
            ProjectPermission::withTrashed()
                ->where('project_id', $projectId)
                ->where('user_id', $userId)
                ->restore();

            return $existingProjectMember;
        }

        // Create new record if no soft-deleted record exists
        return ProjectMember::create($data);
    }

    public function addOwner(int $projectId, int $userId)
    {
        $projectMember = $this->add(null, [
            'project_id' => $projectId,
            'user_id' => $userId,
            'role_id' => UserRole::USER_OWNER,
        ]);

        return $projectMember->user;
    }

    public function addMultipleMembers(int $projectId, array $userIds)
    {
        $userMembers = [];

        foreach ($userIds as $key => $userId) {
            $projectMember = $this->add(null, [
                'project_id' => $projectId,
                'user_id' => $userId,
                'role_id' => UserRole::USER_MEMBER,
            ]);

            $userMembers[] = $projectMember->user;
        }

        return $userMembers;
    }

    public function updateMultipleMembers(int $projectId, array $newUserIds)
    {
        // Get current project members (excluding owner)
        $currentprojectMembers = ProjectMember::where('project_id', $projectId)
            ->where('role_id', UserRole::USER_MEMBER)
            ->get();
        $currentUserIds = $currentprojectMembers->pluck('user_id')->toArray();

        // Find IDs to remove (exist in current but not in new)
        $userIdsToRemove = array_diff($currentUserIds, $newUserIds);

        // Find IDs to add (exist in new but not in current)
        $userIdsToAdd = array_diff($newUserIds, $currentUserIds);

        // Handle removals (soft delete)
        if (is_not_empty_col($userIdsToRemove)) {
            $this->softRemove(null, [
                'user_ids' => $userIdsToRemove,
                'project_id' => $projectId,
            ]);
        }

        foreach ($userIdsToAdd as $userId) {
            $this->add(null, [
                'project_id' => $projectId,
                'user_id' => $userId,
                'role_id' => UserRole::USER_MEMBER,
            ]);
        }

        // Return updated list of all active project members
        return $this->getMembersByProjectId($projectId);
    }

    public function softRemove(?AppRequest $request = null, ?array $data = null)
    {
        if (is_not_null($data)) {
            $userIds = $data['user_ids'];
            $projectId = $data['project_id'];
        } else {
            $data = $request->validated();
            $userIds = [$request->getProjectUserId()];
            $projectId = $request->getProjectId();
        }

        ProjectMember::where('project_id', $projectId)
            ->whereIn('user_id', $userIds)
            ->delete();

        // Also soft delete related project_permissions
        ProjectPermission::where('project_id', $projectId)
            ->whereIn('user_id', $userIds)
            ->delete();

        return null;
    }

    public function join(AppRequest $request)
    {
        /** @var \Illuminate\Contracts\Auth\Access\Authorizable */
        $currentUser = Auth::user();

        $projectShortHash = $request->getProjectShortHash();

        $project = Project::get()
            ->where('short_hash', $projectShortHash)
            ->first() ?? throw new ModelNotFoundException;

        $members = $this->addMultipleMembers($project->id, [$currentUser->id]);

        return $members[0];
    }

    public function leave(AppRequest $request)
    {
        /** @var \Illuminate\Contracts\Auth\Access\Authorizable */
        $currentUser = Auth::user();

        $projectId = $request->getProjectId();

        return $this->softRemove(null, [
            'user_ids' => [$currentUser->id],
            'project_id' => $projectId,
        ]);
    }
}
