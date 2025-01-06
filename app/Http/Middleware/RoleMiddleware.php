<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
{

    if (Auth::check()) {
        // Get the current logged-in user's ID
        $userId = Auth::id();

        // Query the database to check the user's role directly
        $userRole = DB::table('users')
                      ->where('id', $userId)
                      ->value('role');

        // If the user's role does not match the required role(s), deny access
        if (!in_array($userRole, $roles)) {
            return redirect('/unauthorized');
        }

        // Allow the request to proceed if the role matches
        return $next($request);
    }
    // Redirect to unauthorized if the user is not authenticated
    return redirect('/login');
}

}
