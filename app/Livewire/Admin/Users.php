<?php

namespace App\Livewire\Admin;

use App\Models\Department;
use App\Models\Level;
use App\Models\Role;
use App\Models\Subsidiary;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Users extends Component
{
    use WithPagination;

    // Properti untuk filter dan pencarian
    public $search = '';
    public $filterDepartment = '';
    public $filterRole = '';

    // Properti untuk modal
    public $showModal = false;
    public $showDetailModal = false;
    public $editingId = null;
    public $detailUser;

    // Properti untuk form
    public $name, $email, $password, $password_confirmation;
    public $subsidiary_id, $department_id, $role_id, $level_id;

    // Properti untuk menampung data master
    public $departments, $roles, $levels, $subsidiaries;

    public function mount()
    {
        // Ambil data master sekali saja saat komponen dimuat
        $this->departments = Department::orderBy('name')->get();
        $this->roles = Role::orderBy('name')->get();
        $this->levels = Level::orderBy('name')->get();
        $this->subsidiaries = Subsidiary::orderBy('name')->get();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $user = User::with('profile')->findOrFail($id);
        $this->editingId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->password_confirmation = '';
        
        $this->subsidiary_id = $user->profile->subsidiary_id;
        $this->department_id = $user->profile->department_id;
        $this->role_id = $user->profile->role_id;
        $this->level_id = $user->profile->level_id;

        $this->showModal = true;
    }

public function save()
{
    $rules = [
        'name' => 'required|string|max:255',
        'email' => [
            'required',
            'email',
            'max:255',
            Rule::unique('users', 'email')->ignore($this->editingId),
        ],
        'subsidiary_id' => 'required|exists:subsidiaries,id',
        'department_id' => 'required|exists:departments,id',
        'role_id' => 'required|exists:roles,id',
        'level_id' => 'required|exists:levels,id',
    ];

    // Password hanya wajib saat create
    if (!$this->editingId) {
        $rules['password'] = 'required|string|min:6|confirmed';
    } elseif ($this->password) {
        $rules['password'] = 'nullable|string|min:6|confirmed';
    }

    $validated = $this->validate($rules);

    // Simpan user
    $user = User::updateOrCreate(
        ['id' => $this->editingId],
        [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password ? Hash::make($this->password) : User::find($this->editingId)?->password,
        ]
    );

    // Selalu update/buat profile
    $user->profile()->updateOrCreate(
        ['user_id' => $user->id],
        [
            'subsidiary_id' => $this->subsidiary_id,
            'department_id' => $this->department_id,
            'role_id' => $this->role_id,
            'level_id' => $this->level_id,
        ]
    );

    $this->resetForm();
    $this->showModal = false;
    session()->flash('success', $this->editingId ? 'Pengguna berhasil diperbarui.' : 'Pengguna berhasil ditambahkan.');
}
    public function viewDetails($id)
    {
        // Eager load relasi untuk efisiensi
        $this->detailUser = User::with([
            'profile.department', 
            'profile.role', 
            'profile.level', 
            'profile.subsidiary',
            'visitRequests' => function ($query) { // Ganti 'visitRequests' dengan nama relasi Anda
                $query->latest()->limit(5);
            }
        ])->findOrFail($id);
        
        $this->showDetailModal = true;
    }

    public function delete($id)
    {
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
        $this->reset(['editingId', 'name', 'email', 'password', 'password_confirmation', 'subsidiary_id', 'department_id', 'role_id', 'level_id']);
    }

    public function render()
    {
        $users = User::with('profile.department', 'profile.role')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterDepartment, function ($query) {
                $query->whereHas('profile', fn($q) => $q->where('department_id', $this->filterDepartment));
            })
            ->when($this->filterRole, function ($query) {
                $query->whereHas('profile', fn($q) => $q->where('role_id', $this->filterRole));
            })
            ->paginate(10);

        return view('livewire.admin.users', [
            'users' => $users
        ]);
    }
}
