<?php

namespace Database\Seeders;

use App\Models\Materials;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Materials::create([
            'name' => 'مخصوص'
        ]);
    }
}
