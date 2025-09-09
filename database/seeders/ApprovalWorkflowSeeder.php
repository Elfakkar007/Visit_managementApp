<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;
use App\Models\ApprovalWorkflow;

class ApprovalWorkflowSeeder extends Seeder
{
    public function run(): void
    {
        $staff = Level::where('name', 'Staff')->first();
        $spv = Level::where('name', 'SPV')->first();
        $manager = Level::where('name', 'Manager')->first();
        $deputi = Level::where('name', 'Deputi')->first();

        // Aturan 1: Manager approve Staff di departemen yang sama
        ApprovalWorkflow::firstOrCreate([
            'requester_level_id' => $staff->id,
            'approver_level_id' => $manager->id,
            'scope' => 'department',
        ]);

        // Aturan 2: Manager approve SPV di departemen yang sama
        ApprovalWorkflow::firstOrCreate([
            'requester_level_id' => $spv->id,
            'approver_level_id' => $manager->id,
            'scope' => 'department',
        ]);

        // Aturan 3: Deputi approve Manager di subsidiary yang sama
        ApprovalWorkflow::firstOrCreate([
            'requester_level_id' => $manager->id,
            'approver_level_id' => $deputi->id,
            'scope' => 'subsidiary',
        ]);
    }
}