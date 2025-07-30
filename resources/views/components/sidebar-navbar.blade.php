<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 text-base">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    <div class="grid grid-cols-2 md:grid-cols-12 items-center w-full">

      <div class="md:col-span-3 flex items-center">
        <button id="sidebarToggle" class="mr-3 text-gray-500 hover:text-gray-700 md:hidden">
          <i class="fas fa-bars text-lg"></i>
        </button>
        <a href="/dashboard" class="text-xl font-bold text-[#0CC0DF]">EasyManage</a>
      </div>

      <div class="hidden md:flex md:col-span-6 justify-center items-center text-sm">
        @php $path = Request::path(); @endphp
        @if(Str::startsWith($path, 'dinas'))
          <span class="text-black font-semibold"><i class="fas fa-calendar-check mr-1 text-blue-500"></i>Jadwal Dinas</span>
        @elseif(Str::startsWith($path, 'manajemen-logistik') || Str::startsWith($path, 'logistics'))
          <span class="text-black font-semibold"><i class="fas fa-boxes mr-1 text-yellow-500"></i>Manajemen Logistik</span>
        @elseif(Str::startsWith($path, 'ppi') || Str::startsWith($path, 'bundle'))
          <span class="text-black font-semibold"><i class="fas fa-shield-virus mr-1 text-blue-500"></i>PPI</span>
        @elseif(Str::startsWith($path, 'kinerja-staff'))
          <span class="text-black font-semibold"><i class="fas fa-chart-line mr-1 text-green-500"></i>Kinerja Staff</span>
        @elseif(Str::startsWith($path, 'tna'))
          <span class="text-black font-semibold"><i class="fas fa-book mr-1 text-purple-500"></i>TNA</span>
        @elseif(Str::startsWith($path, 'indikator-mutu'))
          <span class="text-black font-semibold"><i class="fas fa-bullseye mr-1 text-indigo-500"></i>Indikator Mutu</span>
        @elseif(Str::startsWith($path, 'schedule'))
          <span class="text-black font-semibold"><i class="fas fa-calendar-alt mr-1 text-pink-500"></i>Schedule</span>
        @elseif(Str::startsWith($path, 'laporan') || Str::startsWith($path, 'reports'))
          <span class="text-black font-semibold"><i class="fas fa-file-alt mr-1 text-red-500"></i>Laporan</span>
        @elseif(Str::startsWith($path, 'notifikasi'))
          <span class="text-black font-semibold"><i class="fas fa-bell mr-1 text-orange-500"></i>Notifikasi</span>
        @elseif($path == 'dashboard')
          <span class="text-black font-semibold"><i class="fas fa-home mr-1 text-green-500"></i>Dashboard</span>
        @endif
      </div>

      <div class="md:col-span-3 flex items-center justify-end gap-4">
        <div class="hidden sm:block text-sm text-gray-600 font-medium whitespace-nowrap">
          <span id="realtime-date"></span>
        </div>
        <div class="relative">
          <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300" aria-expanded="false" data-dropdown-toggle="dropdown-user">
            <span class="sr-only">Open user menu</span>
            <img class="w-8 h-8 rounded-full bg-white" src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('images/p.png') }}" alt="user photo">
          </button>
        </div>
      </div>

    </div>
  </div>
</nav>

<div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow absolute mt-12 right-4" id="dropdown-user">
    <div class="px-4 py-3" role="none">
        <p class="text-sm text-gray-900" role="none">{{ Auth::user()->name }}</p>
        <p class="text-sm font-medium text-gray-900 truncate" role="none">{{ Auth::user()->email }}</p>
    </div>
    <ul class="py-1" role="none">
        <li><a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Settings</a></li>
        <li>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Sign out</button>
            </form>
        </li>
    </ul>
</div>

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-56 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0" aria-label="Sidebar">
  <div class="h-full px-3 pb-4 overflow-y-auto bg-white flex flex-col">
    <div class="relative mb-4 mt-2">
      <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
        <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
        </svg>
      </div>
      <input type="text" id="menu-search" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari menu...">
    </div>
    
    <ul class="space-y-2 font-medium flex-grow" id="menu-list">
      <li>
        <a href="/dashboard" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 {{ Request::is('dashboard') ? 'bg-gray-100' : '' }}">
          <i class="fas fa-home w-4 text-center text-gray-500"></i>
          <span class="ms-3">Beranda</span>
        </a>
      </li>
      <li>
        <a href="/dinas" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 {{ Request::is('dinas*') ? 'bg-gray-100' : '' }}">
          <i class="fas fa-calendar-check w-4 text-center text-gray-500"></i>
          <span class="flex-1 ms-3 whitespace-nowrap">Jadwal Dinas</span>
        </a>
      </li>
      <li>
        <a href="/manajemen-logistik" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 {{ Request::is('manajemen-logistik*') || Request::is('logistics*') ? 'bg-gray-100' : '' }}">
          <i class="fas fa-boxes w-4 text-center text-gray-500"></i>
          <span class="flex-1 ms-3 whitespace-nowrap">Manajemen Logistik</span>
        </a>
      </li>
      <li>
        <a href="/ppi" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 {{ Request::is('ppi*') || Request::is('bundle*') ? 'bg-gray-100' : '' }}">
          <i class="fas fa-shield-virus w-4 text-center text-gray-500"></i>
          <span class="flex-1 ms-3 whitespace-nowrap">PPI</span>
        </a>
      </li>
      <li>
        <a href="/kinerja-staff" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 {{ Request::is('kinerja-staff*') ? 'bg-gray-100' : '' }}">
            <i class="fas fa-chart-line w-4 text-center text-gray-500"></i>
          <span class="flex-1 ms-3 whitespace-nowrap">Kinerja Staff</span>
        </a>
      </li>
      <li>
        <a href="/tna" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 {{ Request::is('tna*') ? 'bg-gray-100' : '' }}">
          <i class="fas fa-book w-4 text-center text-gray-500"></i>
          <span class="flex-1 ms-3 whitespace-nowrap">TNA</span>
        </a>
      </li>
      <li>
        <a href="/indikator-mutu" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 {{ Request::is('indikator-mutu*') ? 'bg-gray-100' : '' }}">
          <i class="fas fa-bullseye w-4 text-center text-gray-500"></i>
          <span class="flex-1 ms-3 whitespace-nowrap">Indikator Mutu</span>
        </a>
      </li>
      <li>
        <a href="/schedule" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 {{ Request::is('schedule*') ? 'bg-gray-100' : '' }}">
          <i class="fas fa-calendar-alt w-4 text-center text-gray-500"></i>
          <span class="flex-1 ms-3 whitespace-nowrap">Schedule</span>
        </a>
      </li>
      <li>
        <a href="/laporan" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 {{ Request::is('laporan*') || Request::is('reports*') ? 'bg-gray-100' : '' }}">
          <i class="fas fa-file-alt w-4 text-center text-gray-500"></i>
          <span class="flex-1 ms-3 whitespace-nowrap">Laporan</span>
        </a>
      </li>
      <li>
        <a href="/notifikasi" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 {{ Request::is('notifikasi*') ? 'bg-gray-100' : '' }}">
          <i class="fas fa-bell w-4 text-center text-gray-500"></i>
          <span class="flex-1 ms-3 whitespace-nowrap">Notifikasi</span>
        </a>
      </li>
    </ul>
    
    <div class="mt-auto py-4 flex justify-left">
      <img src="{{ asset('images/logo wahid.png') }}" alt="Logo Wahid" class="w-12 h-12 object-contain opacity-70 hover:opacity-100 transition-opacity duration-300">
    </div>
  </div>
</aside>

<script>
document.addEventListener("DOMContentLoaded", function () {
  // Script for realtime date
  const dateEl = document.getElementById("realtime-date");
  if(dateEl) {
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    dateEl.innerText = now.toLocaleDateString('id-ID', options);
  }

  // Script for sidebar toggle
  const sidebar = document.getElementById('logo-sidebar');
  const sidebarToggle = document.getElementById('sidebarToggle');
  
  if (sidebar && sidebarToggle) {
    const sidebarState = localStorage.getItem('sidebarState');
    if (window.innerWidth < 768 && sidebarState !== 'visible') {
        sidebar.classList.add('-translate-x-full');
    } else if (sidebarState === 'hidden') {
        sidebar.classList.add('-translate-x-full');
    } else {
        sidebar.classList.remove('-translate-x-full');
    }
    
    sidebarToggle.addEventListener('click', function(event) {
      event.stopPropagation();
      sidebar.classList.toggle('-translate-x-full');
      localStorage.setItem('sidebarState', sidebar.classList.contains('-translate-x-full') ? 'hidden' : 'visible');
    });
    
    document.addEventListener('click', function(event) {
      if (window.innerWidth < 768 && sidebar && !sidebar.contains(event.target) && !sidebarToggle.contains(event.target) && !sidebar.classList.contains('-translate-x-full')) {
          sidebar.classList.add('-translate-x-full');
          localStorage.setItem('sidebarState', 'hidden');
      }
    });
  }
  
  // Search functionality
  const searchInput = document.getElementById('menu-search');
  const menuItems = document.querySelectorAll('#menu-list li');
  
  if(searchInput && menuItems.length) {
    searchInput.addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      menuItems.forEach(item => {
        item.style.display = item.textContent.toLowerCase().includes(searchTerm) ? 'block' : 'none';
      });
    });
  }
});
</script>

<style>
/* Active menu style */
.bg-gray-100 {
  background-color: #f3f4f6;
}
/* Sidebar animation */
#logo-sidebar {
  transition: transform 0.3s ease-in-out;
}
</style>