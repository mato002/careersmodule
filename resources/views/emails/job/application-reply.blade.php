@php
use Illuminate\Support\Str;
@endphp

@component('emails.components.message')
<h1>Message from Fortress Lenders</h1>

<p>Dear {{ $application->full_name }},</p>

<p>Thank you for your job application. We have a message for you regarding your application.</p>

@component('emails.components.panel')
<p style="margin: 0 0 12px 0;"><strong>Your Application Details:</strong></p>
<ul style="margin: 0; padding-left: 20px; color: #374151;">
    <li style="margin-bottom: 8px;"><strong>Position:</strong> {{ $application->jobPost->title ?? 'N/A' }}</li>
    <li style="margin-bottom: 8px;"><strong>Application Date:</strong> {{ $application->created_at->format('F d, Y') }}</li>
    <li style="margin-bottom: 0;"><strong>Status:</strong> {{ Str::headline($application->status ?? 'pending') }}</li>
</ul>
@endcomponent

<h2>Our Message</h2>

<div class="message-box">
{!! nl2br(e($message->message)) !!}
</div>

@if($application->status === 'approved' || $application->status === 'accepted')
@component('emails.components.button', ['url' => route('careers.index')])
View Career Opportunities
@endcomponent
@endif

<p>Thank you for your interest in joining Fortress Lenders Ltd.</p>

<p>Best regards,<br>
<strong>Fortress Lenders HR Team</strong></p>
@endcomponent

