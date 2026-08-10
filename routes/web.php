<?php

use Illuminate\Support\Facades\Route;

// ── Landing ───────────────────────────────────────────────────────────────────
Route::get('/', fn () => view('landing.index'));
Route::get('/privacy', fn () => view('landing.privacy'));
Route::post('/book-demo', [\App\Http\Controllers\PublicController::class, 'storeDemo'])->name('public.book-demo');

// TEMPORARY LOGOUT FOR DEV
Route::get('/force-logout', function () {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
});

// ── Auth ──────────────────────────────────────────────────────────────────────
require __DIR__ . '/web/auth.php';

// ── Admin ─────────────────────────────────────────────────────────────────────
require __DIR__ . '/web/admin.php';

// ── Student ───────────────────────────────────────────────────────────────────
require __DIR__ . '/web/student.php';

// ── Teacher ───────────────────────────────────────────────────────────────────
require __DIR__ . '/web/teacher.php';
