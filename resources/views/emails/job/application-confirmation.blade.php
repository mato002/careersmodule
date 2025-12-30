@component('emails.components.message')
<h1>Thank You for Applying</h1>

<p>Dear Candidate,</p>

<p>Thank you for your interest in Fortress Lenders and for taking the time to apply for a role in our organization.</p>

<p>This message confirms that we have successfully received your application materials, including your resume and cover letter, through our online portal.</p>

<p>We appreciate your interest in joining our team. Our hiring team will now carefully review your qualifications against the requirements of the role.</p>

<h2>What Happens Next:</h2>

<ul style="margin: 0; padding-left: 20px; color: #374151;">
    <li style="margin-bottom: 8px;"><strong>Review Timeline:</strong> Due to the high volume of applications we receive, the review process may take 2-3 weeks.</li>
    <li style="margin-bottom: 8px;"><strong>Next Steps:</strong> Only candidates whose skills and experience closely match the position requirements will be contacted for an interview.</li>
    <li style="margin-bottom: 0;"><strong>Status:</strong> If you are not selected to move forward, you will be notified via email once the position is filled.</li>
</ul>

<p>We appreciate your patience during this process.</p>

<h2>Track Your Application</h2>

<p>We've automatically created a candidate account for you! You can access your dashboard to track all your applications in one place.</p>

<p><strong>📧 Check your email</strong> for your login credentials (sent separately). Once you log in, you'll be able to:</p>

<ul style="margin: 0; padding-left: 20px; color: #374151;">
    <li style="margin-bottom: 8px;">View all your applications</li>
    <li style="margin-bottom: 8px;">Track status and next steps</li>
    <li style="margin-bottom: 8px;">Take aptitude tests when eligible</li>
    <li style="margin-bottom: 0;">View detailed application information</li>
</ul>

<p style="margin-top: 20px;">You can also check your application status using the link below:</p>

@component('emails.components.button', ['url' => $statusUrl])
View Application Status
@endcomponent

<p style="margin-top: 20px; font-size: 14px; color: #6b7280;">
    <strong>Note:</strong> If you haven't received your login credentials email, please check your spam folder or contact us. You can also use the "Forgot Password" link on the login page if needed.
</p>

<p>Sincerely,<br>
<strong>Fortress Lenders Hiring Team</strong></p>
@endcomponent

