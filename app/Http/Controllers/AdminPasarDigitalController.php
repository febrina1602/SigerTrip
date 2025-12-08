<?php

namespace App\Http\Controllers;

use App\Models\RentalVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPasarDigitalController extends Controller
{
    /**
     * Display all vehicles grouped by agent
     */
    public function index(Request $request)
    {
        // Ambil agent ID dari query (untuk filter)
        $agentId = $request->query('agent');
        $type = $request->query('type');

        // Ambil semua kendaraan untuk statistik
        $allVehicles = RentalVehicle::with('agent.user')->get();

        // Apply type filter
        if ($type) {
            $allVehicles = $allVehicles->where('vehicle_type', $type);
        }

        // Jika ada filter agent, tampilkan semua kendaraan dari agent tersebut
        if ($agentId) {
            $vehicles = RentalVehicle::where('agent_id', $agentId)
                ->with('agent.user')
                ->orderBy('created_at', 'desc')
                ->paginate(12);

            return view('admin.pasar-digital.index', compact('vehicles', 'allVehicles', 'agentId', 'type'));
        }

        // Sebaliknya, group by agent dengan pagination
        // Pagination berdasarkan vehicles, bukan agents
        $vehicles = RentalVehicle::with('agent.user')
            ->orderBy('agent_id', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(6); // 6 vehicles per page

        return view('admin.pasar-digital.index', compact('vehicles', 'allVehicles', 'type'));
    }

    /**
     * Show edit form for a vehicle
     */
    public function edit($id)
    {
        $vehicle = RentalVehicle::findOrFail($id);
        return view('admin.pasar-digital.edit', compact('vehicle'));
    }

    /**
     * Update a vehicle
     */
    public function update(Request $request, $id)
    {
        $vehicle = RentalVehicle::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'vehicle_type' => 'required|in:CAR,MOTORCYCLE',
            'price_per_day' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:1990|max:' . (date('Y') + 1),
            'transmission' => 'nullable|string|max:50',
            'seats' => 'nullable|integer|min:1|max:20',
            'plate_number' => 'nullable|string|max:50',
            'fuel_type' => 'nullable|string|max:50',
            'include_driver' => 'nullable|boolean',
            'include_fuel' => 'nullable|boolean',
            'min_rental_days' => 'nullable|integer|min:1|max:30',
            'include_pickup_drop' => 'nullable|boolean',
            'terms_conditions' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image
        if ($request->hasFile('image')) {
            if ($vehicle->image_url) {
                Storage::disk('public')->delete($vehicle->image_url);
            }
            $validated['image_url'] = $request->file('image')->store('vehicles', 'public');
        }

        $vehicle->update($validated);

        return redirect()->route('admin.pasar.index')
            ->with('success', 'Kendaraan berhasil diperbarui!');
    }

    /**
     * Delete a vehicle
     */
    public function destroy($id)
    {
        $vehicle = RentalVehicle::findOrFail($id);

        // Delete image
        if ($vehicle->image_url) {
            Storage::disk('public')->delete($vehicle->image_url);
        }

        $vehicle->delete();

        return redirect()->route('admin.pasar.index')
            ->with('success', 'Kendaraan berhasil dihapus!');
    }
}
?>