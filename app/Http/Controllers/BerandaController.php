<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DestinationCategory;
use App\Models\Destination;
use App\Models\TourPackage;
use App\Models\RentalVehicle;

class BerandaController extends Controller
{
    public function wisatawan()
    {
        $categories = DestinationCategory::all();

        // Destinasi unggulan
        $recommendations = Destination::where('is_featured', true)
            ->orderBy('rating', 'desc')
            ->take(6)
            ->get();

        // Paket wisata populer
        $tourPackages = TourPackage::orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Kendaraan sewa terbaru
        $rentalVehicles = RentalVehicle::orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('beranda.wisatawan', compact(
            'categories',
            'recommendations',
            'tourPackages',
            'rentalVehicles'
        ));
    }
}
