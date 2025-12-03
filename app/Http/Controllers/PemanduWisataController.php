<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\TourPackage;
use App\Models\LocalTourAgent;
use Illuminate\Http\Request;

class PemanduWisataController extends Controller
{
    /**
     * Menampilkan semua agen yang terverifikasi
     */
    public function index(Request $request)
    {
        $keyword = $request->get('q');

        // Jika user agent yang login, tampilkan LocalTourAgent milik agent tersebut
        if (auth()->check() && auth()->user()->role === \App\Models\User::ROLE_AGENT) {
            $userAgent = Agent::where('user_id', auth()->id())->first();
            if ($userAgent) {
                $localQuery = LocalTourAgent::where('agent_id', $userAgent->id);
                if ($keyword) {
                    $localQuery->where('name', 'like', '%' . $keyword . '%');
                }
                $localTourAgents = $localQuery->orderBy('created_at', 'desc')->get();
            } else {
                $localTourAgents = collect();
            }

            return view('wisatawan.pemanduWisata.index', compact('localTourAgents', 'keyword'));
        }

        $query = Agent::where('is_verified', true);

        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('address', 'like', '%' . $keyword . '%');
            });
        }

        $agents = $query->orderBy('created_at', 'desc')->get();

        return view('wisatawan.pemanduWisata.index', compact('agents', 'keyword'));
    }

    /**
     * Menampilkan detail satu agen
     */
    public function show(Agent $agent)
    {
        if (!$agent->is_verified) {
            abort(404, 'Agen tour tidak ditemukan.');
        }

        $tourPackages = $agent->tourPackages()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('wisatawan.pemanduWisata.detailAgen', compact('agent', 'tourPackages'));
    }

    /**
     * Menampilkan semua paket dari satu agen
     */
    public function packages(Agent $agent)
    {
        if (!$agent->is_verified) {
            abort(404, 'Agen tour tidak ditemukan.');
        }
        
        $tourPackages = $agent->tourPackages()
            ->orderBy('created_at', 'desc')
            ->get();

        $isOwner = false;
        if (auth()->check() && auth()->user()->role === \App\Models\User::ROLE_AGENT) {
            $userAgent = Agent::where('user_id', auth()->id())->first();
            $isOwner = $userAgent && $userAgent->id === $agent->id;
        }

        return view('wisatawan.pemanduWisata.packages', compact('agent', 'tourPackages', 'isOwner'));
    }

    /**
     * Menampilkan detail satu paket perjalanan
     */
    public function packageDetail(Agent $agent, TourPackage $tourPackage)
    {
        if ($tourPackage->agent_id !== $agent->id) {
            abort(404, 'Paket perjalanan tidak ditemukan.');
        }

        if (!$agent->is_verified) {
            abort(404, 'Agen tour tidak ditemukan.');
        }

        $isOwner = false;
        if (auth()->check() && auth()->user()->role === \App\Models\User::ROLE_AGENT) {
            $userAgent = Agent::where('user_id', auth()->id())->first();
            $isOwner = $userAgent && $userAgent->id === $agent->id;
        }

        return view('wisatawan.pemanduWisata.package-detail', compact('agent', 'tourPackage', 'isOwner'));
    }
}