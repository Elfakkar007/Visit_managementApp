@props(['name', 'title'])

<div
    x-data="{ show: false, name: '{{ $name }}' }"
    x-show="show"
    x-on:open-modal.window="show = ($event.detail.name === name)"
    x-on:close-modal.window="show = false"
    x-on:keydown.escape.window="show = false"
    style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center"
    x-transition.opacity.duration.300ms
>
    {{-- Background Blur --}}
    <div 
        x-on:click="show = false" 
        class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity"
    ></div>

    {{-- Modal Content --}}
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-8 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-8 scale-95"
        class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden"
    >
        {{-- Header --}}
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-xl font-semibold text-gray-900">
                {{ $title }}
            </h3>
            <button 
                x-on:click="show = false" 
                class="rounded-full p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition"
            >
                ✕
            </button>
        </div>

        {{-- Body --}}
        <div class="px-6 py-5 max-h-[70vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100">
            {{ $slot }}
        </div>

       
        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            @if (isset($footer))
                {{-- Jika ada slot footer, tampilkan isinya --}}
                {{ $footer }}
            @else
                {{-- Jika tidak, tampilkan tombol Close default --}}
                <div class="flex justify-end">
                    <button 
                        type="button"
                        x-on:click="show = false" 
                        class="px-4 py-2 rounded-lg bg-gray-200 text-gray-800 hover:bg-gray-300 transition"
                    >
                        Close
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
