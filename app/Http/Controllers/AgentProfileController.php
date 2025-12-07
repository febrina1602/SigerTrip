<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AgentProfileController extends Controller
{
    // Tampilkan form edit profil
    public function edit()
    {
        $user = Auth::user();
        $agent = $user->agent;

        // Pastikan view ini ada di: resources/views/agent/profile/edit.blade.php
        return view('agent.profile.edit', compact('user', 'agent'));
    }

    // Proses update profil agensi
    public function update(Request $request)
    {
        $user = Auth::user();
        $agent = $user->agent;

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'address'       => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'description'   => 'required|string|min:10',
            'location'      => 'nullable|string|max:255',
            'banner_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Update Banner jika ada
        if ($request->hasFile('banner_image')) {
            if ($agent->banner_image_url) {
                Storage::disk('public')->delete($agent->banner_image_url);
            }
            $agent->banner_image_url = $request->file('banner_image')->store('agent-banners', 'public');
        }

        // Update data Agent
        $agent->update([
            'name'          => $validated['name'],
            'address'       => $validated['address'],
            'contact_phone' => $validated['contact_phone'],
            'description'   => $validated['description'],
            'location'      => $validated['location'] ?? $agent->location,
        ]);

        // Redirect sukses
        return redirect()->route('agent.dashboard')
            ->with('success', 'Profil agensi berhasil diperbarui! Akses fitur kini terbuka.');
    }
}