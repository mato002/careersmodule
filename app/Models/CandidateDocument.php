<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'document_type',
        'uploaded_by',
        'template_path',
        'filled_path',
        'status',
        'notes',
        'uploaded_by_user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the candidate that owns the document.
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Get the user who uploaded the document (HR).
     */
    public function uploadedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }

    /**
     * Get document type label.
     */
    public function getDocumentTypeLabelAttribute(): string
    {
        return match($this->document_type) {
            'offer_letter' => 'Offer Letter',
            'contract' => 'Contract',
            'id' => 'National ID',
            'kra' => 'KRA PIN Certificate',
            'sha' => 'SHA Certificate',
            default => ucfirst(str_replace('_', ' ', $this->document_type)),
        };
    }

    /**
     * Check if document has template (HR uploaded).
     */
    public function hasTemplate(): bool
    {
        return !empty($this->template_path);
    }

    /**
     * Check if document has filled version (candidate uploaded).
     */
    public function hasFilledVersion(): bool
    {
        return !empty($this->filled_path);
    }

    /**
     * Get template URL.
     */
    public function getTemplateUrlAttribute(): ?string
    {
        if ($this->template_path) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->template_path);
        }
        return null;
    }

    /**
     * Get filled version URL.
     */
    public function getFilledUrlAttribute(): ?string
    {
        if ($this->filled_path) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->filled_path);
        }
        return null;
    }
}
