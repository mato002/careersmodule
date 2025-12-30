<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TokenPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_date',
        'total_tokens',
        'cost_per_token',
        'total_cost',
        'provider',
        'status',
        'expires_at',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'total_tokens' => 'integer',
        'cost_per_token' => 'decimal:8',
        'total_cost' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    /**
     * Get allocations from this purchase
     */
    public function allocations(): HasMany
    {
        return $this->hasMany(CompanyTokenAllocation::class, 'token_purchase_id');
    }

    /**
     * Get total allocated tokens from this purchase
     */
    public function getTotalAllocatedTokensAttribute(): int
    {
        return $this->allocations()->sum('allocated_tokens');
    }

    /**
     * Get remaining tokens in this purchase
     */
    public function getRemainingTokensAttribute(): int
    {
        return $this->total_tokens - $this->total_allocated_tokens;
    }

    /**
     * Check if purchase is exhausted
     */
    public function isExhausted(): bool
    {
        return $this->remaining_tokens <= 0;
    }

    /**
     * Check if purchase is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}

