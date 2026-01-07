<?php

namespace App\Services;

use App\Models\JobApplication;
use App\Models\CvParsedData;
use App\Models\JobPost;
use App\Models\Company;
use App\Models\AIPrompt;
use App\Services\TokenService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class AIAnalysisService
{
    protected string $apiProvider;
    protected ?string $apiKey;
    protected ?string $apiUrl;
    protected TokenService $tokenService;

    public function __construct(TokenService $tokenService = null)
    {
        $this->apiProvider = config('ai.provider', 'openai'); // openai, anthropic, local
        $this->apiKey = config('ai.api_key') ?: env('OPENAI_API_KEY') ?: null;
        $this->apiUrl = config('ai.api_url') ?: null;
        $this->tokenService = $tokenService ?? app(TokenService::class);
    }

    /**
     * Analyze CV and generate summary
     */
    public function analyzeCv(JobApplication $application): array
    {
        $cvParsedData = $application->cvParsedData;
        
        // Check if CV is empty or too minimal
        if (!$cvParsedData || empty($cvParsedData->raw_text)) {
            Log::warning('No CV parsed data available for analysis', [
                'application_id' => $application->id
            ]);
            return $this->generateFallbackSummary($application, true); // Mark as empty CV
        }
        
        // Check if CV content is meaningful (not just whitespace or very short)
        $rawText = trim($cvParsedData->raw_text);
        if (strlen($rawText) < 100) {
            Log::warning('CV content is too minimal', [
                'application_id' => $application->id,
                'text_length' => strlen($rawText)
            ]);
            return $this->generateFallbackSummary($application, true); // Mark as empty/minimal CV
        }

        try {
            $companyId = $this->getCompanyId($application);
            Log::info('AI CV Analysis - Company ID resolved', [
                'application_id' => $application->id,
                'company_id' => $companyId,
                'application_company_id' => $application->company_id,
                'job_post_company_id' => $application->jobPost?->company_id,
            ]);
            
            $prompt = $this->buildAnalysisPrompt($application, $cvParsedData);
            $response = $this->callAI($prompt, $companyId, $application->id, 'cv_analyze');
            
            return $this->parseAIResponse($response);
        } catch (\Exception $e) {
            Log::error('AI CV analysis failed', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            return $this->generateFallbackSummary($application);
        }
    }
    
    /**
     * Check if CV content matches application form data
     */
    public function compareCvWithApplication(JobApplication $application): array
    {
        $cvParsedData = $application->cvParsedData;
        $mismatches = [];
        
        if (!$cvParsedData || empty($cvParsedData->raw_text)) {
            return ['cv_empty' => true, 'mismatches' => []];
        }
        
        $cvText = strtolower($cvParsedData->raw_text);
        
        // Compare name
        if (!empty($application->name)) {
            $nameParts = explode(' ', strtolower($application->name));
            $nameFound = false;
            foreach ($nameParts as $part) {
                if (strlen($part) > 2 && strpos($cvText, $part) !== false) {
                    $nameFound = true;
                    break;
                }
            }
            if (!$nameFound) {
                $mismatches[] = "Name in CV does not match application form";
            }
        }
        
        // Compare email
        if (!empty($application->email)) {
            $emailDomain = substr(strrchr($application->email, "@"), 1);
            if (strpos($cvText, strtolower($application->email)) === false && 
                strpos($cvText, strtolower($emailDomain)) === false) {
                $mismatches[] = "Email in CV does not match application form";
            }
        }
        
        // Compare education
        if (!empty($application->education_level)) {
            $educationFound = false;
            $educationKeywords = [
                'bachelor' => ['bachelor', 'bsc', 'ba', 'bs'],
                'master' => ['master', 'msc', 'ma', 'ms', 'mba'],
                'diploma' => ['diploma', 'certificate'],
                'phd' => ['phd', 'doctorate', 'doctoral'],
            ];
            
            foreach ($educationKeywords as $level => $keywords) {
                if (stripos($application->education_level, $level) !== false) {
                    foreach ($keywords as $keyword) {
                        if (strpos($cvText, $keyword) !== false) {
                            $educationFound = true;
                            break 2;
                        }
                    }
                }
            }
            
            if (!$educationFound && strlen($application->education_level) > 3) {
                $mismatches[] = "Education level in CV does not match application form";
            }
        }
        
        // Compare skills
        if (!empty($application->relevant_skills)) {
            $skills = explode(',', $application->relevant_skills);
            $skillsFound = 0;
            foreach ($skills as $skill) {
                $skill = trim(strtolower($skill));
                if (strlen($skill) > 3 && strpos($cvText, $skill) !== false) {
                    $skillsFound++;
                }
            }
            
            // If less than 30% of skills are found in CV, it's a mismatch
            if (count($skills) > 0 && ($skillsFound / count($skills)) < 0.3) {
                $mismatches[] = "Skills listed in application form are not found in CV";
            }
        }
        
        // Compare current position
        if (!empty($application->current_job_title)) {
            $titleParts = explode(' ', strtolower($application->current_job_title));
            $titleFound = false;
            foreach ($titleParts as $part) {
                if (strlen($part) > 3 && strpos($cvText, $part) !== false) {
                    $titleFound = true;
                    break;
                }
            }
            if (!$titleFound && strlen($application->current_job_title) > 5) {
                $mismatches[] = "Current position in CV does not match application form";
            }
        }
        
        return [
            'cv_empty' => false,
            'mismatches' => $mismatches,
            'cv_length' => strlen($cvParsedData->raw_text)
        ];
    }

    /**
     * Analyze application and match to job requirements
     */
    public function analyzeApplication(JobApplication $application): array
    {
        $jobPost = $application->jobPost;
        
        if (!$jobPost) {
            return [];
        }

        try {
            $companyId = $this->getCompanyId($application);
            Log::info('AI Application Analysis - Company ID resolved', [
                'application_id' => $application->id,
                'company_id' => $companyId,
                'application_company_id' => $application->company_id,
                'job_post_company_id' => $jobPost?->company_id,
            ]);
            
            $prompt = $this->buildApplicationAnalysisPrompt($application, $jobPost);
            $response = $this->callAI($prompt, $companyId, $application->id, 'scoring');
            
            return $this->parseApplicationAnalysisResponse($response);
        } catch (\Exception $e) {
            Log::error('AI application analysis failed', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Generate candidate profile summary
     */
    public function generateProfileSummary(JobApplication $application): string
    {
        $cvParsedData = $application->cvParsedData;
        $jobPost = $application->jobPost;
        
        if (!$cvParsedData) {
            return $this->generateBasicSummary($application);
        }

        try {
            $companyId = $this->getCompanyId($application);
            $prompt = $this->buildProfileSummaryPrompt($application, $cvParsedData, $jobPost);
            $response = $this->callAI($prompt, $companyId, $application->id, 'cv_analyze');
            
            return $this->extractTextFromResponse($response);
        } catch (\Exception $e) {
            Log::error('AI profile summary generation failed', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            return $this->generateBasicSummary($application);
        }
    }

    /**
     * Match candidate skills to job requirements
     */
    public function matchSkillsToJob(JobApplication $application): array
    {
        $jobPost = $application->jobPost;
        $cvParsedData = $application->cvParsedData;
        
        if (!$jobPost || !$cvParsedData) {
            return [];
        }

        try {
            $prompt = $this->buildSkillMatchingPrompt($application, $cvParsedData, $jobPost);
            $response = $this->callAI($prompt);
            
            return $this->parseSkillMatchingResponse($response);
        } catch (\Exception $e) {
            Log::error('AI skill matching failed', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Build analysis prompt for CV
     */
    private function buildAnalysisPrompt(JobApplication $application, CvParsedData $cvParsedData): string
    {
        $user = auth()->user();
        $role = $this->getUserRole($user);
        
        // Try to get stored prompt
        $storedPrompt = AIPrompt::getPrompt('cv_analysis', $role);
        if ($storedPrompt) {
            $template = $storedPrompt->content;
        } else {
            // Default template
            $template = "Analyze the following CV/resume and provide a comprehensive summary.\n\n" .
                       "Job Position: {job_post->title}\n" .
                       "Job Requirements: {job_post->requirements}\n\n" .
                       "CV Content:\n{cv_parsed_data->raw_text}\n\n" .
                       "Please provide:\n" .
                       "1. A concise summary of the candidate's background (2-3 sentences)\n" .
                       "2. Key strengths and relevant experience\n" .
                       "3. Education highlights\n" .
                       "4. Notable skills and certifications\n" .
                       "5. Overall assessment for the position\n\n" .
                       "Format your response as JSON with keys: summary, strengths, education_highlights, skills, assessment.";
        }
        
        // Replace placeholders
        $jobPost = $application->jobPost;
        $prompt = $template;
        $prompt = str_replace('{job_post->title}', $jobPost?->title ?? 'N/A', $prompt);
        $prompt = str_replace('{job_post->requirements}', $jobPost?->requirements ?? 'N/A', $prompt);
        $prompt = str_replace('{cv_parsed_data->raw_text}', $cvParsedData->raw_text ?? '', $prompt);

        return $prompt;
    }

    /**
     * Build application analysis prompt
     */
    private function buildApplicationAnalysisPrompt(JobApplication $application, JobPost $jobPost): string
    {
        $user = auth()->user();
        $role = $this->getUserRole($user);
        
        // Try to get stored prompt
        $storedPrompt = AIPrompt::getPrompt('application_analysis', $role);
        if ($storedPrompt) {
            $template = $storedPrompt->content;
        } else {
            // Default template (will be replaced with placeholders)
            $template = $this->getDefaultApplicationAnalysisTemplate();
        }
        
        // Replace placeholders with actual data
        $cvComparison = $this->compareCvWithApplication($application);
        $cvParsedData = $application->cvParsedData;
        $cvLength = $cvParsedData ? strlen(trim($cvParsedData->raw_text)) : 0;
        $cvPreview = $cvParsedData ? substr(trim($cvParsedData->raw_text), 0, 500) : '';
        $mismatches = !empty($cvComparison['mismatches']) ? implode("\n- ", $cvComparison['mismatches']) : '';
        
        $prompt = $template;
        $prompt = str_replace('{job_post->title}', $jobPost->title ?? 'N/A', $prompt);
        $prompt = str_replace('{job_post->description}', $jobPost->description ?? 'N/A', $prompt);
        $prompt = str_replace('{job_post->requirements}', $jobPost->requirements ?? 'N/A', $prompt);
        $prompt = str_replace('{application->name}', $application->name ?? 'N/A', $prompt);
        $prompt = str_replace('{application->email}', $application->email ?? 'N/A', $prompt);
        $prompt = str_replace('{application->education_level}', $application->education_level ?? 'N/A', $prompt);
        $prompt = str_replace('{application->area_of_study}', $application->area_of_study ?? 'N/A', $prompt);
        $prompt = str_replace('{application->current_job_title}', $application->current_job_title ?? 'N/A', $prompt);
        $prompt = str_replace('{application->current_company}', $application->current_company ?? 'N/A', $prompt);
        $prompt = str_replace('{application->relevant_skills}', $application->relevant_skills ?? 'N/A', $prompt);
        $prompt = str_replace('{application->why_interested}', $application->why_interested ?? 'N/A', $prompt);
        $prompt = str_replace('{application->why_good_fit}', $application->why_good_fit ?? 'N/A', $prompt);
        $prompt = str_replace('{application->career_goals}', $application->career_goals ?? 'N/A', $prompt);
        $prompt = str_replace('{cv_length}', (string)$cvLength, $prompt);
        $prompt = str_replace('{list of mismatches}', $mismatches ? "- " . $mismatches : 'None', $prompt);
        $prompt = str_replace('{cv_preview}', $cvPreview, $prompt);
        
        // Handle CV empty condition
        if ($cvComparison['cv_empty']) {
            $prompt = str_replace('CV Content Analysis:', "⚠️ CRITICAL: CV IS EMPTY OR TOO MINIMAL!\nThe uploaded CV contains no meaningful content (less than 100 characters).\nThis is a MAJOR RED FLAG - penalize heavily (score should be 0-20).\n\nCV Content Analysis:", $prompt);
        } elseif (!$cvParsedData) {
            $prompt = str_replace('CV Content Analysis:', "⚠️ WARNING: No CV was uploaded or CV parsing failed.\nThis should be penalized as the candidate did not provide a CV.\n\nCV Content Analysis:", $prompt);
        }

        return $prompt;
    }

    /**
     * Get default application analysis template
     */
    private function getDefaultApplicationAnalysisTemplate(): string
    {
        return "You are an expert HR analyst evaluating a job application. Be CRITICAL and apply human intelligence.\n\n" .
               "IMPORTANT: Detect and penalize:\n" .
               "- Gibberish, random characters, or nonsensical text (e.g., 'ghcgfhcfg', 'abc123xyz')\n" .
               "- Meaningless or placeholder responses\n" .
               "- Responses that don't answer the question\n" .
               "- Very brief or generic responses without substance\n" .
               "- Copy-pasted or template-like answers\n" .
               "- EMPTY or MINIMAL CVs (less than 100 characters of meaningful content)\n" .
               "- CV content that DOES NOT MATCH application form data (name, email, education, skills, experience)\n\n" .
               "Job Position: {job_post->title}\n" .
               "Job Description: {job_post->description}\n" .
               "Job Requirements: {job_post->requirements}\n\n" .
               "Application Form Data:\n" .
               "Name: {application->name}\n" .
               "Email: {application->email}\n" .
               "Education: {application->education_level} in {application->area_of_study}\n" .
               "Current Position: {application->current_job_title} at {application->current_company}\n" .
               "Skills: {application->relevant_skills}\n" .
               "Why Interested: {application->why_interested}\n" .
               "Why Good Fit: {application->why_good_fit}\n" .
               "Career Goals: {application->career_goals}\n\n" .
               "CV Content Analysis:\n" .
               "- CV Length: {cv_length} characters\n" .
               "⚠️ CRITICAL MISMATCHES DETECTED (if any):\n" .
               "- {list of mismatches}\n" .
               "CV Content Preview (first 500 chars):\n{cv_preview}\n\n" .
               "CRITICAL EVALUATION INSTRUCTIONS:\n" .
               "1. First, check if CV is EMPTY or MINIMAL - if so, score should be 0-20 and recommend REJECT.\n" .
               "2. Check if CV content MATCHES application form data - mismatches are RED FLAGS.\n" .
               "3. Check if responses are MEANINGFUL and RELEVANT. If they contain gibberish, random characters, or are nonsensical, mark them as weaknesses and reduce the score significantly.\n" .
               "4. Only list items as 'matching_points' (strengths) if they are:\n" .
               "   - Genuinely relevant to the job\n" .
               "   - Meaningful and well-articulated\n" .
               "   - Demonstrate real understanding or experience\n" .
               "   - VERIFIED in the CV (if CV is provided)\n" .
               "5. If CV is empty, minimal, or doesn't match form data, add to 'missing_requirements' as a MAJOR weakness.\n" .
               "6. Be strict with scoring:\n" .
               "   - Empty/minimal CV = 0-20 score\n" .
               "   - CV mismatch = 20-40 score\n" .
               "   - Meaningless/gibberish responses = 0-30 score\n" .
               "   - Good responses with matching CV = 70-100 score\n" .
               "7. Only recommend 'pass' if CV is provided, matches form data, and responses are genuinely good.\n\n" .
               "Please provide:\n" .
               "1. Match score (0-100) - be strict based on CV quality and response quality\n" .
               "2. Key matching points (ONLY if CV is good and responses are meaningful)\n" .
               "3. Missing requirements or gaps (include: empty CV, CV mismatches, gibberish responses)\n" .
               "4. Recommendation (pass/reject/manual_review) - reject if CV is empty/minimal or responses are meaningless\n" .
               "5. Confidence level (0-1)\n\n" .
               "Format your response as JSON with keys: match_score, matching_points, missing_requirements, recommendation, confidence.";
    }

    /**
     * Build profile summary prompt
     */
    private function buildProfileSummaryPrompt(JobApplication $application, CvParsedData $cvParsedData, ?JobPost $jobPost): string
    {
        $user = auth()->user();
        $role = $this->getUserRole($user);
        
        // Try to get stored prompt
        $storedPrompt = AIPrompt::getPrompt('profile_summary', $role);
        if ($storedPrompt) {
            $template = $storedPrompt->content;
        } else {
            // Default template
            $template = "Generate a professional candidate profile summary based on the following information:\n\n" .
                       "Candidate: {application->name}\n" .
                       "Email: {application->email}\n\n" .
                       "Applied for: {job_post->title}\n\n" .
                       "CV Content:\n{cv_parsed_data->raw_text}\n\n" .
                       "Create a 3-4 sentence professional summary highlighting:\n" .
                       "- Professional background and experience\n" .
                       "- Key skills and qualifications\n" .
                       "- Notable achievements or strengths\n" .
                       "- Relevance to the position (if job post provided)\n\n" .
                       "Write in third person, professional tone.";
        }
        
        // Replace placeholders
        $prompt = $template;
        $prompt = str_replace('{application->name}', $application->name ?? 'N/A', $prompt);
        $prompt = str_replace('{application->email}', $application->email ?? 'N/A', $prompt);
        $prompt = str_replace('{job_post->title}', $jobPost?->title ?? 'N/A', $prompt);
        $prompt = str_replace('{cv_parsed_data->raw_text}', $cvParsedData->raw_text ?? '', $prompt);

        return $prompt;
    }

    /**
     * Build skill matching prompt
     */
    private function buildSkillMatchingPrompt(JobApplication $application, CvParsedData $cvParsedData, JobPost $jobPost): string
    {
        $user = auth()->user();
        $role = $this->getUserRole($user);
        
        // Try to get stored prompt
        $storedPrompt = AIPrompt::getPrompt('skill_matching', $role);
        if ($storedPrompt) {
            $template = $storedPrompt->content;
        } else {
            // Default template
            $template = "Match the candidate's skills to the job requirements.\n\n" .
                       "Job Position: {job_post->title}\n" .
                       "Job Requirements: {job_post->requirements}\n\n" .
                       "Candidate Skills:\n" .
                       "Technical: {technical_skills}\n" .
                       "Soft: {soft_skills}\n" .
                       "Additional Skills from Application: {application->relevant_skills}\n\n" .
                       "Provide:\n" .
                       "1. Matching skills (skills that match job requirements)\n" .
                       "2. Missing skills (required skills not found)\n" .
                       "3. Bonus skills (additional valuable skills)\n" .
                       "4. Match percentage\n\n" .
                       "Format as JSON with keys: matching_skills, missing_skills, bonus_skills, match_percentage.";
        }
        
        // Replace placeholders
        $skills = $cvParsedData->parsed_skills ?? [];
        $technicalSkills = !empty($skills['technical']) ? implode(', ', $skills['technical']) : 'None';
        $softSkills = !empty($skills['soft']) ? implode(', ', $skills['soft']) : 'None';
        
        $prompt = $template;
        $prompt = str_replace('{job_post->title}', $jobPost->title ?? 'N/A', $prompt);
        $prompt = str_replace('{job_post->requirements}', $jobPost->requirements ?? 'N/A', $prompt);
        $prompt = str_replace('{technical_skills}', $technicalSkills, $prompt);
        $prompt = str_replace('{soft_skills}', $softSkills, $prompt);
        $prompt = str_replace('{application->relevant_skills}', $application->relevant_skills ?? 'N/A', $prompt);

        return $prompt;
    }

    /**
     * Call AI API
     */
    private function callAI(string $prompt, ?int $companyId = null, ?int $jobApplicationId = null, string $operationType = 'other'): string
    {
        // If no API key configured, return empty (will use fallback)
        if (empty($this->apiKey)) {
            Log::warning('AI API key not configured');
            throw new \Exception('AI API key not configured');
        }

        return match($this->apiProvider) {
            'openai' => $this->callOpenAI($prompt, $companyId, $jobApplicationId, $operationType),
            'anthropic' => $this->callAnthropic($prompt, $companyId, $jobApplicationId, $operationType),
            'local' => $this->callLocalLLM($prompt),
            default => throw new \Exception("Unsupported AI provider: {$this->apiProvider}"),
        };
    }

    /**
     * Get company ID for token tracking
     */
    private function getCompanyId(JobApplication $application): ?int
    {
        // Try to get company from application first
        if ($application->company_id) {
            return $application->company_id;
        }
        
        // Fallback to job post's company
        if ($application->jobPost && $application->jobPost->company_id) {
            return $application->jobPost->company_id;
        }
        
        // Fallback to authenticated user's company
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id) {
            return $user->company_id;
        }
        
        // Last resort: get first company (for single-tenant setups)
        $company = Company::first();
        return $company?->id;
    }

    /**
     * Get system prompt for current user role
     */
    private function getSystemPrompt(): string
    {
        $user = auth()->user();
        $role = $this->getUserRole($user);
        
        $storedPrompt = AIPrompt::getPrompt('system', $role);
        if ($storedPrompt) {
            return $storedPrompt->content;
        }
        
        // Default system prompt
        return 'You are a CRITICAL expert HR analyst specializing in candidate evaluation. You must detect gibberish, meaningless responses, and nonsensical text. Be strict and apply human intelligence - do NOT treat random characters or placeholder text as strengths. Only identify genuine, meaningful strengths. Penalize meaningless responses heavily.';
    }

    /**
     * Get user role for prompt selection
     */
    private function getUserRole($user): ?string
    {
        if (!$user) {
            return null;
        }
        
        if ($user->isAdmin()) {
            return 'admin';
        }
        
        if ($user->hasRole('hr_manager')) {
            return 'hr_manager';
        }
        
        if ($user->isClient()) {
            return 'client';
        }
        
        return null; // Default
    }

    /**
     * Call OpenAI API
     */
    private function callOpenAI(string $prompt, ?int $companyId = null, ?int $jobApplicationId = null, string $operationType = 'other'): string
    {
        // Estimate tokens before call (for logging only - we don't block calls anymore)
        $estimatedTokens = $this->tokenService->estimateTokens($operationType, strlen($prompt));
        
        // Log estimated tokens (but don't block - we'll track usage regardless)
        if ($companyId) {
            $hasEnough = $this->tokenService->hasEnoughTokens($companyId, $estimatedTokens);
            if (!$hasEnough) {
                Log::warning('Estimated tokens exceed available balance, but proceeding with call (usage will be tracked)', [
                    'company_id' => $companyId,
                    'estimated_tokens' => $estimatedTokens,
                    'operation_type' => $operationType,
                    'job_application_id' => $jobApplicationId,
                ]);
                // Don't throw - allow the call to proceed, usage will be tracked for billing
            }
        }

        // Get system prompt based on user role
        $systemPrompt = $this->getSystemPrompt();

        // Log that we're about to call OpenAI (for debugging)
        Log::info('Calling OpenAI API', [
            'operation_type' => $operationType,
            'company_id' => $companyId,
            'job_application_id' => $jobApplicationId,
            'prompt_length' => strlen($prompt),
            'api_key_prefix' => substr($this->apiKey, 0, 7) . '...' . substr($this->apiKey, -4), // Log partial key for verification
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => config('ai.model', 'gpt-4o-mini'),
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemPrompt,
                ],
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
            'temperature' => 0.3,
            'max_tokens' => 2000,
        ]);

        if ($response->failed()) {
            Log::error('OpenAI API call failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'operation_type' => $operationType,
                'job_application_id' => $jobApplicationId,
            ]);
            throw new \Exception('OpenAI API error: ' . $response->body());
        }

        $data = $response->json();
        $content = $data['choices'][0]['message']['content'] ?? '';
        
        // Log successful API call
        Log::info('OpenAI API call successful', [
            'operation_type' => $operationType,
            'company_id' => $companyId,
            'job_application_id' => $jobApplicationId,
            'tokens_used' => $data['usage']['total_tokens'] ?? null,
        ]);
        
        // Track token usage - ALWAYS try to deduct if we have usage data
        if (isset($data['usage'])) {
            $usage = $data['usage'];
            $tokensUsed = $usage['total_tokens'] ?? ($usage['prompt_tokens'] + $usage['completion_tokens']);
            
            // If no companyId, try to get it from the application
            if (!$companyId && $jobApplicationId) {
                try {
                    $application = JobApplication::find($jobApplicationId);
                    if ($application) {
                        $companyId = $this->getCompanyId($application);
                        Log::info('Retrieved company_id from application for token tracking', [
                            'application_id' => $jobApplicationId,
                            'company_id' => $companyId,
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::warning('Could not retrieve application for company_id', [
                        'application_id' => $jobApplicationId,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
            
            if ($companyId) {
                try {
                    $deducted = $this->tokenService->deductTokens(
                $companyId,
                $tokensUsed,
                $operationType,
                [
                    'input_tokens' => $usage['prompt_tokens'] ?? 0,
                    'output_tokens' => $usage['completion_tokens'] ?? 0,
                    'model' => config('ai.model', 'gpt-4o-mini'),
                            'prompt_length' => strlen($prompt),
                            'response_length' => strlen($content),
                ],
                $jobApplicationId
            );
                    
                    if ($deducted) {
                        Log::info('Token deduction successful', [
                            'company_id' => $companyId,
                            'tokens_used' => $tokensUsed,
                            'operation_type' => $operationType,
                            'job_application_id' => $jobApplicationId,
                        ]);
                    } else {
                        Log::warning('Token deduction failed but operation completed', [
                            'company_id' => $companyId,
                            'tokens_used' => $tokensUsed,
                            'operation_type' => $operationType,
                            'job_application_id' => $jobApplicationId,
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Token deduction error', [
                        'company_id' => $companyId,
                        'tokens_used' => $tokensUsed,
                        'operation_type' => $operationType,
                        'job_application_id' => $jobApplicationId,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    // Don't fail the operation if token logging fails
                }
            } else {
                Log::warning('Token usage not tracked - no company_id available', [
                    'operation_type' => $operationType,
                    'job_application_id' => $jobApplicationId,
                    'tokens_used' => $tokensUsed,
                ]);
            }
        }
        
        return $content;
    }

    /**
     * Call Anthropic API
     */
    private function callAnthropic(string $prompt, ?int $companyId = null, ?int $jobApplicationId = null, string $operationType = 'other'): string
    {
        // Estimate tokens before call
        $estimatedTokens = $this->tokenService->estimateTokens($operationType, strlen($prompt));
        
        // Check token availability if company ID provided
        if ($companyId && !$this->tokenService->hasEnoughTokens($companyId, $estimatedTokens)) {
            // Don't throw - allow call to proceed, usage will be tracked
            Log::warning('Estimated tokens exceed available balance, but proceeding with call (usage will be tracked for billing)', [
                'company_id' => $companyId,
                'estimated_tokens' => $estimatedTokens,
                'operation_type' => $operationType,
            ]);
        }

        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'Content-Type' => 'application/json',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model' => config('ai.model', 'claude-3-haiku-20240307'),
            'max_tokens' => 2000,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
        ]);

        if ($response->failed()) {
            throw new \Exception('Anthropic API error: ' . $response->body());
        }

        $data = $response->json();
        $content = $data['content'][0]['text'] ?? '';
        
        // Track token usage (Anthropic returns usage in headers or response)
        if ($companyId && isset($data['usage'])) {
            $usage = $data['usage'];
            $tokensUsed = $usage['input_tokens'] + $usage['output_tokens'];
            
            $this->tokenService->deductTokens(
                $companyId,
                $tokensUsed,
                $operationType,
                [
                    'input_tokens' => $usage['input_tokens'] ?? 0,
                    'output_tokens' => $usage['output_tokens'] ?? 0,
                    'model' => config('ai.model', 'claude-3-haiku-20240307'),
                ],
                $jobApplicationId
            );
        }
        
        return $content;
    }

    /**
     * Call local LLM (e.g., Ollama)
     */
    private function callLocalLLM(string $prompt): string
    {
        $url = $this->apiUrl ?: config('ai.local_api_url', 'http://localhost:11434/api/generate');
        
        $response = Http::post($url, [
            'model' => config('ai.model', 'llama2'),
            'prompt' => $prompt,
            'stream' => false,
        ]);

        if ($response->failed()) {
            throw new \Exception('Local LLM API error: ' . $response->body());
        }

        $data = $response->json();
        return $data['response'] ?? '';
    }

    /**
     * Parse AI response for CV analysis
     */
    private function parseAIResponse(string $response): array
    {
        // Try to extract JSON from response
        if (preg_match('/\{.*\}/s', $response, $matches)) {
            $json = json_decode($matches[0], true);
            if ($json) {
                return $json;
            }
        }

        // Fallback: parse text response
        return [
            'summary' => $response,
            'strengths' => [],
            'education_highlights' => [],
            'skills' => [],
            'assessment' => '',
        ];
    }

    /**
     * Parse application analysis response
     */
    private function parseApplicationAnalysisResponse(string $response): array
    {
        if (preg_match('/\{.*\}/s', $response, $matches)) {
            $json = json_decode($matches[0], true);
            if ($json) {
                return $json;
            }
        }

        return [
            'match_score' => 0,
            'matching_points' => [],
            'missing_requirements' => [],
            'recommendation' => 'manual_review',
            'confidence' => 0.5,
        ];
    }

    /**
     * Parse skill matching response
     */
    private function parseSkillMatchingResponse(string $response): array
    {
        if (preg_match('/\{.*\}/s', $response, $matches)) {
            $json = json_decode($matches[0], true);
            if ($json) {
                return $json;
            }
        }

        return [
            'matching_skills' => [],
            'missing_skills' => [],
            'bonus_skills' => [],
            'match_percentage' => 0,
        ];
    }

    /**
     * Extract text from AI response
     */
    private function extractTextFromResponse(string $response): string
    {
        // Remove JSON markers if present
        $response = preg_replace('/^```json\s*/', '', $response);
        $response = preg_replace('/^```\s*/', '', $response);
        $response = preg_replace('/\s*```$/', '', $response);
        
        return trim($response);
    }

    /**
     * Generate fallback summary when AI is unavailable
     */
    private function generateFallbackSummary(JobApplication $application, bool $cvEmpty = false): array
    {
        $summary = "Application from {$application->name}";
        
        if ($cvEmpty) {
            $summary .= ". ⚠️ WARNING: CV is empty or contains minimal content.";
        }
        
        if ($application->education_level) {
            $summary .= " with {$application->education_level}";
            if ($application->area_of_study) {
                $summary .= " in {$application->area_of_study}";
            }
        }
        
        if ($application->current_job_title) {
            $summary .= ". Currently working as {$application->current_job_title}";
            if ($application->current_company) {
                $summary .= " at {$application->current_company}";
            }
        }

        return [
            'summary' => $summary,
            'strengths' => [],
            'education_highlights' => [],
            'skills' => [],
            'assessment' => $cvEmpty ? 'CV is empty or minimal - requires manual review' : 'Requires manual review',
            'cv_empty' => $cvEmpty,
        ];
    }

    /**
     * Generate basic summary
     */
    private function generateBasicSummary(JobApplication $application): string
    {
        $summary = "{$application->name} is a candidate";
        
        if ($application->education_level) {
            $summary .= " with a {$application->education_level}";
            if ($application->area_of_study) {
                $summary .= " in {$application->area_of_study}";
            }
        }
        
        if ($application->current_job_title) {
            $summary .= " currently working as {$application->current_job_title}";
        }
        
        $summary .= ".";

        return $summary;
    }
}

