<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyTokenAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'token_purchase_id',
        'allocated_tokens',
        'used_tokens',
        'remaining_tokens',
        'allocated_at',
        'expires_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'allocated_tokens' => 'integer',
        'used_tokens' => 'integer',
        'remaining_tokens' => 'integer',
        'allocated_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the company
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the token purchase
     */
    public function tokenPurchase(): BelongsTo
    {
        return $this->belongsTo(TokenPurchase::class, 'token_purchase_id');
    }

    /**
     * Get usage logs for this allocation
     */
    public function usageLogs(): HasMany
    {
        return $this->hasMany(TokenUsageLog::class, 'company_token_allocation_id');
    }

    /**
     * Check if allocation is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' 
            && $this->remaining_tokens > 0
            && ($this->expires_at === null || $this->expires_at->isFuture());
    }

    /**
     * Deduct tokens from this allocation
     */
    public function deductTokens(int $tokens): bool
    {
        if ($this->remaining_tokens < $tokens) {
            return false;
        }

        $this->used_tokens += $tokens;
        $this->remaining_tokens -= $tokens;

        if ($this->remaining_tokens <= 0) {
            $this->status = 'exhausted';
        }

        return $this->save();
    }

    /**
     * Check if allocation is expired
     */
    public function isExpired(): bool
    {
        if ($this->expires_at === null) {
            return false;
        }

        if ($this->expires_at->isPast()) {
            $this->update(['status' => 'expired']);
            return true;
        }

        return false;
    }
}

