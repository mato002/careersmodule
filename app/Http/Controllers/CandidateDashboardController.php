<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidateDashboardController extends Controller
{
    /**
     * Display the candidate dashboard.
     */
    public function index(Request $request)
    {
        $candidate = Auth::guard('candidate')->user();
        
        if (!$candidate) {
            abort(403, 'Unauthorized. Please log in as a candidate.');
        }
        
        // Link any existing applications by email if not already linked
        $this->linkApplicationsByEmail($candidate);
        
        // Get all applications for this candidate
        $applications = JobApplication::where('candidate_id', $candidate->id)
            ->with([
                'jobPost',
                'aiSievingDecision',
                'aptitudeTestSession',
                'interviews' => function($query) {
                    $query->orderBy('scheduled_at', 'desc');
                },
                'messages.sender',
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Statistics
        $stats = [
            'total' => JobApplication::where('candidate_id', $candidate->id)->count(),
            'pending' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'pending')->count(),
            'sieving_passed' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'sieving_passed')->count(),
            'sieving_rejected' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'sieving_rejected')->count(),
            'stage_2_passed' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'stage_2_passed')->count(),
            'hired' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'hired')->count(),
        ];
        
        return view('candidate.dashboard', compact('applications', 'stats'));
    }

    /**
     * Show a specific application.
     */
    public function show(JobApplication $application)
    {
        $candidate = Auth::guard('candidate')->user();
        
        if (!$candidate) {
            abort(403, 'Unauthorized. Please log in as a candidate.');
        }
        
        // Ensure the application belongs to the logged-in candidate
        if ($application->candidate_id !== $candidate->id) {
            abort(403, 'Unauthorized access to this application.');
        }
        
        $application->load(['jobPost', 'aiSievingDecision', 'aptitudeTestSession']);
        
        // Generate token for direct access
        $token = md5($application->email . $application->id . config('app.key'));
        
        return view('candidate.application-show', compact('application', 'token'));
    }

    /**
     * Link existing applications by email to the candidate.
     */
    private function linkApplicationsByEmail(Candidate $candidate): void
    {
        JobApplication::where('email', $candidate->email)
            ->whereNull('candidate_id')
            ->update(['candidate_id' => $candidate->id]);
    }
}

