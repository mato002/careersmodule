@component('emails.components.message')
<h1>New Contact Message</h1>

<p>A new inquiry was submitted via the Fortress Lenders website.</p>

@component('emails.components.panel')
<p style="margin: 0 0 12px 0;"><strong>Contact Information:</strong></p>
<ul style="margin: 0 0 20px 0; padding-left: 20px; color: #374151;">
    <li style="margin-bottom: 8px;"><strong>Name:</strong> {{ $contact->name }}</li>
    <li style="margin-bottom: 8px;"><strong>Email:</strong> {{ $contact->email }}</li>
    <li style="margin-bottom: 8px;"><strong>Phone:</strong> {{ $contact->phone ?: 'N/A' }}</li>
    <li style="margin-bottom: 8px;"><strong>Subject:</strong> {{ $contact->subject ?: '—' }}</li>
    <li style="margin-bottom: 0;"><strong>Submitted:</strong> {{ $contact->created_at->format('M d, Y g:i A') }}</li>
</ul>
<p style="margin: 0;"><strong>Message:</strong><br>
{{ $contact->message }}</p>
@endcomponent

@component('emails.components.button', ['url' => config('app.url') . '/admin/contact-messages/' . $contact->id])
View in Admin Dashboard
@endcomponent

<p>Best regards,<br>
<strong>Fortress Lenders Support Team</strong></p>
@endcomponent







