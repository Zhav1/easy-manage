<!DOCTYPE html>
<html lang="en" class="h-full bg-white w-screen">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Jadwal Dinas Rumah Sakit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <style>
        .animate-fadeIn {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.6s ease-out forwards;
        }

        .shift-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
            border-radius: 0.25rem;
            display: inline-block;
            margin: 0.1rem;
        }

        .shift-pagi {
            background-color: #FEF3C7;
            color: #92400E;
        }

        .shift-sore {
            background-color: #DBEAFE;
            color: #1E40AF;
        }

        .shift-malam {
            background-color: #E5E7EB;
            color: #4B5563;
        }

        /* FullCalendar custom styles */
        .fc-event {
            cursor: pointer;
            font-size: 0.8em;
            padding: 2px 4px;
            margin: 1px 2px;
        }

        .fc-event-pagi {
            background-color: #FEF3C7;
            border-color: #F59E0B;
            color: #92400E;
        }

        .fc-event-sore {
            background-color: #DBEAFE;
            border-color: #3B82F6;
            color: #1E40AF;
        }

        .fc-event-malam {
            background-color: #E5E7EB;
            border-color: #6B7280;
            color: #4B5563;
        }

        .fc-toolbar-title {
            font-size: 1.1em;
        }
        
        /* Smaller calendar */
        .fc .fc-toolbar {
            padding: 0.5em 0;
        }
        
        .fc .fc-button {
            padding: 0.3em 0.6em;
            font-size: 0.9em;
        }
        
        .fc .fc-view-harness {
            min-height: 400px;
        }
        
        /* Green buttons */
        .btn-green {
            background-color: #10B981;
            color: white;
            border: none;
        }
        
        .btn-green:hover {
            background-color: #059669;
        }
        
        .btn-outline-green {
            background-color: transparent;
            color: #10B981;
            border: 1px solid #10B981;
        }
        
        .btn-outline-green:hover {
            background-color: #ECFDF5;
        }
        
        .btn-red {
            background-color: #EF4444;
            color: white;
            border: none;
        }
        
        .btn-red:hover {
            background-color: #DC2626;
        }
        
        /* Staff table styles */
        .staff-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .staff-table th, .staff-table td {
            padding: 0.5rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .staff-table th {
            background-color: #f9fafb;
            font-weight: 600;
        }
        
        .staff-table tr:hover {
            background-color: #f3f4f6;
        }
        html {
  scrollbar-width: none;
}

/* Untuk IE/Edge */
body {
  -ms-overflow-style: none;
}

/* Pastikan konten utama bisa scroll */
.main-content {
  overflow-y: scroll;
  -webkit-overflow-scrolling: touch; /* Untuk scroll halus di mobile */
}
    </style>
</head>
<body class="min-h-full">
    @include('components.sidebar-navbar')
    
    <div class="p-4 pt-20 pl-60 pr-5 animate-fadeIn">
        <div class="p-6 rounded-xl shadow-lg bg-white/80 backdrop-blur-sm dark:border-gray-700 dark:bg-white-800/80">
            <!-- Header -->
            <div class="text-center mb-6">
                <div class="inline-block p-4 transform hover:scale-105 transition-all duration-300">
                    <h1 class="text-2xl font-bold text-green-500 tracking-wide">Jadwal Dinas</h1>
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
                                 src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="Profile Photo">
                            <div class="absolute bottom-0 right-0 bg-green-500 rounded-full p-1 border-2 border-white">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                    </div>
                    <div class="md:w-3/4 p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">Dr. Ahmad Budiman, S.Kep, Ns</h2>
                                <p class="text-gray-600 text-sm mb-1">Kepala Ruangan Rawat Inap</p>
                                <div class="flex items-center text-gray-600 text-sm mb-3">
                                    <i class="fas fa-hospital mr-2"></i>
                                    <span>RS Adam Malik Medan</span>
                                </div>
                            </div>
                            <div class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                                Aktif
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-2 mt-4">
                            <div class="bg-gray-50 p-2 rounded-lg text-center">
                                <p class="text-gray-500 text-xs">Total Ruangan</p>
                                <p class="text-lg font-bold">4</p>
                            </div>
                            <div class="bg-gray-50 p-2 rounded-lg text-center">
                                <p class="text-gray-500 text-xs">Total Staff</p>
                                <p class="text-lg font-bold" id="totalStaffCount">68</p>
                            </div>
                            <div class="bg-gray-50 p-2 rounded-lg text-center">
                                <p class="text-gray-500 text-xs">Masa Jabatan</p>
                                <p class="text-lg font-bold">3 Tahun</p>
                            </div>
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
                        <button onclick="openAddStaffModal()" class="btn-green px-3 py-1 rounded-lg text-sm">
                            <i class="fas fa-plus mr-1"></i> Tambah Staff
                        </button>
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
                <div class="bg-white p-3 rounded-lg shadow border border-gray-200">
                    <h3 class="font-medium text-gray-800 text-sm mb-2">Keterangan Shift:</h3>
                    <div class="flex flex-wrap gap-3">
                        <div class="flex items-center text-sm">
                            <span class="shift-badge shift-pagi mr-2">P</span>
                            <span>Pagi (07:00 - 14:00)</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="shift-badge shift-sore mr-2">S</span>
                            <span>Sore (14:00 - 21:00)</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="shift-badge shift-malam mr-2">M</span>
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
                        <option value="P">Pagi (07:00 - 14:00)</option>
                        <option value="S">Sore (14:00 - 21:00)</option>
                        <option value="M">Malam (21:00 - 07:00)</option>
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
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1" for="staffFullName">Nama Lengkap</label>
                    <input type="text" id="staffFullName" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1" for="staffPosition">Jabatan</label>
                    <select id="staffPosition" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                        <option value="">Pilih Jabatan</option>
                        <option value="Perawat">Perawat</option>
                        <option value="Dokter">Dokter</option>
                        <option value="Bidan">Bidan</option>
                        <option value="Apoteker">Apoteker</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1" for="staffDepartment">Ruangan</label>
                    <select id="staffDepartment" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                        <option value="">Pilih Ruangan</option>
                        <option value="Rawat Inap">Rawat Inap</option>
                        <option value="IGD">IGD</option>
                        <option value="ICU">ICU</option>
                        <option value="Radiologi">Radiologi</option>
                        <option value="Laboratorium">Laboratorium</option>
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

    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js'></script>
    
    <script>
        // Staff data
        let staffData = [
            { id: 1, name: 'Mita', position: 'Perawat', department: 'Rawat Inap', status: 'Aktif' },
            { id: 2, name: 'Yani', position: 'Perawat', department: 'Rawat Inap', status: 'Aktif' },
            { id: 3, name: 'Yono', position: 'Perawat', department: 'Rawat Inap', status: 'Aktif' },
            { id: 4, name: 'Rudi', position: 'Perawat', department: 'IGD', status: 'Aktif' },
            { id: 5, name: 'Siti', position: 'Perawat', department: 'IGD', status: 'Aktif' },
            { id: 6, name: 'Andi', position: 'Perawat', department: 'ICU', status: 'Aktif' },
            { id: 7, name: 'Budi', position: 'Perawat', department: 'ICU', status: 'Aktif' }
        ];

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize staff table
            renderStaffTable();
            updateTotalStaffCount();
            
            // Initialize calendar
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                height: 'auto',
                events: [
                    {
                        id: '1',
                        title: 'Mita (P)',
                        start: '2025-06-01T07:00:00',
                        end: '2025-06-01T14:00:00',
                        className: 'fc-event-pagi',
                        extendedProps: {
                            staff: 'Mita',
                            shift: 'P'
                        }
                    },
                    {
                        id: '2',
                        title: 'Yani (S)',
                        start: '2025-06-01T14:00:00',
                        end: '2025-06-01T21:00:00',
                        className: 'fc-event-sore',
                        extendedProps: {
                            staff: 'Yani',
                            shift: 'S'
                        }
                    },
                    {
                        id: '3',
                        title: 'Yono (P)',
                        start: '2025-06-02T07:00:00',
                        end: '2025-06-02T14:00:00',
                        className: 'fc-event-pagi',
                        extendedProps: {
                            staff: 'Yono',
                            shift: 'P'
                        }
                    },
                    {
                        id: '4',
                        title: 'Mita (S)',
                        start: '2025-06-02T14:00:00',
                        end: '2025-06-02T21:00:00',
                        className: 'fc-event-sore',
                        extendedProps: {
                            staff: 'Mita',
                            shift: 'S'
                        }
                    },
                    {
                        id: '5',
                        title: 'Yani (P)',
                        start: '2025-06-03T07:00:00',
                        end: '2025-06-03T14:00:00',
                        className: 'fc-event-pagi',
                        extendedProps: {
                            staff: 'Yani',
                            shift: 'P'
                        }
                    },
                    {
                        id: '6',
                        title: 'Yono (S)',
                        start: '2025-06-03T14:00:00',
                        end: '2025-06-03T21:00:00',
                        className: 'fc-event-sore',
                        extendedProps: {
                            staff: 'Yono',
                            shift: 'S'
                        }
                    },
                    {
                        id: '7',
                        title: 'Mita (P)',
                        start: '2025-06-04T07:00:00',
                        end: '2025-06-04T14:00:00',
                        className: 'fc-event-pagi',
                        extendedProps: {
                            staff: 'Mita',
                            shift: 'P'
                        }
                    },
                    {
                        id: '8',
                        title: 'Yani (M)',
                        start: '2025-06-04T21:00:00',
                        end: '2025-06-05T07:00:00',
                        className: 'fc-event-malam',
                        extendedProps: {
                            staff: 'Yani',
                            shift: 'M'
                        }
                    }
                ],
                eventClick: function(info) {
                    openEditModal(info.event);
                },
                dateClick: function(info) {
                    openAddModal(info.dateStr);
                },
                eventContent: function(arg) {
                    // Custom event display
                    const shiftBadge = document.createElement('div');
                    shiftBadge.classList.add('shift-badge');
                    
                    if (arg.event.extendedProps.shift === 'P') {
                        shiftBadge.classList.add('shift-pagi');
                        shiftBadge.innerHTML = `${arg.event.extendedProps.staff} (P)`;
                    } else if (arg.event.extendedProps.shift === 'S') {
                        shiftBadge.classList.add('shift-sore');
                        shiftBadge.innerHTML = `${arg.event.extendedProps.staff} (S)`;
                    } else {
                        shiftBadge.classList.add('shift-malam');
                        shiftBadge.innerHTML = `${arg.event.extendedProps.staff} (M)`;
                    }
                    
                    const arrayOfDomNodes = [shiftBadge];
                    return { domNodes: arrayOfDomNodes };
                }
            });

            calendar.render();

            // Staff table functions
            function renderStaffTable() {
                const tbody = document.getElementById('staffTableBody');
                tbody.innerHTML = '';
                
                staffData.forEach((staff, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${staff.name}</td>
                        <td>${staff.position}</td>
                        <td>${staff.department}</td>
                        <td>
                            <span class="px-2 py-1 rounded-full text-xs ${
                                staff.status === 'Aktif' ? 'bg-green-100 text-green-800' : 
                                staff.status === 'Cuti' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'
                            }">
                                ${staff.status}
                            </span>
                        </td>
                        <td class="flex space-x-2">
                            <button onclick="openEditStaffModal(${staff.id})" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="confirmDeleteStaff(${staff.id})" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
                
                // Update staff dropdown in schedule modal
                updateStaffDropdown();
            }
            
            function updateStaffDropdown() {
                const select = document.getElementById('staffName');
                select.innerHTML = '<option value="">Pilih Staff</option>';
                
                staffData.forEach(staff => {
                    if (staff.status === 'Aktif') {
                        const option = document.createElement('option');
                        option.value = staff.name;
                        option.textContent = `${staff.name} (${staff.department})`;
                        select.appendChild(option);
                    }
                });
            }
            
            function updateTotalStaffCount() {
                document.getElementById('totalStaffCount').textContent = staffData.length;
            }
            
            // Staff modal functions
            window.openAddStaffModal = function() {
                document.getElementById('staffModalTitle').textContent = 'Tambah Staff Baru';
                document.getElementById('staffId').value = '';
                document.getElementById('staffFullName').value = '';
                document.getElementById('staffPosition').value = '';
                document.getElementById('staffDepartment').value = '';
                document.getElementById('staffStatus').value = 'Aktif';
                document.getElementById('deleteStaffBtn').classList.add('hidden');
                
                document.getElementById('staffModal').classList.remove('hidden');
                document.getElementById('staffModal').classList.add('flex');
            }
            
            window.openEditStaffModal = function(staffId) {
                const staff = staffData.find(s => s.id === staffId);
                if (!staff) return;
                
                document.getElementById('staffModalTitle').textContent = 'Edit Staff';
                document.getElementById('staffId').value = staff.id;
                document.getElementById('staffFullName').value = staff.name;
                document.getElementById('staffPosition').value = staff.position;
                document.getElementById('staffDepartment').value = staff.department;
                document.getElementById('staffStatus').value = staff.status;
                document.getElementById('deleteStaffBtn').classList.remove('hidden');
                
                document.getElementById('staffModal').classList.remove('hidden');
                document.getElementById('staffModal').classList.add('flex');
            }
            
            window.closeStaffModal = function() {
                document.getElementById('staffModal').classList.add('hidden');
                document.getElementById('staffModal').classList.remove('flex');
            }
            
            // Staff form submission
            document.getElementById('staffForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const staffId = document.getElementById('staffId').value;
                const name = document.getElementById('staffFullName').value;
                const position = document.getElementById('staffPosition').value;
                const department = document.getElementById('staffDepartment').value;
                const status = document.getElementById('staffStatus').value;
                
                if (staffId) {
                    // Update existing staff
                    const index = staffData.findIndex(s => s.id == staffId);
                    if (index !== -1) {
                        staffData[index] = {
                            id: parseInt(staffId),
                            name,
                            position,
                            department,
                            status
                        };
                    }
                } else {
                    // Add new staff
                    const newId = staffData.length > 0 ? Math.max(...staffData.map(s => s.id)) + 1 : 1;
                    staffData.push({
                        id: newId,
                        name,
                        position,
                        department,
                        status
                    });
                }
                
                renderStaffTable();
                updateTotalStaffCount();
                closeStaffModal();
            });
            
            window.confirmDeleteStaff = function(staffId) {
                if (confirm('Apakah Anda yakin ingin menghapus staff ini?')) {
                    deleteStaff(staffId);
                }
            };
            
            window.deleteStaff = function() {
                const staffId = document.getElementById('staffId').value;
                if (!staffId) return;
                
                staffData = staffData.filter(s => s.id != staffId);
                renderStaffTable();
                updateTotalStaffCount();
                closeStaffModal();
            };

            // Schedule modal functions
            function openAddModal(dateStr) {
                document.getElementById('modalTitle').textContent = 'Tambah Jadwal Dinas';
                document.getElementById('eventId').value = '';
                document.getElementById('staffName').value = '';
                document.getElementById('shiftType').value = '';
                document.getElementById('startDate').value = dateStr;
                document.getElementById('endDate').value = dateStr;
                document.getElementById('deleteBtn').classList.add('hidden');
                
                document.getElementById('scheduleModal').classList.remove('hidden');
                document.getElementById('scheduleModal').classList.add('flex');
            }

            function openEditModal(event) {
                document.getElementById('modalTitle').textContent = 'Edit Jadwal Dinas';
                document.getElementById('eventId').value = event.id;
                document.getElementById('staffName').value = event.extendedProps.staff;
                document.getElementById('shiftType').value = event.extendedProps.shift;
                
                // Format dates without time
                const startDate = event.start.toISOString().split('T')[0];
                const endDate = event.end ? event.end.toISOString().split('T')[0] : startDate;
                
                document.getElementById('startDate').value = startDate;
                document.getElementById('endDate').value = endDate;
                document.getElementById('deleteBtn').classList.remove('hidden');
                
                document.getElementById('scheduleModal').classList.remove('hidden');
                document.getElementById('scheduleModal').classList.add('flex');
            }

            window.closeScheduleModal = function() {
                document.getElementById('scheduleModal').classList.add('hidden');
                document.getElementById('scheduleModal').classList.remove('flex');
            }

            // Form submission
            document.getElementById('scheduleForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const eventId = document.getElementById('eventId').value;
                const staffName = document.getElementById('staffName').value;
                const shiftType = document.getElementById('shiftType').value;
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;
                
                // Determine event time based on shift type
                let startTime, endTime;
                if (shiftType === 'P') {
                    startTime = 'T07:00:00';
                    endTime = 'T14:00:00';
                } else if (shiftType === 'S') {
                    startTime = 'T14:00:00';
                    endTime = 'T21:00:00';
                } else {
                    startTime = 'T21:00:00';
                    // Next day 07:00 for night shift
                    const nextDay = new Date(new Date(endDate).getTime() + 86400000);
                    const nextDayStr = new Date(nextDay).toISOString().split('T')[0];
                    endTime = 'T07:00:00';
                    endDate = nextDayStr;
                }
                
                const eventData = {
                    id: eventId || Date.now().toString(),
                    title: `${staffName} (${shiftType})`,
                    start: startDate + startTime,
                    end: endDate + endTime,
                    className: shiftType === 'P' ? 'fc-event-pagi' : 
                              shiftType === 'S' ? 'fc-event-sore' : 'fc-event-malam',
                    extendedProps: {
                        staff: staffName,
                        shift: shiftType
                    }
                };
                
                if (eventId) {
                    // Update existing event
                    const existingEvent = calendar.getEventById(eventId);
                    if (existingEvent) {
                        existingEvent.setProp('title', eventData.title);
                        existingEvent.setStart(eventData.start);
                        existingEvent.setEnd(eventData.end);
                        existingEvent.setProp('className', eventData.className);
                        existingEvent.setExtendedProp('staff', staffName);
                        existingEvent.setExtendedProp('shift', shiftType);
                    }
                } else {
                    // Add new event
                    calendar.addEvent(eventData);
                }
                
                closeScheduleModal();
                
                // In a real app, you would save to database here
                console.log('Event saved:', eventData);
            });

            window.deleteEvent = function() {
                const eventId = document.getElementById('eventId').value;
                const event = calendar.getEventById(eventId);
                
                if (event) {
                    if (confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
                        event.remove();
                        closeScheduleModal();
                        
                        // In a real app, you would delete from database here
                        console.log('Event deleted:', eventId);
                    }
                }
            };

            // Close modal when clicking outside
            document.getElementById('scheduleModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeScheduleModal();
                }
            });
            
            document.getElementById('staffModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeStaffModal();
                }
            });

            // Add fade-in animation for elements
            const elements = document.querySelectorAll('.animate-fadeIn');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>