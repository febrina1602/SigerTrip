<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari user dengan role 'agent' yang sudah verified
        $verifiedAgent = User::where('role', 'agent')
            ->where('status', 'aktif')
            ->where('verified_at', '!=', null)
            ->first();

        if (!$verifiedAgent) {
            $this->command->error('Tabel "users" tidak memiliki agent yang sudah terverifikasi!');
            return;
        }

        // Buat atau update Agent dengan data lengkap
        Agent::updateOrCreate(
            ['user_id' => $verifiedAgent->id], 
            [
                'name' => 'PT Lampung Tour & Travel',
                'email' => $verifiedAgent->email,
                'address' => 'Jl. Raya Bandar Lampung No. 123, Lampung',
                'location' => 'Bandar Lampung, Lampung',
                'phone_number' => '081234567890',
                'contact_phone' => '081234567890',
                'agent_type' => 'LOCAL_TOUR',
                'is_verified' => true,
                'rating' => 4.5,
                'banner_image_url' => 'https://via.placeholder.com/1920x400?text=PT+Lampung+Tour',
                'description' => 'Agen tour terpercaya yang menyediakan paket wisata lengkap ke berbagai destinasi di Lampung dengan pemandu wisata profesional dan berpengalaman.',
            ]
        );

        $this->command->info('Agent terverifikasi berhasil dibuat/diupdate!');
    }
}
