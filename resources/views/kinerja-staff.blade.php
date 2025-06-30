<!DOCTYPE html>
<html lang="en" class="h-full bg-white w-screen">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Kinerja Staf</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
  <style>
    .card-hover {
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-hover:hover {
      transform: translateY(-8px) scale(1.02);
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }
    .glass-effect {
      backdrop-filter: blur(20px) saturate(180%);
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.8) 100%);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }
    .status-indicator {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      display: inline-block;
      margin-right: 8px;
    }
    .performance-badge {
      display: inline-flex;
      align-items: center;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      background-color: #e0f2ff;
      color: #1e40af;
    }
    .animated-button {
      position: relative;
      overflow: hidden;
      transition: all 0.3s ease;
    }
    .animated-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
    }
    .animated-button::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: all 0.6s;
    }
    .animated-button:hover::before {
      left: 100%;
    }
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .animate-fade-in-up {
      animation: fadeInUp 0.6s ease-out;
    }
    .table-row {
      transition: all 0.3s ease;
    }
    .table-row:hover {
      background-color: #ffffff;
      transform: scale(1.01);
    }
  </style>
</head>
<body class="bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 text-gray-800">
  @include('components.sidebar-navbar')
  <div class="p-4">
    <main class="pl-60 pr-5 flex-1 px-6 py-8 mt-8">
      <div class="glass-effect rounded-3xl p-8 mb-8 shadow-xl animate-fade-in-up">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-4xl font-bold text-black mb-3">
              <i class="fas fa-chart-line mr-3 text-green-500"></i>Kinerja Staf
            </h1>
            <p class="text-gray-600 text-lg">Lihat dan catat penilaian kinerja staf Anda berdasarkan indikator yang tersedia.</p>
          </div>
          <div class="flex space-x-4">
            <button class="animated-button bg-white border border-blue-500 text-blue-500 px-6 py-3 rounded-2xl font-semibold">
              <i class="fas fa-plus mr-2 text-blue-500"></i>Tambah Penilaian
            </button>
            <button class="animated-button bg-white border border-blue-500 text-blue-500 px-6 py-3 rounded-2xl font-semibold">
              <i class="fas fa-download mr-2 text-blue-500"></i>Export
            </button>
          </div>
        </div>
      </div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
  <div class="bg-white rounded-2xl p-6 text-gray-700 shadow-lg hover:shadow-xl transition">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm font-medium text-gray-500">Excellent Performance</p>
        <p class="text-3xl font-bold text-gray-700">2</p>
      </div>
      <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
        <i class="fas fa-star text-2xl text-yellow-500"></i>
      </div>
    </div>
  </div>

  <div class="bg-white rounded-2xl p-6 text-gray-700 shadow-lg hover:shadow-xl transition">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm font-medium text-gray-500">Good Performance</p>
        <p class="text-3xl font-bold text-gray-700">1</p>
      </div>
      <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
        <i class="fas fa-thumbs-up text-2xl text-blue-500"></i>
      </div>
    </div>
  </div>

  <div class="bg-white rounded-2xl p-6 text-gray-700 shadow-lg hover:shadow-xl transition">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm font-medium text-gray-500">Need Mentoring</p>
        <p class="text-3xl font-bold text-gray-700">1</p>
      </div>
      <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
        <i class="fas fa-exclamation-triangle text-2xl text-yellow-500"></i>
      </div>
    </div>
  </div>

  <div class="bg-white rounded-2xl p-6 text-gray-700 shadow-lg hover:shadow-xl transition">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm font-medium text-gray-500">Need Improvement</p>
        <p class="text-3xl font-bold text-gray-700">1</p>
      </div>
      <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
        <i class="fas fa-arrow-up text-2xl text-red-500"></i>
      </div>
    </div>
  </div>
</div>


      <!-- Tabel Kinerja Staf -->
      <div class="card-hover bg-white rounded-3xl shadow-xl p-8 animate-fade-in-up">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-users mr-3 text-blue-500"></i>Rekapitulasi Penilaian Staf
          </h2>
          <div class="flex items-center space-x-4">
            <div class="relative">
              <input type="text" placeholder="Cari staff..." class="pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <i class="fas fa-search absolute left-3 top-3 text-blue-500"></i>
            </div>
            <select class="px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option>Semua Status</option>
              <option>Teladan</option>
              <option>Good</option>
              <option>Perlu Pendampingan</option>
              <option>Perlu Perbaikan</option>
            </select>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full text-sm bg-white text-black rounded-xl">
            <thead>
              <tr class="bg-gray-100 text-black">
                <th class="px-6 py-4 text-left font-semibold">Nama</th>
                <th class="px-6 py-4 text-left font-semibold">Kedisiplinan</th>
                <th class="px-6 py-4 text-left font-semibold">Komunikasi</th>
                <th class="px-6 py-4 text-left font-semibold">Komplain</th>
                <th class="px-6 py-4 text-left font-semibold">Kepatuhan</th>
                <th class="px-6 py-4 text-left font-semibold">Target Kerja</th>
                <th class="px-6 py-4 text-left font-semibold">Status</th>
                <th class="px-6 py-4 text-left font-semibold">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <!-- Contoh baris -->
              <tr class="table-row">
                <td class="px-6 py-4">
                  <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">Y</div>
                    <div>
                      <p class="font-semibold text-black">Yanti</p>
                      <p class="text-xs text-gray-500">Staff ID: 001</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator" style="background:#10b981"></span>
                    <span class="text-green-700 font-medium">Tepat Waktu</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span class="text-blue-600 font-medium">Baik</span>
                </td>
                <td class="px-6 py-4">
                  <span class="text-green-600 font-medium">Tidak Ada</span>
                </td>
                <td class="px-6 py-4">
                  <span class="text-green-700 font-medium">Sangat Patuh</span>
                </td>
                <td class="px-6 py-4">
                  <span class="text-green-700 font-medium">Tercapai</span>
                </td>
                <td class="px-6 py-4">
                  <span class="performance-badge">Good Performance</span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex space-x-2">
                    <button class="animated-button bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-lg text-xs font-semibold">
                      <i class="fas fa-pen mr-1 text-blue-500"></i>Edit
                    </button>
                    <button class="animated-button bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-lg text-xs font-semibold">
                      <i class="fas fa-eye mr-1 text-blue-500"></i>Detail
                    </button>
                  </div>
                </td>
              </tr>
              <!-- Tambah baris lainnya di sini sesuai data -->
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
