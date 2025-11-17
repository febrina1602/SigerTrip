<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // belum login -> arahkan ke login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // bukan admin -> tolak
        if (Auth::user()->role !== 'admin') {
            // pilih salah satu: abort 403 atau redirect dgn pesan
            // abort(403, 'Anda tidak memiliki akses.');
            return redirect('/')->with('error', 'Akses admin saja.');
        }

        return $next($request);
    }
}
