<?php

namespace App\Http\Middleware;

use App\Services\SessionManagementService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackUserSession
{
    public function __construct(
        protected SessionManagementService $sessionManagementService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only track sessions for employees (web guard), not candidates
        // Candidates use a separate authentication system and don't need session tracking
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $sessionId = $request->session()->getId();
            
            // Update session activity
            $this->sessionManagementService->updateActivity($user, $sessionId);
        }

        return $next($request);
    }
}
