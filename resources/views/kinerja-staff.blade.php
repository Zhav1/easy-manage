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
    .gradient-bg {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    }
    .badge-excellent {
      background: linear-gradient(135deg, #10b981, #059669);
      color: white;
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    .badge-good {
      background: linear-gradient(135deg, #3b82f6, #1d4ed8);
      color: white;
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    .badge-warning {
      background: linear-gradient(135deg, #f59e0b, #d97706);
      color: white;
      box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }
    .badge-danger {
      background: linear-gradient(135deg, #ef4444, #dc2626);
      color: white;
      box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
    .status-indicator {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      display: inline-block;
      margin-right: 8px;
    }
    .status-excellent { background: linear-gradient(135deg, #10b981, #059669); }
    .status-good { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
    .status-warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .status-danger { background: linear-gradient(135deg, #ef4444, #dc2626); }
    
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
    
    .table-row:hover {
      background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(139, 92, 246, 0.05));
      transform: scale(1.01);
      transition: all 0.3s ease;
    }
  </style>
</head>
<body class="min-h-full bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 text-gray-800">
  @include('components.sidebar-navbar')
  <div class="p-4">
    <main class="pl-60 pr-5 flex-1 px-6 py-8 mt-8">
      <!-- Header Section -->
      <div class="glass-effect rounded-3xl p-8 mb-8 shadow-xl animate-fade-in-up">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent mb-3">
              <i class="fas fa-chart-line mr-3"></i>Kinerja Staf
            </h1>
            <p class="text-gray-600 text-lg">Lihat dan catat penilaian kinerja staf Anda berdasarkan indikator yang tersedia.</p>
          </div>
          <div class="flex space-x-4">
            <button class="animated-button bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-3 rounded-2xl font-semibold">
              <i class="fas fa-plus mr-2"></i>Tambah Penilaian
            </button>
            <button class="animated-button bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-3 rounded-2xl font-semibold">
              <i class="fas fa-download mr-2"></i>Export
            </button>
          </div>
        </div>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="card-hover bg-gradient-to-br from-green-400 to-green-600 rounded-2xl p-6 text-white shadow-lg">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-green-100 text-sm font-medium">Excellent Performance</p>
              <p class="text-3xl font-bold">2</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
              <i class="fas fa-star text-2xl"></i>
            </div>
          </div>
        </div>
        
        <div class="card-hover bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-blue-100 text-sm font-medium">Good Performance</p>
              <p class="text-3xl font-bold">1</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
              <i class="fas fa-thumbs-up text-2xl"></i>
            </div>
          </div>
        </div>
        
        <div class="card-hover bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl p-6 text-white shadow-lg">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-yellow-100 text-sm font-medium">Need Mentoring</p>
              <p class="text-3xl font-bold">1</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
              <i class="fas fa-exclamation-triangle text-2xl"></i>
            </div>
          </div>
        </div>
        
        <div class="card-hover bg-gradient-to-br from-red-400 to-red-600 rounded-2xl p-6 text-white shadow-lg">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-red-100 text-sm font-medium">Need Improvement</p>
              <p class="text-3xl font-bold">1</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
              <i class="fas fa-arrow-up text-2xl"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabel Kinerja Staf -->
      <div class="card-hover bg-white rounded-3xl shadow-xl p-8 animate-fade-in-up" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-users mr-3 text-purple-500"></i>Rekapitulasi Penilaian Staf
          </h2>
          <div class="flex items-center space-x-4">
            <div class="relative">
              <input type="text" placeholder="Cari staff..." class="pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent">
              <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            <select class="px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent">
              <option>Semua Status</option>
              <option>Excellent</option>
              <option>Good</option>
              <option>Need Mentoring</option>
              <option>Need Improvement</option>
            </select>
          </div>
        </div>
        
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700">
                <th class="px-6 py-4 text-left font-semibold rounded-tl-xl">
                  <div class="flex items-center space-x-2">
                    <i class="fas fa-user text-indigo-500"></i>
                    <span>Nama</span>
                  </div>
                </th>
                <th class="px-6 py-4 text-left font-semibold">
                  <div class="flex items-center space-x-2">
                    <i class="fas fa-clock text-green-500"></i>
                    <span>Kedisiplinan</span>
                  </div>
                </th>
                <th class="px-6 py-4 text-left font-semibold">
                  <div class="flex items-center space-x-2">
                    <i class="fas fa-comments text-blue-500"></i>
                    <span>Komunikasi</span>
                  </div>
                </th>
                <th class="px-6 py-4 text-left font-semibold">
                  <div class="flex items-center space-x-2">
                    <i class="fas fa-exclamation-circle text-orange-500"></i>
                    <span>Komplain</span>
                  </div>
                </th>
                <th class="px-6 py-4 text-left font-semibold">
                  <div class="flex items-center space-x-2">
                    <i class="fas fa-shield-alt text-purple-500"></i>
                    <span>Kepatuhan</span>
                  </div>
                </th>
                <th class="px-6 py-4 text-left font-semibold">
                  <div class="flex items-center space-x-2">
                    <i class="fas fa-target text-red-500"></i>
                    <span>Target Kerja</span>
                  </div>
                </th>
                <th class="px-6 py-4 text-left font-semibold">
                  <div class="flex items-center space-x-2">
                    <i class="fas fa-award text-yellow-500"></i>
                    <span>Status</span>
                  </div>
                </th>
                <th class="px-6 py-4 text-left font-semibold rounded-tr-xl">
                  <div class="flex items-center space-x-2">
                    <i class="fas fa-cogs text-gray-500"></i>
                    <span>Aksi</span>
                  </div>
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr class="table-row">
                <td class="px-6 py-4">
                  <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-pink-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">Y</div>
                    <div>
                      <p class="font-semibold text-gray-800">Yanti</p>
                      <p class="text-xs text-gray-500">Staff ID: 001</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-excellent"></span>
                    <span class="text-green-700 font-medium">Tepat Waktu</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-good"></span>
                    <span class="text-blue-700 font-medium">Baik</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-excellent"></span>
                    <span class="text-green-700 font-medium">Tidak Ada</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-excellent"></span>
                    <span class="text-green-700 font-medium">Sangat Patuh</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-excellent"></span>
                    <span class="text-green-700 font-medium">Tercapai</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span class="performance-badge badge-good">Good Performance</span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex space-x-2">
                    <button class="animated-button bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-4 py-2 rounded-lg text-xs font-semibold">
                      <i class="fas fa-pen mr-1"></i>Edit
                    </button>
                    <button class="animated-button bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 rounded-lg text-xs font-semibold">
                      <i class="fas fa-eye mr-1"></i>Detail
                    </button>
                  </div>
                </td>
              </tr>
              
              <tr class="table-row">
                <td class="px-6 py-4">
                  <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold">B</div>
                    <div>
                      <p class="font-semibold text-gray-800">Budi</p>
                      <p class="text-xs text-gray-500">Staff ID: 002</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-danger"></span>
                    <span class="text-red-700 font-medium">Sering Telat</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-warning"></span>
                    <span class="text-orange-700 font-medium">Cukup</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-warning"></span>
                    <span class="text-orange-700 font-medium">1 Komplain</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-warning"></span>
                    <span class="text-orange-700 font-medium">Perlu Pendampingan</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-danger"></span>
                    <span class="text-red-700 font-medium">Belum Tercapai</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span class="performance-badge badge-danger">Perlu Perbaikan</span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex space-x-2">
                    <button class="animated-button bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-4 py-2 rounded-lg text-xs font-semibold">
                      <i class="fas fa-pen mr-1"></i>Edit
                    </button>
                    <button class="animated-button bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 rounded-lg text-xs font-semibold">
                      <i class="fas fa-eye mr-1"></i>Detail
                    </button>
                  </div>
                </td>
              </tr>
              
              <tr class="table-row">
                <td class="px-6 py-4">
                  <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-green-500 rounded-full flex items-center justify-center text-white font-bold">S</div>
                    <div>
                      <p class="font-semibold text-gray-800">Siti</p>
                      <p class="text-xs text-gray-500">Staff ID: 003</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-excellent"></span>
                    <span class="text-green-700 font-medium">Tepat Waktu</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-excellent"></span>
                    <span class="text-green-700 font-medium">Sangat Baik</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-excellent"></span>
                    <span class="text-green-700 font-medium">Tidak Ada</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-good"></span>
                    <span class="text-blue-700 font-medium">Patuh</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-excellent"></span>
                    <span class="text-green-700 font-medium">Tercapai</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span class="performance-badge badge-excellent">Rajin & Inisiatif</span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex space-x-2">
                    <button class="animated-button bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-4 py-2 rounded-lg text-xs font-semibold">
                      <i class="fas fa-pen mr-1"></i>Edit
                    </button>
                    <button class="animated-button bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 rounded-lg text-xs font-semibold">
                      <i class="fas fa-eye mr-1"></i>Detail
                    </button>
                  </div>
                </td>
              </tr>
              
              <tr class="table-row">
                <td class="px-6 py-4">
                  <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white font-bold">R</div>
                    <div>
                      <p class="font-semibold text-gray-800">Rina</p>
                      <p class="text-xs text-gray-500">Staff ID: 004</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-warning"></span>
                    <span class="text-orange-700 font-medium">Kadang Terlambat</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-warning"></span>
                    <span class="text-orange-700 font-medium">Cukup</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-danger"></span>
                    <span class="text-red-700 font-medium">2 Komplain</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-warning"></span>
                    <span class="text-orange-700 font-medium">Sedang</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-warning"></span>
                    <span class="text-orange-700 font-medium">Progres</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span class="performance-badge badge-warning">Perlu Pendampingan</span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex space-x-2">
                    <button class="animated-button bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-4 py-2 rounded-lg text-xs font-semibold">
                      <i class="fas fa-pen mr-1"></i>Edit
                    </button>
                    <button class="animated-button bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 rounded-lg text-xs font-semibold">
                      <i class="fas fa-eye mr-1"></i>Detail
                    </button>
                  </div>
                </td>
              </tr>
              
              <tr class="table-row">
                <td class="px-6 py-4">
                  <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-full flex items-center justify-center text-white font-bold">A</div>
                    <div>
                      <p class="font-semibold text-gray-800">Anton</p>
                      <p class="text-xs text-gray-500">Staff ID: 005</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-excellent"></span>
                    <span class="text-green-700 font-medium">Tepat Waktu</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-good"></span>
                    <span class="text-blue-700 font-medium">Baik</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-excellent"></span>
                    <span class="text-green-700 font-medium">Tidak Ada</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-excellent"></span>
                    <span class="text-green-700 font-medium">Sangat Patuh</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <span class="status-indicator status-excellent"></span>
                    <span class="text-green-700 font-medium">Tercapai</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span class="performance-badge badge-excellent">Teladan</span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex space-x-2">
                    <button class="animated-button bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-4 py-2 rounded-lg text-xs font-semibold">
                      <i class="fas fa-pen mr-1"></i>Edit