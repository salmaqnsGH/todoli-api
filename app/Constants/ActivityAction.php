<?php

namespace App\Constants;

class ActivityAction
{
    const ADD = 1;

    const UPDATE = 2;

    const DELETE = 3;

    const RESTORE = 4;

    private static array $names = [
        self::ADD => 'Add',
        self::UPDATE => 'Update',
        self::DELETE => 'Delete',
        self::RESTORE => 'Restore',
    ];

    public static function getName(int $action): string
    {
        return self::$names[$action] ?? '';
    }
}
