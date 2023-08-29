<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // for ($i = 0; $i < 10; $i++) {
        //     \App\Models\User::factory(40000)->create();
        //     sleep(10);
        // }

        $this->call(CategorySeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
