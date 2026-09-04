<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
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
            color: #ffffff;
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
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .details-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .detail-item {
            margin-bottom: 12px;
            font-size: 15px;
            color: #4a5568;
        }

        .detail-item:last-child {
            margin-bottom: 0;
        }

        .detail-label {
            font-weight: 600;
            color: #2d3748;
            width: 140px;
            display: inline-block;
        }

        .footer {
            background-color: #f8fafc;
            padding: 20px 30px;
            text-align: center;
            font-size: 14px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
        }

        .social-links {
            margin: 15px 0;
        }

        .social-links a {
            margin: 0 10px;
            color: #51040e;
            text-decoration: none;
            font-weight: 600;
        }

        p {
            line-height: 1.6;
            color: #4a5568;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>Payment Confirmed!</h1>
            <p>Your demo class has been booked</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Dear {{ $payment->student_name }},
            </div>

            <p>Thank you for choosing Harita Music Academy! We have successfully received your payment of
                <strong>₹{{ number_format($payment->amount) }}</strong>.</p>
            <p>This amount is fully adjustable against your course fees if you choose to enroll after the demo.</p>

            <div class="details-box">
                <div class="detail-item">
                    <span class="detail-label">Payment ID:</span>
                    <span>{{ $payment->razorpay_payment_id ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Instrument:</span>
                    <span>{{ $payment->instrument }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Amount Paid:</span>
                    <span>₹{{ number_format($payment->amount) }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Preferred Date:</span>
                    <span>{{ \Carbon\Carbon::parse($payment->preferred_date)->format('l, F j, Y') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Preferred Time:</span>
                    <span>{{ \Carbon\Carbon::parse($payment->preferred_time)->format('h:i A') }}</span>
                </div>
            </div>

            <p>Our team will contact you shortly to confirm the exact schedule and provide the meeting link for your
                demo session.</p>

            <p>If you have any questions, feel free to reply to this email or contact us directly.</p>

            <p style="margin-top: 30px;">
                Warm Regards,<br>
                <strong>Harita Music Academy Team</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} Harita Music Academy. All rights reserved.</p>
            <div class="social-links">
                <a href="#">Website</a> |
                <a href="#">Contact Us</a>
            </div>
            <p style="font-size: 12px; margin-top: 10px;">
                This is an automated email. Please do not reply directly to this message.
            </p>
        </div>
    </div>
</body>

</html>
