<div>
    {{-- ▼ KONDISI @if SEKARANG MEMBUNGKUS SEMUA YANG BERHUBUNGAN DENGAN FILTER ▼ --}}
    @if($mode === 'monitor')
        @persist('filters')
        {{-- Tombol Export --}}
        <div class="p-4 bg-white flex justify-end">
            <a href="{{ route('requests.export', request()->query()) }}" 
               class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                Export to Excel
            </a>
        </div>

        {{-- Form Filter --}}
        <div class="p-6 bg-white border-b border-gray-200 rounded-t-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label for="filterUser" class="block text-sm font-medium text-gray-700">Nama Pemohon</label>
                    <select wire:model.live="filterUser" id="filterUser" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Semua</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="filterDepartment" class="block text-sm font-medium text-gray-700">Departemen</label>
                    <select wire:model.live="filterDepartment" id="filterDepartment" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Semua</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="filterSubsidiary" class="block text-sm font-medium text-gray-700">Subsidiary</label>
                    <select wire:model.live="filterSubsidiary" id="filterSubsidiary" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Semua</option>
                        @foreach($subsidiaries as $subsidiary)
                            <option value="{{ $subsidiary->id }}">{{ $subsidiary->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="filterStatus" class="block text-sm font-medium text-gray-700">Status</label>
                    <select wire:model.live="filterStatus" id="filterStatus" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Semua</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="filterDate" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                    <input wire:model.live="filterDate" type="date" id="filterDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
            </div>
        </div>
        @endpersist

    @endif
    {{-- ▲ AKHIR DARI BLOK KONDISI ▲ --}}

    {{-- Tabel Hasil (selalu tampil) --}}
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-b-lg">
        @include('visit_requests._table', ['requests' => $requests])
        <div class="p-4">
            {{ $requests->links() }}
        </div>
    </div>
</div>