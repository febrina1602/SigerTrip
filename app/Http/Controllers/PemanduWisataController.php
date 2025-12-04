<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\TourPackage;
use App\Models\LocalTourAgent;
use Illuminate\Http\Request;

class PemanduWisataController extends Controller
{
    /**
     * Main catalog: tampilkan semua LocalTourAgent
     * - Jika agent login: tampilkan LocalTourAgent milik agent itu (untuk manage)
     * - Jika user/guest: tampilkan LocalTourAgent dari verified agents (katalog)
     */
    public function index(Request $request)
    {
        $keyword = $request->get('q');

        // AGENT: lihat LocalTourAgent milik mereka (manage mode)
        if (auth()->check() && auth()->user()->role === \App\Models\User::ROLE_AGENT) {
            $userAgent = Agent::where('user_id', auth()->id())->first();
            if ($userAgent) {
                $query = LocalTourAgent::where('agent_id', $userAgent->id);
                if ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                }
                $localTourAgents = $query->with('tourPackages')->orderBy('created_at', 'desc')->get();
            } else {
                $localTourAgents = collect();
            }

            return view('wisatawan.pemanduWisata.index', compact('localTourAgents', 'keyword'));
        }

        // USER/GUEST: lihat catalog semua LocalTourAgent dari verified agents
        $query = LocalTourAgent::whereHas('agent', function($q) {
            $q->where('is_verified', true);
        });

        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('description', 'like', '%' . $keyword . '%');
            });
        }

        $localTourAgents = $query->with(['agent', 'tourPackages'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Untuk compatibility (jika ada logic yang butuh $agents)
        $agents = null;

        return view('wisatawan.pemanduWisata.index', compact('localTourAgents', 'agents', 'keyword'));
    }

    /**
     * Menampilkan detail satu LocalTourAgent + paket-paketnya
     */
    public function show(LocalTourAgent $localTourAgent)
    {
        // Pastikan agen verified jika di-browse user
        if (!auth()->check() || auth()->user()->role !== \App\Models\User::ROLE_AGENT) {
            // User/guest browsing - hanya lihat agen dari agent yang verified
            if (!$localTourAgent->agent || !$localTourAgent->agent->is_verified) {
                abort(404, 'Agen tour tidak ditemukan.');
            }
        } else {
            // Agent yang login - hanya lihat agen mereka sendiri (atau allow browse semua?)
            // Untuk sekarang, allow agent browse semua untuk preview
        }

        $tourPackages = $localTourAgent->tourPackages()->orderBy('created_at', 'desc')->get();
        $agent = $localTourAgent->agent;

        return view('wisatawan.pemanduWisata.detailAgen', compact('localTourAgent', 'agent', 'tourPackages'));
    }

    /**
     * Menampilkan semua paket dari satu LocalTourAgent
     */
    public function packages(LocalTourAgent $localTourAgent)
    {
        // Pastikan agen verified
        if (!$localTourAgent->agent || !$localTourAgent->agent->is_verified) {
            abort(404, 'Agen tour tidak ditemukan.');
        }

        $tourPackages = $localTourAgent->tourPackages()->orderBy('created_at', 'desc')->get();
        $agent = $localTourAgent->agent;

        $isOwner = false;
        if (auth()->check() && auth()->user()->role === \App\Models\User::ROLE_AGENT) {
            $userAgent = Agent::where('user_id', auth()->id())->first();
            $isOwner = $userAgent && $userAgent->id === $agent->id;
        }

        return view('wisatawan.pemanduWisata.packages', compact('localTourAgent', 'agent', 'tourPackages', 'isOwner'));
    }

    /**
     * Menampilkan detail satu paket perjalanan dari LocalTourAgent
     */
    public function packageDetail(LocalTourAgent $localTourAgent, TourPackage $tourPackage)
    {
        // Cek paket milik LocalTourAgent
        if ($tourPackage->local_tour_agent_id !== $localTourAgent->id) {
            abort(404, 'Paket perjalanan tidak ditemukan.');
        }

        // Pastikan agen verified
        if (!$localTourAgent->agent || !$localTourAgent->agent->is_verified) {
            abort(404, 'Agen tour tidak ditemukan.');
        }

        $agent = $localTourAgent->agent;

        $isOwner = false;
        if (auth()->check() && auth()->user()->role === \App\Models\User::ROLE_AGENT) {
            $userAgent = Agent::where('user_id', auth()->id())->first();
            $isOwner = $userAgent && $userAgent->id === $agent->id;
        }

        return view('wisatawan.pemanduWisata.package-detail', compact('localTourAgent', 'agent', 'tourPackage', 'isOwner'));
    }
}