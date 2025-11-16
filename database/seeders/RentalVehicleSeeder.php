<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RentalVehicle;
use App\Models\Agent;

class RentalVehicleSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil agen yang sudah terverifikasi
        $agent = Agent::where('is_verified', true)->first();

        if (!$agent) {
            $this->command->error('Tidak ada agen terverifikasi. Buat agen dulu.');
            return;
        }

        RentalVehicle::updateOrCreate(
            ['name' => 'Avanza Xenia'],
            [
                'agent_id' => $agent->id,
                'vehicle_type' => 'CAR',
                'price_per_day' => 350000,
                'location' => 'Bandar Lampung',
                'description' => 'Mobil keluarga yang nyaman untuk perjalanan Anda di Lampung. Kapasitas 7 penumpang.',
                'image_url' => 'https://example.com/avanza.jpg' 
            ]
        );

        RentalVehicle::updateOrCreate(
            ['name' => 'Innova Reborn'],
            [
                'agent_id' => $agent->id,
                'vehicle_type' => 'CAR',
                'price_per_day' => 550000,
                'location' => 'Bandar Lampung',
                'description' => 'Kenyamanan ekstra untuk perjalanan bisnis atau keluarga. Kapasitas 7-8 penumpang.',
                'image_url' => 'https://example.com/innova.jpg' 
            ]
        );

        RentalVehicle::updateOrCreate(
            ['name' => 'Honda BeAT'],
            [
                'agent_id' => $agent->id,
                'vehicle_type' => 'MOTORCYCLE',
                'price_per_day' => 80000,
                'location' => 'Metro',
                'description' => 'Solusi praktis dan hemat bahan bakar untuk berkeliling kota.',
                'image_url' => 'https://example.com/beat.jpg' 
            ]
        );
    }
}