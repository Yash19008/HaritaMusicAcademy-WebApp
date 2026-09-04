<?php

use Illuminate\Support\Facades\Route;

// ── Landing ───────────────────────────────────────────────────────────────────
Route::get('/', fn () => view('landing.index'));
Route::get('/privacy', fn () => view('landing.privacy'));
Route::post('/book-demo', [\App\Http\Controllers\PublicController::class, 'storeDemo'])->name('public.book-demo');

// ── Razorpay Payment (Demo Booking) ──────────────────────────────────────────
Route::post('/payment/create-order', [\App\Http\Controllers\PaymentController::class, 'createOrder'])->name('payment.create');
Route::post('/payment/verify',       [\App\Http\Controllers\PaymentController::class, 'verifyPayment'])->name('payment.verify');
// Webhook: CSRF excluded via VerifyCsrfToken middleware
Route::post('/payment/webhook',      [\App\Http\Controllers\PaymentController::class, 'webhook'])->name('payment.webhook');

// TEMPORARY LOGOUT FOR DEV
Route::get('/force-logout', function () {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
});

// ── Auth ──────────────────────────────────────────────────────────────────────
require __DIR__ . '/web/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-as-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
});

// ── Admin ─────────────────────────────────────────────────────────────────────
require __DIR__ . '/web/admin.php';

// ── Student ───────────────────────────────────────────────────────────────────
require __DIR__ . '/web/student.php';

// ── Teacher ───────────────────────────────────────────────────────────────────
require __DIR__ . '/web/teacher.php';
