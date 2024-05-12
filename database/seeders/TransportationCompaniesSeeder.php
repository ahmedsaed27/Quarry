<?php

namespace Database\Seeders;

use App\Models\TransportationCompanies;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransportationCompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransportationCompanies::create([
            'name' => 'شركه حديد عز',
            'transportation_cost' => 50,
            'phone' => '01123036666',
        ]);
    }
}
