<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Agent;
use App\Models\TourPackage;

class CreateSampleTourPackageSeeder extends Seeder
{
    public function run()
    {
        // Ensure storage dir exists
        Storage::disk('public')->makeDirectory('tour_packages');

        // Copy an existing image from public/images if available
        $source = public_path('images/logo.png');
        $targetPath = 'tour_packages/sample1.jpg';

        if (file_exists($source)) {
            Storage::disk('public')->put($targetPath, file_get_contents($source));
        } else {
            // create a tiny placeholder if source absent
            Storage::disk('public')->put($targetPath, '');
        }

        $imageUrl = Storage::url($targetPath); // /storage/tour_packages/sample1.jpg

        // Find or create an agent and user
        $agent = Agent::first();

        if (! $agent) {
            // create user
            $user = User::where('email', 'sample-agent@example.com')->first();
            if (! $user) {
                $user = User::create([
                    'full_name' => 'Sample Agent',
                    'email' => 'sample-agent@example.com',
                    'password' => Hash::make('password123'),
                    'role' => 'agent',
                    'status' => 'aktif',
                ]);
            }

            $agent = Agent::create([
                'user_id' => $user->id,
                'name' => 'Sample Travel Co.',
                'agent_type' => 'LOCAL_TOUR',
                'address' => 'Jl. Contoh No.1',
                'location' => 'Lampung',
                'contact_phone' => '081234567890',
                'is_verified' => true,
                'rating' => 4.5,
                'banner_image_url' => $imageUrl,
                'description' => 'Agen contoh untuk testing',
            ]);
        }

        // Create a sample tour package
        $pkg = TourPackage::create([
            'agent_id' => $agent->id,
            'name' => 'Paket Wisata Contoh: Pulau & Snorkeling',
            'description' => 'Nikmati tur 2 hari 1 malam ke pulau-pulau terdekat, termasuk snorkeling dan makan siang laut.',
            'cover_image_url' => $imageUrl,
            'price_per_person' => 750000,
            'minimum_participants' => 2,
            'maximum_participants' => 20,
            'duration_days' => 2,
            'duration_nights' => 1,
            'start_time' => null,
            'end_time' => null,
            'availability_period' => 'April - Oktober',
            'facilities' => ['Akomodasi', 'Makan 3x', 'Perahu', 'Pemandu'],
            'destinations_visited' => [],
            'is_published' => false,
        ]);

        $this->command->info('Sample tour package created with id: ' . $pkg->id);
    }
}
