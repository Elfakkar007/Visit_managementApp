@php
    // Cek apakah user yang login adalah Admin
    $layout = Auth::user()->profile?->role?->name === 'Admin' ? 'admin-layout' : 'app-layout';
@endphp

<x-dynamic-component :component="$layout">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Status Tamu Aktif</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <livewire:receptionist.guest-status-table />
        </div>
    </div>
</x-dynamic-component>