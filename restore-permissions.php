<?php
/**
 * Restore dashboard.view permission to all roles
 * Run: php restore-permissions.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "🔧 Restoring dashboard.view permission...\n\n";

// Find or create the permission
$permission = Permission::firstOrCreate([
    'name' => 'dashboard.view',
    'guard_name' => 'web'
]);

echo "✅ Permission 'dashboard.view' found/created (ID: {$permission->id})\n\n";

// Restore to all roles
$roles = ['Super Admin', 'Admin', 'Teacher', 'Student'];

foreach ($roles as $roleName) {
    $role = Role::where('name', $roleName)->first();
    
    if ($role) {
        // Check if already has permission
        if ($role->hasPermissionTo('dashboard.view')) {
            echo "   ℹ️  {$roleName}: Already has dashboard.view\n";
        } else {
            $role->givePermissionTo('dashboard.view');
            echo "   ✅ {$roleName}: Restored dashboard.view permission\n";
        }
    } else {
        echo "   ⚠️  {$roleName}: Role not found\n";
    }
}

echo "\n🎉 Done! Clear cache and refresh the page.\n";
echo "\nRun these commands:\n";
echo "  php artisan cache:clear\n";
echo "  php artisan permission:cache-reset\n";
echo "\nThen refresh your browser.\n";
