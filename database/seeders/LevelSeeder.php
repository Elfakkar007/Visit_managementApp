<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        Level::create(['name' => 'Staff']);
        Level::create(['name' => 'SPV']);
        Level::create(['name' => 'Manager']); // <-- Diperbaiki
        Level::create(['name' => 'Deputi']);
    }
}