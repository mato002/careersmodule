<?php

namespace App\Mail;

use App\Models\JobApplication;
use App\Models\JobApplicationMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobApplicationReply extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public JobApplication $application,
        public JobApplicationMessage $message
    ) {
    }

    public function build(): self
    {
        $this->application->loadMissing('jobPost');
        $jobTitle = $this->application->jobPost->title ?? 'Your Application';
        $subject = 'Re: Your Job Application - ' . $jobTitle;
        
        return $this
            ->subject($subject)
            ->markdown('emails.job.application-reply', [
                'application' => $this->application,
                'message' => $this->message,
            ]);
    }
}

