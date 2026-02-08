<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Destination;
use App\Models\TourPackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TourPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agent = Agent::first();
        $destinations = Destination::take(3)->pluck('id');

        if (!$agent || $destinations->isEmpty()) {
            $this->command->error('Pastikan ada data di tabel Agents dan Destinations sebelum menjalankan seeder ini.');
            return;
        }

        // Get or create a LocalTourAgent for this Agent (required for new structure)
        $localTourAgent = \App\Models\LocalTourAgent::firstOrCreate(
            ['agent_id' => $agent->id, 'name' => $agent->name . ' - Local Tour'],
            [
                'description' => 'Local tour agent untuk ' . $agent->name,
                'contact_phone' => $agent->contact_phone ?? '',
                'is_verified' => true,
            ]
        );

        TourPackage::updateOrCreate(
            ['name' => 'Paket Snorkeling Pahawang 2D1N'],
            [
                'local_tour_agent_id' => $localTourAgent->id,
                'agent_id' => $agent->id, // booted() will set this, but explicit for clarity
                'description' => 'Nikmati keindahan bawah laut Pulau Pahawang...',
                'cover_image_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=600&q=80',
                'price_per_person' => 500000,
                'duration' => '2 Hari 1 Malam',
                'facilities' => 'Penginapan, Makan 3x, Alat Snorkel, Perahu, Dokumentasi',
                'destinations_visited' => [$destinations[0] ?? 1],
            ]
        );

        TourPackage::updateOrCreate(
            ['name' => 'Surfing Trip Krui 3D2N'],
            [
                'local_tour_agent_id' => $localTourAgent->id,
                'agent_id' => $agent->id,
                'description' => 'Paket khusus untuk para peselancar...',
                'cover_image_url' => 'https://images.unsplash.com/photo-1519046904884-53103b34b206?w=600&q=80',
                'price_per_person' => 1200000,
                'duration' => '3 Hari 2 Malam',
                'facilities' => 'Villa/Homestay, Transportasi, Papan Selancar (opsional)',
                'destinations_visited' => [$destinations[1] ?? 2],
            ]
        );

        TourPackage::updateOrCreate(
            ['name' => 'One Day Trip Pulau Tegal Mas'],
            [
                'local_tour_agent_id' => $localTourAgent->id,
                'agent_id' => $agent->id,
                'description' => 'Liburan singkat ke Tegal Mas...',
                'cover_image_url' => 'https://images.unsplash.com/photo-1502602898657-3e91760c0341?w=600&q=80',
                'price_per_person' => 350000,
                'duration' => '1 Hari',
                'facilities' => 'Perahu, Tiket Masuk, Makan Siang, Dokumentasi',
                'destinations_visited' => [$destinations[2] ?? 3],
            ]
        );
    }
}