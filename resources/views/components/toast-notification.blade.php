@once
    <div
        x-data="{ toasts: [] }"
        x-on:show-toast.window="
            let toast = $event.detail[0] || $event.detail;
            toasts.push({ 
                id: Date.now(), 
                message: toast.message, 
                type: toast.type || 'success' 
            });
        "
        class="fixed inset-0 z-[100] flex flex-col-reverse items-end justify-start h-screen w-screen p-4 space-y-4 pointer-events-none"
    >
        <template x-for="(toast, index) in toasts" :key="toast.id">
            <div
                x-data="{
                    show: false,
                    init() {
                        this.$nextTick(() => this.show = true);
                        setTimeout(() => this.show = false, 5000);
                        setTimeout(() => this.toasts.splice(index, 1), 5500);
                    }
                }"
                x-show="show"
                x-transition:enter="transform ease-out duration-300 transition"
                x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0 translate-y-2 sm:translate-x-2"
                class="w-full max-w-sm bg-white rounded-xl shadow-lg pointer-events-auto ring-1 ring-black ring-opacity-5"
            >
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div x-show="toast.type === 'success'" class="p-2 bg-green-100 rounded-full">
                                <svg class="w-5 h-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div x-show="toast.type === 'error'" class="p-2 bg-red-100 rounded-full">
                                <svg class="w-5 h-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                            </div>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-semibold text-gray-900" x-text="toast.message"></p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="show = false; setTimeout(() => toasts.splice(index, 1), 500)" type="button" class="inline-flex text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Close</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
@endonce