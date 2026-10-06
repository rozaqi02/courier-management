<?php

namespace Database\Seeders;

use App\Models\Courier;
use Illuminate\Database\Seeder;

class CourierSeeder extends Seeder
{
    public function run(): void
    {
        Courier::factory()->count(20)->create();

        // Create specific test data
        Courier::create([
            'name'          => 'Budiono Hadi Agung',
            'phone'         => '08123456789',
            'email'         => 'budiono@example.com',
            'level'         => 3,
            'vehicle_type'  => 'motorcycle',
            'vehicle_plate' => 'B 1234 XYZ',
            'address'       => 'Jakarta Selatan',
            'status'        => 'active',
        ]);
    }
}
