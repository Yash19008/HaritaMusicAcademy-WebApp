<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Booking Confirmation</title>
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
            <h1>🎵 Class Booking Confirmed!</h1>
            <p>Your {{ $booking->instrument }} Session is Scheduled</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Dear <strong>{{ $isTeacher ? ($booking->teacher->user->name ?? 'Teacher') : ($targetStudent->name ?? $booking->student->name ?? 'Student') }}</strong>,
            </div>
            
            <p class="info-text">
                Your upcoming music class has been successfully scheduled. Here are the details for your session:
            </p>
            
            <div class="details-box">
                <h3>📅 Session Details</h3>
                <div class="detail-item">
                    <span class="detail-label">Instrument:</span>
                    <span class="detail-value">{{ $booking->instrument }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Date & Time:</span>
                    <span class="detail-value">{{ $booking->starts_at->format('l, F j, Y \a\t h:i A') }} - {{ $booking->ends_at->format('h:i A') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">{{ $isTeacher ? 'Student:' : 'Instructor:' }}</span>
                    <span class="detail-value">{{ $isTeacher ? ($booking->student_group_id ? $booking->studentGroup->name : ($booking->student->name ?? 'N/A')) : ($booking->teacher->user->name ?? 'N/A') }}</span>
                </div>
            </div>
            
            @if($booking->notes)
            <div class="notes-box">
                <strong>Additional Notes:</strong><br>
                {{ $booking->notes }}
            </div>
            @endif
            
            @if($booking->google_meet_link)
            <div style="text-align: center;">
                <a href="{{ $booking->google_meet_link }}" class="button">Join Google Meet</a>
            </div>
            @else
            <p class="info-text" style="text-align: center; background: #eef2ff; padding: 15px; border-radius: 8px;">
                The Google Meet link will be provided closer to the class time or available in your dashboard.
            </p>
            @endif
            
            <p class="info-text">
                Please make sure to log in a few minutes before the class begins. If you need to reschedule or cancel, you can do so from your dashboard.
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
