<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvParsedData extends Model
{
    use HasFactory;

    protected $table = 'cv_parsed_data';

    protected $fillable = [
        'job_application_id',
        'parsed_name',
        'parsed_email',
        'parsed_phone',
        'parsed_address',
        'parsed_work_experience',
        'parsed_education',
        'parsed_skills',
        'parsed_certifications',
        'parsed_languages',
        'parsed_projects',
        'raw_text',
        'parser_version',
        'parsing_confidence',
        'parsed_at',
    ];

    protected $casts = [
        'parsed_work_experience' => 'array',
        'parsed_education' => 'array',
        'parsed_skills' => 'array',
        'parsed_certifications' => 'array',
        'parsed_languages' => 'array',
        'parsed_projects' => 'array',
        'parsing_confidence' => 'decimal:2',
        'parsed_at' => 'datetime',
    ];

    public function jobApplication(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class);
    }
}


