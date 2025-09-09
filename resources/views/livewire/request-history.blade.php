<div>
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
                    <tr class="hover:bg-gray-50">
                        <td class="p-4">{{ $requests->firstItem() + $loop->index }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $request->user->name }}</td>
                        <td class="px-6 py-4 max-w-sm"><p class="font-normal text-gray-800 truncate">{{ $request->destination }}</p></td>
                        <td class="px-6 py-4 hidden sm:table-cell">{{ \Carbon\Carbon::parse($request->from_date)->isoFormat('D MMM YY') }} - {{ \Carbon\Carbon::parse($request->to_date)->isoFormat('D MMM YY') }}</td>
                        <td class="px-6 py-4 hidden lg:table-cell">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $statusColors[$request->status_id] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $request->status->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="inline-flex rounded-md shadow-sm" role="group">
                                <button wire:click="viewDetail({{ $request->id }})" type="button" class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-900 bg-gray-300 border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-dark focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700">
                                <svg class="w-3 h-3 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
                                    <path d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/>
                                </svg>
                                Detail
                            </button>
                                {{-- Tombol Aksi Cepat di Tabel --}}
                                @if($mode === 'approval' && $request->status->name === 'Pending')
                                    @can('approve', $request)
                                        <button wire:click.prevent="$dispatch('confirm-action', { requestId: {{ $request->id }}, action: 'reject' })" type="button" class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-red-600 border border-red-600 hover:bg-red-700 focus:z-10 focus:ring-2 focus:ring-red-500">
                                            Tolak
                                        </button>
                                        <button wire:click.prevent="$dispatch('confirm-action', { requestId: {{ $request->id }}, action: 'approve' })" type="button" class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-green-600 border border-green-600 rounded-e-lg hover:bg-green-700 focus:z-10 focus:ring-2 focus:ring-green-500">
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