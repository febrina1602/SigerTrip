<?php

namespace App\Http\Controllers;

use App\Models\TourPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TourPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $isAgent = $user && $user->isAgent();
        
        if ($isAgent) {
            // If agent doesn't have agent profile yet, redirect to setup
            if (!$user->agent) {
                return redirect('/agent/profile')->with('warning', 'Harap lengkapi profil agensi Anda terlebih dahulu.');
            }
            
            // Agent: Show only their packages
            $packages = TourPackage::where('agent_id', $user->agent->id)->paginate(12);
            return view('agent.tour-packages.index', compact('packages', 'isAgent'));
        } else {
            // User: Show all published packages from all agents
            $packages = TourPackage::where('is_published', true)
                ->with('agent')
                ->orderBy('created_at', 'desc')
                ->paginate(12);
            return view('agent.tour-packages.index', compact('packages', 'isAgent'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Show the create form. If the blade doesn't exist, return a simple fallback view.
        if (view()->exists('agent.tour-packages.create')) {
            return view('agent.tour-packages.create');
        }

        return view('agent.tour-packages.create', []);
    }

    /**
     * Display a single tour package detail.
     */
    public function show($id)
    {
        $package = TourPackage::findOrFail($id);

        // Only show published packages to non-agents
        if (!$package->is_published && (!Auth::check() || !Auth::user()->isAgent())) {
            abort(403, 'Paket ini belum dipublikasikan.');
        }

        return view('wisatawan.tour-packages.show', compact('package'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price_per_person' => 'required|numeric|min:0',
            'duration' => 'nullable|string|max:100',
            'duration_days' => 'nullable|integer|min:0',
            'duration_nights' => 'nullable|integer|min:0',
            'facilities' => 'nullable|string',
            'minimum_participants' => 'nullable|integer|min:0',
            'availability_period' => 'nullable|string|max:255',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Get the authenticated user's agent
        $user = Auth::user();
        if (!$user || !$user->isAgent() || !$user->agent) {
            return redirect()->route('tour-packages.index')
                ->with('error', 'Anda harus menjadi agen untuk membuat paket perjalanan.');
        }

        // Handle file upload
        $imageUrl = null;
        if ($request->hasFile('cover_image_file')) {
            $file = $request->file('cover_image_file');
            $path = $file->store('tour_packages', 'public');
            $imageUrl = Storage::url($path);
        }

        // Create the tour package
        $package = new TourPackage();
        $package->agent_id = $user->agent->id;
        $package->name = $validated['title'];
        $package->description = $validated['description'] ?? '';
        $package->price_per_person = $validated['price_per_person'];
        $package->duration = $validated['duration'] ?? null;
        $package->facilities = $validated['facilities'] ?? null; // Model mutator handles JSON conversion
        $package->cover_image_url = $imageUrl;
        // Store additional fields from the form when available
        $package->duration_days = isset($validated['duration_days']) ? $validated['duration_days'] : null;
        $package->duration_nights = isset($validated['duration_nights']) ? $validated['duration_nights'] : null;
        $package->minimum_participants = isset($validated['minimum_participants']) ? $validated['minimum_participants'] : null;
        $package->availability_period = $validated['availability_period'] ?? null;
        
        // Store additional fields if there are other DB columns to map
        $package->save();

        return redirect()->route('tour-packages.index')
            ->with('success', 'Paket perjalanan berhasil dibuat!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $package = TourPackage::find($id);
        
        if (!$package) {
            return redirect()->route('tour-packages.index')
                ->with('error', 'Paket perjalanan tidak ditemukan.');
        }

        // Check if user owns this package
        $user = Auth::user();
        if (!$user || !$user->agent || $package->agent_id !== $user->agent->id) {
            return redirect()->route('tour-packages.index')
                ->with('error', 'Anda tidak memiliki hak untuk mengedit paket ini.');
        }

        return view('agent.tour-packages.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $package = TourPackage::find($id);
        
        if (!$package) {
            return redirect()->route('tour-packages.index')
                ->with('error', 'Paket perjalanan tidak ditemukan.');
        }

        // Check if user owns this package
        $user = Auth::user();
        if (!$user || !$user->agent || $package->agent_id !== $user->agent->id) {
            return redirect()->route('tour-packages.index')
                ->with('error', 'Anda tidak memiliki hak untuk mengedit paket ini.');
        }

        // Validate input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price_per_person' => 'required|numeric|min:0',
            'duration' => 'nullable|string|max:100',
            'duration_days' => 'nullable|integer|min:0',
            'duration_nights' => 'nullable|integer|min:0',
            'facilities' => 'nullable|string',
            'minimum_participants' => 'nullable|integer|min:0',
            'availability_period' => 'nullable|string|max:255',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload (replace old image if new one provided)
        if ($request->hasFile('cover_image_file')) {
            $file = $request->file('cover_image_file');
            $path = $file->store('tour_packages', 'public');
            $package->cover_image_url = Storage::url($path);
        }

        // Update the package
        $package->name = $validated['title'];
        $package->description = $validated['description'] ?? '';
        $package->price_per_person = $validated['price_per_person'];
        $package->duration = $validated['duration'] ?? null;
        $package->facilities = $validated['facilities'] ?? null; // Model mutator handles JSON conversion
        // Update additional fields from the form
        $package->duration_days = isset($validated['duration_days']) ? $validated['duration_days'] : $package->duration_days;
        $package->duration_nights = isset($validated['duration_nights']) ? $validated['duration_nights'] : $package->duration_nights;
        $package->minimum_participants = isset($validated['minimum_participants']) ? $validated['minimum_participants'] : $package->minimum_participants;
        $package->availability_period = $validated['availability_period'] ?? $package->availability_period;
        $package->save();

        return redirect()->route('tour-packages.index')
            ->with('success', 'Paket perjalanan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $package = TourPackage::find($id);
        
        if (!$package) {
            return redirect()->route('tour-packages.index')
                ->with('error', 'Paket perjalanan tidak ditemukan.');
        }

        // Check if user owns this package
        $user = Auth::user();
        if (!$user || !$user->agent || $package->agent_id !== $user->agent->id) {
            return redirect()->route('tour-packages.index')
                ->with('error', 'Anda tidak memiliki hak untuk menghapus paket ini.');
        }

        $package->delete();

        return redirect()->route('tour-packages.index')
            ->with('success', 'Paket perjalanan berhasil dihapus!');
    }
}
