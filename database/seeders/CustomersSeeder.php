<?php

namespace Database\Seeders;

use App\Models\Customers;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customers::create([
            'name' => 'شركه حديد عز',
            'email' => 'ezz@ezz.com',
            'phone' => '01123036666',
            'address' => [
                'اسكندريه',
                'السخنه',
            ],
        ]);
    }
}
