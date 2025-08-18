<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        Department::create(['name' => 'IT']);
        Department::create(['name' => 'HRD']);
        Department::create(['name' => 'GA']);
        Department::create(['name' => 'Produksi']); // Hanya satu
    }
}