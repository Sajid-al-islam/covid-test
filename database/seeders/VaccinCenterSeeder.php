<?php

namespace Database\Seeders;

use App\Models\VaccineCenter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VaccinCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VaccineCenter::truncate();
        VaccineCenter::insert([
            ['name' => 'Mirpur 1', 'capacity' => 100, 'address' => 'Monipur school', 'created_at' => now()],
            ['name' => 'Mirpur 12', 'capacity' => 50, 'address' => 'Mirpur MDC model school', 'created_at' => now()],
            ['name' => 'Mohakhali 1', 'capacity' => 150, 'address' => 'Mohakhali TB hospital', 'created_at' => now()],
            ['name' => 'Shaymoli', 'capacity' => 200, 'address' => 'National Heart institute, hospital', 'created_at' => now()],
            ['name' => 'Kurmitola', 'capacity' => 200, 'address' => 'Kurmitola hospital', 'created_at' => now()],
            ['name' => 'Uttora', 'capacity' => 200, 'address' => 'Uttora hospital', 'created_at' => now()],
            ['name' => 'Banani', 'capacity' => 200, 'address' => 'Banani Generel hospital', 'created_at' => now()],
            ['name' => 'Dhanmondi', 'capacity' => 200, 'address' => 'Square hospital', 'created_at' => now()],
            ['name' => 'Shahbag', 'capacity' => 200, 'address' => 'Dhaka medical college', 'created_at' => now()]
        ]);
    }
}
