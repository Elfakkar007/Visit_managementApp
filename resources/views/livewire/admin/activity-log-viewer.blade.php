<div>
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="search" class="block text-xs font-medium text-gray-600 mb-1">Cari Aktivitas / Aktor</label>
                <input wire:model.live.debounce.300ms="search" id="search" type="text" placeholder="Cari..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
            <div>
                <label for="filterUser" class="block text-xs font-medium text-gray-600 mb-1">Filter Berdasarkan Aktor</label>
                <select wire:model.live="filterUser" id="filterUser" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Semua Pengguna</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Deskripsi</th>
                    <th scope="col" class="px-6 py-3">Aktor</th>
                    <th scope="col" class="px-6 py-3">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $log->description }}</td>
                    <td class="px-6 py-4">{{ $log->causer->name ?? 'Sistem' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $log->created_at->isoFormat('D MMM Y, HH:mm') }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-6 py-4 text-center">Tidak ada aktivitas ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $logs->links() }}</div>
</div>