<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{-- Breadcrumb --}}
            <a href="{{ route('admin.roles.index') }}" class="text-blue-600 hover:underline">Hak Akses</a>
            <span class="text-gray-500 mx-2">/</span>
            <span>Edit Izin untuk Peran: {{ $role->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900">Daftar Izin (Permissions)</h3>
                            <p class="mt-1 text-sm text-gray-600">Pilih izin yang akan diberikan kepada peran <span class="font-bold">{{ $role->name }}</span>.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($permissions as $permission)
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="permission-{{ $permission->id }}" 
                                               name="permissions[]" 
                                               value="{{ $permission->name }}" 
                                               type="checkbox" 
                                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                                               {{-- Cek apakah peran ini sudah memiliki izin ini --}}
                                               @if($role->hasPermissionTo($permission->name)) checked @endif
                                        >
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="permission-{{ $permission->id }}" class="font-medium text-gray-700">{{ $permission->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @error('permissions')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror

                        <div class="mt-8 flex justify-end">
                            <a href="{{ route('admin.roles.index') }}" class="py-2.5 px-5 text-sm font-medium text-white focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 transition-colors duration-150">
                                Batal
                            </a>
                            <button type="submit" class="ms-3 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 transition-colors duration-150">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-admin-layout>