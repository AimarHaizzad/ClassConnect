<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): RedirectResponse|View
    {
        // If user is already authenticated, redirect to dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Show login page for guests
        return view('auth.login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('username', 'password');
        $userType = $request->input('user_type');

        $user = User::where('username', $credentials['username'])
            ->where('user_type', $userType)
            ->first();

        // Check if account is locked
        if ($user && $user->locked_until && $user->locked_until->isFuture()) {
            $minutesRemaining = now()->diffInMinutes($user->locked_until);
            return back()->withErrors([
                'username' => "Your account has been locked due to multiple failed login attempts. Please try again in {$minutesRemaining} minute(s).",
            ])->onlyInput('username');
        }

        // If lock period has expired, reset the lock
        if ($user && $user->locked_until && $user->locked_until->isPast()) {
            $user->update([
                'failed_login_attempts' => 0,
                'locked_until' => null,
            ]);
        }

        // Compare plain text passwords
        if ($user && $credentials['password'] === $user->password) {
            // Reset failed login attempts on successful login
            $user->update([
                'failed_login_attempts' => 0,
                'locked_until' => null,
            ]);

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        // Increment failed login attempts
        if ($user) {
            $failedAttempts = ($user->failed_login_attempts ?? 0) + 1;
            $maxAttempts = 5;
            $lockoutMinutes = 30;

            if ($failedAttempts >= $maxAttempts) {
                // Lock the account for 30 minutes
                $user->update([
                    'failed_login_attempts' => $failedAttempts,
                    'locked_until' => now()->addMinutes($lockoutMinutes),
                ]);

                return back()->withErrors([
                    'username' => "Your account has been locked after {$maxAttempts} failed login attempts. Please try again in {$lockoutMinutes} minutes.",
                ])->onlyInput('username');
            } else {
                // Just increment the counter
                $user->update([
                    'failed_login_attempts' => $failedAttempts,
                ]);

                $remainingAttempts = $maxAttempts - $failedAttempts;
                return back()->withErrors([
                    'username' => "The provided credentials do not match our records. {$remainingAttempts} attempt(s) remaining before account lockout.",
                ])->onlyInput('username');
            }
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = User::create($request->validated());

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}
