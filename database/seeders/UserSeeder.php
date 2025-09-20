<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Subsidiary;
use App\Models\Department;
use App\Models\Level;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $subsidiaries = Subsidiary::pluck('id', 'name');
        $departments = Department::pluck('id', 'name');
        $levels = Level::pluck('id', 'name');

        // --- USER SUPER ADMIN ---
        $adminUser = User::updateOrCreate(['email' => 'admin.it@satoria.com'],[
            'name' => 'Admin IT', 'password' => Hash::make('password')
        ]);
        $adminUser->profile()->updateOrCreate([], [
            'subsidiary_id' => $subsidiaries['Pusat'],
            'department_id' => $departments['IT'],
            'level_id' => $levels['Staff'],
        ]);
        $adminUser->syncRoles('Admin'); // Peran tunggal

        // --- DEPUTI (APPROVER TERTINGGI) ---
        $deputiAgro = User::updateOrCreate(['email' => 'deputi.agro@satoria.com'], ['name' => 'Deputi Agro', 'password' => Hash::make('password')]);
        $deputiAgro->profile()->updateOrCreate([],['subsidiary_id' => $subsidiaries['Agro'], 'department_id' => $departments['GA'], 'level_id' => $levels['Deputi']]);
        $deputiAgro->syncRoles('Approver'); // Peran tunggal

        $deputiAneka = User::updateOrCreate(['email' => 'deputi.aneka@satoria.com'], ['name' => 'Deputi Aneka', 'password' => Hash::make('password')]);
        $deputiAneka->profile()->updateOrCreate([],['subsidiary_id' => $subsidiaries['Aneka'], 'department_id' => $departments['GA'], 'level_id' => $levels['Deputi']]);
        $deputiAneka->syncRoles('Approver'); // Peran tunggal

        // --- DEPARTEMEN-DEPARTEMEN DI SUBSIDIARY PUSAT ---
        $managerIt = User::updateOrCreate(['email' => 'manager.it@satoria.com'], ['name' => 'Manager IT', 'password' => Hash::make('password')]);
        $managerIt->profile()->updateOrCreate([],['subsidiary_id' => $subsidiaries['Pusat'], 'department_id' => $departments['IT'], 'level_id' => $levels['Manager']]);
        $managerIt->syncRoles('Approver' ,'Staff'); // Peran tunggal

        $spvIt = User::updateOrCreate(['email' => 'spv.it@satoria.com'], ['name' => 'SPV IT', 'password' => Hash::make('password')]);
        $spvIt->profile()->updateOrCreate([],['subsidiary_id' => $subsidiaries['Pusat'], 'department_id' => $departments['IT'], 'level_id' => $levels['SPV']]);
        $spvIt->syncRoles('Staff'); // SPV diasumsikan sebagai staff dalam hal hak akses dasar

        $managerHrd = User::updateOrCreate(['email' => 'manager.hrd@satoria.com'], ['name' => 'Manager HRD', 'password' => Hash::make('password')]);
        $managerHrd->profile()->updateOrCreate([],['subsidiary_id' => $subsidiaries['Pusat'], 'department_id' => $departments['HRD'], 'level_id' => $levels['Manager']]);
        $managerHrd->syncRoles('HRD','Staff','Approver'); // Peran fungsional spesifik

        $staffHrd = User::updateOrCreate(['email' => 'staff.hrd@satoria.com'], ['name' => 'Staff HRD', 'password' => Hash::make('password')]);
        $staffHrd->profile()->updateOrCreate([],['subsidiary_id' => $subsidiaries['Pusat'], 'department_id' => $departments['HRD'], 'level_id' => $levels['Staff']]);
        $staffHrd->syncRoles('HRD','Staff'); // Peran fungsional spesifik

        $resepsionis = User::updateOrCreate(['email' => 'resepsionis.ga@satoria.com'], ['name' => 'Resepsionis GA', 'password' => Hash::make('password')]);
        $resepsionis->profile()->updateOrCreate([],['subsidiary_id' => $subsidiaries['Pusat'], 'department_id' => $departments['GA'], 'level_id' => $levels['Staff']]);
        $resepsionis->syncRoles('Resepsionis'); // Peran tunggal

        // --- DEPARTEMEN PRODUKSI DI MASING-MASING SUBSIDIARY ---
        $managerProdAgro = User::updateOrCreate(['email' => 'manager.prod.agro@satoria.com'], ['name' => 'Manager Produksi Agro', 'password' => Hash::make('password')]);
        $managerProdAgro->profile()->updateOrCreate([],['subsidiary_id' => $subsidiaries['Agro'], 'department_id' => $departments['Produksi'], 'level_id' => $levels['Manager']]);
        $managerProdAgro->syncRoles('Staff','Approver'); // Peran tunggal

        $staffProdAgro = User::updateOrCreate(['email' => 'staff.prod.agro@satoria.com'], ['name' => 'Staff Produksi Agro', 'password' => Hash::make('password')]);
        $staffProdAgro->profile()->updateOrCreate([],['subsidiary_id' => $subsidiaries['Agro'], 'department_id' => $departments['Produksi'], 'level_id' => $levels['Staff']]);
        $staffProdAgro->syncRoles('Staff'); // Peran tunggal

        $managerProdAneka = User::updateOrCreate(['email' => 'manager.prod.aneka@satoria.com'], ['name' => 'Manager Produksi Aneka', 'password' => Hash::make('password')]);
        $managerProdAneka->profile()->updateOrCreate([],['subsidiary_id' => $subsidiaries['Aneka'], 'department_id' => $departments['Produksi'], 'level_id' => $levels['Manager']]);
        $managerProdAneka->syncRoles('Staff','Approver'); // Peran tunggal

        $staffProdAneka = User::updateOrCreate(['email' => 'staff.prod.aneka@satoria.com'], ['name' => 'Staff Produksi Aneka', 'password' => Hash::make('password')]);
        $staffProdAneka->profile()->updateOrCreate([],['subsidiary_id' => $subsidiaries['Aneka'], 'department_id' => $departments['Produksi'], 'level_id' => $levels['Staff']]);
        $staffProdAneka->syncRoles('Staff'); // Peran tunggal

        
    }
}