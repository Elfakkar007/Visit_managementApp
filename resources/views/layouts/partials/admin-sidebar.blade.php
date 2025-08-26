<aside id="default-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
   <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50">
      <a href="{{ route('admin.dashboard') }}" class="flex items-center ps-2.5 mb-5">
         <span class="self-center text-xl font-semibold whitespace-nowrap">Satoria Admin</span>
      </a>
      <ul class="space-y-2 font-medium">
         <li>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('admin.dashboard') ? 'bg-gray-200' : '' }}">
               <span class="text-xl">📊</span>
               <span class="ms-3">Dashboard</span>
            </a>
         </li>
         <li>
            <a href="{{ route('requests.my') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('requests.my') ? 'bg-gray-200' : '' }}">
               <span class="text-xl">✈️</span>
               <span class="ms-3">Request Saya</span>
            </a>
         </li>
         <li>
            <a href="{{ route('admin.activities.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
               <span class="text-xl">📜</span>
               <span class="flex-1 ms-3 whitespace-nowrap">Aktivitas</span>
            </a>
         </li>
         <li>
            <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
               <span class="text-xl">👋</span>
               <span class="flex-1 ms-3 whitespace-nowrap">Tamu</span>
            </a>
         </li>
         <li>
            <a href="{{ route('admin.users.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('admin.users.index') ? 'bg-gray-200' : '' }}">
            <span class="text-xl">👥</span>
            <span class="flex-1 ms-3 whitespace-nowrap">User Profiles</span>
            </a>
         </li>
         <li>
            <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100" aria-controls="dropdown-config" data-collapse-toggle="dropdown-config">
                  <span class="text-xl">⚙️</span>
                  <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Konfigurasi</span>
                  <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                  </svg>
            </button>
            <ul id="dropdown-config" class="hidden py-2 space-y-2">
                  <li>
                    <a href="{{ route('admin.master-data') }}" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 {{ request()->routeIs('admin.master-data') ? 'bg-gray-200' : '' }}">Data Master</a>
                </li>
                  <li>
                     <a href="#" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100">Hak Akses</a>
                  </li>
            </ul>
         </li>
         <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                   <span class="text-xl">🚪</span>
                   <span class="flex-1 ms-3 whitespace-nowrap">Log Out</span>
                </button>
            </form>
         </li>
      </ul>
   </div>
</aside>
