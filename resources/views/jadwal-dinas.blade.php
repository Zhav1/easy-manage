<!DOCTYPE html>
<html lang="en" class="h-full bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Jadwal Dinas Rumah Sakit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src={{ asset('js/dinas.js') }}></script>
    <link rel="stylesheet" href={{ asset('css/dinas.css') }}>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />        
</head>
<body class="min-h-full bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100">
    <script>
    window.authToken = "{{ session('token') }}";
    </script>

    @include('components.sidebar-navbar')
    
    <div class="pt-20 px-4 sm:pl-64 sm:pr-5 animate-fadeIn">
        <div class="p-4 sm:p-6 rounded-xl shadow-lg bg-white/80 backdrop-blur-sm dark:border-gray-700 dark:bg-white-800/80">
            <!-- Header -->
            <div class="text-center mb-6">
                <div class="inline-block p-4 transform hover:scale-105 transition-all duration-300">
                    <h1 class="text-2xl sm:text-4xl font-bold text-black mb-3">Jadwal Dinas</h1>
                </div>
                <div class="flex justify-center">
                    <img src="{{ asset('images/icon-jadwal-piket.png') }}" alt="Logo Jadwal Dinas"
                         class="h-16 sm:h-20 w-auto rounded-lg transition-transform duration-300 hover:scale-105" />
                </div>
            </div>

            <!-- Profile Kepala Ruangan - Responsive -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="flex flex-col md:flex-row">
                    <div class="p-4 flex justify-center md:w-1/4">
                        <div class="relative">
                            <img class="h-24 w-24 sm:h-32 sm:w-32 rounded-full object-cover border-4 border-green-500" 
                                    src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('images/p.png') }}" alt="Profile Photo">
                            <div class="absolute bottom-0 right-0 bg-green-500 rounded-full p-1 border-2 border-white">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 md:w-3/4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-lg sm:text-xl font-bold text-gray-800">{{ Auth::user()->name }}</h2>
                                <p class="text-gray-600 text-xs sm:text-sm mb-1">Kepala Ruangan {{ Auth::user()->department->name }}</p>
                                <div class="flex items-center text-gray-600 text-xs sm:text-sm mb-3">
                                    <i class="fas fa-hospital mr-2"></i>
                                    <span>{{ Auth::user()->hospital->name }}</span>
                                </div>
                            </div>
                            <div class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                                Aktif
                            </div>
                        </div>
                        <div class="bg-gray-50 p-2 rounded-lg text-center">
                            <p class="text-gray-500 text-xs">Total Staff</p>
                            <p class="text-lg font-bold" id="totalStaffCount"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jadwal Dinas Section -->
            <div class="mt-6">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-calendar-alt mr-2 text-green-600"></i>
                    Kalender Jadwal Dinas
                </h2>

                <!-- Calendar Container - Responsive -->
                <div id="calendar" class="mb-6 text-xs sm:text-sm"></div>

                <!-- Staff Management Section -->
                <div class="mb-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-3 gap-2">
                        <h3 class="text-base sm:text-lg font-bold text-gray-800 flex items-center">
                            <i class="fas fa-users mr-2 text-green-600"></i>
                            Manajemen Staff
                        </h3>
                        <div class="flex gap-2">
                            <button onclick="openAddStaffModal()" class="btn-green px-2 py-1 sm:px-3 sm:py-1 rounded-lg text-xs sm:text-sm">
                                <i class="fas fa-plus mr-1"></i> Tambah Staff
                            </button>
                            <button onclick="openAddPositionModal()" class="btn-green px-2 py-1 sm:px-3 sm:py-1 rounded-lg text-xs sm:text-sm">
                                <i class="fas fa-plus mr-1"></i> Tambah Jabatan
                            </button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto bg-white rounded-lg shadow">
                        <table class="w-full min-w-max">
                            <thead>
                                <tr class="text-xs sm:text-sm">
                                    <th class="py-2 px-3">No</th>
                                    <th class="py-2 px-3">Nama</th>
                                    <th class="py-2 px-3">Jabatan</th>
                                    <th class="py-2 px-3">Ruangan</th>
                                    <th class="py-2 px-3">Status</th>
                                    <th class="py-2 px-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="staffTableBody" class="text-xs sm:text-sm">
                                <!-- Staff data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Shift Legend -->
                <div class="bg-white p-3 rounded-lg shadow border border-gray-200">
                    <h3 class="font-medium text-gray-800 text-sm mb-2">Keterangan Shift:</h3>
                    <div class="flex flex-col sm:flex-row sm:flex-wrap gap-3">
                        <div class="flex items-center text-xs sm:text-sm">
                            <span class="shift-badge-legend shift-pagi mr-2">P</span>
                            <span>Pagi (07:00 - 14:00)</span>
                        </div>
                        <div class="flex items-center text-xs sm:text-sm">
                            <span class="shift-badge-legend shift-sore mr-2">S</span>
                            <span>Sore (14:00 - 21:00)</span>
                        </div>
                        <div class="flex items-center text-xs sm:text-sm">
                            <span class="shift-badge-legend shift-malam mr-2">M</span>
                            <span>Malam (21:00 - 07:00)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Schedule Modal - Responsive -->
    <div id="scheduleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg p-4 w-full mx-4 sm:max-w-md max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-3">
                <h3 id="modalTitle" class="text-lg font-bold">Tambah Jadwal Dinas</h3>
                <button onclick="closeScheduleModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="scheduleForm">
                <input type="hidden" id="eventId">
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1" for="staffName">Nama Staff</label>
                    <select id="staffName" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                        <option value="">Pilih Staff</option>
                        <!-- Staff options will be loaded dynamically -->
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1" for="shiftType">Jenis Shift</label>
                    <select id="shiftType" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                        <option value="">Pilih Shift</option>
                    </select>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="block text-gray-700 text-sm mb-1" for="startDate">Tanggal Mulai</label>
                        <input type="date" id="startDate" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm mb-1" for="endDate">Tanggal Selesai</label>
                        <input type="date" id="endDate" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 justify-between sm:justify-end">
                    <button type="button" onclick="closeScheduleModal()" class="px-2 py-1 sm:px-3 sm:py-1 rounded-lg text-xs sm:text-sm text-gray-700 hover:bg-gray-100 btn-outline-green">Batal</button>
                    <button type="submit" class="px-2 py-1 sm:px-3 sm:py-1 rounded-lg text-xs sm:text-sm text-white btn-green">Simpan</button>
                    <button type="button" id="deleteBtn" onclick="deleteEvent()" class="px-2 py-1 sm:px-3 sm:py-1 rounded-lg text-xs sm:text-sm text-white btn-red hidden">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add/Edit Staff Modal - Responsive -->
    <div id="staffModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg p-4 w-full mx-4 sm:max-w-md max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-3">
                <h3 id="staffModalTitle" class="text-lg font-bold">Tambah Staff Baru</h3>
                <button onclick="closeStaffModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="staffForm">
                <input type="hidden" id="staffId">
                <input type="hidden" id="userId" name="user_id" value="{{ auth()->user()->id }}">
                <input type="hidden" id="staffDepartment" value="{{ auth()->user()->department_id }}">
                <input type="hidden" id="staffHospital" value="{{ auth()->user()->hospital_id }}">
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1" for="staffFullName">Nama Lengkap</label>
                    <input type="text" id="staffFullName" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1" for="staffPosition">Jabatan</label>
                    <select id="staffPosition" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                        <option value="">Pilih Jabatan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1" for="staffStatus">Status</label>
                    <select id="staffStatus" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                        <option value="Cuti">Cuti</option>
                    </select>
                </div>
                <div class="flex flex-wrap gap-2 justify-between sm:justify-end">
                    <button type="button" onclick="closeStaffModal()" class="px-2 py-1 sm:px-3 sm:py-1 rounded-lg text-xs sm:text-sm text-gray-700 hover:bg-gray-100 btn-outline-green">Batal</button>
                    <button type="submit" class="px-2 py-1 sm:px-3 sm:py-1 rounded-lg text-xs sm:text-sm text-white btn-green">Simpan</button>
                    <button type="button" id="deleteStaffBtn" onclick="deleteStaff()" class="px-2 py-1 sm:px-3 sm:py-1 rounded-lg text-xs sm:text-sm text-white btn-red hidden">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Position Modal - Responsive -->
    <div id="positionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg p-4 w-full mx-4 sm:max-w-md max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-3">
                <h3 id="positionModalTitle" class="text-lg font-bold">Tambah Jabatan Baru</h3>
                <button onclick="closePositionModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="positionForm">
                <input type="hidden" id="positionId">
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1" for="positionName">Nama Jabatan</label>
                    <input type="text" id="positionName" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1" for="positionDescription">Deskripsi</label>
                    <textarea id="positionDescription" class="w-full px-3 py-2 border rounded-lg text-sm"></textarea>
                </div>
                <div class="flex flex-wrap gap-2 justify-between sm:justify-end">
                    <button type="button" onclick="closePositionModal()" 
                            class="px-2 py-1 sm:px-3 sm:py-1 rounded-lg text-xs sm:text-sm text-gray-700 hover:bg-gray-100 btn-outline-green">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-2 py-1 sm:px-3 sm:py-1 rounded-lg text-xs sm:text-sm text-white btn-green">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js'></script>
<style>
    /* Responsive adjustments */
@media (max-width: 640px) {
    .fc-header-toolbar {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .fc-toolbar-chunk {
        margin-bottom: 0.5rem;
    }
    
    .fc-today-button {
        margin-top: 0.5rem;
    }
    
    .fc-col-header-cell-cushion {
        font-size: 0.7rem;
        padding: 2px;
    }
    
    .fc-daygrid-day-number {
        font-size: 0.7rem;
    }
    
    .fc-event-time, .fc-event-title {
        font-size: 0.6rem;
    }
}

/* Mobile sidebar adjustment */
@media (max-width: 768px) {
    .pl-60 {
        padding-left: 1rem;
    }
    .pr-5 {
        padding-right: 1rem;
    }
}

/* Table responsive */
.staff-table {
    width: 100%;
    border-collapse: collapse;
}

.staff-table th, .staff-table td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

@media (max-width: 640px) {
    .staff-table th, .staff-table td {
        padding: 6px 4px;
        font-size: 0.75rem;
    }
}
</style>
</body>
</html>