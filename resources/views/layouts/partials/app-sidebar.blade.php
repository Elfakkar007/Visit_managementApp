{{-- File Baru: resources/views/layouts/partials/app-sidebar.blade.php --}}
<aside id="app-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50">
        <a href="{{ route('dashboard') }}" class="flex items-center ps-2.5 mb-5">
            <img src="{{ asset('images/logo-sidebar.png') }}" class="w-full h-auto object-contain px-4" alt="Satoria VMS Logo" />
 
        </a>
        <ul class="space-y-2 font-medium">
         
            @can('create visit requests')
            <li>
                {{-- Link ini akan aktif saat di halaman 'dashboard', 'requests.my', atau 'requests.create' --}}
                <a href="{{ route('requests.my') }}" 
                class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs(['dashboard', 'requests.my', 'requests.create']) ? 'bg-gray-200' : '' }}">
                    <span class="text-xl">✈️</span>
                    <span class="ms-3">Request Saya</span>
                </a>
            </li>
            @endcan
                        
            @can('approve visit requests')
            <li>
                <a href="{{ route('requests.approval') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('requests.approval') ? 'bg-gray-200' : '' }}">
                    <span class="text-xl">✅</span>
                    <span class="ms-3">Approval</span>
                </a>
            </li>
            @endcan

            @can('view monitor page')
            <li>
                <a href="{{ route('requests.monitor') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('requests.monitor') ? 'bg-gray-200' : '' }}">
                    <span class="text-xl">📈</span>
                    <span class="ms-3">Pantau Semua</span>
                </a>
            </li>
            @endcan

            @role('Resepsionis')
            <li>
                <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100" aria-controls="dropdown-receptionist" data-collapse-toggle="dropdown-receptionist">
                    <span class="text-xl">🛎️</span>
                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Manajemen Tamu</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>
                <ul id="dropdown-receptionist" class="hidden py-2 space-y-2">
                    <li>
                        <a href="{{ route('receptionist.scanner') }}" class="flex items-center w-full p-2 pl-11 text-gray-900 rounded-lg hover:bg-gray-100 group">Scanner</a>
                    </li>
                    <li>
                        <a href="{{ route('receptionist.guestStatus') }}" class="flex items-center w-full p-2 pl-11 text-gray-900 rounded-lg hover:bg-gray-100 group">Status Tamu</a>
                    </li>
                    <li>
                        <a href="{{ route('receptionist.history') }}" class="flex items-center w-full p-2 pl-11 text-gray-900 rounded-lg hover:bg-gray-100 group">Riwayat Kunjungan</a>
                    </li>
                </ul>
            </li>
            @endrole

            @role('Admin')
             <li>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 text-red-700 rounded-lg hover:bg-gray-100 group">
                    <span class="text-xl">🔐</span>
                    <span class="ms-3 font-bold">Admin Panel</span>
                </a>
            </li>
            @endrole
        </ul>
    </div>
</aside>