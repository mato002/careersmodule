@component('emails.components.message')
<h1>Congratulations! You've Passed Initial Screening</h1>

<p>Dear {{ $application->first_name ?? 'Candidate' }},</p>

<p>We are pleased to inform you that your application for the <strong>{{ $application->jobPost->title ?? 'position' }}</strong> position has successfully passed our initial AI screening process.</p>

<div class="message-box">
    <p style="margin: 0; font-size: 16px; color: #0f766e; font-weight: 600;">🎉 Next Step: Aptitude Test</p>
    <p style="margin: 12px 0 0 0; color: #374151;">
        To proceed to the next stage of our hiring process, you need to complete an online aptitude test. This test will assess your numerical, logical, verbal, and scenario-based reasoning skills.
    </p>
</div>

<h2>Test Details:</h2>

<ul style="margin: 0; padding-left: 20px; color: #374151;">
    <li style="margin-bottom: 8px;"><strong>Duration:</strong> Approximately 30-45 minutes</li>
    <li style="margin-bottom: 8px;"><strong>Format:</strong> Multiple choice questions</li>
    <li style="margin-bottom: 8px;"><strong>Sections:</strong> Numerical, Logical, Verbal, and Scenario-based reasoning</li>
    <li style="margin-bottom: 0;"><strong>Deadline:</strong> Please complete within 7 days of receiving this email</li>
</ul>

<h2>Ready to Start?</h2>

<p>Click the button below to begin your aptitude test. Make sure you have a stable internet connection and can dedicate uninterrupted time to complete the test.</p>

@component('emails.components.button', ['url' => $testUrl])
Start Aptitude Test
@endcomponent

<p style="margin-top: 30px; font-size: 14px; color: #6b7280;">
    <strong>Note:</strong> You can also access your application status and test at any time using the link below:
</p>

<p style="margin: 15px 0; text-align: center;">
    <a href="{{ $statusUrl }}" style="color: #0f766e; text-decoration: underline; font-size: 14px;">View Application Status</a>
</p>

<h2>What Happens After the Test?</h2>

<p>Once you complete the aptitude test:</p>

<ul style="margin: 0; padding-left: 20px; color: #374151;">
    <li style="margin-bottom: 8px;">Your results will be automatically scored</li>
    <li style="margin-bottom: 8px;">If you pass, you'll be eligible for the next interview stage</li>
    <li style="margin-bottom: 0;">You'll receive further instructions via email</li>
</ul>

<p>We wish you the best of luck with your test!</p>

<p>Sincerely,<br>
<strong>Fortress Lenders Hiring Team</strong></p>
@endcomponent

