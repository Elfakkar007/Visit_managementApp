<?php

namespace Database\Seeders;

use App\Models\Subsidiary;
use Illuminate\Database\Seeder;

class SubsidiarySeeder extends Seeder
{
    public function run(): void
    {
        Subsidiary::create(['name' => 'Pusat']);
        Subsidiary::create(['name' => 'Agro']);
        Subsidiary::create(['name' => 'Aneka']);
    }
}