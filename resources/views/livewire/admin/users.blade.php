<div>
    @if (session()->has('success'))
        {{-- Menggunakan Flowbite Alert Component --}}
        <div class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50" role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Info</span>
            <div>
                <span class="font-medium">Sukses!</span> {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- MODAL UNTUK CREATE & EDIT (FLOWBITE STYLE) --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex justify-center items-center w-full h-full bg-gray-900 bg-opacity-50">
        <div class="relative p-4 w-full max-w-2xl h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">
                        {{ $editingId ? 'Edit Data Pengguna' : 'Tambah Pengguna Baru' }}
                    </h3>
                    <button wire:click="closeModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                        <span class="sr-only">Tutup modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form wire:submit.prevent="save">
                    <div class="p-4 md:p-5 grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Kolom Kiri --}}
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap</label>
                                <input wire:model="name" type="text" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                                <input wire:model="email" type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div x-data="{ show: false }">
                                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" wire:model="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10" placeholder="{{ $editingId ? 'Kosongkan jika tidak diubah' : '' }}">
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 focus:outline-none" tabindex="-1">
                                        <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        <svg x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95M6.873 6.872A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.965 9.965 0 01-4.293 5.95M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" /></svg>
                                    </button>
                                </div>
                                @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Konfirmasi Password</label>
                                <input wire:model="password_confirmation" type="password" id="password_confirmation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            </div>
                        </div>
                        {{-- Kolom Kanan --}}
                        <div class="space-y-4">
                            <div>
                                <label for="subsidiary_id" class="block mb-2 text-sm font-medium text-gray-900">Subsidiary</label>
                                <select wire:model="subsidiary_id" id="subsidiary_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    <option value="">Pilih Subsidiary</option>
                                    @foreach($subsidiaries as $subsidiary)
                                        <option value="{{ $subsidiary->id }}">{{ $subsidiary->name }}</option>
                                    @endforeach
                                </select>
                                @error('subsidiary_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="department_id" class="block mb-2 text-sm font-medium text-gray-900">Departemen</label>
                                <select wire:model="department_id" id="department_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    <option value="">Pilih Departemen</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900">Peran Pengguna</label>
                                
                                {{-- Pembungkus untuk daftar checkbox --}}
                                <div class="p-3 bg-gray-50 border border-gray-300 rounded-lg max-h-48 overflow-y-auto">
                                    <div class="space-y-2">
                                        @forelse($roles as $role)
                                            <div class="flex items-center">
                                                <input wire:model="assigned_roles" type="checkbox" value="{{ $role->name }}" id="role_{{ $role->id }}" 
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                                <label for="role_{{ $role->id }}" class="ms-2 text-sm font-medium text-gray-900">
                                                    {{ $role->name }}
                                                </label>
                                            </div>
                                        @empty
                                            <p class="text-sm text-gray-500">Tidak ada peran ditemukan.</p>
                                        @endforelse
                                    </div>
                                </div>
                                @error('assigned_roles') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                             <div>
                                <label for="level_id" class="block mb-2 text-sm font-medium text-gray-900">Level</label>
                                <select wire:model="level_id" id="level_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    <option value="">Pilih Level</option>
                                    @foreach($levels as $level)
                                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                                    @endforeach
                                </select>
                                @error('level_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan</button>
                        <button wire:click="closeModal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL UNTUK DETAIL & AKTIVITAS (FLOWBITE STYLE) --}}
    @if($showDetailModal && $detailUser)
    <div class="fixed inset-0 z-50 flex justify-center items-center w-full h-full bg-gray-900 bg-opacity-50">
        <div class="relative p-4 w-full max-w-3xl h-auto">
            <div class="relative bg-white rounded-lg shadow" x-data="{ activeTab: 'profile' }">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">Detail Pengguna: {{ $detailUser->name }}</h3>
                    <button wire:click="closeModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                        <span class="sr-only">Tutup modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
                    <!-- Navigasi Tab Flowbite -->
                    <div class="mb-4 border-b border-gray-200">
                        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" role="tablist">
                            <li class="me-2" role="presentation">
                                <button @click.prevent="activeTab = 'profile'" :class="{ 'text-blue-600 border-blue-600': activeTab === 'profile', 'border-transparent hover:text-gray-600 hover:border-gray-300': activeTab !== 'profile' }" class="inline-block p-4 border-b-2 rounded-t-lg" type="button" role="tab">Profil</button>
                            </li>
                            <li class="me-2" role="presentation">
                                <button @click.prevent="activeTab = 'activity'" :class="{ 'text-blue-600 border-blue-600': activeTab === 'activity', 'border-transparent hover:text-gray-600 hover:border-gray-300': activeTab !== 'activity' }" class="inline-block p-4 border-b-2 rounded-t-lg" type="button" role="tab">Aktivitas Terbaru</button>
                            </li>
                        </ul>
                    </div>
                    <!-- Konten Tab -->
                    <div>
                        {{-- Tab Profil --}}
                        <div x-show="activeTab === 'profile'" class="p-4 rounded-lg bg-gray-50">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6 text-sm">
                                <div><dt class="font-medium text-gray-500">Email</dt><dd class="mt-1 text-gray-900">{{ $detailUser->email }}</dd></div>
                                <div><dt class="font-medium text-gray-500">Subsidiary</dt><dd class="mt-1 text-gray-900">{{ $detailUser->profile->subsidiary->name ?? '-' }}</dd></div>
                                <div><dt class="font-medium text-gray-500">Departemen</dt><dd class="mt-1 text-gray-900">{{ $detailUser->profile->department->name ?? '-' }}</dd></div>
                                <div><dt class="font-medium text-gray-500">Peran</dt><dd class="mt-1 text-gray-900">{{ $detailUser->getRoleNames()->implode(', ') ?: '-' }}</dd></div>
                                <div><dt class="font-medium text-gray-500">Level</dt><dd class="mt-1 text-gray-900">{{ $detailUser->profile->level->name ?? '-' }}</dd></div>
                            </dl>
                        </div>
                        {{-- Tab Aktivitas --}}
                        <div x-show="activeTab === 'activity'" style="display: none;" class="p-4 rounded-lg bg-gray-50">
                            <h4 class="font-medium mb-3">5 Request Perjalanan Terakhir</h4>
                            <ul class="divide-y divide-gray-200">
                                @forelse($detailUser->visitRequests as $request)
                                    <li class="py-3">
                                        <p class="text-sm font-medium text-gray-900">Tujuan: {{ $request->destination }}</p>
                                        <p class="text-sm text-gray-500">Diajukan pada: {{ $request->created_at->format('d M Y, H:i') }} | Status: {{ $request->status->name }}</p>
                                    </li>
                                @empty
                                    <li class="py-3 text-sm text-gray-500">Tidak ada aktivitas request ditemukan.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                 <!-- Modal footer -->
                <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
                    <button wire:click="closeModal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif



    <!-- Konten Utama -->
    <div class="bg-white p-6 rounded-lg shadow">
       <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 mb-4">
            
            {{-- Kolom Pencarian --}}
            <div class="w-full md:w-1/2">
                <label for="simple-search" class="sr-only">Cari</label>
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" id="simple-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="Cari nama atau email...">
                </div>
            </div>
            
            {{-- Filter & Tombol Aksi --}}
            <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-3 w-full md:w-auto">
                    <select wire:model.live="filterDepartment" class="w-full md:w-auto flex-shrink-0 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                        <option value="">Semua Departemen</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="filterRoleName" class="w-full md:w-auto flex-shrink-0 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                        <option value="">Semua Peran</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                @can('create users')
                <button wire:click="create" type="button" class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">
                    <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                    </svg>
                    Tambah Pengguna
                </button>
                @endcan
            </div>
        </div>

        <!-- Tabel Pengguna -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Pengguna</th>
                        {{-- Sembunyikan kolom ini di layar kecil --}}
                        <th scope="col" class="px-6 py-3 hidden md:table-cell">Departemen</th>
                        <th scope="col" class="px-6 py-3 hidden lg:table-cell">Peran</th>
                        <th scope="col" class="px-6 py-3"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                            {{-- Tampilkan peran di bawah nama HANYA di layar kecil --}}
                            <div class="text-xs text-gray-500 mt-1 lg:hidden">
                                Peran: {{ $user->getRoleNames()->implode(', ') }}
                            </div>
                        </td>
                        {{-- Sembunyikan kolom ini di layar kecil --}}
                        <td class="px-6 py-4 hidden md:table-cell">{{ $user->profile->department->name ?? '-' }}</td>
                        <td class="px-6 py-4 hidden lg:table-cell">{{ $user->getRoleNames()->implode(', ') }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-4">
                                @can('view users')
                                <button wire:click="viewDetails({{ $user->id }})" class="font-medium text-green-600 hover:underline">Detail</button>
                                @endcan
                                @can('edit users')
                                <button wire:click="edit({{ $user->id }})" class="font-medium text-blue-600 hover:underline">Edit</button>
                                @endcan
                                @can('delete users')
                                <button wire:click="delete({{ $user->id }})" wire:confirm="Anda yakin ingin menghapus pengguna ini?" class="font-medium text-red-600 hover:underline">Hapus</button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-4 text-center">Tidak ada pengguna ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>