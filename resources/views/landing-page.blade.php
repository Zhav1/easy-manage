<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyManage - Kelola Ruangan dan Staff Jadi Gampang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                        blue: {
                            brand: '#0CC0DF',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'slide-up': 'slideUp 0.8s ease-out',
                        'fade-in': 'fadeIn 1s ease-out',
                        'bounce-gentle': 'bounceGentle 2s ease-in-out infinite',
                        'gradient-shift': 'gradientShift 8s ease-in-out infinite',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
        }
        @keyframes slideUp {
            from { transform: translateY(100px); opacity: 0; }
            to { transform: translateY(0px); opacity: 1; }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes bounceGentle {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .gradient-bg {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 25%, #15803d 50%, #14532d 75%, #22c55e 100%);
            background-size: 400% 400%;
            animation: gradientShift 8s ease-in-out infinite;
        }
        .glass-effect {
            backdrop-filter: blur(16px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(34, 197, 94, 0.2);
            box-shadow: 0 8px 32px rgba(34, 197, 94, 0.1);
        }
        .text-gradient-green {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 50%, #15803d 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-pattern {
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(34, 197, 94, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(22, 163, 74, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(21, 128, 61, 0.05) 0%, transparent 50%);
        }
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        .floating-elements::before,
        .floating-elements::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: linear-gradient(45deg, rgba(34, 197, 94, 0.1), rgba(22, 163, 74, 0.1));
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }
        .floating-elements::before {
            top: 10%;
            left: -10%;
            animation-delay: -2s;
        }
        .floating-elements::after {
            bottom: 10%;
            right: -10%;
            animation-delay: -4s;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-100 overflow-x-hidden">
    <div class="floating-elements"></div>
    
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 glass-effect animate-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo Section -->
                <div class="flex items-center animate-slide-up">
                    <span class="text-3xl font-bold" style="color: #0CC0DF;">EasyManage</span>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-4 animate-slide-up">
                    <a href="/login">
                        <button class="px-6 py-2 text-green-600 font-medium hover:text-green-800 transition-colors duration-300 transform hover:scale-105">
                            Masuk
                        </button>
                    </a>
                    <a href="/register">
                        <button class="px-6 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white font-medium rounded-full hover:from-green-600 hover:to-green-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            Daftar
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-24 pb-12 px-4 sm:px-6 lg:px-8 hero-pattern relative">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="animate-slide-up">
                    <div class="inline-flex items-center bg-green-100 text-green-800 text-sm font-medium px-4 py-2 rounded-full mb-6 animate-bounce-gentle">
                        🏥 Sistem Desktop Terpercaya
                    </div>
                    
                    <h1 class="text-5xl lg:text-7xl font-extrabold leading-tight mb-6">
                        <span class="text-gradient-green">Kelola</span><br>
                        <span class="text-gray-900">Ruangan & Staff</span><br>
                        <span class="text-gradient-green">Jadi Gampang</span>
                    </h1>
                    
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        Pantau ruangan rumah sakit dan jadwal staff dengan mudah dari desktop. 
                        Tau siapa yang piket hari ini, ruangan mana yang aktif, dan kelola semua dari satu tempat.
                    </p>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 mb-8">
                        <button class="group px-8 py-4 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-full hover:from-green-600 hover:to-green-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-2xl">
                            <span class="flex items-center justify-center">
                                Mulai Sekarang
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                        </button>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-8 pt-8 border-t border-green-200">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600 mb-1">50+</div>
                            <div class="text-sm text-gray-500">Rumah Sakit</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600 mb-1">Desktop</div>
                            <div class="text-sm text-gray-500">Based System</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600 mb-1">Real-time</div>
                            <div class="text-sm text-gray-500">Monitoring</div>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Interactive Dashboard Mockup -->
                <div class="relative animate-float">
                    <div class="relative z-10">
                        <!-- Main Dashboard Card -->
                        <div class="bg-white rounded-3xl shadow-2xl p-8 transform hover:scale-105 transition-transform duration-500 border border-green-100">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-semibold text-gray-800">Ruang IGD</h3>
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                                    <span class="text-sm text-green-600 font-medium">Staff Ready</span>
                                </div>
                            </div>
                            
                            <!-- Status Cards -->
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-xl">
                                    <div class="text-2xl font-bold text-green-600">3</div>
                                    <div class="text-sm text-green-700">Staff Aktif</div>
                                </div>
                                <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-xl">
                                    <div class="text-2xl font-bold text-green-600">24/7</div>
                                    <div class="text-sm text-green-700">Operasional</div>
                                </div>
                            </div>
                            
                            <!-- Staff Schedule -->
                            <div class="space-y-3 mb-6">
                                <h4 class="font-medium text-gray-700">Jadwal Staff Minggu Ini</h4>
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                        <div>
                                            <span class="text-sm font-medium text-gray-800">Senin</span>
                                            <div class="text-xs text-gray-500">Shift Pagi</div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <div class="w-8 h-8 bg-green-200 rounded-full flex items-center justify-center">
                                                <span class="text-xs font-medium text-green-800">Y</span>
                                            </div>
                                            <span class="text-sm text-green-600 font-medium">Yanti</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                        <div>
                                            <span class="text-sm font-medium text-gray-800">Selasa</span>
                                            <div class="text-xs text-gray-500">Shift Pagi</div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <div class="w-8 h-8 bg-blue-200 rounded-full flex items-center justify-center">
                                                <span class="text-xs font-medium text-blue-800">A</span>
                                            </div>
                                            <span class="text-sm text-blue-600 font-medium">Andi</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                                        <div>
                                            <span class="text-sm font-medium text-gray-800">Rabu</span>
                                            <div class="text-xs text-gray-500">Shift Pagi</div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <div class="w-8 h-8 bg-purple-200 rounded-full flex items-center justify-center">
                                                <span class="text-xs font-medium text-purple-800">S</span>
                                            </div>
                                            <span class="text-sm text-purple-600 font-medium">Sari</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="flex gap-2">
                                <button class="flex-1 py-2 px-3 bg-green-500 text-white text-sm rounded-lg hover:bg-green-600 transition-colors">
                                    + Atur Jadwal
                                </button>
                                <button class="flex-1 py-2 px-3 bg-gray-100 text-gray-600 text-sm rounded-lg hover:bg-gray-200 transition-colors">
                                    Laporan Staff
                                </button>
                            </div>
                        </div>

                        <!-- Floating Success Cards -->
                        <div class="absolute -bottom-6 -left-6 bg-gradient-to-r from-green-400 to-emerald-500 text-white p-4 rounded-2xl shadow-xl transform -rotate-6 hover:-rotate-12 transition-transform duration-300">
                            <div class="text-2xl font-bold">🖥️</div>
                            <div class="text-sm opacity-90">Desktop</div>
                        </div>
                    </div>

                    <!-- Background Decorations -->
                    <div class="absolute inset-0 bg-gradient-to-r from-green-400 to-green-500 rounded-3xl transform rotate-6 scale-105 opacity-10"></div>
                    <div class="absolute inset-0 bg-gradient-to-l from-green-500 to-emerald-500 rounded-3xl transform -rotate-3 scale-110 opacity-5"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 px-4 sm:px-6 lg:px-8 gradient-bg">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white mb-4">
                    Kenapa Rumah Sakit Pilih EasyManage?
                </h2>
                <p class="text-xl text-green-100 max-w-3xl mx-auto">
                    Fitur-fitur yang bikin manajemen ruangan dan staff jadi lebih terorganisir.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="group p-8 bg-white/90 backdrop-blur-sm rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-green-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Manajemen Staff</h3>
                    <p class="text-gray-600">Atur jadwal piket staff dengan mudah. Tau siapa yang masuk hari ini, siapa yang libur, dan siapa yang standby untuk emergency.</p>
                </div>

                <!-- Feature 2 -->
                <div class="group p-8 bg-white/90 backdrop-blur-sm rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-green-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Monitor Ruangan</h3>
                    <p class="text-gray-600">Pantau semua ruangan dari desktop. IGD, ICU, ruang rawat inap, dan ruang operasi - semua status real-time dalam satu layar.</p>
                </div>

                <!-- Feature 3 -->
                <div class="group p-8 bg-white/90 backdrop-blur-sm rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-green-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Laporan Lengkap</h3>
                    <p class="text-gray-600">Generate laporan kehadiran staff, penggunaan ruangan, dan statistik operasional untuk evaluasi manajemen rumah sakit.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl font-bold text-gray-900 mb-6">
                Siap Upgrade Sistem Rumah Sakit?
            </h2>
            <p class="text-xl text-gray-600 mb-8">
                Bergabunglah dengan puluhan rumah sakit yang sudah menggunakan EasyManage 
                untuk kelola ruangan dan staff mereka dengan lebih efisien.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="px-8 py-4 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-full hover:from-green-600 hover:to-green-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    Mulai Sekarang?
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-green-800 text-white py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <span class="text-2xl font-bold" style="color: #0CC0DF;">EasyManage</span>
                    <p class="text-green-200 mt-4">Solusi manajemen ruangan dan staff untuk rumah sakit modern.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Fitur Utama</h4>
                    <ul class="space-y-2 text-green-200">
                        <li><a href="#" class="hover:text-white transition-colors">Manajemen Staff</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Monitor Ruangan</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Laporan Real-time</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Untuk Rumah Sakit</h4>
                    <ul class="space-y-2 text-green-200">
                        <li><a href="#" class="hover:text-white transition-colors">IGD Management</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">ICU Monitoring</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Staff Scheduling</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Support</h4>
                    <ul class="space-y-2 text-green-200">
                        <li><a href="#" class="hover:text-white transition-colors">Panduan Penggunaan</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Training Online</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Technical Support</a></li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 border-t border-green-700 text-center text-green-200">
                © 2025 EasyManage. Sistem manajemen terpercaya untuk rumah sakit Indonesia.
            </div>
        </div>
    </footer>
</body>
</html>