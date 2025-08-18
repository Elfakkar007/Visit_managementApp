<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600 mt-1">Pilih salah satu menu di bawah untuk mengelola data aplikasi.</p>
                </div>
            </div>

            {{-- Grid Menu Navigasi --}}
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <a href="{{ route('admin.users.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6">
                        <h4 class="font-semibold text-lg text-gray-800">Manajemen Pengguna</h4>
                        <p class="text-gray-500 mt-2">Tambah, edit, atau hapus data pengguna.</p>
                    </div>
                </a>
                <a href="{{ route('admin.departments.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6">
                        <h4 class="font-semibold text-lg text-gray-800">Manajemen Departemen</h4>
                        <p class="text-gray-500 mt-2">Kelola daftar departemen perusahaan.</p>
                    </div>
                </a>
                <a href="{{ route('admin.levels.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6">
                        <h4 class="font-semibold text-lg text-gray-800">Manajemen Level</h4>
                        <p class="text-gray-500 mt-2">Kelola daftar level jabatan.</p>
                    </div>
                </a>
                <a href="{{ route('admin.roles.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6">
                        <h4 class="font-semibold text-lg text-gray-800">Manajemen Role</h4>
                        <p class="text-gray-500 mt-2">Kelola peran pengguna dalam sistem.</p>
                    </div>
                </a>
                 <a href="{{ route('admin.subsidiaries.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6">
                        <h4 class="font-semibold text-lg text-gray-800">Manajemen Subsidiary</h4>
                        <p class="text-gray-500 mt-2">Kelola daftar subsidiary perusahaan.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>