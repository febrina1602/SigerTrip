<?php

namespace App\Http\Controllers;

use App\Models\TourPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TourPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Cek apakah user adalah agent
        if ($user->isAgent()) {
            // Pastikan profil agent sudah ada
            if (!$user->agent) {
                return redirect()->route('agent.profile.edit')
                    ->with('warning', 'Harap lengkapi profil agensi Anda terlebih dahulu.');
            }
            
            // Agent: Tampilkan paket miliknya saja
            $packages = TourPackage::where('agent_id', $user->agent->id)
                ->orderBy('created_at', 'desc')
                ->paginate(12);

            // PERBAIKAN: Gunakan 'agent.tour_packages.index' (underscore)
            return view('agent.tour_packages.index', compact('packages'));
        } 
        
        // Fallback untuk non-agent (jika route ini diakses admin/user)
        abort(403, 'Unauthorized access.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // PERBAIKAN: Gunakan 'agent.tour_packages.create'
        return view('agent.tour_packages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_person' => 'required|numeric|min:0',
            'duration' => 'nullable|string|max:100',
            'facilities' => 'nullable|string',
            'minimum_participants' => 'nullable|integer|min:1',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user = Auth::user();
        
        $imageUrl = null;
        if ($request->hasFile('cover_image_file')) {
            $imageUrl = $request->file('cover_image_file')->store('tour_packages', 'public');
        }

        TourPackage::create([
            'agent_id' => $user->agent->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? '',
            'price_per_person' => $validated['price_per_person'],
            'duration' => $validated['duration'],
            'facilities' => $validated['facilities'],
            'minimum_participants' => $validated['minimum_participants'] ?? 1,
            'cover_image_url' => $imageUrl,
            // Field opsional lain bisa ditambahkan defaultnya
            'duration_days' => 1,
            'duration_nights' => 0,
        ]);

        return redirect()->route('agent.tour-packages.index')
            ->with('success', 'Paket perjalanan berhasil dibuat!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $package = TourPackage::findOrFail($id);
        $user = Auth::user();

        // Pastikan milik agent yang sedang login
        if ($package->agent_id !== $user->agent->id) {
            abort(403);
        }

        // PERBAIKAN: Gunakan 'agent.tour_packages.edit'
        return view('agent.tour_packages.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $package = TourPackage::findOrFail($id);
        $user = Auth::user();

        if ($package->agent_id !== $user->agent->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_person' => 'required|numeric|min:0',
            'duration' => 'nullable|string|max:100',
            'facilities' => 'nullable|string',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('cover_image_file')) {
            if ($package->cover_image_url) {
                Storage::disk('public')->delete($package->cover_image_url);
            }
            $package->cover_image_url = $request->file('cover_image_file')->store('tour_packages', 'public');
        }

        $package->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price_per_person' => $validated['price_per_person'],
            'duration' => $validated['duration'],
            'facilities' => $validated['facilities'],
        ]);

        return redirect()->route('agent.tour-packages.index')
            ->with('success', 'Paket perjalanan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $package = TourPackage::findOrFail($id);
        $user = Auth::user();

        if ($package->agent_id !== $user->agent->id) {
            abort(403);
        }

        if ($package->cover_image_url) {
            Storage::disk('public')->delete($package->cover_image_url);
        }

        $package->delete();

        return redirect()->route('agent.tour-packages.index')
            ->with('success', 'Paket perjalanan berhasil dihapus!');
    }
}