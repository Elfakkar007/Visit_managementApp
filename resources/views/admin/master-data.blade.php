<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Data Master') }}
        </h2>
    </x-slot>

    <div>
        <livewire:admin.master-data />
    </div>
</x-admin-layout>