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
    public function handle($request, Closure $next, $role)
    {
        // Split the role parameter by '|', allowing for multiple roles
        $roles = explode('|', $role);
        $user = Auth::user();

        // Check if the user has any of the specified roles
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        return redirect('/')->with('error', 'You do not have access to this page.');
    }
}
