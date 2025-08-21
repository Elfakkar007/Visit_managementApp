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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"  x-data="{ 
        selectedRequest: null, 
        openDetailModal(request) { 
            this.selectedRequest = request; 
            this.$dispatch('open-modal', { name: 'request-detail-modal' }); 
        } 
     }">
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
                <x-modal name="request-detail-modal" title="Detail Permintaan Kunjungan">
                    {{-- Tampilkan konten HANYA jika selectedRequest tidak null --}}
                    <div x-if="selectedRequest">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                            
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Nama Pemohon</dt>
                                {{-- Menggunakan optional chaining (?.) dan fallback ('-') --}}
                                <dd class="mt-1 text-sm text-gray-900" x-text="selectedRequest?.user?.name ?? '-'"></dd>
                            </div>

                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Departemen</dt>
                                <dd class="mt-1 text-sm text-gray-900" x-text="selectedRequest?.user?.profile?.department?.name ?? '-'"></dd>
                            </div>

                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Tujuan</dt>
                                <dd class="mt-1 text-sm text-gray-900" x-text="selectedRequest?.destination ?? '-'"></dd>
                            </div>

                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Keperluan</dt>
                                <dd class="mt-1 text-sm text-gray-900" x-text="selectedRequest?.purpose ?? '-'"></dd>
                            </div>

                            <div x-show="selectedRequest.status.name === 'Rejected' && selectedRequest.rejection_reason" 
                                class="mt-4 pt-4 border-t border-gray-200">
                                <dl>
                                    <dt class="text-sm font-medium text-red-600">Alasan Penolakan:</dt>
                                    <dd class="mt-1 text-sm text-gray-800 bg-red-50 p-3 rounded-md" 
                                        x-text="selectedRequest.rejection_reason"></dd>
                                </dl>
                            </div>
                        </dl>
                    </div>
                </x-modal>
            </div>
        </div>
    </div>
</x-app-layout>