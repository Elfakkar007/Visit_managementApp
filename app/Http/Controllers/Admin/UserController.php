<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Department;
use App\Models\Level;
use App\Models\Role;
use App\Models\Subsidiary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna.
     */
    public function index()
    {
        // Eager load relasi untuk menghindari N+1 problem
        $users = User::with('profile.department', 'profile.level', 'profile.role')
            ->latest()
            ->paginate(10);
            
        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan form untuk membuat pengguna baru.
     */
    public function create()
    {
        // Ambil semua data master untuk dropdown di form
        $departments = Department::orderBy('name')->get();
        $levels = Level::all();
        $roles = Role::all();
        $subsidiaries = Subsidiary::all();
        
        return view('admin.users.create', compact('departments', 'levels', 'roles', 'subsidiaries'));
    }

    /**
     * Menyimpan pengguna baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'subsidiary_id' => ['required', 'exists:subsidiaries,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'role_id' => ['required', 'exists:roles,id'],
            'level_id' => ['required', 'exists:levels,id'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        // Gunakan transaction untuk memastikan kedua tabel berhasil terisi
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->profile()->create([
                'subsidiary_id' => $request->subsidiary_id,
                'department_id' => $request->department_id,
                'role_id' => $request->role_id,
                'level_id' => $request->level_id,
                'phone' => $request->phone,
            ]);
        });

        return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil dibuat.');
    }

    /**
     * Menampilkan form untuk mengedit pengguna.
     */
    public function edit(User $user)
    {
        // Load relasi profile untuk memastikan data tersedia
        $user->load('profile');

        $departments = Department::orderBy('name')->get();
        $levels = Level::all();
        $roles = Role::all();
        $subsidiaries = Subsidiary::all();

        return view('admin.users.edit', compact('user', 'departments', 'levels', 'roles', 'subsidiaries'));
    }

    /**
     * Memperbarui data pengguna di database.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()], // Password opsional
            'subsidiary_id' => ['required', 'exists:subsidiaries,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'role_id' => ['required', 'exists:roles,id'],
            'level_id' => ['required', 'exists:levels,id'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        DB::transaction(function () use ($request, $user) {
            // Update data di tabel 'users'
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
            ];
            // Hanya update password jika diisi
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);

            // Update atau buat data di 'user_profiles'
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'subsidiary_id' => $request->subsidiary_id,
                    'department_id' => $request->department_id,
                    'role_id' => $request->role_id,
                    'level_id' => $request->level_id,
                    'phone' => $request->phone,
                ]
            );
        });
        
        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Menghapus pengguna dari database.
     */
    public function destroy(User $user)
    {
        // Mencegah admin menghapus dirinya sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $user->delete(); // Karena ada onDelete('cascade'), profile akan ikut terhapus.

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}