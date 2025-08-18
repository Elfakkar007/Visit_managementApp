<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // === Ambil ID dari data master untuk relasi ===
        $pusat = \App\Models\Subsidiary::where('name', 'Pusat')->firstOrFail();
        $agro = \App\Models\Subsidiary::where('name', 'Agro')->firstOrFail();
        $aneka = \App\Models\Subsidiary::where('name', 'Aneka')->firstOrFail();
        
        $adminRole = \App\Models\Role::where('name', 'Admin')->firstOrFail();
        $staffRole = \App\Models\Role::where('name', 'Staff')->firstOrFail();
        $approverRole = \App\Models\Role::where('name', 'Approver')->firstOrFail();
        $receptionistRole = \App\Models\Role::where('name', 'Resepsionis')->firstOrFail();
        
        $staffLevel = \App\Models\Level::where('name', 'Staff')->firstOrFail();
        $spvLevel = \App\Models\Level::where('name', 'SPV')->firstOrFail();
        $managerLevel = \App\Models\Level::where('name', 'Manager')->firstOrFail();
        $deputiLevel = \App\Models\Level::where('name', 'Deputi')->firstOrFail();

        $itDept = \App\Models\Department::where('name', 'IT')->firstOrFail();
        $hrdDept = \App\Models\Department::where('name', 'HRD')->firstOrFail();
        $gaDept = \App\Models\Department::where('name', 'GA')->firstOrFail();
        $produksiDept = \App\Models\Department::where('name', 'Produksi')->firstOrFail();
        
        // --- DEPUTI (SUBSIDIARY AGRO & ANEKA) ---
        User::create(['name' => 'Deputi Agro', 'email' => 'deputi.agro@satoria.com', 'password' => Hash::make('password')])
            ->profile()->create(['subsidiary_id' => $agro->id, 'department_id' => $gaDept->id, 'role_id' => $approverRole->id, 'level_id' => $deputiLevel->id]);

        User::create(['name' => 'Deputi Aneka', 'email' => 'deputi.aneka@satoria.com', 'password' => Hash::make('password')])
            ->profile()->create(['subsidiary_id' => $aneka->id, 'department_id' => $gaDept->id, 'role_id' => $approverRole->id, 'level_id' => $deputiLevel->id]);

        // --- DEPT IT (SUBSIDIARY PUSAT) ---
        User::create(['name' => 'Admin IT', 'email' => 'admin.it@satoria.com', 'password' => Hash::make('password')])
            ->profile()->create(['subsidiary_id' => $pusat->id, 'department_id' => $itDept->id, 'role_id' => $adminRole->id, 'level_id' => $staffLevel->id]);
        User::create(['name' => 'Staff IT', 'email' => 'staff.it@satoria.com', 'password' => Hash::make('password')])
            ->profile()->create(['subsidiary_id' => $pusat->id, 'department_id' => $itDept->id, 'role_id' => $staffRole->id, 'level_id' => $staffLevel->id]);
        User::create(['name' => 'SPV IT', 'email' => 'spv.it@satoria.com', 'password' => Hash::make('password')])
            ->profile()->create(['subsidiary_id' => $pusat->id, 'department_id' => $itDept->id, 'role_id' => $staffRole->id, 'level_id' => $spvLevel->id]);
        User::create(['name' => 'Manager IT', 'email' => 'manager.it@satoria.com', 'password' => Hash::make('password')])
            ->profile()->create(['subsidiary_id' => $pusat->id, 'department_id' => $itDept->id, 'role_id' => $approverRole->id, 'level_id' => $managerLevel->id]);
            
        // --- DEPT HRD (SUBSIDIARY PUSAT) ---
        User::create(['name' => 'Staff HRD', 'email' => 'staff.hrd@satoria.com', 'password' => Hash::make('password')])
            ->profile()->create(['subsidiary_id' => $pusat->id, 'department_id' => $hrdDept->id, 'role_id' => $staffRole->id, 'level_id' => $staffLevel->id]);
        User::create(['name' => 'SPV HRD', 'email' => 'spv.hrd@satoria.com', 'password' => Hash::make('password')])
            ->profile()->create(['subsidiary_id' => $pusat->id, 'department_id' => $hrdDept->id, 'role_id' => $staffRole->id, 'level_id' => $spvLevel->id]);
        User::create(['name' => 'Manager HRD', 'email' => 'manager.hrd@satoria.com', 'password' => Hash::make('password')])
            ->profile()->create(['subsidiary_id' => $pusat->id, 'department_id' => $hrdDept->id, 'role_id' => $approverRole->id, 'level_id' => $managerLevel->id]);
            
        // --- DEPT GA (SUBSIDIARY PUSAT) ---
        User::create(['name' => 'Resepsionis GA', 'email' => 'resepsionis.ga@satoria.com', 'password' => Hash::make('password')])
            ->profile()->create(['subsidiary_id' => $pusat->id, 'department_id' => $gaDept->id, 'role_id' => $receptionistRole->id, 'level_id' => $staffLevel->id]);
        User::create(['name' => 'Staff GA', 'email' => 'staff.ga@satoria.com', 'password' => Hash::make('password')])
            ->profile()->create(['subsidiary_id' => $pusat->id, 'department_id' => $gaDept->id, 'role_id' => $staffRole->id, 'level_id' => $staffLevel->id]);
        User::create(['name' => 'SPV GA', 'email' => 'spv.ga@satoria.com', 'password' => Hash::make('password')])
            ->profile()->create(['subsidiary_id' => $pusat->id, 'department_id' => $gaDept->id, 'role_id' => $staffRole->id, 'level_id' => $spvLevel->id]);
        User::create(['name' => 'Manager GA', 'email' => 'manager.ga@satoria.com', 'password' => Hash::make('password')])
            ->profile()->create(['subsidiary_id' => $pusat->id, 'department_id' => $gaDept->id, 'role_id' => $approverRole->id, 'level_id' => $managerLevel->id]);

        // --- DEPT PRODUKSI (DIBEDAKAN OLEH SUBSIDIARY) ---
        // Produksi untuk Agro
        User::create(['name' => 'Staff Produksi Agro', 'email' => 'staff.prod.agro@satoria.com', 'password' => Hash::make('password')])
            ->profile()->create(['subsidiary_id' => $agro->id, 'department_id' => $produksiDept->id, 'role_id' => $staffRole->id, 'level_id' => $staffLevel->id]);
        User::create(['name' => 'SPV Produksi Agro', 'email' => 'spv.prod.agro@satoria.com', 'password' => Hash::make('password')])
            ->profile()->create(['subsidiary_id' => $agro->id, 'department_id' => $produksiDept->id, 'role_id' => $staffRole->id, 'level_id' => $spvLevel->id]);
        User::create(['name' => 'Manager Produksi Agro', 'email' => 'manager.prod.agro@satoria.com', 'password' => Hash::make('password')])
            ->profile()->create(['subsidiary_id' => $agro->id, 'department_id' => $produksiDept->id, 'role_id' => $approverRole->id, 'level_id' => $managerLevel->id]);

        // Produksi untuk Aneka
        User::create(['name' => 'Staff Produksi Aneka', 'email' => 'staff.prod.aneka@satoria.com', 'password' => Hash::make('password')])
            ->profile()->create(['subsidiary_id' => $aneka->id, 'department_id' => $produksiDept->id, 'role_id' => $staffRole->id, 'level_id' => $staffLevel->id]);
        User::create(['name' => 'SPV Produksi Aneka', 'email' => 'spv.prod.aneka@satoria.com', 'password' => Hash::make('password')])
            ->profile()->create(['subsidiary_id' => $aneka->id, 'department_id' => $produksiDept->id, 'role_id' => $staffRole->id, 'level_id' => $spvLevel->id]);
        User::create(['name' => 'Manager Produksi Aneka', 'email' => 'manager.prod.aneka@satoria.com', 'password' => Hash::make('password')])
            ->profile()->create(['subsidiary_id' => $aneka->id, 'department_id' => $produksiDept->id, 'role_id' => $approverRole->id, 'level_id' => $managerLevel->id]);
    }
}