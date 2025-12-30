<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Fortress Lenders' }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #1f2937;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .email-header {
            background: linear-gradient(135deg, #0f766e 0%, #134e4a 100%);
            padding: 45px 30px;
            text-align: center;
        }
        .logo-container {
            display: inline-block;
            margin-bottom: 20px;
        }
        .logo-box {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #0f766e 0%, #134e4a 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.2);
        }
        .logo-text {
            color: #fbbf24;
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -1px;
        }
        .company-name {
            color: #ffffff;
            font-size: 26px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .tagline {
            color: #d1d5db;
            font-size: 13px;
            margin-top: 8px;
            font-weight: 400;
        }
        .email-body {
            padding: 50px 40px;
        }
        .email-body h1 {
            font-size: 24px;
            font-weight: 700;
            color: #0f766e;
            margin: 0 0 25px 0;
            line-height: 1.3;
        }
        .email-body h2 {
            font-size: 20px;
            font-weight: 600;
            color: #1f2937;
            margin: 30px 0 18px 0;
        }
        .email-body p {
            margin: 0 0 20px 0;
            color: #374151;
            font-size: 15px;
            line-height: 1.7;
        }
        .email-body strong {
            color: #1f2937;
            font-weight: 600;
        }
        .message-box {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-left: 4px solid #0f766e;
            border-radius: 6px;
            padding: 28px;
            margin: 30px 0;
            line-height: 1.8;
            color: #374151;
            font-size: 15px;
        }
        .email-footer {
            background-color: #1f2937;
            color: #9ca3af;
            padding: 40px 30px;
            text-align: center;
            font-size: 12px;
            line-height: 1.6;
        }
        .email-footer strong {
            color: #ffffff;
            font-weight: 600;
        }
        .email-footer a {
            color: #14b8a6;
            text-decoration: none;
        }
        .email-footer a:hover {
            text-decoration: underline;
        }
        .divider {
            height: 1px;
            background-color: #374151;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <div class="logo-container">
                <div class="logo-box">
                    <span class="logo-text">F</span>
                </div>
            </div>
            <h1 class="company-name">Fortress Lenders</h1>
            <p class="tagline">The Force Of Possibilities</p>
        </div>

        <div class="email-body">
            {{ $slot }}
        </div>

        <div class="email-footer">
            <p style="margin: 0 0 16px 0;"><strong>Fortress Lenders Ltd</strong></p>
            <p style="margin: 0 0 24px 0;">Head Office: Fortress Hse, Nakuru County<br>Barnabas Muguga Opp. Epic ridge Academy</p>
            <p style="margin: 0 0 24px 0;">
                <strong>Contact Us:</strong><br>
                Phone: +254 743 838 312<br>
                Email: <a href="mailto:info@fortresslenders.com">info@fortresslenders.com</a>
            </p>
            <div class="divider"></div>
            <p style="font-size: 11px; color: #6b7280; margin: 0;">
                This email was sent from Fortress Lenders Ltd. Please do not reply directly to this email.
            </p>
        </div>
    </div>
</body>
</html>
