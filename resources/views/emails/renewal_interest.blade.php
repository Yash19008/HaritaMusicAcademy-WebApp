@extends('emails.layouts.base')

@section('title', 'Student Renewal Interest')

@section('header_icon')
🔥
@endsection

@section('header_title', 'Hot Lead: Course Renewal')

@section('header_subtitle', 'A student has expressed interest in renewing')

@section('content')
<div style="font-size: 18px; color: #2d3748; margin-bottom: 20px; font-weight: 500;">
    Hello Admin,
</div>

<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 15px 0;">
    A student with low credits has expressed interest in purchasing another course or renewing their current package!
</p>

<!-- Details Box -->
<table role="presentation" style="width: 100%; border: 0; border-spacing: 0; background-color: #f8f9fa; border-left: 4px solid #2563eb; margin: 25px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
    <tr>
        <td style="padding: 20px;">
            <h3 style="margin: 0 0 15px; color: #2563eb; font-size: 16px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Student Details</h3>
            
            <table role="presentation" style="width: 100%; border: 0; border-spacing: 0;">
                <tr>
                    <td style="width: 130px; padding: 6px 0; font-weight: 600; color: #4a5568;">Student Name:</td>
                    <td style="padding: 6px 0; color: #2d3748;"><div style="background-color: #fff; padding: 8px 15px; border-radius: 6px; border: 1px solid #e2e8f0; font-weight: 500;">{{ $student->user->name ?? $student->name }}</div></td>
                </tr>
                <tr>
                    <td style="width: 130px; padding: 6px 0; font-weight: 600; color: #4a5568;">Email:</td>
                    <td style="padding: 6px 0; color: #2d3748;"><div style="background-color: #fff; padding: 8px 15px; border-radius: 6px; border: 1px solid #e2e8f0; font-weight: 500;"><a href="mailto:{{ $student->user->email ?? $student->email }}" style="color: #2563eb; text-decoration: none;">{{ $student->user->email ?? $student->email }}</a></div></td>
                </tr>
                <tr>
                    <td style="width: 130px; padding: 6px 0; font-weight: 600; color: #4a5568;">Phone:</td>
                    <td style="padding: 6px 0; color: #2d3748;"><div style="background-color: #fff; padding: 8px 15px; border-radius: 6px; border: 1px solid #e2e8f0; font-weight: 500;">{{ $student->phone ?? 'N/A' }}</div></td>
                </tr>
                <tr>
                    <td style="width: 130px; padding: 6px 0; font-weight: 600; color: #4a5568;">Current Course:</td>
                    <td style="padding: 6px 0; color: #2d3748;"><div style="background-color: #fff; padding: 8px 15px; border-radius: 6px; border: 1px solid #e2e8f0; font-weight: 500;">{{ $student->course->name ?? 'N/A' }}</div></td>
                </tr>
                <tr>
                    <td style="width: 130px; padding: 6px 0; font-weight: 600; color: #4a5568;">Remaining Credits:</td>
                    <td style="padding: 6px 0; color: #dc2626;"><div style="background-color: #fff; padding: 8px 15px; border-radius: 6px; border: 1px solid #e2e8f0; font-weight: bold;">{{ $student->credits }}</div></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 15px 0;">
    Please follow up with this student as soon as possible to secure the renewal.
</p>

<div style="text-align: center; margin: 20px 0;">
    <a href="{{ url('/admin/students/' . $student->id) }}" style="display: inline-block; background-color: #2563eb; background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%); color: #ffffff; padding: 15px 40px; text-decoration: none; border-radius: 8px; font-weight: 600; text-align: center;">View Student Profile</a>
</div>

<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 15px 0;">
    Best regards,<br>
    <strong>Harita Music Academy System</strong>
</p>
@endsection
