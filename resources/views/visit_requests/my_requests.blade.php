@php
    // Gunakan fungsi hasRole() dari Spatie yang dinamis
    $layout = Auth::user()->hasRole('Admin') ? 'admin-layout' : 'app-layout';
@endphp

<x-dynamic-component :component="$layout">
    <x-slot name="header">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    {{ __('Riwayat Permintaan Saya') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Lihat semua riwayat perjalanan dinas yang pernah Anda ajukan.
                </p>
            </div>
            
            {{-- Tombol ini akan pindah ke bawah judul di layar kecil --}}
            @can('create visit requests')
            <a href="{{ route('requests.create') }}" class="inline-flex items-center justify-center mt-3 sm:mt-0 px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Buat Request Baru
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    @if(session('success'))
                        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @livewire('my-requests')
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
