<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin (langsung aktif)
        User::create([
            'full_name' => 'Admin Utama',
            'email' => 'admin@sigertrip.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'aktif',
            'verified_at' => now(),
            'phone_number' => '081234567890',
            'gender' => 'Laki-laki',
        ]);

        // Wisatawan (langsung aktif)
        User::create([
            'full_name' => 'Wisatawan 1',
            'email' => 'wisatawan@sigertrip.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'aktif',
            'verified_at' => now(),
            'phone_number' => '082345678901',
            'gender' => 'Perempuan',
        ]);

        // Agent (pending - butuh verifikasi)
        User::create([
            'full_name' => 'Agent Pending',
            'email' => 'agent@sigertrip.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'status' => 'pending',
            'verified_at' => null,
            'phone_number' => '083456789012',
            'gender' => 'Laki-laki',
        ]);

        // Agent yang sudah diverifikasi
        User::create([
            'full_name' => 'Agent Aktif',
            'email' => 'agent2@sigertrip.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'status' => 'aktif',
            'verified_at' => now(),
            'phone_number' => '084567890123',
            'gender' => 'Laki-laki',
        ]);
    }
}