<div>
    @if (session()->has('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('success') }}
        </div>
    @endif

    {{-- MODAL DETAIL & AKSI --}}
    @if ($showDetailModal && $selectedRequest)
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 z-50 flex justify-center items-center px-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
            <div class="flex justify-between items-center border-b rounded-t-lg bg-gray-50 px-6 py-4">
                <h3 class="text-xl font-semibold text-gray-800">Detail Permintaan Kunjungan</h3>
                <button wire:click="closeModal" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                </button>
            </div>
            
            <div class="p-6 max-h-[60vh] overflow-y-auto">
                {{-- Menggunakan file partial untuk menampilkan detail --}}
                @include('visit_requests.partials.detail-content', ['request' => $selectedRequest])
            </div>

            {{-- FOOTER DENGAN FORM AKSI (HANYA UNTUK APPROVER & JIKA STATUS PENDING) --}}
            @if(($mode === 'approval' || $mode === 'hrd_approval') && $selectedRequest->status->name === 'Pending')
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
                <div class="flex justify-between items-start">
                    <div class="flex-grow mr-4">
                        <label for="rejection_reason" class="text-sm font-medium text-gray-700">Catatan / Alasan Penolakan (Opsional)</label>
                        <textarea wire:model="rejection_reason" id="rejection_reason" rows="2" class="mt-1 form-input w-full text-sm" placeholder="Berikan catatan jika perlu..."></textarea>
                    </div>
                    <div class="flex-shrink-0 flex items-center space-x-3 pt-6">
                        <button wire:click="rejectRequest" type="button" class="btn-danger">Tolak</button>
                        <button wire:click="approveRequest" type="button" class="btn-success">Setujui</button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Filter (Hanya untuk monitor/admin) --}}
    @if ($mode === 'monitor' || $mode === 'admin')
    <div class="p-4 bg-white rounded-lg shadow-sm mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
            <select wire:model.live="filterUser" class="form-select">
                <option value="">Semua Pemohon</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterDepartment" class="form-select">
                <option value="">Semua Departemen</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterSubsidiary" class="form-select">
                <option value="">Semua Subsidiary</option>
                @foreach($subsidiaries as $subsidiary)
                    <option value="{{ $subsidiary->id }}">{{ $subsidiary->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterStatus" class="form-select">
                <option value="">Semua Status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                @endforeach
            </select>
            <input wire:model.live="filterDate" type="date" class="form-input">
        </div>
        <div class="mt-4 flex justify-end">
            <button wire:click="exportExcel" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                Export Excel
            </button>
        </div>
    </div>
    @endif

    {{-- Tabel Data --}}
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 text-left">Pemohon</th>
                            <th class="p-3 text-left">Departemen</th>
                            <th class="p-3 text-left">Tujuan</th>
                            <th class="p-3 text-left">Tanggal</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Approver</th>
                            <th class="p-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($requests as $request)
                        <tr>
                            <td class="p-3">
                                <div class="font-medium text-gray-900">{{ $request->user->name }}</div>
                                <div class="text-gray-500">{{ $request->user->profile->level->name ?? '' }}</div>
                            </td>
                            <td class="p-3">{{ $request->user->profile->department->name ?? '' }}</td>
                            <td class="p-3"><div class="font-medium text-gray-900">{{ $request->destination }}</div></td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($request->from_date)->isoFormat('D MMM YYYY') }} s/d {{ \Carbon\Carbon::parse($request->to_date)->isoFormat('D MMM YYYY') }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $request->status->color ?? 'bg-gray-200' }}">
                                    {{ $request->status->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="p-3">
                                <div>{{ $request->approver->name ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $request->approved_at ? \Carbon\Carbon::parse($request->approved_at)->isoFormat('D MMM YYYY, HH:mm') : '' }}</div>
                            </td>
                            <td class="p-3">
                                <button wire:click="viewDetail({{ $request->id }})" class="font-medium text-blue-600 hover:underline">
                                    Lihat Detail
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-3 text-center text-gray-500">Tidak ada data ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</div>
