<div>
    {{-- Modal untuk Tambah & Edit Role --}}
    @if($showModal)
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
            <h3 class="text-lg font-medium mb-4">{{ $editingId ? 'Edit Nama Peran' : 'Tambah Peran Baru' }}</h3>
            <form wire:submit.prevent="save">
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama Peran</label>
                    <input wire:model="name" type="text" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required>
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" wire:click="$set('showModal', false)" class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5">Batal</button>
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Konten Utama Halaman --}}
    <div class="flex justify-between items-center mb-4">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Hak Akses</h2>
        <button wire:click="create" class="px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800">Tambah Role Baru</button>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama Peran (Role)</th>
                    <th scope="col" class="px-6 py-3">Jumlah Pengguna</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $role->name }}</td>
                    <td class="px-6 py-4">{{ $role->users()->count() }}</td>
                   <td class="px-6 py-4">
                        <div class="inline-flex rounded-md shadow-sm" role="group">
                            <a href="{{ route('admin.roles.edit', $role->id) }}" class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-blue-600 rounded-l-lg hover:bg-blue-700">Edit Izin</a>
                            <button wire:click="edit({{ $role->id }})" class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-gray-600 hover:bg-gray-700">Edit Nama</button>
                            <button
                                type="button"
                                @click="$dispatch('open-confirmation-modal', {
                                    title: 'Konfirmasi Hapus',
                                    message: 'Anda yakin ingin menghapus peran \'{{ $role->name }}\'?',
                                    confirmText: 'Ya, Hapus',
                                    color: 'red',
                                    livewireEvent: 'delete-role',
                                    livewireParams: [{{ $role->id }}]
                                })"
                                class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-red-600 rounded-r-lg hover:bg-red-700">
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-6 py-4 text-center">Tidak ada peran yang bisa dikelola.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $roles->links() }}</div>
</div>