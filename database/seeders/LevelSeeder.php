<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        Level::firstOrCreate(['name' => 'Staff']);
        Level::firstOrCreate(['name' => 'SPV']);
        Level::firstOrCreate(['name' => 'Manager']); // <-- Diperbaiki
        Level::firstOrCreate(['name' => 'Deputi']);
    }
}