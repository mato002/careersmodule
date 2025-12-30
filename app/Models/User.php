<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_banned',
        'role',
        'company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'is_admin' => 'boolean',
            'is_banned' => 'boolean',
        ];
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user is admin (either via role or is_admin flag for backward compatibility).
     */
    public function isAdmin(): bool
    {
        return $this->is_admin || $this->role === 'admin';
    }

    /**
     * Check if user is banned.
     */
    public function isBanned(): bool
    {
        return $this->is_banned ?? false;
    }

    /**
     * Get available roles.
     */
    public static function getRoles(): array
    {
        return [
            'user' => 'User',
            'candidate' => 'Candidate',
            'admin' => 'Administrator',
            'hr_manager' => 'HR Manager',
            'editor' => 'Editor',
            'client' => 'Client (Company Admin)',
        ];
    }

    /**
     * Check if user is HR Manager or has HR-related access.
     * Clients have the same permissions as HR managers for careers module.
     */
    public function isHrManager(): bool
    {
        return $this->role === 'hr_manager' || $this->role === 'client' || $this->isAdmin();
    }

    /**
     * Check if user can access careers section.
     */
    public function canAccessCareers(): bool
    {
        return $this->isHrManager() || $this->isAdmin() || $this->isClient();
    }

    /**
     * Get the activity logs for the user.
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Get the user sessions for the user.
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(UserSession::class);
    }

    /**
     * Get active sessions for the user.
     */
    public function activeSessions()
    {
        return $this->sessions()->active()->orderBy('last_activity', 'desc');
    }

    /**
     * Get job applications for the user (candidate).
     */
    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * Check if user is a candidate.
     * NOTE: Candidates should eventually be moved to a separate candidates table.
     * This method is kept for backward compatibility during migration.
     */
    public function isCandidate(): bool
    {
        return $this->role === 'candidate' || ($this->role === 'user' && !$this->is_admin);
    }
    
    /**
     * Check if user is an employee (not a candidate).
     */
    public function isEmployee(): bool
    {
        return !$this->isCandidate() && ($this->is_admin || in_array($this->role, ['admin', 'hr_manager', 'editor', 'client']));
    }

    /**
     * Check if user is a client (company admin).
     */
    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    /**
     * Get the company this user belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
