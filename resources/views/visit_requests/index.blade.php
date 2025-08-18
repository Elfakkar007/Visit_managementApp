<x-app-layout>
    <x-slot name="header">
        {{-- Judul Halaman Dinamis di Navigasi Atas --}}
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if(request()->routeIs('requests.hrd_approval'))
                {{ __('Approval Khusus HRD') }}
            @elseif(Auth::user()->profile?->department?->name === 'HRD')
                {{ __('Pantau Semua Request') }}
            @else
                {{ __('Dashboard Approval') }}
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                     @if(session('success'))
                        {{-- ... (session message) ... --}}
                    @endif
                    
                    {{-- Judul dan Deskripsi Dinamis di Dalam Konten --}}
                    <div class="mb-4">
                        @if(request()->routeIs('requests.hrd_approval'))
                            <h3 class="text-lg font-medium">Approval Request Bawahan HRD</h3>
                            <p class="text-gray-600">Berikut adalah daftar permintaan dari bawahan Anda di departemen HRD.</p>
                        @elseif(Auth::user()->profile?->department?->name === 'HRD')
                             <h3 class="text-lg font-medium">Memantau Semua Request</h3>
                            <p class="text-gray-600">Anda dapat melihat semua request perjalanan dinas dari seluruh departemen.</p>
                        @else
                     <h3 class="text-lg font-medium">Dashboard Approval Kunjungan</h3>
                            <p class="text-gray-600">Berikut adalah daftar permintaan yang membutuhkan tindakan Anda.</p>
                        @endif
                    </div>


                    @include('visit_requests._table', ['requests' => $visitRequests])

                    <div class="mt-4">
                        {{ $visitRequests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>