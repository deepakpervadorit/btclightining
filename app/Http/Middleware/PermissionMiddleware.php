<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class PermissionMiddleware
{
    public function handle($request, Closure $next, $permission = null)
    {
        // Check if staff is logged in via session
        if (!Session::has('staff_id')) {
            return redirect()->route('login')->withErrors('You must be logged in to access this page.');
        }

        // Get the logged-in staff ID from the session
        $staffId = Session::get('staff_id');

        // Fetch the user's roles
        $userRoles = DB::table('role_staff')
                        ->where('staff_id', $staffId)
                        ->pluck('role_id')
                        ->toArray();

        // Fetch the permissions for the user's roles
        $permissions = DB::table('permissions')
                            ->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
                            ->whereIn('permission_role.role_id', $userRoles)
                            ->pluck('permissions.name')
                            ->toArray();
        
        // Check if the user has the required permission
        if ($permission && !in_array($permission, $permissions)) {
            return redirect()->back()->withErrors('You do not have permission to access this page.');
        }

        // Allow the request to proceed if all checks pass
        return $next($request);
    }
}
