<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleAccess
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // If route requires admin, let any internal staff (non-student, non-teacher) pass.
        // Spatie will handle fine-grained permissions inside.
        if ($role === 'admin' && !$user->hasAnyRole(['student', 'teacher'])) {
            return $next($request);
        }

        if (!$user->hasRole($role)) {
            // Redirect to the user's actual dashboard based on their role
            if ($user->hasRole('teacher')) return redirect()->route('teacher.dashboard');
            if ($user->hasRole('student')) return redirect()->route('student.dashboard');
            return redirect('/');
        }

        return $next($request);
    }
}
