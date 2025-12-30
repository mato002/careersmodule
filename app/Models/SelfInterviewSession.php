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

        $application->update([
            'self_interview_score' => $percentageScore,
            'self_interview_passed' => $this->is_passed,
            'self_interview_completed_at' => now(),
        ]);

        // Record in status history for auditing (does not change status)
        \App\Models\JobApplicationStatusHistory::create([
            'job_application_id' => $application->id,
            'previous_status' => $application->status,
            'new_status' => $application->status,
            'changed_by' => null,
            'source' => 'self_interview_completion',
            'notes' => "Self interview completed. Score: {$percentageScore}% ({$score}/{$totalPossible}). " . ($this->is_passed ? 'Passed' : 'Failed'),
        ]);
    }
}


