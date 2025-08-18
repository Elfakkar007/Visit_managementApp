<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index()
    {
        $levels = Level::latest()->paginate(10);
        return view('admin.levels.index', compact('levels'));
    }

    public function create()
    {
        return view('admin.levels.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:levels,name']);
        Level::create($request->all());
        return redirect()->route('admin.levels.index')->with('success', 'Level baru berhasil ditambahkan.');
    }

    public function edit(Level $level)
    {
        return view('admin.levels.edit', compact('level'));
    }

    public function update(Request $request, Level $level)
    {
        $request->validate(['name' => 'required|string|unique:levels,name,' . $level->id]);
        $level->update($request->all());
        return redirect()->route('admin.levels.index')->with('success', 'Level berhasil diperbarui.');
    }

    public function destroy(Level $level)
    {
        if ($level->userProfiles()->exists()) {
            return back()->with('error', 'Level tidak bisa dihapus karena masih digunakan oleh user.');
        }
        
        $level->delete();
        return redirect()->route('admin.levels.index')->with('success', 'Level berhasil dihapus.');
    }
}