{{-- Ganti seluruh isi file dengan kode ini --}}
<div wire:poll.60s="fetchOverdueGuests" x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="relative text-gray-500 hover:text-gray-700 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        @if($overdueGuestsCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                {{ $overdueGuestsCount }}
            </span>
        @endif
    </button>

    <div x-show="open" @click.away="open = false" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 w-80 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50"
         style="display: none;">
        <div class="p-2">
            <div class="px-2 py-1 font-bold text-sm text-gray-800 border-b">Notifikasi</div>
            <div class="mt-2 max-h-96 overflow-y-auto">
                @forelse($overdueGuests as $guestVisit)
                    <a href="{{ route('receptionist.guestStatus') }}" class="block px-2 py-2 text-sm text-gray-700 rounded-md hover:bg-gray-100">
                        <p class="font-medium">
                            <span class="font-bold">{{ $guestVisit->guest->name }}</span> belum check-out.
                        </p>
                        <p class="text-xs text-gray-500">
                            Check-in sejak: {{ $guestVisit->time_in->diffForHumans() }}
                        </p>
                    </a>
                @empty
                    <div class="px-2 py-4 text-center text-sm text-gray-500">
                        Tidak ada notifikasi baru.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>