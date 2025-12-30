@php
use Illuminate\Support\Str;
@endphp

@component('emails.components.message')
<h1>Message from Fortress Lenders</h1>

<p>Dear {{ $application->full_name }},</p>

<p>Thank you for your loan application. We have a message for you regarding your application.</p>

@component('emails.components.panel')
<p style="margin: 0 0 12px 0;"><strong>Your Application Details:</strong></p>
<ul style="margin: 0; padding-left: 20px; color: #374151;">
    <li style="margin-bottom: 8px;"><strong>Loan Type:</strong> {{ $application->loan_type }}</li>
    <li style="margin-bottom: 8px;"><strong>Amount Requested:</strong> KES {{ number_format($application->amount_requested, 2) }}</li>
    <li style="margin-bottom: 0;"><strong>Status:</strong> {{ Str::headline($application->status) }}</li>
</ul>
@endcomponent

<h2>Our Message</h2>

<div class="message-box">
{!! nl2br(e($message->message)) !!}
</div>

@if($application->status === 'approved')
@component('emails.components.button', ['url' => route('loan.apply')])
View Loan Products
@endcomponent
@endif

<p>Thank you for choosing Fortress Lenders Ltd.</p>

<p>Best regards,<br>
<strong>Fortress Lenders Support Team</strong></p>
@endcomponent

