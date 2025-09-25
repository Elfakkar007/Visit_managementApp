<aside id="default-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    {{-- 1. Tambahkan class 'flex flex-col' di sini --}}
    <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50 flex flex-col">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center ps-2.5 mb-5">
            <img src="{{ asset('images/logo-sidebar.png') }}" class="w-full h-auto object-contain px-4" alt="Satoria VMS Logo" />
        </a>

        {{-- 2. Tambahkan class 'flex-grow' di sini --}}
        <ul class="space-y-2 font-medium flex-grow">
            {{-- Menu Dashboard --}}
            <li>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('admin.dashboard') ? 'bg-gray-200' : '' }}">
                    <span class="text-xl">📊</span>
                    <span class="ms-3">Dashboard</span>
                </a>
            </li>

            {{-- Dropdown Request --}}
            <li>
                <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100" aria-controls="dropdown-request" data-collapse-toggle="dropdown-request">
                    <span class="text-xl">✈️</span>
                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Request</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>
                <ul id="dropdown-request" class="hidden py-2 space-y-2">
                    @can('create visit requests')
                    <li>
                        <a href="{{ route('requests.my') }}" class="flex items-center w-full p-2 pl-11 text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('requests.my') ? 'bg-gray-200' : '' }}">Request Saya</a>
                    </li>
                    @endcan
                    @can('approve visit requests')
                    <li>
                        <a href="{{ route('admin.requests.approval') }}" class="flex items-center w-full p-2 pl-11 text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('admin.requests.approval') ? 'bg-gray-200' : '' }}">Approval</a>
                    </li>
                    @endcan
                </ul>
            </li>

            {{-- Dropdown Aktivitas --}}
            <li>
                <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100" aria-controls="dropdown-activity" data-collapse-toggle="dropdown-activity">
                    <span class="text-xl">📜</span>
                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Aktivitas</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>
                <ul id="dropdown-activity" class="hidden py-2 space-y-2">
                    @can('view monitor page')
                    <li>
                        <a href="{{ route('requests.monitor') }}" class="flex items-center w-full p-2 pl-11 text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('requests.monitor') ? 'bg-gray-200' : '' }}">Pantau Semua</a>
                    </li>
                    @endcan
                    <li>
                        <a href="{{ route('admin.activities.index') }}" class="flex items-center w-full p-2 pl-11 text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('admin.activities.index') ? 'bg-gray-200' : '' }}">Log Aktivitas</a>
                    </li>
                </ul>
            </li>

            {{-- Dropdown Tamu --}}
            <li>
                <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100" aria-controls="dropdown-guest" data-collapse-toggle="dropdown-guest">
                    <span class="text-xl">🛎️</span>
                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Tamu</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>
                <ul id="dropdown-guest" class="hidden py-2 space-y-2">
                    @can('use scanner')
                    <li>
                        <a href="{{ route('receptionist.scanner') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 {{ request()->routeIs('receptionist.scanner') ? 'bg-gray-200' : '' }}">Scanner</a>
                    </li>
                    @endcan
                    <li>
                        <a href="{{ route('admin.guests.status') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 {{ request()->routeIs('admin.guests.status') ? 'bg-gray-200' : '' }}">Status Tamu</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.guests.history') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 {{ request()->routeIs('admin.guests.history') ? 'bg-gray-200' : '' }}">Riwayat Kunjungan</a>
                    </li>
                </ul>
            </li>

            {{-- Menu User Profiles --}}
            @can('view users')
            <li>
                <a href="{{ route('admin.users.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('admin.users.index') ? 'bg-gray-200' : '' }}">
                    <span class="text-xl">👥</span>
                    <span class="flex-1 ms-3 whitespace-nowrap">User Profiles</span>
                </a>
            </li>
            @endcan

            {{-- Dropdown Konfigurasi --}}
            @can('manage master data')
            <li>
                <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100" aria-controls="dropdown-config" data-collapse-toggle="dropdown-config">
                    <span class="text-xl">⚙️</span>
                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Konfigurasi</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>
                <ul id="dropdown-config" class="hidden py-2 space-y-2">
                    <li><a href="{{ route('admin.master-data') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 {{ request()->routeIs('admin.master-data') ? 'bg-gray-200' : '' }}">Data Master</a></li>
                    <li><a href="{{ route('admin.roles.index') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 {{ request()->routeIs('admin.roles.*') ? 'bg-gray-200' : '' }}">Hak Akses</a></li>
                    <li><a href="{{ route('admin.workflows.index') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 {{ request()->routeIs('admin.workflows.*') ? 'bg-gray-200' : '' }}">Alur Approval</a></li>
                </ul>
            </li>
            @endcan
        </ul>

        {{-- 3. Pindahkan Tombol Log Out ke sini (di luar <ul>) --}}
        <form method="POST" action="{{ route('logout') }}" class="pt-2">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center p-2 text-sm font-medium text-red-700 border border-red-700 rounded-lg hover:bg-red-700 hover:text-white transition-colors duration-150">
                <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span>Log Out</span>
            </button>
        </form>
    </div>
</aside>