<div>
    {{-- MODAL DETAIL DENGAN DESAIN BARU --}}
    @if ($showDetailModal && $selectedRequest)
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 z-50 flex justify-center items-center px-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
            {{-- Header Modal dengan Warna Baru --}}
            <div class="flex justify-between items-center border-b rounded-t-lg bg-gray-50 px-6 py-4">
                <h3 class="text-xl font-semibold text-gray-800">Detail Permintaan Kunjungan</h3>
                <button wire:click="closeModal" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            
            <div class="p-6 max-h-[70vh] overflow-y-auto pr-4">
                {{-- Informasi Pemohon --}}
                <div class="mb-6">
                    <h4 class="font-semibold text-gray-700 border-b pb-2 mb-3">Informasi Pemohon</h4>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-4 text-sm">
                        <div>
                            <dt class="font-medium text-gray-500">Nama Pemohon</dt>
                            <dd class="mt-1 text-gray-900">{{ $selectedRequest->user->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Subsidiary</dt>
                            <dd class="mt-1 text-gray-900">{{ $selectedRequest->user->profile->subsidiary->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Departemen</dt>
                            <dd class="mt-1 text-gray-900">{{ $selectedRequest->user->profile->department->name ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Detail Perjalanan --}}
                <div class="mb-6">
                    <h4 class="font-semibold text-gray-700 border-b pb-2 mb-3">Detail Perjalanan</h4>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-4 text-sm">
                        <div class="sm:col-span-2">
                            <dt class="font-medium text-gray-500">Tujuan</dt>
                            <dd class="mt-1 text-gray-900">{{ $selectedRequest->destination ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="font-medium text-gray-500">Keperluan</dt>
                            <dd class="mt-1 text-gray-900 whitespace-pre-wrap">{{ $selectedRequest->purpose ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Tanggal Mulai</dt>
                            <dd class="mt-1 text-gray-900">{{ \Carbon\Carbon::parse($selectedRequest->from_date)->isoFormat('dddd, D MMMM YYYY') }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Tanggal Selesai</dt>
                            <dd class="mt-1 text-gray-900">{{ \Carbon\Carbon::parse($selectedRequest->to_date)->isoFormat('dddd, D MMMM YYYY') }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Status Approval --}}
                <div>
                    <h4 class="font-semibold text-gray-700 border-b pb-2 mb-3">Status Approval</h4>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-4 text-sm">
                        <div>
                            <dt class="font-medium text-gray-500">Status Saat Ini</dt>
                            <dd class="mt-1 text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($selectedRequest->status->name == 'Approved') bg-green-100 text-green-800
                                    @elseif($selectedRequest->status->name == 'Rejected') bg-red-100 text-red-800
                                    @elseif($selectedRequest->status->name == 'Cancelled') bg-gray-100 text-gray-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $selectedRequest->status->name }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Diproses oleh (Approver)</dt>
                            <dd class="mt-1 text-gray-900">{{ $selectedRequest->approver->name ?? '-' }}</dd>
                        </div>
                        {{-- Tampilkan catatan/alasan penolakan jika ada --}}
                        @if($selectedRequest->rejection_reason)
                        <div class="sm:col-span-2">
                            <dt class="font-medium text-red-600">Catatan dari Approver:</dt>
                            <dd class="mt-1 text-sm text-gray-800 bg-red-50 p-3 rounded-md">{{ $selectedRequest->rejection_reason }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            {{-- Footer Modal dengan Tombol CTA --}}
            <div class="flex justify-end items-center p-4 border-t border-gray-200 rounded-b-lg bg-gray-50">
                <button wire:click="closeModal" type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5">Tutup</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Tabel Data (Tidak ada perubahan di sini) --}}
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Tujuan</th>
                    <th scope="col" class="px-6 py-3">Tanggal</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Approver</th>
                    <th scope="col" class="px-6 py-3"><span class="sr-only">Aksi</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($requests as $request)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ $request->destination }}
                    </th>
                    <td class="px-6 py-4">
                        {{ \Carbon\Carbon::parse($request->from_date)->isoFormat('D MMM YYYY') }} - {{ \Carbon\Carbon::parse($request->to_date)->isoFormat('D MMM YYYY') }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($request->status->name == 'Approved') bg-green-100 text-green-800
                            @elseif($request->status->name == 'Rejected') bg-red-100 text-red-800
                            @elseif($request->status->name == 'Cancelled') bg-gray-100 text-gray-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $request->status->name }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        {{ $request->approver->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button wire:click="viewDetail({{ $request->id }})" class="font-medium text-blue-600 hover:underline">Lihat Detail</button>
                        @if($request->status->name === 'Pending')
                        <form action="{{ route('requests.cancel', $request) }}" method="POST" class="inline-block ml-4">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="font-medium text-red-600 hover:underline">Batalkan</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center">Anda belum memiliki riwayat permintaan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $requests->links() }}
    </div>
</div>
