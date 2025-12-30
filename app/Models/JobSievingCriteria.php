<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobSievingCriteria extends Model
{
    use HasFactory;

    protected $table = 'job_sieving_criteria';

    protected $fillable = [
        'job_post_id',
        'criteria_json',
        'auto_pass_threshold',
        'auto_reject_threshold',
        'auto_pass_confidence',
        'auto_reject_confidence',
        'created_by',
    ];

    protected $casts = [
        'criteria_json' => 'array',
        'auto_pass_confidence' => 'decimal:2',
        'auto_reject_confidence' => 'decimal:2',
    ];

    public function jobPost(): BelongsTo
    {
        return $this->belongsTo(JobPost::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get default criteria template
     */
    public static function getDefaultCriteria(): array
    {
        return [
            'education' => [
                'weight' => 25,
                'mandatory' => true,
                'minimum_level' => 'Diploma',
                'preferred_levels' => ['Bachelor\'s', 'Master\'s'],
                'relevant_fields' => [
                    'Finance', 'Accounting', 'Business', 'Economics',
                    'Commerce', 'Banking', 'Actuarial Science'
                ],
                'field_match_bonus' => 5,
            ],
            'experience' => [
                'weight' => 30,
                'mandatory' => false,
                'min_years' => 1,
                'preferred_years' => 2,
                'relevant_industries' => [
                    'Finance', 'Banking', 'Lending', 'Financial Services',
                    'Microfinance', 'Insurance'
                ],
                'points_per_year' => 8,
                'industry_match_bonus' => 10,
                'max_points' => 30,
            ],
            'skills' => [
                'weight' => 25,
                'mandatory' => false,
                'required_skills' => [
                    'MS Excel', 'Communication', 'Customer Service',
                    'Financial Analysis', 'Data Entry'
                ],
                'preferred_skills' => [
                    'QuickBooks', 'Loan Processing', 'Credit Analysis',
                    'Risk Assessment', 'Report Writing'
                ],
                'points_per_required' => 5,
                'points_per_preferred' => 3,
                'max_points' => 25,
            ],
            'response_quality' => [
                'weight' => 20,
                'mandatory' => false,
                'min_length' => 50,
                'quality_indicators' => [
                    'specific examples',
                    'relevant experience mentioned',
                    'professional tone',
                    'shows understanding of role'
                ],
                'points_per_indicator' => 5,
                'max_points' => 20,
            ],
            'red_flags' => [
                'incomplete_application' => -20,
                'salary_expectations_too_high' => -15,
                'availability_delayed_days' => 90,
                'availability_penalty' => -10,
                'generic_responses' => -10,
                'excessive_spelling_errors' => -5,
            ],
        ];
    }
}

