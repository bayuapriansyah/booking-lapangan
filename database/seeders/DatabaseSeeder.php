<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Court;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);

        $courts = [
            'Lapangan 1',
            'Lapangan 2',
            'Lapangan 3',
            'Lapangan 4',
        ];

        foreach ($courts as $court) {
            Court::create([
                'name' => $court,
                'type' => 'badminton',
                'price_per_hour' => rand(5, 15),
                'is_active' => true,
            ]);
        }
    }
}