<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('wisatawan.profile.edit', compact('user'));
    }

    public function showPasswordForm()
    {
        $user = Auth::user();
        return view('wisatawan.profile.password', compact('user'));
    }

    /**
     * Update data profil ATAU password.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'full_name' => 'sometimes|required|string|max:255',
            'email' => [ 'sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id), ],
            'phone_number' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validasi foto
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->has('full_name')) {
             $user->fill($request->only('full_name', 'email', 'phone_number', 'gender'));
        }
        
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture_url) {
                $oldPath = str_replace(Storage::url(''), '', $user->profile_picture_url);
                Storage::disk('public')->delete($oldPath);
            }

            // Simpan gambar baru di folder 'public/profiles'
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $user->profile_picture_url = Storage::url($path);
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}