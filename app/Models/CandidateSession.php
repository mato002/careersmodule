<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'session_id',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'platform',
        'location',
        'last_activity',
        'is_current',
    ];

    protected $casts = [
        'last_activity' => 'datetime',
        'is_current' => 'boolean',
    ];

    /**
     * Get the candidate that owns the session.
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Get active sessions for a candidate.
     */
    public function scopeActive($query)
    {
        return $query->where('last_activity', '>', now()->subMinutes(config('session.lifetime', 120)));
    }

    /**
     * Get the device icon based on device type.
     */
    public function getDeviceIconAttribute(): string
    {
        return match($this->device_type) {
            'mobile' => '📱',
            'tablet' => '📱',
            'desktop' => '💻',
            default => '🖥️',
        };
    }
}

