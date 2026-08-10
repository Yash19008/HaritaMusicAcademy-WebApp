<?php

use App\Http\Controllers\Teacher\TeacherController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role.access:teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        Route::get('/',                [TeacherController::class, 'dashboard'])->name('dashboard');
        Route::get('/my-classes',     [TeacherController::class, 'myClasses'])->name('my-classes');
        Route::get('/leaves',         [TeacherController::class, 'leaves'])->name('leaves');
        Route::post('/leaves',        [TeacherController::class, 'applyLeave'])->name('leaves.store');
        Route::get('/payroll',        [TeacherController::class, 'payroll'])->name('payroll');
        Route::get('/feedbacks',      [TeacherController::class, 'feedbacks'])->name('feedbacks');
        Route::get('/referrals',      [TeacherController::class, 'referrals'])->name('referrals');
        Route::post('/referrals',     [TeacherController::class, 'storeReferral'])->name('referrals.store');
        Route::get('/profile',        [TeacherController::class, 'profile'])->name('profile');
        Route::get('/settings',       [TeacherController::class, 'settings'])->name('settings');
        Route::post('/settings',      [TeacherController::class, 'saveSettings'])->name('settings.save');
    });
