<?php

namespace App\Http\Controllers;

use App\Mail\JobApplicationConfirmation;
use App\Mail\JobApplicationReceived;
use App\Mail\CandidateAccountCreated;
use App\Models\GeneralSetting;
use App\Models\JobApplication;
use App\Models\JobPost;
use App\Services\AISievingService;
use App\Jobs\ProcessCvJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class JobApplicationController extends Controller
{
    public function create(JobPost $jobPost)
    {
        $job = $jobPost;
        
        if (!$job->is_active) {
            abort(404);
        }

        // Check if application deadline has passed
        if ($job->application_deadline && $job->application_deadline->isPast()) {
            return redirect()->route('careers.show', $job->slug)
                ->with('error', 'The application deadline for this position has passed.');
        }

        return view('careers.apply', compact('job'));
    }

    public function store(Request $request, JobPost $jobPost)
    {
        $job = $jobPost;
        
        // Check if job is active
        if (!$job->is_active) {
            return redirect()->route('careers.index')
                ->with('error', 'This job posting is no longer available.');
        }
        
        // Check if application deadline has passed
        if ($job->application_deadline && $job->application_deadline->isPast()) {
            return redirect()->route('careers.show', $job->slug)
                ->with('error', 'The application deadline for this position has passed. Applications are no longer being accepted.');
        }
        
        $validated = $request->validate([
            // Page 1
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\']+$/',
            'phone' => ['required', 'string', 'regex:/^\+[1-9]\d{1,14}$/', 'max:20'],
            'email' => 'required|email|max:255',
            // Job-related questions
            'why_interested' => 'required|string',
            'why_good_fit' => 'required|string',
            'career_goals' => 'required|string',
            'salary_expectations' => 'nullable|string|max:255',
            'availability_date' => 'required|date',
            'relevant_skills' => 'required|string',
            'education_level' => 'nullable|string|max:255',
            'area_of_study' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'education_status' => 'nullable|string|max:255',
            'education_start_year' => 'nullable|integer|min:1950|max:' . (date('Y') + 5),
            'education_end_year' => 'nullable|integer|min:1950|max:' . date('Y'),
            'education_expected_completion_year' => 'nullable|integer|min:' . date('Y') . '|max:' . (date('Y') + 10),
            'other_achievements' => 'nullable|string',
            'work_experience' => 'nullable|array',
            'current_job_title' => 'nullable|string|max:255',
            'current_company' => 'nullable|string|max:255',
            'currently_working' => 'boolean',
            'duties_and_responsibilities' => 'nullable|string',
            'other_experiences' => 'nullable|string',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
            
            // Page 3: Support Details
            'support_details' => 'nullable|string',
            'certifications' => 'nullable|string',
            'languages' => 'nullable|string',
            'professional_memberships' => 'nullable|string',
            'awards_recognition' => 'nullable|string',
            'portfolio_links' => 'nullable|string',
            'availability_travel' => 'nullable|string|in:Yes,No,Limited',
            'availability_relocation' => 'nullable|string|in:Yes,No,Maybe',
            
            // Page 3
            'referrers' => 'nullable|array',
            'notice_period' => 'nullable|string|max:255',
            'agreement_accepted' => 'required|accepted',
            
            // Application message
            'application_message' => 'nullable|string',
        ], [
            'name.regex' => 'Name should only contain letters, spaces, hyphens, and apostrophes.',
            'phone.regex' => 'Phone number must include country code starting with + (e.g., +254712345678).',
            'phone.required' => 'Phone number is required.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        // Check for duplicate application (same email, phone, and job post)
        $existingApplication = JobApplication::where('email', $validated['email'])
            ->where('phone', $validated['phone'])
            ->where('job_post_id', $job->id)
            ->first();

        if ($existingApplication) {
            return redirect()->route('careers.show', $job->slug)
                ->with('error', 'You have already submitted an application for this position. Please check your email for confirmation or contact us if you need to update your application.')
                ->withInput();
        }

        // Handle CV upload
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('job-applications/cvs', 'public');
            $validated['cv_path'] = $cvPath;
        }

        $validated['job_post_id'] = $job->id;
        $validated['status'] = 'pending';
        
        // Auto-assign company_id from job post
        if ($job->company_id) {
            $validated['company_id'] = $job->company_id;
        }

        // Generate AI summary (placeholder - you can integrate actual AI service)
        $validated['ai_summary'] = $this->generateAISummary($validated);
        $validated['ai_details'] = $this->generateAIDetails($validated);

        try {
            // Link to candidate if authenticated
            $candidate = auth()->guard('candidate')->user();
            if ($candidate) {
                $validated['candidate_id'] = $candidate->id;
            }
            
            $application = JobApplication::create($validated);
            
            // Record initial status in history
            \App\Models\JobApplicationStatusHistory::create([
                'job_application_id' => $application->id,
                'previous_status' => null,
                'new_status' => 'pending',
                'changed_by' => null, // System/Application creation
                'source' => 'application_submission',
                'notes' => 'Application submitted',
            ]);
            
            // Auto-create candidate account if not exists and not already authenticated
            if (!$candidate) {
                $candidate = $this->createOrLinkCandidateAccount($application);
                if ($candidate) {
                    $application->update(['candidate_id' => $candidate->id]);
                }
            } elseif (!$application->candidate_id && $candidate->email === $application->email) {
                // If candidate is authenticated, ensure the application is linked
                $application->update(['candidate_id' => $candidate->id]);
            }
            
            // Dispatch CV processing job (async)
            if ($application->cv_path) {
                ProcessCvJob::dispatch($application);
            }
            
            // Run AI sieving evaluation (after CV processing if possible, or async)
            try {
                $sievingService = new AISievingService();
                $sievingService->evaluate($application);
            } catch (\Exception $e) {
                // Log error but don't fail application submission
                \Log::error('AI Sieving evaluation failed', [
                    'application_id' => $application->id,
                    'error' => $e->getMessage(),
                ]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Catch database unique constraint violation as a fallback
            if ($e->getCode() === '23000' || str_contains($e->getMessage(), 'Duplicate entry') || str_contains($e->getMessage(), 'unique_application_per_job')) {
                return redirect()->route('careers.show', $job->slug)
                    ->with('error', 'You have already submitted an application for this position. Please check your email for confirmation or contact us if you need to update your application.')
                    ->withInput();
            }
            throw $e;
        }

        // Send confirmation email to the applicant
        $this->sendConfirmationEmail($application);

        // Notify admin team
        $this->notifyTeam($application);

        // Redirect to application status page with token
        $token = md5($application->email . $application->id . config('app.key'));
        return redirect()->route('application.status', ['application' => $application->id, 'token' => $token])
            ->with('success', 'Your application has been submitted successfully!');
    }

    /**
     * Show application status to candidate
     */
    public function status(Request $request, JobApplication $application)
    {
        // Verify token for security
        $token = $request->query('token');
        $expectedToken = md5($application->email . $application->id . config('app.key'));
        
        if ($token !== $expectedToken) {
            // If no token, show lookup form
            if (!$token) {
                return view('careers.application-status-lookup');
            }
            abort(403, 'Invalid access token.');
        }

        // Load relationships
        $application->load(['jobPost', 'aiSievingDecision', 'aptitudeTestSession']);

        return view('careers.application-status', compact('application'));
    }

    /**
     * Lookup application by email and phone
     */
    public function lookup(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        $application = JobApplication::where('email', $validated['email'])
            ->where('phone', $validated['phone'])
            ->latest()
            ->first();

        if (!$application) {
            return back()->with('error', 'No application found with the provided email and phone number.');
        }

        // Generate token and redirect
        $token = md5($application->email . $application->id . config('app.key'));
        return redirect()->route('application.status', ['application' => $application->id, 'token' => $token]);
    }

    private function generateAISummary(array $data): string
    {
        // Placeholder AI summary generation
        $summary = "Application from {$data['name']}";
        if (!empty($data['education_level'])) {
            $summary .= " with {$data['education_level']} in {$data['area_of_study']}";
        }
        if (!empty($data['current_job_title'])) {
            $summary .= ". Currently working as {$data['current_job_title']} at {$data['current_company']}";
        }
        return $summary;
    }

    private function generateAIDetails(array $data): string
    {
        // Placeholder AI details generation
        $details = "Education: {$data['education_level']} in {$data['area_of_study']}\n";
        $details .= "Institution: {$data['institution']}\n";
        if (!empty($data['work_experience'])) {
            $details .= "Work Experience: " . json_encode($data['work_experience']) . "\n";
        }
        return $details;
    }

    protected function sendConfirmationEmail(JobApplication $application): void
    {
        if (! $application->email) {
            return;
        }

        Mail::to($application->email)->send(new JobApplicationConfirmation($application));
    }

    /**
     * Create or link candidate account for application
     */
    protected function createOrLinkCandidateAccount(JobApplication $application): ?\App\Models\Candidate
    {
        // Check if candidate already exists
        $candidate = \App\Models\Candidate::where('email', $application->email)->first();
        
        if ($candidate) {
            // Candidate exists, just link the application
            return $candidate;
        }
        
        // Create new candidate account
        $temporaryPassword = \Illuminate\Support\Str::random(12); // Generate secure random password
        
        try {
            $candidate = \App\Models\Candidate::create([
                'name' => $application->name,
                'email' => $application->email,
                'password' => \Illuminate\Support\Facades\Hash::make($temporaryPassword),
                'email_verified_at' => now(), // Auto-verify since they applied
            ]);
            
            // Send account creation email with login credentials
            try {
                \Illuminate\Support\Facades\Mail::to($candidate->email)
                    ->send(new \App\Mail\CandidateAccountCreated($candidate, $temporaryPassword));
            } catch (\Exception $e) {
                \Log::error('Failed to send candidate account creation email', [
                    'candidate_id' => $candidate->id,
                    'error' => $e->getMessage(),
                ]);
            }
            
            return $candidate;
        } catch (\Exception $e) {
            \Log::error('Failed to create candidate account', [
                'email' => $application->email,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    protected function notifyTeam(JobApplication $application): void
    {
        // Get recipients from GeneralSettings
        $generalSettings = GeneralSetting::query()->latest()->first();
        
        $recipients = [];
        if ($generalSettings && $generalSettings->job_notification_recipients) {
            $recipients = collect(explode(',', $generalSettings->job_notification_recipients))
                ->map(fn ($email) => trim($email))
                ->filter()
                ->unique()
                ->values()
                ->all();
        }

        // Fallback to config if no database settings
        if (empty($recipients)) {
            $recipients = config('job.notification_recipients', []);
        }

        if (empty($recipients)) {
            return;
        }

        try {
            Mail::to($recipients)->send(new JobApplicationReceived($application));
        } catch (\Exception $e) {
            // Log error but don't fail the application submission
            \Log::error('Failed to send job application notification', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

