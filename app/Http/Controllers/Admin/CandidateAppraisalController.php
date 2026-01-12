<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\CandidateAppraisal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CandidateAppraisalController extends Controller
{
    /**
     * Display candidate appraisals.
     */
    public function index(Candidate $candidate): View
    {
        $user = auth()->user();
        
        // Check company access for clients
        if ($user && $user->isClient() && $user->company_id) {
            $hasAccess = $candidate->jobApplications()
                ->where('company_id', $user->company_id)
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You do not have permission to access this candidate.');
            }
        }

        $appraisals = CandidateAppraisal::where('candidate_id', $candidate->id)
            ->with('createdByUser')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('type');

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
        ];

        return view('admin.candidates.appraisals', compact('candidate', 'appraisals', 'counts'));
    }

    /**
     * Show the form for creating a new appraisal.
     */
    public function create(Candidate $candidate): View
    {
        $user = auth()->user();
        
        // Check company access for clients
        if ($user && $user->isClient() && $user->company_id) {
            $hasAccess = $candidate->jobApplications()
                ->where('company_id', $user->company_id)
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You do not have permission to access this candidate.');
            }
        }

        return view('admin.candidates.appraisals-create', compact('candidate'));
    }

    /**
     * Store a newly created appraisal.
     */
    public function store(Request $request, Candidate $candidate): RedirectResponse
    {
        $user = auth()->user();
        
        // Check company access for clients
        if ($user && $user->isClient() && $user->company_id) {
            $hasAccess = $candidate->jobApplications()
                ->where('company_id', $user->company_id)
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You do not have permission to access this candidate.');
            }
        }

        $validated = $request->validate([
            'type' => ['required', 'string', 'in:performance_review,hr_communication,warning'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:10'],
            'strengths' => ['nullable', 'string'],
            'areas_for_improvement' => ['nullable', 'string'],
            'goals' => ['nullable', 'string'],
            'warning_level' => ['nullable', 'string', 'in:verbal,written,final'],
            'warning_date' => ['nullable', 'date'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
            'status' => ['nullable', 'string', 'in:draft,published'],
        ]);

        // Handle attachments
        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('candidate-appraisals/attachments', 'public');
                $attachmentPaths[] = $path;
            }
        }

        CandidateAppraisal::create([
            'candidate_id' => $candidate->id,
            'type' => $validated['type'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'rating' => $validated['rating'] ?? null,
            'strengths' => $validated['strengths'] ?? null,
            'areas_for_improvement' => $validated['areas_for_improvement'] ?? null,
            'goals' => $validated['goals'] ?? null,
            'warning_level' => $validated['warning_level'] ?? null,
            'warning_date' => $validated['warning_date'] ?? null,
            'attachments' => !empty($attachmentPaths) ? $attachmentPaths : null,
            'status' => $validated['status'] ?? 'published',
            'created_by_user_id' => auth()->id(),
        ]);

        return redirect()->route('admin.candidates.appraisals', $candidate)
            ->with('success', 'Appraisal created successfully.');
    }

    /**
     * Show the form for editing an appraisal.
     */
    public function edit(CandidateAppraisal $appraisal): View
    {
        $candidate = $appraisal->candidate;
        $user = auth()->user();
        
        // Check company access for clients
        if ($user && $user->isClient() && $user->company_id) {
            $hasAccess = $candidate->jobApplications()
                ->where('company_id', $user->company_id)
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You do not have permission to access this candidate.');
            }
        }

        return view('admin.candidates.appraisals-edit', compact('candidate', 'appraisal'));
    }

    /**
     * Update an appraisal.
     */
    public function update(Request $request, CandidateAppraisal $appraisal): RedirectResponse
    {
        $candidate = $appraisal->candidate;
        $user = auth()->user();
        
        // Check company access for clients
        if ($user && $user->isClient() && $user->company_id) {
            $hasAccess = $candidate->jobApplications()
                ->where('company_id', $user->company_id)
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You do not have permission to access this candidate.');
            }
        }

        $validated = $request->validate([
            'type' => ['required', 'string', 'in:performance_review,hr_communication,warning'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:10'],
            'strengths' => ['nullable', 'string'],
            'areas_for_improvement' => ['nullable', 'string'],
            'goals' => ['nullable', 'string'],
            'warning_level' => ['nullable', 'string', 'in:verbal,written,final'],
            'warning_date' => ['nullable', 'date'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
            'status' => ['nullable', 'string', 'in:draft,published'],
            'remove_attachments' => ['nullable', 'array'],
        ]);

        // Handle new attachments
        $attachmentPaths = $appraisal->attachments ?? [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('candidate-appraisals/attachments', 'public');
                $attachmentPaths[] = $path;
            }
        }

        // Handle removal of attachments
        if ($request->has('remove_attachments')) {
            foreach ($request->input('remove_attachments') as $pathToRemove) {
                if (in_array($pathToRemove, $attachmentPaths)) {
                    Storage::disk('public')->delete($pathToRemove);
                    $attachmentPaths = array_diff($attachmentPaths, [$pathToRemove]);
                }
            }
        }

        $appraisal->update([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'rating' => $validated['rating'] ?? null,
            'strengths' => $validated['strengths'] ?? null,
            'areas_for_improvement' => $validated['areas_for_improvement'] ?? null,
            'goals' => $validated['goals'] ?? null,
            'warning_level' => $validated['warning_level'] ?? null,
            'warning_date' => $validated['warning_date'] ?? null,
            'attachments' => !empty($attachmentPaths) ? array_values($attachmentPaths) : null,
            'status' => $validated['status'] ?? 'published',
        ]);

        return redirect()->route('admin.candidates.appraisals', $candidate)
            ->with('success', 'Appraisal updated successfully.');
    }

    /**
     * Delete an appraisal.
     */
    public function destroy(CandidateAppraisal $appraisal): RedirectResponse
    {
        $candidate = $appraisal->candidate;
        $user = auth()->user();
        
        // Check company access for clients
        if ($user && $user->isClient() && $user->company_id) {
            $hasAccess = $candidate->jobApplications()
                ->where('company_id', $user->company_id)
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You do not have permission to access this candidate.');
            }
        }

        // Delete attachments
        if ($appraisal->attachments) {
            foreach ($appraisal->attachments as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $appraisal->delete();

        return redirect()->route('admin.candidates.appraisals', $candidate)
            ->with('success', 'Appraisal deleted successfully.');
    }
}
