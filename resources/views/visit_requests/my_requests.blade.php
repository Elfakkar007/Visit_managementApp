@php
    // Tentukan layout yang akan digunakan berdasarkan peran pengguna
    $layout = Auth::user()->profile?->role?->name === 'Admin' ? 'admin-layout' : 'app-layout';
@endphp

<x-dynamic-component :component="$layout">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Riwayat Permintaan Kunjungan Saya') }}
            </h2>
            
            @if(Auth::user()->profile?->level?->name !== 'Deputi')
            <a href="{{ route('requests.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Buat Request Baru
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
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
