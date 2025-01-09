<?php

namespace App\Constants;

class Permission
{
    const USER_VIEW = 1;

    const USER_VIEW_OWN = 2;

    const USER_ADD = 3;

    const USER_DELETE_OWN = 4;

    const ORGANIZATION_VIEW = 5;

    const ORGANIZATION_VIEW_OWN = 6;

    const ORGANIZATION_ADD = 7;

    const ORGANIZATION_EDIT_OWN = 8;

    const ORGANIZATION_DELETE_OWN = 9;

    const PROJECT_VIEW = 10;

    const PROJECT_VIEW_OWN = 11;

    const PROJECT_ADD = 12;

    const PROJECT_EDIT_OWN = 13;

    const PROJECT_DELETE_OWN = 14;

    const PROJECT_MEMBER_VIEW = 15;

    const PROJECT_MEMBER_ADD = 16;

    const PROJECT_MEMBER_EDIT_OWN = 17;

    const PROJECT_MEMBER_REMOVE_OWN = 18;

    const TASK_VIEW = 19;

    const TASK_VIEW_OWN = 20;

    const TASK_ADD = 21;

    const TASK_EDIT_OWN = 22;

    const TASK_DELETE_OWN = 23;

    const TASK_USER_ASSIGN = 24;

    const TASK_COMMENT_VIEW = 25;

    const TASK_COMMENT_VIEW_OWN = 26;

    const TASK_COMMENT_ADD = 27;

    const TASK_COMMENT_EDIT_OWN = 28;

    const TASK_COMMENT_DELETE_OWN = 29;

    const TASK_PRIORITY_VIEW = 30;

    const TASK_PRIORITY_ADD = 31;

    const TASK_PRIORITY_EDIT_OWN = 32;

    const TASK_PRIORITY_DELETE_OWN = 33;

    const TASK_PRIORITY_SET_OWN = 34;

    const TASK_STATUS_VIEW = 35;

    const TASK_STATUS_ADD = 36;

    const TASK_STATUS_EDIT_OWN = 37;

    const TASK_STATUS_DELETE_OWN = 38;

    const TASK_STATUS_SET_OWN = 39;

    const USER_DELETE_OTHER = 40;

    const ORGANIZATION_EDIT_OTHER = 41;

    const ORGANIZATION_DELETE_OTHER = 42;

    const PROJECT_EDIT_OTHER = 43;

    const PROJECT_DELETE_OTHER = 44;

    const PROJECT_MEMBER_EDIT_OTHER = 45;

    const PROJECT_MEMBER_REMOVE_OTHER = 46;

    const TASK_EDIT_OTHER = 47;

    const TASK_DELETE_OTHER = 48;

    const TASK_COMMENT_EDIT_OTHER = 49;

    const TASK_COMMENT_DELETE_OTHER = 50;

    const TASK_PRIORITY_SET_OTHER = 51;

    const TASK_STATUS_SET_OTHER = 52;

    const USER_VERIFY = 53;

    const PROJECT_MEMBER_LEAVE_OWN = 54;

    const ACTIVITY_VIEW = 55;

    const ACTIVITY_VIEW_OWN = 56;

    const PROJECT_NOTIFY_OWN = 57;

    const TASK_NOTIFY_OWN = 58;

    const TASK_COMMENT_NOTIFY_OWN = 59;

    private static array $names = [
        self::USER_VIEW => 'View Users',
        self::USER_VIEW_OWN => 'View Own User',
        self::USER_ADD => 'Add User',
        self::USER_DELETE_OWN => 'Delete Own User',
        self::ORGANIZATION_VIEW => 'View Organizations',
        self::ORGANIZATION_VIEW_OWN => 'View Own Organization',
        self::ORGANIZATION_ADD => 'Add Organization',
        self::ORGANIZATION_EDIT_OWN => 'Edit Own Organization',
        self::ORGANIZATION_DELETE_OWN => 'Delete Own Organization',
        self::PROJECT_VIEW => 'View Projects',
        self::PROJECT_VIEW_OWN => 'View Own Projects',
        self::PROJECT_ADD => 'Add Project',
        self::PROJECT_EDIT_OWN => 'Edit Own Project',
        self::PROJECT_DELETE_OWN => 'Delete Own Project',
        self::PROJECT_MEMBER_VIEW => 'View Project Members',
        self::PROJECT_MEMBER_ADD => 'Add Project Member',
        self::PROJECT_MEMBER_EDIT_OWN => 'Edit Own Project Member',
        self::PROJECT_MEMBER_REMOVE_OWN => 'Remove Own Project Member',
        self::TASK_VIEW => 'View Tasks',
        self::TASK_VIEW_OWN => 'View Own Tasks',
        self::TASK_ADD => 'Add Task',
        self::TASK_EDIT_OWN => 'Edit Own Task',
        self::TASK_DELETE_OWN => 'Delete Own Task',
        self::TASK_USER_ASSIGN => 'Assign Task User',
        self::TASK_COMMENT_VIEW => 'View Task Comments',
        self::TASK_COMMENT_VIEW_OWN => 'View Own Task Comments',
        self::TASK_COMMENT_ADD => 'Add Task Comment',
        self::TASK_COMMENT_EDIT_OWN => 'Edit Own Task Comment',
        self::TASK_COMMENT_DELETE_OWN => 'Delete Own Task Comment',
        self::TASK_PRIORITY_VIEW => 'View Task Priorities',
        self::TASK_PRIORITY_ADD => 'Add Task Priority',
        self::TASK_PRIORITY_EDIT_OWN => 'Edit Own Task Priority',
        self::TASK_PRIORITY_DELETE_OWN => 'Delete Own Task Priority',
        self::TASK_PRIORITY_SET_OWN => 'Set Own Task Priority',
        self::TASK_STATUS_VIEW => 'View Task Statuses',
        self::TASK_STATUS_ADD => 'Add Task Status',
        self::TASK_STATUS_EDIT_OWN => 'Edit Own Task Status',
        self::TASK_STATUS_DELETE_OWN => 'Delete Own Task Status',
        self::TASK_STATUS_SET_OWN => 'Set Own Task Status',
        self::USER_DELETE_OTHER => 'Delete Other User',
        self::ORGANIZATION_EDIT_OTHER => 'Edit Other Organization',
        self::ORGANIZATION_DELETE_OTHER => 'Delete Other Organization',
        self::PROJECT_EDIT_OTHER => 'Edit Other Project',
        self::PROJECT_DELETE_OTHER => 'Delete Other Project',
        self::PROJECT_MEMBER_EDIT_OTHER => 'Edit Other Project Member',
        self::PROJECT_MEMBER_REMOVE_OTHER => 'Remove Other Project Member',
        self::TASK_EDIT_OTHER => 'Edit Other Task',
        self::TASK_DELETE_OTHER => 'Delete Other Task',
        self::TASK_COMMENT_EDIT_OTHER => 'Edit Other Task Comment',
        self::TASK_COMMENT_DELETE_OTHER => 'Delete Other Task Comment',
        self::TASK_PRIORITY_SET_OTHER => 'Set Other Task Priority',
        self::TASK_STATUS_SET_OTHER => 'Set Other Task Status',
        self::USER_VERIFY => 'Verify User',
        self::PROJECT_MEMBER_LEAVE_OWN => 'Leave Own Project',
        self::ACTIVITY_VIEW => 'View Activities',
        self::ACTIVITY_VIEW_OWN => 'View Own Activities',
        self::PROJECT_NOTIFY_OWN => 'Get Own Project Notifications',
        self::TASK_NOTIFY_OWN => 'Get Own Task Notifications',
        self::TASK_COMMENT_NOTIFY_OWN => 'Get Own Task Comment Notifications',
    ];

    public static function getName(int $permission): string
    {
        return self::$names[$permission] ?? '';
    }

    public static function getDefaultSuperAdminPermissions()
    {
        return [
            self::USER_VIEW,
            self::USER_ADD,
            self::USER_VERIFY,
            self::USER_DELETE_OTHER,
        ];
    }

    public static function getDefaultUserOwnerPermissions()
    {
        return [
            self::ORGANIZATION_VIEW_OWN,
            self::ORGANIZATION_ADD,
            self::ORGANIZATION_EDIT_OWN,
            self::ORGANIZATION_DELETE_OWN,
            self::PROJECT_VIEW_OWN,
            self::PROJECT_ADD,
            self::PROJECT_EDIT_OWN,
            self::PROJECT_DELETE_OWN,
            self::PROJECT_MEMBER_VIEW,
            self::PROJECT_MEMBER_ADD,
            self::PROJECT_MEMBER_EDIT_OWN,
            self::PROJECT_MEMBER_REMOVE_OWN,
            self::PROJECT_MEMBER_LEAVE_OWN,
            self::PROJECT_NOTIFY_OWN,
            self::TASK_VIEW,
            self::TASK_VIEW_OWN,
            self::TASK_ADD,
            self::TASK_EDIT_OWN,
            self::TASK_DELETE_OWN,
            self::TASK_USER_ASSIGN,
            self::TASK_NOTIFY_OWN,
            self::TASK_COMMENT_VIEW,
            self::TASK_COMMENT_VIEW_OWN,
            self::TASK_COMMENT_ADD,
            self::TASK_COMMENT_EDIT_OWN,
            self::TASK_COMMENT_DELETE_OWN,
            self::TASK_COMMENT_NOTIFY_OWN,
            self::TASK_PRIORITY_VIEW,
            self::TASK_PRIORITY_ADD,
            self::TASK_PRIORITY_EDIT_OWN,
            self::TASK_PRIORITY_DELETE_OWN,
            self::TASK_PRIORITY_SET_OWN,
            self::TASK_PRIORITY_SET_OTHER,
            self::TASK_STATUS_VIEW,
            self::TASK_STATUS_ADD,
            self::TASK_STATUS_EDIT_OWN,
            self::TASK_STATUS_DELETE_OWN,
            self::TASK_STATUS_SET_OWN,
            self::TASK_STATUS_SET_OTHER,
            self::ACTIVITY_VIEW,
            self::ACTIVITY_VIEW_OWN,
        ];
    }

    public static function getDefaultUserMemberPermissions()
    {
        return [
            self::ORGANIZATION_VIEW_OWN,
            self::PROJECT_VIEW_OWN,
            self::PROJECT_MEMBER_VIEW,
            self::PROJECT_MEMBER_LEAVE_OWN,
            self::PROJECT_NOTIFY_OWN,
            self::TASK_VIEW,
            self::TASK_VIEW_OWN,
            self::TASK_NOTIFY_OWN,
            self::TASK_COMMENT_VIEW,
            self::TASK_COMMENT_VIEW_OWN,
            self::TASK_COMMENT_ADD,
            self::TASK_COMMENT_EDIT_OWN,
            self::TASK_COMMENT_DELETE_OWN,
            self::TASK_COMMENT_NOTIFY_OWN,
            self::TASK_STATUS_SET_OWN,
            self::ACTIVITY_VIEW_OWN,
        ];
    }
}
