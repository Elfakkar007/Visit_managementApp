@props(['name', 'title'])

<div
    x-data="{ show: false, name: '{{ $name }}' }"
    x-show="show"
    x-on:open-modal.window="show = ($event.detail.name === name)"
    x-on:close-modal.window="show = false"
    x-on:keydown.escape.window="show = false"
    style="display: none;"
    class="fixed z-50 inset-0"
    x-transition.enter.duration.300ms
    x-transition.leave.duration.300ms
>
    {{-- Latar Belakang Abu-abu --}}
    <div x-on:click="show = false" class="fixed inset-0 bg-gray-500/75"></div>

    {{-- Konten Modal --}}
    <div class="bg-white rounded-lg shadow-xl m-4 sm:mx-auto sm:max-w-lg max-h-[90vh] overflow-y-auto"
         x-show="show"
         x-transition.opacity.duration.300ms
    >
        {{-- Judul Modal --}}
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-bold">{{ $title }}</h3>
            <button x-on:click="show = false" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>

        {{-- Isi Modal (Slot) --}}
        <div class="p-6">
            {{ $slot }}
        </div>
    </div>
</div>