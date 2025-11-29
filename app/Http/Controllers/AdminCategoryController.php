<?php

namespace App\Http\Controllers;

use App\Models\DestinationCategory;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminCategoryController extends Controller
{
    /**
     * Tampilkan daftar kategori
     */
    public function index()
    {
        $categories = DestinationCategory::withCount([
            'destinations' => function($query) {
            }
        ])
        ->orderBy('name', 'asc')
        ->get();
        
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Tampilkan form tambah kategori
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Simpan kategori baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:destination_categories,name',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'icon_url' => null,
        ];

        // Upload icon jika ada
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('categories', 'public');
            $data['icon_url'] = Storage::url($iconPath);
        }

        DestinationCategory::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit kategori
     */
    public function edit($id)
    {
        $category = DestinationCategory::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update kategori
     */
    public function update(Request $request, $id)
    {
        $category = DestinationCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:destination_categories,name,' . $id,
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
        ]);

        $data = [
            'name' => $request->name,
        ];

        // Upload icon baru jika ada
        if ($request->hasFile('icon')) {
            // Hapus icon lama
            if ($category->icon_url) {
                $oldPath = str_replace('/storage/', '', $category->icon_url);
                Storage::disk('public')->delete($oldPath);
            }

            $iconPath = $request->file('icon')->store('categories', 'public');
            $data['icon_url'] = Storage::url($iconPath);
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Hapus kategori
     */
    public function destroy($id)
    {
        $category = DestinationCategory::findOrFail($id);

        // Cek apakah kategori masih memiliki destinasi
        if ($category->destinations()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih memiliki ' . $category->destinations()->count() . ' destinasi!');
        }

        // Hapus icon jika ada
        if ($category->icon_url) {
            $path = str_replace('/storage/', '', $category->icon_url);
            Storage::disk('public')->delete($path);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }

    /**
     * Lihat destinasi dalam kategori
     */
    public function show($id)
    {
        $category = DestinationCategory::findOrFail($id);
        $destinations = Destination::where('category_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('admin.categories.show', compact('category', 'destinations'));
    }
}