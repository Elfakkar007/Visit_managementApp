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
use Spatie\Permission\Models\Permission;

class Users extends Component
{
    use WithPagination;



    // Properti untuk modal
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $editingId = null;
    public $userToDelete; 
    public $showDetailModal = false;
    public $detailUser;

    public $name, $email, $password, $password_confirmation;
    public $subsidiary_id, $department_id, $level_id;
    public $assigned_roles = [];
    public $all_permissions;
    public $assigned_direct_permissions = [];
    
    public $departments, $roles, $levels, $subsidiaries;
    public $search = '';
    public $filterDepartment = '';
    public $filterRoleName = '';


    public function mount()
    {
        $this->departments = Department::orderBy('name')->get();
        $this->roles = Role::where('name', '!=', 'Admin')->orderBy('name')->get();
        $this->levels = Level::orderBy('name')->get();
        $this->subsidiaries = Subsidiary::orderBy('name')->get();
        $this->all_permissions = Permission::orderBy('name')->get();
    }
    
    public function create()
    {
        $this->authorize('create users');
        $this->resetForm();
        $this->showEditModal = true;
    }
    
    public function edit($id)
    {
        $this->authorize('edit users');
        $user = User::with('profile', 'roles', 'permissions')->findOrFail($id);
        $this->editingId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        if ($user->profile) {
            $this->subsidiary_id = $user->profile->subsidiary_id;
            $this->department_id = $user->profile->department_id;
            $this->level_id = $user->profile->level_id;
        }
        $this->assigned_roles = $user->getRoleNames()->toArray();
        $this->assigned_direct_permissions = $user->getDirectPermissions()->pluck('name')->toArray();
        $this->showEditModal = true;
    }

     public function viewDetail($id)
    {
        // Eager load semua relasi yang dibutuhkan di modal detail
        $this->detailUser = User::with(['profile.subsidiary', 'profile.department', 'profile.level', 'visitRequests'])->findOrFail($id);
        $this->showDetailModal = true;
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
            'assigned_direct_permissions' => 'nullable|array',
        ];

        if (!$this->editingId || $this->password) {
            $rules['password'] = 'required|string|min:6|confirmed';
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

        $user->syncRoles($this->assigned_roles);
        $user->syncPermissions($this->assigned_direct_permissions);

        $this->closeModal();
        $this->dispatch('show-toast', type: 'success', message: $this->editingId ? 'Pengguna berhasil diperbarui.' : 'Pengguna berhasil ditambahkan.');
    }


    public function askToDelete($id)
    {
        $this->userToDelete = User::findOrFail($id);
        $this->showDeleteModal = true;
    }

    
    public function confirmDelete()
    {
        $this->authorize('delete users');
        
        if (!$this->userToDelete) {
            return;
        }

        // Langsung lakukan soft delete tanpa pengecekan.
        // Fitur SoftDeletes di model User akan menangani ini secara otomatis.
        $this->userToDelete->delete(); 
        
        $this->dispatch('show-toast', type: 'success', message: 'Pengguna berhasil dihapus.');
        
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->reset(['showEditModal', 'showDeleteModal', 'editingId', 'userToDelete', 'showDetailModal', 'detailUser']);
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'subsidiary_id', 'department_id', 'level_id', 'assigned_roles', 'assigned_direct_permissions']);
    }

    public function render()
    {
        $users = User::with('profile.department', 'roles')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterDepartment, fn($q) => $q->whereHas('profile', fn($subq) => $subq->where('department_id', $this->filterDepartment)))
            ->when($this->filterRoleName, fn($q) => $q->whereHas('roles', fn($subq) => $subq->where('name', $this->filterRoleName)))
            ->paginate(10);

        return view('livewire.admin.users', [
            'users' => $users
        ]);
    }
}