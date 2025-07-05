<!DOCTYPE html>
<html lang="en" class="h-full bg-white">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>TNA - Pendidikan & Pelatihan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/tna.css') }}">
    <style>
        /* Mobile-specific styles */
        @media (max-width: 768px) {
            body {
                overflow-x: hidden;
            }
            .sidebar-transform {
                transform: translateX(-100%);
            }
            .sidebar-transform.mobile-show {
                transform: translateX(0);
            }
            main {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
                margin-top: 4rem !important;
            }
            .glass-effect {
                padding: 1.5rem !important;
            }
            .grid-cols-1 {
                grid-template-columns: 1fr !important;
            }
            .flex-wrap {
                flex-wrap: wrap;
            }
            .flex-wrap button {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            .management-table, .tna-table {
                font-size: 0.875rem;
            }
            .management-table th, .tna-table th {
                padding: 0.5rem !important;
            }
            .management-table td, .tna-table td {
                padding: 0.5rem !important;
                white-space: nowrap;
            }
            .text-4xl {
                font-size: 1.75rem !important;
            }
            .text-2xl {
                font-size: 1.5rem !important;
            }
            .text-lg {
                font-size: 1rem !important;
            }
            .p-8 {
                padding: 1.5rem !important;
            }
            .p-6 {
                padding: 1rem !important;
            }
            .mobile-menu-btn {
                display: block !important;
            }
        }
        
        /* General mobile-friendly styles */
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 50;
        }
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
        }
        .btn-mobile {
            padding: 0.75rem 1rem !important;
            font-size: 0.875rem !important;
        }
    </style>
</head>
<body class="min-h-full bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 text-gray-800">
    <!-- Mobile menu button -->
    <button class="mobile-menu-btn p-2 rounded-md bg-white shadow-md text-gray-600" onclick="toggleMobileMenu()">
        <i class="fas fa-bars"></i>
    </button>

    @include('components.sidebar-navbar')
    
    <div class="p-4 md:p-6">
        <main class="md:pl-60 pr-5 flex-1 px-4 md:px-6 py-4 md:py-8 mt-0 md:mt-8">
            <!-- Glass effect header -->
            <div class="glass-effect rounded-3xl p-6 md:p-8 mb-6 md:mb-8 shadow-xl">
                <h1 class="text-3xl md:text-4xl font-bold text-black mb-3">
                    <i class="fas fa-graduation-cap mr-3 text-green-500"></i>Training Need Assessment (TNA)
                </h1>
                <p class="text-gray-600 text-base md:text-lg">Catat seminar, pelatihan, dan pendidikan lanjutan staf sebagai dasar perencanaan pengembangan SDM.</p>
            </div>

            <!-- Stats cards - stacked on mobile -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
                <div class="bg-white text-gray-700 p-4 md:p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs md:text-sm font-medium uppercase tracking-wider">Total Staff</p>
                            <p class="text-2xl md:text-3xl font-bold mt-1 md:mt-2" id="totalStaffCount">0</p>
                            <p class="text-xs text-gray-400 mt-1">Personil aktif</p>
                        </div>
                        <div class="bg-blue-50 p-2 md:p-3 rounded-full text-blue-500">
                            <i class="fas fa-users text-lg md:text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white text-gray-700 p-4 md:p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs md:text-sm font-medium uppercase tracking-wider">Seminar/Workshop</p>
                            <p class="text-2xl md:text-3xl font-bold mt-1 md:mt-2" id="totalSeminarCount">0</p>
                            <p class="text-xs text-gray-400 mt-1">Kegiatan tahun ini</p>
                        </div>
                        <div class="bg-green-50 p-2 md:p-3 rounded-full text-green-500">
                            <i class="fas fa-chalkboard-teacher text-lg md:text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white text-gray-700 p-4 md:p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs md:text-sm font-medium uppercase tracking-wider">Pelatihan</p>
                            <p class="text-2xl md:text-3xl font-bold mt-1 md:mt-2" id="totalPelatihanCount">0</p>
                            <p class="text-xs text-gray-400 mt-1">Program terselesaikan</p>
                        </div>
                        <div class="bg-amber-50 p-2 md:p-3 rounded-full text-amber-500">
                            <i class="fas fa-medal text-lg md:text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white text-gray-700 p-4 md:p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs md:text-sm font-medium uppercase tracking-wider">Pendidikan Lanjutan</p>
                            <p class="text-2xl md:text-3xl font-bold mt-1 md:mt-2" id="totalPendidikanLanjutanCount">0</p>
                            <p class="text-xs text-gray-400 mt-1">Staf berkembang</p>
                        </div>
                        <div class="bg-purple-50 p-2 md:p-3 rounded-full text-purple-500">
                            <i class="fas fa-user-graduate text-lg md:text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action buttons - stacked on mobile -->
            <div class="flex flex-wrap gap-2 md:gap-4 mb-6 md:mb-8">
                <button onclick="openAddTnaModal()" class="btn-green-tna btn-mobile">
                    <i class="fas fa-plus mr-2"></i>Tambah Data TNA
                </button>
                <button onclick="openAddStaffModal()" class="btn-blue-tna btn-mobile">
                    <i class="fas fa-user-plus mr-2"></i>Tambah Staff
                </button>
                <button onclick="exportToExcel()" class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-4 py-2 md:px-6 md:py-3 rounded-xl font-semibold shadow-lg card-hover flex items-center btn-mobile">
                    <i class="fas fa-download mr-2"></i>Export Excel
                </button>
                <button onclick="exportToPdf()" class="bg-gradient-to-r from-orange-400 to-red-500 hover:from-orange-500 hover:to-red-600 text-white px-4 py-2 md:px-6 md:py-3 rounded-xl font-semibold shadow-lg card-hover flex items-center btn-mobile">
                    <i class="fas fa-file-pdf mr-2"></i>Export PDF
                </button>
            </div>

            <!-- Staff Management Table -->
            <div class="glass-effect rounded-3xl shadow-xl overflow-hidden card-hover bg-white mb-6 md:mb-8">
                <div class="bg-white p-4 md:p-6">
                    <h2 class="text-xl md:text-2xl font-bold text-black mb-3">
                        <i class="fas fa-users-cog mr-3 text-blue-500"></i>Manajemen Staff
                    </h2>
                </div>
                <div class="p-2 md:p-6 overflow-x-auto">
                    <div class="min-w-full inline-block align-middle">
                        <table class="management-table min-w-full text-sm bg-white rounded-2xl shadow-md">
                            <thead>
                                <tr class="bg-[#f9fcfe] text-black">
                                    <th class="px-3 py-3 text-left font-semibold rounded-tl-xl">No</th>
                                    <th class="px-3 py-3 text-left font-semibold">
                                        <i class="fas fa-user mr-2 text-[#0CC0DF]"></i>Nama
                                    </th>
                                    <th class="px-3 py-3 text-left font-semibold">
                                        <i class="fas fa-briefcase mr-2 text-[#0CC0DF]"></i>Jabatan
                                    </th>
                                    <th class="px-3 py-3 text-left font-semibold">
                                        <i class="fas fa-hospital-alt mr-2 text-[#0CC0DF]"></i>Ruangan
                                    </th>
                                    <th class="px-3 py-3 text-left font-semibold">
                                        <i class="fas fa-info-circle mr-2 text-[#0CC0DF]"></i>Status
                                    </th>
                                    <th class="px-3 py-3 text-left font-semibold rounded-tr-xl">
                                        <i class="fas fa-cogs mr-2 text-[#0CC0DF]"></i>Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="staffTableBody" class="bg-white divide-y divide-gray-200 text-gray-800">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Training Records Table -->
            <div class="glass-effect rounded-3xl shadow-xl overflow-hidden card-hover bg-white">
                <div class="bg-white p-4 md:p-6">
                    <h2 class="text-xl md:text-2xl font-bold text-black mb-3">
                        <i class="fas fa-chalkboard-teacher mr-3 text-green-500"></i>Rekap Pendidikan & Pelatihan Staf
                    </h2>
                </div>
                <div class="p-2 md:p-6 overflow-x-auto">
                    <div class="min-w-full inline-block align-middle">
                        <table class="tna-table min-w-full text-sm bg-white rounded-2xl shadow-md">
                            <thead>
                                <tr class="bg-[#f9fcfe] text-black">
                                    <th class="px-3 py-3 text-left font-semibold rounded-tl-xl">
                                        <i class="fas fa-user mr-2 text-[#0CC0DF]"></i>Nama
                                    </th>
                                    <th class="px-3 py-3 text-left font-semibold">
                                        <i class="fas fa-microphone mr-2 text-[#0CC0DF]"></i>Seminar / Workshop
                                    </th>
                                    <th class="px-3 py-3 text-left font-semibold">
                                        <i class="fas fa-dumbbell mr-2 text-[#0CC0DF]"></i>Pelatihan
                                    </th>
                                    <th class="px-3 py-3 text-left font-semibold">
                                        <i class="fas fa-graduation-cap mr-2 text-[#0CC0DF]"></i>Pendidikan Lanjutan
                                    </th>
                                    <th class="px-3 py-3 text-left font-semibold rounded-tr-xl">
                                        <i class="fas fa-cogs mr-2 text-[#0CC0DF]"></i>Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tnaRecordsTableBody" class="bg-white divide-y divide-gray-200 text-gray-800">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modals (unchanged from your original) -->
    <div id="staffModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg p-5 w-full max-w-md">
            <!-- ... your existing modal content ... -->
        </div>
    </div>

    <div id="tnaModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg p-5 w-full max-w-md">
            <!-- ... your existing modal content ... -->
        </div>
    </div>

    <script>
        window.authToken = "{{ session('token') }}";
        
        // Mobile menu toggle function
        function toggleMobileMenu() {
            const sidebar = document.querySelector('.sidebar-transform');
            sidebar.classList.toggle('mobile-show');
        }
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar-transform');
            const mobileBtn = document.querySelector('.mobile-menu-btn');
            
            if (!sidebar.contains(event.target) && !mobileBtn.contains(event.target)) {
                sidebar.classList.remove('mobile-show');
            }
        });
    </script>
    <script src="{{ asset('js/tna.js') }}"></script>
</body>
</html>