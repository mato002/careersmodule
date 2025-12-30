<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyTokenUsageSummary extends Model
{
    use HasFactory;

    protected $table = 'company_token_usage_summary';

    protected $fillable = [
        'company_id',
        'period',
        'total_tokens_used',
        'cv_parse_tokens',
        'cv_analyze_tokens',
        'scoring_tokens',
        'decision_tokens',
        'other_tokens',
        'total_cost',
        'operations_count',
    ];

    protected $casts = [
        'period' => 'date',
        'total_tokens_used' => 'integer',
        'cv_parse_tokens' => 'integer',
        'cv_analyze_tokens' => 'integer',
        'scoring_tokens' => 'integer',
        'decision_tokens' => 'integer',
        'other_tokens' => 'integer',
        'total_cost' => 'decimal:2',
        'operations_count' => 'integer',
    ];

    /**
     * Get the company
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Update or create summary for a period
     */
    public static function updateSummary(int $companyId, string $period, array $data): self
    {
        return self::updateOrCreate(
            [
                'company_id' => $companyId,
                'period' => $period,
            ],
            $data
        );
    }

    /**
     * Increment usage for a specific operation type
     */
    public function incrementUsage(string $operationType, int $tokens, float $cost): void
    {
        $this->total_tokens_used += $tokens;
        $this->total_cost += $cost;
        $this->operations_count += 1;

        // Increment specific operation type
        $field = match($operationType) {
            'cv_parse' => 'cv_parse_tokens',
            'cv_analyze' => 'cv_analyze_tokens',
            'scoring' => 'scoring_tokens',
            'decision' => 'decision_tokens',
            default => 'other_tokens',
        };

        $this->$field += $tokens;
        $this->save();
    }
}

