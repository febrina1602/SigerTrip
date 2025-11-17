<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Destination;
use App\Models\DestinationCategory;
use App\Models\User;

class AdminDestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::latest()->get();
        $totalWisata = Destination::count();
        $totalKategori = DestinationCategory::count();
        $totalUser = User::count();

        return view('admin.beranda', compact('destinations', 'totalWisata', 'totalKategori', 'totalUser'));
    }

    public function create()
    {
        // ambil kategori dari DB (lebih baik daripada hardcode)
        $categories = DestinationCategory::all();
        return view('admin.wisata.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'description' => 'required|string',
            'facilities' => 'nullable|string',
            'price_per_person' => 'required|numeric',
            'parking_price' => 'nullable|numeric',
            'category_id' => 'required|exists:destination_categories,id',
            'popular_activities' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'rating' => 'nullable|numeric|min:0|max:5',
            'is_featured' => 'nullable|boolean',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Simpan gambar ke disk 'public' dan dapatkan URL publik
        $imagePath = $request->file('image')->store('destinations', 'public');
        $imageUrl = Storage::url($imagePath); // jadi /storage/destinations/...

        // Simpan data destinasi
        Destination::create([
            'name' => $request->name,
            'address' => $request->address,
            'description' => $request->description,
            'facilities' => $request->facilities,
            'price_per_person' => $request->price_per_person,
            'parking_price' => $request->parking_price ?? 0,
            'category_id' => $request->category_id,
            'popular_activities' => $request->popular_activities,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'rating' => $request->rating ?? 0,
            'is_featured' => $request->has('is_featured') ? 1 : 0,
            'image_url' => $imageUrl,
        ]);

        return redirect()->route('admin.beranda')->with('success', 'Destinasi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $destination = Destination::findOrFail($id);
        $categories = DestinationCategory::all();
        
        return view('admin.wisata.edit', compact('destination', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $destination = Destination::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'description' => 'required|string',
            'facilities' => 'nullable|string',
            'price_per_person' => 'required|numeric',
            'parking_price' => 'nullable|numeric',
            'category_id' => 'required|exists:destination_categories,id',
            'popular_activities' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'rating' => 'nullable|numeric|min:0|max:5',
            'is_featured' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update data
        $dataToUpdate = [
            'name' => $request->name,
            'address' => $request->address,
            'description' => $request->description,
            'facilities' => $request->facilities,
            'price_per_person' => $request->price_per_person,
            'parking_price' => $request->parking_price ?? 0,
            'category_id' => $request->category_id,
            'popular_activities' => $request->popular_activities,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'rating' => $request->rating ?? 0,
            'is_featured' => $request->has('is_featured') ? 1 : 0,
        ];

        // Jika ada upload gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($destination->image_url) {
                $oldImagePath = str_replace('/storage/', '', $destination->image_url);
                Storage::disk('public')->delete($oldImagePath);
            }

            // Upload gambar baru
            $imagePath = $request->file('image')->store('destinations', 'public');
            $dataToUpdate['image_url'] = Storage::url($imagePath);
        }

        $destination->update($dataToUpdate);

        return redirect()->route('admin.beranda')->with('success', 'Destinasi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $destination = Destination::findOrFail($id);
        
        // Hapus gambar dari storage
        if ($destination->image_url) {
            $imagePath = str_replace('/storage/', '', $destination->image_url);
            Storage::disk('public')->delete($imagePath);
        }
        
        $destination->delete();
        
        return redirect()->route('admin.beranda')->with('success', 'Destinasi berhasil dihapus!');
    }
}