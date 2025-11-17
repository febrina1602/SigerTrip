<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\TourPackage;
use Illuminate\Http\Request;

class PemanduWisataController extends Controller
{
    /**
     * Menampilkan semua agen yang terverifikasi
     */
    public function index(Request $request)
    {
        $keyword = $request->get('q');
        
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

        return view('wisatawan.pemanduWisata.packages', compact('agent', 'tourPackages'));
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

        return view('wisatawan.pemanduWisata.package-detail', compact('agent', 'tourPackage'));
    }
}