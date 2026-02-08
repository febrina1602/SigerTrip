<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\TourPackage;
use Illuminate\Http\Request;

class PemanduWisataController extends Controller
{
    /**
     * Tampilkan daftar Agen Wisata (Induk)
     */
    public function index(Request $request)
    {
        $keyword = $request->get('q');

        // Query ke model AGENT (bukan LocalTourAgent)
        // Hanya ambil agent yang sudah diverifikasi oleh admin
        $query = Agent::where('is_verified', true);

        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('description', 'like', '%' . $keyword . '%')
                  ->orWhere('location', 'like', '%' . $keyword . '%');
            });
        }

        // Opsional: Hanya tampilkan agent yang sudah punya paket wisata
        // $query->has('tourPackages');

        $agents = $query->with('tourPackages')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('wisatawan.pemanduWisata.index', compact('agents', 'keyword'));
    }

    /**
     * Detail Profil Agen
     */
    public function show(Agent $agent)
    {
        // Pastikan agent verified
        if (!$agent->is_verified) {
            abort(404, 'Agen belum diverifikasi.');
        }

        $tourPackages = $agent->tourPackages()->orderBy('created_at', 'desc')->get();

        // Variable $localTourAgent kita ganti logicnya jadi $agent itu sendiri untuk view
        return view('wisatawan.pemanduWisata.detailAgen', compact('agent', 'tourPackages'));
    }

    /**
     * Daftar Paket milik Agen
     */
    public function packages(Agent $agent)
    {
        // Pastikan agen verified
        if (!$agent->is_verified) {
            abort(404, 'Agen tour tidak ditemukan atau belum diverifikasi.');
        }

        $tourPackages = $agent->tourPackages()
            ->where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $isOwner = false;
        if (auth()->check() && auth()->user()->role === \App\Models\User::ROLE_AGENT) {
            $userAgent = auth()->user()->agent;
            $isOwner = $userAgent && $userAgent->id === $agent->id;
        }

        return view('wisatawan.pemanduWisata.packages', compact('agent', 'tourPackages', 'isOwner'));
    }

    /**
     * Detail Paket
     */
    public function packageDetail(Agent $agent, TourPackage $tourPackage)
    {
        // Validasi paket milik agen ini
        if ($tourPackage->agent_id !== $agent->id) {
            abort(404);
        }

        $isOwner = false;
        if (auth()->check() && auth()->user()->role === \App\Models\User::ROLE_AGENT) {
            $user = auth()->user();
            if ($user->agent && $user->agent->id === $agent->id) {
                $isOwner = true;
            }
        }

        return view('wisatawan.pemanduWisata.package-detail', compact('agent', 'tourPackage', 'isOwner'));
    }
}