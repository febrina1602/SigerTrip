<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Agent; // model agen/mitra
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;      // untuk transaction
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Tampilkan form registrasi wisatawan biasa
     */
    public function showRegistrationForm()
    {
        // mode normal (user)
        return view('auth.register');
    }

    /**
     * Tampilkan form registrasi mitra/agen
     */
    public function showAgentRegistrationForm()
    {
        // kirim flag $isAgent ke view
        return view('auth.register', [
            'isAgent' => true,
        ]);
    }

    /**
     * Tampilkan form login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses registrasi wisatawan (role: user)
     */
    public function register(Request $request)
    {
        try {
            // 1) Validasi input
            $validated = $request->validate([
                'full_name'    => 'required|string|max:255',
                'phone_number' => 'required|string|max:20',
                'email'        => 'required|string|email|max:255|unique:users,email',
                'password'     => 'required|string|min:8|confirmed',
            ]);

            // 2) Buat user baru role user - LANGSUNG AKTIF
            $user = User::create([
                'full_name'    => $validated['full_name'],
                'phone_number' => $validated['phone_number'],
                'email'        => $validated['email'],
                'password'     => Hash::make($validated['password']),
                'role'         => User::ROLE_USER,
                'status'       => 'aktif',      // ✅ TAMBAHKAN INI
                'verified_at'  => now(),        // ✅ TAMBAHKAN INI
            ]);

            // 3) Login & redirect
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Pendaftaran berhasil! Selamat datang.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Proses registrasi mitra/agen (role: agent, status = pending)
     */
    public function registerAgent(Request $request)
    {
        try {
            $validated = $request->validate([
                'full_name'    => 'required|string|max:255',
                'phone_number' => 'required|string|max:20',
                'email'        => 'required|string|email|max:255|unique:users,email',
                'password'     => 'required|string|min:8|confirmed',
            ]);

            $user = null;

            DB::transaction(function () use ($validated, &$user) {
                // 1) Buat user baru role agent - STATUS PENDING
                $user = User::create([
                    'full_name'    => $validated['full_name'],
                    'phone_number' => $validated['phone_number'],
                    'email'        => $validated['email'],
                    'password'     => Hash::make($validated['password']),
                    'role'         => User::ROLE_AGENT,
                    'status'       => 'pending',    // ✅ Agent harus pending
                    'verified_at'  => null,         // ✅ Belum diverifikasi
                ]);

                // 2) Buat data agent
                Agent::create([
                    'user_id'       => $user->id,
                    'name'          => $validated['full_name'],
                    'agent_type'    => Agent::TYPES[0],
                    'address'       => '',
                    'contact_phone' => $validated['phone_number'],
                    'is_verified'   => false,
                ]);
            });

            // JANGAN login agent yang masih pending
            // Redirect ke login dengan pesan info
            return redirect()->route('dashboard')
                ->with('success', 'Pendaftaran mitra berhasil! Silakan tunggu verifikasi dari admin sebelum dapat login.');
                
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
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // 2) Coba autentikasi
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = auth()->user();

            // Cek status user - NONAKTIF tidak bisa login
            if ($user->status === 'nonaktif') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
                ])->onlyInput('email');
            }

            // Cek status user - PENDING (khusus agent)
            if ($user->role === User::ROLE_AGENT && $user->status === 'pending') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun mitra Anda masih menunggu verifikasi dari admin.',
                ])->onlyInput('email');
            }

            // Redirect berdasarkan role
            if ($user->role === User::ROLE_ADMIN) {
                return redirect()->route('admin.beranda')
                    ->with('success', 'Selamat datang, Admin!');
            }

            if ($user->role === User::ROLE_AGENT) {
                return redirect()->route('agent.dashboard')
                    ->with('success', 'Login mitra berhasil!');
            }

            // default: wisatawan/user biasa
            return redirect()->route('dashboard')
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

        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }
}
