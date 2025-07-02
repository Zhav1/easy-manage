<!DOCTYPE html>
<html lang="en" class="h-full bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    
    <div class=" pt-20 pl-60 pr-5 animate-fadeIn">
        <div class="p-6 rounded-xl shadow-lg bg-white/80 backdrop-blur-sm dark:border-gray-700 dark:bg-white-800/80">
            <!-- Header -->
            <div class="text-center mb-6">
                <div class="inline-block p-4 transform hover:scale-105 transition-all duration-300">
                    <h1 class="text-4xl font-bold text-black mb-3">Jadwal Dinas</h1>
                </div>
                <div class="flex justify-center">
                    <img src="{{ asset('images/icon-jadwal-piket.png') }}" alt="Logo Jadwal Dinas"
                         class="h-20 w-auto rounded-lg transition-transform duration-300 hover:scale-105" />
                </div>
            </div>

            <!-- Profile Kepala Ruangan - Compact Version -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="md:flex">
                    <div class="md:w-1/4 p-4 flex justify-center">
                        <div class="relative">
                            <img class="h-32 w-32 rounded-full object-cover border-4 border-green-500" 
                                 src="images/foto-formal.png" alt="Profile Photo">
                            <div class="absolute bottom-0 right-0 bg-green-500 rounded-full p-1 border-2 border-white">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                    </div>
                    <div class="md:w-3/4 p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">{{ Auth::user()->name }}</h2>
                                <p class="text-gray-600 text-sm mb-1">Kepala Ruangan {{ Auth::user()->department->name }}</p>
                                <div class="flex items-center text-gray-600 text-sm mb-3">
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
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-calendar-alt mr-2 text-green-600"></i>
                    Kalender Jadwal Dinas
                </h2>

                <!-- Calendar Container - Smaller -->
                <div id="calendar" class="mb-6"></div>

                <!-- Staff Management Section -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="fas fa-users mr-2 text-green-600"></i>
                            Manajemen Staff
                        </h3>
                        <div class="flex- justify-between">
                            <button onclick="openAddStaffModal()" class="btn-green px-3 py-1 rounded-lg text-sm">
                                <i class="fas fa-plus mr-1"></i> Tambah Staff
                            </button>
                            <button onclick="openAddPositionModal()" class="btn-green px-3 py-1 rounded-lg text-sm">
                                <i class="fas fa-plus mr-1"></i> Tambah Jabatan
                            </button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto bg-white rounded-lg shadow">
                        <table class="staff-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Ruangan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="staffTableBody">
                                <!-- Staff data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Shift Legend -->
                <div class="bg-white p-3 rounded-lg shadow border border-gray-200 ">
                    <h3 class="font-medium text-gray-800 text-sm mb-2">Keterangan Shift:</h3>
                    <div class="flex flex-wrap gap-3">
                        <div class="flex items-center text-sm justify-between">
                            <span class="shift-badge-legend shift-pagi mr-2">P</span>
                            <span>Pagi (07:00 - 14:00)</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="shift-badge-legend shift-sore mr-2">S</span>
                            <span>Sore (14:00 - 21:00)</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="shift-badge-legend shift-malam mr-2">M</span>
                            <span>Malam (21:00 - 07:00)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Schedule Modal -->
    <div id="scheduleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-5 w-full max-w-md">
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
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="block text-gray-700 text-sm mb-1" for="startDate">Tanggal Mulai</label>
                        <input type="date" id="startDate" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm mb-1" for="endDate">Tanggal Selesai</label>
                        <input type="date" id="endDate" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                    </div>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeScheduleModal()" class="px-3 py-1 rounded-lg text-sm text-gray-700 hover:bg-gray-100 btn-outline-green">Batal</button>
                    <button type="submit" class="px-3 py-1 rounded-lg text-sm text-white btn-green">Simpan</button>
                    <button type="button" id="deleteBtn" onclick="deleteEvent()" class="px-3 py-1 rounded-lg text-sm text-white btn-red hidden">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add/Edit Staff Modal -->
    <div id="staffModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-5 w-full max-w-md">
            <div class="flex justify-between items-center mb-3">
                <h3 id="staffModalTitle" class="text-lg font-bold">Tambah Staff Baru</h3>
                <button onclick="closeStaffModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="staffForm">
                <input type="hidden" id="staffId">
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
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeStaffModal()" class="px-3 py-1 rounded-lg text-sm text-gray-700 hover:bg-gray-100 btn-outline-green">Batal</button>
                    <button type="submit" class="px-3 py-1 rounded-lg text-sm text-white btn-green">Simpan</button>
                    <button type="button" id="deleteStaffBtn" onclick="deleteStaff()" class="px-3 py-1 rounded-lg text-sm text-white btn-red hidden">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Position Modal -->
    <div id="positionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-5 w-full max-w-md">
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
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closePositionModal()" 
                            class="px-3 py-1 rounded-lg text-sm text-gray-700 hover:bg-gray-100 btn-outline-green">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-3 py-1 rounded-lg text-sm text-white btn-green">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js'></script>

</body>
</html>