<?php

namespace Database\Seeders;

use App\Models\TransportWorkers;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransportWorkersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransportWorkers::create([
            'transportation_companies_id' => 1,
            'name' => 'محمد زيدان',
            'phone' => '01123035545',
            'car_number' => '151 - س ر ى',
        ]);
    }
}
