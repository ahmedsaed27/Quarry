<?php

namespace Database\Seeders;

use App\Models\SupplyOrder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplyOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SupplyOrder::create([
            'customers_id' => 1,
            'supply_number' => 12341516,
            'ton' => 300,
            'customers_id' => 1,
        ]);
    }
}
