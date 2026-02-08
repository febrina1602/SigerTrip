<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // === FILTER ROLE ===
        if ($request->filled('role') && $request->role !== '' && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // === FILTER STATUS ===
        if ($request->filled('status') && $request->status !== '' && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // === FILTER SEARCH ===
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // === PAGINATION dengan Query String ===
        $users = $query->orderBy('created_at', 'desc')
                    ->paginate(10)
                    ->appends($request->all()); // Gunakan all() bukan query()

        // === STATISTIK ===
        $totalUser = User::count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalAgent = User::where('role', 'agent')->count();
        $totalRegularUser = User::where('role', 'user')->count();
        $totalPending = User::where('status', 'pending')->count();

        return view('admin.users.index', compact(
            'users',
            'totalUser',
            'totalAdmin',
            'totalAgent',
            'totalRegularUser',
            'totalPending'
        ));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,agent,user',
            'phone_number' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // LOGIKA: Hanya agent yang pending, admin dan user langsung aktif
        if ($request->role === 'agent') {
            $status = 'pending';
            $verifiedAt = null;
        } else {
            $status = 'aktif';
            $verifiedAt = now();
        }

        $data = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $status,
            'verified_at' => $verifiedAt,
            'phone_number' => $request->phone_number,
            'gender' => $request->gender,
        ];

        // Upload profile picture
        if ($request->hasFile('profile_picture')) {
            $imagePath = $request->file('profile_picture')->store('profiles', 'public');
            $data['profile_picture_url'] = Storage::url($imagePath);
        }

        User::create($data);

        $message = $request->role === 'agent' 
            ? 'Agent berhasil ditambahkan dan menunggu verifikasi!' 
            : 'User berhasil ditambahkan!';

        return redirect()->route('admin.users.index')
            ->with('success', $message);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,agent,user',
            'status' => 'required|in:aktif,nonaktif,pending', // Tambah validasi status
            'phone_number' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'password' => 'nullable|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status, // Tambah field status
            'phone_number' => $request->phone_number,
            'gender' => $request->gender,
        ];

        // Update verified_at jika status diubah menjadi aktif
        if ($request->status === 'aktif' && $user->status !== 'aktif') {
            $data['verified_at'] = now();
        }

        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Upload profile picture baru
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture_url) {
                $oldPath = str_replace('/storage/', '', $user->profile_picture_url);
                Storage::disk('public')->delete($oldPath);
            }
            $imagePath = $request->file('profile_picture')->store('profiles', 'public');
            $data['profile_picture_url'] = Storage::url($imagePath);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Tidak bisa hapus diri sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        // Hapus foto profil jika ada
        if ($user->profile_picture_url) {
            $path = str_replace('/storage/', '', $user->profile_picture_url);
            Storage::disk('public')->delete($path);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus!');
    }

    public function verify($id)
    {
        $user = User::findOrFail($id);

        // HANYA AGENT yang bisa diverifikasi
        if ($user->role !== 'agent') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Hanya agent yang perlu diverifikasi!');
        }

        // Cek apakah masih pending
        if ($user->status === 'pending') {
            $user->update([
                'status' => 'aktif',
                'verified_at' => now(),
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', "Agent {$user->full_name} berhasil diverifikasi dan sekarang aktif!");
        }

        return redirect()->route('admin.users.index')
            ->with('error', 'Agent ini sudah diverifikasi sebelumnya!');
    }

    // METHOD BARU: Tolak Agent
    public function reject($id)
    {
        $user = User::findOrFail($id);

        // HANYA AGENT PENDING yang bisa ditolak
        if ($user->role !== 'agent' || $user->status !== 'pending') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Hanya agent pending yang bisa ditolak!');
        }

        // Ubah status menjadi nonaktif (ditolak)
        $user->update([
            'status' => 'nonaktif',
            'verified_at' => null,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Agent {$user->full_name} ditolak dan statusnya menjadi nonaktif!");
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        // Tidak bisa ubah status diri sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak bisa mengubah status akun sendiri!');
        }

        $newStatus = $user->status === 'aktif' ? 'nonaktif' : 'aktif';
        $user->update(['status' => $newStatus]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Status user berhasil diubah menjadi ' . $newStatus . '!');
    }
}