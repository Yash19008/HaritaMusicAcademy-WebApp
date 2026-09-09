<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;

// ── Landing ───────────────────────────────────────────────────────────────────

// ── Landing: root → India (default) ─────────────────────────────────────────
Route::get('/', fn () => redirect('/in', 301));
Route::get('/privacy', fn () => view('landing.privacy'))->name('landing.privacy');

// ── Landing: country-prefixed pages ──────────────────────────────────────────
// Supported slugs: in | us | uk | cad | uae
// SetLocale middleware resolves locale config and shares $locale + $country with all views.
Route::prefix('{country}')
    ->middleware('set.locale')
    ->where(['country' => 'in|us|uk|cad|uae'])
    ->group(function () {
        Route::get('/', [LandingController::class, 'index'])->name('landing.index');
        Route::post('/book-demo', [LandingController::class, 'storeDemo'])->name('public.book-demo');
    });


// ── Razorpay Payment (Demo Booking) ──────────────────────────────────────────
Route::post('/payment/create-order', [\App\Http\Controllers\PaymentController::class, 'createOrder'])->middleware('throttle:5,1')->name('payment.create');
Route::post('/payment/verify',       [\App\Http\Controllers\PaymentController::class, 'verifyPayment'])->name('payment.verify');
// Webhook: CSRF excluded via VerifyCsrfToken middleware
Route::post('/payment/webhook',      [\App\Http\Controllers\PaymentController::class, 'webhook'])->name('payment.webhook');

// Debug routes removed for production security

// ── Auth ──────────────────────────────────────────────────────────────────────
require __DIR__ . '/web/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-as-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
});

// Attendance links may be used by guest demo students; the controller enforces
// authentication for regular classes and all teacher joins.
Route::get('/join/{type}/{token}', [\App\Http\Controllers\AttendanceController::class, 'join'])
    ->whereIn('type', ['teacher', 'student'])
    ->name('attendance.join');

// ── Admin ─────────────────────────────────────────────────────────────────────
require __DIR__ . '/web/admin.php';

// ── Student ───────────────────────────────────────────────────────────────────
require __DIR__ . '/web/student.php';

// ── Teacher ───────────────────────────────────────────────────────────────────
require __DIR__ . '/web/teacher.php';
