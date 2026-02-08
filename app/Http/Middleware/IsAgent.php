<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAgent
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // 1. Cek Login
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. Cek Role Agent (Strict Redirect)
        if ($user->role !== 'agent') {
            if ($user->role === 'admin') {
                return redirect()->route('admin.beranda')->with('error', 'Admin tidak dapat mengakses halaman Mitra.');
            }
            if ($user->role === 'user') {
                return redirect()->route('dashboard')->with('error', 'Anda harus mendaftar sebagai Mitra untuk akses ini.');
            }
            // Fallback
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        // 3. Cek Status Verifikasi (Pending)
        if ($user->status === 'pending') {
            // Izinkan akses ke route logout agar tidak terjebak
            if ($request->routeIs('logout')) {
                return $next($request);
            }
            
            // Redirect ke halaman info atau dashboard umum dengan pesan
            return redirect()->route('beranda.wisatawan')
                ->with('info', 'Akun mitra Anda sedang dalam proses verifikasi oleh Admin.');
        }

        // 4. CEK KELENGKAPAN PROFIL (Logic tetap sama seperti sebelumnya)
        $agent = $user->agent;
        if ($agent && $user->status === 'aktif') {
            $isProfileIncomplete = empty($agent->address) || empty($agent->description) || empty($agent->contact_phone);

            $allowedRoutes = ['agent.profile.edit', 'agent.profile.update', 'logout'];

            if ($isProfileIncomplete && !in_array($request->route()->getName(), $allowedRoutes)) {
                return redirect()->route('agent.profile.edit')
                    ->with('warning', 'Mohon lengkapi profil agensi Anda sebelum melanjutkan.');
            }
        }

        return $next($request);
    }
}