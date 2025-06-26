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
  </style>
</head>
<body class="min-h-full bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 text-gray-800">
    @include('components.sidebar-navbar')
  <div class="p-4 text-black">
    <main class="  pl-60 pr-5  flex-1 px-6 py-8 mt-8 ">
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
          <div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent mb-2">
              Laporan Kepala Ruangan
            </h1>
            <p class="text-gray-600">Kamis, 26 Juni 2025</p>
            <p class="text-sm text-gray-500 mt-1">Rekap aktivitas, kinerja staf, dan informasi penting ruangan</p>
          </div>
        </div>
      </div>

      <!-- Seksi: Catatan Harian & Notifikasi -->
      <div class="card-hover bg-white rounded-2xl p-6 mb-8 shadow">
        <h2 class="text-xl font-semibold text-gray-800 mb-4"><i class="fas fa-calendar-check mr-2 text-green-500"></i>Catatan Harian & Notifikasi</h2>
        <table class="min-w-full text-sm">
          <thead class="bg-gray-100 text-gray-700">
            <tr>
              <th class="px-4 py-2">Tanggal & Jam</th>
              <th class="px-4 py-2">Briefing</th>
              <th class="px-4 py-2">Rapat</th>
              <th class="px-4 py-2">Supervisi</th>
              <th class="px-4 py-2">Handover</th>
              <th class="px-4 py-2">Tugas Luar</th>
              <th class="px-4 py-2">Laporan</th>
              <th class="px-4 py-2">Catatan</th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-t">
              <td class="px-4 py-2">26-06-2025 07:30</td>
              <td class="px-4 py-2">Ya</td>
              <td class="px-4 py-2">Tidak</td>
              <td class="px-4 py-2">Ya</td>
              <td class="px-4 py-2">Ya</td>
              <td class="px-4 py-2">Tidak</td>
              <td class="px-4 py-2">Terkirim</td>
              <td class="px-4 py-2">Lancar</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Seksi: Kinerja Staff -->
      <div class="card-hover bg-white rounded-2xl p-6 mb-8 shadow">
        <h2 class="text-xl font-semibold text-gray-800 mb-4"><i class="fas fa-user-cog mr-2 text-purple-500"></i>Kinerja Staf</h2>
        <table class="w-full text-sm">
          <thead class="bg-gray-100 text-gray-700">
            <tr>
              <th class="px-4 py-2">Nama</th>
              <th class="px-4 py-2">Kedisiplinan</th>
              <th class="px-4 py-2">Komunikasi</th>
              <th class="px-4 py-2">Komplain</th>
              <th class="px-4 py-2">Kepatuhan</th>
              <th class="px-4 py-2">Target</th>
              <th class="px-4 py-2">Catatan</th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-t">
              <td class="px-4 py-2">Yanti</td>
              <td class="px-4 py-2">Tepat Waktu</td>
              <td class="px-4 py-2">Baik</td>
              <td class="px-4 py-2">Tidak Ada</td>
              <td class="px-4 py-2">Patuh</td>
              <td class="px-4 py-2">100%</td>
              <td class="px-4 py-2 text-green-600">Good Performance</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Seksi: TNA -->
      <div class="card-hover bg-white rounded-2xl p-6 mb-8 shadow">
        <h2 class="text-xl font-semibold text-gray-800 mb-4"><i class="fas fa-graduation-cap mr-2 text-indigo-500"></i>TNA - Pendidikan & Pelatihan</h2>
        <table class="w-full text-sm">
          <thead class="bg-gray-100 text-gray-700">
            <tr>
              <th class="px-4 py-2">Nama</th>
              <th class="px-4 py-2">Seminar/Webinar</th>
              <th class="px-4 py-2">Pelatihan</th>
              <th class="px-4 py-2">Pendidikan Lanjutan</th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-t">
              <td class="px-4 py-2">Yanti</td>
              <td class="px-4 py-2">Webinar ICU 2024</td>
              <td class="px-4 py-2">Pelatihan Keselamatan</td>
              <td class="px-4 py-2">S2 Keperawatan</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Seksi: Indikator & Logistik -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card-hover bg-white rounded-2xl p-6 shadow">
          <h2 class="text-lg font-semibold mb-2 text-gray-800"><i class="fas fa-list-check mr-2 text-blue-500"></i>Indikator Mutu</h2>
          <p class="text-sm text-gray-600">13 indikator kepatuhan seperti identifikasi pasien, cuci tangan, penggunaan APD, dll.</p>
        </div>

        <div class="card-hover bg-white rounded-2xl p-6 shadow">
          <h2 class="text-lg font-semibold mb-2 text-gray-800"><i class="fas fa-boxes-packing mr-2 text-yellow-500"></i>Informasi Logistik & PPI</h2>
          <p class="text-sm text-gray-600">Data dan bahan yang dikirim dari unit logistik, PPI, dan mutu akan ditampilkan di sini.</p>
        </div>
      </div>
    </main>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>
