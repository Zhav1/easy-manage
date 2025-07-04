<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EasyManage Dashboard</title>
    
    <!-- Preload critical resources -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"></noscript>
    
    @vite('resources/css/app.css')
    @vite('resources/css/app.js')
    
    <style>
        /* Chrome, Safari, Opera */
::-webkit-scrollbar {
  width: 0px;          /* hilang sama sekali */
  background: transparent;
}

/* Firefox */
html, body {
  scrollbar-width: none;      /* hilang di Firefox */
  -ms-overflow-style: none;   /* hilang di Edge lama/IE */
}

        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --bg-gradient: linear-gradient(to bottom right, #F8F5FF, #F3F4F6, #FDF2F8);
        }
        
        body {
            background: var(--bg-gradient);
            color: #1F2937;
        }
        
        .gradient-bg {
            background: var(--primary-gradient);
        }
        
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
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
        
        /* Scrollbar styling */
        .scrollable-content {
            scrollbar-width: thin;
            scrollbar-color: #E5E7EB transparent;
        }
        
        .scrollable-content::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        .scrollable-content::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .scrollable-content::-webkit-scrollbar-thumb {
            background-color: #E5E7EB;
            border-radius: 20px;
        }
        
        /* Focus styles for accessibility */
        a:focus, button:focus {
            outline: 2px solid #4F46E5;
            outline-offset: 2px;
        }
        
        /* Reduced motion media query */
        @media (prefers-reduced-motion: reduce) {
            .card-hover, .icon-bounce {
                transition: none;
                animation: none;
            }
        }
    </style>
    
    <script>
        // Theme management
        document.addEventListener('DOMContentLoaded', function() {
            const theme = localStorage.getItem('color-theme') || 'light';
            document.documentElement.classList.toggle('dark', theme === 'dark');
            
            // Set aria-labels for theme toggler if exists
            const themeToggler = document.getElementById('theme-toggle');
            if (themeToggler) {
                themeToggler.setAttribute('aria-label', theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode');
            }
        });
    </script>
</head>
<body class="h-full overflow-hidden  bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100">
    @include('components.sidebar-navbar')
    
    <div class="sm:ml-64  mt-3 h-full overflow-hidden">
        <main class="h-[calc(100vh-4rem)] overflow-y-auto scrollable-content px-4 sm:px-6 py-8 mt-14" aria-label="Main content">
            <!-- Welcome Section -->
            <section aria-labelledby="welcome-heading" class="glass-effect rounded-2xl p-6 sm:p-8 mb-8 shadow-lg">
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <div class="relative">
                        <img class="w-24 h-24 rounded-full border-4 border-white shadow-xl ring-4 ring-green-100"
                         src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('images/p.png') }}"
                         alt="Foto Profil"> 
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 rounded-full border-4 border-white flex items-center justify-center">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                    </div>
                    
                    <div class="text-center sm:text-left">
                        <h1 id="welcome-heading" class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent mb-2">
                            Selamat <span id="greeting-time">
                            @php
                                $hour = date('H');
                                if ($hour < 11) echo 'Pagi';
                                elseif ($hour < 15) echo 'Siang';
                                elseif ($hour < 19) echo 'Sore';
                                else echo 'Malam';
                            @endphp
                            </span>, {{ Auth::user()->name }}!
                        </h1>
                        <p class="text-gray-600 flex items-center justify-center sm:justify-start gap-2">
                            <i class="fas fa-calendar-alt text-blue-500" aria-hidden="true"></i>
                            <time datetime="{{ now()->toIso8601String() }}">
                                {{ now()->translatedFormat('l, d F Y') }}
                            </time>
                        </p>
                        <p class="text-sm text-gray-500 mt-1">Semoga hari Anda produktif dan menyenangkan.</p>
                    </div>
                </div>
            </section>

            <!-- Quick Stats -->
            <section aria-labelledby="quick-stats-heading" class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 mb-8">
                <h2 id="quick-stats-heading" class="sr-only">Quick Statistics</h2>
                
                <article class="stat-card rounded-xl p-4 shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Jadwal Hari Ini</p>
                            <p class="text-2xl font-bold text-green-600">5</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg flex items-center justify-center" aria-hidden="true">
                            <i class="fas fa-calendar-day text-green-600"></i>
                        </div>
                    </div>
                </article>
                
                <article class="stat-card rounded-xl p-4 shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Stok Menipis</p>
                            <p class="text-2xl font-bold text-orange-600">12</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-100 rounded-lg flex items-center justify-center" aria-hidden="true">
                            <i class="fas fa-exclamation-triangle text-orange-600"></i>
                        </div>
                    </div>
                </article>
                
                <article class="stat-card rounded-xl p-4 shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Monitoring PPI</p>
                            <p class="text-2xl font-bold text-blue-600">98%</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center" aria-hidden="true">
                            <i class="fas fa-shield-virus text-blue-600"></i>
                        </div>
                    </div>
                </article>
                
                <article class="stat-card rounded-xl p-4 shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Task Selesai</p>
                            <p class="text-2xl font-bold text-purple-600">28</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-lg flex items-center justify-center" aria-hidden="true">
                            <i class="fas fa-tasks text-purple-600"></i>
                        </div>
                    </div>
                </article>
            </section>

            <!-- Shortcut Cards - Enhanced -->
            <section aria-labelledby="shortcuts-heading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <h2 id="shortcuts-heading" class="sr-only">Quick Shortcuts</h2>
                
                <!-- Jadwal Dinas -->
                <article class="card-hover bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center shadow-lg" aria-hidden="true">
                                <i class="fas fa-calendar-check text-white text-xl sm:text-2xl icon-bounce"></i>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-2" aria-hidden="true"></span>
                                    Aktif
                                </span>
                            </div>
                        </div>
                        
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3">Jadwal Dinas</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Kelola shift dan kunjungan dinas dengan sistem yang terintegrasi dan mudah digunakan.
                        </p>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-clock text-green-500 w-4 mr-3" aria-hidden="true"></i>
                                Shift berikutnya: 14:00 - 22:00
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-users text-green-500 w-4 mr-3" aria-hidden="true"></i>
                                Tim: 8 perawat aktif
                            </div>
                        </div>
                        
                        <a href="/dinas" class="inline-flex items-center justify-center w-full px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-lg hover:shadow-xl focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            <i class="fas fa-calendar-alt mr-2" aria-hidden="true"></i>
                            Lihat Jadwal
                        </a>
                    </div>
                </article>

                <!-- Manajemen Logistik -->
                <article class="card-hover bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg" aria-hidden="true">
                                <i class="fas fa-boxes text-white text-xl sm:text-2xl icon-bounce"></i>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <span class="w-2 h-2 bg-yellow-400 rounded-full mr-2" aria-hidden="true"></span>
                                    Perlu Perhatian
                                </span>
                            </div>
                        </div>
                        
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3">Manajemen Logistik</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Pantau inventaris, stok obat, alat kesehatan, dan kebutuhan operasional lainnya secara real-time.
                        </p>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-chart-line text-orange-500 w-4 mr-3" aria-hidden="true"></i>
                                Stok tersedia: 1,247 item
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-exclamation-circle text-orange-500 w-4 mr-3" aria-hidden="true"></i>
                                Menipis: 12 item
                            </div>
                        </div>
                        
                        <a href="/manajemen-logistik" class="inline-flex items-center justify-center w-full px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-semibold rounded-xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-200 shadow-lg hover:shadow-xl focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                            <i class="fas fa-warehouse mr-2" aria-hidden="true"></i>
                            Lihat Inventaris
                        </a>
                    </div>
                </article>

                <!-- PPI -->
                <article class="card-hover bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg" aria-hidden="true">
                                <i class="fas fa-shield-virus text-white text-xl sm:text-2xl icon-bounce"></i>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full mr-2" aria-hidden="true"></span>
                                    Optimal
                                </span>
                            </div>
                        </div>
                        
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3">PPI</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Sistem monitoring pencegahan dan pengendalian infeksi untuk menjaga standar keamanan fasilitas.
                        </p>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-percentage text-blue-500 w-4 mr-3" aria-hidden="true"></i>
                                Kepatuhan: 98.5%
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-clipboard-check text-blue-500 w-4 mr-3" aria-hidden="true"></i>
                                Audit terakhir: 2 hari lalu
                            </div>
                        </div>
                        
                        <a href="/pengendalian-dan-pencegahan-infeksi" class="inline-flex items-center justify-center w-full px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <i class="fas fa-chart-bar mr-2" aria-hidden="true"></i>
                            Lihat Monitoring
                        </a>
                    </div>
                </article>
            </section>

            <!-- Quick Actions -->
            <section aria-labelledby="quick-actions-heading" class="mt-10 glass-effect rounded-2xl p-6 shadow-lg">
                <h3 id="quick-actions-heading" class="text-xl font-bold text-gray-900 mb-4">Aksi Cepat</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
    <!-- Tombol Tambah Jadwal -->
    <a href="/dinas" class="flex flex-col items-center p-4 rounded-xl hover:bg-white hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-2" aria-hidden="true">
            <i class="fas fa-plus text-purple-600"></i>
        </div>
        <span class="text-sm font-medium text-gray-700">Tambah Jadwal</span>
    </a>
    
    <!-- Tombol Notifikasi -->
    <a href="/notifikasi" class="flex flex-col items-center p-4 rounded-xl hover:bg-white hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-100 rounded-lg flex items-center justify-center mb-2" aria-hidden="true">
            <i class="fas fa-bell text-red-600"></i>
        </div>
        <span class="text-sm font-medium text-gray-700">Notifikasi</span>
    </a>
    
    <!-- Tombol Lihat Laporan -->
    <a href="/laporan" class="flex flex-col items-center p-4 rounded-xl hover:bg-white hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-teal-100 rounded-lg flex items-center justify-center mb-2" aria-hidden="true">
            <i class="fas fa-file-alt text-teal-600"></i>
        </div>
        <span class="text-sm font-medium text-gray-700">Lihat Laporan</span>
    </a>
    
    <!-- Tombol Pengaturan -->
    <a href="/profile" class="flex flex-col items-center p-4 rounded-xl hover:bg-white hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-2" aria-hidden="true">
            <i class="fas fa-cog text-indigo-600"></i>
        </div>
        <span class="text-sm font-medium text-gray-700">Pengaturan</span>
    </a>
</div>
            </section>
        </main>
    </div>

    <!-- Load Flowbite JS at the end of body -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js" defer></script>
</body>
</html>