<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Drop the hardcoded 'role' column from users if it exists
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function ($table) {
                $table->dropColumn('role');
            });
        }

        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'dashboard.view',
            // Users/Roles
            'users.view', 'users.create', 'users.edit', 'users.delete',
            // Students
            'students.view', 'students.create', 'students.edit', 'students.delete',
            // Teachers
            'teachers.view', 'teachers.create', 'teachers.edit', 'teachers.delete',
            // Courses
            'courses.view', 'courses.create', 'courses.edit', 'courses.delete',
            // Classes & Bookings
            'classes.view', 'classes.create', 'classes.edit', 'classes.delete',
            'bookings.view', 'bookings.create', 'bookings.edit', 'bookings.delete',
            // Credits
            'credits.view', 'credits.create', 'credits.edit', 'credits.delete',
            // Leaves
            'leaves.view', 'leaves.create', 'leaves.edit', 'leaves.delete', 'leaves.approve',
            // Sales
            'sales.view', 'sales.create', 'sales.edit', 'sales.delete',
            // Reports & Payroll
            'reports.view',
            'payroll.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles and Assign Permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'status' => 'active', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        $teacherRole = Role::firstOrCreate(['name' => 'teacher', 'status' => 'active', 'guard_name' => 'web']);
        $teacherRole->syncPermissions([
            'dashboard.view',
            'classes.view',
            'leaves.view',
            'payroll.view'
        ]);

        $studentRole = Role::firstOrCreate(['name' => 'student', 'status' => 'active', 'guard_name' => 'web']);
        $studentRole->syncPermissions([
            'dashboard.view',
            'classes.view'
        ]);

        // Fix existing users to ensure they have the role via Spatie
        // (Since we dropped the role column, we can just assign the first user as admin)
        $adminUser = User::where('email', 'admin@haritamusic.com')->first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }
    }
}
