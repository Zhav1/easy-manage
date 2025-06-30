<!DOCTYPE html>
<html lang="en" class="h-full bg-white w-screen">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EasyManage Dashboard</title>
    @vite('resources/css/app.css')
    @vite('resources/css/app.js')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .icon-bounce {
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% {
                transform: translate3d(0,0,0);
            }
            40%, 43% {
                transform: translate3d(0,-5px,0);
            }
            70% {
                transform: translate3d(0,-3px,0);
            }
            90% {
                transform: translate3d(0,-1px,0);
            }
        }
        .glass-effect {
            backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(209, 213, 219, 0.3);
        }
        .stat-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
    </style>
    <script>
        if (localStorage.getItem('color-theme') === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
        }
    </script>
</head>
<body class="min-h-full bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 text-gray-800">
    @include('components.sidebar-navbar')
    
    <div class="p-4 sm:ml-64 text-black">
        <!-- Main Content -->
        <main class="flex-1 px-6 py-8 mt-8">
            <!-- Welcome Section -->
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
                            Selamat Pagi, Abidzar!
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <i class="fas fa-calendar-alt text-blue-500"></i>
                            Senin, 23 Juni 2025
                        </p>
                        <p class="text-sm text-gray-500 mt-1">Semoga hari Anda produktif dan menyenangkan</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="stat-card rounded-xl p-4 shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Jadwal Hari Ini</p>
                            <p class="text-2xl font-bold text-green-600">5</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-day text-green-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card rounded-xl p-4 shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Stok Menipis</p>
                            <p class="text-2xl font-bold text-orange-600">12</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-orange-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card rounded-xl p-4 shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Monitoring PPI</p>
                            <p class="text-2xl font-bold text-blue-600">98%</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shield-virus text-blue-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card rounded-xl p-4 shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Task Selesai</p>
                            <p class="text-2xl font-bold text-purple-600">28</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tasks text-purple-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shortcut Cards - Enhanced -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Jadwal Dinas -->
                <div class="card-hover bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-calendar-check text-white text-2xl icon-bounce"></i>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                    Aktif
                                </span>
                            </div>
                        </div>
                        
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">Jadwal Dinas</h2>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Kelola shift dan kunjungan dinas dengan sistem yang terintegrasi dan mudah digunakan.
                        </p>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-clock text-green-500 w-4 mr-3"></i>
                                Shift berikutnya: 14:00 - 22:00
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-users text-green-500 w-4 mr-3"></i>
                                Tim: 8 perawat aktif
                            </div>
                        </div>
                        
                        <a href="/dinas" class="inline-flex items-center justify-center w-full px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Lihat Jadwal
                        </a>
                    </div>
                </div>

                <!-- Manajemen Logistik -->
                <div class="card-hover bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-boxes text-white text-2xl icon-bounce"></i>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <span class="w-2 h-2 bg-yellow-400 rounded-full mr-2"></span>
                                    Perlu Perhatian
                                </span>
                            </div>
                        </div>
                        
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">Manajemen Logistik</h2>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Pantau inventaris, stok obat, alat kesehatan, dan kebutuhan operasional lainnya secara real-time.
                        </p>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-chart-line text-orange-500 w-4 mr-3"></i>
                                Stok tersedia: 1,247 item
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-exclamation-circle text-orange-500 w-4 mr-3"></i>
                                Menipis: 12 item
                            </div>
                        </div>
                        
                        <a href="/manajemen-logistik" class="inline-flex items-center justify-center w-full px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-semibold rounded-xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="fas fa-warehouse mr-2"></i>
                            Lihat Inventaris
                        </a>
                    </div>
                </div>

                <!-- PPI -->
                <div class="card-hover bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-shield-virus text-white text-2xl icon-bounce"></i>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full mr-2"></span>
                                    Optimal
                                </span>
                            </div>
                        </div>
                        
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">PPI</h2>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Sistem monitoring pencegahan dan pengendalian infeksi untuk menjaga standar keamanan fasilitas.
                        </p>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-percentage text-blue-500 w-4 mr-3"></i>
                                Kepatuhan: 98.5%
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-clipboard-check text-blue-500 w-4 mr-3"></i>
                                Audit terakhir: 2 hari lalu
                            </div>
                        </div>
                        
                        <a href="/pengendalian-dan-pencegahan-infeksi" class="inline-flex items-center justify-center w-full px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Lihat Monitoring
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-10 glass-effect rounded-2xl p-6 shadow-lg">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Aksi Cepat</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <button class="flex flex-col items-center p-4 rounded-xl hover:bg-white hover:shadow-md transition-all duration-200">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-2">
                            <i class="fas fa-plus text-purple-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Tambah Jadwal</span>
                    </button>
                    
                    <button class="flex flex-col items-center p-4 rounded-xl hover:bg-white hover:shadow-md transition-all duration-200">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-2">
                            <i class="fas fa-bell text-red-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Notifikasi</span>
                    </button>
                    
                    <button class="flex flex-col items-center p-4 rounded-xl hover:bg-white hover:shadow-md transition-all duration-200">
                        <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mb-2">
                            <i class="fas fa-file-alt text-teal-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Lihat Laporan</span>
                    </button>
                    
                    <button class="flex flex-col items-center p-4 rounded-xl hover:bg-white hover:shadow-md transition-all duration-200">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-2">
                            <i class="fas fa-cog text-indigo-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Pengaturan</span>
                    </button>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>