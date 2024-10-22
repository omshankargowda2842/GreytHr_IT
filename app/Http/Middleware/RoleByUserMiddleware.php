<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleByUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  // Adjust to string to handle ENUM values like 'user', 'admin', 'super_admin'
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Fetch the authenticated user from the 'it' guard
        $user = Auth::guard('it')->user();

        if (!$user->role && !$user) {
            // Redirect or abort if the user does not have the required role
            return redirect()->route('dashboard'); // Change this to your desired redirect
        }

        // If the user has the required role, allow the request to proceed
        return $next($request);
    }
}
