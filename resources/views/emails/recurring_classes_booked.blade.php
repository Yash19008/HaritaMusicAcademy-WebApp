@extends('emails.layouts.base')

@section('title', 'Recurring Class Booking Summary')

@section('header_icon')
🎵
@endsection

@section('header_title', 'Recurring Booking Confirmed!')

@section('header_subtitle')
Your {{ $firstBooking->instrument ?? 'Music' }} Sessions are Scheduled
@endsection

@section('content')
<div style="font-size: 18px; color: #2d3748; margin-bottom: 20px; font-weight: 500;">
    Dear <strong>{{ $isTeacher ? $firstBooking->teacher->user->name ?? 'Teacher' : $targetStudent->name ?? $firstBooking->student->name ?? 'Student' }}</strong>,
</div>

<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 15px 0;">
    Your recurring music classes have been successfully scheduled. A total of <strong>{{ $totalClasses }} classes</strong> have been booked.
</p>

<!-- Details Box -->
<table role="presentation" style="width: 100%; border: 0; border-spacing: 0; background-color: #f8f9fa; border-left: 4px solid #51040e; margin: 25px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
    <tr>
        <td style="padding: 20px;">
            <h3 style="margin: 0 0 15px; color: #51040e; font-size: 16px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">📅 Summary</h3>
            
            <table role="presentation" style="width: 100%; border: 0; border-spacing: 0;">
                <tr>
                    <td style="width: 130px; padding: 6px 0; font-weight: 600; color: #4a5568;">Instrument:</td>
                    <td style="padding: 6px 0; color: #2d3748;"><div style="background-color: #fff; padding: 8px 15px; border-radius: 6px; border: 1px solid #e2e8f0; font-weight: 500;">{{ $firstBooking->instrument ?? 'N/A' }}</div></td>
                </tr>
                <tr>
                    <td style="width: 130px; padding: 6px 0; font-weight: 600; color: #4a5568;">{{ $isTeacher ? 'Student:' : 'Instructor:' }}</td>
                    <td style="padding: 6px 0; color: #2d3748;"><div style="background-color: #fff; padding: 8px 15px; border-radius: 6px; border: 1px solid #e2e8f0; font-weight: 500;">{{ $isTeacher ? ($firstBooking->student_group_id ? $firstBooking->studentGroup->name : ($firstBooking->student->name ?? 'N/A')) : ($firstBooking->teacher->user->name ?? 'N/A') }}</div></td>
                </tr>
                <tr>
                    <td style="width: 130px; padding: 6px 0; font-weight: 600; color: #4a5568;">Total Classes:</td>
                    <td style="padding: 6px 0; color: #2d3748;"><div style="background-color: #fff; padding: 8px 15px; border-radius: 6px; border: 1px solid #e2e8f0; font-weight: 500;">{{ $totalClasses }}</div></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 25px 0 15px;"><strong>Your Scheduled Dates:</strong></p>

<table role="presentation" style="width: 100%; border-collapse: collapse; margin-top: 5px; background-color: #fff; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
    <thead>
        <tr>
            <th style="background-color: #f8f9fa; color: #51040e; padding: 12px 15px; text-align: left; font-weight: 600; border-bottom: 2px solid #e2e8f0; font-size: 14px;">Date & Time</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bookings as $booking)
            <tr>
                <td style="padding: 12px 15px; border-bottom: 1px solid #e2e8f0; color: #4a5568; font-size: 14px;">{{ $booking->starts_at->format('l, M d, Y - h:i A') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<p style="text-align: center; background: #eef2ff; padding: 15px; border-radius: 8px; color: #4a5568; font-size: 15px; line-height: 1.6; margin: 25px 0 15px;">
    The Google Meet link will be provided closer to the class time or available in your dashboard.
</p>

@if ($firstBooking->notes)
<table role="presentation" style="width: 100%; border: 0; border-spacing: 0; background-color: #fffaf0; border: 1px dashed #ed8936; margin: 20px 0; border-radius: 6px;">
    <tr>
        <td style="padding: 15px; font-style: italic; color: #c05621;">
            <strong>Additional Notes:</strong><br>
            {{ $firstBooking->notes }}
        </td>
    </tr>
</table>
@endif

<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 25px 0 15px;">
    You can manage, cancel, or reschedule any of these individual sessions directly from your dashboard.
</p>

<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 15px 0 0;">
    Best regards,<br>
    <strong>Harita Music Academy Team</strong>
</p>
@endsection
