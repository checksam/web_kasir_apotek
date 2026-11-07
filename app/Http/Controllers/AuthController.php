<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->hasRole('kasir')) {
                return redirect()->route('kasir.dashboard');
            }

            if ($user->hasRole('pelanggan')) {
                return redirect()->route('pelanggan.dashboard');
            }

            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Akun ini belum memiliki role yang diizinkan.',
            ]);
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials + ['is_active' => true], $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

        $user = Auth::user();
        if (! $user->hasAnyRole(['admin', 'kasir', 'pelanggan'])) {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Akun ini belum memiliki role yang diizinkan.',
            ]);
        }

        $request->session()->regenerate();
        $role = $user->getRoleNames()->first();
        $request->session()->put('user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $role,
        ]);

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('kasir')) {
            return redirect()->route('kasir.dashboard');
        }

        return redirect()->route('pelanggan.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->forget('user');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
