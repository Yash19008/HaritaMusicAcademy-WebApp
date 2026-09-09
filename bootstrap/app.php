<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Exclude Razorpay webhook from CSRF — it's verified via X-Razorpay-Signature header
        $middleware->validateCsrfTokens(except: [
            'payment/webhook',
        ]);

        $middleware->alias([
            'role.access' => \App\Http\Middleware\RoleAccess::class,
            'set.locale'  => \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->redirectUsersTo(function (\Illuminate\Http\Request $request) {
            $user = $request->user();
            if ($user->hasRole('teacher')) return route('teacher.dashboard');
            if ($user->hasRole('student')) return route('student.dashboard');
            return route('admin.dashboard');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
