<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_application_id',
        'interview_type',
        'scheduled_at',
        'location',
        'notes',
        'result',
        'feedback',
        'test_submission_email',
        'test_document_path',
        'conducted_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function application()
    {
        // Explicitly specify the foreign key so Eloquent uses job_application_id
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    public function conductedBy()
    {
        return $this->belongsTo(User::class, 'conducted_by');
    }
}


