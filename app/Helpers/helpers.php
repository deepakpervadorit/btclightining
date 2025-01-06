<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

if (!function_exists('userHasPermission')) {
    function userHasPermission($permissionName)
    {
        // Get the current user's staff ID from the session
        $staffId = Session::get('staff_id'); // Assuming staff_id is stored in session

        // Get the role ID of the current user
        $roleId = DB::table('role_staff')
                    ->where('staff_id', $staffId)
                    ->value('role_id');

        // Check if the role has the required permission
        $hasPermission = DB::table('permission_role')
                        ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
                        ->where('permission_role.role_id', $roleId)
                        ->where('permissions.name', $permissionName)
                        ->exists();

        return $hasPermission;
    }
}
