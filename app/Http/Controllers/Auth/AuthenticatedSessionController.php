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
                // Link existing job applications by email for candidates
                \App\Models\JobApplication::where('email', $candidate->email)
                    ->whereNull('candidate_id')
                    ->update(['candidate_id' => $candidate->id]);
                
                return redirect()->intended(route('candidate.dashboard', absolute: false));
            }

            // Check if employee/user is logged in
            $user = Auth::guard('web')->user();
            if ($user) {
                $this->sessionManagementService->trackSession($user, $request);
                $this->activityLogService->logLogin($user, true);
            }

            return redirect()->intended(route('dashboard', absolute: false));
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

        // Remove session from tracking (only for employees)
        if ($user && $sessionId) {
            $this->sessionManagementService->revokeSession($sessionId);
            $this->activityLogService->logLogout($user);
        }

        return redirect()->route('login');
    }
}
