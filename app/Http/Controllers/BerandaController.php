<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\DestinationCategory;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    // Beranda untuk wisatawan
    public function wisatawan()
    {
        $categories = DestinationCategory::all();
        
        // Ambil destinasi yang featured (untuk rekomendasi)
        $recommendations = Destination::where('is_featured', true)
            ->orderBy('rating', 'desc')
            ->get();
        
        // Jika belum ada yang featured, ambil 6 destinasi terbaru
        if ($recommendations->isEmpty()) {
            $recommendations = Destination::orderBy('created_at', 'desc')
                ->limit(6)
                ->get();
        }
        
        return view('beranda.wisatawan', compact('categories', 'recommendations'));
    }
}
