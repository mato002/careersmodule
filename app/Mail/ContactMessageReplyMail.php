<?php

namespace App\Mail;

use App\Models\ContactMessage;
use App\Models\ContactMessageReply as ContactMessageReplyModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessageReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactMessage $contactMessage,
        public ContactMessageReplyModel $reply
    ) {
    }

    public function build(): self
    {
        $subject = 'Re: ' . ($this->contactMessage->subject ?? 'Your Inquiry');
        
        return $this
            ->subject($subject)
            ->markdown('emails.contact.reply', [
                'contactMessage' => $this->contactMessage,
                'reply' => $this->reply,
            ]);
    }
}

