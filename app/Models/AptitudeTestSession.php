<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AptitudeTestSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_application_id',
        'questions_answered',
        'total_score',
        'total_possible_score',
        'is_passed',
        'pass_threshold',
        'started_at',
        'completed_at',
        'time_taken_seconds',
    ];

    protected $casts = [
        'questions_answered' => 'array',
        'is_passed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    /**
     * Calculate score and update application
     */
    public function calculateScore(): void
    {
        $score = 0;
        $totalPossible = 0;

        foreach ($this->questions_answered as $questionId => $answer) {
            $question = AptitudeTestQuestion::find($questionId);
            if ($question) {
                $totalPossible += $question->points;
                if (strtolower(trim($answer)) === strtolower(trim($question->correct_answer))) {
                    $score += $question->points;
                }
            }
        }

        // Calculate percentage score
        $percentageScore = $totalPossible > 0 ? round(($score / $totalPossible) * 100) : 0;

        $this->total_score = $score;
        $this->total_possible_score = $totalPossible;
        $this->is_passed = $percentageScore >= $this->pass_threshold;
        $this->save();

        $application = $this->application;
        $previousStatus = $application->status;

        // Update job application
        $application->update([
            'aptitude_test_score' => $percentageScore,
            'aptitude_test_passed' => $this->is_passed,
            'aptitude_test_completed_at' => now(),
        ]);

        // Record status change in history
        \App\Models\JobApplicationStatusHistory::create([
            'job_application_id' => $application->id,
            'previous_status' => $previousStatus,
            'new_status' => $application->status, // Will be updated by checkStage2Completion
            'changed_by' => null, // System change
            'source' => 'aptitude_test_completion',
            'notes' => "Aptitude test completed. Score: {$percentageScore}% ({$score}/{$totalPossible}). " . ($this->is_passed ? 'Passed' : 'Failed'),
        ]);

        // Check if both Stage 2 requirements are met (aptitude + interview 1)
        $this->checkStage2Completion();
    }

    /**
     * Check if Stage 2 is complete (aptitude passed + interview 1 passed)
     */
    private function checkStage2Completion(): void
    {
        $application = $this->application->fresh(); // Refresh to get latest data

        // Check if aptitude passed
        $aptitudePassed = $application->aptitude_test_passed === true;

        // Check if interview 1 (online) passed
        $interview1Passed = $application->interviews()
            ->where('interview_type', 'online_interview')
            ->where('result', 'pass')
            ->exists();

        $previousStatus = $application->status;
        $newStatus = $previousStatus;

        // If both passed, move to Stage 3
        if ($aptitudePassed && $interview1Passed) {
            $newStatus = 'stage_2_passed';
        } elseif (!$aptitudePassed) {
            // If aptitude failed, reject
            $newStatus = 'sieving_rejected';
        } elseif ($aptitudePassed) {
            // If aptitude passed but interview not yet done, keep current status
            // Status will be updated when interview is completed
            return;
        }

        // Update status if it changed
        if ($newStatus !== $previousStatus) {
            $application->update(['status' => $newStatus]);
            
            // Record status change
            \App\Models\JobApplicationStatusHistory::create([
                'job_application_id' => $application->id,
                'previous_status' => $previousStatus,
                'new_status' => $newStatus,
                'changed_by' => null, // System change
                'source' => 'aptitude_test_completion',
                'notes' => $aptitudePassed && $interview1Passed 
                    ? 'Stage 2 completed: Aptitude test passed and Interview 1 passed'
                    : 'Aptitude test failed',
            ]);
        }
    }
}

