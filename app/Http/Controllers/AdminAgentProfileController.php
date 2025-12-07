<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminAgentProfileController extends Controller
{
    /**
     * Tampilkan list profil agent dengan status verifikasi
     */
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'semua');

        // Ambil semua agent + user
        $agents = Agent::with('user')
            ->get()
            ->map(function ($agent) {
                $agent->is_profile_complete = 
                    !empty($agent->name) && 
                    !empty($agent->address) &&
                    !empty($agent->contact_phone) &&
                    !empty($agent->description ?? null);

                return $agent;
            });

        // ---- FILTERING ----
        if ($filter === 'terverifikasi') {
            $agents = $agents->where('is_verified', true);
        }

        if ($filter === 'menunggu') {
            $agents = $agents->filter(function ($agent) {
                return !$agent->is_verified && $agent->is_profile_complete;
            });
        }

        if ($filter === 'belum') {
            $agents = $agents->where('is_profile_complete', false);
        }

        // Statistik
        $totalAgents = Agent::count();
        $verifiedAgents = Agent::where('is_verified', true)->count();
        $pendingAgents = $agents->filter(function ($agent) {
            return !$agent->is_verified && $agent->is_profile_complete;
        })->count();
        $incompleteAgents = Agent::all()->filter(function ($a) {
            return empty($a->name) || empty($a->address) || empty($a->contact_phone) || empty($a->description);
        })->count();

        return view('admin.profil-agent.index', compact(
            'agents',
            'totalAgents',
            'verifiedAgents',
            'pendingAgents',
            'incompleteAgents'
        ));
    }


    /**
     * Tampilkan detail profil agent
     */
    public function show($id)
    {
        // Gunakan findOrFail dengan eager loading
        $agent = Agent::with('user')->findOrFail($id);
        
        // Cek apakah user ada
        if (!$agent->user) {
            return redirect()->route('admin.profil-agent.index')
                ->with('error', 'Agent tidak memiliki user yang terkait.');
        }
        
        // Check if profile is complete
        $agent->is_profile_complete = !empty($agent->name) && 
                                    !empty($agent->address) && 
                                    !empty($agent->contact_phone);

        return view('admin.profil-agent.show', compact('agent'));
    }

    /**
     * Verifikasi profil agent
     */
    public function verify(Request $request, Agent $agent)
    {
        // Cek apakah profile lengkap
        $isComplete = !empty($agent->name) && 
                      !empty($agent->address) && 
                      !empty($agent->contact_phone);

        if (!$isComplete) {
            return redirect()->back()
                ->with('error', 'Profil agent tidak lengkap. Pastikan nama, alamat, dan kontak terisi.');
        }

        $agent->update([
            'is_verified' => true,
        ]);

        return redirect()->back()
            ->with('success', 'Profil agent berhasil diverifikasi!');
    }

    /**
     * Reject/Batalkan verifikasi profil
     */
    public function reject(Request $request, Agent $agent)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        $agent->update([
            'is_verified' => false,
        ]);

        return redirect()->back()
            ->with('success', 'Verifikasi profil ditolak. Agent akan diminta melengkapi kembali.');
    }

    /**
     * Reset profil agent
     */
    public function reset(Agent $agent)
    {
        $agent->update([
            'is_verified' => false,
        ]);

        return redirect()->back()
            ->with('success', 'Status verifikasi profil direset.');
    }

    /**
     * Hapus agent
     */
    public function destroy(Agent $agent)
    {
        // Hapus banner image jika ada
        if ($agent->banner_image_url) {
            Storage::disk('public')->delete($agent->banner_image_url);
        }

        // Hapus agent
        $agent->delete();

        return redirect()->route('admin.profil-agent.index')
            ->with('success', 'Agent berhasil dihapus.');
    }
}