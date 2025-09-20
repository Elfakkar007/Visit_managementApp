<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // --- 1. DEFINISI SEMUA IZIN (Permissions) ---
        $permissions = [
            'view users', 'create users', 'edit users', 'delete users',
            'manage master data',
            'create visit requests',      // Kemampuan untuk MEMBUAT request
            'approve visit requests',     // Kemampuan untuk APPROVE request
            'view monitor page',        // Kemampuan untuk MEMANTAU semua request
            'use scanner', 'view guest history',
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // --- 2. BUAT PERAN BERBASIS KEMAMPUAN (Capability-Based Roles) ---

        // Role 'Staff': Hanya bisa membuat request
        Role::firstOrCreate(['name' => 'Staff'])->syncPermissions(['create visit requests']);

        // Role 'Approver': Hanya bisa melakukan approval
        Role::firstOrCreate(['name' => 'Approver'])->syncPermissions(['approve visit requests']);

        // Role 'HRD': Hanya bisa memantau semua request
        Role::firstOrCreate(['name' => 'HRD'])->syncPermissions(['view monitor page']);

        // Role 'Resepsionis': Terkait manajemen tamu
        Role::firstOrCreate(['name' => 'Resepsionis'])->syncPermissions(['use scanner', 'view guest history']);

        // Role 'Admin': Bisa segalanya
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all()); // Beri semua izin yang ada
    }
}