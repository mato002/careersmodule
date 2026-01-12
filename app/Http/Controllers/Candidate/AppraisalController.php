<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\CandidateAppraisal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AppraisalController extends Controller
{
    /**
     * Display the appraisals page.
     */
    public function index()
    {
        $candidate = Auth::guard('candidate')->user();
        
        $appraisals = CandidateAppraisal::where('candidate_id', $candidate->id)
            ->with('createdByUser')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('type');
        
        // Get counts
        $counts = [
            'performance_reviews' => CandidateAppraisal::where('candidate_id', $candidate->id)
                ->where('type', 'performance_review')
                ->count(),
            'hr_communications' => CandidateAppraisal::where('candidate_id', $candidate->id)
                ->where('type', 'hr_communication')
                ->count(),
            'warnings' => CandidateAppraisal::where('candidate_id', $candidate->id)
                ->where('type', 'warning')
                ->count(),
            'unacknowledged' => CandidateAppraisal::where('candidate_id', $candidate->id)
                ->whereNull('acknowledged_at')
                ->count(),
        ];
        
        return view('candidate.appraisals.index', compact('candidate', 'appraisals', 'counts'));
    }

    /**
     * Show a specific appraisal.
     */
    public function show(CandidateAppraisal $appraisal)
    {
        $candidate = Auth::guard('candidate')->user();
        
        // Ensure appraisal belongs to candidate
        if ($appraisal->candidate_id !== $candidate->id) {
            abort(403, 'Unauthorized access.');
        }
        
        $appraisal->load('createdByUser');
        
        return view('candidate.appraisals.show', compact('candidate', 'appraisal'));
    }

    /**
     * Acknowledge an appraisal.
     */
    public function acknowledge(Request $request, CandidateAppraisal $appraisal)
    {
        $candidate = Auth::guard('candidate')->user();
        
        // Ensure appraisal belongs to candidate
        if ($appraisal->candidate_id !== $candidate->id) {
            abort(403, 'Unauthorized access.');
        }
        
        $validated = $request->validate([
            'acknowledgment_notes' => ['nullable', 'string', 'max:1000'],
        ]);
        
        $appraisal->acknowledged_at = now();
        $appraisal->acknowledgment_notes = $validated['acknowledgment_notes'] ?? null;
        $appraisal->status = 'acknowledged';
        $appraisal->save();
        
        return Redirect::route('candidate.appraisals.index')
            ->with('success', 'Appraisal acknowledged successfully.');
    }
}
