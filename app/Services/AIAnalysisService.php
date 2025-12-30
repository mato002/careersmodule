<?php

namespace App\Services;

use App\Models\JobApplication;
use App\Models\CvParsedData;
use App\Models\JobPost;
use App\Models\Company;
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
        
        if (!$cvParsedData || empty($cvParsedData->raw_text)) {
            Log::warning('No CV parsed data available for analysis', [
                'application_id' => $application->id
            ]);
            return $this->generateFallbackSummary($application);
        }

        try {
            $companyId = $this->getCompanyId($application);
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
        $jobPost = $application->jobPost;
        
        $prompt = "Analyze the following CV/resume and provide a comprehensive summary.\n\n";
        
        if ($jobPost) {
            $prompt .= "Job Position: {$jobPost->title}\n";
            $prompt .= "Job Requirements: {$jobPost->requirements}\n\n";
        }
        
        $prompt .= "CV Content:\n{$cvParsedData->raw_text}\n\n";
        
        $prompt .= "Please provide:\n";
        $prompt .= "1. A concise summary of the candidate's background (2-3 sentences)\n";
        $prompt .= "2. Key strengths and relevant experience\n";
        $prompt .= "3. Education highlights\n";
        $prompt .= "4. Notable skills and certifications\n";
        $prompt .= "5. Overall assessment for the position\n\n";
        $prompt .= "Format your response as JSON with keys: summary, strengths, education_highlights, skills, assessment.";

        return $prompt;
    }

    /**
     * Build application analysis prompt
     */
    private function buildApplicationAnalysisPrompt(JobApplication $application, JobPost $jobPost): string
    {
        $prompt = "Analyze this job application and match it to the job requirements.\n\n";
        
        $prompt .= "Job Position: {$jobPost->title}\n";
        $prompt .= "Job Description: {$jobPost->description}\n";
        $prompt .= "Job Requirements: {$jobPost->requirements}\n\n";
        
        $prompt .= "Application Details:\n";
        $prompt .= "Name: {$application->name}\n";
        $prompt .= "Education: {$application->education_level} in {$application->area_of_study}\n";
        $prompt .= "Current Position: {$application->current_job_title} at {$application->current_company}\n";
        $prompt .= "Skills: {$application->relevant_skills}\n";
        $prompt .= "Why Interested: {$application->why_interested}\n";
        $prompt .= "Why Good Fit: {$application->why_good_fit}\n";
        
        if ($application->cvParsedData) {
            $prompt .= "\nCV Parsed Data Available\n";
        }
        
        $prompt .= "\nPlease provide:\n";
        $prompt .= "1. Match score (0-100) indicating how well the candidate matches the job\n";
        $prompt .= "2. Key matching points\n";
        $prompt .= "3. Missing requirements or gaps\n";
        $prompt .= "4. Recommendation (pass/reject/manual_review)\n";
        $prompt .= "5. Confidence level (0-1)\n\n";
        $prompt .= "Format your response as JSON with keys: match_score, matching_points, missing_requirements, recommendation, confidence.";

        return $prompt;
    }

    /**
     * Build profile summary prompt
     */
    private function buildProfileSummaryPrompt(JobApplication $application, CvParsedData $cvParsedData, ?JobPost $jobPost): string
    {
        $prompt = "Generate a professional candidate profile summary based on the following information:\n\n";
        
        $prompt .= "Candidate: {$application->name}\n";
        $prompt .= "Email: {$application->email}\n\n";
        
        if ($jobPost) {
            $prompt .= "Applied for: {$jobPost->title}\n\n";
        }
        
        $prompt .= "CV Content:\n{$cvParsedData->raw_text}\n\n";
        
        $prompt .= "Create a 3-4 sentence professional summary highlighting:\n";
        $prompt .= "- Professional background and experience\n";
        $prompt .= "- Key skills and qualifications\n";
        $prompt .= "- Notable achievements or strengths\n";
        $prompt .= "- Relevance to the position (if job post provided)\n";
        $prompt .= "\nWrite in third person, professional tone.";

        return $prompt;
    }

    /**
     * Build skill matching prompt
     */
    private function buildSkillMatchingPrompt(JobApplication $application, CvParsedData $cvParsedData, JobPost $jobPost): string
    {
        $skills = $cvParsedData->parsed_skills ?? [];
        $skillsText = '';
        if (!empty($skills['technical'])) {
            $skillsText .= "Technical: " . implode(', ', $skills['technical']) . "\n";
        }
        if (!empty($skills['soft'])) {
            $skillsText .= "Soft: " . implode(', ', $skills['soft']) . "\n";
        }
        
        $prompt = "Match the candidate's skills to the job requirements.\n\n";
        $prompt .= "Job Position: {$jobPost->title}\n";
        $prompt .= "Job Requirements: {$jobPost->requirements}\n\n";
        $prompt .= "Candidate Skills:\n{$skillsText}\n";
        $prompt .= "Additional Skills from Application: {$application->relevant_skills}\n\n";
        
        $prompt .= "Provide:\n";
        $prompt .= "1. Matching skills (skills that match job requirements)\n";
        $prompt .= "2. Missing skills (required skills not found)\n";
        $prompt .= "3. Bonus skills (additional valuable skills)\n";
        $prompt .= "4. Match percentage\n\n";
        $prompt .= "Format as JSON with keys: matching_skills, missing_skills, bonus_skills, match_percentage.";

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
        // For now, get the first company (single tenant)
        // Later: get from job post or user's company
        $company = Company::first();
        return $company?->id;
    }

    /**
     * Call OpenAI API
     */
    private function callOpenAI(string $prompt, ?int $companyId = null, ?int $jobApplicationId = null, string $operationType = 'other'): string
    {
        // Estimate tokens before call
        $estimatedTokens = $this->tokenService->estimateTokens($operationType, strlen($prompt));
        
        // Check token availability if company ID provided
        if ($companyId && !$this->tokenService->hasEnoughTokens($companyId, $estimatedTokens)) {
            throw new \Exception('Insufficient tokens available. Please purchase more tokens.');
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => config('ai.model', 'gpt-4o-mini'),
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an expert HR analyst specializing in candidate evaluation and CV analysis. Provide accurate, professional, and structured responses.',
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
            throw new \Exception('OpenAI API error: ' . $response->body());
        }

        $data = $response->json();
        $content = $data['choices'][0]['message']['content'] ?? '';
        
        // Track token usage
        if ($companyId && isset($data['usage'])) {
            $usage = $data['usage'];
            $tokensUsed = $usage['total_tokens'] ?? ($usage['prompt_tokens'] + $usage['completion_tokens']);
            
            $this->tokenService->deductTokens(
                $companyId,
                $tokensUsed,
                $operationType,
                [
                    'input_tokens' => $usage['prompt_tokens'] ?? 0,
                    'output_tokens' => $usage['completion_tokens'] ?? 0,
                    'model' => config('ai.model', 'gpt-4o-mini'),
                ],
                $jobApplicationId
            );
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
            throw new \Exception('Insufficient tokens available. Please purchase more tokens.');
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
    private function generateFallbackSummary(JobApplication $application): array
    {
        $summary = "Application from {$application->name}";
        
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
            'assessment' => 'Requires manual review',
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

