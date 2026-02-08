<?php

namespace App\Http\Controllers;

use App\Models\TourPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminTourPackageController extends Controller
{
    /**
     * Display all tour packages grouped by agent
     */
    public function index(Request $request)
    {
        // Ambil agent ID dari query (untuk filter detail)
        $agentId = $request->query('agent');

        // Ambil semua paket dengan agent untuk statistik
        $allPackages = TourPackage::with('agent.user')->get();

        // Jika ada filter agent, tampilkan semua paket dari agent tersebut
        if ($agentId) {
            $packages = TourPackage::where('agent_id', $agentId)
                ->with('agent.user')
                ->orderBy('created_at', 'desc')
                ->paginate(12);

            return view('admin.tour_packages.index', compact('packages', 'allPackages', 'agentId'));
        }

        // Sebaliknya, group by agent dengan pagination
        // Pagination berdasarkan packages, bukan agents
        $packages = TourPackage::with('agent.user')
            ->orderBy('agent_id', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(6); // 6 packages per page

        return view('admin.tour_packages.index', compact('packages', 'allPackages'));
    }

    /**
     * Show edit form for a package
     */
    public function edit($id)
    {
        $package = TourPackage::findOrFail($id);
        return view('admin.tour_packages.edit', compact('package'));
    }

    /**
     * Update a package
     */
    public function update(Request $request, $id)
    {
        $package = TourPackage::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_person' => 'required|numeric|min:0',
            'duration' => 'nullable|string|max:100',
            'facilities' => 'nullable|string',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('cover_image_file')) {
            // Delete old image
            if ($package->cover_image_url) {
                Storage::disk('public')->delete($package->cover_image_url);
            }
            $validated['cover_image_url'] = $request->file('cover_image_file')->store('tour_packages', 'public');
        }

        $package->update($validated);

        return redirect()->route('admin.tour-packages.index')
            ->with('success', 'Paket perjalanan berhasil diperbarui!');
    }

    /**
     * Delete a package
     */
    public function destroy($id)
    {
        $package = TourPackage::findOrFail($id);

        // Delete image
        if ($package->cover_image_url) {
            Storage::disk('public')->delete($package->cover_image_url);
        }

        $package->delete();

        return redirect()->route('admin.tour-packages.index')
            ->with('success', 'Paket perjalanan berhasil dihapus!');
    }
}
?>