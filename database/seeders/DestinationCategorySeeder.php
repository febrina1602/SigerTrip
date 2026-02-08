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
        
        DestinationCategory::updateOrCreate(
            ['name' => 'Pantai'],
            ['icon_url' => 'https://cdn-icons-png.flaticon.com/128/3097/3097033.png'] 
        );
        
        DestinationCategory::updateOrCreate(
            ['name' => 'Gunung'],
            ['icon_url' => 'https://cdn-icons-png.flaticon.com/128/2072/2072895.png']
        );
        
        DestinationCategory::updateOrCreate(
            ['name' => 'Air Terjun'],
            ['icon_url' => 'https://cdn-icons-png.flaticon.com/128/182/182161.png']
        );
        
        DestinationCategory::updateOrCreate(
            ['name' => 'Kuliner'],
            ['icon_url' => 'https://cdn-icons-png.flaticon.com/128/3448/3448613.png']
        );
        
        DestinationCategory::updateOrCreate(
            ['name' => 'Budaya'],
            ['icon_url' => 'https://cdn-icons-png.flaticon.com/128/3958/3958410.png']
        );
    }
}