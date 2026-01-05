<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JobPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'requirements',
        'responsibilities',
        'location',
        'department',
        'employment_type',
        'experience_level',
        'application_deadline',
        'is_active',
        'views',
        'company_id',
    ];

    protected $casts = [
        'application_deadline' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Retrieve the model for bound value.
     * When using {jobPost:slug} in routes, Laravel passes $field = 'slug'
     * This ensures we always look up by slug for route model binding
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // When using {jobPost:slug}, $field will be 'slug'
        // When using default binding, $field will be null and we use getRouteKeyName() which returns 'slug'
        if ($field === 'slug' || $field === null) {
            return $this->where('slug', $value)->firstOrFail();
        }
        
        // Fallback to parent implementation for other fields
        return parent::resolveRouteBinding($value, $field);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($jobPost) {
            if (empty($jobPost->slug)) {
                $jobPost->slug = Str::slug($jobPost->title);
            }
        });

        static::updating(function ($jobPost) {
            // Update slug if title changed
            if ($jobPost->isDirty('title') && empty($jobPost->slug)) {
                $jobPost->slug = Str::slug($jobPost->title);
            }
        });
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function sievingCriteria()
    {
        return $this->hasOne(JobSievingCriteria::class);
    }

    /**
     * Scope to filter by company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only jobs that are open for applications.
     * This means: active, and deadline hasn't passed (or no deadline set).
     */
    public function scopeOpenForApplications($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('application_deadline')
                  ->orWhere('application_deadline', '>=', now());
            });
    }

    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Get the application status of the job post.
     */
    public function getApplicationStatusAttribute(): string
    {
        if (!$this->is_active) {
            return 'inactive';
        }

        $deadlinePassed = $this->application_deadline && $this->application_deadline->isPast();
        $applicationsCount = $this->applications_count ?? $this->applications()->count();

        if ($deadlinePassed) {
            return 'closed';
        }

        if ($applicationsCount > 0) {
            return 'accepting_with_applications';
        }

        return 'accepting';
    }

    /**
     * Get the status label for display.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->application_status) {
            'inactive' => 'Inactive',
            'closed' => 'Closed',
            'accepting_with_applications' => 'Open (Has Applications)',
            'accepting' => 'Open',
            default => 'Unknown',
        };
    }

    /**
     * Get the status badge color classes.
     */
    public function getStatusBadgeClassesAttribute(): string
    {
        return match($this->application_status) {
            'inactive' => 'bg-gray-200 text-gray-700',
            'closed' => 'bg-red-100 text-red-800',
            'accepting_with_applications' => 'bg-blue-100 text-blue-800',
            'accepting' => 'bg-green-100 text-green-800',
            default => 'bg-gray-200 text-gray-700',
        };
    }
}


