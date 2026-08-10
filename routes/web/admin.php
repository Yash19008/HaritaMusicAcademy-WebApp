<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CreditController;
use App\Http\Controllers\Admin\LeaveController;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role.access:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        // Students
        Route::get('/students',                       [AdminController::class, 'students'])->name('students');
        Route::post('/students',                      [AdminController::class, 'storeStudent'])->name('students.store');
        Route::put('/students/{student}',             [AdminController::class, 'updateStudent'])->name('students.update');
        Route::delete('/students/{student}',          [AdminController::class, 'destroyStudent'])->name('students.destroy');
        Route::post('/students/bulk-import',          [AdminController::class, 'bulkImportStudents'])->name('students.bulk-import');
        Route::post('/student-groups',                [AdminController::class, 'storeGroup'])->name('groups.store');
        Route::put('/student-groups/{studentGroup}',  [AdminController::class, 'updateGroup'])->name('groups.update');
        Route::delete('/student-groups/{studentGroup}', [AdminController::class, 'destroyGroup'])->name('groups.destroy');

        // Teachers
        Route::get('/teachers',               [AdminController::class, 'teachers'])->name('teachers');
        Route::post('/teachers',              [AdminController::class, 'storeTeacher'])->name('teachers.store');
        Route::put('/teachers/{teacher}',     [AdminController::class, 'updateTeacher'])->name('teachers.update');
        Route::delete('/teachers/{teacher}',  [AdminController::class, 'destroyTeacher'])->name('teachers.destroy');

        // Class Booking
        Route::get('/class-booking',                        [\App\Http\Controllers\Admin\ClassBookingController::class, 'index'])->name('class-booking');
        Route::post('/bookings',                            [\App\Http\Controllers\Admin\ClassBookingController::class, 'store'])->name('bookings.store');
        Route::put('/bookings/{booking}/status',            [\App\Http\Controllers\Admin\ClassBookingController::class, 'updateStatus'])->name('bookings.status');
        Route::put('/bookings/{booking}/reschedule',        [\App\Http\Controllers\Admin\ClassBookingController::class, 'reschedule'])->name('bookings.reschedule');

        // Credits
        Route::get('/credits',               [CreditController::class, 'index'])->name('credits');
        Route::post('/credits/adjust',       [CreditController::class, 'adjust'])->name('credits.adjust');

        // Sales
        Route::get('/sales',               [AdminController::class, 'sales'])->name('sales');
        Route::post('/sales',              [AdminController::class, 'storeLead'])->name('sales.store');
        Route::put('/sales/{payment}',     [AdminController::class, 'updateLead'])->name('sales.update');
        Route::post('/sales/{payment}/convert', [AdminController::class, 'convertLeadToStudent'])->name('sales.convert');
        Route::delete('/sales/{payment}',  [AdminController::class, 'destroyLead'])->name('sales.destroy');

        // Demos
        Route::get('/demos',                                  [\App\Http\Controllers\Admin\DemoBookingController::class, 'index'])->name('demos');
        Route::post('/demos',                                 [\App\Http\Controllers\Admin\DemoBookingController::class, 'store'])->name('demos.store');
        Route::put('/demos/{demo}/status',                    [\App\Http\Controllers\Admin\DemoBookingController::class, 'updateStatus'])->name('demos.status');
        Route::post('/demos/{demo}/convert',                  [\App\Http\Controllers\Admin\DemoBookingController::class, 'convert'])->name('demos.convert');

        // Reports
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');

        // Leaves
        Route::get('/leaves',                                   [LeaveController::class, 'index'])->name('leaves');
        Route::post('/leaves/{teacherLeave}/approve',           [LeaveController::class, 'approve'])->name('leaves.approve');
        Route::post('/leaves/{teacherLeave}/reject',            [LeaveController::class, 'reject'])->name('leaves.reject');

        // Payroll
        Route::get('/payroll', [AdminController::class, 'payroll'])->name('payroll');
        Route::post('/payroll/disburse-all', [AdminController::class, 'disburseAllPayroll'])->name('payroll.disburse-all');
        Route::put('/payroll/{payroll}/rate', [AdminController::class, 'updatePayrollRate'])->name('payroll.rate.update');
        Route::post('/payroll/{payroll}/disburse', [AdminController::class, 'disbursePayroll'])->name('payroll.disburse');
        // Referrals
        Route::get('/referrals',               [AdminController::class, 'referrals'])->name('referrals');
        Route::put('/referrals/{referral}',    [AdminController::class, 'updateReferral'])->name('referrals.update');

        // Feedbacks
        Route::get('/feedbacks',                              [AdminController::class, 'feedbacks'])->name('feedbacks');
        Route::put('/feedbacks/{feedback}/status',            [AdminController::class, 'updateFeedbackStatus'])->name('feedbacks.status');

        // Roles & Permissions
        Route::get('/roles', [RoleController::class, 'index'])->name('roles');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
        Route::post('/roles/{role}/clone', [RoleController::class, 'clone'])->name('roles.clone');
        
        // Role Permissions API
        Route::get('/api/roles/permissions', [RoleController::class, 'getRolePermissions'])->name('api.roles.permissions');
        Route::post('/api/roles/permissions', [RoleController::class, 'updatePermissions'])->name('api.roles.permissions.update');
        
        // Users API
        Route::get('/api/users', [RoleController::class, 'getUsers'])->name('api.users');
        Route::post('/users', [RoleController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{user}', [RoleController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [RoleController::class, 'destroyUser'])->name('users.destroy');

        // Settings
        Route::get('/settings',   [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings',  [AdminController::class, 'saveSettings'])->name('settings.save');

        // Profile
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    });
