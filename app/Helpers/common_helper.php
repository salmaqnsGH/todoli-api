<?php

use App\Constants\Permission;

if (! function_exists('is_not')) {
    function is_not($condition): bool
    {
        return ! $condition;
    }
}

if (! function_exists('is_not_null')) {
    function is_not_null($value): bool
    {
        return ! is_null($value);
    }
}

if (! function_exists('pn')) {
    function pn(int|array $permission): string
    {
        if (is_array($permission)) {
            // Multiple permissions: convert each to name and join with |
            return implode('|', array_map(fn ($p) => Permission::getName($p), $permission));
        }

        // Single permission
        return Permission::getName($permission);
    }
}
