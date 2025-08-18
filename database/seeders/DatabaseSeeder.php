<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SubsidiarySeeder::class,
            DepartmentSeeder::class,
            RoleSeeder::class,
            LevelSeeder::class,
            StatusSeeder::class,
            UserSeeder::class, // UserSeeder dipanggil terakhir
        ]);
    }
}