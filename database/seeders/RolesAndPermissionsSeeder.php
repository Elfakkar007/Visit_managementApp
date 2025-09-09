<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ------------------------------------------------------------------------------------
        // BAGIAN 1: Buat semua izin (Permissions)
        // ------------------------------------------------------------------------------------
        
        $permissions = [
            // Izin Manajemen User
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Izin Manajemen Master Data
            'manage master data',

            // Izin Visit Request
            'create visit requests',
            'view own visit requests',
            'view department visit requests',
            'view all visit requests',
            'approve visit requests',
            'view monitor page', // Khusus HRD
            'view approval history',

            // Izin Resepsionis
            'use scanner',
            'view guest history',
            'check-in guests',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        // ------------------------------------------------------------------------------------
        // BAGIAN 2 & 3: Buat Peran (Roles) dan berikan izin
        // ------------------------------------------------------------------------------------

        // Role Staff -> Hanya bisa membuat request untuk dirinya sendiri
        $staffRole = Role::firstOrCreate(['name' => 'Staff']);
        $staffRole->givePermissionTo([
            'create visit requests',
            'view own visit requests',
        ]);
        
        // Role Approver -> Bisa membuat request + approve request bawahannya
        $approverRole = Role::firstOrCreate(['name' => 'Approver']);
        $approverRole->givePermissionTo([
            'view department visit requests',
            'approve visit requests',
        ]);

        // Role Resepsionis -> Semua yang berhubungan dengan tamu
        $receptionistRole = Role::firstOrCreate(['name' => 'Resepsionis']);
        $receptionistRole->givePermissionTo([
            'use scanner',
            'view guest history',
            'check-in guests',
        ]);
        
        // Role HRD -> Punya akses spesial untuk memantau
        $hrdRole = Role::firstOrCreate(['name' => 'HRD']);
        $hrdRole->givePermissionTo([
            'view all visit requests',
            'view monitor page',
        ]);

        // Role Admin -> Bisa melakukan segalanya
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());
    }
}