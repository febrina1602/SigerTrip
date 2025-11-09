<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Destination;
use App\Models\DestinationCategory;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID kategori
        $pantai = DestinationCategory::where('name', 'Pantai')->first();
        $gunung = DestinationCategory::where('name', 'Gunung')->first();

        // 1. Destinasi Rekomendasi (Featured)
        Destination::updateOrCreate(
            ['name' => 'Pantai Pahawang'], // Cari berdasarkan nama
            [ // Data untuk diisi (sesuai tabel 'destinations')
                'category_id' => $pantai->id,
                'address' => 'Punduh Pidada, Pesawaran',
                'description' => 'Pulau Pahawang adalah destinasi snorkeling populer...',
                'facilities' => 'Toilet, Warung, Tempat Ganti',
                'image_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=400&q=80',
                'rating' => 4.5, // Sesuai kolom 'rating'
                'price_per_person' => 50000, // Sesuai 'price_per_person'
                'parking_price' => 10000, // Sesuai 'parking_price'
                'popular_activities' => json_encode(['Snorkeling', 'Berenang', 'Fotografi']), // Sesuai 'popular_activities'
                'is_featured' => true, // Sesuai 'is_featured'
                'latitude' => -5.678901, // Sesuai 'latitude'
                'longitude' => 105.123456, // Sesuai 'longitude'
            ]
        );

        // 2. Destinasi Biasa (Non-Featured)
        Destination::updateOrCreate(
            ['name' => 'Gunung Rajabasa'],
            [
                'category_id' => $gunung->id,
                'address' => 'Kalianda, Lampung Selatan',
                'description' => 'Gunung berapi yang indah dengan pemandangan...',
                'facilities' => 'Pos Pendakian, Warung',
                'image_url' => 'https.unsplash.com/photo-1519681393784-ade231f1ac14?w=400&q=80',
                'rating' => 4.2,
                'price_per_person' => 0,
                'parking_price' => 5000,
                'popular_activities' => json_encode(['Mendaki', 'Berkemah']),
                'is_featured' => false,
                'latitude' => -5.876543,
                'longitude' => 105.654321,
            ]
        );
    }
}