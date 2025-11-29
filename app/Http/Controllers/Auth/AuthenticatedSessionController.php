<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses autentikasi (bisa pakai npm atau email).
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi: salah satu (email / npm) wajib diisi + password
        $request->validate([
            'password' => ['required', 'string'],
            'email'    => ['nullable', 'email'],
            'npm'      => ['nullable', 'string', 'max:50'],
        ]);

        if (!$request->filled('email') && !$request->filled('npm')) {
            return back()->withErrors([
                'email' => 'Masukkan email atau NPM.',
                'npm'   => 'Masukkan email atau NPM.',
            ])->onlyInput('email', 'npm');
        }

        $remember = $request->boolean('remember');

        // 1) Coba login pakai NPM jika diisi
        if ($request->filled('npm')) {
            if (Auth::attempt(['npm' => $request->npm, 'password' => $request->password], $remember)) {
                $request->session()->regenerate();
                return redirect()->intended(route('dashboard', absolute: false));
            }
        }

        // 2) Kalau gagal / tidak diisi, coba login pakai email (jika ada)
        if ($request->filled('email')) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
                $request->session()->regenerate();
                return redirect()->intended(route('dashboard', absolute: false));
            }
        }

        // Gagal autentikasi
        return back()->withErrors([
            'email' => 'Kredensial tidak valid.',
        ])->onlyInput('email', 'npm');
    }

    /**
     * Logout.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
