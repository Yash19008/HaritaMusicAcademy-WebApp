<?php

/**
 * Assign Roles to Existing Users
 * 
 * This script automatically assigns appropriate roles to all users
 * based on whether they have teacher or student records.
 * 
 * Run: php assign-roles-to-users.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "   🔐 Assign Roles to Existing Users\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "\n";

$users = \App\Models\User::all();

if ($users->count() === 0) {
    echo "No users found in database.\n\n";
    exit;
}

$assigned = 0;
$skipped = 0;
$errors = 0;

foreach ($users as $user) {
    echo "User #{$user->id}: {$user->name} ({$user->email})\n";
    
    // Check if user already has a role
    if ($user->roles()->count() > 0) {
        $currentRole = $user->roles->first()->name;
        echo "  → Already has role: {$currentRole}\n";
        $skipped++;
        continue;
    }
    
    try {
        // Determine role based on related records
        if ($user->teacher()->exists()) {
            $user->assignRole('Teacher');
            echo "  ✅ Assigned role: Teacher\n";
            $assigned++;
        } elseif ($user->student()->exists()) {
            $user->assignRole('Student');
            echo "  ✅ Assigned role: Student\n";
            $assigned++;
        } elseif ($user->id === 1) {
            // First user is Super Admin
            $user->assignRole('Super Admin');
            echo "  ✅ Assigned role: Super Admin (Primary Administrator)\n";
            $assigned++;
        } else {
            // Others default to Admin
            $user->assignRole('Admin');
            echo "  ✅ Assigned role: Admin\n";
            $assigned++;
        }
    } catch (\Exception $e) {
        echo "  ❌ Error: " . $e->getMessage() . "\n";
        $errors++;
    }
    
    echo "\n";
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "Summary:\n";
echo "  Assigned: $assigned\n";
echo "  Skipped (already had role): $skipped\n";
echo "  Errors: $errors\n";
echo "  Total: " . ($assigned + $skipped + $errors) . "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "\n";

if ($assigned > 0) {
    echo "✅ Successfully assigned roles to $assigned user(s)!\n\n";
}

if ($errors > 0) {
    echo "⚠️  $errors error(s) occurred. Please check the output above.\n\n";
}

// Display role assignments
echo "📊 Current Role Distribution:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$roles = \Spatie\Permission\Models\Role::withCount('users')->get();
foreach ($roles as $role) {
    echo sprintf("  %-15s : %d user(s)\n", $role->name, $role->users_count);
}

echo "\n";
echo "✅ All done! You can now go to /admin/roles to manage users.\n\n";
