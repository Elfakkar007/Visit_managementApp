<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subsidiary;
use Illuminate\Http\Request;

class SubsidiaryController extends Controller
{
    public function index()
    {
        $subsidiaries = Subsidiary::latest()->paginate(10);
        return view('admin.subsidiaries.index', compact('subsidiaries'));
    }

    public function create()
    {
        return view('admin.subsidiaries.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:subsidiaries,name']);
        Subsidiary::create($request->all());
        return redirect()->route('admin.subsidiaries.index')->with('success', 'Subsidiary baru berhasil ditambahkan.');
    }

    public function edit(Subsidiary $subsidiary)
    {
        return view('admin.subsidiaries.edit', compact('subsidiary'));
    }

    public function update(Request $request, Subsidiary $subsidiary)
    {
        $request->validate(['name' => 'required|string|unique:subsidiaries,name,' . $subsidiary->id]);
        $subsidiary->update($request->all());
        return redirect()->route('admin.subsidiaries.index')->with('success', 'Subsidiary berhasil diperbarui.');
    }

    public function destroy(Subsidiary $subsidiary)
    {
        if ($subsidiary->userProfiles()->exists()) {
            return back()->with('error', 'Subsidiary tidak bisa dihapus karena masih digunakan oleh user.');
        }

        $subsidiary->delete();
        return redirect()->route('admin.subsidiaries.index')->with('success', 'Subsidiary berhasil dihapus.');
    }
}