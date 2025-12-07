<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifiedAgent
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // Pastikan user sudah login dan adalah agent
        if (!$user || $user->role !== 'agent') {
            return redirect()->route('login')
                ->with('error', 'Anda harus login sebagai agent.');
        }

        // Ambil data agent
        $agent = $user->agent;

        if (!$agent) {
            return redirect()->route('agent.profile.edit')
                ->with('warning', 'Profil agensi belum dibuat. Silakan lengkapi profil terlebih dahulu.');
        }

        // Cek apakah sudah diverifikasi
        if (!$agent->is_verified) {
            return redirect()->route('agent.dashboard')
                ->with('error', 'Profil Anda belum diverifikasi oleh admin. Selesaikan proses verifikasi terlebih dahulu untuk mengakses fitur ini.');
        }

        return $next($request);
    }
}