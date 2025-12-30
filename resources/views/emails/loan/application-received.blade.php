@component('emails.components.message')
<h1>New Loan Application Received</h1>

<p>A new loan application has been submitted on the Fortress Lenders website.</p>

@component('emails.components.panel')
<p style="margin: 0 0 12px 0;"><strong>Applicant Information:</strong></p>
<ul style="margin: 0 0 20px 0; padding-left: 20px; color: #374151;">
    <li style="margin-bottom: 8px;"><strong>Full Name:</strong> {{ $application->full_name }}</li>
    <li style="margin-bottom: 8px;"><strong>Phone:</strong> {{ $application->phone }}</li>
    <li style="margin-bottom: 8px;"><strong>Email:</strong> {{ $application->email ?? 'N/A' }}</li>
    <li style="margin-bottom: 8px;"><strong>Date of Birth:</strong> {{ optional($application->date_of_birth)->format('d M Y') ?? 'N/A' }}</li>
    <li style="margin-bottom: 8px;"><strong>Town:</strong> {{ $application->town ?? 'N/A' }}</li>
    <li style="margin-bottom: 8px;"><strong>Residence:</strong> {{ $application->residence ?? 'N/A' }}</li>
    <li style="margin-bottom: 8px;"><strong>Client Type:</strong> {{ ucfirst($application->client_type ?? 'N/A') }}</li>
    <li style="margin-bottom: 8px;"><strong>Loan Type:</strong> {{ $application->loan_type }}</li>
    <li style="margin-bottom: 8px;"><strong>Amount Requested:</strong> KES {{ number_format($application->amount_requested, 2) }}</li>
    <li style="margin-bottom: 0;"><strong>Repayment Period:</strong> {{ $application->repayment_period }}</li>
</ul>
<p style="margin: 0;"><strong>Purpose of Loan:</strong><br>
{{ $application->purpose ?? 'N/A' }}</p>
@endcomponent

@component('emails.components.button', ['url' => route('admin.loan-applications.index')])
View in Admin Dashboard
@endcomponent

<p>Best regards,<br>
<strong>Fortress Lenders Support Team</strong></p>
@endcomponent
