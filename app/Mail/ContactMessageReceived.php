<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessageReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $message)
    {
    }

    public function build(): self
    {
        return $this->subject('New contact message from '.$this->message->name)
            ->view('emails.contact.received')
            ->with([
                'contact' => $this->message,
            ]);
    }
}







