<?php

namespace App\Services\Api\Project;

use App\Constants\UserRole;
use App\Http\Requests\Api\Project\AddProjectMemberRequest;
use App\Http\Requests\AppRequest;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\ProjectPermission;

class ProjectMemberService
{
    public function getMembersByProjectId(int $projectId)
    {
        return Project::findOrFail($projectId)
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
            $projectId = $request->getId();
            $data['project_id'] = $projectId;
        }

        // Handle additions - first try to restore soft deleted records
        $existingMember = ProjectMember::withTrashed()
            ->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->first();

        if ($existingMember) {
            $existingMember->restore();
            ProjectPermission::withTrashed()
                ->where('project_id', $projectId)
                ->where('user_id', $userId)
                ->restore();

            return $existingMember;
        }

        // Create new record if no soft-deleted record exists
        return ProjectMember::create($data);
    }

    public function addOwner(int $projectId, int $userId)
    {
        return $this->add(null, [
            'project_id' => $projectId,
            'user_id' => $userId,
            'role_id' => UserRole::USER_OWNER,
        ]);
    }

    public function addMultipleMembers(int $projectId, array $userIds)
    {
        $members = [];

        foreach ($userIds as $key => $userId) {
            $members[] = $this->add(null, [
                'project_id' => $projectId,
                'user_id' => $userId,
                'role_id' => UserRole::USER_MEMBER,
            ]);
        }

        return $members;
    }

    public function updateMultipleMembers(int $projectId, array $newUserIds)
    {
        // Get current members (excluding owner)
        $currentMembers = ProjectMember::where('project_id', $projectId)
            ->where('role_id', UserRole::USER_MEMBER)
            ->get();
        $currentUserIds = $currentMembers->pluck('user_id')->toArray();

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

        // Return updated list of all active members
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
            $projectId = $request->getId();
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
}
