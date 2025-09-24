<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Department;
use App\Models\Level;
use App\Models\Subsidiary;
use App\Models\Destination;
use Livewire\WithPagination;
use Livewire\Attributes\On;

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
        'subsidiaries' => Subsidiary::class,
        'destinations' => Destination::class,
    ];

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
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

   #[On('delete-data')]
    public function delete($requestId) // Terima parameter sebagai 'requestId'
    {
        $modelClass = $this->getModel();
        try {
            // Gunakan variabel 'requestId' yang sama di sini
            $modelClass::findOrFail($requestId)->delete();
            $this->dispatch('show-toast', type: 'success', message: 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Gagal! Data ini masih digunakan di tempat lain.');
        }
    }

    public function render()
    {
        $modelClass = $this->getModel();
        $data = $modelClass::where('name', 'ilike', '%' . $this->search . '%')->paginate(10);
        
        return view('livewire.admin.master-data', [
            'data' => $data,
            'title' => ucwords(str_replace('_', ' ', $this->activeTab))
        ]);
    }
}