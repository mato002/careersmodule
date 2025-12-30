@component('emails.components.message')
<h1>Your Candidate Account Has Been Created</h1>

<p>Dear {{ $candidate->name }},</p>

<p>Thank you for applying to Fortress Lenders! We've automatically created a candidate account for you so you can track your application status and access your dashboard.</p>

<div class="message-box" style="background-color: #f0fdf4; border-left: 4px solid #10b981; padding: 16px; margin: 20px 0;">
    <p style="margin: 0; font-size: 16px; color: #065f46; font-weight: 600;">🔐 Your Login Credentials</p>
    <p style="margin: 12px 0 0 0; color: #374151;">
        <strong>Email:</strong> {{ $candidate->email }}<br>
        <strong>Temporary Password:</strong> <code style="background-color: #f3f4f6; padding: 4px 8px; border-radius: 4px; font-family: monospace;">{{ $temporaryPassword }}</code>
    </p>
    <p style="margin: 12px 0 0 0; color: #6b7280; font-size: 14px;">
        <strong>⚠️ Important:</strong> Please change your password after your first login for security.
    </p>
</div>

<h2>Access Your Dashboard</h2>

<p>You can now log in to your candidate dashboard to:</p>

<ul style="margin: 0; padding-left: 20px; color: #374151;">
    <li style="margin-bottom: 8px;">View all your job applications in one place</li>
    <li style="margin-bottom: 8px;">Track application status and next steps</li>
    <li style="margin-bottom: 8px;">Take aptitude tests when eligible</li>
    <li style="margin-bottom: 8px;">View AI evaluation results</li>
    <li style="margin-bottom: 0;">Update your profile information</li>
</ul>

@component('emails.components.button', ['url' => $loginUrl])
Login to Dashboard
@endcomponent

<h2>What Happens Next?</h2>

<p>Your application is being processed through our AI screening system. You'll receive email updates at each stage:</p>

<ul style="margin: 0; padding-left: 20px; color: #374151;">
    <li style="margin-bottom: 8px;"><strong>Initial Screening:</strong> AI evaluation (automatic)</li>
    <li style="margin-bottom: 8px;"><strong>If Passed:</strong> Aptitude test invitation</li>
    <li style="margin-bottom: 8px;"><strong>If Passed:</strong> Interview scheduling</li>
    <li style="margin-bottom: 0;"><strong>Final Decision:</strong> Hiring notification</li>
</ul>

<p style="margin-top: 20px; font-size: 14px; color: #6b7280;">
    <strong>Security Tip:</strong> Never share your login credentials with anyone. If you forget your password, you can reset it using the "Forgot Password" link on the login page.
</p>

<p>Sincerely,<br>
<strong>Fortress Lenders Hiring Team</strong></p>
@endcomponent

