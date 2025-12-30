<?php

namespace App\Jobs;

use App\Models\JobApplication;
use App\Services\CvParserService;
use App\Services\AIAnalysisService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessCvJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public JobApplication $application
    ) {}

    /**
     * Execute the job.
     */
    public function handle(CvParserService $cvParser, AIAnalysisService $aiAnalysis): void
    {
        try {
            // Parse CV
            if (config('ai.enable_cv_parsing', true)) {
                $cvParsedData = $cvParser->parse($this->application);
                
                if ($cvParsedData) {
                    Log::info('CV parsed successfully', [
                        'application_id' => $this->application->id,
                        'confidence' => $cvParsedData->parsing_confidence
                    ]);
                }
            }

            // Generate AI summary and analysis
            if (config('ai.enable_ai_analysis', true)) {
                $analysis = $aiAnalysis->analyzeCv($this->application);
                
                // Update application with AI summary
                if (!empty($analysis['summary'])) {
                    $this->application->update([
                        'ai_summary' => $analysis['summary'],
                        'ai_details' => json_encode($analysis, JSON_PRETTY_PRINT),
                    ]);
                }

                // Generate profile summary
                $profileSummary = $aiAnalysis->generateProfileSummary($this->application);
                if ($profileSummary && empty($this->application->ai_summary)) {
                    $this->application->update(['ai_summary' => $profileSummary]);
                }
            }
        } catch (\Exception $e) {
            Log::error('CV processing job failed', [
                'application_id' => $this->application->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Don't throw - allow job to complete even if processing fails
        }
    }
}


