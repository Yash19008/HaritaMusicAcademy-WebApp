<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "   🔧 Fix Existing Conversions\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "\n";

// Find all payments with status='converted' that have demo bookings not converted
$payments = \App\Models\Payment::where('status', 'converted')->with('latestDemo')->get();

$fixed = 0;
$alreadyFixed = 0;
$noDemo = 0;

foreach ($payments as $payment) {
    if (!$payment->latestDemo) {
        echo "Payment #{$payment->id} ({$payment->student_name}): No demo booking\n";
        $noDemo++;
        continue;
    }
    
    if ($payment->latestDemo->status === 'converted') {
        echo "Payment #{$payment->id} ({$payment->student_name}): Already fixed ✅\n";
        $alreadyFixed++;
        continue;
    }
    
    // Need to fix
    echo "Payment #{$payment->id} ({$payment->student_name}):\n";
    echo "  Demo #{$payment->latestDemo->id} status: {$payment->latestDemo->status} → converting...\n";
    
    // Find student by email
    $contactParts = explode('|', $payment->contact ?? '');
    $email = trim($contactParts[0] ?? '');
    $student = \App\Models\Student::where('email', $email)->first();
    
    if ($student) {
        $payment->latestDemo->update([
            'status' => 'converted',
            'converted_student_id' => $student->id,
        ]);
        echo "  ✅ Fixed! Demo status → converted, linked to student #{$student->id}\n";
        $fixed++;
    } else {
        echo "  ⚠️  Student not found for email: {$email}\n";
    }
    
    echo "\n";
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "Summary:\n";
echo "  Fixed: $fixed\n";
echo "  Already OK: $alreadyFixed\n";
echo "  No Demo: $noDemo\n";
echo "  Total: " . ($fixed + $alreadyFixed + $noDemo) . "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "\n";

if ($fixed > 0) {
    echo "✅ Fixed $fixed conversion(s)! Refresh your sales page to see the changes.\n\n";
}
