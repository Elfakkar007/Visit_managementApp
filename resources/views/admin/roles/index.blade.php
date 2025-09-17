<x-admin-layout>
    <x-slot name="header">
        {{-- Header dikosongkan karena judul sudah dihandle di dalam komponen Livewire --}}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Panggil komponen Livewire yang baru kita buat --}}
                    <livewire:admin.roles-manager />
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>