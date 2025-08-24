<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = [
            [
                'brand' => 'Toyota',
                'model' => 'Avanza',
                'year' => 2022,
                'otr_price' => 220000000,
            ],
            [
                'brand' => 'Honda',
                'model' => 'Civic',
                'year' => 2021,
                'otr_price' => 350000000,
            ],
            [
                'brand' => 'Mitsubishi',
                'model' => 'Xpander',
                'year' => 2023,
                'otr_price' => 270000000,
            ],
            [
                'brand' => 'Suzuki',
                'model' => 'Ertiga',
                'year' => 2020,
                'otr_price' => 210000000,
            ],
            [
                'brand' => 'Daihatsu',
                'model' => 'Terios',
                'year' => 2022,
                'otr_price' => 250000000,
            ],
        ];

        foreach ($vehicles as $v) {
            Vehicle::create($v);
        }
    }
}
