<?php

use Spatie\Permission\Models\Role;

if (!function_exists('hasPermissionForActiveRole')) {
    function hasPermissionForActiveRole(string $permission): bool
    {
        $activeRole = session('active_role');

        if (!$activeRole) {
            return false;
        }

        $role = Role::where('name', $activeRole)->first();

        return $role && $role->hasPermissionTo($permission);
    }
}
