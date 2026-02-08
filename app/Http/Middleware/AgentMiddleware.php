<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login'); // Redirect ke halaman login jika belum login
        }

        $user = Auth::user();

        // Jika pengguna tidak memiliki role 'agent', maka redirect atau abort
        if (! $user->isAgent()) {
            // Pilihan 1: Menggunakan Abort 403
            // return abort(403, 'Halaman ini hanya untuk mitra/agent.');

            // Pilihan 2: Redirect ke halaman beranda dengan pesan error
            return redirect()->route('beranda.wisatawan')
                ->with('error', 'Hanya mitra/agent yang dapat mengakses halaman ini.');
        }

        return $next($request); // Lanjutkan request jika role sesuai
    }
}
