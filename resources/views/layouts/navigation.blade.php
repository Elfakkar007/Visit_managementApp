<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <!-- <x-application-logo class="block h-9 w-auto fill-current text-gray-800" /> -->
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">

                    {{-- 1. Link "Request Saya" (Untuk semua yang bisa request, KECUALI Deputi) --}}
                    @if(in_array(Auth::user()->profile?->role?->name, ['Staff', 'Approver', 'Resepsionis']) && Auth::user()->profile?->level?->name !== 'Deputi')
                        <x-nav-link :href="route('requests.my')" :active="request()->routeIs('requests.my') || request()->routeIs('requests.create')">
                            Request Saya
                        </x-nav-link>
                    @endif

                    {{-- 2. Link "Approval" (HANYA untuk Approver) --}}
                    @if(Auth::user()->profile?->role?->name === 'Approver')
                        {{-- Link khusus untuk Manajer HRD --}}
                        @if(Auth::user()->profile?->department?->name === 'HRD')
                            <x-nav-link :href="route('requests.hrd_approval')" :active="request()->routeIs('requests.hrd_approval')">
                                Approval HRD
                            </x-nav-link>
                        @else
                            {{-- Link untuk Approver biasa --}}
                            <x-nav-link :href="route('requests.index')" :active="request()->routeIs('requests.index') && !request()->routeIs('requests.hrd_approval')">
                                Approval
                            </x-nav-link>
                        @endif
                    @endif
                    
                    {{-- 3. Link "Pantau Semua" (HANYA untuk departemen HRD) --}}
                    @if(Auth::user()->profile?->department?->name === 'HRD')
                        <x-nav-link :href="route('requests.index')" :active="request()->routeIs('requests.index')">
                            Pantau Semua
                        </x-nav-link>
                    @endif

                    {{-- 4. Link "Manajemen Tamu" (HANYA untuk Resepsionis) --}}
                    @if(Auth::user()->profile?->role?->name === 'Resepsionis')
                        <x-nav-link :href="route('receptionist.scanner')" :active="request()->routeIs('receptionist.*')">
                            Manajemen Tamu
                        </x-nav-link>
                    @endif
                    
                    {{-- 5. Link "Admin Dashboard" (HANYA untuk Admin) --}}
                    @if(Auth::user()->profile?->role?->name === 'Admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                            Admin Dashboard
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Desktop Logout -->
           <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="me-3 text-right">
                    <div class="font-medium text-sm text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-500">{{ Auth::user()->profile?->department?->name }} | {{ Auth::user()->profile?->level?->name }}</div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        Log Out
                    </button>
                </form>
            </div>

            <!-- Hamburger Menu (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" 
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" 
                              class="inline-flex" 
                              stroke-linecap="round" 
                              stroke-linejoin="round" 
                              stroke-width="2" 
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" 
                              class="hidden" 
                              stroke-linecap="round" 
                              stroke-linejoin="round" 
                              stroke-width="2" 
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <!-- Mobile Navigation Menu -->
<div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
    <div class="pt-2 pb-3 space-y-1">

        {{-- 1. Link "Request Saya" (Untuk semua yang bisa request, KECUALI Deputi) --}}
        @if(in_array(Auth::user()->profile?->role?->name, ['Staff', 'Approver', 'Resepsionis']) && Auth::user()->profile?->level?->name !== 'Deputi')
            <x-responsive-nav-link :href="route('requests.my')" :active="request()->routeIs('requests.my') || request()->routeIs('requests.create')">
                Request Saya
            </x-responsive-nav-link>
        @endif

        {{-- 2. Link "Approval" (HANYA untuk Approver) --}}
        @if(Auth::user()->profile?->role?->name === 'Approver')
            @if(Auth::user()->profile?->department?->name === 'HRD')
                <x-responsive-nav-link :href="route('requests.hrd_approval')" :active="request()->routeIs('requests.hrd_approval')">
                    Approval HRD
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('requests.index')" :active="request()->routeIs('requests.index') && !request()->routeIs('requests.hrd_approval')">
                    Approval
                </x-responsive-nav-link>
            @endif
        @endif

        {{-- 3. Link "Pantau Semua" (HANYA untuk departemen HRD) --}}
        @if(Auth::user()->profile?->department?->name === 'HRD')
            <x-responsive-nav-link :href="route('requests.index')" :active="request()->routeIs('requests.index')">
                Pantau Semua
            </x-responsive-nav-link>
        @endif

        {{-- 4. Link "Manajemen Tamu" (HANYA untuk Resepsionis) --}}
        @if(Auth::user()->profile?->role?->name === 'Resepsionis')
            <x-responsive-nav-link :href="route('receptionist.scanner')" :active="request()->routeIs('receptionist.*')">
                Manajemen Tamu
            </x-responsive-nav-link>
        @endif

        {{-- 5. Link "Admin Dashboard" (HANYA untuk Admin) --}}
        @if(Auth::user()->profile?->role?->name === 'Admin')
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                Admin Dashboard
            </x-responsive-nav-link>
        @endif
    </div>

    <!-- Mobile User Settings -->
    <div class="pt-4 pb-1 border-t border-gray-200">
        <div class="px-4">
            <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
            <div class="text-xs text-gray-500">{{ Auth::user()->profile?->department?->name }} | {{ Auth::user()->profile?->level?->name }}</div>
        </div>

        <div class="mt-3 space-y-1">
            <!-- Mobile Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</div>
</nav>

