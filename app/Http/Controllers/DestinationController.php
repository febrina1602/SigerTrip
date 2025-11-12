<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\DestinationCategory;
use Illuminate\Http\Request;

class DestinationController extends Controller
{

    /**
     * Menampilkan destinasi berdasarkan kategori
     * 
     * @param string $categorySlug - Slug atau ID kategori
     * @return \Illuminate\View\View
     */
    public function byCategory($categorySlug)
    {
        // Cari kategori berdasarkan ID atau nama
        $category = DestinationCategory::where('id', $categorySlug)
            ->orWhere('name', 'like', '%' . $categorySlug . '%')
            ->first();
        
        // Jika kategori tidak ditemukan, redirect ke beranda
        if (!$category) {
            return redirect()->route('beranda.wisatawan')
                ->with('error', 'Kategori tidak ditemukan.');
        }
        
        // Ambil semua kategori untuk navigasi
        $categories = DestinationCategory::all();
        
        // Ambil destinasi berdasarkan kategori
        $destinations = Destination::where('category_id', $category->id)
            ->orderBy('rating', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('wisatawan.destinations.category', compact('category', 'categories', 'destinations'));
    }
    
    /**
     * Menampilkan detail destinasi
     * 
     * @param int $id - ID destinasi
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Cari destinasi berdasarkan ID
        $destination = Destination::with('category')->findOrFail($id);
        
        // Ambil destinasi lain dari kategori yang sama untuk rekomendasi
        $relatedDestinations = Destination::where('category_id', $destination->category_id)
            ->where('id', '!=', $destination->id)
            ->limit(3)
            ->get();
        
        return view('wisatawan.destinations.detail', compact('destination', 'relatedDestinations'));
    }
}
