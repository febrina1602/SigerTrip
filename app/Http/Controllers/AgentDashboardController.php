<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\LocalTourAgent;
use App\Models\TourPackage;

class AgentDashboardController extends Controller
{
    /**
     * Tampilkan dashboard khusus untuk agent
     */
    public function index()
    {
        $user = auth()->user();
        
        // Validasi bahwa user adalah agent
        if ($user->role !== 'agent') {
            abort(403, 'Unauthorized');
        }

        // Ambil data agent berdasarkan user_id
        $agent = Agent::where('user_id', $user->id)->first();
        
        // Jika agent tidak ditemukan, create otomatis
        if (!$agent) {
            $agent = Agent::create([
                'user_id' => $user->id,
                'name' => $user->full_name,
                'agent_type' => 'LOCAL_TOUR',
                'contact_phone' => $user->phone_number ?? '',
                'is_verified' => false,
            ]);
        }

        // Ambil semua agen tour lokal milik agent ini
        $localTourAgents = LocalTourAgent::where('agent_id', $agent->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung total agen tour lokal
        $totalLocalTourAgents = $localTourAgents->count();

        // Ambil semua paket perjalanan dari agen tour lokal milik agent ini
        $tourPackages = TourPackage::whereIn('local_tour_agent_id', $localTourAgents->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung total paket perjalanan
        $totalTourPackages = $tourPackages->count();

        // Hitung total destinasi yang dikunjungi (unique)
        $totalDestinations = count(
            collect($tourPackages)
                ->flatMap(function ($package) {
                    return $package->destinations_visited ?? [];
                })
                ->unique()
        );

        // Rating rata-rata agen
        $averageRating = $localTourAgents->avg('rating') ?? 0;

        // Data untuk grafik (paket terbaru bulan ini)
        $packagesThisMonth = TourPackage::whereIn('local_tour_agent_id', $localTourAgents->pluck('id'))
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Agen tour lokal terbaru (untuk ditampilkan)
        $recentLocalTourAgents = $localTourAgents->take(3);

        // Paket terbaru (untuk ditampilkan)
        $recentTourPackages = $tourPackages->take(5);

        return view('agent.dashboard', compact(
            'agent',
            'localTourAgents',
            'totalLocalTourAgents',
            'tourPackages',
            'totalTourPackages',
            'totalDestinations',
            'averageRating',
            'packagesThisMonth',
            'recentLocalTourAgents',
            'recentTourPackages',
            'user'
        ));
    }

    /**
     * Store a new LocalTourAgent (called from modal via AJAX)
     */
    public function storeLocalTourAgent(Request $request)
    {
        $user = auth()->user();
        if (!$user || $user->role !== \App\Models\User::ROLE_AGENT) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $agent = Agent::where('user_id', $user->id)->first();
        if (!$agent) {
            return response()->json(['error' => 'Agent profile not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'contact_phone' => 'nullable|string',
        ]);

        $local = LocalTourAgent::create(array_merge($validated, ['agent_id' => $agent->id]));

        return response()->json(['success' => true, 'local' => $local]);
    }

    /**
     * Delete a tour package (agent owner only)
     */
    public function deleteTourPackage(\Illuminate\Http\Request $request, \App\Models\TourPackage $tourPackage)
    {
        $user = auth()->user();
        if (!$user || $user->role !== \App\Models\User::ROLE_AGENT) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        // Check ownership: tourPackage.agent_id must match agent record for this user
        $agent = Agent::where('user_id', $user->id)->first();
        if (!$agent || $tourPackage->agent_id !== $agent->id) {
            return redirect()->back()->with('error', 'Anda tidak berwenang menghapus paket ini.');
        }

        $tourPackage->delete();
        return redirect()->back()->with('success', 'Paket perjalanan berhasil dihapus.');
    }

    /**
     * Update a tour package (agent owner only) via AJAX
     */
    public function updateTourPackage(\Illuminate\Http\Request $request, \App\Models\TourPackage $tourPackage)
    {
        $user = auth()->user();
        if (!$user || $user->role !== \App\Models\User::ROLE_AGENT) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $agent = Agent::where('user_id', $user->id)->first();
        if (!$agent || $tourPackage->agent_id !== $agent->id) {
            return response()->json(['error' => 'Not owner'], 403);
        }

        $validated = $request->validate([
            'description' => 'nullable|string',
            'facilities' => 'nullable|string',
            'price_per_person' => 'nullable|numeric',
            'duration' => 'nullable|string',
        ]);

        $tourPackage->fill($validated);
        $tourPackage->save();

        return response()->json(['success' => true]);
    }
}
