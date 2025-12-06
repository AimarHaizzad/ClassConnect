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

        // Compare plain text passwords
        if ($user && $credentials['password'] === $user->password) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
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
