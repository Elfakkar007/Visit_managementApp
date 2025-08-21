<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Approval') }}
        </h2>
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
                    <p class="mb-4">Berikut adalah daftar permintaan yang membutuhkan tindakan Anda.</p>
                    {{-- Langsung tampilkan tabel, tanpa filter --}}
                    @livewire('request-history', ['mode' => 'approval'])
                    <div class="mt-4">{{ $visitRequests->links() }}</div>
                </div>
            </div>
             {{-- resources/views/visit_requests/approval.blade.php --}}

            <x-modal name="request-detail-modal" title="Detail Permintaan Kunjungan">
                {{-- Bagian Body Modal (Konten Detail) --}}
                <div x-if="selectedRequest">
                    {{-- Konten detail seperti sebelumnya, tidak ada perubahan --}}
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
                            <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap" x-text="selectedRequest?.purpose ?? '-'"></dd>
                        </div>
                    </dl>
                </div>

                {{-- Slot Footer Baru dengan Tombol Aksi --}}
                <x-slot:footer>
    <div x-if="selectedRequest" class="flex justify-between items-center w-full">
        {{-- Tombol Tutup di Kiri --}}
        <button type="button" x-on:click="$dispatch('close-modal')" 
                class="px-4 py-2 rounded-lg bg-gray-200 text-gray-800 hover:bg-gray-300 transition">
            Tutup
        </button>

        {{-- Form Aksi di Kanan --}}
        <div class="flex items-start space-x-3">
            {{-- ▼ PASTIKAN ID INI... ▼ --}}
            <form id="modal-reject-form" :action="`/requests/${selectedRequest.id}/reject`" method="POST" class="flex items-start space-x-2">
                @csrf
                @method('PATCH')
                <textarea name="rejection_reason" rows="1" 
                          class="w-48 border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                          placeholder="Alasan penolakan (opsional)"></textarea>
                
                {{-- ▼ ...SAMA DENGAN YANG DIPANGGIL DI SINI ▼ --}}
                <button type="button" onclick="confirmAction('modal-reject-form')" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
                    Tolak
                </button>
            </form>

            <form :action="`/requests/${selectedRequest.id}/approve`" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition">
                    Setujui
                </button>
            </form>
        </div>
    </div>
</x-slot:footer>
            </x-modal>
        </div>
    </div>
</x-app-layout> 