@component('emails.components.message')
<h1>We Received Your Message</h1>

<p>Hello {{ $contact->name }},</p>

<p>Thank you for contacting Fortress Lenders Ltd. We have received your message and one of our team members will follow up shortly.</p>

@component('emails.components.panel')
<p style="margin: 0 0 12px 0;"><strong>Your submission:</strong></p>
<ul style="margin: 0; padding-left: 20px; color: #374151;">
    <li style="margin-bottom: 8px;"><strong>Subject:</strong> {{ $contact->subject ?: 'General inquiry' }}</li>
    <li style="margin-bottom: 0;"><strong>Message:</strong> {{ $contact->message }}</li>
</ul>
@endcomponent

<p>If your request is urgent, please call us on +254 743 838 312 or email info@fortresslenders.com.</p>

<p>Thank you for choosing Fortress Lenders Ltd.</p>

<p>Best regards,<br>
<strong>Fortress Lenders Support Team</strong></p>
@endcomponent







