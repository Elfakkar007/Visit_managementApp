{{-- File Baru: resources/views/layouts/navigation.blade.php --}}
<nav class="bg-white border-b border-gray-100">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <div class="flex items-center">
                <div class="flex items-center space-x-2">
                     <button data-drawer-target="app-sidebar" data-drawer-toggle="app-sidebar" aria-controls="app-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <span class="sr-only">Buka sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                       <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                    </svg>
                 </button>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <span class="text-sm text-gray-500">|</span>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->profile->department->name ?? 'No Department' }}</div>
                    <span class="text-sm text-gray-500">|</span>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->profile->level->name ?? 'No Level' }}</div>
                </div>
            </div>

            <div class="flex items-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"  class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">
                        Log Out
                    </button>
                </form>
            </div>

        </div>
    </div>
</nav>