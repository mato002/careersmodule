<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Candidate extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'phone_country_code',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'date_of_birth',
        'profile_photo_path',
        'preferred_language',
        'status',
        'last_login_at',
        'last_login_ip',
        // Bio data fields
        'national_id',
        'kra_pin',
        'nssf_number',
        'nhif_number',
        'marital_status',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_phone_country_code',
        'emergency_contact_relationship',
        'medical_conditions',
        'allergies',
        'blood_group',
        'next_of_kin_name',
        'next_of_kin_phone',
        'next_of_kin_phone_country_code',
        'next_of_kin_relationship',
        'next_of_kin_address',
        'biodata_completed',
        'biodata_completed_at',
        // Additional bio data fields
        'position',
        'nationality',
        'sex',
        'religion',
        'current_address',
        'home_county',
        'home_sub_county',
        'home_ward',
        'home_estate',
        'home_house_number',
        'spouse_name',
        'spouse_phone_country_code',
        'spouse_phone',
        'number_of_children',
        'children_names',
        'father_name',
        'father_phone_country_code',
        'father_phone',
        'father_county',
        'father_sub_county',
        'father_ward',
        'mother_name',
        'mother_phone_country_code',
        'mother_phone',
        'health_physical_condition',
        'primary_school',
        'primary_graduation_year',
        'secondary_school',
        'secondary_graduation_year',
        'university_college',
        'university_graduation_year',
        'professional_qualifications',
        'special_skills',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'last_login_at' => 'datetime',
            'biodata_completed_at' => 'datetime',
            'status' => 'string',
            'biodata_completed' => 'boolean',
            'number_of_children' => 'integer',
            'primary_graduation_year' => 'integer',
            'secondary_graduation_year' => 'integer',
            'university_graduation_year' => 'integer',
        ];
    }

    /**
     * Get job applications for this candidate.
     */
    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * Get sessions for this candidate.
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(CandidateSession::class);
    }

    /**
     * Get active sessions for this candidate.
     */
    public function activeSessions()
    {
        return $this->sessions()->active()->orderBy('last_activity', 'desc');
    }

    /**
     * Get activity logs for this candidate.
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'candidate_id');
    }

    /**
     * Get documents for this candidate.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(CandidateDocument::class);
    }

    /**
     * Get appraisals for this candidate.
     */
    public function appraisals(): HasMany
    {
        return $this->hasMany(CandidateAppraisal::class);
    }

    /**
     * Check if candidate is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if candidate is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Check if candidate is banned.
     */
    public function isBanned(): bool
    {
        return $this->status === 'banned';
    }

    /**
     * Get profile photo URL.
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        if ($this->profile_photo_path) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->profile_photo_path);
        }
        return null;
    }

    /**
     * Get full phone number.
     */
    public function getFullPhoneAttribute(): ?string
    {
        if ($this->phone) {
            return ($this->phone_country_code ? '+' . $this->phone_country_code . ' ' : '') . $this->phone;
        }
        return null;
    }

    /**
     * Get full address.
     */
    public function getFullAddressAttribute(): ?string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]);
        return !empty($parts) ? implode(', ', $parts) : null;
    }

    /**
     * Calculate profile completeness percentage.
     */
    public function getProfileCompletenessAttribute(): int
    {
        $fields = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'date_of_birth' => $this->date_of_birth,
            'profile_photo_path' => $this->profile_photo_path,
        ];

        $filled = count(array_filter($fields));
        $total = count($fields);

        return $total > 0 ? (int) round(($filled / $total) * 100) : 0;
    }
}

