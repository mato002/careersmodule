<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageConfirmation;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('company')) {
            return back()
                ->withErrors(['name' => 'Please try again.'])
                ->withInput();
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'company' => ['nullable', 'prohibited'],
        ]);

        $data['status'] = 'new';

        $contactMessage = ContactMessage::create($data);

        $this->notifyTeam($contactMessage);
        $this->acknowledgeSender($contactMessage);

        return back()->with('status', 'Thank you! Your message has been received. Our team will contact you shortly.');
    }

    protected function notifyTeam(ContactMessage $contactMessage): void
    {
        $recipients = config('contact.notification_recipients', []);

        if (empty($recipients)) {
            return;
        }

        Mail::to($recipients)->send(new ContactMessageReceived($contactMessage));
    }

    protected function acknowledgeSender(ContactMessage $contactMessage): void
    {
        if (! $contactMessage->email) {
            return;
        }

        Mail::to($contactMessage->email)->send(new ContactMessageConfirmation($contactMessage));
    }
}
