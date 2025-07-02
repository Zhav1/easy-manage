<!DOCTYPE html>
<html lang="en" class="h-full bg-white w-screen">
<head>
<<<<<<< HEAD
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>TNA - Pendidikan & Pelatihan</title>
  <link rel="stylesheet" href="{{asset('css/tna.css')}}">
  <script src="{{asset('js/tna.js')}}"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
  <style>
    .card-hover {
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .card-hover:hover {
      transform: translateY(-8px) scale(1.02);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    .glass-effect {
      backdrop-filter: blur(20px) saturate(180%);
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
      border: 1px solid rgba(243, 99, 99, 0.2);
    }
    ::-webkit-scrollbar {
  width: 0px;          /* hilang sama sekali */
  background: transparent;
}

/* Firefox */
html, body {
  scrollbar-width: none;      /* hilang di Firefox */
  -ms-overflow-style: none;   /* hilang di Edge lama/IE */
}
  </style>
</head>
<body class="min-h-full bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 text-gray-800">
  <script>
    window.authToken = "{{ session('token') }}";
    </script>
  @include('components.sidebar-navbar')
  <div class="p-4">
    <main class="pl-60 pr-5 flex-1 px-6 py-8 mt-8">
      <div class="glass-effect rounded-3xl p-8 mb-8 shadow-xl">
        <h1 class="text-4xl font-bold text-black mb-3">
          <i class="fas fa-graduation-cap mr-3 text-green-500"></i>Training Need Assessment (TNA)
        </h1>
        <p class="text-gray-600 text-lg">Catat seminar, pelatihan, dan pendidikan lanjutan staf sebagai dasar perencanaan pengembangan SDM.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8" id="stats-cards">
        <!-- Cards will be dynamically loaded -->
      </div>

      <!-- Action Buttons -->
      <div class="flex gap-4 mb-8">
        <button onclick="openStaffModal()" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg card-hover flex items-center">
          <i class="fas fa-user-plus mr-2"></i>Tambah Staf
        </button>
        <button onclick="openTnaModal()" class="bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg card-hover flex items-center">
          <i class="fas fa-plus mr-2"></i>Tambah Data TNA
        </button>
        <button class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg card-hover flex items-center">
          <i class="fas fa-download mr-2"></i>Export Excel
        </button>
        <button class="bg-gradient-to-r from-orange-400 to-red-500 hover:from-orange-500 hover:to-red-600 text-white px-6 py-3 rounded-xl font-semibold shadow-lg card-hover flex items-center">
          <i class="fas fa-file-pdf mr-2"></i>Export PDF
        </button>
      </div>

      <!-- Staff Management Table -->
      <div class="glass-effect rounded-3xl shadow-xl overflow-hidden card-hover bg-white mb-8">
        <div class="bg-white p-6">
          <h2 class="text-2xl font-bold text-black mb-3">
            <i class="fas fa-users mr-3 text-blue-500"></i>Manajemen Staf
          </h2>
        </div>
        <div class="p-6 overflow-x-auto">
          <table class="min-w-full text-sm bg-white rounded-2xl shadow-md">
            <thead>
              <tr class="bg-[#f9fcfe] text-black">
                <th class="px-6 py-4 text-left font-semibold rounded-tl-xl">No</th>
                <th class="px-6 py-4 text-left font-semibold">Nama</th>
                <th class="px-6 py-4 text-left font-semibold">Jabatan</th>
                <th class="px-6 py-4 text-left font-semibold">Ruangan</th>
                <th class="px-6 py-4 text-left font-semibold">Status</th>
                <th class="px-6 py-4 text-left font-semibold rounded-tr-xl">Tindakan</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-gray-800" id="staff-table-body">
              <!-- Staff data will be loaded here -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Tabel TNA -->
      <div class="glass-effect rounded-3xl shadow-xl overflow-hidden card-hover bg-white">
        <div class="bg-white p-6">
          <h2 class="text-2xl font-bold text-black mb-3">
            <i class="fas fa-chalkboard-teacher mr-3 text-green-500"></i>Rekap Pendidikan & Pelatihan Staf
          </h2>
        </div>
        <div class="p-6 overflow-x-auto">
          <table class="min-w-full text-sm bg-white rounded-2xl shadow-md">
            <thead>
              <tr class="bg-[#f9fcfe] text-black">
                <th class="px-6 py-4 text-left font-semibold rounded-tl-xl">
                  <i class="fas fa-user mr-2 text-[#0CC0DF]"></i>Nama
                </th>
                <th class="px-6 py-4 text-left font-semibold">
                  <i class="fas fa-microphone mr-2 text-[#0CC0DF]"></i>Seminar / Workshop / Webinar
                </th>
                <th class="px-6 py-4 text-left font-semibold">
                  <i class="fas fa-dumbbell mr-2 text-[#0CC0DF]"></i>Pelatihan
                </th>
                <th class="px-6 py-4 text-left font-semibold">
                  <i class="fas fa-graduation-cap mr-2 text-[#0CC0DF]"></i>Pendidikan Lanjutan
                </th>
                <th class="px-6 py-4 text-left font-semibold rounded-tr-xl">
                  <i class="fas fa-cogs mr-2 text-[#0CC0DF]"></i>Aksi
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-gray-800" id="tna-table-body">
              <!-- TNA data will be loaded here -->
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>

  <!-- Staff Modal -->
  <div id="staff-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <div class="relative bg-white rounded-lg shadow">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t bg-gradient-to-r from-blue-500 to-indigo-600">
                <h3 class="text-xl font-semibold text-white" id="staff-modal-title">
                    Tambah Staf Baru
                </h3>
                <button type="button" onclick="closeStaffModal()" class="text-white bg-transparent hover:bg-blue-600 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-4 md:p-5 space-y-4">
                <form id="staff-form">
                    <input type="hidden" id="staff-id">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                        <div class="col-span-1">
                            <label for="position_id" class="block mb-2 text-sm font-medium text-gray-900">Jabatan</label>
                            <select id="position_id" name="position_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                <!-- Options akan diisi via JavaScript -->
                            </select>
                        </div>
                        <div class="col-span-1">
                            <label for="department_id" class="block mb-2 text-sm font-medium text-gray-900">Departemen</label>
                            <select id="department_id" name="department_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                <!-- Options akan diisi via JavaScript -->
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                            <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                                <option value="Cuti">Cuti</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="closeStaffModal()" class="text-gray-700 bg-white hover:bg-gray-100 border border-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2">Batal</button>
                        <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

  <!-- TNA Modal -->
  <div id="tna-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t bg-gradient-to-r from-purple-500 to-indigo-600">
          <h3 class="text-xl font-semibold text-white" id="tna-modal-title">
            Tambah Data TNA
          </h3>
          <button type="button" onclick="closeTnaModal()" class="text-white bg-transparent hover:bg-purple-600 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="p-4 md:p-5 space-y-4">
          <form id="tna-form">
            <input type="hidden" id="tna-id">
            <div class="grid gap-4 mb-4 grid-cols-2">
              <div class="col-span-2">
                <label for="staff_id" class="block mb-2 text-sm font-medium text-gray-900">Staf</label>
                <select id="staff_id" name="staff_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                  <option value="">Pilih Staf</option>
                </select>
              </div>
              <div class="col-span-2">
                <label for="type" class="block mb-2 text-sm font-medium text-gray-900">Jenis Kegiatan</label>
                <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                  <option value="">Pilih Jenis</option>
                  <option value="seminar">Seminar/Workshop/Webinar</option>
                  <option value="training">Pelatihan</option>
                  <option value="education">Pendidikan Lanjutan</option>
                </select>
              </div>
              <div class="col-span-2">
                <label for="title" class="block mb-2 text-sm font-medium text-gray-900">Judul Kegiatan</label>
                <input type="text" name="title" id="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
              </div>
              <div class="col-span-2">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Deskripsi</label>
                <textarea id="description" name="description" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"></textarea>
              </div>
              <div class="col-span-1">
                <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
              </div>
              <div class="col-span-1">
                <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900">Tanggal Selesai</label>
                <input type="date" name="end_date" id="end_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
              </div>
              <div class="col-span-2">
                <label for="organizer" class="block mb-2 text-sm font-medium text-gray-900">Penyelenggara</label>
                <input type="text" name="organizer" id="organizer" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
              </div>
            </div>
            <div class="flex justify-end">
              <button type="button" onclick="closeTnaModal()" class="text-gray-700 bg-white hover:bg-gray-100 border border-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2">Batal</button>
              <button type="submit" class="text-white bg-purple-600 hover:bg-purple-700 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- View TNA Modal -->
  <div id="view-tna-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t bg-gradient-to-r from-green-500 to-teal-600">
          <h3 class="text-xl font-semibold text-white">
            Detail Kegiatan
          </h3>
          <button type="button" onclick="closeViewTnaModal()" class="text-white bg-transparent hover:bg-green-600 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="p-4 md:p-5 space-y-4">
          <div class="grid gap-4 mb-4 grid-cols-2">
            <div class="col-span-2">
              <h4 class="text-lg font-semibold text-gray-900" id="view-tna-title"></h4>
              <p class="text-gray-600" id="view-tna-staff"></p>
            </div>
            <div class="col-span-2">
              <p class="text-gray-700" id="view-tna-description"></p>
            </div>
            <div class="col-span-1">
              <p class="text-sm text-gray-500">Tanggal Mulai</p>
              <p class="text-gray-700" id="view-tna-start-date"></p>
            </div>
            <div class="col-span-1">
              <p class="text-sm text-gray-500">Tanggal Selesai</p>
              <p class="text-gray-700" id="view-tna-end-date"></p>
            </div>
            <div class="col-span-2">
              <p class="text-sm text-gray-500">Penyelenggara</p>
              <p class="text-gray-700" id="view-tna-organizer"></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
=======
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
>>>>>>> e45d446e3f936884ee07c33057ad864c3cd2c908
</body>
</html>