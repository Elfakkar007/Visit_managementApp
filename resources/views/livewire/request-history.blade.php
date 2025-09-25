<div>
<div>
    @if(in_array($mode, ['monitor', 'admin']))
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6 space-y-4">
        {{-- BARIS PERTAMA: 4 FILTER UTAMA --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="filterUser" class="block text-xs font-medium text-gray-600 mb-1">Pemohon</label>
                <select wire:model.live="filterUser" id="filterUser" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Semua</option>
                    @foreach($users as $user) <option value="{{ $user->id }}">{{ $user->name }}</option> @endforeach
                </select>
            </div>
            <div>
                <label for="filterDepartment" class="block text-xs font-medium text-gray-600 mb-1">Departemen</label>
                <select wire:model.live="filterDepartment" id="filterDepartment" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Semua</option>
                    @foreach($departments as $department) <option value="{{ $department->id }}">{{ $department->name }}</option> @endforeach
                </select>
            </div>
            <div>
                <label for="filterSubsidiary" class="block text-xs font-medium text-gray-600 mb-1">Subsidiary</label>
                <select wire:model.live="filterSubsidiary" id="filterSubsidiary" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Semua</option>
                    @foreach($subsidiaries as $subsidiary) <option value="{{ $subsidiary->id }}">{{ $subsidiary->name }}</option> @endforeach
                </select>
            </div>
            <div>
                <label for="filterStatus" class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                <select wire:model.live="filterStatus" id="filterStatus" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Semua</option>
                    @foreach($all_statuses as $status) <option value="{{ $status->id }}">{{ $status->name }}</option> @endforeach
                </select>
            </div>
        </div>

        {{-- BARIS KEDUA: 3 FILTER TANGGAL & TOMBOL EXPORT --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="filterYear" class="block text-xs font-medium text-gray-600 mb-1">Tahun</label>
                <select wire:model.live="filterYear" id="filterYear" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Semua</option>
                    @foreach($years as $year) <option value="{{ $year }}">{{ $year }}</option> @endforeach
                </select>
            </div>
            <div>
                <label for="filterMonth" class="block text-xs font-medium text-gray-600 mb-1">Bulan</label>
                <select wire:model.live="filterMonth" id="filterMonth" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Semua</option>
                    @foreach($months as $num => $name) <option value="{{ $num }}">{{ $name }}</option> @endforeach
                </select>
            </div>
            <div>
                <label for="filterDate" class="block text-xs font-medium text-gray-600 mb-1">Tanggal Spesifik</label>
                <input wire:model.live="filterDate" id="filterDate" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
            {{-- Tombol Export dipindahkan ke kolom terakhir agar sejajar --}}
            <div class="flex items-end">
                <button wire:click="exportExcel" class="w-full inline-flex items-center justify-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL DETAIL DENGAN AKSI APPROVE/REJECT --}}
    @if ($showDetailModal && $selectedRequest)
    <div class="fixed inset-0 z-50 flex justify-center items-center w-full h-full bg-black bg-opacity-50 backdrop-blur-sm">
        <div class="relative p-4 w-full max-w-2xl h-auto">
            <div class="relative bg-white rounded-lg shadow">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">Detail Permintaan Kunjungan</h3>
                    <button wire:click="closeModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                        <span class="sr-only">Tutup modal</span>
                    </button>
                </div>
                <div class="p-4 md:p-5 space-y-4 max-h-[60vh] overflow-y-auto">
                    @include('visit_requests.partials.detail-content', ['request' => $selectedRequest])
                </div>
                <div class="p-4 md:p-5 space-y-4 border-t border-gray-200 rounded-b">
                    {{-- Hanya tampilkan form aksi jika status masih Pending --}}
                    @if($selectedRequest->status->name === 'Pending')
                        @can('approve', $selectedRequest)
                            <div>
                                <label for="approver_note" class="block mb-2 text-sm font-medium text-gray-900">Catatan (Opsional)</label>
                                <textarea wire:model.lazy="approverNote" id="approver_note" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Berikan catatan jika perlu..."></textarea>
                            </div>
                            <div class="flex items-center justify-end space-x-4">
                                <button wire:click="reject({{ $selectedRequest->id }})" wire:loading.attr="disabled" type="button" class="py-2.5 px-5 text-sm font-medium text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300">
                                    Tolak
                                </button>
                                <button wire:click="approve({{ $selectedRequest->id }})" wire:loading.attr="disabled" type="button" class="py-2.5 px-5 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                    Setujui
                                </button>
                            </div>
                        @endcan
                    @else
                         <div class="flex justify-end">
                             <button wire:click="closeModal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10">Tutup</button>
                         </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Tabel Data Responsif --}}
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="p-4">No.</th>
                    <th scope="col" class="px-6 py-3">Pemohon</th>
                    <th scope="col" class="px-6 py-3">Tujuan</th>
                    <th scope="col" class="px-6 py-3 hidden sm:table-cell">Tanggal</th>
                    <th scope="col" class="px-6 py-3 hidden lg:table-cell">Status</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($requests as $request)
                    <tr wire:key="request-{{ $request->id }}" class="hover:bg-gray-50">
                        <td class="p-4">{{ $requests->firstItem() + $loop->index }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $request->user?->name ?? 'Pengguna Telah Dihapus' }}</td>
                        <td class="px-6 py-4 max-w-sm"><p class="font-normal text-gray-800 truncate">{{ $request->destination }}</p></td>
                        <td class="px-6 py-4 hidden sm:table-cell">{{ \Carbon\Carbon::parse($request->from_date)->isoFormat('dddd, D MMMM YYYY, HH:mm') }} - {{ \Carbon\Carbon::parse($request->to_date)->isoFormat('dddd, D MMMM YYYY, HH:mm') }}</td>
                        <td class="px-6 py-4 hidden lg:table-cell">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $statusColors[$request->status_id] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $request->status->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="inline-flex rounded-md shadow-sm" role="group">
                                <button wire:click="viewDetail({{ $request->id }})" type="button" class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-900 bg-gray-300 rounded-s-lg hover:bg-gray-100">
                                    Detail
                                </button>
                                @if($mode === 'approval' && $request->status->name === 'Pending')
                                    @can('approve', $request)
                                        <button type="button" 
                                            @click="$dispatch('open-confirmation-modal', {
                                                title: 'Konfirmasi Penolakan',
                                                message: 'Anda akan menolak permintaan ini.',
                                                confirmText: 'Ya, Tolak',
                                                color: 'red',
                                                livewireEvent: 'reject-request',
                                                livewireParams: [{{ $request->id }}]
                                            })"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-red-600 hover:bg-red-700">
                                            Tolak
                                        </button>
                                        <button type="button" 
                                            @click="$dispatch('open-confirmation-modal', {
                                                title: 'Konfirmasi Persetujuan',
                                                message: 'Anda akan menyetujui permintaan ini.',
                                                confirmText: 'Ya, Setujui',
                                                color: 'green',
                                                livewireEvent: 'approve-request',
                                                livewireParams: [{{ $request->id }}]
                                            })"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-green-600 rounded-e-lg hover:bg-green-700">
                                            Setujui
                                        </button>
                                    @endcan
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-8 text-center text-gray-500">Tidak ada data permintaan untuk ditampilkan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="p-4 border-t border-gray-200">
         {{ $requests->links() }}
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('confirm-action', (event) => {
                const { requestId, action } = event;
                const actionText = action === 'approve' ? 'menyetujui' : 'menolak';

                Swal.fire({
                    title: 'Anda Yakin?',
                    text: `Anda akan ${actionText} permintaan ini.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: `Ya, ${actionText}!`,
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Memanggil metode di komponen Livewire
                        Livewire.dispatch(`${action}-request`, [requestId]);
                    }
                })
            });
        });
    </script>
    @endpush
</div>
</div>
