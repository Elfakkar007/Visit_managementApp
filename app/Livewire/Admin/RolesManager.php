<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;

class RolesManager extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editingId = null;
    public $name;

    public function create()
    {
        $this->reset(['name', 'editingId']);
        $this->showModal = true;
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        if ($role->name === 'Admin') return; // Mencegah role Admin diedit namanya

        $this->editingId = $id;
        $this->name = $role->name;
        $this->showModal = true;
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|unique:roles,name'
        ];
        // Jika sedang edit, abaikan nama yang sedang diedit dari aturan unique
        if ($this->editingId) {
            $rules['name'] .= ',' . $this->editingId;
        }

        $this->validate($rules);

        Role::updateOrCreate(
            ['id' => $this->editingId],
            ['name' => $this->name, 'guard_name' => 'web']
        );

        $this->showModal = false;
        $this->dispatch('show-toast', type: 'success', message: 'Peran berhasil disimpan.');
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);
        if ($role->name === 'Admin' || $role->users()->count() > 0) {
            $this->dispatch('show-toast', type: 'error', message: 'Peran Admin atau peran yang masih digunakan tidak dapat dihapus.');
            return;
        }

        $role->delete();
        $this->dispatch('show-toast', type: 'success', message: 'Peran berhasil dihapus.');
    }

    public function render()
    {
        $roles = Role::where('name', '!=', 'Admin')->orderBy('name')->paginate(10);
        return view('livewire.admin.roles-manager', [
            'roles' => $roles
        ]);
    }
}