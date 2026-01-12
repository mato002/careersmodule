<?php

namespace App\Http\Middleware;

use App\Services\SessionManagementService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackCandidateSession
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
        // Track sessions for candidates
        if (Auth::guard('candidate')->check()) {
            $candidate = Auth::guard('candidate')->user();
            $sessionId = $request->session()->getId();
            
            // Update session activity
            $this->sessionManagementService->updateCandidateActivity($candidate, $sessionId);
        }

        return $next($request);
    }
}

