<div>
    {{-- Modal Detail --}}
    @if($selectedVisit)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="text-xl font-semibold text-gray-900">Detail Kunjungan Tamu</h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Data Tamu --}}
                <div>
                    <h4 class="font-semibold text-gray-700 border-b pb-2 mb-3">Data Tamu</h4>
                    <dl class="space-y-2 text-sm">
                        <div><dt class="text-gray-500">Nama</dt><dd class="font-medium">{{ $selectedVisit->guest->name }}</dd></div>
                        <div><dt class="text-gray-500">Perusahaan</dt><dd>{{ $selectedVisit->guest->company }}</dd></div>
                        <div><dt class="text-gray-500">Telepon</dt><dd>{{ $selectedVisit->guest->phone }}</dd></div>
                        <div><dt class="text-gray-500">Foto KTP</dt><dd><img src="{{ route('receptionist.showKtpImage', $selectedVisit->id) }}" class="mt-1 rounded border max-h-40"></dd></div>
                    </dl>
                </div>
                {{-- Detail Kunjungan --}}
                <div>
                    <h4 class="font-semibold text-gray-700 border-b pb-2 mb-3">Detail Kunjungan</h4>
                    <dl class="space-y-2 text-sm">
                        <div><dt class="text-gray-500">Tujuan</dt><dd class="font-medium">{{ $selectedVisit->visit_destination ?? '-' }}</dd></div>
                        <div><dt class="text-gray-500">Status</dt><dd>{{ ucwords(str_replace('_', ' ', $selectedVisit->status)) }}</dd></div>
                        <div><dt class="text-gray-500">Check-in</dt><dd>{{ $selectedVisit->time_in ? $selectedVisit->time_in->format('d M Y, H:i') : '-' }} oleh {{ $selectedVisit->checkedInBy->name ?? '' }}</dd></div>
                        <div><dt class="text-gray-500">Check-out</dt><dd>{{ $selectedVisit->time_out ? $selectedVisit->time_out->format('d M Y, H:i') : '-' }} oleh {{ $selectedVisit->checkedOutBy->name ?? '' }}</dd></div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Filter dan Tombol Export --}}
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
    {{-- Gunakan Flexbox untuk mengatur layout --}}
    <div class="flex flex-wrap items-end gap-4">
        {{-- Input filter akan memakan ruang yang tersedia --}}
        <div class="flex-grow">
            <label for="searchName" class="block text-xs font-medium text-gray-600">Nama Tamu</label>
            <input wire:model.live.debounce.300ms="searchName" id="searchName" type="text" placeholder="Cari nama..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">
        </div>
        <div class="flex-grow">
            <label for="searchCompany" class="block text-xs font-medium text-gray-600">Perusahaan</dabel>
            <input wire:model.live.debounce.300ms="searchCompany" id="searchCompany" type="text" placeholder="Cari perusahaan..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">
        </div>
        <div class="flex-grow">
            <label for="searchDate" class="block text-xs font-medium text-gray-600">Tanggal</label>
            <input wire:model.live="searchDate" id="searchDate" type="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">
        </div>
        
        {{-- Tombol akan menempel di kanan --}}
        <div class="flex-shrink-0">
            <button wire:click="exportExcel" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 text-sm">
                Export Excel
            </button>
        </div>
    </div>
</div>


    {{-- Tabel Data --}}
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tamu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tujuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu Check-in</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($visits as $visit)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $visit->guest->name }}</div>
                                <div class="text-sm text-gray-500">{{ $visit->guest->company }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $visit->visit_destination ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $visit->time_in ? $visit->time_in->format('d M Y, H:i') : 'Belum Check-in' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($visit->status == 'checked_in') bg-blue-100 text-blue-800
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
                                Tidak ada tamu aktif yang cocok dengan filter.
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
