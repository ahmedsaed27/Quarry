<?php

namespace Database\Seeders;

use App\Models\Quarries;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuarriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Quarries::create([
            'name' => 'محجر السلام',
            'phone' => '01123036666',
            'address' => [
                'محجر 1 حلوان',
            ],
        ]);
    }
}
