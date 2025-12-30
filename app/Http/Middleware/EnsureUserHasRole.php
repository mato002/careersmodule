<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Unauthorized');
        }

        // Check if user has any of the required roles
        $hasRole = false;
        foreach ($roles as $role) {
            if ($user->hasRole($role) || 
                ($role === 'admin' && $user->isAdmin()) ||
                ($role === 'hr_manager' && $user->isHrManager()) ||
                ($role === 'client' && $user->isClient())) {
                $hasRole = true;
                break;
            }
        }

        if (! $hasRole) {
            abort(403, 'You do not have the required role to access this resource.');
        }

        return $next($request);
    }
}

