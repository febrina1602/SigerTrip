<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Tampilkan form registrasi
     * resources/views/auth/register.blade.php
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Tampilkan form login
     * resources/views/auth/login.blade.php
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses registrasi
     */
    public function register(Request $request)
    {
        try {
            // 1) Validasi
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'email'     => 'required|string|email|max:255|unique:users,email',
                'password'  => 'required|string|min:8|confirmed', // butuh field password_confirmation
            ]);

            // 2) Buat user baru
            $user = User::create([
                'full_name' => $validated['full_name'],
                'email'     => $validated['email'],
                'password'  => Hash::make($validated['password']),
                'role'      => 'user', // default role
            ]);

            // 3) Login & redirect
            Auth::login($user);
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Pendaftaran berhasil! Selamat datang.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        // 1) Validasi
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // 2) Coba autentikasi
        $remember = $request->boolean('remember'); // cek kalau ada checkbox remember
        if (Auth::attempt($request->only('email', 'password'), $remember)) {
            $request->session()->regenerate(); // anti session fixation
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Login berhasil!');
        }

        // 3) Gagal
        return back()->withErrors([
            'email' => 'Kredensial yang Anda masukkan tidak valid.',
        ])->onlyInput('email');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil logout.');
    }
}
