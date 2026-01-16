<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidateDashboardController extends Controller
{
    /**
     * Display the candidate dashboard (overview).
     */
    public function index(Request $request)
    {
        $candidate = Auth::guard('candidate')->user();
        
        if (!$candidate) {
            abort(403, 'Unauthorized. Please log in as a candidate.');
        }
        
        // Link any existing applications by email if not already linked
        $this->linkApplicationsByEmail($candidate);
        
        // Get statistics for dashboard overview
        $stats = [
            'total' => JobApplication::where('candidate_id', $candidate->id)->count(),
            'pending' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'pending')->count(),
            'sieving_passed' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'sieving_passed')->count(),
            'sieving_rejected' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'sieving_rejected')->count(),
            'aptitude_failed' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'aptitude_failed')->count(),
            'stage_2_passed' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'stage_2_passed')->count(),
            'hired' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'hired')->count(),
        ];
        
        // Get recent applications (last 5) for quick overview
        $recentApplications = JobApplication::where('candidate_id', $candidate->id)
            ->with(['jobPost', 'jobPost.company'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get applications requiring action
        $actionRequired = JobApplication::where('candidate_id', $candidate->id)
            ->where(function($query) {
                $query->whereIn('status', ['sieving_passed', 'pending_manual_review'])
                      ->whereNull('aptitude_test_completed_at');
            })
            ->orWhere(function($query) {
                $query->where('aptitude_test_passed', true)
                      ->whereNull('self_interview_completed_at')
                      ->whereNotIn('status', ['stage_2_passed', 'hired', 'sieving_rejected']);
            })
            ->with(['jobPost'])
            ->limit(5)
            ->get();
        
        return view('candidate.dashboard', compact('stats', 'recentApplications', 'actionRequired', 'candidate'));
    }

    /**
     * Display all applications (detailed list).
     */
    public function applications(Request $request)
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
                'jobPost.company',
                'aiSievingDecision',
                'aptitudeTestSession',
                'selfInterviewSession',
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
            'aptitude_failed' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'aptitude_failed')->count(),
            'stage_2_passed' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'stage_2_passed')->count(),
            'hired' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'hired')->count(),
        ];
        
        return view('candidate.applications', compact('applications', 'stats'));
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
        
        $application->load([
            'jobPost',
            'jobPost.company',
            'aiSievingDecision',
            'aptitudeTestSession',
            'selfInterviewSession',
            'interviews' => function($query) {
                $query->orderBy('scheduled_at', 'desc');
            }
        ]);
        
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

