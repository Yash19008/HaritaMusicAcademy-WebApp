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
        Route::post('/students/{student}/resend-credentials', [AdminController::class, 'resendCredentials'])->name('students.resend-credentials');
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
        Route::get('/teachers/{teacher}/slots',             [\App\Http\Controllers\Admin\ClassBookingController::class, 'getAvailableSlots'])->name('class-booking.slots');
        Route::post('/bookings',                            [\App\Http\Controllers\Admin\ClassBookingController::class, 'store'])->name('bookings.store');
        Route::put('/bookings/{booking}/status',            [\App\Http\Controllers\Admin\ClassBookingController::class, 'updateStatus'])->name('bookings.status');
        Route::post('/class-booking/{booking}/attendance',  [\App\Http\Controllers\Admin\ClassBookingController::class, 'updateAttendance'])->name('class-booking.attendance');
        
        // Rescheduling
        Route::put('/bookings/{booking}/reschedule',        [\App\Http\Controllers\RescheduleController::class, 'adminReschedule'])->name('bookings.reschedule');
        Route::post('/bookings/{booking}/approve-reschedule', [\App\Http\Controllers\RescheduleController::class, 'approveRequest'])->name('bookings.reschedule.approve');
        Route::post('/bookings/{booking}/reject-reschedule', [\App\Http\Controllers\RescheduleController::class, 'rejectRequest'])->name('bookings.reschedule.reject');
        Route::get('/reschedule/slots',                     [\App\Http\Controllers\RescheduleController::class, 'getAvailableSlots'])->name('reschedule.slots');

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
        Route::post('/demos/{demo}/attendance',               [\App\Http\Controllers\Admin\DemoBookingController::class, 'updateAttendance'])->name('demos.attendance');

        // Reports
        Route::get('/reports/export',                         [AdminController::class, 'exportReports'])->name('reports.export');
        Route::get('/reports',                                [AdminController::class, 'reports'])->name('reports');

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

        // Reminder Configs
        Route::get('/reminder-configs', [\App\Http\Controllers\Admin\ReminderConfigController::class, 'index'])->name('reminder-configs.index');
        Route::post('/reminder-configs', [\App\Http\Controllers\Admin\ReminderConfigController::class, 'store'])->name('reminder-configs.store');
        Route::put('/reminder-configs/{reminderConfig}', [\App\Http\Controllers\Admin\ReminderConfigController::class, 'update'])->name('reminder-configs.update');
        Route::delete('/reminder-configs/{reminderConfig}', [\App\Http\Controllers\Admin\ReminderConfigController::class, 'destroy'])->name('reminder-configs.destroy');

        // Credit Packages
        Route::post('/credit-packages', [\App\Http\Controllers\Admin\CreditPackageController::class, 'store'])->name('credit-packages.store');
        Route::put('/credit-packages/{creditPackage}', [\App\Http\Controllers\Admin\CreditPackageController::class, 'update'])->name('credit-packages.update');
        Route::delete('/credit-packages/{creditPackage}', [\App\Http\Controllers\Admin\CreditPackageController::class, 'destroy'])->name('credit-packages.destroy');

        // Syllabus
        Route::get('/syllabus', [\App\Http\Controllers\Admin\SyllabusController::class, 'index'])->name('syllabus.index');
        Route::post('/syllabus', [\App\Http\Controllers\Admin\SyllabusController::class, 'store'])->name('syllabus.store');
        Route::put('/syllabus/{syllabus}', [\App\Http\Controllers\Admin\SyllabusController::class, 'update'])->name('syllabus.update');
        Route::delete('/syllabus/{syllabus}', [\App\Http\Controllers\Admin\SyllabusController::class, 'destroy'])->name('syllabus.destroy');
        Route::get('/syllabus/{syllabus}/download', [\App\Http\Controllers\Admin\SyllabusController::class, 'download'])->name('syllabus.download');

        // Curriculum
        Route::get('/curriculum', [\App\Http\Controllers\Admin\CurriculumController::class, 'index'])->name('curriculum.index');
        Route::post('/curriculum', [\App\Http\Controllers\Admin\CurriculumController::class, 'store'])->name('curriculum.store');
        Route::put('/curriculum/{curriculum}', [\App\Http\Controllers\Admin\CurriculumController::class, 'update'])->name('curriculum.update');
        Route::delete('/curriculum/{curriculum}', [\App\Http\Controllers\Admin\CurriculumController::class, 'destroy'])->name('curriculum.destroy');
        Route::get('/curriculum/{curriculum}/download', [\App\Http\Controllers\Admin\CurriculumController::class, 'download'])->name('curriculum.download');

        // Profile
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    });
