<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AISievingDecision extends Model
{
    use HasFactory;

    protected $table = 'ai_sieving_decisions';

    protected $fillable = [
        'job_application_id',
        'ai_decision',
        'ai_confidence',
        'ai_score',
        'ai_reasoning',
        'ai_strengths',
        'ai_weaknesses',
        'human_override',
        'human_decision',
        'human_feedback',
        'was_ai_correct',
    ];

    protected $casts = [
        'ai_confidence' => 'decimal:2',
        'ai_strengths' => 'array',
        'ai_weaknesses' => 'array',
        'human_override' => 'boolean',
        'was_ai_correct' => 'boolean',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }
}

