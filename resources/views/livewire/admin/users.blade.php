<div>
    @if (session()->has('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('success') }}
        </div>
    @endif

    {{-- MODAL UNTUK CREATE & EDIT --}}
 
@if($showModal)
<div class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex justify-center items-start pt-10">
    <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-2xl">
        <h3 class="text-lg font-medium mb-4">{{ $editingId ? 'Edit' : 'Tambah' }} Pengguna</h3>
        <form wire:submit.prevent="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Kolom Kiri --}}
                <div>
                    <div class="mb-4">
                        <label for="name" class="block mb-2 text-sm font-medium">Nama Lengkap</label>
                        <input wire:model="name" type="text" id="name" class="form-input w-full" required>
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block mb-2 text-sm font-medium">Email</label>
                        <input wire:model="email" type="email" id="email" class="form-input w-full" required>
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    {{-- Tambahkan Alpine.js untuk show/hide password --}}
                    <div class="mb-4" x-data="{ show: false }">
                        <label for="password" class="block mb-2 text-sm font-medium">Password</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model="password" id="password" class="form-input w-full pr-10" placeholder="{{ $editingId ? 'Kosongkan jika tidak diubah' : '' }}">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 focus:outline-none" tabindex="-1">
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95M6.873 6.872A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.965 9.965 0 01-4.293 5.95M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4" x-data="{ show: false }">
                        <label for="password_confirmation" class="block mb-2 text-sm font-medium">Konfirmasi Password</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model="password_confirmation" id="password_confirmation" class="form-input w-full pr-10">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 focus:outline-none" tabindex="-1">
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95M6.873 6.872A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.965 9.965 0 01-4.293 5.95M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                    {{-- Kolom Kanan --}}
                    <div>
                        <div class="mb-4">
                            <label for="subsidiary_id" class="block mb-2 text-sm font-medium">Subsidiary</label>
                            <select wire:model="subsidiary_id" id="subsidiary_id" class="form-select w-full" required>
                                <option value="">Pilih Subsidiary</option>
                                @foreach($subsidiaries as $subsidiary)
                                    <option value="{{ $subsidiary->id }}">{{ $subsidiary->name }}</option>
                                @endforeach
                            </select>
                            @error('subsidiary_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="department_id" class="block mb-2 text-sm font-medium">Departemen</label>
                            <select wire:model="department_id" id="department_id" class="form-select w-full" required>
                                <option value="">Pilih Departemen</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                            @error('department_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="role_id" class="block mb-2 text-sm font-medium">Role</label>
                            <select wire:model="role_id" id="role_id" class="form-select w-full" required>
                                <option value="">Pilih Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="level_id" class="block mb-2 text-sm font-medium">Level</label>
                            <select wire:model="level_id" id="level_id" class="form-select w-full" required>
                                <option value="">Pilih Level</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                                @endforeach
                            </select>
                            @error('level_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" wire:click="closeModal" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
   

    {{-- MODAL UNTUK DETAIL & AKTIVITAS --}}
    @if($showDetailModal && $detailUser)
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex justify-center items-start pt-10">
        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-3xl" x-data="{ activeTab: 'profile' }">
            <div class="flex justify-between items-center mb-4">
                 <h3 class="text-lg font-medium">Detail Pengguna: {{ $detailUser->name }}</h3>
                 <button wire:click="closeModal" class="text-gray-500 hover:text-gray-800">&times;</button>
            </div>

            <!-- Navigasi Tab -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <a href="#" @click.prevent="activeTab = 'profile'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'profile', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'profile' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Profil</a>
                    <a href="#" @click.prevent="activeTab = 'activity'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'activity', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'activity' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Aktivitas Terbaru</a>
                </nav>
            </div>

            <!-- Konten Tab -->
            <div class="mt-6">
                {{-- Tab Profil --}}
                <div x-show="activeTab === 'profile'">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6 text-sm">
                        <div><dt class="font-medium text-gray-500">Email</dt><dd class="mt-1 text-gray-900">{{ $detailUser->email }}</dd></div>
                        <div><dt class="font-medium text-gray-500">Subsidiary</dt><dd class="mt-1 text-gray-900">{{ $detailUser->profile->subsidiary->name ?? '-' }}</dd></div>
                        <div><dt class="font-medium text-gray-500">Departemen</dt><dd class="mt-1 text-gray-900">{{ $detailUser->profile->department->name ?? '-' }}</dd></div>
                        <div><dt class="font-medium text-gray-500">Role</dt><dd class="mt-1 text-gray-900">{{ $detailUser->profile->role->name ?? '-' }}</dd></div>
                        <div><dt class="font-medium text-gray-500">Level</dt><dd class="mt-1 text-gray-900">{{ $detailUser->profile->level->name ?? '-' }}</dd></div>
                    </dl>
                </div>
                {{-- Tab Aktivitas --}}
                <div x-show="activeTab === 'activity'" style="display: none;">
                    <h4 class="font-medium mb-2">5 Request Perjalanan Terakhir</h4>
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
    </div>
    @endif

    <!-- Konten Utama -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
            <div class="w-full md:w-1/3">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau email..." class="form-input w-full">
            </div>
            <div class="w-full md:w-auto flex items-center gap-4">
                <select wire:model.live="filterDepartment" class="form-select">
                    <option value="">Semua Departemen</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="filterRole" class="form-select">
                    <option value="">Semua Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <button wire:click="create" class="inline-flex items-center justify-center w-full md:w-auto px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 transition duration-150 ease-in-out">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Pengguna
            </button>
        </div>

        <!-- Tabel Pengguna -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Pengguna</th>
                        <th scope="col" class="px-6 py-3">Departemen</th>
                        <th scope="col" class="px-6 py-3">Role</th>
                        <th scope="col" class="px-6 py-3"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4">{{ $user->profile->department->name ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $user->profile->role->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-right space-x-4">
                            <button wire:click="viewDetails({{ $user->id }})" class="font-medium text-green-600 hover:underline">Detail</button>
                            <button wire:click="edit({{ $user->id }})" class="font-medium text-blue-600 hover:underline">Edit</button>
                            <button wire:click="delete({{ $user->id }})" wire:confirm="Anda yakin ingin menghapus pengguna ini?" class="font-medium text-red-600 hover:underline">Hapus</button>
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
