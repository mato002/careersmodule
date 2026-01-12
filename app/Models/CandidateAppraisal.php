<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateAppraisal extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'type',
        'title',
        'content',
        'rating',
        'strengths',
        'areas_for_improvement',
        'goals',
        'warning_level',
        'warning_date',
        'attachments',
        'status',
        'created_by_user_id',
        'acknowledged_at',
        'acknowledgment_notes',
    ];

    protected $casts = [
        'attachments' => 'array',
        'warning_date' => 'date',
        'acknowledged_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the candidate that owns the appraisal.
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Get the user who created the appraisal (HR).
     */
    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'performance_review' => 'Performance Review',
            'hr_communication' => 'HR Communication',
            'warning' => 'Warning',
            default => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    /**
     * Get warning level label.
     */
    public function getWarningLevelLabelAttribute(): ?string
    {
        if (!$this->warning_level) {
            return null;
        }
        
        return match($this->warning_level) {
            'verbal' => 'Verbal Warning',
            'written' => 'Written Warning',
            'final' => 'Final Warning',
            default => ucfirst($this->warning_level),
        };
    }

    /**
     * Check if appraisal is acknowledged.
     */
    public function isAcknowledged(): bool
    {
        return !empty($this->acknowledged_at);
    }

    /**
     * Get attachment URLs.
     */
    public function getAttachmentUrlsAttribute(): array
    {
        if (!$this->attachments) {
            return [];
        }

        return array_map(function($path) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
        }, $this->attachments);
    }
}
