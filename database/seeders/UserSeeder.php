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
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // ====================================================================================
        // Penjelasan:
        // 1. Kita tidak lagi menggunakan 'role_id' saat membuat profil.
        // 2. Kita menggunakan method `$user->assignRole('Nama Role')` dari Spatie
        //    untuk menugaskan peran ke setiap user SETELAH user tersebut dibuat.
        //    Ini adalah cara baru yang dinamis.
        // ====================================================================================

        // Ambil semua data master untuk efisiensi
        $subsidiaries = Subsidiary::pluck('id', 'name');
        $departments = Department::pluck('id', 'name');
        $levels = Level::pluck('id', 'name');

        // --- USER SUPER ADMIN ---
        $adminUser = User::updateOrCreate([
            'name' => 'Admin IT',
            'email' => 'admin.it@satoria.com',
            'password' => Hash::make('password')
        ]);
        $adminUser->profile()->updateOrCreate([
            'subsidiary_id' => $subsidiaries['Pusat'],
            'department_id' => $departments['IT'],
            'level_id' => $levels['Staff'],
        ]);
        $adminUser->assignRole('Admin'); // Menugaskan role 'Admin'


        // --- DEPUTI (APPROVER TERTINGGI) ---
        $deputiAgro = User::updateOrCreate(['name' => 'Deputi Agro', 'email' => 'deputi.agro@satoria.com', 'password' => Hash::make('password')]);
        $deputiAgro->profile()->updateOrCreate(['subsidiary_id' => $subsidiaries['Agro'], 'department_id' => $departments['GA'], 'level_id' => $levels['Deputi']]);
        $deputiAgro->assignRole('Approver');

        $deputiAneka = User::updateOrCreate(['name' => 'Deputi Aneka', 'email' => 'deputi.aneka@satoria.com', 'password' => Hash::make('password')]);
        $deputiAneka->profile()->updateOrCreate(['subsidiary_id' => $subsidiaries['Aneka'], 'department_id' => $departments['GA'], 'level_id' => $levels['Deputi']]);
        $deputiAneka->assignRole('Approver');


        // --- DEPARTEMEN-DEPARTEMEN DI SUBSIDIARY PUSAT ---

        // Dept IT (Pusat)
        $managerIt = User::updateOrCreate(['name' => 'Manager IT', 'email' => 'manager.it@satoria.com', 'password' => Hash::make('password')]);
        $managerIt->profile()->updateOrCreate(['subsidiary_id' => $subsidiaries['Pusat'], 'department_id' => $departments['IT'], 'level_id' => $levels['Manager']]);
        $managerIt->assignRole('Staff');
        $managerIt->assignRole('Approver');

        $spvIt = User::updateOrCreate(['name' => 'SPV IT', 'email' => 'spv.it@satoria.com', 'password' => Hash::make('password')]);
        $spvIt->profile()->updateOrCreate(['subsidiary_id' => $subsidiaries['Pusat'], 'department_id' => $departments['IT'], 'level_id' => $levels['SPV']]);
        $spvIt->assignRole('Staff');

        // Dept HRD (Pusat) - Punya Role Spesial
        $managerHrd = User::updateOrCreate(['name' => 'Manager HRD', 'email' => 'manager.hrd@satoria.com', 'password' => Hash::make('password')]);
        $managerHrd->profile()->updateOrCreate(['subsidiary_id' => $subsidiaries['Pusat'], 'department_id' => $departments['HRD'], 'level_id' => $levels['Manager']]);
        $managerHrd->assignRole('Approver');
        $managerHrd->assignRole('HRD'); // Diberi role tambahan 'HRD'
        $managerHrd->assignRole('Staff'); // Diberi role tambahan 'HRD'

        $staffHrd = User::updateOrCreate(['name' => 'Staff HRD', 'email' => 'staff.hrd@satoria.com', 'password' => Hash::make('password')]);
        $staffHrd->profile()->updateOrCreate(['subsidiary_id' => $subsidiaries['Pusat'], 'department_id' => $departments['HRD'], 'level_id' => $levels['Staff']]);
        $staffHrd->assignRole('Staff');
        $staffHrd->assignRole('HRD'); // Diberi role tambahan 'HRD'

        // Dept GA (Pusat) - Ada Resepsionis di sini
        $managerGa = User::updateOrCreate(['name' => 'Manager GA', 'email' => 'manager.ga@satoria.com', 'password' => Hash::make('password')]);
        $managerGa->profile()->updateOrCreate(['subsidiary_id' => $subsidiaries['Pusat'], 'department_id' => $departments['GA'], 'level_id' => $levels['Manager']]);
        $managerGa->assignRole('Approver');
        $managerGa->assignRole('Staff');

        $resepsionis = User::updateOrCreate(['name' => 'Resepsionis GA', 'email' => 'resepsionis.ga@satoria.com', 'password' => Hash::make('password')]);
        $resepsionis->profile()->updateOrCreate(['subsidiary_id' => $subsidiaries['Pusat'], 'department_id' => $departments['GA'], 'level_id' => $levels['Staff']]);
        $resepsionis->assignRole('Resepsionis');


        // --- DEPARTEMEN PRODUKSI DI MASING-MASING SUBSIDIARY ---

        // Produksi untuk Agro
        $managerProdAgro = User::updateOrCreate(['name' => 'Manager Produksi Agro', 'email' => 'manager.prod.agro@satoria.com', 'password' => Hash::make('password')]);
        $managerProdAgro->profile()->updateOrCreate(['subsidiary_id' => $subsidiaries['Agro'], 'department_id' => $departments['Produksi'], 'level_id' => $levels['Manager']]);
        $managerProdAgro->assignRole('Approver');
        $managerProdAgro->assignRole('Staff');

        $staffProdAgro = User::updateOrCreate(['name' => 'Staff Produksi Agro', 'email' => 'staff.prod.agro@satoria.com', 'password' => Hash::make('password')]);
        $staffProdAgro->profile()->updateOrCreate(['subsidiary_id' => $subsidiaries['Agro'], 'department_id' => $departments['Produksi'], 'level_id' => $levels['Staff']]);
        $staffProdAgro->assignRole('Staff');

        // Produksi untuk Aneka
        $managerProdAneka = User::updateOrCreate(['name' => 'Manager Produksi Aneka', 'email' => 'manager.prod.aneka@satoria.com', 'password' => Hash::make('password')]);
        $managerProdAneka->profile()->updateOrCreate(['subsidiary_id' => $subsidiaries['Aneka'], 'department_id' => $departments['Produksi'], 'level_id' => $levels['Manager']]);
        $managerProdAneka->assignRole('Approver');
        $managerProdAneka->assignRole('Staff');

        $staffProdAneka = User::updateOrCreate(['name' => 'Staff Produksi Aneka', 'email' => 'staff.prod.aneka@satoria.com', 'password' => Hash::make('password')]);
        $staffProdAneka->profile()->updateOrCreate(['subsidiary_id' => $subsidiaries['Aneka'], 'department_id' => $departments['Produksi'], 'level_id' => $levels['Staff']]);
        $staffProdAneka->assignRole('Staff');
    }
}