<!DOCTYPE html>
<html>
<head>
    <title>Student Renewal Interest</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eaeaea; border-radius: 8px;">
        <h2 style="color: #2563eb; margin-bottom: 20px;">Hot Lead: Course Renewal</h2>
        <p>Hello Admin,</p>
        
        <p>A student with low credits has expressed interest in purchasing another course or renewing their current package!</p>
        
        <div style="background-color: #f8fafc; padding: 15px; border-left: 4px solid #2563eb; margin: 20px 0;">
            <p style="margin: 0 0 10px 0;"><strong>Student Name:</strong> {{ $student->user->name ?? $student->name }}</p>
            <p style="margin: 0 0 10px 0;"><strong>Email:</strong> {{ $student->user->email ?? $student->email }}</p>
            <p style="margin: 0 0 10px 0;"><strong>Phone:</strong> {{ $student->phone ?? 'N/A' }}</p>
            <p style="margin: 0 0 10px 0;"><strong>Current Course:</strong> {{ $student->course->name ?? 'N/A' }}</p>
            <p style="margin: 0;"><strong>Remaining Credits:</strong> <span style="color: #dc2626; font-weight: bold;">{{ $student->credits }}</span></p>
        </div>

        <p>Please follow up with this student as soon as possible to secure the renewal.</p>

        <p style="margin-top: 30px; font-size: 0.9em; color: #666;">
            Best regards,<br>
            Harita Music Academy System
        </p>
    </div>
</body>
</html>
