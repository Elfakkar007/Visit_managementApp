<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Riwayat Permintaan Kunjungan Saya') }}
            </h2>
            
            {{-- Tombol ini hanya muncul jika level BUKAN Deputi --}}
            @if(Auth::user()->profile?->level?->name !== 'Deputi')
            <a href="{{ route('requests.create') }}" class="inline-flex items-center ...">
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
                        <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-3 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    @include('visit_requests._table', ['requests' => $visitRequests])
                    
                    <div class="mt-4">
                        {{ $visitRequests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>