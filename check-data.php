<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "   📊 Database Check - Students & Payments\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "\n";

// Check last 5 payments
echo "📋 Last 5 Payments:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
$payments = \App\Models\Payment::orderBy('id', 'desc')->limit(5)->get();
if ($payments->count() > 0) {
    foreach ($payments as $payment) {
        echo sprintf(
            "ID: %d | Name: %s | Status: %s | Amount: ₹%.2f | Date: %s\n",
            $payment->id,
            $payment->student_name,
            $payment->status,
            $payment->amount ?? 0,
            $payment->transaction_date ?? 'N/A'
        );
    }
} else {
    echo "No payments found\n";
}
echo "\n";

// Check last 5 students
echo "👥 Last 5 Students:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
$students = \App\Models\Student::orderBy('id', 'desc')->limit(5)->get();
if ($students->count() > 0) {
    foreach ($students as $student) {
        echo sprintf(
            "ID: %d | Name: %s | Email: %s | Credits: %d | Status: %s\n",
            $student->id,
            $student->name,
            $student->email,
            $student->credits,
            $student->status
        );
    }
} else {
    echo "❌ No students found in database!\n";
}
echo "\n";

// Check converted payments
echo "✅ Converted Payments:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
$converted = \App\Models\Payment::where('status', 'converted')->get();
if ($converted->count() > 0) {
    foreach ($converted as $payment) {
        $contactParts = explode('|', $payment->contact ?? '');
        $email = $contactParts[0] ?? '';
        
        // Try to find matching student
        $student = \App\Models\Student::where('email', $email)->first();
        
        echo sprintf(
            "Payment ID: %d | Name: %s | Email: %s | Student Found: %s\n",
            $payment->id,
            $payment->student_name,
            $email,
            $student ? "✅ Yes (ID: {$student->id})" : "❌ No"
        );
    }
} else {
    echo "No converted payments found\n";
}
echo "\n";

// Check users with student role
echo "🔐 Users with Student Role:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
$users = \App\Models\User::whereHas('roles', function($q) {
    $q->where('name', 'student');
})->orderBy('id', 'desc')->limit(5)->get();

if ($users->count() > 0) {
    foreach ($users as $user) {
        $student = \App\Models\Student::where('user_id', $user->id)->first();
        echo sprintf(
            "User ID: %d | Name: %s | Email: %s | Student Record: %s\n",
            $user->id,
            $user->name,
            $user->email,
            $student ? "✅ Yes (ID: {$student->id})" : "❌ No"
        );
    }
} else {
    echo "No users with student role found\n";
}
echo "\n";

// Summary
echo "═══════════════════════════════════════════════════════════════\n";
echo "   📊 Summary\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo sprintf("Total Payments: %d\n", \App\Models\Payment::count());
echo sprintf("Total Students: %d\n", \App\Models\Student::count());
echo sprintf("Converted Payments: %d\n", \App\Models\Payment::where('status', 'converted')->count());
echo sprintf("Active Students: %d\n", \App\Models\Student::where('status', 'active')->count());
echo sprintf("Users with Student Role: %d\n", \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'student'); })->count());
echo "\n";

// Check for mismatches
$convertedCount = \App\Models\Payment::where('status', 'converted')->count();
$studentCount = \App\Models\Student::count();

if ($convertedCount > $studentCount) {
    echo "⚠️  WARNING: More converted payments ($convertedCount) than students ($studentCount)\n";
    echo "   Some conversions may have failed to create student records.\n";
} elseif ($studentCount > $convertedCount) {
    echo "ℹ️  INFO: More students ($studentCount) than converted payments ($convertedCount)\n";
    echo "   This is normal if students were added manually.\n";
} else {
    echo "✅ Converted payments and students match perfectly!\n";
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "\n";
