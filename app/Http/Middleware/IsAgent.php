<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAgent
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Check if user is authenticated and is an agent
        if (!$user || !$user->isAgent()) {
            return redirect('/tour-packages')->with('error', 'Anda tidak memiliki akses ke fitur ini.');
        }

        // If agent doesn't have agent profile yet, redirect to setup profile
        if (!$user->agent) {
            return redirect('/agent/profile')->with('warning', 'Harap lengkapi profil agensi Anda terlebih dahulu.');
        }

        return $next($request);
    }
}
