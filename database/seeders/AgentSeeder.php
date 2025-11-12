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
        $user = User::first(); 

        if (!$user) {
            $this->command->error('Tabel "users" masih kosong. Harap buat data user terlebih dahulu!');
            return;
        }

        Agent::updateOrCreate(
            ['user_id' => $user->id], 
            [
                'name' => 'SigerTrip Tour & Rental',
                'address' => 'Bandar Lampung, Lampung',
                'phone_number' => '081234567890',
                'is_verified' => true,
                
            ]
        );
    }
}
