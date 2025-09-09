<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        Department::firstOrCreate(['name' => 'IT']);
        Department::firstOrCreate(['name' => 'HRD']);
        Department::firstOrCreate(['name' => 'GA']);
        Department::firstOrCreate(['name' => 'Produksi']); // Hanya satu
    }
}