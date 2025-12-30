<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\SelfInterviewQuestion;
use App\Models\SelfInterviewSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SelfInterviewController extends Controller
{
    /**
     * Show the self‑interview for a job application.
     */
    public function show(JobApplication $application)
    {
        $candidate = Auth::guard('candidate')->user();
        $isCandidateView = $candidate !== null;

        if ($isCandidateView && $application->candidate_id !== $candidate->id) {
            abort(403, 'Unauthorized access to this application.');
        }

        // Require aptitude test to be passed before self interview
        if (! $application->aptitude_test_passed) {
            return redirect()->route('candidate.dashboard')
                ->with('error', 'You must complete and pass the aptitude test before starting the self interview.');
        }

        // If already completed, show results
        if ($application->self_interview_completed_at) {
            $session = $application->selfInterviewSession;
            if ($session && $session->completed_at) {
                $questionIds = array_keys($session->answers ?? []);
                $questions = SelfInterviewQuestion::whereIn('id', $questionIds)->get()->keyBy('id');
                return view('self-interview.results', compact('application', 'session', 'questions', 'isCandidateView'));
            }

            return view('self-interview.results', compact('application', 'isCandidateView'));
        }

        // Find or create session
        $session = $application->selfInterviewSession;

        if (! $session) {
            $jobPostId = $application->job_post_id;

            $questions = SelfInterviewQuestion::query()
                ->where(function ($q) use ($jobPostId) {
                    $q->whereNull('job_post_id');
                    if ($jobPostId) {
                        $q->orWhere('job_post_id', $jobPostId);
                    }
                })
                ->where('is_active', true)
                ->orderBy('display_order')
                ->get();

            if ($questions->isEmpty()) {
                return redirect()->route('candidate.dashboard')
                    ->with('error', 'Self interview questions are not configured for this position yet. Please contact support.');
            }

            $session = SelfInterviewSession::create([
                'job_application_id' => $application->id,
                'answers' => [],
                'pass_threshold' => 70,
                'started_at' => now(),
            ]);

            $questionIds = $questions->pluck('id')->toArray();
            session(['self_interview_questions_' . $session->id => $questionIds]);
        } else {
            $questionIds = session('self_interview_questions_' . $session->id, []);

            if (empty($questionIds)) {
                $jobPostId = $application->job_post_id;

                $questions = SelfInterviewQuestion::query()
                    ->where(function ($q) use ($jobPostId) {
                        $q->whereNull('job_post_id');
                        if ($jobPostId) {
                            $q->orWhere('job_post_id', $jobPostId);
                        }
                    })
                    ->where('is_active', true)
                    ->orderBy('display_order')
                    ->get();

                $questionIds = $questions->pluck('id')->toArray();
                session(['self_interview_questions_' . $session->id => $questionIds]);
            }
        }

        if (empty($questionIds)) {
            return redirect()->route('candidate.dashboard')
                ->with('error', 'Unable to load self interview questions. Please contact support.');
        }

        $questions = SelfInterviewQuestion::whereIn('id', $questionIds)
            ->orderByRaw('FIELD(id, ' . implode(',', $questionIds) . ')')
            ->get();

        return view('self-interview.take', compact('application', 'session', 'questions', 'isCandidateView'));
    }

    /**
     * Submit self‑interview answers.
     */
    public function submit(Request $request, JobApplication $application)
    {
        $candidate = Auth::guard('candidate')->user();
        $isCandidateView = $candidate !== null;

        if ($isCandidateView && $application->candidate_id !== $candidate->id) {
            abort(403, 'Unauthorized access to this application.');
        }

        if (! $application->aptitude_test_passed) {
            return redirect()->route('candidate.dashboard')
                ->with('error', 'You must complete and pass the aptitude test before submitting the self interview.');
        }

        $session = $application->selfInterviewSession;

        if (! $session) {
            return redirect()->route('candidate.dashboard')
                ->with('error', 'Self interview session not found.');
        }

        if ($session->completed_at) {
            return redirect()->route('self-interview.results', $application)
                ->with('info', 'You have already completed this self interview.');
        }

        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'nullable|string|max:255',
        ]);

        $timeTaken = $session->started_at ? now()->diffInSeconds($session->started_at) : null;

        $session->update([
            'answers' => $validated['answers'],
            'completed_at' => now(),
            'time_taken_seconds' => $timeTaken,
        ]);

        $session->calculateScore();

        return redirect()->route('self-interview.results', $application)
            ->with('success', 'Self interview submitted successfully!');
    }

    public function results(JobApplication $application)
    {
        $candidate = Auth::guard('candidate')->user();
        $isCandidateView = $candidate !== null;

        if ($isCandidateView && $application->candidate_id !== $candidate->id) {
            abort(403, 'Unauthorized access to this application.');
        }

        $session = $application->selfInterviewSession;

        if (! $session || ! $session->completed_at) {
            return redirect()->route('self-interview.show', $application);
        }

        $questionIds = array_keys($session->answers ?? []);
        $questions = SelfInterviewQuestion::whereIn('id', $questionIds)->get()->keyBy('id');

        return view('self-interview.results', compact('application', 'session', 'questions', 'isCandidateView'));
    }
}


