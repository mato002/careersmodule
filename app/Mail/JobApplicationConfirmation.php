<?php

namespace App\Mail;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobApplicationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public JobApplication $application)
    {
    }

    public function build(): self
    {
        $this->application->loadMissing('jobPost');
        
        // Generate secure token for status page access
        $token = md5($this->application->email . $this->application->id . config('app.key'));
        $statusUrl = route('application.status', [
            'application' => $this->application->id,
            'token' => $token
        ]);
        
        return $this
            ->subject('Thank You for Applying to Fortress Lenders Limited - Application Received')
            ->view('emails.job.application-confirmation', [
                'application' => $this->application,
                'statusUrl' => $statusUrl,
            ]);
    }
}

