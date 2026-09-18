@extends('emails.layouts.base')

@section('title', 'Welcome to Harita Music Academy')

@section('header_icon')
🎵
@endsection

@section('header_title', 'Welcome to Harita Music Academy')

@section('header_subtitle', 'Your Musical Journey Begins Here')

@section('content')
<div style="font-size: 18px; color: #2d3748; margin-bottom: 20px; font-weight: 500;">
    Dear <strong>{{ $studentName }}</strong>,
</div>

<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 15px 0;">
    Congratulations! Your enrollment at <strong>Harita Music Academy</strong> has been successfully completed. 
    We're thrilled to have you join our community of passionate musicians.
</p>

<!-- Details Box -->
<table role="presentation" style="width: 100%; border: 0; border-spacing: 0; background-color: #f8f9fa; border-left: 4px solid #51040e; margin: 25px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
    <tr>
        <td style="padding: 20px;">
            <h3 style="margin: 0 0 15px; color: #51040e; font-size: 16px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">🔐 Your Login Credentials</h3>
            
            <table role="presentation" style="width: 100%; border: 0; border-spacing: 0;">
                <tr>
                    <td style="width: 130px; padding: 6px 0; font-weight: 600; color: #4a5568;">Email:</td>
                    <td style="padding: 6px 0; color: #2d3748;"><div style="background-color: #fff; padding: 8px 15px; border-radius: 6px; border: 1px solid #e2e8f0; font-family: 'Courier New', Courier, monospace; font-weight: 500;">{{ $email }}</div></td>
                </tr>
                <tr>
                    <td style="width: 130px; padding: 6px 0; font-weight: 600; color: #4a5568;">Password:</td>
                    <td style="padding: 6px 0; color: #2d3748;"><div style="background-color: #fff; padding: 8px 15px; border-radius: 6px; border: 1px solid #e2e8f0; font-family: 'Courier New', Courier, monospace; font-weight: 500;">{{ $password }}</div></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table role="presentation" style="width: 100%; border: 0; border-spacing: 0; background-color: #fcf8f8; border-left: 4px solid #51040e; margin: 20px 0; border-radius: 6px;">
    <tr>
        <td style="padding: 15px;">
            <p style="margin: 0; color: #51040e; font-size: 14px; line-height: 1.6;">
                <strong>⚠️ Important:</strong> Please change your password after your first login for security purposes. 
                Keep your credentials confidential and do not share them with anyone.
            </p>
        </td>
    </tr>
</table>

<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 15px 0;">
    You can now access your student dashboard to:
</p>
<ul style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 15px 0 15px 20px; padding: 0;">
    <li style="margin-bottom: 5px;">View your class schedule</li>
    <li style="margin-bottom: 5px;">Check your credit balance</li>
    <li style="margin-bottom: 5px;">Book classes with your teacher</li>
    <li style="margin-bottom: 5px;">Access learning resources</li>
    <li style="margin-bottom: 5px;">Track your progress</li>
</ul>

<div style="text-align: center; margin: 20px 0;">
    <a href="{{ $loginUrl }}" style="display: inline-block; background-color: #51040e; background: linear-gradient(135deg, #51040e 0%, #7d0a1b 100%); color: #ffffff; padding: 15px 40px; text-decoration: none; border-radius: 8px; font-weight: 600; text-align: center;">Login to Your Dashboard</a>
</div>

<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 15px 0;">
    If you have any questions or need assistance, please don't hesitate to contact our support team.
</p>

<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 15px 0;">
    We look forward to supporting you on your musical journey!
</p>

<p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 15px 0;">
    Best regards,<br>
    <strong>Harita Music Academy Team</strong>
</p>
@endsection
