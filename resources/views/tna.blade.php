<!DOCTYPE html>
<html lang="en" class="h-full bg-white w-screen">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>TNA - Pendidikan & Pelatihan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/tna.css') }}">
    <script src="{{ asset('js/tna.js') }}"></script>
</head>
<body class="min-h-full bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 text-gray-800">
    @include('components.sidebar-navbar')
    <div class="p-4">
        <main class="pl-60 pr-5 flex-1 px-6 py-8 mt-8">
            <div class="glass-effect rounded-3xl p-8 mb-8 shadow-xl">
                <h1 class="text-4xl font-bold text-black mb-3">
                    <i class="fas fa-graduation-cap mr-3 text-green-500"></i>Training Need Assessment (TNA)
                </h1>
                <p class="text-gray-600 text-lg">Catat seminar, pelatihan, dan pendidikan lanjutan staf sebagai dasar perencanaan pengembangan SDM.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white text-gray-700 p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Staff</p>
                            <p class="text-3xl font-bold mt-2" id="totalStaffCount">0</p>
                            <p class="text-xs text-gray-400 mt-1">Personil aktif</p>
                        </div>
                        <div class="bg-blue-50 p-3 rounded-full text-blue-500">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white text-gray-700 p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Seminar/Workshop</p>
                            <p class="text-3xl font-bold mt-2" id="totalSeminarCount">0</p>
                            <p class="text-xs text-gray-400 mt-1">Kegiatan tahun ini</p>
                        </div>
                        <div class="bg-green-50 p-3 rounded-full text-green-500">
                            <i class="fas fa-chalkboard-teacher text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white text-gray-700 p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Pelatihan</p>
                            <p class="text-3xl font-bold mt-2" id="totalPelatihanCount">0</p>
                            <p class="text-xs text-gray-400 mt-1">Program terselesaikan</p>
                        </div>
                        <div class="bg-amber-50 p-3 rounded-full text-amber-500">
                            <i class="fas fa-medal text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white text-gray-700 p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Pendidikan Lanjutan</p>
                            <p class="text-3xl font-bold mt-2" id="totalPendidikanLanjutanCount">0</p>
                            <p class="text-xs text-gray-400 mt-1">Staf berkembang</p>
                        </div>
                        <div class="bg-purple-50 p-3 rounded-full text-purple-500">
                            <i class="fas fa-user-graduate text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 mb-8">
                <button onclick="openAddTnaModal()" class="btn-green-tna">
                    <i class="fas fa-plus mr-2"></i>Tambah Data TNA
                </button>
                <button onclick="openAddStaffModal()" class="btn-blue-tna">
                    <i class="fas fa-user-plus mr-2"></i>Tambah Staff
                </button>
                <button onclick="exportToExcel()" class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg card-hover flex items-center">
                    <i class="fas fa-download mr-2"></i>Export Excel
                </button>
                <button onclick="exportToPdf()" class="bg-gradient-to-r from-orange-400 to-red-500 hover:from-orange-500 hover:to-red-600 text-white px-6 py-3 rounded-xl font-semibold shadow-lg card-hover flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i>Export PDF
                </button>
            </div>

            <div class="glass-effect rounded-3xl shadow-xl overflow-hidden card-hover bg-white mb-8">
                <div class="bg-white p-6">
                    <h2 class="text-2xl font-bold text-black mb-3">
                        <i class="fas fa-users-cog mr-3 text-blue-500"></i>Manajemen Staff
                    </h2>
                </div>
                <div class="p-6 overflow-x-auto">
                    <table class="management-table min-w-full text-sm bg-white rounded-2xl shadow-md">
                        <thead>
                            <tr class="bg-[#f9fcfe] text-black">
                                <th class="px-4 py-4 text-left font-semibold rounded-tl-xl">No</th>
                                <th class="px-4 py-4 text-left font-semibold">
                                    <i class="fas fa-user mr-2 text-[#0CC0DF]"></i>Nama
                                </th>
                                <th class="px-4 py-4 text-left font-semibold">
                                    <i class="fas fa-briefcase mr-2 text-[#0CC0DF]"></i>Jabatan
                                </th>
                                <th class="px-4 py-4 text-left font-semibold">
                                    <i class="fas fa-hospital-alt mr-2 text-[#0CC0DF]"></i>Ruangan
                                </th>
                                <th class="px-4 py-4 text-left font-semibold">
                                    <i class="fas fa-info-circle mr-2 text-[#0CC0DF]"></i>Status
                                </th>
                                <th class="px-4 py-4 text-left font-semibold rounded-tr-xl">
                                    <i class="fas fa-cogs mr-2 text-[#0CC0DF]"></i>Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody id="staffTableBody" class="bg-white divide-y divide-gray-200 text-gray-800">
                            </tbody>
                    </table>
                </div>
            </div>

            <div class="glass-effect rounded-3xl shadow-xl overflow-hidden card-hover bg-white">
                <div class="bg-white p-6">
                    <h2 class="text-2xl font-bold text-black mb-3">
                        <i class="fas fa-chalkboard-teacher mr-3 text-green-500"></i>Rekap Pendidikan & Pelatihan Staf
                    </h2>
                </div>
                <div class="p-6 overflow-x-auto">
                    <table class="tna-table min-w-full text-sm bg-white rounded-2xl shadow-md">
                        <thead>
                            <tr class="bg-[#f9fcfe] text-black">
                                <th class="px-4 py-4 text-left font-semibold rounded-tl-xl">
                                    <i class="fas fa-user mr-2 text-[#0CC0DF]"></i>Nama
                                </th>
                                <th class="px-4 py-4 text-left font-semibold">
                                    <i class="fas fa-microphone mr-2 text-[#0CC0DF]"></i>Seminar / Workshop / Webinar
                                </th>
                                <th class="px-4 py-4 text-left font-semibold">
                                    <i class="fas fa-dumbbell mr-2 text-[#0CC0DF]"></i>Pelatihan
                                </th>
                                <th class="px-4 py-4 text-left font-semibold">
                                    <i class="fas fa-graduation-cap mr-2 text-[#0CC0DF]"></i>Pendidikan Lanjutan
                                </th>
                                <th class="px-4 py-4 text-left font-semibold rounded-tr-xl">
                                    <i class="fas fa-cogs mr-2 text-[#0CC0DF]"></i>Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody id="tnaRecordsTableBody" class="bg-white divide-y divide-gray-200 text-gray-800">
                            </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

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
                <input type="hidden" id="userId" value="{{ auth()->user()->id }}">
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
                    <button type="button" onclick="closeStaffModal()" class="px-3 py-1 rounded-lg text-sm text-gray-700 hover:bg-gray-100 border border-gray-300">Batal</button>
                    <button type="submit" class="px-3 py-1 rounded-lg text-sm text-white bg-blue-600 hover:bg-blue-700">Simpan</button>
                    <button type="button" id="deleteStaffBtn" onclick="deleteStaff()" class="px-3 py-1 rounded-lg text-sm text-white bg-red-600 hover:bg-red-700 hidden">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <div id="tnaModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-5 w-full max-w-md">
            <div class="flex justify-between items-center mb-3">
                <h3 id="tnaModalTitle" class="text-lg font-bold">Tambah Data TNA</h3>
                <button onclick="closeTnaModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="tnaForm">
                <input type="hidden" id="tnaId">
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1" for="tnaStaffName">Nama Staff</label>
                    <select id="tnaStaffName" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                        <option value="">Pilih Staff</option>
                        </select>
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1" for="seminarWorkshopWebinar">Seminar / Workshop / Webinar</label>
                    <input type="text" id="seminarWorkshopWebinar" class="w-full px-3 py-2 border rounded-lg text-sm" placeholder="Contoh: Webinar ICU Nasional 2024">
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1" for="pelatihan">Pelatihan</label>
                    <input type="text" id="pelatihan" class="w-full px-3 py-2 border rounded-lg text-sm" placeholder="Contoh: Pelatihan Manajemen IGD">
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1" for="pendidikanLanjutan">Pendidikan Lanjutan</label>
                    <input type="text" id="pendidikanLanjutan" class="w-full px-3 py-2 border rounded-lg text-sm" placeholder="Contoh: S2 Keperawatan">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeTnaModal()" class="px-3 py-1 rounded-lg text-sm text-gray-700 hover:bg-gray-100 border border-gray-300">Batal</button>
                    <button type="submit" class="px-3 py-1 rounded-lg text-sm text-white bg-green-600 hover:bg-green-700">Simpan</button>
                    <button type="button" id="deleteTnaBtn" onclick="deleteTnaRecord()" class="px-3 py-1 rounded-lg text-sm text-white bg-red-600 hover:bg-red-700 hidden">Hapus</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        window.authToken = "{{ session('token') }}";
    </script>
    <script src="{{ asset('js/tna.js') }}"></script>
</body>
</html>