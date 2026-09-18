@extends('emails.layouts.base')

@section('title', 'Demo Class Confirmation')

@section('header_icon')
🎵
@endsection

@section('header_title', 'Demo Class Confirmed!')

@section('header_subtitle')
Your {{ $booking->instrument }} Demo Session is Scheduled
@endsection

@section('content')
<div style="font-size: 18px; color: #2d3748; margin-bottom: 20px; font-weight: 500;">
    Dear <strong>{{ $isTeacher ? ($booking->teacher->user->name ?? 'Teacher') : ($booking->student_name ?? 'Student') }}</strong>,
</div>

<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 15px 0;">
    Your upcoming demo class has been successfully scheduled. Here are the details for your session:
</p>

<table role="presentation" style="width: 100%; border: 0; border-spacing: 0; background-color: #f8f9fa; border-left: 4px solid #51040e; margin: 25px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
    <tr>
        <td style="padding: 20px;">
            <h3 style="margin: 0 0 15px; color: #51040e; font-size: 16px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">📅 Session Details</h3>
            
            <table role="presentation" style="width: 100%; border: 0; border-spacing: 0;">
                <tr>
                    <td style="width: 130px; padding: 6px 0; font-weight: 600; color: #4a5568;">Instrument:</td>
                    <td style="padding: 6px 0; color: #2d3748;"><div style="background-color: #fff; padding: 8px 15px; border-radius: 6px; border: 1px solid #e2e8f0; font-weight: 500;">{{ $booking->instrument }}</div></td>
                </tr>
                <tr>
                    <td style="width: 130px; padding: 6px 0; font-weight: 600; color: #4a5568;">Date & Time:</td>
                    <td style="padding: 6px 0; color: #2d3748;"><div style="background-color: #fff; padding: 8px 15px; border-radius: 6px; border: 1px solid #e2e8f0; font-weight: 500;">{{ \Carbon\Carbon::parse($booking->scheduled_at)->format('l, F j, Y \a\t h:i A') }} ({{ $booking->duration_minutes }} Mins)</div></td>
                </tr>
                <tr>
                    <td style="width: 130px; padding: 6px 0; font-weight: 600; color: #4a5568;">{{ $isTeacher ? 'Student:' : 'Instructor:' }}</td>
                    <td style="padding: 6px 0; color: #2d3748;"><div style="background-color: #fff; padding: 8px 15px; border-radius: 6px; border: 1px solid #e2e8f0; font-weight: 500;">{{ $isTeacher ? ($booking->student_name ?? 'N/A') : ($booking->teacher->user->name ?? 'N/A') }}</div></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

@if($booking->google_meet_link)
<div style="text-align: center; margin: 20px 0;">
    @php
        $joinUrl = $isTeacher ? $booking->teacher_join_url : $booking->student_join_url;
    @endphp
    <a href="{{ $joinUrl }}" style="display: inline-block; background-color: #51040e; background: linear-gradient(135deg, #51040e 0%, #7d0a1b 100%); color: #ffffff; padding: 15px 40px; text-decoration: none; border-radius: 8px; font-weight: 600; text-align: center;">Join Class</a>
</div>
@else
<p style="text-align: center; background: #eef2ff; padding: 15px; border-radius: 8px; color: #4a5568; font-size: 15px; line-height: 1.6; margin: 15px 0;">
    The Google Meet link will be provided closer to the class time or available in your dashboard.
</p>
@endif

<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 15px 0;">
    Please make sure to log in a few minutes before the class begins. Ensure your camera and microphone are working properly.
</p>

<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 15px 0;">
    Best regards,<br>
    <strong>Harita Music Academy Team</strong>
</p>
@endsection
