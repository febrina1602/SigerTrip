<?php

namespace App\Http\Controllers;

use App\Models\RentalVehicle;
use Illuminate\Http\Request;

class PasarDigitalController extends Controller
{
    /**
     * Menampilkan halaman utama Pasar Digital (daftar kendaraan).
     */
    public function index(Request $request)
    {
        $query = RentalVehicle::query();

        // Hanya tampilkan kendaraan dari agen yang sudah diverifikasi
        // Mirip dengan PemanduWisataController
        $query->whereHas('agent', function ($q) {
            $q->where('is_verified', true);
        });

        // 1. Filter berdasarkan Tipe Kendaraan (dari gambar UI Anda)
        $vehicleType = $request->get('type'); // cth: ?type=CAR
        if ($vehicleType && in_array($vehicleType, ['CAR', 'MOTORCYCLE'])) {
            $query->where('vehicle_type', $vehicleType);
        }

        // 2. Filter berdasarkan Pencarian
        $search = $request->get('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('location', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        
        $vehicles = $query->latest()->paginate(12)->withQueryString();

        return view('wisatawan.pasarDigital.index', [
            'vehicles' => $vehicles,
            'currentType' => $vehicleType, // Untuk menandai tombol aktif
            'search' => $search,
        ]);
    }

    /**
     * Menampilkan halaman detail satu kendaraan.
     */
    public function show(RentalVehicle $vehicle)
    {
        // Pastikan agen dari kendaraan ini terverifikasi sebelum ditampilkan
        if (!$vehicle->agent || !$vehicle->agent->is_verified) {
            abort(404, 'Kendaraan tidak ditemukan.');
        }

        // Ambil kendaraan lain dari agen yang sama (jika ada)
        $otherVehicles = RentalVehicle::where('agent_id', $vehicle->agent_id)
            ->where('id', '!=', $vehicle->id)
            ->whereHas('agent', fn($q) => $q->where('is_verified', true))
            ->take(3)
            ->get();

        return view('wisatawan.pasarDigital.detail', [
            'vehicle' => $vehicle,
            'otherVehicles' => $otherVehicles
        ]);
    }
}