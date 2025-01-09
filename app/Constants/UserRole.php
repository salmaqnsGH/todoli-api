<?php

namespace App\Constants;

class UserRole
{
    const SUPER_ADMIN = 1;

    const USER_OWNER = 2;

    const USER_MEMBER = 3;

    private static array $names = [
        self::SUPER_ADMIN => 'Super Admin',
        self::USER_OWNER => 'User Owner',
        self::USER_MEMBER => 'User Member',
    ];

    public static function getName(int $role): string
    {
        return self::$names[$role] ?? '';
    }
}
