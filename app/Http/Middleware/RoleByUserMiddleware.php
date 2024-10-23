<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        // Split roles
        $roles = explode('|', $role);
        // dd($roles); // Debugging to check roles array

        $user = Auth::user();
        // dd($user->role);

        // Check if the user has any of the roles
        foreach ($roles as $role) {
            if ($user->hasRole(trim($role))) { // Ensure trimming whitespace
                return $next($request); // Allows access
            }
        }

        return redirect('/')->with('error', 'You do not have access to this page.');
    }
}
