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
     */
    public function handle(Request $request, Closure $next, int $role): Response
    {
        $user = Auth::guard('it')->user();

        if (!$user || !$user->hasRole($role)) {
            // Redirect or abort if the user does not have the required role
            return redirect()->route('dashboard'); // Change this to your desired redirect
        }

        return $next($request);
    }
}
