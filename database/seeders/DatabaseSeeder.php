<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Materials;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        // Materials::factory(10)->create();
        $this->call(UsersSeeder::class);
        $this->call(CompaniesSeeder::class);
        $this->call(CustomersSeeder::class);
        $this->call(MaterialsSeeder::class);
        $this->call(QuarriesSeeder::class);
        $this->call(TransportationCompaniesSeeder::class);
        $this->call(TransportWorkersSeeder::class);
        $this->call(SupplyOrderSeeder::class);

    }
}
