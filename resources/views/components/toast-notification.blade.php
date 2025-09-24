@once
    {{-- Container untuk menampung semua toast yang muncul --}}
    <div 
        x-data="{ toasts: [] }" 
        @show-toast.window="toasts.push({ id: Date.now(), message: $event.detail.message, type: $event.detail.type || 'success' })"
        aria-live="assertive" 
        class="pointer-events-none fixed inset-0 flex items-end px-4 py-6 sm:items-start sm:p-6 z-[100]"
    >
        <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
            {{-- Template Toast --}}
            <template x-for="toast in toasts" :key="toast.id">
                <div
                    x-data="{ show: false }"
                    x-init="() => { setTimeout(() => show = true, 100); setTimeout(() => { show = false; setTimeout(() => toasts = toasts.filter(t => t.id !== toast.id), 300) }, 5000) }"
                    x-show="show"
                    x-transition:enter="transform ease-out duration-300 transition"
                    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5"
                >
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="shrink-0">
                                {{-- Ikon Sukses --}}
                                <div x-show="toast.type === 'success'" class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-green-100 text-green-500">
                                     <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/></svg>
                                </div>
                                {{-- Ikon Error --}}
                                <div x-show="toast.type === 'error'" class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-red-100 text-red-500">
                                     <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/></svg>
                                </div>
                            </div>
                            <div class="ml-3 w-0 flex-1 pt-0.5">
                                <p class="text-sm font-medium text-gray-900" x-text="toast.message"></p>
                            </div>
                            <div class="ml-4 flex shrink-0">
                                <button @click="show = false; setTimeout(() => toasts = toasts.filter(t => t.id !== toast.id), 300)" type="button" class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
@endonce

