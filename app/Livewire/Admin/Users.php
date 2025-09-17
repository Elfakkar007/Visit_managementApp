<?php

namespace App\Livewire\Admin;

use App\Models\Department;
use App\Models\Level;
use App\Models\Subsidiary;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission; // DITAMBAHKAN

class Users extends Component
{
    use WithPagination;

    // Properti untuk filter dan pencarian
    public $search = '';
    public $filterDepartment = '';
    public $filterRoleName = '';

    // Properti untuk modal
    public $showModal = false;
    public $showDetailModal = false;
    public $editingId = null;
    public $detailUser;

    // Properti untuk form
    public $name, $email, $password, $password_confirmation;
    public $subsidiary_id, $department_id, $level_id;
    public $assigned_roles = [];
    
    // --- PROPERTI BARU UNTUK IZIN KHUSUS ---
    public $all_permissions;
    public $assigned_direct_permissions = [];
    // ----------------------------------------

    public $departments, $roles, $levels, $subsidiaries;

    public function mount()
    {
        $this->departments = Department::orderBy('name')->get();
        $this->roles = Role::orderBy('name')->get();
        $this->levels = Level::orderBy('name')->get();
        $this->subsidiaries = Subsidiary::orderBy('name')->get();
        
        // --- DITAMBAHKAN: Ambil semua izin untuk form ---
        $this->all_permissions = Permission::orderBy('name')->get();
    }

    public function create()
    {
        $this->authorize('create users');
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->authorize('edit users');
        $user = User::with('profile', 'roles', 'permissions')->findOrFail($id);
        $this->editingId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->password_confirmation = '';
        
        $this->subsidiary_id = $user->profile->subsidiary_id;
        $this->department_id = $user->profile->department_id;
        $this->level_id = $user->profile->level_id;
        $this->assigned_roles = $user->getRoleNames()->toArray();

        // --- DITAMBAHKAN: Ambil izin khusus milik user ---
        $this->assigned_direct_permissions = $user->getDirectPermissions()->pluck('name')->toArray();

        $this->showModal = true;
    }

    public function save()
    {
        $this->authorize($this->editingId ? 'edit users' : 'create users');

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editingId)],
            'subsidiary_id' => 'required|exists:subsidiaries,id',
            'department_id' => 'required|exists:departments,id',
            'level_id' => 'required|exists:levels,id',
            'assigned_roles' => 'required|array|min:1',
            'assigned_roles.*' => 'exists:roles,name',
            // --- DITAMBAHKAN: Validasi untuk izin khusus ---
            'assigned_direct_permissions' => 'nullable|array',
            'assigned_direct_permissions.*' => 'exists:permissions,name',
        ];

        if (!$this->editingId) {
            $rules['password'] = 'required|string|min:6|confirmed';
        } elseif ($this->password) {
            $rules['password'] = 'nullable|string|min:6|confirmed';
        }

        $this->validate($rules);

        $userData = ['name' => $this->name, 'email' => $this->email];
        if ($this->password) { $userData['password'] = Hash::make($this->password); }
        $user = User::updateOrCreate(['id' => $this->editingId], $userData);
        $user->profile()->updateOrCreate(['user_id' => $user->id], [
            'subsidiary_id' => $this->subsidiary_id,
            'department_id' => $this->department_id,
            'level_id' => $this->level_id,
        ]);

        // --- DI-UPGRADE: Sinkronkan peran DAN izin khusus ---
        $user->syncRoles($this->assigned_roles);
        $user->syncPermissions($this->assigned_direct_permissions);

        $this->closeModal();
        session()->flash('success', $this->editingId ? 'Pengguna berhasil diperbarui.' : 'Pengguna berhasil ditambahkan.');
    }
    
    public function viewDetails($id)
    {
        $this->authorize('view users');
        $this->detailUser = User::with([
            'profile.department', 
            'roles',
            'profile.level', 
            'profile.subsidiary',
            'visitRequests' => fn($q) => $q->latest()->limit(5)
        ])->findOrFail($id);
        
        $this->showDetailModal = true;
    }

    public function delete($id)
    {
        $this->authorize('delete users');
        User::findOrFail($id)->delete();
        session()->flash('success', 'Pengguna berhasil dihapus.');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showDetailModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['editingId', 'name', 'email', 'password', 'password_confirmation', 'subsidiary_id', 'department_id', 'level_id', 'assigned_roles', 'assigned_direct_permissions']);
    }
    
    public function render()
    {
        $users = User::with('profile.department', 'roles')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterDepartment, function ($query) {
                $query->whereHas('profile', fn($q) => $q->where('department_id', $this->filterDepartment));
            })
            ->when($this->filterRoleName, function ($query) {
                $query->whereHas('roles', fn($q) => $q->where('name', $this->filterRoleName));
            })
            ->paginate(10);

        return view('livewire.admin.users', [
            'users' => $users
        ]);
    }
}   