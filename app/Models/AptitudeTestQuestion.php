<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AptitudeTestQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_post_id',
        'company_id',
        'section',
        'question',
        'options',
        'correct_answer',
        'points',
        'explanation',
        'display_order',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean',
    ];

    public function jobPost(): BelongsTo
    {
        return $this->belongsTo(JobPost::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBySection($query, string $section)
    {
        return $query->where('section', $section);
    }

    /**
     * Scope to filter by job post (null means global questions)
     */
    public function scopeForJobPost($query, $jobPostId)
    {
        return $query->where(function ($q) use ($jobPostId) {
            $q->where('job_post_id', $jobPostId)
              ->orWhereNull('job_post_id'); // Include global questions
        });
    }

    /**
     * Scope to filter by company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Get questions for a test (randomized, limited per section)
     * If job_post_id is provided, gets job-specific questions + global questions
     * If null, gets only global questions
     * Now also filters by company_id
     */
    public static function getTestQuestions(?int $jobPostId = null, ?int $companyId = null): array
    {
        $query = self::active();
        
        // Filter by company if provided
        if ($companyId) {
            $query->forCompany($companyId);
        }
        
        if ($jobPostId) {
            // Get job-specific questions + global questions (where job_post_id is null)
            $query->forJobPost($jobPostId);
        } else {
            // Only global questions
            $query->whereNull('job_post_id');
        }

        $questions = [
            'numerical' => (clone $query)->bySection('numerical')->inRandomOrder()->limit(10)->get(),
            'logical' => (clone $query)->bySection('logical')->inRandomOrder()->limit(6)->get(),
            'verbal' => (clone $query)->bySection('verbal')->inRandomOrder()->limit(5)->get(),
            'scenario' => (clone $query)->bySection('scenario')->inRandomOrder()->limit(4)->get(),
        ];

        return $questions;
    }
}

