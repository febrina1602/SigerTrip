<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAgent
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // 1. Cek Login
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. Cek Role Agent
        if (!$user->isAgent()) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses sebagai mitra.');
        }

        // 3. Cek Status Verifikasi
        if ($user->status === 'pending') {
            // Jika sedang di halaman dashboard (default login redirect), biarkan tapi beri pesan
            // Atau redirect ke halaman "menunggu verifikasi" khusus jika ada
            // Disini kita redirect ke dashboard user biasa agar tidak stuck
            if ($request->routeIs('agent.*')) {
                 return redirect()->route('dashboard')
                    ->with('info', 'Akun mitra Anda masih menunggu verifikasi admin.');
            }
        }

        // 4. CEK KELENGKAPAN PROFIL AGENSI (Logika Baru)
        $agent = $user->agent;
        if ($agent && $user->status === 'aktif') {
            // Tentukan kolom wajib diisi
            $isProfileIncomplete = empty($agent->address) || empty($agent->description) || empty($agent->contact_phone);

            // Jika profil belum lengkap DAN user tidak sedang di halaman profil/logout
            if ($isProfileIncomplete && 
                !$request->routeIs('agent.profile.edit') && 
                !$request->routeIs('agent.profile.update') && 
                !$request->routeIs('logout')
            ) {
                return redirect()->route('agent.profile.edit')
                    ->with('warning', 'Halo! Silakan lengkapi profil agensi Anda (Alamat, Deskripsi, Kontak) terlebih dahulu sebelum mengakses fitur lainnya.');
            }
        }

        return $next($request);
    }
}