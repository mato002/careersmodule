<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureNotCandidate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Block candidates from accessing admin/employee areas
        if ($user && ($user->role === 'candidate' || ($user->role === 'user' && !$user->is_admin))) {
            abort(403, 'Access denied. This area is for employees only.');
        }
        
        return $next($request);
    }
}

