<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pantau Semua Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- 1. Bungkus kembali komponen Livewire dengan card putih --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @livewire('request-history')
            </div>

            {{-- 2. Modal tetap di luar, diurus oleh Alpine --}}
            <div x-data>
                <x-modal name="request-detail-modal" title="Detail Permintaan Kunjungan">
                    {{-- Kita gunakan $dispatch untuk mendengarkan event --}}
                    <div x-data="{ selectedRequest: null }" 
                         @open-detail-modal.window="selectedRequest = $event.detail">
                        
                        <template x-if="selectedRequest">
                            <div>
                                {{-- ... Seluruh isi <dl> modal detail Anda di sini ... --}}
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Nama Pemohon</dt>
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
                                    <div x-show="selectedRequest && selectedRequest.status && selectedRequest.status.name === 'Rejected' && selectedRequest.rejection_reason" class="sm:col-span-2 mt-4 pt-4 border-t border-gray-200">
                                        <dl>
                                            <dt class="text-sm font-medium text-red-600">Alasan Penolakan:</dt>
                                            <dd class="mt-1 text-sm text-gray-800 bg-red-50 p-3 rounded-md" x-text="selectedRequest?.rejection_reason"></dd>
                                        </dl>
                                    </div>
                                </dl>
                            </div>
                        </template>

                    </div>
                </x-modal>
            </div>
        </div>
    </div>

    {{-- 3. Definisikan fungsi openDetailModal secara global --}}
    @push('scripts')
    <script>
        function openDetailModal(request) {
            // Kirim event yang bisa didengar oleh Alpine
            window.dispatchEvent(new CustomEvent('open-detail-modal', { detail: request }));
            // Buka modalnya
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { name: 'request-detail-modal' } }));
        }
    </script>
    @endpush

</x-app-layout>