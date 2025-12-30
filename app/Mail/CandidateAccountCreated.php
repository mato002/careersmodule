<?php

namespace App\Mail;

use App\Models\Candidate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidateAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public Candidate $candidate;
    public string $temporaryPassword;
    public string $loginUrl;

    public function __construct(Candidate $candidate, string $temporaryPassword)
    {
        $this->candidate = $candidate;
        $this->temporaryPassword = $temporaryPassword;
        $this->loginUrl = route('login');
    }

    public function build(): self
    {
        return $this
            ->subject('Your Candidate Account - Fortress Lenders')
            ->view('emails.candidate.account-created');
    }
}

