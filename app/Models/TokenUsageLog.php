<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TokenUsageLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'job_application_id',
        'company_token_allocation_id',
        'operation_type',
        'tokens_used',
        'input_tokens',
        'output_tokens',
        'model_used',
        'cost_per_token',
        'total_cost',
        'metadata',
    ];

    protected $casts = [
        'tokens_used' => 'integer',
        'input_tokens' => 'integer',
        'output_tokens' => 'integer',
        'cost_per_token' => 'decimal:8',
        'total_cost' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Get the company
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the job application
     */
    public function jobApplication(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class);
    }

    /**
     * Get the token allocation
     */
    public function tokenAllocation(): BelongsTo
    {
        return $this->belongsTo(CompanyTokenAllocation::class, 'company_token_allocation_id');
    }

    /**
     * Scope for operation type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('operation_type', $type);
    }

    /**
     * Scope for date range
     */
    public function scopeInPeriod($query, $startDate, $endDate = null)
    {
        $query->where('created_at', '>=', $startDate);
        
        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }
        
        return $query;
    }
}

