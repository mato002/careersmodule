<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplicationReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_application_id',
        'reviewed_by',
        'decision',
        'review_notes',
        'regret_template',
        'pass_template',
    ];

    public function application()
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}


