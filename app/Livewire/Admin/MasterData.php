<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Department;
use App\Models\Level;
use App\Models\Role;
use App\Models\Subsidiary;
use Livewire\WithPagination;

class MasterData extends Component
{
    use WithPagination;

    public $activeTab = 'departments';
    public $name;
    public $editingId = null;
    public $showModal = false;
    public $search = '';

    protected $models = [
        'departments' => Department::class,
        'levels' => Level::class,
        'roles' => Role::class,
        'subsidiaries' => Subsidiary::class,
    ];

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage(); // Reset paginasi saat ganti tab
    }

    protected function getModel()
    {
        return $this->models[$this->activeTab];
    }

    public function create()
    {
        $this->reset(['name', 'editingId']);
        $this->showModal = true;
    }

    public function edit($id)
    {
        $modelClass = $this->getModel();
        $record = $modelClass::findOrFail($id);
        $this->editingId = $id;
        $this->name = $record->name;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate(['name' => 'required|string|min:3|max:255']);

        $modelClass = $this->getModel();
        
        $modelClass::updateOrCreate(
            ['id' => $this->editingId],
            ['name' => $this->name]
        );

        session()->flash('success', 'Data berhasil disimpan.');
        $this->showModal = false;
    }

    public function delete($id)
    {
        $modelClass = $this->getModel();
        $modelClass::findOrFail($id)->delete();
        session()->flash('success', 'Data berhasil dihapus.');
    }

    public function render()
    {
        $modelClass = $this->getModel();
        $data = $modelClass::where('name', 'like', '%' . $this->search . '%')->paginate(10);
        
        return view('livewire.admin.master-data', [
            'data' => $data,
            'title' => ucwords(str_replace('_', ' ', $this->activeTab))
        ]);
    }
}
