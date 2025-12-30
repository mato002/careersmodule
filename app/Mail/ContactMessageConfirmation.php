<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessageConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $message)
    {
    }

    public function build(): self
    {
        return $this->subject('We received your message - Fortress Lenders Ltd')
            ->view('emails.contact.confirmation')
            ->with([
                'contact' => $this->message,
            ]);
    }
}







