<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DestinationCategory;

class DestinationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gunakan updateOrCreate:
        // Parameter 1: Cari berdasarkan 'name'
        // Parameter 2: Data yang diisi (termasuk 'icon_url' dari tabel Anda)
        
        DestinationCategory::updateOrCreate(
            ['name' => 'Pantai'],
            ['icon_url' => null] // Sesuai tabel 'destination_categories'
        );
        
        DestinationCategory::updateOrCreate(
            ['name' => 'Gunung'],
            ['icon_url' => null]
        );
        
        DestinationCategory::updateOrCreate(
            ['name' => 'Air Terjun'],
            ['icon_url' => null]
        );
        
        DestinationCategory::updateOrCreate(
            ['name' => 'Kuliner'],
            ['icon_url' => null]
        );
        
        DestinationCategory::updateOrCreate(
            ['name' => 'Budaya'],
            ['icon_url' => null]
        );
    }
}