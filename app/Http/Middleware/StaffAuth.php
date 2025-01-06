<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class StaffAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role = null)
    {
        // Check if staff is logged in via session
        if (!Session::has('staff_id')) {
            return redirect()->route('login')->withErrors('You must be logged in to access this page.');
        }

        $staffId = Session::get('staff_id');

        // Check if a specific role is required
        if ($role) {
            $userRoles = DB::table('roles')
                ->join('role_staff', 'roles.id', '=', 'role_staff.role_id')
                ->where('role_staff.staff_id', $staffId)
                ->pluck('roles.name');

            if (!$userRoles->contains($role)) {
                return response()->view('errors.403', ['message' => 'Unauthorized access.'], 403);
            }
        }

        return $next($request);
    }
}
