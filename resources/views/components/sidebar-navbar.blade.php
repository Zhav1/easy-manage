<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 text-base">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
        <div class="flex items-center justify-start rtl:justify-end">
            <div class="text-xl font-bold text-[#0CC0DF]">EasyManage</div>
        </div>
        <div class="space-x-4 hidden md:flex text-sm gap-8">
            <a href="/" class="text-gray-700 hover:text-green-600"><i class="fa fa-home mr-1"></i>Dashboard</a>
            <a href="/dinas" class="text-gray-700 hover:text-green-600"><i class="fa fa-calendar-check mr-1"></i>Jadwal Dinas</a>
            <a href="/manajemen-logistik" class="text-gray-700 hover:text-yellow-600"><i class="fa fa-boxes mr-1"></i>Logistik</a>
            <a href="/pengendalian-dan-pencegahan-infeksi" class="text-gray-700 hover:text-blue-600"><i class="fa fa-shield-virus mr-1"></i>PPI</a>
        </div>
<div class="flex items-center ms-3">
    <div>
        <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300" 
                aria-expanded="false" data-dropdown-toggle="dropdown-user">
            <span class="sr-only">Open user menu</span>
            <img class="w-8 h-8 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="user photo">
        </button>
    </div>
    
    <!-- Dropdown menu -->
    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow" id="dropdown-user">
        <div class="px-4 py-3" role="none">
            <p class="text-sm text-gray-900" role="none">Neil Sims</p>
            <p class="text-sm font-medium text-gray-900 truncate" role="none">neil.sims@flowbite.com</p>
        </div>
        <ul class="py-1" role="none">
            <li>
                <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Lihat Profile</a>
            </li>
            <li>
                <a href="settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Settings</a>
            </li>
            <li>
                <form action="{{ route('logout') }}" method="POST" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                @csrf
                    <button type="submit" class="w-full block text-left text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Sign out</button>
                </form>
            </li>
        </ul>
    </div>
</div>
        </div>
    </div>
    </nav>
   
    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-56 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 " aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white ">
        <ul class="space-y-2 font-medium">
            <li>
                <a href="/" class="flex items-center p-2 text-gray-900 rounded-lg  ">
                <svg class="w-3 h-3 text-gray-500 transition duration-75 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                    <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                    <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
                </svg>
                <span class="ms-3">Beranda</span>
                </a>
            </li>

            <li>
                <a href="/dinas" class="flex items-center p-2 text-gray-900 rounded-lg ">
                <svg class="shrink-0 w-3 h-3 text-gray-500 transition duration-75 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                    <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Jadwal Dinas</span>
                </a>
            </li>
            <li>
                <a href="/manajemen-logistik" class="flex items-center p-2 text-gray-900 rounded-lg ">
                <svg class="shrink-0 w-3 h-3 text-gray-500 transition duration-75 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                    <path d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z"/>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Manajemen Logistik</span>
                </a>
            </li>
            <li>
                <a href="/pengendalian-dan-pencegahan-infeksi" class="flex items-center p-2 text-gray-900 rounded-lg ">
                <svg class="shrink-0 w-3 h-3 text-gray-500 transition duration-75  " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                    <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"/>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">PPI</span>
                </a>
            </li>
            <li>
                <a href="/kinerja-staff" class="flex items-center p-2 text-gray-900 rounded-lg ">
                <svg class="shrink-0 w-4 h-4 text-gray-500 transition duration-75 group-hover:text-gray-700" 
                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 3a1 1 0 000 2h2v9a1 1 0 001 1h2a1 1 0 001-1V5h2v6a1 1 0 001 1h2a1 1 0 001-1V5h2a1 1 0 100-2H3z" />
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Kinerja Staff</span>
                </a>
            </li>
            <li>
                <a href="/tna" class="flex items-center p-2 text-gray-900 rounded-lg ">
                <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 3a2 2 0 012-2h6l4 4v11a2 2 0 01-2 2H6a2 2 0 01-2-2V3zm7 1v3h3l-3-3zM7 10h6a1 1 0 100-2H7a1 1 0 000 2zm0 4h6a1 1 0 100-2H7a1 1 0 000 2z"/>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">TNA</span>
                </a>
            </li>
            <li>
                <a href="/indikator-mutu" class="flex items-center p-2 text-gray-900 rounded-lg ">
                <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a7 7 0 107 7H9V2z"/>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Indikator Mutu</span>
                </a>
            </li>
            <li>
                <a href="/schedule" class="flex items-center p-2 text-gray-900 rounded-lg ">
                <svg class="shrink-0 w-4 h-4 text-gray-500 transition duration-75 group-hover:text-gray-900" 
                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M6 2a1 1 0 000 2h8a1 1 0 100-2H6zM3 6a2 2 0 012-2h10a2 2 0 012 2v1H3V6zm14 3v7a2 2 0 01-2 2H5a2 2 0 01-2-2V9h14zm-4 2a1 1 0 10-2 0v2a1 1 0 102 0v-2z" />
                </svg>
                <path d="M10 2a6 6 0 00-6 6v2.586l-.707.707A1 1 0 004 13h12a1 1 0 00.707-1.707L16 10.586V8a6 6 0 00-6-6zM2 14a2 2 0 002 2h12a2 2 0 002-2H2z" />
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Schedule</span>
                </a>
            </li>
            <li>
                <a href="/laporan" class="flex items-center p-2 text-gray-900 rounded-lg ">
                <svg class="shrink-0 w-4 h-4 text-gray-500 transition duration-75 group-hover:text-gray-900"
                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 3a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2H6a2 2 0 01-2-2V3zm7 1v4h4l-4-4zM7 9h6a1 1 0 110 2H7a1 1 0 110-2zm0 4h6a1 1 0 110 2H7a1 1 0 110-2z" />
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Laporan</span>
                </a>
            </li>
            <li>
                <a href="/notifikasi" class="flex items-center p-2 text-gray-900 rounded-lg ">
                <svg class="shrink-0 w-3 h-3 text-gray-500 transition duration-75 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                <path d="M10 2a6 6 0 00-6 6v2.586l-.707.707A1 1 0 004 13h12a1 1 0 00.707-1.707L16 10.586V8a6 6 0 00-6-6zM2 14a2 2 0 002 2h12a2 2 0 002-2H2z"/>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Notifikasi</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
