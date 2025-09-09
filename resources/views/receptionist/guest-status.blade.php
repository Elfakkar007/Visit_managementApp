@php
    // DIUBAH: Gunakan fungsi hasRole() dari Spatie yang dinamis
    $layout = Auth::user()->hasRole('Admin') ? 'admin-layout' : 'app-layout';
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