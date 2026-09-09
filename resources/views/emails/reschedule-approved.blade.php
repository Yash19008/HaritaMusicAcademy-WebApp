<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Reschedule Approved</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            background: linear-gradient(135deg, #51040e 0%, #7d0a1b 100%);
            color: #ffffff;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
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
            color: #2d3748;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #51040e 0%, #7d0a1b 100%);
            color: #ffffff !important;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 4px 6px rgba(81, 4, 14, 0.3);
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        .button:hover {
            box-shadow: 0 6px 8px rgba(81, 4, 14, 0.4);
            background: linear-gradient(135deg, #7d0a1b 0%, #51040e 100%);
        }
        .info-text {
            color: #4a5568;
            font-size: 15px;
            line-height: 1.6;
            margin: 15px 0;
        }
        /* Specific blocks for credentials */
        .credentials-box, .details-box {
            background-color: #f8f9fa;
            border-left: 4px solid #51040e;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .credentials-box h3, .details-box h3 {
            margin: 0 0 15px;
            color: #51040e;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
        }
        .credential-item, .detail-item {
            margin: 12px 0;
            display: flex;
            align-items: center;
        }
        .credential-label {
            font-weight: 600;
            color: #4a5568;
            min-width: 100px;
        }
        .detail-label {
            font-weight: 600;
            color: #4a5568;
            min-width: 130px;
        }
        .credential-value {
            color: #2d3748;
            background-color: #fff;
            padding: 8px 15px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            border: 1px solid #e2e8f0;
            flex: 1;
        }
        .detail-value {
            color: #2d3748;
            background-color: #fff;
            padding: 8px 15px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            flex: 1;
            font-weight: 500;
        }
        .warning-box {
            background-color: #fcf8f8;
            border-left: 4px solid #51040e;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
        }
        .warning-box p {
            margin: 0;
            color: #51040e;
            font-size: 14px;
        }
        .notes-box {
            background-color: #fffaf0;
            border: 1px dashed #ed8936;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
            font-style: italic;
            color: #c05621;
        }
        .footer {
            background-color: #fcfbf8;
            padding: 30px;
            text-align: center;
            color: #718096;
            font-size: 13px;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer a {
            color: #51040e;
            text-decoration: none;
            font-weight: 500;
        }
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .schedule-table th {
            background-color: #f8f9fa;
            color: #51040e;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0;
        }
        .schedule-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
        }
        .schedule-table tr:last-child td {
            border-bottom: none;
        }
    </style>


</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>✅ Class Reschedule Approved</h1>
        </div>
        
        <div class="content">
            <p class="info-text">
                Good news! The class reschedule request has been approved. Your Google Calendar event has been automatically updated.
            </p>
            
            <div class="details-box">
                <h3>New Schedule Details</h3>
                <div class="detail-item">
                    <span class="detail-label">Instrument:</span>
                    <span class="detail-value">{{ $booking->instrument }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Student:</span>
                    <span class="detail-value">{{ $booking->student->user->name ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Teacher:</span>
                    <span class="detail-value">{{ $booking->teacher->user->name ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">New Date:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($booking->starts_at)->format('l, F j, Y') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">New Time:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($booking->starts_at)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->ends_at)->format('h:i A') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Meet Link:</span>
                    <span class="detail-value">
                        <a href="{{ $joinUrl }}" style="color: #059669; text-decoration: none;">Join Google Meet</a>
                    </span>
                </div>
            </div>
            
            <p class="info-text">
                Please make sure to join the class on time using the provided Google Meet link.
            </p>
            
            <div style="text-align: center;">
                <a href="{{ url('/') }}" class="button">Go to Dashboard</a>
            </div>
            
            <p class="info-text">
                Thank you,<br>
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
