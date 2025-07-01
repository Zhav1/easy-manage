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
  </style>
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
        <!-- Cards -->
        <!-- Total Staff Card -->
        <div class="bg-white text-gray-700 p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Staff</p>
              <p class="text-3xl font-bold mt-2">5</p>
              <p class="text-xs text-gray-400 mt-1">Personil aktif</p>
            </div>
            <div class="bg-blue-50 p-3 rounded-full text-blue-500">
              <i class="fas fa-users text-xl"></i>
            </div>
          </div>
        </div>
        <!-- Seminar/Workshop Card -->
        <div class="bg-white text-gray-700 p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Seminar/Workshop</p>
              <p class="text-3xl font-bold mt-2">5</p>
              <p class="text-xs text-gray-400 mt-1">Kegiatan tahun ini</p>
            </div>
            <div class="bg-green-50 p-3 rounded-full text-green-500">
              <i class="fas fa-chalkboard-teacher text-xl"></i>
            </div>
          </div>
        </div>
        <!-- Pelatihan Card -->
        <div class="bg-white text-gray-700 p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Pelatihan</p>
              <p class="text-3xl font-bold mt-2">5</p>
              <p class="text-xs text-gray-400 mt-1">Program terselesaikan</p>
            </div>
            <div class="bg-amber-50 p-3 rounded-full text-amber-500">
              <i class="fas fa-medal text-xl"></i>
            </div>
          </div>
        </div>
        <!-- Pendidikan Lanjutan Card -->
        <div class="bg-white text-gray-700 p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Pendidikan Lanjutan</p>
              <p class="text-3xl font-bold mt-2">4</p>
              <p class="text-xs text-gray-400 mt-1">Staf berkembang</p>
            </div>
            <div class="bg-purple-50 p-3 rounded-full text-purple-500">
              <i class="fas fa-user-graduate text-xl"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex gap-4 mb-8">
        <button class="bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg card-hover flex items-center">
          <i class="fas fa-plus mr-2"></i>Tambah Data TNA
        </button>
        <button class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg card-hover flex items-center">
          <i class="fas fa-download mr-2"></i>Export Excel
        </button>
        <button class="bg-gradient-to-r from-orange-400 to-red-500 hover:from-orange-500 hover:to-red-600 text-white px-6 py-3 rounded-xl font-semibold shadow-lg card-hover flex items-center">
          <i class="fas fa-file-pdf mr-2"></i>Export PDF
        </button>
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
            <tbody class="bg-white divide-y divide-gray-200 text-gray-800">
              <tr class="hover:bg-white transition-all duration-300">
                <td class="px-6 py-4 flex items-center">
                  <div class="w-10 h-10 bg-[#0CC0DF] rounded-full flex items-center justify-center text-white font-bold mr-3">Y</div>
                  Yanti
                </td>
                <td class="px-6 py-4">
                  <span class="text-1xl font-bold text-black mb-3">Webinar ICU Nasional 2024</span>
                </td>
                <td class="px-6 py-4">
                  <span class="text-1xl font-bold text-black mb-3">Pelatihan Manajemen IGD</span>
                </td>
                <td class="px-6 py-4">
                  <span class="text-1xl font-bold text-black mb-3">S2 Keperawatan</span>
                </td>
                <td class="px-6 py-4">
                  <button class="bg-white hover:bg-gray-100 text-black px-4 py-2 rounded-lg text-xs font-medium transition-all duration-300 flex items-center border border-[#0CC0DF]">
                    <i class="fas fa-pen mr-1 text-[#0CC0DF]"></i>Edit
                  </button>
                </td>
              </tr>
              <tr class="hover:bg-white transition-all duration-300">
                <td class="px-6 py-4 flex items-center">
                  <div class="w-10 h-10 bg-[#0CC0DF] rounded-full flex items-center justify-center text-white font-bold mr-3">R</div>
                  Rina
                </td>
                <td class="px-6 py-4">
                  <span class="text-1xl font-bold text-black mb-3">Webinar Keselamatan Pasien</span>
                </td>
                <td class="px-6 py-4">
                  <span class="text-1xl font-bold text-black mb-3">Pelatihan Pencegahan Infeksi</span>
                </td>
                <td class="px-6 py-4">
                  <span class="text-1xl font-bold text-black mb-3">Belum Ada</span>
                </td>
                <td class="px-6 py-4">
                  <button class="bg-white hover:bg-gray-100 text-black px-4 py-2 rounded-lg text-xs font-medium transition-all duration-300 flex items-center border border-[#0CC0DF]">
                    <i class="fas fa-pen mr-1 text-[#0CC0DF]"></i>Edit
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
