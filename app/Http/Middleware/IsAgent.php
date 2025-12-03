<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAgent
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // Belum login → tolak
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Role bukan agent → tolak
        if (!$user->isAgent()) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses sebagai mitra.');
        }

        // Agent belum diverifikasi → tolak
        if ($user->status === 'pending') {
            return redirect()->route('dashboard')
                ->with('info', 'Akun mitra Anda masih menunggu verifikasi admin.');
        }

        return $next($request);
    }
}
