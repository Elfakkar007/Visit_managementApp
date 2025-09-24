<?php

namespace App\Livewire\Admin;

use App\Models\ApprovalWorkflow;
use App\Models\ApprovalWorkflowCondition;
use App\Models\ApprovalWorkflowStep;
use App\Models\Department;
use App\Models\Level;
use App\Models\Subsidiary;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\On;

class ApprovalWorkflowManager extends Component
{
    use WithPagination;

    // Properti untuk Modal
    public $showModal = false;
    public $editingId = null;
    public $showDetailModal = false;
    public $detailWorkflow;

    // Properti Form
    public $name;
    public $description;
    public $conditions = [];
    public $steps = [];

    // Properti Data Master
    public $allLevels, $allRoles, $allUsers, $allDepartments, $allSubsidiaries;

    // Listener untuk refresh
    protected $listeners = ['workflowSaved' => '$refresh'];

    public function mount()
    {
        $this->allLevels = Level::orderBy('name')->get();
        $this->allRoles = Role::orderBy('name')->get();
        $this->allUsers = User::orderBy('name')->get(['id', 'name']);
        $this->allDepartments = Department::orderBy('name')->get();
        $this->allSubsidiaries = Subsidiary::orderBy('name')->get();
    }

    private function resetForm()
    {
        $this->reset(['editingId', 'name', 'description', 'conditions', 'steps']);
    }

    public function create()
    {
        $this->resetForm();
        // Menambahkan baris default HANYA untuk mode create
        $this->conditions[] = ['type' => 'level', 'value' => ''];
        $this->steps[] = ['type' => 'serial', 'approvers' => [['type' => 'level', 'value' => '', 'scope' => 'department']]];
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetForm(); // Dikosongkan dulu agar tidak ada data sisa
        $workflow = ApprovalWorkflow::with('conditions', 'steps')->findOrFail($id);
        
        $this->editingId = $id;
        $this->name = $workflow->name;
        $this->description = $workflow->description;

        // Memuat data kondisi yang ada
        foreach ($workflow->conditions as $condition) {
            $this->conditions[] = ['type' => $condition->condition_type, 'value' => $condition->condition_value];
        }

        // Memuat data langkah dan approver yang ada
        $formattedSteps = [];
        foreach ($workflow->steps as $step) {
            $formattedSteps[$step->step][$step->approval_type][] = [
                'type' => $step->approver_type, 
                'value' => $step->approver_id, 
                'scope' => $step->scope // <-- PERBAIKAN: Memuat data scope
            ];
        }

        foreach ($formattedSteps as $stepData) {
            foreach ($stepData as $approvalType => $approvers) {
                $this->steps[] = ['type' => $approvalType, 'approvers' => $approvers];
            }
        }

        $this->showModal = true;
    }

    public function viewDetail($id)
    {
        $this->detailWorkflow = ApprovalWorkflow::with('conditions', 'steps')->findOrFail($id);
        $this->showDetailModal = true;
    }

    // Fungsi dinamis untuk form (add/remove)
    public function addCondition() { $this->conditions[] = ['type' => 'level', 'value' => '']; }
    public function removeCondition($index) { unset($this->conditions[$index]); $this->conditions = array_values($this->conditions); }
    public function addStep() { $this->steps[] = ['type' => 'serial', 'approvers' => [['type' => 'level', 'value' => '', 'scope' => 'department']]]; }
    public function removeStep($index) { unset($this->steps[$index]); $this->steps = array_values($this->steps); }
    public function addApprover($stepIndex) { $this->steps[$stepIndex]['approvers'][] = ['type' => 'level', 'value' => '', 'scope' => 'department']; }
    public function removeApprover($stepIndex, $approverIndex) { unset($this->steps[$stepIndex]['approvers'][$approverIndex]); $this->steps[$stepIndex]['approvers'] = array_values($this->steps[$stepIndex]['approvers']); }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'conditions' => 'required|array|min:1',
            'conditions.*.value' => 'required',
            'steps' => 'required|array|min:1',
            'steps.*.approvers' => 'required|array|min:1',
            'steps.*.approvers.*.value' => 'required',
            'steps.*.approvers.*.scope' => 'required|in:department,subsidiary,global', // <-- PERBAIKAN: Validasi untuk scope
        ]);

        DB::transaction(function () {
            $workflow = ApprovalWorkflow::updateOrCreate(
                ['id' => $this->editingId],
                ['name' => $this->name, 'description' => $this->description]
            );

            $workflow->conditions()->delete();
            $workflow->steps()->delete();

            foreach ($this->conditions as $condition) {
                $workflow->conditions()->create([
                    'condition_type' => $condition['type'],
                    'condition_id' => $condition['value'],
                    'condition_value' => $condition['value'],
                ]);
            }

            foreach ($this->steps as $index => $step) {
                foreach ($step['approvers'] as $approver) {
                    $workflow->steps()->create([
                        'step' => $index + 1,
                        'approval_type' => $step['type'],
                        'approver_type' => $approver['type'],
                        'approver_id' => $approver['value'],
                        'scope' => $approver['scope'], // <-- PERBAIKAN: Menyimpan data scope
                    ]);
                }
            }
        });

        $this->showModal = false;
       $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Alur approval berhasil disimpan.'
        ]);




        $this->dispatch('workflowSaved');
    }
    
     #[On('delete-workflow')]
    public function delete($requestId)
    {
        ApprovalWorkflow::findOrFail($requestId)->delete();
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Alur approval berhasil dihapus.'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.approval-workflow-manager', [
            'workflows' => ApprovalWorkflow::withCount('steps')->latest()->paginate(10)
        ]);
    }
}