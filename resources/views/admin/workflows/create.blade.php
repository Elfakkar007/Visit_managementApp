<x-admin-layout>
    <x-slot name="header">
        {{-- Breadcrumb Flowbite untuk Navigasi Header --}}
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.workflows.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                        </svg>
                        Alur Approval
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Tambah Aturan Baru</span>
                    </div>
                </li>
            </ol>
        </nav>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Kartu Utama dengan Shadow --}}
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Buat Aturan Alur Kerja</h3>
                    <p class="text-gray-500 mb-6">Definisikan alur persetujuan berdasarkan level jabatan pengguna.</p>

                    <form action="{{ route('admin.workflows.store') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            
                            {{-- Langkah 1: Requester --}}
                            <div>
                                <label for="requester_level_id" class="block mb-2 text-sm font-medium text-gray-900">1. Jika Pengaju Memiliki Level</label>
                                <select name="requester_level_id" id="requester_level_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    <option value="" disabled selected>Pilih Level Pengaju</option>
                                    @foreach($levels as $level)
                                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            {{-- Langkah 2: Approver --}}
                            <div>
                                <label for="approver_level_id" class="block mb-2 text-sm font-medium text-gray-900">2. Maka Harus Disetujui Oleh Level</label>
                                <select name="approver_level_id" id="approver_level_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    <option value="" disabled selected>Pilih Level Penyetuju</option>
                                    @foreach($levels as $level)
                                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            {{-- Langkah 3: Scope --}}
                            <div>
                                <label for="scope" class="block mb-2 text-sm font-medium text-gray-900">3. Dengan Cakupan (Scope)</label>
                                <select name="scope" id="scope" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    <option value="" disabled selected>Pilih Cakupan</option>
                                    <option value="department">Departemen yang Sama</option>
                                    <option value="subsidiary">Subsidiary yang Sama</option>
                                </select>
                                <p class="mt-2 text-xs text-gray-500">Contoh: Approval hanya berlaku untuk approver di departemen yang sama dengan requester.</p>
                            </div>

                        </div>
                        
                        {{-- Aksi Tombol --}}
                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.workflows.index') }}" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 transition-colors duration-150">
                                Batal
                            </a>
                            <button type="submit" class="ms-3 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none transition-colors duration-150">
                                Simpan Aturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>