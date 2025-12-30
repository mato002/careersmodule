<?php

namespace App\Http\Controllers;

use App\Models\AptitudeTestQuestion;
use App\Models\AptitudeTestSession;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AptitudeTestController extends Controller
{
    /**
     * Show the aptitude test for a job application
     */
    public function show(JobApplication $application)
    {
        // Check if candidate is authenticated
        $candidate = \Illuminate\Support\Facades\Auth::guard('candidate')->user();
        $isCandidateView = $candidate !== null;
        
        // If candidate is authenticated, verify the application belongs to them
        if ($isCandidateView && $application->candidate_id !== $candidate->id) {
            abort(403, 'Unauthorized access to this application.');
        }
        
        // Verify the application has passed sieving
        if (!in_array($application->status, ['sieving_passed', 'pending_manual_review'])) {
            if ($isCandidateView) {
                return redirect()->route('candidate.dashboard')
                    ->with('error', 'You are not eligible to take the aptitude test at this time.');
            }
            return redirect()->route('careers.index')
                ->with('error', 'You are not eligible to take the aptitude test at this time.');
        }

        // Check if test already completed
        if ($application->aptitude_test_completed_at) {
            $session = $application->aptitudeTestSession;
            if ($session && $session->completed_at) {
                $questionIds = array_keys($session->questions_answered ?? []);
                $questions = AptitudeTestQuestion::whereIn('id', $questionIds)->get()->keyBy('id');
                return view('aptitude-test.results', compact('application', 'session', 'questions', 'isCandidateView'));
            }
            // If no session but test is marked as completed, show basic results
            return view('aptitude-test.results', compact('application', 'isCandidateView'));
        }

        // Check if test session exists
        $session = $application->aptitudeTestSession;

        if (!$session) {
            // Create new test session - use job-specific questions
            $jobPostId = $application->job_post_id;
            $companyId = $application->company_id;
            $questions = AptitudeTestQuestion::getTestQuestions($jobPostId, $companyId);
            $allQuestions = collect($questions['numerical'])
                ->merge($questions['logical'])
                ->merge($questions['verbal'])
                ->merge($questions['scenario'])
                ->shuffle();

            $session = AptitudeTestSession::create([
                'job_application_id' => $application->id,
                'questions_answered' => [],
                'pass_threshold' => 70,
                'started_at' => now(),
            ]);

            // Store question IDs for this session
            $questionIds = $allQuestions->pluck('id')->toArray();
            session(['test_questions_' . $session->id => $questionIds]);
        } else {
            // Get question IDs from session
            $questionIds = session('test_questions_' . $session->id, []);
            
            // If session data expired or is empty, regenerate questions
            if (empty($questionIds)) {
                $jobPostId = $application->job_post_id;
                $companyId = $application->company_id;
                $questions = AptitudeTestQuestion::getTestQuestions($jobPostId, $companyId);
                $allQuestions = collect($questions['numerical'])
                    ->merge($questions['logical'])
                    ->merge($questions['verbal'])
                    ->merge($questions['scenario'])
                    ->shuffle();
                
                $questionIds = $allQuestions->pluck('id')->toArray();
                session(['test_questions_' . $session->id => $questionIds]);
            }
        }

        // Ensure we have question IDs before querying
        if (empty($questionIds)) {
            return redirect()->route('careers.index')
                ->with('error', 'Unable to load test questions. Please contact support.');
        }

        $questions = AptitudeTestQuestion::whereIn('id', $questionIds)
            ->orderByRaw('FIELD(id, ' . implode(',', $questionIds) . ')')
            ->get();

        return view('aptitude-test.take', compact('application', 'session', 'questions', 'isCandidateView'));
    }

    /**
     * Submit aptitude test answers
     */
    public function submit(Request $request, JobApplication $application)
    {
        // Check if candidate is authenticated
        $candidate = \Illuminate\Support\Facades\Auth::guard('candidate')->user();
        $isCandidateView = $candidate !== null;
        
        // If candidate is authenticated, verify the application belongs to them
        if ($isCandidateView && $application->candidate_id !== $candidate->id) {
            abort(403, 'Unauthorized access to this application.');
        }
        
        $session = $application->aptitudeTestSession;

        if (!$session) {
            if ($isCandidateView) {
                return redirect()->route('candidate.dashboard')
                    ->with('error', 'Test session not found.');
            }
            return redirect()->route('careers.index')
                ->with('error', 'Test session not found.');
        }

        if ($session->completed_at) {
            return redirect()->route('aptitude-test.results', $application)
                ->with('info', 'You have already completed this test.');
        }

        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'nullable|string|max:1',
        ]);

        // Calculate time taken
        $timeTaken = $session->started_at ? now()->diffInSeconds($session->started_at) : null;

        // Store answers
        $session->update([
            'questions_answered' => $validated['answers'],
            'completed_at' => now(),
            'time_taken_seconds' => $timeTaken,
        ]);

        // Calculate score
        $session->calculateScore();

        // Refresh application to get updated aptitude_test_passed field
        $application->refresh();

        // Redirect based on authentication
        if ($isCandidateView) {
            // If candidate passed aptitude, move them straight to self interview
            if ($application->aptitude_test_passed) {
                return redirect()->route('self-interview.show', $application)
                    ->with('success', 'Aptitude test submitted successfully. Next, complete your self interview.');
            }

            // Otherwise show aptitude results
            return redirect()->route('aptitude-test.results', $application)
                ->with('success', 'Test submitted successfully!');
        }
        
        return redirect()->route('aptitude-test.results', $application)
            ->with('success', 'Test submitted successfully!');
    }

    /**
     * Show test results
     */
    public function results(JobApplication $application)
    {
        // Check if candidate is authenticated
        $candidate = \Illuminate\Support\Facades\Auth::guard('candidate')->user();
        $isCandidateView = $candidate !== null;
        
        // If candidate is authenticated, verify the application belongs to them
        if ($isCandidateView && $application->candidate_id !== $candidate->id) {
            abort(403, 'Unauthorized access to this application.');
        }
        
        $session = $application->aptitudeTestSession;

        if (!$session || !$session->completed_at) {
            return redirect()->route('aptitude-test.show', $application);
        }

        // Get questions with answers
        $questionIds = array_keys($session->questions_answered ?? []);
        $questions = AptitudeTestQuestion::whereIn('id', $questionIds)->get()->keyBy('id');

        return view('aptitude-test.results', compact('application', 'session', 'questions', 'isCandidateView'));
    }

    /**
     * Verify access token (for email links)
     */
    public function verify(Request $request, JobApplication $application)
    {
        $token = $request->query('token');
        $expectedToken = md5($application->email . $application->id . config('app.key'));

        if ($token !== $expectedToken) {
            abort(403, 'Invalid access token.');
        }

        return redirect()->route('aptitude-test.show', $application);
    }
}

