

@props([
    'triggerId' => 'confirmationModalTrigger',
    'modalId' => 'confirmationModal'
])

<div x-data="{
        show: false,
        title: '',
        message: '',
        confirmText: 'Ya, Lanjutkan',
        cancelText: 'Batal',
        confirmColor: 'red',
        livewireEvent: null,
        livewireParams: null
    }"
    x-on:open-confirmation-modal.window="
        show = true;
        title = $event.detail.title;
        message = $event.detail.message;
        confirmText = $event.detail.confirmText || 'Ya, Lanjutkan';
        confirmColor = $event.detail.color || 'red';
        livewireEvent = $event.detail.livewireEvent;
        livewireParams = $event.detail.livewireParams;
    "
    x-on:keydown.escape.window="show = false"
    x-show="show"
    style="display: none;"
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-title" role="dialog" aria-modal="true"
>
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        {{-- Latar Belakang --}}
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="show = false"></div>

        {{-- Konten Modal --}}
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:w-full sm:max-w-lg sm:p-6 sm:align-middle">
           <div>
                <div class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full" :class="{ 'bg-red-100': confirmColor === 'red', 'bg-blue-100': confirmColor === 'blue', 'bg-green-100': confirmColor === 'green' }">
                    <svg class="h-6 w-6" :class="{ 'text-red-600': confirmColor === 'red', 'text-blue-600': confirmColor === 'blue', 'text-green-600': confirmColor === 'green' }" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                </div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-base font-semibold leading-6 text-gray-900" x-text="title"></h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500" x-text="message"></p>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <button
                   
                    @click="Livewire.dispatch(livewireEvent, { requestId: livewireParams[0] }); show = false;"
                    type="button"
                    class="inline-flex w-full justify-center rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto"
                    :class="{
                        'bg-red-600 hover:bg-red-500': confirmColor === 'red',
                        'bg-blue-600 hover:bg-blue-500': confirmColor === 'blue',
                        'bg-green-600 hover:bg-green-500': confirmColor === 'green'
                    }"
                    x-text="confirmText"
                ></button>
                <button @click="show = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" x-text="cancelText"></button>
            </div>
        </div>
    </div>
</div>