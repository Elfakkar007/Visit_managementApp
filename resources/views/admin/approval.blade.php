<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Approval Semua Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                   <p class="mb-4">Sebagai Admin, Anda dapat melihat dan melakukan approval untuk semua request perjalanan dinas.</p>
                   {{-- Kita panggil komponen yang sama dengan mode baru --}}
                   @livewire('request-history', ['mode' => 'admin_approval'])
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>