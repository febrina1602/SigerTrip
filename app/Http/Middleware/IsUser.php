<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsUser
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Jika belum login (Guest), IZINKAN akses (karena ini halaman publik)
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // 2. Jika sudah login, CEK ROLE
        
        // Jika Agent coba masuk halaman User -> Lempar ke Dashboard Agent
        if ($user->role === 'agent') {
            return redirect()->route('agent.dashboard')->with('warning', 'Anda sedang login sebagai Agent.');
        }

        // Jika Admin coba masuk halaman User -> Lempar ke Dashboard Admin
        if ($user->role === 'admin') {
            return redirect()->route('admin.beranda')->with('warning', 'Anda sedang login sebagai Admin.');
        }

        // Jika User biasa -> Silakan lanjut
        return $next($request);
    }
}