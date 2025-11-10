<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /** Tampilkan form register */
    public function showRegistrationForm()
    {
        // views/resources/register.blade.php
        return view('register');
    }

    /** Tampilkan form login */
    public function showLoginForm()
    {
        // views/resources/login.blade.php
        return view('login');
    }

    /** Proses register */
    public function register(Request $request)
    {
        try {
            // 1) Validasi
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'email'     => 'required|string|email|max:255|unique:users,email',
                'password'  => 'required|string|min:8|confirmed',
            ]);

            // 2) Buat user (PAKAI nilai validasi, bukan string rules)
            $user = User::create([
                'full_name' => $validated['full_name'],
                'email'     => $validated['email'],
                'password'  => Hash::make($validated['password']),
                 'role'      => 'user',
            ]);

            // 3) Login otomatis lalu arahkan
            Auth::login($user);
            return redirect()->route('dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    /** Proses login */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard')->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'email' => 'Kredensial yang Anda masukkan tidak valid.',
        ])->onlyInput('email');
    }

    /** Logout */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Anda telah berhasil logout.');
    }
}
