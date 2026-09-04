<?php

use App\Http\Controllers\Teacher\TeacherController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role.access:teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        Route::get('/',                [TeacherController::class, 'dashboard'])->name('dashboard');
        Route::get('/my-classes',     [TeacherController::class, 'myClasses'])->name('my-classes');
        Route::get('/demo-classes',   [TeacherController::class, 'demoClasses'])->name('demo-classes');
        Route::post('/my-classes/{booking}/reschedule',[\App\Http\Controllers\RescheduleController::class, 'requestReschedule'])->name('my-classes.reschedule');
        Route::get('/reschedule/slots',               [\App\Http\Controllers\RescheduleController::class, 'getAvailableSlots'])->name('reschedule.slots');
        Route::get('/leaves',         [TeacherController::class, 'leaves'])->name('leaves');
        Route::get('/leaves/loss',    [TeacherController::class, 'calculateLoss'])->name('leaves.loss');
        Route::post('/leaves',        [TeacherController::class, 'applyLeave'])->name('leaves.store');
        Route::get('/payroll',        [TeacherController::class, 'payroll'])->name('payroll');
        Route::get('/feedbacks',      [TeacherController::class, 'feedbacks'])->name('feedbacks');
        Route::get('/referrals',      [TeacherController::class, 'referrals'])->name('referrals');
        Route::post('/referrals',     [TeacherController::class, 'storeReferral'])->name('referrals.store');
        Route::get('/profile',        [TeacherController::class, 'profile'])->name('profile');
        Route::get('/settings',       [TeacherController::class, 'settings'])->name('settings');
        Route::post('/settings',      [TeacherController::class, 'saveSettings'])->name('settings.save');

        // Resources
        Route::get('/resources',      [TeacherController::class, 'resources'])->name('resources');
        Route::get('/resources/download/{filename}', [TeacherController::class, 'downloadResource'])->name('resources.download');

        // Opportunities
        Route::get('/opportunities/current', [\App\Http\Controllers\Teacher\OpportunityController::class, 'current'])->name('opportunities.current');
        Route::post('/opportunities/{id}/accept', [\App\Http\Controllers\Teacher\OpportunityController::class, 'accept'])->name('opportunities.accept');
        Route::post('/opportunities/{id}/reject', [\App\Http\Controllers\Teacher\OpportunityController::class, 'reject'])->name('opportunities.reject');
    });
