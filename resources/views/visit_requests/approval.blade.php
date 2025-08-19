<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Approval') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"
            x-data="{ selectedRequest: null }">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-4">Berikut adalah daftar permintaan yang membutuhkan tindakan Anda.</p>
                    {{-- Langsung tampilkan tabel, tanpa filter --}}
                    @include('visit_requests._table', ['requests' => $visitRequests])
                    <div class="mt-4">{{ $visitRequests->links() }}</div>
                </div>
            </div>
             <x-modal name="request-detail-modal" title="Detail Permintaan Kunjungan">
                <div x-if="selectedRequest">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                        {{-- Data akan diisi oleh Alpine.js --}}
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Nama Pemohon</dt>
                            <dd class="mt-1 text-sm text-gray-900" x-text="selectedRequest.user.name"></dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Departemen</dt>
                            <dd class="mt-1 text-sm text-gray-900" x-text="selectedRequest.user.profile.department.name"></dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Tujuan</dt>
                            <dd class="mt-1 text-sm text-gray-900" x-text="selectedRequest.tujuan"></dd>
                        </div>
                        {{-- Anda bisa menambahkan detail lain di sini --}}
                    </dl>
                </div>
            </x-modal>
        </div>
    </div>
</x-app-layout> 