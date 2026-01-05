<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelfInterviewSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_application_id',
        'answers',
        'total_score',
        'total_possible_score',
        'is_passed',
        'pass_threshold',
        'started_at',
        'completed_at',
        'time_taken_seconds',
    ];

    protected $casts = [
        'answers' => 'array',
        'is_passed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    /**
     * Calculate score and update application self-interview fields.
     */
    public function calculateScore(): void
    {
        $score = 0;
        $totalPossible = 0;

        foreach ($this->answers ?? [] as $questionId => $answer) {
            $question = SelfInterviewQuestion::find($questionId);
            if (! $question) {
                continue;
            }

            // Only questions with a defined correct_answer are auto‑marked.
            // Open‑ended questions (no correct_answer) are for qualitative review
            // and do NOT reduce the candidate's percentage score.
            if ($question->correct_answer !== null && $question->correct_answer !== '') {
                $totalPossible += $question->points;

                if (strtolower(trim($answer)) === strtolower(trim($question->correct_answer))) {
                    $score += $question->points;
                }
            }
        }

        // If there are no auto‑marked questions, treat the self interview as 100%
        $percentageScore = $totalPossible > 0 ? round(($score / $totalPossible) * 100) : 100;

        $this->total_score = $score;
        $this->total_possible_score = $totalPossible;
        $this->is_passed = $percentageScore >= $this->pass_threshold;
        $this->save();

        $application = $this->application;

        $previousStatus = $application->status;
        
        $application->update([
            'self_interview_score' => $percentageScore,
            'self_interview_passed' => $this->is_passed,
            'self_interview_completed_at' => now(),
        ]);

        // Refresh application to get latest data
        $application->refresh();

        // Record in status history for auditing
        \App\Models\JobApplicationStatusHistory::create([
            'job_application_id' => $application->id,
            'previous_status' => $previousStatus,
            'new_status' => $application->status, // Will be updated by checkStage2Completion
            'changed_by' => null,
            'source' => 'self_interview_completion',
            'notes' => "Self interview completed. Score: {$percentageScore}% ({$score}/{$totalPossible}). " . ($this->is_passed ? 'Passed' : 'Failed'),
        ]);

        // Check if both Stage 2 requirements are met (aptitude + self-interview)
        $this->checkStage2Completion();
    }

    /**
     * Check if Stage 2 is complete (aptitude passed + self-interview passed)
     */
    private function checkStage2Completion(): void
    {
        $application = $this->application->fresh(); // Refresh to get latest data

        // Check if aptitude passed
        $aptitudePassed = $application->aptitude_test_passed === true;

        // Check if self-interview passed
        $selfInterviewPassed = $application->self_interview_passed === true;

        $previousStatus = $application->status;
        $newStatus = $previousStatus;

        // Determine new status based on self-interview result
        if ($aptitudePassed && $selfInterviewPassed) {
            // If both aptitude and self-interview passed, move to Stage 2 Passed
            $newStatus = 'stage_2_passed';
        } elseif (!$selfInterviewPassed) {
            // If self-interview failed, don't change status
            return;
        } elseif ($selfInterviewPassed && !$aptitudePassed) {
            // If self-interview passed but aptitude not yet done, keep current status
            return;
        }

        // Update status if it changed
        if ($newStatus !== $previousStatus) {
            $application->update(['status' => $newStatus]);
            
            // Record status change
            $notes = '';
            if ($aptitudePassed && $selfInterviewPassed) {
                $notes = 'Stage 2 completed: Aptitude test passed and Self-interview passed';
            }
            
            \App\Models\JobApplicationStatusHistory::create([
                'job_application_id' => $application->id,
                'previous_status' => $previousStatus,
                'new_status' => $newStatus,
                'changed_by' => null, // System change
                'source' => 'self_interview_completion',
                'notes' => $notes,
            ]);
        }
    }
}


