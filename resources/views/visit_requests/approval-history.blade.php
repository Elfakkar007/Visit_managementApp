<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Approval Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                   <p class="mb-4">Berikut adalah riwayat semua request yang pernah Anda setujui atau tolak.</p>
                   @livewire('request-history', ['mode' => 'approval_history'])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>