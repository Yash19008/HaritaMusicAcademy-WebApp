<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Reminder – Harita Music Academy</title>
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
        .header-icon {
            font-size: 48px;
            margin-bottom: 12px;
            display: block;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            color: #ffffff;
        }
        .header p {
            margin: 10px 0 0;
            font-size: 15px;
            opacity: 0.88;
            color: #f5d9dd;
        }
        /* Countdown badge */
        .countdown-badge {
            display: inline-block;
            background: rgba(255,255,255,0.18);
            border: 1.5px solid rgba(255,255,255,0.4);
            border-radius: 30px;
            padding: 8px 22px;
            margin-top: 18px;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.5px;
        }
        .content {
            padding: 36px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #2d3748;
            margin-bottom: 14px;
            font-weight: 500;
        }
        .info-text {
            color: #4a5568;
            font-size: 15px;
            line-height: 1.65;
            margin: 12px 0;
        }
        /* Detail box */
        .details-box {
            background-color: #f8f9fa;
            border-left: 4px solid #51040e;
            padding: 20px 24px;
            margin: 24px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.03);
        }
        .details-box h3 {
            margin: 0 0 16px;
            color: #51040e;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 700;
        }
        .detail-row {
            display: flex;
            align-items: flex-start;
            margin: 10px 0;
            gap: 12px;
        }
        .detail-label {
            font-weight: 600;
            color: #718096;
            min-width: 130px;
            font-size: 14px;
            padding-top: 2px;
        }
        .detail-value {
            color: #2d3748;
            font-size: 14px;
            font-weight: 500;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 6px 14px;
            flex: 1;
        }
        /* Join button */
        .btn-wrap {
            text-align: center;
            margin: 28px 0 10px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #51040e 0%, #7d0a1b 100%);
            color: #ffffff !important;
            padding: 15px 42px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 4px 14px rgba(81, 4, 14, 0.35);
            letter-spacing: 0.3px;
        }
        /* Tips box */
        .tips-box {
            background: linear-gradient(135deg, #fffbf0, #fff8e8);
            border: 1px dashed #e6a817;
            padding: 16px 20px;
            border-radius: 8px;
            margin: 20px 0 0;
        }
        .tips-box p {
            margin: 0;
            color: #744210;
            font-size: 13.5px;
            line-height: 1.6;
        }
        .tips-box strong {
            color: #92400e;
        }
        /* Footer */
        .footer {
            background-color: #fcfbf8;
            padding: 28px 30px;
            text-align: center;
            color: #718096;
            font-size: 13px;
            border-top: 1px solid #e2e8f0;
        }
        .footer p { margin: 5px 0; }
        .footer a { color: #51040e; text-decoration: none; font-weight: 500; }
        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 24px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <span class="header-icon">⏰</span>
            <h1>Class Reminder</h1>
            <p>Your {{ $isDemo ? 'Demo' : '' }} {{ $instrument }} class is coming up soon</p>
            <div class="countdown-badge">Starting {{ $timeLabel }}</div>
        </div>

        <!-- Body -->
        <div class="content">
            <div class="greeting">Hello <strong>{{ $recipientName }}</strong>,</div>

            <p class="info-text">
                This is a friendly reminder that your upcoming
                <strong>{{ $isDemo ? 'Demo' : '' }} {{ $instrument }} class</strong>
                is scheduled to begin {{ strtolower($timeLabel) }}.
                Please make sure you are prepared and have a good connection.
            </p>

            <!-- Session Details -->
            <div class="details-box">
                <h3>📅 Session Details</h3>

                <div class="detail-row">
                    <span class="detail-label">📚 Subject</span>
                    <span class="detail-value">{{ $instrument }}{{ $isDemo ? ' (Demo Class)' : '' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">🕐 Date & Time</span>
                    <span class="detail-value">{{ $startsAt }} ({{ $timezone }})</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">⏱ Duration</span>
                    <span class="detail-value">{{ $duration }} minutes</span>
                </div>

                @if($teacherName && !$isTeacher)
                <div class="detail-row">
                    <span class="detail-label">👨‍🏫 Instructor</span>
                    <span class="detail-value">{{ $teacherName }}</span>
                </div>
                @endif

                @if($studentName && $isTeacher)
                <div class="detail-row">
                    <span class="detail-label">🎓 Student</span>
                    <span class="detail-value">{{ $studentName }}</span>
                </div>
                @endif
            </div>

            @if($joinUrl)
            <!-- Join Button -->
            <div class="btn-wrap">
                <a href="{{ $joinUrl }}" class="button">🎬 Join Google Meet Now</a>
            </div>
            <p class="info-text" style="text-align: center; font-size: 13px; color: #718096; margin-top: 8px;">
                Click the button above or paste this link in your browser:<br>
                <a href="{{ $joinUrl }}" style="color: #51040e; font-size: 12px;">{{ $joinUrl }}</a>
            </p>
            @else
            <p class="info-text" style="text-align: center; background: #eef2ff; padding: 14px; border-radius: 8px;">
                📎 Your Google Meet link will be available in your dashboard.
            </p>
            @endif

            <div class="divider"></div>

            <!-- Tips -->
            <div class="tips-box">
                <p>
                    <strong>💡 Quick Tips:</strong><br>
                    ✔ Join 2–3 minutes early to test your audio & video.<br>
                    ✔ Find a quiet, well-lit space for your session.<br>
                    ✔ Have your instrument or notes ready before the class starts.
                </p>
            </div>

            <p class="info-text" style="margin-top: 24px;">
                Best regards,<br>
                <strong>Harita Music Academy Team</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Harita Music Academy</strong></p>
            <p>Nurturing Musical Excellence Since 2026</p>
            <p>
                Email: <a href="mailto:info@haritamusicacademy.com">info@haritamusicacademy.com</a> |
                Phone: +91 (123) 456-7890
            </p>
            <p style="margin-top: 15px; font-size: 12px; color: #999;">
                This is an automated reminder. Please do not reply to this message.
            </p>
            <p style="margin-top: 10px; font-size: 11px; color: #999;">
                © {{ date('Y') }} Harita Music Academy. All rights reserved. |
                Developed by <a href="https://sitesoch.com" style="color: #667eea;">Sitesoch</a>
            </p>
        </div>
    </div>
</body>
</html>
