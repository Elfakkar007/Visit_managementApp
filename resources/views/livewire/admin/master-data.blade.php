<div>
    @if (session()->has('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <!-- Modal Form -->
    @if($showModal)
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
            <h3 class="text-lg font-medium mb-4">{{ $editingId ? 'Edit' : 'Tambah' }} {{ $title }}</h3>
            <form wire:submit.prevent="save">
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama</label>
                    <input wire:model="name" type="text" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" wire:click="$set('showModal', false)" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5">
                        Batal
                    </button>
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Konten Utama dengan Tab -->
    <div class="bg-white p-6 rounded-lg shadow">
        <!-- Navigasi Tab -->
        <div class="mb-4 border-b border-gray-200">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
                <li class="me-2" role="presentation">
                    <button wire:click="switchTab('departments')" class="inline-block p-4 border-b-2 rounded-t-lg {{ $activeTab == 'departments' ? 'border-blue-600 text-blue-600' : 'hover:text-gray-600 hover:border-gray-300' }}">Departments</button>
                </li>
                <li class="me-2" role="presentation">
                    <button wire:click="switchTab('levels')" class="inline-block p-4 border-b-2 rounded-t-lg {{ $activeTab == 'levels' ? 'border-blue-600 text-blue-600' : 'hover:text-gray-600 hover:border-gray-300' }}">Levels</button>
                </li>
                <li role="presentation">
                    <button wire:click="switchTab('subsidiaries')" class="inline-block p-4 border-b-2 rounded-t-lg {{ $activeTab == 'subsidiaries' ? 'border-blue-600 text-blue-600' : 'hover:text-gray-600 hover:border-gray-300' }}">Subsidiaries</button>
                </li>
                <li role="presentation">
                    <button wire:click="switchTab('destinations')" class="inline-block p-4 border-b-2 rounded-t-lg {{ $activeTab == 'destinations' ? 'border-blue-600 text-blue-600' : 'hover:text-gray-600 hover:border-gray-300' }}">Destinations</button>
                </li>
            </ul>
        </div>

        <!-- Konten Tab -->
        <div>
            <div class="flex justify-between items-center mb-4">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari..." class="w-1/3 border-gray-300 rounded-lg shadow-sm">
                <button wire:click="create" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                   + Tambah {{ $title }}
                </button>
            </div>

            <!-- Tabel Data -->
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Nama</th>
                            <th scope="col" class="px-6 py-3"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $item)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $item->id }}</td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $item->name }}</th>
                           <td class="px-6 py-4 text-right space-x-4">
                                <button wire:click="edit({{ $item->id }})" class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Edit</button>
                                <button
                                    type="button"
                                    @click="$dispatch('open-confirmation-modal', {
                                        title: 'Konfirmasi Hapus',
                                        message: 'Anda yakin ingin menghapus data \'{{ $item->name }}\'?',
                                        confirmText: 'Ya, Hapus',
                                        color: 'red',
                                        livewireEvent: 'delete-data',
                                        livewireParams: [{{ $item->id }}]
                                    })"
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-4 text-center">Tidak ada data ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $data->links() }}</div>
        </div>
    </div>
</div>