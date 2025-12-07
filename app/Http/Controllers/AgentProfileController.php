<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;

class AgentProfileController extends Controller
{
    /**
     * Show the agent profile form (display only, no DB save)
     */
    public function show()
    {
        // Check if user is authenticated
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }

        // Check if user is an agent
        if (!$user->isAgent()) {
            return redirect('/tour-packages')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Get current authenticated user's agent profile (if exists)
        $agent = Agent::where('user_id', auth()->id())->first();

        return view('agent.profile', [
            'agent' => $agent,
        ]);
    }
}