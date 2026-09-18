@extends('emails.layouts.base')

@section('title', 'Payment Confirmation')

@section('header_title', 'Payment Confirmed!')

@section('header_subtitle', 'Your demo class has been booked')

@section('content')
<div style="font-size: 18px; color: #2d3748; margin-bottom: 20px; font-weight: 500;">
    Dear {{ $payment->student_name }},
</div>

<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 15px 0;">
    Thank you for choosing Harita Music Academy! We have successfully received your payment of
    <strong>₹{{ number_format($payment->amount) }}</strong>.
</p>
<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 15px 0;">
    This amount is fully adjustable against your course fees if you choose to enroll after the demo.
</p>

<table role="presentation" style="width: 100%; border: 0; border-spacing: 0; background-color: #f8fafc; border: 1px solid #e2e8f0; margin: 20px 0; border-radius: 8px;">
    <tr>
        <td style="padding: 20px;">
            <table role="presentation" style="width: 100%; border: 0; border-spacing: 0;">
                <tr>
                    <td style="width: 140px; padding: 6px 0; font-weight: 600; color: #2d3748; font-size: 15px;">Payment ID:</td>
                    <td style="padding: 6px 0; color: #4a5568; font-size: 15px;">{{ $payment->razorpay_payment_id ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="width: 140px; padding: 6px 0; font-weight: 600; color: #2d3748; font-size: 15px;">Instrument:</td>
                    <td style="padding: 6px 0; color: #4a5568; font-size: 15px;">{{ $payment->instrument }}</td>
                </tr>
                <tr>
                    <td style="width: 140px; padding: 6px 0; font-weight: 600; color: #2d3748; font-size: 15px;">Amount Paid:</td>
                    <td style="padding: 6px 0; color: #4a5568; font-size: 15px;">₹{{ number_format($payment->amount) }}</td>
                </tr>
                <tr>
                    <td style="width: 140px; padding: 6px 0; font-weight: 600; color: #2d3748; font-size: 15px;">Preferred Date:</td>
                    <td style="padding: 6px 0; color: #4a5568; font-size: 15px;">{{ \Carbon\Carbon::parse($payment->preferred_date)->format('l, F j, Y') }}</td>
                </tr>
                <tr>
                    <td style="width: 140px; padding: 6px 0; font-weight: 600; color: #2d3748; font-size: 15px;">Preferred Time:</td>
                    <td style="padding: 6px 0; color: #4a5568; font-size: 15px;">{{ \Carbon\Carbon::parse($payment->preferred_time)->format('h:i A') }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 15px 0;">
    Our team will contact you shortly to confirm the exact schedule and provide the meeting link for your demo session.
</p>

<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 15px 0;">
    If you have any questions, feel free to reply to this email or contact us directly.
</p>

<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin-top: 30px; margin-bottom: 0;">
    Warm Regards,<br>
    <strong>Harita Music Academy Team</strong>
</p>
@endsection
