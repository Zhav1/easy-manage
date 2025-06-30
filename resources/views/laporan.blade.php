<!DOCTYPE html>
<html lang="en" class="h-full bg-white w-screen">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Laporan Kepala Ruangan</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .card-hover {
      transition: all 0.3s ease;
    }
    .card-hover:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    .icon-bounce {
      animation: bounce 2s infinite;
    }
    @keyframes bounce {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-5px); }
    }
    .glass-effect {
      backdrop-filter: blur(16px) saturate(180%);
      background-color: rgba(255, 255, 255, 0.75);
      border: 1px solid rgba(209, 213, 219, 0.3);
    }
    .tab-content {
      display: none;
    }
    .tab-content.active {
      display: block;
    }
    .progress-bar {
      transition: width 0.3s ease;
    }
    .notification-badge {
      animation: pulse 2s infinite;
    }
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.1); }
      100% { transform: scale(1); }
    }
  </style>
</head>
<body class="min-h-full bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 text-gray-800">
  @include('components.sidebar-navbar')
  <div class="p-4 text-black">
    <main class="pl-60 pr-5 flex-1 px-6 py-8 mt-8">
      <!-- Header Profil -->
      <div class="glass-effect rounded-2xl p-8 mb-8 shadow-lg">
        <div class="flex items-center gap-6">
          <div class="relative">
            <img src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" 
                 alt="Foto Profil" 
                 class="w-24 h-24 rounded-full border-4 border-white shadow-xl ring-4 ring-green-100" />
            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 rounded-full border-4 border-white flex items-center justify-center">
              <i class="fas fa-check text-white text-xs"></i>
            </div>
          </div>
          <div class="flex-1">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent mb-2">
              Laporan Kepala Ruangan
            </h1>
            <p class="text-gray-600">Kamis, 26 Juni 2025</p>
            <p class="text-sm text-gray-500 mt-1">Rekap aktivitas, kinerja staf, dan informasi penting ruangan</p>
          </div>
          <div class="flex gap-4">
            <div class="text-center p-4 bg-green-100 rounded-lg">
              <div class="text-2xl font-bold text-green-600">24</div>
              <div class="text-sm text-green-700">Staf Aktif</div>
            </div>
            <div class="text-center p-4 bg-blue-100 rounded-lg">
              <div class="text-2xl font-bold text-blue-600">95%</div>
              <div class="text-sm text-blue-700">Kepatuhan</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tab Navigation -->
      <div class="bg-white rounded-2xl shadow mb-8">
        <div class="flex border-b overflow-x-auto">
          <button onclick="showTab('catatan')" class="tab-btn px-6 py-4 font-medium text-blue-600 border-b-2 border-blue-600 whitespace-nowrap">
            <i class="fas fa-calendar-check mr-2"></i>Catatan Harian
          </button>
          <button onclick="showTab('jadwal')" class="tab-btn px-6 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
            <i class="fas fa-clock mr-2"></i>Jadwal Dinas
          </button>
          <button onclick="showTab('logistik')" class="tab-btn px-6 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
            <i class="fas fa-boxes mr-2"></i>Manajemen Logistik
          </button>
          <button onclick="showTab('ppi')" class="tab-btn px-6 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
            <i class="fas fa-shield-alt mr-2"></i>PPI
          </button>
          <button onclick="showTab('kinerja')" class="tab-btn px-6 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
            <i class="fas fa-user-cog mr-2"></i>Kinerja Staff
          </button>
          <button onclick="showTab('tna')" class="tab-btn px-6 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
            <i class="fas fa-graduation-cap mr-2"></i>TNA
          </button>
          <button onclick="showTab('mutu')" class="tab-btn px-6 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
            <i class="fas fa-award mr-2"></i>Indikator Mutu
          </button>
        </div>

        <!-- Tab Content: Catatan Harian -->
        <div id="catatan" class="tab-content active p-6">
          <h2 class="text-xl font-semibold text-gray-800 mb-4">Catatan Harian & Notifikasi</h2>
          <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
              <thead class="bg-gray-100 text-gray-700">
                <tr>
                  <th class="px-4 py-3 text-left">Tanggal & Jam</th>
                  <th class="px-4 py-3 text-center">Briefing</th>
                  <th class="px-4 py-3 text-center">Rapat</th>
                  <th class="px-4 py-3 text-center">Supervisi</th>
                  <th class="px-4 py-3 text-center">Handover</th>
                  <th class="px-4 py-3 text-center">Tugas Luar</th>
                  <th class="px-4 py-3 text-center">Laporan</th>
                  <th class="px-4 py-3 text-left">Catatan</th>
                </tr>
              </thead>
              <tbody>
                <tr class="border-t hover:bg-gray-50">
                  <td class="px-4 py-3">26-06-2025 07:30</td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded">Ya</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-red-100 text-red-800 px-2 py-1 rounded">Tidak</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded">Ya</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded">Ya</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-gray-100 text-gray-800 px-2 py-1 rounded">Tidak</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">Terkirim</span></td>
                  <td class="px-4 py-3">Semua kegiatan berjalan lancar</td>
                </tr>
                <tr class="border-t hover:bg-gray-50">
                  <td class="px-4 py-3">25-06-2025 14:00</td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded">Ya</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded">Ya</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded">Ya</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded">Ya</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded">Ya</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">Terkirim</span></td>
                  <td class="px-4 py-3">Rapat evaluasi bulanan</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab Content: Jadwal Dinas -->
        <div id="jadwal" class="tab-content p-6">
          <h2 class="text-xl font-semibold text-gray-800 mb-4">Jadwal Dinas Staff</h2>
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
              <h3 class="text-lg font-semibold mb-2">Shift Pagi (07:00-14:00)</h3>
              <p class="text-blue-100">8 Staff bertugas</p>
            </div>
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-6 text-white">
              <h3 class="text-lg font-semibold mb-2">Shift Sore (14:00-21:00)</h3>
              <p class="text-orange-100">6 Staff bertugas</p>
            </div>
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
              <h3 class="text-lg font-semibold mb-2">Shift Malam (21:00-07:00)</h3>
              <p class="text-purple-100">4 Staff bertugas</p>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
              <h3 class="text-lg font-semibold mb-2">Staff Cuti/Libur</h3>
              <p class="text-green-100">6 Staff tidak bertugas</p>
            </div>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
              <thead class="bg-gray-100">
                <tr>
                  <th class="px-4 py-3 text-left">Nama Staff</th>
                  <th class="px-4 py-3 text-center">Senin</th>
                  <th class="px-4 py-3 text-center">Selasa</th>
                  <th class="px-4 py-3 text-center">Rabu</th>
                  <th class="px-4 py-3 text-center">Kamis</th>
                  <th class="px-4 py-3 text-center">Jumat</th>
                  <th class="px-4 py-3 text-center">Sabtu</th>
                  <th class="px-4 py-3 text-center">Minggu</th>
                </tr>
              </thead>
              <tbody>
                <tr class="border-t hover:bg-gray-50">
                  <td class="px-4 py-3 font-medium">Yanti</td>
                  <td class="px-4 py-3 text-center"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">Pagi</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">Pagi</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs">Sore</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs">Sore</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Libur</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Libur</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Malam</span></td>
                </tr>
                <tr class="border-t hover:bg-gray-50">
                  <td class="px-4 py-3 font-medium">Sari</td>
                  <td class="px-4 py-3 text-center"><span class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs">Sore</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Malam</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Libur</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">Pagi</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">Pagi</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs">Sore</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Cuti</span></td>
                </tr>
                <tr class="border-t hover:bg-gray-50">
                  <td class="px-4 py-3 font-medium">Ahmad</td>
                  <td class="px-4 py-3 text-center"><span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Malam</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Libur</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">Pagi</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">Pagi</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs">Sore</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Malam</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Libur</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab Content: Kinerja Staff -->
        <div id="kinerja" class="tab-content p-6">
          <h2 class="text-xl font-semibold text-gray-800 mb-4">Kinerja Staff</h2>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead class="bg-gray-100 text-gray-700">
                <tr>
                  <th class="px-4 py-3 text-left">Nama</th>
                  <th class="px-4 py-3 text-center">Kedisiplinan</th>
                  <th class="px-4 py-3 text-center">Komunikasi</th>
                  <th class="px-4 py-3 text-center">Komplain</th>
                  <th class="px-4 py-3 text-center">Kepatuhan</th>
                  <th class="px-4 py-3 text-center">Target</th>
                  <th class="px-4 py-3 text-center">Score</th>
                  <th class="px-4 py-3 text-left">Catatan</th>
                </tr>
              </thead>
              <tbody>
                <tr class="border-t hover:bg-gray-50">
                  <td class="px-4 py-3 font-medium">Yanti</td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded">Tepat Waktu</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded">Baik</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded">Tidak Ada</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded">Patuh</span></td>
                  <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center">
                      <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                        <div class="bg-green-500 h-2 rounded-full progress-bar" style="width: 100%"></div>
                      </div>
                      <span class="text-green-600 font-medium">100%</span>
                    </div>
                  </td>
                  <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center gap-1">
                      <i class="fas fa-star text-yellow-500"></i>
                      <i class="fas fa-star text-yellow-500"></i>
                      <i class="fas fa-star text-yellow-500"></i>
                      <i class="fas fa-star text-yellow-500"></i>
                      <i class="fas fa-star text-yellow-500"></i>
                    </div>
                  </td>
                  <td class="px-4 py-3 text-green-600 font-medium">Good Performance</td>
                </tr>
                <tr class="border-t hover:bg-gray-50">
                  <td class="px-4 py-3 font-medium">Sari</td>
                  <td class="px-4 py-3 text-center"><span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Terlambat 1x</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded">Baik</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded">Tidak Ada</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded">Patuh</span></td>
                  <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center">
                      <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                        <div class="bg-yellow-500 h-2 rounded-full progress-bar" style="width: 85%"></div>
                      </div>
                      <span class="text-yellow-600 font-medium">85%</span>
                    </div>
                  </td>
                  <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center gap-1">
                      <i class="fas fa-star text-yellow-500"></i>
                      <i class="fas fa-star text-yellow-500"></i>
                      <i class="fas fa-star text-yellow-500"></i>
                      <i class="fas fa-star text-yellow-500"></i>
                      <i class="far fa-star text-gray-300"></i>
                    </div>
                  </td>
                  <td class="px-4 py-3 text-yellow-600">Perlu perbaikan kedisiplinan</td>
                </tr>
                <tr class="border-t hover:bg-gray-50">
                  <td class="px-4 py-3 font-medium">Ahmad</td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded">Tepat Waktu</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded">Sangat Baik</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded">Tidak Ada</span></td>
                  <td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-800 px-2 py-1 rounded">Patuh</span></td>
                  <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center">
                      <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                        <div class="bg-green-500 h-2 rounded-full progress-bar" style="width: 98%"></div>
                      </div>
                      <span class="text-green-600 font-medium">98%</span>
                    </div>
                  </td>
                  <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center gap-1">
                      <i class="fas fa-star text-yellow-500"></i>
                      <i class="fas fa-star text-yellow-500"></i>
                      <i class="fas fa-star text-yellow-500"></i>
                      <i class="fas fa-star text-yellow-500"></i>
                      <i class="fas fa-star text-yellow-500"></i>
                    </div>
                  </td>
                  <td class="px-4 py-3 text-green-600 font-medium">Excellent Performance</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab Content: Logistik -->
        <div id="logistik" class="tab-content p-6">
          <h2 class="text-xl font-semibold text-gray-800 mb-4">Manajemen Logistik</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-lg font-semibold">Stok Tersedia</h3>
                  <p class="text-2xl font-bold">156</p>
                </div>
                <i class="fas fa-boxes text-3xl opacity-70"></i>
              </div>
            </div>
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-6 text-white">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-lg font-semibold">Stok Rendah</h3>
                  <p class="text-2xl font-bold">12</p>
                </div>
                <i class="fas fa-exclamation-triangle text-3xl opacity