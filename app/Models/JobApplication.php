<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'job_post_id',
        'company_id',
        'name',
        'phone',
        'email',
        'why_interested',
        'why_good_fit',
        'career_goals',
        'salary_expectations',
        'availability_date',
        'relevant_skills',
        'education_level',
        'area_of_study',
        'institution',
        'education_status',
        'education_start_year',
        'education_end_year',
        'education_expected_completion_year',
        'other_achievements',
        'work_experience',
        'current_job_title',
        'current_company',
        'currently_working',
        'duties_and_responsibilities',
        'other_experiences',
        'ai_summary',
        'ai_details',
        'support_details',
        'certifications',
        'languages',
        'professional_memberships',
        'awards_recognition',
        'portfolio_links',
        'availability_travel',
        'availability_relocation',
        'referrers',
        'notice_period',
        'agreement_accepted',
        'cv_path',
        'application_message',
        'status',
        'aptitude_test_score',
        'aptitude_test_passed',
        'aptitude_test_completed_at',
        'self_interview_score',
        'self_interview_passed',
        'self_interview_completed_at',
    ];

    protected $casts = [
        'work_experience' => 'array',
        'referrers' => 'array',
        'currently_working' => 'boolean',
        'agreement_accepted' => 'boolean',
        'aptitude_test_passed' => 'boolean',
        'aptitude_test_completed_at' => 'datetime',
        'self_interview_passed' => 'boolean',
        'self_interview_completed_at' => 'datetime',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function jobPost(): BelongsTo
    {
        return $this->belongsTo(JobPost::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(JobApplicationReview::class);
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(JobApplicationMessage::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(JobApplicationStatusHistory::class);
    }

    public function aiSievingDecision()
    {
        return $this->hasOne(AISievingDecision::class);
    }

    public function aptitudeTestSession()
    {
        return $this->hasOne(AptitudeTestSession::class);
    }

    public function selfInterviewSession()
    {
        return $this->hasOne(SelfInterviewSession::class);
    }

    public function cvParsedData()
    {
        return $this->hasOne(CvParsedData::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    /**
     * Scope to filter by company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }
}

