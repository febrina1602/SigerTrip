<?php

namespace App\Http\Controllers;

use App\Models\RentalVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminPasarDigitalController extends Controller
{
    /**
     * Halaman daftar semua kendaraan (admin view)
     */
    public function index(Request $request)
    {
        $type = $request->query('type'); // CAR / MOTORCYCLE / null
        
        $allVehiclesQuery = RentalVehicle::with('agent');
        
        $query = RentalVehicle::with('agent')
            ->orderBy('agent_id', 'asc')
            ->orderBy('created_at', 'desc');

        if ($type) {
            $query->where('vehicle_type', $type);
            $allVehiclesQuery->where('vehicle_type', $type);
        }

        $allVehicles = $allVehiclesQuery->get();

        $allGrouped = $query->get()->groupBy('agent_id');
        
        $perPage = 6;
        $page = $request->get('page', 1);
        $total = count($allGrouped);
        
        $paginatedAgents = $allGrouped->slice(($page - 1) * $perPage, $perPage);
        
        $vehicles = new LengthAwarePaginator(
            $paginatedAgents->values()->all(),
            $total,
            $perPage,
            $page,
            [
                'path'  => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('admin.pasar-digital.index', compact('vehicles', 'allVehicles', 'type'));
    }

    /**
     * Form edit kendaraan (admin)
     */
    public function edit(RentalVehicle $vehicle)
    {
        return view('admin.pasar-digital.edit', compact('vehicle'));
    }

    /**
     * Update kendaraan (admin)
     */
    public function update(Request $request, RentalVehicle $vehicle)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'vehicle_type'     => 'required|in:CAR,MOTORCYCLE',
            'price_per_day'    => 'required|numeric|min:0',
            'location'         => 'required|string|max:255',
            'description'      => 'nullable|string',

            'brand'            => 'nullable|string|max:100',
            'model'            => 'nullable|string|max:100',
            'year'             => 'nullable|integer|min:1990|max:' . (date('Y') + 1),
            'transmission'     => 'nullable|string|max:50',
            'seats'            => 'nullable|integer|min:1|max:20',
            'plate_number'     => 'nullable|string|max:50',
            'fuel_type'        => 'nullable|string|max:50',

            'include_driver'      => 'nullable|boolean',
            'include_fuel'        => 'nullable|boolean',
            'min_rental_days'     => 'nullable|integer|min:1|max:30',
            'include_pickup_drop' => 'nullable|boolean',
            'terms_conditions'    => 'nullable|string',

            'image'            => 'nullable|image|max:2048',
        ]);

        // Gambar baru
        if ($request->hasFile('image')) {
            if ($vehicle->image_url) {
                Storage::disk('public')->delete($vehicle->image_url);
            }
            $vehicle->image_url = $request->file('image')->store('vehicles', 'public');
        }

        $vehicle->update([
            'name'               => $data['name'],
            'vehicle_type'       => $data['vehicle_type'],
            'price_per_day'      => $data['price_per_day'],
            'location'           => $data['location'],
            'description'        => $data['description'] ?? null,
            'brand'              => $data['brand'] ?? null,
            'model'              => $data['model'] ?? null,
            'year'               => $data['year'] ?? null,
            'transmission'       => $data['transmission'] ?? null,
            'seats'              => $data['seats'] ?? null,
            'plate_number'       => $data['plate_number'] ?? null,
            'fuel_type'          => $data['fuel_type'] ?? null,

            'include_driver'     => $request->boolean('include_driver'),
            'include_fuel'       => $request->boolean('include_fuel'),
            'min_rental_days'    => $data['min_rental_days'] ?? 1,
            'include_pickup_drop'=> $request->boolean('include_pickup_drop'),
            'terms_conditions'   => $data['terms_conditions'] ?? null,
        ]);

        return redirect()->route('admin.pasar')
            ->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    /**
     * Hapus kendaraan (admin)
     */
    public function destroy(RentalVehicle $vehicle)
    {
        if ($vehicle->image_url) {
            Storage::disk('public')->delete($vehicle->image_url);
        }

        $vehicle->delete();

        return redirect()->route('admin.pasar')
            ->with('success', 'Kendaraan berhasil dihapus.');
    }
}
