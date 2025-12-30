@component('emails.components.message')
<h1>New Job Application Received</h1>

<p>A new job application has been submitted through the Fortress Lenders website.</p>

@component('emails.components.panel')
<p style="margin: 0 0 12px 0;"><strong>Application Details:</strong></p>
<ul style="margin: 0 0 20px 0; padding-left: 20px; color: #374151;">
    <li style="margin-bottom: 8px;"><strong>Applicant Name:</strong> {{ $application->name }}</li>
    <li style="margin-bottom: 8px;"><strong>Email:</strong> {{ $application->email }}</li>
    <li style="margin-bottom: 8px;"><strong>Phone:</strong> {{ $application->phone ?: 'N/A' }}</li>
    <li style="margin-bottom: 8px;"><strong>Position:</strong> {{ $application->jobPost->title ?? 'N/A' }}</li>
    <li style="margin-bottom: 8px;"><strong>Department:</strong> {{ $application->jobPost->department ?? 'N/A' }}</li>
    <li style="margin-bottom: 8px;"><strong>Availability Date:</strong> {{ $application->availability_date ? \Carbon\Carbon::parse($application->availability_date)->format('M d, Y') : 'N/A' }}</li>
    <li style="margin-bottom: 0;"><strong>Submitted:</strong> {{ $application->created_at->format('M d, Y g:i A') }}</li>
</ul>

@if($application->education_level)
<p style="margin: 0 0 12px 0;"><strong>Education:</strong> {{ $application->education_level }} 
@if($application->area_of_study) in {{ $application->area_of_study }}@endif
@if($application->institution) from {{ $application->institution }}@endif
</p>
@endif

@if($application->current_job_title)
<p style="margin: 0 0 12px 0;"><strong>Current Position:</strong> {{ $application->current_job_title }}
@if($application->current_company) at {{ $application->current_company }}@endif
</p>
@endif

@if($application->ai_summary)
<p style="margin: 0 0 12px 0;"><strong>AI Summary:</strong><br>
{{ \Illuminate\Support\Str::limit($application->ai_summary, 200) }}</p>
@endif
@endcomponent

@component('emails.components.button', ['url' => config('app.url') . '/admin/job-applications/' . $application->id])
View Application in Admin Dashboard
@endcomponent

<p>Best regards,<br>
<strong>Fortress Lenders Hiring Team</strong></p>
@endcomponent

