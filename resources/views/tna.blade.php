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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
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
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .gradient-bg {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .pulse-animation {
      animation: pulse 2s infinite;
    }
    @keyframes pulse {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.8; }
    }
    .colorful-border {
      background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4, #feca57);
      background-size: 300% 300%;
      animation: gradientShift 3s ease infinite;
    }
    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
    .stat-card {
      background: linear-gradient(135deg, var(--tw-gradient-from), var(--tw-gradient-to));
      border-radius: 1.5rem;
      position: relative;
      overflow: hidden;
    }
    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.05));
      pointer-events: none;
    }
  </style>
</head>
<body class="min-h-full bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 text-gray-800">
  @include('components.sidebar-navbar')
  <div class="p-4">
    <main class="pl-60 pr-5 flex-1 px-6 py-8 mt-8">
      <!-- Header dengan efek glass dan gradient -->
      <div class="glass-effect rounded-3xl p-8 mb-8 shadow-xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 colorful-border"></div>
        <div class="relative z-10">
          <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent mb-3">
            <i class="fas fa-graduation-cap mr-3"></i>Training Need Assessment (TNA)
          </h1>
          <p class="text-gray-600 text-lg">Catat seminar, pelatihan, dan pendidikan lanjutan staf sebagai dasar perencanaan pengembangan SDM.</p>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="stat-card from-emerald-400 to-emerald-600 text-white p-6 card-hover">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-emerald-100 text-sm font-medium">Total Staf</p>
              <p class="text-3xl font-bold">5</p>
            </div>
            <div class="bg-white bg-opacity-20 p-3 rounded-full">
              <i class="fas fa-users text-2xl"></i>
            </div>
          </div>
        </div>

        <div class="stat-card from-blue-400 to-blue-600 text-white p-6 card-hover">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-blue-100 text-sm font-medium">Seminar/Workshop</p>
              <p class="text-3xl font-bold">5</p>
            </div>
            <div class="bg-white bg-opacity-20 p-3 rounded-full">
              <i class="fas fa-chalkboard-teacher text-2xl"></i>
            </div>
          </div>
        </div>

        <div class="stat-card from-amber-400 to-orange-500 text-white p-6 card-hover">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-amber-100 text-sm font-medium">Pelatihan</p>
              <p class="text-3xl font-bold">5</p>
            </div>
            <div class="bg-white bg-opacity-20 p-3 rounded-full">
              <i class="fas fa-medal text-2xl"></i>
            </div>
          </div>
        </div>

        <div class="stat-card from-rose-400 to-pink-600 text-white p-6 card-hover">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-rose-100 text-sm font-medium">Pendidikan Lanjutan</p>
              <p class="text-3xl font-bold">4</p>
            </div>
            <div class="bg-white bg-opacity-20 p-3 rounded-full">
              <i class="fas fa-user-graduate text-2xl"></i>
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

      <!-- Tabel TNA dengan tema berwarna -->
      <div class="glass-effect rounded-3xl shadow-xl overflow-hidden card-hover">
        <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 p-6">
          <h2 class="text-2xl font-bold text-white flex items-center">
            <i class="fas fa-chalkboard-teacher mr-3"></i>Rekap Pendidikan & Pelatihan Staf
          </h2>
        </div>
        
        <div class="p-6">
          <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
              <thead>
                <tr class="bg-gradient-to-r from-gray-50 to-gray-100">
                  <th class="px-6 py-4 text-left font-semibold text-gray-700 rounded-tl-xl">
                    <i class="fas fa-user mr-2 text-indigo-500"></i>Nama
                  </th>
                  <th class="px-6 py-4 text-left font-semibold text-gray-700">
                    <i class="fas fa-microphone mr-2 text-blue-500"></i>Seminar / Workshop / Webinar
                  </th>
                  <th class="px-6 py-4 text-left font-semibold text-gray-700">
                    <i class="fas fa-dumbbell mr-2 text-emerald-500"></i>Pelatihan
                  </th>
                  <th class="px-6 py-4 text-left font-semibold text-gray-700">
                    <i class="fas fa-graduation-cap mr-2 text-purple-500"></i>Pendidikan Lanjutan
                  </th>
                  <th class="px-6 py-4 text-left font-semibold text-gray-700 rounded-tr-xl">
                    <i class="fas fa-cogs mr-2 text-orange-500"></i>Aksi
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-300">
                  <td class="px-6 py-4">
                    <div class="flex items-center">
                      <div class="w-10 h-10 bg-gradient-to-r from-pink-400 to-rose-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                        Y
                      </div>
                      <span class="font-medium text-gray-800">Yanti</span>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">
                      Webinar ICU Nasional 2024
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-xs font-medium">
                      Pelatihan Manajemen IGD
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-medium">
                      S2 Keperawatan
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <button class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white px-4 py-2 rounded-lg text-xs font-medium transition-all duration-300 flex items-center">
                      <i class="fas fa-pen mr-1"></i>Edit
                    </button>
                  </td>
                </tr>
                <tr class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 transition-all duration-300">
                  <td class="px-6 py-4">
                    <div class="flex items-center">
                      <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                        B
                      </div>
                      <span class="font-medium text-gray-800">Budi</span>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <span class="bg-cyan-100 text-cyan-800 px-3 py-1 rounded-full text-xs font-medium">
                      Workshop Komunikasi Efektif
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">
                      Pelatihan Basic Life Support
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-xs font-medium">
                      D3 Keperawatan
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <button class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-4 py-2 rounded-lg text-xs font-medium transition-all duration-300 flex items-center">
                      <i class="fas fa-pen mr-1"></i>Edit
                    </button>
                  </td>
                </tr>
                <tr class="hover:bg-gradient-to-r hover:from-pink-50 hover:to-rose-50 transition-all duration-300">
                  <td class="px-6 py-4">
                    <div class="flex items-center">
                      <div class="w-10 h-10 bg-gradient-to-r from-emerald-400 to-teal-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                        S
                      </div>
                      <span class="font-medium text-gray-800">Siti</span>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <span class="bg-pink-100 text-pink-800 px-3 py-1 rounded-full text-xs font-medium">
                      Seminar Gizi Anak 2025
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-medium">
                      Pelatihan Keperawatan Anak
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <span class="bg-teal-100 text-teal-800 px-3 py-1 rounded-full text-xs font-medium">
                      S1 Keperawatan
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <button class="bg-gradient-to-r from-pink-500 to-rose-600 hover:from-pink-600 hover:to-rose-700 text-white px-4 py-2 rounded-lg text-xs font-medium transition-all duration-300 flex items-center">
                      <i class="fas fa-pen mr-1"></i>Edit
                    </button>
                  </td>
                </tr>
                <tr class="hover:bg-gradient-to-r hover:from-orange-50 hover:to-amber-50 transition-all duration-300">
                  <td class="px-6 py-4">
                    <div class="flex items-center">
                      <div class="w-10 h-10 bg-gradient-to-r from-orange-400 to-red-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                        R
                      </div>
                      <span class="font-medium text-gray-800">Rina</span>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-xs font-medium">
                      Webinar Keselamatan Pasien
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-medium">
                      Pelatihan Pencegahan Infeksi
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-medium">
                      Belum Ada
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <button class="bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white px-4 py-2 rounded-lg text-xs font-medium transition-all duration-300 flex items-center">
                      <i class="fas fa-pen mr-1"></i>Edit
                    </button>
                  </td>
                </tr>
                <tr class="hover:bg-gradient-to-r hover:from-violet-50 hover:to-purple-50 transition-all duration-300">
                  <td class="px-6 py-4">
                    <div class="flex items-center">
                      <div class="w-10 h-10 bg-gradient-to-r from-violet-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                        A
                      </div>
                      <span class="font-medium text-gray-800">Anton</span>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <span class="bg-violet-100 text-violet-800 px-3 py-1 rounded-full text-xs font-medium">
                      Seminar Manajemen Kritis
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">
                      Pelatihan Leadership Tim
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-medium">
                      S2 Manajemen RS
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <button class="bg-gradient-to-r from-violet-500 to-purple-600 hover:from-violet-600 hover:to-purple-700 text-white px-4 py-2 rounded-lg text-xs font-medium transition-all duration-300 flex items-center">
                      <i class="fas fa-pen mr-1"></i>Edit
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>