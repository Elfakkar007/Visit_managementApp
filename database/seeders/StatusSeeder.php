<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        Status::create(['name' => 'Pending', 'color' => 'yellow']);
        Status::create(['name' => 'Approved', 'color' => 'green']);
        Status::create(['name' => 'Rejected', 'color' => 'red']);
        Status::create(['name' => 'Cancelled', 'color' => 'gray']);
    }
}