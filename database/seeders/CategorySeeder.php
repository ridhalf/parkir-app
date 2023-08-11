<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Mobil', 'charge' => 5000],
            ['name' => 'Motor', 'charge' => 3000],
        ];
        DB::table('categories')->insert($categories);
    }
}
