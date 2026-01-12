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
     * Scope to filter by company (includes company-specific and global questions)
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where(function ($q) use ($companyId) {
            $q->where('company_id', $companyId)
              ->orWhereNull('company_id'); // Include global questions
        });
    }

    /**
     * Get questions for a test (randomized, limited per section)
     * If job_post_id is provided, gets job-specific questions + global questions
     * If null, gets only global questions
     * Now also filters by company_id
     */
    public static function getTestQuestions(?int $jobPostId = null, ?int $companyId = null): array
    {
        // Build base query
        $baseQuery = self::active();
        
        // Filter by company if provided (includes company-specific and global questions)
        if ($companyId) {
            $baseQuery->forCompany($companyId);
        }
        
        if ($jobPostId) {
            // Get job-specific questions + global questions (where job_post_id is null)
            $baseQuery->forJobPost($jobPostId);
        } else {
            // Only global questions
            $baseQuery->whereNull('job_post_id');
        }

        // Build queries for each section (clone to avoid query builder state issues)
        $questions = [
            'numerical' => (clone $baseQuery)->bySection('numerical')->inRandomOrder()->limit(10)->get(),
            'logical' => (clone $baseQuery)->bySection('logical')->inRandomOrder()->limit(6)->get(),
            'verbal' => (clone $baseQuery)->bySection('verbal')->inRandomOrder()->limit(5)->get(),
            'scenario' => (clone $baseQuery)->bySection('scenario')->inRandomOrder()->limit(4)->get(),
        ];

        return $questions;
    }
}

