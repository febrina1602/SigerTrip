<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->role !== 'admin') {
            if ($user->role === 'agent') {
                return redirect()->route('agent.dashboard')->with('error', 'Akses khusus Admin.');
            }
            if ($user->role === 'user') {
                return redirect()->route('dashboard')->with('error', 'Akses khusus Admin.');
            }
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        return $next($request);
    }
}