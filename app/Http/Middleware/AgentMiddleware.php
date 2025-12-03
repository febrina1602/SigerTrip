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
        // Jika belum login, lempar ke login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Kalau punya helper isAgent() di model User
        if (! $user->isAgent()) {
            // Bisa abort 403 atau redirect ke beranda
            return abort(403, 'Halaman ini hanya untuk mitra/agent.');
            // atau:
            // return redirect()->route('beranda.wisatawan')
            //     ->with('error', 'Anda bukan mitra/agent.');
        }

        return $next($request);
    }
}
