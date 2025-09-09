<?php

namespace Database\Seeders;

use App\Models\Subsidiary;
use Illuminate\Database\Seeder;

class SubsidiarySeeder extends Seeder
{
    public function run(): void
    {
        Subsidiary::firstOrCreate(['name' => 'Pusat']);
        Subsidiary::firstOrCreate(['name' => 'Agro']);
        Subsidiary::firstOrCreate(['name' => 'Aneka']);
    }
}