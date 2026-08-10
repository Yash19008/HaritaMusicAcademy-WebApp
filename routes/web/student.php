<?php

use App\Http\Controllers\Student\StudentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role.access:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/',                 [StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/my-classes',      [StudentController::class, 'myClasses'])->name('my-classes');
        Route::post('/my-classes/book',[StudentController::class, 'bookClass'])->name('my-classes.book');
        Route::post('/my-classes/{booking}/reschedule',[StudentController::class, 'requestReschedule'])->name('my-classes.reschedule');
        Route::get('/credits',         [StudentController::class, 'credits'])->name('credits');
        Route::get('/feedback',        [StudentController::class, 'feedback'])->name('feedback');
        Route::post('/feedback',       [StudentController::class, 'storeFeedback'])->name('feedback.store');
        Route::get('/referrals',       [StudentController::class, 'referrals'])->name('referrals');
        Route::post('/referrals',      [StudentController::class, 'storeReferral'])->name('referrals.store');
        Route::get('/profile',         [StudentController::class, 'profile'])->name('profile');
        Route::get('/settings',        [StudentController::class, 'settings'])->name('settings');
        Route::post('/settings',       [StudentController::class, 'saveSettings'])->name('settings.save');
    });
