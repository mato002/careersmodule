<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'email',
        'phone',
        'address',
        'api_key',
        'subscription_plan',
        'subscription_status',
        'subscription_expires_at',
        'is_active',
        'settings',
        'token_package_type',
        'token_limit_per_month',
        'token_alert_threshold',
        'ai_enabled',
        'ai_auto_sieve',
        'ai_threshold',
    ];

    protected $casts = [
        'subscription_expires_at' => 'datetime',
        'is_active' => 'boolean',
        'ai_enabled' => 'boolean',
        'ai_auto_sieve' => 'boolean',
        'ai_threshold' => 'decimal:1',
        'settings' => 'array',
        'token_limit_per_month' => 'integer',
    ];

    /**
     * Get token allocations for this company
     */
    public function tokenAllocations(): HasMany
    {
        return $this->hasMany(CompanyTokenAllocation::class);
    }

    /**
     * Get active token allocation
     */
    public function activeTokenAllocation()
    {
        return $this->tokenAllocations()
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->orderBy('allocated_at', 'desc')
            ->first();
    }

    /**
     * Get token usage logs
     */
    public function tokenUsageLogs(): HasMany
    {
        return $this->hasMany(TokenUsageLog::class);
    }

    /**
     * Get token usage summaries
     */
    public function tokenUsageSummaries(): HasMany
    {
        return $this->hasMany(CompanyTokenUsageSummary::class);
    }

    /**
     * Get current month token usage summary
     */
    public function currentMonthUsage()
    {
        $period = now()->startOfMonth()->format('Y-m-d');
        return $this->tokenUsageSummaries()
            ->where('period', $period)
            ->first();
    }

    /**
     * Get total remaining tokens
     */
    public function getTotalRemainingTokensAttribute(): int
    {
        $allocation = $this->activeTokenAllocation();
        return $allocation ? $allocation->remaining_tokens : 0;
    }

    /**
     * Check if company has enough tokens
     */
    public function hasEnoughTokens(int $requiredTokens): bool
    {
        return $this->total_remaining_tokens >= $requiredTokens;
    }

    /**
     * Check if subscription is active
     */
    public function isSubscriptionActive(): bool
    {
        return $this->subscription_status === 'active' 
            && ($this->subscription_expires_at === null || $this->subscription_expires_at->isFuture())
            && $this->is_active;
    }

    /**
     * Get users (admins) for this company
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the primary admin user for this company
     */
    public function primaryAdmin()
    {
        return $this->users()->where('role', 'client')->first();
    }
}

