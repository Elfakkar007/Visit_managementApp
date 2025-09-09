<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        Status::firstOrCreate(['name' => 'Pending', 'color' => 'yellow']);
        Status::firstOrCreate(['name' => 'Approved', 'color' => 'green']);
        Status::firstOrCreate(['name' => 'Rejected', 'color' => 'red']);
        Status::firstOrCreate(['name' => 'Cancelled', 'color' => 'gray']);
    }
}