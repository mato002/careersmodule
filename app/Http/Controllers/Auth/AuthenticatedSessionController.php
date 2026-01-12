<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\ActivityLogService;
use App\Services\SessionManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        protected ActivityLogService $activityLogService,
        protected SessionManagementService $sessionManagementService
    ) {}

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();

            $request->session()->regenerate();

            // Check if candidate is logged in
            $candidate = Auth::guard('candidate')->user();
            if ($candidate) {
                // Update last login
                $candidate->update([
                    'last_login_at' => now(),
                    'last_login_ip' => $request->ip(),
                ]);
                
                // Track candidate session
                $this->sessionManagementService->trackCandidateSession($candidate, $request);
                
                // Log candidate login
                $this->activityLogService->logLogin($candidate, true);
                
                // Link existing job applications by email for candidates
                \App\Models\JobApplication::where('email', $candidate->email)
                    ->whereNull('candidate_id')
                    ->update(['candidate_id' => $candidate->id]);
                
                // Get intended URL or use candidate dashboard
                $intended = $request->session()->pull('url.intended');
                if ($intended) {
                    // Clean up any double /careers/ prefix
                    $intended = preg_replace('#/careers/careers/#', '/careers/', $intended);
                    $intended = preg_replace('#^/careers/careers#', '/careers', $intended);
                    // Remove leading /careers if it's a relative path that shouldn't have it
                    $intended = preg_replace('#^/careers(/candidate/dashboard)#', '$1', $intended);
                    return redirect($intended);
                }
                // Fallback: redirect to candidate dashboard (relative path, no /careers prefix)
                return redirect('/candidate/dashboard');
            }

            // Check if employee/user is logged in
            $user = Auth::guard('web')->user();
            if ($user) {
                $this->sessionManagementService->trackSession($user, $request);
                $this->activityLogService->logLogin($user, true);
            }

            // Get intended URL or use dashboard
            $intended = $request->session()->pull('url.intended');
            if ($intended) {
                // Clean up any double /careers/ prefix
                $intended = preg_replace('#/careers/careers/#', '/careers/', $intended);
                $intended = preg_replace('#^/careers/careers#', '/careers', $intended);
                // Remove leading /careers if it's a relative path that shouldn't have it
                $intended = preg_replace('#^/careers(/dashboard|/admin)#', '$1', $intended);
                return redirect($intended);
            }
            
            // Fallback: redirect to dashboard (relative path, no /careers prefix)
            // The dashboard route will then redirect to admin or profile based on user role
            return redirect('/dashboard');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log failed login attempt
            $this->activityLogService->logLogin(null, false);
            
            throw $e;
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $candidate = Auth::guard('candidate')->user();
        $user = Auth::guard('web')->user();
        $sessionId = $request->session()->getId();

        // Logout from both guards
        Auth::guard('candidate')->logout();
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Remove session from tracking and log logout
        if ($candidate && $sessionId) {
            $this->sessionManagementService->revokeCandidateSession($sessionId);
            $this->activityLogService->logLogout($candidate);
        } elseif ($user && $sessionId) {
            $this->sessionManagementService->revokeSession($sessionId);
            $this->activityLogService->logLogout($user);
        }

        return redirect()->route('login');
    }
}
