@component('emails.components.message')
<h1>Message from Fortress Lenders</h1>

<p>Dear {{ $contactMessage->name }},</p>

<p>Thank you for contacting us. We have a response to your inquiry.</p>

@component('emails.components.panel')
<p style="margin: 0 0 12px 0;"><strong>Your Original Message:</strong></p>
<ul style="margin: 0; padding-left: 20px; color: #374151;">
    <li style="margin-bottom: 8px;"><strong>Subject:</strong> {{ $contactMessage->subject ?? 'General Inquiry' }}</li>
    <li style="margin-bottom: 0;"><strong>Submitted:</strong> {{ $contactMessage->created_at->format('F d, Y') }}</li>
</ul>
@endcomponent

<h2>Our Response</h2>

<div class="message-box">
{!! nl2br(e($reply->message)) !!}
</div>

<p>Thank you for choosing Fortress Lenders Ltd.</p>

<p>Best regards,<br>
<strong>Fortress Lenders Support Team</strong></p>
@endcomponent

