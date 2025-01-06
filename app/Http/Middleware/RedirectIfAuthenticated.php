<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the staff is already logged in
        if (Session::has('staff_id')) {
            // Redirect to the dashboard or another page if authenticated
            return redirect()->route('dashboard');
        }

        // If not authenticated, continue to the next request (login page)
        return $next($request);
    }
}
