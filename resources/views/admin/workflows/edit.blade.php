<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Aturan Alur Kerja') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Panggil komponen edit dan kirim ID dari model --}}
                    <livewire:admin.workflow-edit :workflowId="$workflow->id" />
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>