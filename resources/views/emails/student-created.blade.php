<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Harita Music Academy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .credentials-box {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }
        .credentials-box h3 {
            margin: 0 0 15px;
            color: #667eea;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .credential-item {
            margin: 12px 0;
            display: flex;
            align-items: center;
        }
        .credential-label {
            font-weight: 600;
            color: #555;
            min-width: 100px;
        }
        .credential-value {
            color: #333;
            background-color: #fff;
            padding: 8px 15px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            border: 1px solid #e0e0e0;
            flex: 1;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
        }
        .button:hover {
            box-shadow: 0 6px 8px rgba(102, 126, 234, 0.4);
        }
        .info-text {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin: 15px 0;
        }
        .warning-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
        }
        .warning-box p {
            margin: 0;
            color: #856404;
            font-size: 14px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            color: #666;
            font-size: 13px;
            border-top: 1px solid #e0e0e0;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🎵 Welcome to Harita Music Academy</h1>
            <p>Your Musical Journey Begins Here</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Dear <strong>{{ $studentName }}</strong>,
            </div>
            
            <p class="info-text">
                Congratulations! Your enrollment at <strong>Harita Music Academy</strong> has been successfully completed. 
                We're thrilled to have you join our community of passionate musicians.
            </p>
            
            <div class="credentials-box">
                <h3>🔐 Your Login Credentials</h3>
                <div class="credential-item">
                    <span class="credential-label">Email:</span>
                    <span class="credential-value">{{ $email }}</span>
                </div>
                <div class="credential-item">
                    <span class="credential-label">Password:</span>
                    <span class="credential-value">{{ $password }}</span>
                </div>
            </div>
            
            <div class="warning-box">
                <p>
                    <strong>⚠️ Important:</strong> Please change your password after your first login for security purposes. 
                    Keep your credentials confidential and do not share them with anyone.
                </p>
            </div>
            
            <p class="info-text">
                You can now access your student dashboard to:
            </p>
            <ul class="info-text">
                <li>View your class schedule</li>
                <li>Check your credit balance</li>
                <li>Book classes with your teacher</li>
                <li>Access learning resources</li>
                <li>Track your progress</li>
            </ul>
            
            <div style="text-align: center;">
                <a href="{{ $loginUrl }}" class="button">Login to Your Dashboard</a>
            </div>
            
            <p class="info-text">
                If you have any questions or need assistance, please don't hesitate to contact our support team.
            </p>
            
            <p class="info-text">
                We look forward to supporting you on your musical journey!
            </p>
            
            <p class="info-text">
                Best regards,<br>
                <strong>Harita Music Academy Team</strong>
            </p>
        </div>
        
        <div class="footer">
            <p><strong>Harita Music Academy</strong></p>
            <p>Nurturing Musical Excellence Since 2026</p>
            <p>
                Email: <a href="mailto:info@haritamusicacademy.com">info@haritamusicacademy.com</a> | 
                Phone: +91 (123) 456-7890
            </p>
            <p style="margin-top: 15px; font-size: 12px; color: #999;">
                This is an automated email. Please do not reply to this message.
            </p>
            <p style="margin-top: 10px; font-size: 11px; color: #999;">
                © 2026 Harita Music Academy. All rights reserved. | 
                Developed by <a href="https://sitesoch.com" style="color: #667eea;">Sitesoch</a>
            </p>
        </div>
    </div>
</body>
</html>
