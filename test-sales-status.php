<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "   📊 Sales Page Status Check\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "\n";

$leads = \App\Models\Payment::with('latestDemo')->latest()->get();

echo "Total Leads: " . $leads->count() . "\n\n";

foreach ($leads as $lead) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Lead ID: {$lead->id}\n";
    echo "Name: {$lead->student_name}\n";
    echo "Payment Status: {$lead->status}\n";
    
    if ($lead->latestDemo) {
        echo "Has Demo: ✅ Yes\n";
        echo "Demo Status: {$lead->latestDemo->status}\n";
        
        // Determine displayed status (same logic as blade)
        $demoStatus = $lead->latestDemo->status;
        
        if($demoStatus === 'scheduled') {
            $statusDisplay = 'Demo Scheduled';
        } elseif($demoStatus === 'completed') {
            $statusDisplay = 'Demo Completed';
        } elseif($demoStatus === 'converted') {
            $statusDisplay = 'Converted to Student';
        } elseif($demoStatus === 'cancelled') {
            $statusDisplay = 'Demo Cancelled';
        } elseif($demoStatus === 'no-show') {
            $statusDisplay = 'No Show';
        }
        
        echo "Display Status: {$statusDisplay}\n";
    } else {
        echo "Has Demo: ❌ No\n";
        
        // No demo, use payment status
        if($lead->status === 'pending') {
            $statusDisplay = 'Inquiry';
        } elseif($lead->status === 'confirmed') {
            $statusDisplay = 'Confirmed';
        } elseif($lead->status === 'converted') {
            $statusDisplay = 'Converted to Student';
        } elseif($lead->status === 'cancelled') {
            $statusDisplay = 'Demo Failed';
        } else {
            $statusDisplay = ucfirst($lead->status);
        }
        
        echo "Display Status: {$statusDisplay}\n";
    }
    
    echo "\n";
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "\n";

echo "🔍 Checking what Sales Page SHOULD show:\n\n";

$leads = \App\Models\Payment::with('latestDemo')->latest()->get();

echo "ID | Name          | Payment Status | Demo Status   | Should Display\n";
echo "───┼───────────────┼────────────────┼───────────────┼────────────────────────\n";

foreach ($leads as $lead) {
    $demoStatus = $lead->latestDemo ? $lead->latestDemo->status : 'N/A';
    
    if ($lead->latestDemo) {
        if($lead->latestDemo->status === 'converted') {
            $display = '✅ Converted to Student';
        } elseif($lead->latestDemo->status === 'completed') {
            $display = 'Demo Completed';
        } elseif($lead->latestDemo->status === 'scheduled') {
            $display = 'Demo Scheduled';
        } else {
            $display = ucfirst($lead->latestDemo->status);
        }
    } else {
        if($lead->status === 'converted') {
            $display = '✅ Converted to Student';
        } elseif($lead->status === 'pending') {
            $display = 'Inquiry';
        } else {
            $display = ucfirst($lead->status);
        }
    }
    
    printf(
        "%2d | %-13s | %-14s | %-13s | %s\n",
        $lead->id,
        substr($lead->student_name, 0, 13),
        $lead->status,
        $demoStatus,
        $display
    );
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "\n";
