<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(): mixed
    {
        return view('pages.login', ['noHeader' => true, 'noFooter' => true]);
    }

    public function loginPost(Request $request): mixed
    {
        $MAX_RETRIES = 3;
        $LOCKOUT_DURATION = 10; // 10 minutes

        // Initialize failed attempts count
        $failedAttempts = $request->session()->get('failed_login_attempts', 0);
        $lockoutTime = $request->session()->get('failed_login_locktime');

        // Check if the lockout period has expired
        
        if ($lockoutTime && $lockoutTime->diff(now()) ) {
            return back()->withErrors([
                'email' => "Too many login attempts. Please try again after {$lockoutTime->diff(now())}.",
            ])->onlyInput('email');
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'max:100'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            // Reset the failed attempts count on successful login
            $request->session()->forget('failed_login_attempts');
            $request->session()->forget('failed_login_locktime');

            return redirect()->intended(route('home'));
        }

        // Increment the failed attempts count
        $failedAttempts++;
        $request->session()->put('failed_login_attempts', $failedAttempts);

        // Set the lockout time if max retries are reached
        if ($failedAttempts >= $MAX_RETRIES) {
            $lockoutUntil = now()->addMinutes($LOCKOUT_DURATION);
            $request->session()->put('failed_login_locktime', $lockoutUntil);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
