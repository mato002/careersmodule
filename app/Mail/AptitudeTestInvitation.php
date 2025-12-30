<?php

namespace App\Mail;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AptitudeTestInvitation extends Mailable
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
        
        // Direct link to aptitude test
        $testUrl = route('aptitude-test.show', $this->application);
        
        return $this
            ->subject('Congratulations! You\'ve Passed Initial Screening - Aptitude Test Required')
            ->view('emails.job.aptitude-test-invitation', [
                'application' => $this->application,
                'statusUrl' => $statusUrl,
                'testUrl' => $testUrl,
            ]);
    }
}

