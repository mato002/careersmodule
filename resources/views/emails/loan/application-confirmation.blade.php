@component('emails.components.message')
<h1>Loan Application Received</h1>

<p>Dear {{ $application->full_name }},</p>

<p>Thank you for applying for a loan with Fortress Lenders Ltd. We have received your application and our team will review it shortly.</p>

@component('emails.components.panel')
<p style="margin: 0 0 12px 0;"><strong>Application Summary:</strong></p>
<ul style="margin: 0; padding-left: 20px; color: #374151;">
    <li style="margin-bottom: 8px;"><strong>Loan Type:</strong> {{ $application->loan_type }}</li>
    <li style="margin-bottom: 8px;"><strong>Amount Requested:</strong> KES {{ number_format($application->amount_requested, 2) }}</li>
    <li style="margin-bottom: 0;"><strong>Repayment Period:</strong> {{ $application->repayment_period }}</li>
</ul>
@endcomponent

<p>If we need any additional information, we will contact you using the phone number or email you provided.</p>

<p>Thank you for choosing Fortress Lenders Ltd.</p>

<p>Best regards,<br>
<strong>Fortress Lenders Support Team</strong></p>
@endcomponent
