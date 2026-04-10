<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'customer',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('customer.home')->with('success', 'Account created successfully. Welcome to StayEase Hotel.');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['nullable', 'string'],
            'user' => ['nullable', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $loginValue = trim((string) ($validated['email'] ?? $validated['user'] ?? ''));

        if ($loginValue === '') {
            throw ValidationException::withMessages([
                'email' => 'Email or username is required.',
            ]);
        }

        if ($loginValue === 'admin') {
            $loginValue = 'admin@stayease.local';
        }

        $credentials = [
            'email' => $loginValue,
            'password' => $validated['password'],
        ];

        $remember = (bool) ($validated['remember'] ?? false);

        if (! Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        $redirectRoute = 'customer.home';
        if ($user?->role === 'admin') {
            $redirectRoute = 'dashboard';
        } elseif ($user?->role === 'staff') {
            $redirectRoute = 'staff.dashboard';
        } elseif ($user?->role === 'customer') {
            $redirectRoute = 'customer.history';
        }

        return redirect()->intended(route($redirectRoute));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
