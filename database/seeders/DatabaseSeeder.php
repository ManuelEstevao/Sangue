<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;      // ← importe o façade DB
use Illuminate\Support\Facades\Hash; 

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            FevereiroSeeder::class
        ]);

    }
}
