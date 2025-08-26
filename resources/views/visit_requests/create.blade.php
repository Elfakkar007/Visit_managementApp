@php
    // Tentukan layout yang akan digunakan berdasarkan peran pengguna
    $layout = Auth::user()->profile?->role?->name === 'Admin' ? 'admin-layout' : 'app-layout';
@endphp

<x-dynamic-component :component="$layout">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Permintaan Kunjungan Dinas Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    
                    <form method="POST" action="{{ route('requests.store') }}" class="space-y-6">
                        @csrf

                        {{-- Tujuan Kunjungan --}}
                        <div>
                            <label for="destination" class="block mb-2 text-sm font-medium text-gray-900">Tujuan Kunjungan</label>
                            <input type="text" name="destination" id="destination" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Contoh: Kantor Pusat Jakarta" value="{{ old('destination') }}" required autofocus>
                            <x-input-error :messages="$errors->get('destination')" class="mt-2" />
                        </div>

                        {{-- Keperluan --}}
                        <div>
                            <label for="purpose" class="block mb-2 text-sm font-medium text-gray-900">Keperluan</label>
                            <textarea id="purpose" name="purpose" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Jelaskan keperluan kunjungan Anda secara detail..." required>{{ old('purpose') }}</textarea>
                            <x-input-error :messages="$errors->get('purpose')" class="mt-2" />
                        </div>

                        {{-- Tanggal Mulai & Selesai --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="from_date" class="block mb-2 text-sm font-medium text-gray-900">Tanggal Mulai</label>
                                <input type="date" name="from_date" id="from_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('from_date') }}" required>
                                <x-input-error :messages="$errors->get('from_date')" class="mt-2" />
                            </div>
                            <div>
                                <label for="to_date" class="block mb-2 text-sm font-medium text-gray-900">Tanggal Selesai</label>
                                <input type="date" name="to_date" id="to_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('to_date') }}" required>
                                <x-input-error :messages="$errors->get('to_date')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                            <a href="{{ route('requests.my') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                                Batal
                            </a>
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                                Ajukan Permintaan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
