<!DOCTYPE html>
<html lang="en" class="h-full bg-white">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TNA - Pendidikan &amp; Pelatihan</title>

    <!-- Icons & CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/tna.css') }}">
</head>

<body class="min-h-full bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 text-gray-800">
    <!-- Global auth vars (pakai sebelum JS lain) -->
    <script>
        window.authToken = "{{ session('token') }}";
        window.currentUser = {
            id: {{ Auth::user()->id }},
            department_id: {{ Auth::user()->department_id ?? 'null' }},
            hospital_id: {{ Auth::user()->hospital_id ?? 'null' }}
        };
    </script>
    <script src="{{ asset('js/tna.js') }}"></script>

    <!-- Mobile menu btn -->
    <button class="mobile-menu-btn p-2 rounded-md bg-white shadow-md text-gray-600" onclick="window.toggleMobileMenu()">
        <i class="fas fa-bars"></i>
    </button>

    @include('components.sidebar-navbar')

    <!-- MAIN -->
    <div class="p-4 md:p-0 mt-8">
        <main class="md:pl-60 pr-5 flex-1 px-4 md:px-6 py-4 md:py-8 mt-0 md:mt-8">
            <!-- Hero -->
            <div class="glass-effect rounded-3xl p-6 md:p-8 mb-6 md:mb-8 shadow-xl">
                <h1 class="text-3xl md:text-4xl font-bold text-black mb-3">
                    <i class="fas fa-graduation-cap mr-3 text-green-500"></i>Training Need Assessment (TNA)
                </h1>
                <p class="text-gray-600 text-base md:text-lg">Catat seminar, pelatihan, dan pendidikan lanjutan staf sebagai dasar perencanaan pengembangan SDM.</p>
            </div>

            <!-- KPI cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
                <!-- Total Staff -->
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
                <!-- Seminar/Workshop -->
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
                <!-- Pelatihan -->
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
                <!-- Pendidikan Lanjutan -->
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

            <!-- Action buttons -->
            <div class="flex flex-wrap gap-2 md:gap-4 mb-6 md:mb-8">
                <button onclick="openAddTnaModal()" id="openAddTnaModalBtn" class="btn-green-tna btn-mobile">
                    <i class="fas fa-plus mr-2"></i>Tambah Data TNA
                </button>
                <button onclick="openAddStaffModal()" id="openAddStaffModalBtn" class="btn-blue-tna btn-mobile">
                    <i class="fas fa-user-plus mr-2"></i>Tambah Staff
                </button>
                <button onclick="exportToExcel()" class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-4 py-2 md:px-6 md:py-3 rounded-xl font-semibold shadow-lg card-hover flex items-center btn-mobile">
                    <i class="fas fa-download mr-2"></i>Export Excel
                </button>
                <button onclick="exportToPdf()" class="bg-gradient-to-r from-orange-400 to-red-500 hover:from-orange-500 hover:to-red-600 text-white px-4 py-2 md:px-6 md:py-3 rounded-xl font-semibold shadow-lg card-hover flex items-center btn-mobile">
                    <i class="fas fa-file-pdf mr-2"></i>Export PDF
                </button>
            </div>

            <!-- Manajemen Staff table -->
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
                                    <th class="px-3 py-3 text-left font-semibold"><i class="fas fa-user mr-2 text-[#0CC0DF]"></i>Nama</th>
                                    <th class="px-3 py-3 text-left font-semibold"><i class="fas fa-briefcase mr-2 text-[#0CC0DF]"></i>Jabatan</th>
                                    <th class="px-3 py-3 text-left font-semibold"><i class="fas fa-hospital-alt mr-2 text-[#0CC0DF]"></i>Ruangan</th>
                                    <th class="px-3 py-3 text-left font-semibold"><i class="fas fa-info-circle mr-2 text-[#0CC0DF]"></i>Status</th>
                                    <th class="px-3 py-3 text-left font-semibold rounded-tr-xl"><i class="fas fa-cogs mr-2 text-[#0CC0DF]"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="staffTableBody" class="bg-white divide-y divide-gray-200 text-gray-800"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- REKAP PENDIDIKAN & PELATIHAN -->
            <div class="glass-effect rounded-3xl shadow-xl overflow-hidden card-hover bg-white">
                <div class="bg-white p-4 md:p-6">
                    <h2 class="text-xl md:text-2xl font-bold text-black mb-3">
                        <i class="fas fa-chalkboard-teacher mr-3 text-green-500"></i>Rekap Pendidikan &amp; Pelatihan Staf
                    </h2>
                </div>
                <div class="p-2 md:p-6 overflow-x-auto">
                    <div class="min-w-full inline-block align-middle">
                        <table class="tna-table min-w-full text-sm bg-white rounded-2xl shadow-md">
                            <thead>
                                <tr class="bg-[#f9fcfe] text-black">
                                    <th class="px-3 py-3 text-left font-semibold rounded-tl-xl"><i class="fas fa-user mr-2 text-[#0CC0DF]"></i>Nama</th>
                                    <th class="px-3 py-3 text-left font-semibold"><i class="fas fa-microphone mr-2 text-[#0CC0DF]"></i>Seminar / Workshop</th>
                                    <th class="px-3 py-3 text-left font-semibold"><i class="fas fa-dumbbell mr-2 text-[#0CC0DF]"></i>Pelatihan</th>
                                    <th class="px-3 py-3 text-left font-semibold"><i class="fas fa-graduation-cap mr-2 text-[#0CC0DF]"></i>Pendidikan Lanjutan</th>
                                    <th class="px-3 py-3 text-left font-semibold"><i class="fas fa-calendar-alt mr-2 text-[#0CC0DF]"></i>Tanggal</th>
                                    <th class="px-3 py-3 text-left font-semibold rounded-tr-xl"><i class="fas fa-cogs mr-2 text-[#0CC0DF]"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tnaRecordsTableBody" class="bg-white divide-y divide-gray-200 text-gray-800"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- STAFF MODAL -->
    <div id="staffModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg p-5 w-full max-w-md">
            <div class="flex justify-between items-center mb-3">
                <h3 id="staffModalTitle" class="text-base md:text-lg font-bold">Tambah Staff Baru</h3>
                <button onclick="closeStaffModal()" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times"></i></button>
            </div>
            <form id="staffForm">
                <input type="hidden" id="staffId">
                <input type="hidden" id="userId" name="user_id" value="{{ Auth::user()->id }}">
                <input type="hidden" id="staffDepartment" value="{{ Auth::user()->department_id ?? 'null' }}">
                <input type="hidden" name="hospital_id" value="{{ Auth::user()->hospital_id ?? 'null' }}">
                <div class="mb-3">
                    <label class="block text-gray-700 text-xs md:text-sm mb-1" for="staffFullName">Nama Lengkap</label>
                    <input type="text" id="staffFullName" class="w-full px-3 py-2 border rounded-lg text-xs md:text-sm" required>
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 text-xs md:text-sm mb-1" for="staffPosition">Jabatan</label>
                    <select id="staffPosition" class="w-full px-3 py-2 border rounded-lg text-xs md:text-sm" required>
                        <option value="">Pilih Jabatan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 text-xs md:text-sm mb-1" for="staffStatus">Status</label>
                    <select id="staffStatus" class="w-full px-3 py-2 border rounded-lg text-xs md:text-sm" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                        <option value="Cuti">Cuti</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeStaffModal()" class="px-3 py-1 rounded-lg text-xs md:text-sm text-gray-700 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-3 py-1 rounded-lg text-xs md:text-sm text-white bg-green-500 hover:bg-green-600">Simpan</button>
                    <button type="button" id="deleteStaffBtn" onclick="deleteStaff()" class="px-3 py-1 rounded-lg text-xs md:text-sm text-white bg-red-500 hover:bg-red-600 hidden">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <!-- TNA MODAL -->
    <div id="tnaModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg p-5 w-full max-w-md">
            <div class="flex justify-between items-center mb-3">
                <h3 id="tnaModalTitle" class="text-lg md:text-xl font-bold text-gray-800">Tambah Data TNA</h3>
                <button type="button" onclick="closeTnaModal()" class="text-gray-500 hover:text-gray-700 text-lg"><i class="fas fa-times"></i></button>
            </div>
            <form id="tnaForm">
                <input type="hidden" id="tnaId">
                <!-- Staff -->
                <div class="mb-3 md:mb-4">
                    <label for="tnaStaffName" class="block text-gray-700 text-xs md:text-sm font-medium mb-1 md:mb-2">Pilih Staff</label>
                    <select id="tnaStaffName" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 text-sm" required>
                        <option value="">Pilih Staff</option>
                    </select>
                </div>
                <!-- Tanggal -->
                <div class="mb-3 md:mb-4">
    <label for="tanggal" class="block text-gray-700 text-xs md:text-sm font-medium mb-1 md:mb-2">Tanggal</label>
   <input type="date" id="tanggal" name="tanggal"
       value="{{ date('Y-m-d') }}" 
       class="w-full px-3 py-2 border rounded-lg text-sm" required>
</div>

                <!-- Seminar / Workshop / Webinar -->
                <div class="mb-3 md:mb-4">
                    <label for="seminarWorkshopWebinar" class="block text-gray-700 text-xs md:text-sm font-medium mb-1 md:mb-2">Seminar / Workshop / Webinar</label>
                    <select id="seminarWorkshopWebinar" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 text-sm">
                        <option value="">Pilih Kegiatan</option>
                        <option value="Seminar">Seminar</option>
                        <option value="Workshop">Workshop</option>
                        <option value="Webinar">Webinar</option>
                        <option value="Pelatihan Internal">Pelatihan Internal</option>
                        <option value="Pelatihan Eksternal">Pelatihan Eksternal</option>
                    </select>
                </div>
                <!-- Pelatihan -->
                <div class="mb-3 md:mb-4">
                    <label for="pelatihan" class="block text-gray-700 text-xs md:text-sm font-medium mb-1 md:mb-2">Pelatihan</label>
                    <select id="pelatihan" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 text-sm">
                        <option value="">Pilih Jenis Pelatihan</option>
                        <option value="K3">Keselamatan &amp; Kesehatan Kerja (K3)</option>
                        <option value="BTCLS">BTCLS</option>
                        <option value="PONEK">PONEK</option>
                        <option value="Manajemen Keperawatan">Manajemen Keperawatan</option>
                        <option value="ICT">Pelatihan ICT</option>
                    </select>
                </div>
                <!-- Pendidikan Lanjutan -->
                <div class="mb-4 md:mb-6">
                    <label for="pendidikanLanjutan" class="block text-gray-700 text-xs md:text-sm font-medium mb-1 md:mb-2">Pendidikan Lanjutan</label>
                    <select id="pendidikanLanjutan" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 text-sm">
                        <option value="">Pilih Jenjang</option>
                        <option value="D3">D3 Keperawatan</option>
                        <option value="D4">D4 Keperawatan</option>
                        <option value="S1/S.Kep">S1 / S.Kep</option>
                        <option value="S2/F.Kep">S2 / F.Kep</option>
                        <option value="Ners Spesialis">Ners Spesialis / Spesialis Keperawatan</option>
                        <option value="Doktor">Doktor</option>
                    </select>
                </div>
                <!-- Buttons -->
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeTnaModal()" class="animated-button bg-gray-200 text-gray-800 px-4 py-2 md:px-6 md:py-3 rounded-lg md:rounded-xl font-semibold hover:bg-gray-300 transition duration-300 text-xs md:text-sm">Batal</button>
                    <button type="submit" class="animated-button bg-purple-600 text-white px-4 py-2 md:px-6 md:py-3 rounded-lg md:rounded-xl font-semibold hover:bg-purple-700 transition duration-300 text-xs md:text-sm">Simpan</button>
                    <button type="button" id="deleteTnaBtn" onclick="deleteTnaRecord()" class="animated-button bg-red-600 text-white px-4 py-2 md:px-6 md:py-3 rounded-lg md:rounded-xl font-semibold hover:bg-red-700 transition duration-300 text-xs md:text-sm hidden">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <!-- GLOBAL LOADING OVERLAY -->
    <div id="global-loading-overlay" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-[9999] hidden">
        <div class="flex flex-col items-center space-y-4">
            <div class="relative w-20 h-20">
                <div class="absolute inset-0 border-4 border-blue-400 border-t-blue-600 rounded-full animate-spin"></div>
                <div class="absolute inset-2 border-4 border-white border-t-white rounded-full animate-spin-reverse" style="animation-duration: 1.5s;"></div>
                <div class="absolute inset-4 border-4 border-purple-400 border-t-purple-600 rounded-full animate-spin" style="animation-duration: 2s;"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="fas fa-sync-alt text-white text-3xl animate-pulse"></i>
                </div>
            </div>
            <p class="text-white text-lg font-semibold animate-pulse">Loading Data...</p>
        </div>
    </div>
</body>
</html>
