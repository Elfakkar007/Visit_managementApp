<div>
    {{-- Modal Detail Flowbite --}}
    @if($selectedVisit)
    <div class="fixed inset-0 z-50 flex justify-center items-center w-full h-full bg-gray-900 bg-opacity-50">
        <div class="relative p-4 w-full max-w-2xl h-auto">
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Detail Kunjungan Tamu
                    </h3>
                    <button wire:click="closeModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                        <span class="sr-only">Tutup modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Data Tamu --}}
                    <div>
                        <h4 class="font-semibold text-gray-700 border-b pb-2 mb-3">Data Tamu</h4>
                        <dl class="space-y-3 text-sm">
                            <div><dt class="text-gray-500 font-medium">Nama</dt><dd class="font-semibold text-gray-900">{{ $selectedVisit->guest->name }}</dd></div>
                            <div><dt class="text-gray-500 font-medium">Perusahaan</dt><dd class="text-gray-800">{{ $selectedVisit->guest->company }}</dd></div>
                            <div><dt class="text-gray-500 font-medium">Telepon</dt><dd class="text-gray-800">{{ $selectedVisit->guest->phone }}</dd></div>
                            <div><dt class="text-gray-500 font-medium">Foto KTP</dt><dd><img src="{{ route('receptionist.showKtpImage', $selectedVisit->id) }}" class="mt-1 rounded border max-h-40"></dd></div>
                        </dl>
                    </div>
                    {{-- Detail Kunjungan --}}
                    <div>
                        <h4 class="font-semibold text-gray-700 border-b pb-2 mb-3">Detail Kunjungan</h4>
                        <dl class="space-y-3 text-sm">
                            <div><dt class="text-gray-500 font-medium">Tujuan</dt><dd class="font-semibold text-gray-900">{{ $selectedVisit->visit_destination ?? '-' }}</dd></div>
                            <div><dt class="text-gray-500 font-medium">Status</dt><dd class="text-gray-800">{{ ucwords(str_replace('_', ' ', $selectedVisit->status)) }}</dd></div>
                            <div><dt class="text-gray-500 font-medium">Check-in</dt><dd class="text-gray-800">{{ $selectedVisit->time_in ? $selectedVisit->time_in->format('d M Y, H:i') : '-' }} oleh {{ $selectedVisit->checkedInBy->name ?? '' }}</dd></div>
                            <div><dt class="text-gray-500 font-medium">Check-out</dt><dd class="text-gray-800">{{ $selectedVisit->time_out ? $selectedVisit->time_out->format('d M Y, H:i') : '-' }} oleh {{ $selectedVisit->checkedOutBy->name ?? '' }}</dd></div>
                        </dl>
                    </div>
                </div>
                 <!-- Modal footer -->
                <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
                    <button wire:click="closeModal" type="button" class="text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Filter dan Tombol Export (Responsif) --}}
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
        <div class="flex flex-col md:flex-row items-end space-y-3 md:space-y-0 md:space-x-4">
            {{-- Grup Filter --}}
            <div class="w-full md:flex-1 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                    <label for="searchName" class="block text-xs font-medium text-gray-600 mb-1">Nama Tamu</label>
                    <input wire:model.live.debounce.300ms="searchName" id="searchName" type="text" placeholder="Cari nama..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>
                <div>
                    <label for="searchCompany" class="block text-xs font-medium text-gray-600 mb-1">Perusahaan</label>
                    <input wire:model.live.debounce.300ms="searchCompany" id="searchCompany" type="text" placeholder="Cari perusahaan..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>
                <div>
                    <label for="searchDate" class="block text-xs font-medium text-gray-600 mb-1">Tanggal</label>
                    <input wire:model.live="searchDate" id="searchDate" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>
            </div>
            
            {{-- Tombol Export --}}
            <div class="w-full md:w-auto flex-shrink-0">
                <button wire:click="exportExcel" class="w-full md:w-auto inline-flex items-center justify-center bg-green-600 text-white px-4 py-2.5 rounded-lg hover:bg-green-700 text-sm font-medium">
                    Export Excel
                </button>
            </div>
        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">Tamu</th>
                            <th class="px-6 py-3 hidden sm:table-cell">Tujuan</th>
                            <th class="px-6 py-3 hidden md:table-cell">Yang Dituju</th>
                            <th class="px-6 py-3">Waktu Kunjungan</th>
                            <th class="px-6 py-3 hidden md:table-cell">Status</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($visits as $visit)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $visit->guest->name }}</div>
                                <div class="text-sm text-gray-500">{{ $visit->guest->company }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 hidden sm:table-cell">{{ $visit->visit_destination ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">{{ $visit->destination_person_name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div><span class="font-medium">In:</span> {{ $visit->time_in ? $visit->time_in->format('d M Y, H:i') : '-' }}</div>
                                <div><span class="font-medium">Out:</span> {{ $visit->time_out ? $visit->time_out->format('d M Y, H:i') : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($visit->status == 'checked_in') bg-blue-100 text-blue-800
                                    @elseif($visit->status == 'checked_out') bg-gray-100 text-gray-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucwords(str_replace('_', ' ', $visit->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button wire:click="viewDetail({{ $visit->id }})" class="text-indigo-600 hover:text-indigo-900">Lihat Detail</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada riwayat kunjungan yang cocok dengan filter.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $visits->links() }}</div>
        </div>
    </div>
</div>

