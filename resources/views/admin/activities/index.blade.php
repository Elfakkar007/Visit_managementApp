<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Log Aktivitas Request') }}
        </h2>
    </x-slot>

    <div>
        {{-- Kita panggil komponen yang sudah ada dengan mode admin --}}
        <livewire:request-history mode="admin" />
    </div>
</x-admin-layout>