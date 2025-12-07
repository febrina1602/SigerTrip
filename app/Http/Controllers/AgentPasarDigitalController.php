<?php

namespace App\Http\Controllers;

use App\Models\RentalVehicle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AgentPasarDigitalController extends Controller
{
    /**
     * Pastikan yang akses adalah user dengan role AGENT.
     */
    protected function ensureAgent()
    {
        $user = Auth::user();

        if (!$user || $user->role !== User::ROLE_AGENT) {
            abort(403, 'Hanya mitra/agent yang boleh mengakses halaman ini.');
        }

        if (!$user->agent) {
            abort(403, 'Data agen tidak ditemukan untuk akun ini.');
        }

        return $user->agent;
    }

    /**
     * Halaman daftar kendaraan milik agent (dengan filter jenis).
     */
    public function index(Request $request)
    {
        $agent = $this->ensureAgent();

        $type  = $request->query('type'); // CAR / MOTORCYCLE / null
        
        // Menggunakan relasi untuk mengambil kendaraan milik agent ini saja
        $query = $agent->rentalVehicles()->orderBy('created_at', 'desc');

        if ($type) {
            $query->where('vehicle_type', $type);
        }

        // Tambahkan pagination agar halaman tidak berat
        $vehicles = $query->paginate(6)->withQueryString();

        return view('agent.pasar-digital.index', compact('vehicles', 'type'));
    }

    /**
     * Form tambah kendaraan.
     */
    public function create()
    {
        $agent = $this->ensureAgent();
        return view('agent.pasar-digital.create', compact('agent'));
    }

    /**
     * Simpan kendaraan baru.
     */
    public function store(Request $request)
    {
        $agent = $this->ensureAgent();

        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'vehicle_type'      => 'required|in:CAR,MOTORCYCLE',
            'price_per_day'     => 'required|numeric|min:0',
            'location'          => 'required|string|max:255',
            'description'       => 'nullable|string',

            'brand'             => 'nullable|string|max:100',
            'model'             => 'nullable|string|max:100',
            'year'              => 'nullable|integer|min:1990|max:' . (date('Y') + 1),
            'transmission'      => 'nullable|string|max:50',
            'seats'             => 'nullable|integer|min:1|max:20',
            'plate_number'      => 'nullable|string|max:50',
            'fuel_type'         => 'nullable|string|max:50',

            'include_driver'      => 'nullable|boolean',
            'include_fuel'        => 'nullable|boolean',
            'min_rental_days'     => 'nullable|integer|min:1|max:30',
            'include_pickup_drop' => 'nullable|boolean',
            'terms_conditions'    => 'nullable|string',

            'image'             => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('vehicles', 'public');
        }

        RentalVehicle::create([
            'agent_id'           => $agent->id,
            'name'               => $data['name'],
            'vehicle_type'       => $data['vehicle_type'],
            'price_per_day'      => $data['price_per_day'],
            'location'           => $data['location'],
            'description'        => $data['description'] ?? null,
            'image_url'          => $imagePath,

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

        return redirect()->route('agent.pasar.index')
            ->with('success', 'Kendaraan berhasil didaftarkan.');
    }

    /**
     * Form edit kendaraan.
     */
    public function edit(RentalVehicle $vehicle)
    {
        $agent = $this->ensureAgent();

        // Pastikan kendaraan milik agent yang sedang login
        if ($vehicle->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('agent.pasar-digital.edit', compact('vehicle'));
    }

    /**
     * Update kendaraan.
     */
    public function update(Request $request, RentalVehicle $vehicle)
    {
        $agent = $this->ensureAgent();

        if ($vehicle->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'vehicle_type'      => 'required|in:CAR,MOTORCYCLE',
            'price_per_day'     => 'required|numeric|min:0',
            'location'          => 'required|string|max:255',
            'description'       => 'nullable|string',

            'brand'             => 'nullable|string|max:100',
            'model'             => 'nullable|string|max:100',
            'year'              => 'nullable|integer|min:1990|max:' . (date('Y') + 1),
            'transmission'      => 'nullable|string|max:50',
            'seats'             => 'nullable|integer|min:1|max:20',
            'plate_number'      => 'nullable|string|max:50',
            'fuel_type'         => 'nullable|string|max:50',

            'include_driver'      => 'nullable|boolean',
            'include_fuel'        => 'nullable|boolean',
            'min_rental_days'     => 'nullable|integer|min:1|max:30',
            'include_pickup_drop' => 'nullable|boolean',
            'terms_conditions'    => 'nullable|string',

            'image'             => 'nullable|image|max:2048',
        ]);

        // Siapkan array data update
        $updateData = [
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
        ];

        // Handle gambar jika ada upload baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($vehicle->image_url) {
                Storage::disk('public')->delete($vehicle->image_url);
            }
            // Simpan gambar baru & masukkan ke array update
            $updateData['image_url'] = $request->file('image')->store('vehicles', 'public');
        }

        // Lakukan update
        $vehicle->update($updateData);

        return redirect()->route('agent.pasar.index')
            ->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    /**
     * Hapus kendaraan.
     */
    public function destroy(RentalVehicle $vehicle)
    {
        $agent = $this->ensureAgent();

        if ($vehicle->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        // Hapus file gambar jika ada
        if ($vehicle->image_url) {
            Storage::disk('public')->delete($vehicle->image_url);
        }

        $vehicle->delete();

        return redirect()->route('agent.pasar.index')
            ->with('success', 'Kendaraan berhasil dihapus.');
    }
}