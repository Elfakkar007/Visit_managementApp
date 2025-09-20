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
            LevelSeeder::class,
            StatusSeeder::class,
            RolesAndPermissionsSeeder::class,

        ]);
        $this->call(UserSeeder::class);
    }
}