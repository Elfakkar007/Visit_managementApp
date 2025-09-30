{{-- File Baru: resources/views/layouts/navigation.blade.php --}}
<nav class="bg-white border-b border-gray-100">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- SISI KIRI: Hanya Tombol Hamburger -->
            <div class="flex items-center">
                <button data-drawer-target="app-sidebar" data-drawer-toggle="app-sidebar" aria-controls="app-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <span class="sr-only">Buka sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                    </svg>
                </button>
            </div>

            <!-- SISI KANAN: Info Profil & Tombol Logout -->
            <div class="flex items-center ms-6 space-x-4">
                   @can('use scanner')
                    @livewire('receptionist.overdue-guests-notifier')
                @endcan
                <!-- Info Profil -->
                <div class="hidden sm:flex sm:items-center sm:space-x-2">
                    <div class="font-medium text-base text-gray-800 text-right">{{ Auth::user()->name }}</div>
                    <span class="text-sm text-gray-500">|</span>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->profile->department->name ?? 'No Dept' }}</div>
                    <span class="text-sm text-gray-500">|</span>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->profile->level->name ?? 'No Level' }}</div>
                </div>

                

        </div>
    </div>
</nav>

