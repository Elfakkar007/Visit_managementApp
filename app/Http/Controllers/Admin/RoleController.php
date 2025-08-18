<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Menampilkan daftar semua role.
     */
    public function index()
    {
        // Baris ini mengambil data dari database
        $roles = Role::latest()->paginate(10);
        
        // Baris ini MENGIRIMKAN variabel $roles ke view
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Menampilkan form untuk membuat role baru.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Menyimpan role baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:roles,name']);
        Role::create($request->all());
        return redirect()->route('admin.roles.index')->with('success', 'Role baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit role.
     */
    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Memperbarui data role.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate(['name' => 'required|string|unique:roles,name,' . $role->id]);
        $role->update($request->all());
        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    /**
     * Menghapus role.
     */
    public function destroy(Role $role)
    {
        if ($role->userProfiles()->exists()) {
            return back()->with('error', 'Role tidak bisa dihapus karena masih digunakan oleh user.');
        }
        
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil dihapus.');
    }
}