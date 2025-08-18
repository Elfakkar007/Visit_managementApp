<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::latest()->paginate(10);
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:departments,name']);
        Department::create($request->all());
        return redirect()->route('admin.departments.index')->with('success', 'Departemen baru berhasil ditambahkan.');
    }

    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate(['name' => 'required|string|unique:departments,name,' . $department->id]);
        $department->update($request->all());
        return redirect()->route('admin.departments.index')->with('success', 'Departemen berhasil diperbarui.');
    }

    public function destroy(Department $department)
    {
        // Tambahkan validasi jika departemen sudah digunakan
        if ($department->userProfiles()->exists()) {
            return back()->with('error', 'Departemen tidak bisa dihapus karena masih digunakan oleh user.');
        }
        
        $department->delete();
        return redirect()->route('admin.departments.index')->with('success', 'Departemen berhasil dihapus.');
    }
}