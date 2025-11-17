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

            // 2) Buat user baru role user
            $user = User::create([
                'full_name'    => $validated['full_name'],
                'phone_number' => $validated['phone_number'],
                'email'        => $validated['email'],
                'password'     => Hash::make($validated['password']),
                'role'         => User::ROLE_USER, // konstanta di model User
            ]);

            // 3) Login & redirect
            Auth::login($user);
            $request->session()->regenerate(); // lebih aman

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Pendaftaran berhasil! Selamat datang.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Proses registrasi mitra/agen (role: agent, is_verified = false)
     */
    public function registerAgent(Request $request)
    {
        try {
            // Validasi sama seperti user biasa (nanti kalau mau bisa ditambah field khusus agen)
            $validated = $request->validate([
                'full_name'    => 'required|string|max:255',
                'phone_number' => 'required|string|max:20',
                'email'        => 'required|string|email|max:255|unique:users,email',
                'password'     => 'required|string|min:8|confirmed',
            ]);

            $user = null;

            DB::transaction(function () use ($validated, &$user) {
                // 1) Buat user baru role agent
                $user = User::create([
                    'full_name'    => $validated['full_name'],
                    'phone_number' => $validated['phone_number'],
                    'email'        => $validated['email'],
                    'password'     => Hash::make($validated['password']),
                    'role'         => User::ROLE_AGENT,
                ]);

                // 2) Buat data agent:
                //    - name        : pakai full_name
                //    - agent_type  : default LOCAL_TOUR (pakai konstanta Agent::TYPES[0])
                //    - address     : sementara kosong (bisa diisi nanti lewat form profil)
                //    - contact_phone: pakai phone_number
                //    - is_verified : false (belum diverifikasi admin)
                Agent::create([
                    'user_id'       => $user->id,
                    'name'          => $validated['full_name'],
                    'agent_type'    => Agent::TYPES[0], // misal 'LOCAL_TOUR'
                    'address'       => '',               // atau isi alamat default/optional
                    'contact_phone' => $validated['phone_number'],
                    'is_verified'   => false,
                ]);
            });

            // Kalau TIDAK mau auto-login agen baru, pakai ini:
            // return redirect()->route('login')
            //     ->with('success','Pendaftaran mitra berhasil, silakan tunggu verifikasi admin.');

            // Kalau boleh login tapi fitur mitra dikunci sampai diverifikasi:
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Pendaftaran mitra berhasil! Akun Anda menunggu verifikasi admin.');
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
        $remember = $request->boolean('remember');
        if (Auth::attempt($request->only('email', 'password'), $remember)) {
            $request->session()->regenerate();

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
