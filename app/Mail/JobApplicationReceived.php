<?php

namespace App\Mail;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public JobApplication $application)
    {
    }

    public function build(): self
    {
        $this->application->loadMissing('jobPost');
        
        return $this
            ->subject('New Job Application Received - ' . ($this->application->jobPost->title ?? 'Position'))
            ->view('emails.job.application-received', [
                'application' => $this->application,
            ]);
    }
}

