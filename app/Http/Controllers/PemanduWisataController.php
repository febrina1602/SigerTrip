<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\TourPackage;
use Illuminate\Http\Request;

class PemanduWisataController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('q');
        
        
        $query = Agent::where('is_verified', true)
            ->where('agent_type', 'LOCAL_TOUR');
        
        
        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('address', 'like', '%' . $keyword . '%');
            });
        }
        
        
        $agents = $query->orderBy('created_at', 'desc')->get();
        
        return view('wisatawan.pemanduWisata.index', compact('agents', 'keyword'));
    }

    public function show(Agent $agent)
    {
        // Pastikan agent sudah diverifikasi dan bertipe LOCAL_TOUR
        if (!$agent->is_verified || $agent->agent_type !== 'LOCAL_TOUR') {
            abort(404, 'Agen tour tidak ditemukan.');
        }

        // Ambil semua tour packages dari agen ini
        $tourPackages = $agent->tourPackages()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('wisatawan.pemanduWisata.show', compact('agent', 'tourPackages'));
    }
    public function packages(Agent $agent)
    {
        // Pastikan agent sudah diverifikasi dan bertipe LOCAL_TOUR
        if (!$agent->is_verified || $agent->agent_type !== 'LOCAL_TOUR') {
            abort(404, 'Agen tour tidak ditemukan.');
        }

        
        $tourPackages = $agent->tourPackages()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('wisatawan.pemanduWisata.packages', compact('agent', 'tourPackages'));
    }

    public function packageDetail(Agent $agent, TourPackage $tourPackage)
    {
        
        if ($tourPackage->agent_id !== $agent->id) {
            abort(404, 'Paket perjalanan tidak ditemukan.');
        }

        // Pastikan agent sudah diverifikasi dan bertipe LOCAL_TOUR
        if (!$agent->is_verified || $agent->agent_type !== 'LOCAL_TOUR') {
            abort(404, 'Agen tour tidak ditemukan.');
        }

        return view('wisatawan.pemanduWisata.package-detail', compact('agent', 'tourPackage'));
    }
}

