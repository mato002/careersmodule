<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class DocumentTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_type',
        'template_path',
        'name',
        'description',
        'notes',
        'version',
        'is_active',
        'created_by_user_id',
        'updated_by_user_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'version' => 'integer',
    ];

    /**
     * Get the user who created this template.
     */
    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get the user who last updated this template.
     */
    public function updatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }

    /**
     * Get the document type label.
     */
    public function getDocumentTypeLabelAttribute(): string
    {
        return match($this->document_type) {
            'offer_letter' => 'Offer Letter',
            'contract' => 'Contract',
            default => ucfirst(str_replace('_', ' ', $this->document_type)),
        };
    }

    /**
     * Get the template URL.
     */
    public function getTemplateUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->template_path);
    }

    /**
     * Check if template file exists.
     */
    public function hasTemplate(): bool
    {
        return $this->template_path && Storage::disk('public')->exists($this->template_path);
    }

    /**
     * Scope to get active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
