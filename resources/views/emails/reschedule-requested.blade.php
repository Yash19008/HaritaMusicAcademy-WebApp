@extends('emails.layouts.base')

@section('title', 'Class Reschedule Requested')

@section('header_icon')
🗓️
@endsection

@section('header_title', 'Class Reschedule Requested')

@section('content')
<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 15px 0;">
    A request has been made to reschedule an upcoming class. Please review the proposed details below.
</p>

<!-- Details Box -->
<table role="presentation" style="width: 100%; border: 0; border-spacing: 0; background-color: #f8f9fa; border-left: 4px solid #51040e; margin: 25px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
    <tr>
        <td style="padding: 20px;">
            <h3 style="margin: 0 0 15px; color: #51040e; font-size: 16px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Proposed Schedule</h3>
            
            <table role="presentation" style="width: 100%; border: 0; border-spacing: 0;">
                <tr>
                    <td style="width: 130px; padding: 6px 0; font-weight: 600; color: #4a5568;">Instrument:</td>
                    <td style="padding: 6px 0; color: #2d3748;"><div style="background-color: #fff; padding: 8px 15px; border-radius: 6px; border: 1px solid #e2e8f0; font-weight: 500;">{{ $booking->instrument }}</div></td>
                </tr>
                <tr>
                    <td style="width: 130px; padding: 6px 0; font-weight: 600; color: #4a5568;">Student:</td>
                    <td style="padding: 6px 0; color: #2d3748;"><div style="background-color: #fff; padding: 8px 15px; border-radius: 6px; border: 1px solid #e2e8f0; font-weight: 500;">{{ $booking->student->user->name ?? 'N/A' }}</div></td>
                </tr>
                <tr>
                    <td style="width: 130px; padding: 6px 0; font-weight: 600; color: #4a5568;">Teacher:</td>
                    <td style="padding: 6px 0; color: #2d3748;"><div style="background-color: #fff; padding: 8px 15px; border-radius: 6px; border: 1px solid #e2e8f0; font-weight: 500;">{{ $booking->teacher->user->name ?? 'N/A' }}</div></td>
                </tr>
                <tr>
                    <td style="width: 130px; padding: 6px 0; font-weight: 600; color: #4a5568;">Proposed Date:</td>
                    <td style="padding: 6px 0; color: #2d3748;"><div style="background-color: #fff; padding: 8px 15px; border-radius: 6px; border: 1px solid #e2e8f0; font-weight: 500;">{{ \Carbon\Carbon::parse($booking->reschedule_requested_starts_at)->format('l, F j, Y') }}</div></td>
                </tr>
                <tr>
                    <td style="width: 130px; padding: 6px 0; font-weight: 600; color: #4a5568;">Proposed Time:</td>
                    <td style="padding: 6px 0; color: #2d3748;"><div style="background-color: #fff; padding: 8px 15px; border-radius: 6px; border: 1px solid #e2e8f0; font-weight: 500;">{{ \Carbon\Carbon::parse($booking->reschedule_requested_starts_at)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->reschedule_requested_ends_at)->format('h:i A') }}</div></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 15px 0;">
    <strong>Reason provided:</strong> <br>
    {{ $booking->reschedule_reason ?? 'No specific reason provided.' }}
</p>

<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 15px 0;">
    The academy administration will review this request and you will be notified once it is approved or rejected.
</p>

<div style="text-align: center; margin: 20px 0;">
    <a href="{{ url('/') }}" style="display: inline-block; background-color: #51040e; background: linear-gradient(135deg, #51040e 0%, #7d0a1b 100%); color: #ffffff; padding: 15px 40px; text-decoration: none; border-radius: 8px; font-weight: 600; text-align: center;">Go to Dashboard</a>
</div>

<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 15px 0;">
    Thank you,<br>
    <strong>Harita Music Academy Team</strong>
</p>
@endsection
