<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Unable to authenticate with Google.']);
        }

        // Find user by email
        $user = User::where('email', $googleUser->getEmail())->first();

        // If the user does not exist in our database, reject login
        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'This Google account is not registered in our system. Please contact the administrator.']);
        }

        // Update google_id and avatar only if changed
        $avatarUrl = filter_var($googleUser->getAvatar(), FILTER_VALIDATE_URL) ? $googleUser->getAvatar() : null;
        $changes = [];
        
        if ($user->google_id !== $googleUser->getId()) {
            $changes['google_id'] = $googleUser->getId();
        }
        
        if ($user->avatar !== $avatarUrl) {
            $changes['avatar'] = $avatarUrl;
        }
        
        if (!empty($changes)) {
            $user->update($changes);
        }

        // Perform active status checks identical to standard login
        if ($user->status !== 'active') {
            return redirect()->route('login')->withErrors(['email' => 'Your account is not active. Please contact the administrator.']);
        }

        if ($user->hasRole('teacher') && $user->teacher && $user->teacher->status !== 'active') {
            return redirect()->route('login')->withErrors(['email' => 'Your teacher profile is inactive. Please contact the administrator.']);
        }

        if ($user->hasRole('student') && $user->student && $user->student->status !== 'active') {
            return redirect()->route('login')->withErrors(['email' => 'Your student profile is inactive. Please contact the administrator.']);
        }

        // Log the user in
        Auth::login($user, true);

        // Regenerate session to prevent fixation
        $request->session()->regenerate();

        // Determine redirect path (similar to AuthController)
        if ($user->hasRole('Admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('teacher')) {
            return redirect()->route('teacher.dashboard');
        } elseif ($user->hasRole('student')) {
            return redirect()->route('student.dashboard');
        }

        return redirect('/');
    }
}
