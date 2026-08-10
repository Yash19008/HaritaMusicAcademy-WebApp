<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Define modules and their actions
        $modules = [
            'dashboard' => ['view'],
            'students' => ['view', 'create', 'edit', 'delete'],
            'teachers' => ['view', 'create', 'edit', 'delete'],
            'courses' => ['view', 'create', 'edit', 'delete'],
            'classes' => ['view', 'create', 'edit', 'delete'],
            'bookings' => ['view', 'create', 'edit', 'delete', 'approve'],
            'attendance' => ['view', 'create', 'edit', 'delete'],
            'credits' => ['view', 'create', 'edit', 'delete'],
            'payments' => ['view', 'create', 'edit', 'delete'],
            'sales' => ['view', 'create', 'edit', 'delete'],
            'demos' => ['view', 'create', 'edit', 'delete'],
            'reports' => ['view'],
            'payroll' => ['view', 'create', 'edit', 'delete', 'approve'],
            'referrals' => ['view', 'edit', 'approve'],
            'feedbacks' => ['view', 'edit', 'delete'],
            'leaves' => ['view', 'create', 'edit', 'delete', 'approve'],
            'settings' => ['view', 'edit'],
            'roles' => ['view', 'create', 'edit', 'delete'],
        ];

        // Create all permissions
        $permissions = collect($modules)
            ->flatMap(fn (array $actions, string $module) => 
                collect($actions)->map(fn (string $action) => $module . '.' . $action)
            )
            ->values();

        $permissions->each(fn (string $permission) => 
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web'])
        );

        // Create Super Admin role
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->forceFill([
            'description' => 'Full system access - Can do everything',
            'status' => 'active',
        ])->save();
        $superAdmin->syncPermissions($permissions->all());

        // Create Admin role
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->forceFill([
            'description' => 'Manage academy operations - Full access except deleting roles',
            'status' => 'active',
        ])->save();
        $admin->syncPermissions(
            $permissions->reject(fn (string $permission) => $permission === 'roles.delete')->all()
        );

        // Create Teacher role
        $teacher = Role::firstOrCreate(['name' => 'Teacher', 'guard_name' => 'web']);
        $teacher->forceFill([
            'description' => 'Manage classes, attendance and view students',
            'status' => 'active',
        ])->save();
        $teacher->syncPermissions([
            'dashboard.view',
            'classes.view',
            'classes.edit',
            'attendance.view',
            'attendance.create',
            'attendance.edit',
            'students.view',
            'leaves.view',
            'leaves.create',
            'payroll.view',
        ]);

        // Create Student role
        $student = Role::firstOrCreate(['name' => 'Student', 'guard_name' => 'web']);
        $student->forceFill([
            'description' => 'View classes, book classes and manage profile',
            'status' => 'active',
        ])->save();
        $student->syncPermissions([
            'dashboard.view',
            'classes.view',
            'bookings.view',
            'bookings.create',
            'teachers.view',
            'credits.view',
            'feedbacks.create',
        ]);

        // Reset cached roles and permissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->command->info('✅ Roles and permissions created successfully!');
        $this->command->info('✅ Created 4 roles: Super Admin, Admin, Teacher, Student');
        $this->command->info('✅ Created ' . $permissions->count() . ' permissions');
    }
}
