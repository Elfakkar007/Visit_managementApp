<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role; 
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil role selain Admin untuk mencegah Admin dihapus
        $roles = Role::where('name', '!=', 'Admin')->orderBy('name')->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name'
        ]);

        Role::create(['name' => $validated['name']]);

        return redirect()->route('admin.roles.index')->with('success', 'Peran baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        // Mencegah role 'Admin' diedit
        if ($role->name === 'Admin') {
            return redirect()->route('admin.roles.index')->with('error', 'Peran Admin tidak dapat diedit.');
        }
        $permissions = Permission::orderBy('name')->get();
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        // Mencegah role 'Admin' diupdate
        if ($role->name === 'Admin') {
            return redirect()->route('admin.roles.index')->with('error', 'Peran Admin tidak dapat diupdate.');
        }

        $validated = $request->validate([
            'permissions' => 'nullable|array'
        ]);
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Izin untuk peran ' . $role->name . ' berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Mencegah role 'Admin' dihapus
        if ($role->name === 'Admin' || $role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')->with('error', 'Peran tidak dapat dihapus karena masih digunakan atau merupakan peran Admin.');
        }
        
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Peran berhasil dihapus.');
    }
}