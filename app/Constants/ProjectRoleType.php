<?php

namespace App\Constants;

class ProjectRoleType
{
    const OWNER = 1;

    const TEAM_MEMBER = 2;

    private static array $names = [
        self::OWNER => 'Project Owner',
        self::TEAM_MEMBER => 'Member',
    ];

    public static function getName(int $role): string
    {
        return self::$names[$role] ?? '';
    }
}
