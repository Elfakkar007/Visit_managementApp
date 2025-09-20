<div>
    {{-- MODAL UNTUK LIHAT DETAIL --}}
    @if($showDetailModal && $detailWorkflow)
    <div class="fixed inset-0 z-50 flex justify-center items-start w-full h-full bg-gray-900 bg-opacity-50 backdrop-blur-sm py-12 px-4">
        <div class="relative w-full max-w-2xl">
            <div class="relative bg-white rounded-lg shadow">
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-900">Detail Alur Approval</h3>
                    <button wire:click="$set('showDetailModal', false)" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>
                
                <div class="p-5 space-y-4 max-h-[70vh] overflow-y-auto">
                    {{-- Informasi Dasar --}}
                    <div>
                        <h4 class="font-medium text-gray-800">{{ $detailWorkflow->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $detailWorkflow->description }}</p>
                    </div>

                    {{-- Kondisi --}}
                    <div>
                        <h5 class="font-semibold text-gray-700 mt-4 border-t pt-3">Kondisi (IF)</h5>
                        <ul class="list-disc list-inside text-sm text-gray-600 pl-2">
                            @foreach($detailWorkflow->conditions as $condition)
                                <li>
                                    <span class="font-medium">{{ ucfirst($condition->condition_type) }}</span> adalah <span class="font-semibold text-gray-800">
                                    @php
                                        $modelClass = 'App\\Models\\' . ucfirst($condition->condition_type);
                                        if (class_exists($modelClass)) {
                                            $record = $modelClass::find($condition->condition_value);
                                            echo $record ? $record->name : 'ID: ' . $condition->condition_value;
                                        } elseif ($condition->condition_type === 'role') {
                                            $record = \Spatie\Permission\Models\Role::find($condition->condition_value);
                                            echo $record ? $record->name : 'ID: ' . $condition->condition_value;
                                        } else {
                                            echo 'ID: ' . $condition->condition_value;
                                        }
                                    @endphp
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Langkah Approval --}}
                    <div>
                        <h5 class="font-semibold text-gray-700 mt-4 border-t pt-3">Langkah Approval (THEN)</h5>
                        @foreach($detailWorkflow->steps->groupBy('step') as $stepNumber => $steps)
                            <div class="mt-2 pl-2">
                                <p class="font-medium text-gray-800">Langkah {{ $stepNumber }}: (Tipe: {{ $steps->first()->approval_type }})</p>
                                <ul class="list-disc list-inside text-sm text-gray-600 pl-4">
                                    @foreach($steps as $step)
                                    <li>
                                        Approver: <span class="font-medium">{{ ucfirst($step->approver_type) }}</span> - <span class="font-semibold text-gray-800">
                                        @php
                                            $approverModelClass = 'App\\Models\\' . ucfirst($step->approver_type);
                                             if (class_exists($approverModelClass)) {
                                                $approverRecord = $approverModelClass::find($step->approver_id);
                                                echo $approverRecord ? $approverRecord->name : 'ID: ' . $step->approver_id;
                                            } elseif ($step->approver_type === 'role') {
                                                $approverRecord = \Spatie\Permission\Models\Role::find($step->approver_id);
                                                echo $approverRecord ? $approverRecord->name : 'ID: ' . $step->approver_id;
                                            } else {
                                                echo 'ID: ' . $step->approver_id;
                                            }
                                        @endphp
                                         @if($step->approver_type !== 'user')
                                            | Cakupan: <span class="font-semibold">{{ ucfirst($step->scope) }}</span>
                                        @endif
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="flex items-center justify-end p-4 border-t">
                    <button wire:click="$set('showDetailModal', false)" type="button" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    {{-- MODAL UNTUK CREATE & EDIT WORKFLOW --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex justify-center items-start w-full h-full bg-gray-900 bg-opacity-50 backdrop-blur-sm py-12 px-4">
        <div class="relative w-full max-w-4xl h-full">
            <div class="relative bg-white rounded-lg shadow h-full flex flex-col">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">{{ $editingId ? 'Edit Alur Approval' : 'Buat Alur Approval Baru' }}</h3>
                    <button wire:click="$set('showModal', false)" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>
                
                <form wire:submit.prevent="save" class="flex flex-col overflow-hidden">
                    <div class="p-4 md:p-5 space-y-6 overflow-y-auto">
                        {{-- Bagian Informasi Dasar --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama Aturan</label>
                                <input wire:model="name" type="text" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" placeholder="Cth: Approval Staff Produksi" required>
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Deskripsi (Opsional)</label>
                                <input wire:model="description" type="text" id="description" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" placeholder="Penjelasan singkat aturan ini">
                            </div>
                        </div>

                        {{-- Bagian Kondisi (IF) --}}
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <h4 class="font-semibold mb-3">Kondisi (IF)</h4>
                            <p class="text-xs text-gray-500 mb-4">Aturan ini akan berlaku jika SEMUA kondisi di bawah ini terpenuhi.</p>
                            <div class="space-y-3">
                                @foreach($conditions as $index => $condition)
                                <div class="flex items-center space-x-2">
                                    <select wire:model.live="conditions.{{ $index }}.type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5">
                                        <option value="user">Requester adalah User...</option>
                                        <option value="department">Requester Department adalah...</option>
                                        <option value="subsidiary">Requester Subsidiary adalah...</option>
                                        <option value="role">Requester Role adalah...</option>
                                        <option value="level">Requester Level adalah...</option>
                                    </select>
                                    <select wire:model.live="conditions.{{ $index }}.value" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5">
                                        <option value="">Pilih...</option>
                                        @if($condition['type'] == 'user')
                                            @foreach($allUsers as $user) <option value="{{ $user->id }}">{{ $user->name }}</option> @endforeach
                                        @elseif($condition['type'] == 'department')
                                            @foreach($allDepartments as $dept) <option value="{{ $dept->id }}">{{ $dept->name }}</option> @endforeach
                                        @elseif($condition['type'] == 'subsidiary')
                                            @foreach($allSubsidiaries as $sub) <option value="{{ $sub->id }}">{{ $sub->name }}</option> @endforeach
                                        @elseif($condition['type'] == 'role')
                                            @foreach($allRoles as $role) <option value="{{ $role->id }}">{{ $role->name }}</option> @endforeach
                                        @else {{-- Default to level --}}
                                            @foreach($allLevels as $level) <option value="{{ $level->id }}">{{ $level->name }}</option> @endforeach
                                        @endif
                                    </select>
                                    <button wire:click.prevent="removeCondition({{ $index }})" type="button" class="text-red-500 p-2">&times;</button>
                                </div>
                                @endforeach
                            </div>
                            <button wire:click.prevent="addCondition" type="button" class="mt-3 text-sm text-blue-600 hover:underline">+ Tambah Kondisi</button>
                        </div>
                        
                        {{-- Bagian Langkah Approval (THEN) --}}
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <h4 class="font-semibold mb-3">Langkah Approval (THEN)</h4>
                             @foreach($steps as $stepIndex => $step)
                             <div class="p-3 mb-3 bg-gray-50 border rounded-md">
                                 <div class="flex justify-between items-center mb-3">
                                     <span class="font-medium text-gray-700">Langkah {{ $stepIndex + 1 }}</span>
                                     <button wire:click.prevent="removeStep({{ $stepIndex }})" type="button" class="text-red-500 text-sm">Hapus Langkah</button>
                                 </div>
                                 <div class="mb-3">
                                     <label class="block text-xs font-medium text-gray-600">Tipe Approval di Langkah ini:</label>
                                     <select wire:model="steps.{{ $stepIndex }}.type" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg p-2 mt-1">
                                         <option value="serial">Berjenjang (Serial)</option>
                                         <option value="parallel">Alternatif (Paralel)</option>
                                     </select>
                                 </div>
                                 <h5 class="text-xs font-medium text-gray-600 mb-2">Approver:</h5>
                                 <div class="space-y-2">
                                     @foreach($step['approvers'] as $approverIndex => $approver)
                                     <div class="grid grid-cols-1 md:grid-cols-3 gap-2 items-center">
                                         <select wire:model.live="steps.{{ $stepIndex }}.approvers.{{ $approverIndex }}.type" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5">
                                            <option value="user">User...</option>
                                            <option value="level">Level...</option>
                                            <option value="role">Role...</option>
                                         </select>
                                         <select wire:model.live="steps.{{ $stepIndex }}.approvers.{{ $approverIndex }}.value" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5">
                                             <option value="">Pilih...</option>
                                            @if($approver['type'] == 'user')
                                                @foreach($allUsers as $user) <option value="{{ $user->id }}">{{ $user->name }}</option> @endforeach
                                            @elseif($approver['type'] == 'role')
                                                @foreach($allRoles as $role) <option value="{{ $role->id }}">{{ $role->name }}</option> @endforeach
                                            @else
                                                @foreach($allLevels as $level) <option value="{{ $level->id }}">{{ $level->name }}</option> @endforeach
                                            @endif
                                         </select>
                                        <div class="flex items-center space-x-2">
                                            {{-- HANYA TAMPILKAN DROPDOWN SCOPE JIKA APPROVER BUKAN USER SPESIFIK --}}
                                            @if($approver['type'] !== 'user')
                                                <select wire:model="steps.{{ $stepIndex }}.approvers.{{ $approverIndex }}.scope" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5">
                                                    <option value="department">Dept. Sama</option>
                                                    <option value="subsidiary">Sub. Sama</option>
                                                    <option value="global">Global</option>
                                                </select>
                                            @else
                                                {{-- Jika tipenya user, scope tidak relevan, jadi kita tampilkan placeholder --}}
                                                <input type="text" class="w-full bg-gray-100 border-gray-300 text-gray-500 text-sm rounded-lg p-2.5" value="N/A (User Spesifik)" disabled>
                                            @endif
                                            <button wire:click.prevent="removeApprover({{ $stepIndex }}, {{ $approverIndex }})" type="button" class="text-red-500 p-2">&times;</button>
                                        </div>
                                     </div>
                                     @endforeach
                                 </div>
                                 <button wire:click.prevent="addApprover({{ $stepIndex }})" type="button" class="mt-2 text-xs text-blue-600 hover:underline">+ Tambah Approver</button>
                             </div>
                             @endforeach
                            <button wire:click.prevent="addStep" type="button" class="mt-3 text-sm text-blue-600 hover:underline">+ Tambah Langkah Approval</button>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 mt-auto">
                        <button wire:click="$set('showModal', false)" type="button" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100">Batal</button>
                        <button type="submit" class="ms-3 text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- KONTEN UTAMA HALAMAN (TABEL DAFTAR WORKFLOW) --}}
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-end mb-4">
            <button wire:click="create" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                + Buat Aturan Baru
            </button>
        </div>

        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Nama Aturan</th>
                        <th class="px-6 py-3">Deskripsi</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($workflows as $workflow)
                    <tr wire:key="workflow-{{ $workflow->id }}" class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $workflow->name }}</td>
                        <td class="px-6 py-4">{{ $workflow->description }}</td>
                       <td class="px-6 py-4">
                        <div class="inline-flex rounded-md shadow-sm" role="group">
                            <button wire:click="viewDetail({{ $workflow->id }})" type="button" class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-gray-600 rounded-l-lg hover:bg-gray-700">
                                Lihat Detail
                            </button>
                            <button wire:click="edit({{ $workflow->id }})" type="button" class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                Edit
                            </button>
                            <button wire:click="delete({{ $workflow->id }})" wire:confirm="Anda yakin ingin menghapus aturan ini?" type="button" class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-red-600 rounded-r-lg hover:bg-red-700">
                                Hapus
                            </button>
                        </div>
                    </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-4 text-center">Belum ada alur approval yang dibuat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $workflows->links() }}</div>
    </div>
</div>