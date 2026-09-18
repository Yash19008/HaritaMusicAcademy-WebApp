@extends('emails.layouts.base')

@section('title', 'Class Reminder – Harita Music Academy')

@section('header_icon')
⏰
@endsection

@section('header_title', 'Class Reminder')

@section('header_subtitle')
Your {{ $isDemo ? 'Demo' : '' }} {{ $instrument }} class is coming up soon
<br>
<span style="display: inline-block; background-color: rgba(255,255,255,0.18); border: 1.5px solid rgba(255,255,255,0.4); border-radius: 30px; padding: 8px 22px; margin-top: 18px; font-size: 14px; font-weight: 600; color: #fff; letter-spacing: 0.5px;">Starting {{ $timeLabel }}</span>
@endsection

@section('content')
<div style="font-size: 18px; color: #2d3748; margin-bottom: 14px; font-weight: 500;">
    Hello <strong>{{ $recipientName }}</strong>,
</div>

<p style="color: #4a5568; font-size: 15px; line-height: 1.65; margin: 12px 0;">
    This is a friendly reminder that your upcoming
    <strong>{{ $isDemo ? 'Demo' : '' }} {{ $instrument }} class</strong>
    is scheduled to begin {{ strtolower($timeLabel) }}.
    Please make sure you are prepared and have a good connection.
</p>

<!-- Session Details -->
<table role="presentation" style="width: 100%; border: 0; border-spacing: 0; background-color: #f8f9fa; border-left: 4px solid #51040e; margin: 24px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.03);">
    <tr>
        <td style="padding: 20px 24px;">
            <h3 style="margin: 0 0 16px; color: #51040e; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700;">📅 Session Details</h3>
            
            <table role="presentation" style="width: 100%; border: 0; border-spacing: 0;">
                <tr>
                    <td style="width: 130px; padding: 6px 0; font-weight: 600; color: #718096; font-size: 14px;">📚 Subject</td>
                    <td style="padding: 6px 0; color: #2d3748;"><div style="background-color: #fff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px 14px; font-size: 14px; font-weight: 500;">{{ $instrument }}{{ $isDemo ? ' (Demo Class)' : '' }}</div></td>
                </tr>
                <tr>
                    <td style="width: 130px; padding: 6px 0; font-weight: 600; color: #718096; font-size: 14px;">🕐 Date & Time</td>
                    <td style="padding: 6px 0; color: #2d3748;"><div style="background-color: #fff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px 14px; font-size: 14px; font-weight: 500;">{{ $startsAt }} ({{ $timezone }})</div></td>
                </tr>
                <tr>
                    <td style="width: 130px; padding: 6px 0; font-weight: 600; color: #718096; font-size: 14px;">⏱ Duration</td>
                    <td style="padding: 6px 0; color: #2d3748;"><div style="background-color: #fff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px 14px; font-size: 14px; font-weight: 500;">{{ $duration }} minutes</div></td>
                </tr>
                
                @if($teacherName && !$isTeacher)
                <tr>
                    <td style="width: 130px; padding: 6px 0; font-weight: 600; color: #718096; font-size: 14px;">👨‍🏫 Instructor</td>
                    <td style="padding: 6px 0; color: #2d3748;"><div style="background-color: #fff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px 14px; font-size: 14px; font-weight: 500;">{{ $teacherName }}</div></td>
                </tr>
                @endif
                
                @if($studentName && $isTeacher)
                <tr>
                    <td style="width: 130px; padding: 6px 0; font-weight: 600; color: #718096; font-size: 14px;">🎓 Student</td>
                    <td style="padding: 6px 0; color: #2d3748;"><div style="background-color: #fff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px 14px; font-size: 14px; font-weight: 500;">{{ $studentName }}</div></td>
                </tr>
                @endif
            </table>
        </td>
    </tr>
</table>

@if($joinUrl)
<!-- Join Button -->
<div style="text-align: center; margin: 28px 0 10px;">
    <a href="{{ $joinUrl }}" style="display: inline-block; background-color: #51040e; background: linear-gradient(135deg, #51040e 0%, #7d0a1b 100%); color: #ffffff; padding: 15px 42px; text-decoration: none; border-radius: 8px; font-weight: 700; font-size: 16px; letter-spacing: 0.3px;">🎬 Join Google Meet Now</a>
</div>
<p style="color: #718096; font-size: 13px; line-height: 1.65; margin: 8px 0 12px; text-align: center;">
    Click the button above or paste this link in your browser:<br>
    <a href="{{ $joinUrl }}" style="color: #51040e; font-size: 12px; word-break: break-all;">{{ $joinUrl }}</a>
</p>
@else
<p style="text-align: center; background-color: #eef2ff; padding: 14px; border-radius: 8px; color: #4a5568; font-size: 15px; line-height: 1.65; margin: 12px 0;">
    📎 Your Google Meet link will be available in your dashboard.
</p>
@endif

<div style="height: 1px; background-color: #e2e8f0; margin: 24px 0;"></div>

<!-- Tips -->
<table role="presentation" style="width: 100%; border: 0; border-spacing: 0; background-color: #fffbf0; border: 1px dashed #e6a817; margin: 20px 0 0; border-radius: 8px;">
    <tr>
        <td style="padding: 16px 20px;">
            <p style="margin: 0; color: #744210; font-size: 13.5px; line-height: 1.6;">
                <strong style="color: #92400e;">💡 Quick Tips:</strong><br>
                ✔ Join 2–3 minutes early to test your audio & video.<br>
                ✔ Find a quiet, well-lit space for your session.<br>
                ✔ Have your instrument or notes ready before the class starts.
            </p>
        </td>
    </tr>
</table>

<p style="color: #4a5568; font-size: 15px; line-height: 1.65; margin-top: 24px; margin-bottom: 0;">
    Best regards,<br>
    <strong>Harita Music Academy Team</strong>
</p>
@endsection
