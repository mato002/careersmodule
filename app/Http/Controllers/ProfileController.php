<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\CandidateProfileUpdateRequest;
use App\Services\ActivityLogService;
use App\Services\SessionManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Candidate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        protected SessionManagementService $sessionManagementService,
        protected ActivityLogService $activityLogService
    ) {}
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // Check if candidate is logged in
        $candidate = Auth::guard('candidate')->user();
        if ($candidate) {
            $sessions = $candidate->activeSessions()->get();
            $currentSessionId = $request->session()->getId();
            
            return view('candidate.profile', [
                'candidate' => $candidate,
                'user' => $candidate, // For backward compatibility in views
                'sessions' => $sessions,
                'currentSessionId' => $currentSessionId,
            ]);
        }
        
        // Otherwise, it's an employee
        $user = $request->user();
        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Display the admin-styled profile view.
     */
    public function editAdmin(Request $request): View
    {
        $user = $request->user();
        $sessions = $user->activeSessions()->get();
        $currentSessionId = $request->session()->getId();

        return view('admin.profile', [
            'user' => $user,
            'sessions' => $sessions,
            'currentSessionId' => $currentSessionId,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest|CandidateProfileUpdateRequest $request): RedirectResponse
    {
        // Handle candidate profile update
        $candidate = Auth::guard('candidate')->user();
        if ($candidate) {
            $validated = $request->validated();
            
            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($candidate->profile_photo_path) {
                    Storage::disk('public')->delete($candidate->profile_photo_path);
                }
                
                // Store new photo
                $path = $request->file('profile_photo')->store('candidates/photos', 'public');
                $validated['profile_photo_path'] = $path;
            }
            
            // Remove profile_photo from validated data (we use profile_photo_path)
            unset($validated['profile_photo']);
            
            $candidate->fill($validated);
            
            if ($candidate->isDirty('email')) {
                $candidate->email_verified_at = null;
            }
            
            $candidate->save();
            
            // Log activity
            $this->activityLogService->logCandidateActivity(
                'update',
                "Candidate {$candidate->name} updated their profile",
                $candidate
            );
            
            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        }
        
        // Handle employee profile update
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        
        // Redirect to admin profile if accessed from admin panel
        if ($user->is_admin) {
            return Redirect::route('admin.profile')->with('status', 'profile-updated');
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        // Handle candidate account deletion
        $candidate = Auth::guard('candidate')->user();
        if ($candidate) {
            // Delete profile photo if exists
            if ($candidate->profile_photo_path) {
                Storage::disk('public')->delete($candidate->profile_photo_path);
            }
            
            // Log activity before deletion
            $this->activityLogService->logCandidateActivity(
                'delete',
                "Candidate {$candidate->name} deleted their account",
                $candidate
            );
            
            Auth::guard('candidate')->logout();
            $candidate->delete();
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return Redirect::route('login');
        }

        // Handle employee account deletion
        $user = $request->user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::route('login');
    }

    /**
     * Revoke a specific session.
     * Handles both candidates and employees.
     */
    public function revokeSession(Request $request, string $sessionId): RedirectResponse
    {
        // Check if candidate is logged in
        $candidate = Auth::guard('candidate')->user();
        if ($candidate) {
            return $this->revokeCandidateSession($request, $sessionId);
        }
        
        // Otherwise handle as employee
        $user = $request->user();
        $currentSessionId = $request->session()->getId();

        // Prevent revoking current session
        if ($sessionId === $currentSessionId) {
            return back()->withErrors(['session' => 'You cannot revoke your current session.']);
        }

        // Verify the session belongs to the user
        $session = $user->sessions()->where('session_id', $sessionId)->first();
        
        if (!$session) {
            return back()->withErrors(['session' => 'Session not found.']);
        }

        $this->sessionManagementService->revokeSession($sessionId);

        return back()->with('status', 'Session revoked successfully.');
    }

    /**
     * Revoke all other sessions.
     * Handles both candidates and employees.
     */
    public function revokeOtherSessions(Request $request): RedirectResponse
    {
        // Check if candidate is logged in
        $candidate = Auth::guard('candidate')->user();
        if ($candidate) {
            return $this->revokeOtherCandidateSessions($request);
        }
        
        // Otherwise handle as employee
        $user = $request->user();
        $currentSessionId = $request->session()->getId();

        $count = $this->sessionManagementService->revokeOtherSessions($user, $currentSessionId);

        return back()->with('status', "Revoked {$count} other session(s).");
    }

    /**
     * Revoke a candidate session.
     */
    public function revokeCandidateSession(Request $request, string $sessionId): RedirectResponse
    {
        $candidate = Auth::guard('candidate')->user();
        if (!$candidate) {
            abort(403);
        }
        
        $currentSessionId = $request->session()->getId();

        // Prevent revoking current session
        if ($sessionId === $currentSessionId) {
            return back()->withErrors(['session' => 'You cannot revoke your current session.']);
        }

        // Verify the session belongs to the candidate
        $session = $candidate->sessions()->where('session_id', $sessionId)->first();
        
        if (!$session) {
            return back()->withErrors(['session' => 'Session not found.']);
        }

        $this->sessionManagementService->revokeCandidateSession($sessionId);

        return back()->with('status', 'Session revoked successfully.');
    }

    /**
     * Revoke all other candidate sessions.
     */
    public function revokeOtherCandidateSessions(Request $request): RedirectResponse
    {
        $candidate = Auth::guard('candidate')->user();
        if (!$candidate) {
            abort(403);
        }
        
        $currentSessionId = $request->session()->getId();

        $count = $this->sessionManagementService->revokeOtherCandidateSessions($candidate, $currentSessionId);

        return back()->with('status', "Revoked {$count} other session(s).");
    }
}
