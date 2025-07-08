<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Wahid Angkasa Paripurna</title>
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
                        'scale-in': 'scaleIn 0.6s ease-out',
                        'slide-left': 'slideLeft 0.8s ease-out',
                        'slide-right': 'slideRight 0.8s ease-out',
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
        @keyframes scaleIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        @keyframes slideLeft {
            from { transform: translateX(-100px); opacity: 0; }
            to { transform: translateX(0px); opacity: 1; }
        }
        @keyframes slideRight {
            from { transform: translateX(100px); opacity: 0; }
            to { transform: translateX(0px); opacity: 1; }
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
        .text-gradient-blue {
            background: linear-gradient(135deg, #0CC0DF 0%, #0891b2 50%, #0e7490 100%);
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
            background: linear-gradient(45deg, rgba(34, 197, 94, 0.1), rgba(12, 192, 223, 0.1));
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
        .timeline-item {
            position: relative;
            padding-left: 2rem;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0.5rem;
            width: 1rem;
            height: 1rem;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border-radius: 50%;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.2);
        }
        .timeline-item::after {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 1.5rem;
            width: 2px;
            height: calc(100% - 1rem);
            background: linear-gradient(to bottom, #22c55e, rgba(34, 197, 94, 0.2));
        }
        .timeline-item:last-child::after {
            display: none;
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
                    <a href="/" class="text-3xl font-bold" style="color: #0CC0DF;">EasyManage</a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-gray-600 hover:text-green-600 transition-colors duration-300">Kembali</a>
                    <a href="#about" class="text-green-600 font-medium">Tentang Kami</a>
                    <a href="#services" class="text-gray-600 hover:text-green-600 transition-colors duration-300">Layanan</a>
                    <a href="#contact" class="text-gray-600 hover:text-green-600 transition-colors duration-300">Kontak</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button class="text-gray-600 hover:text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-24 pb-12 px-4 sm:px-6 lg:px-8 hero-pattern relative" id="about">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 animate-slide-up">
                <div class="inline-flex items-center bg-green-100 text-green-800 text-sm font-medium px-4 py-2 rounded-full mb-6 animate-bounce-gentle">
                    <!-- Logo Wahid akan ditempatkan di sini -->
                    <img src="images/logo wahid.png" alt="Wahid Logo" class="w-5 h-5 mr-2">
                    Konsultan Rumah Sakit Terpercaya
                </div>
                <h1 class="text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                    <span class="text-gradient-green">Wahid Angkasa</span><br>
                    <span class="text-gradient-blue">Paripurna</span>
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Membangun masa depan pelayanan kesehatan Indonesia melalui inovasi teknologi 
                    dan konsultasi manajemen rumah sakit yang profesional.
                </p>
            </div>
        </div>
    </section>

    <!-- Company Overview Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="animate-slide-left">
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">
                        Siapa Kami?
                    </h2>
                    <div class="space-y-6 text-lg text-gray-600">
                        <p>
                            <strong class="text-green-600">Wahid Angkasa Paripurna</strong> adalah perusahaan konsultan yang berfokus pada transformasi digital dan manajemen operasional rumah sakit di Indonesia. Kami memahami bahwa setiap rumah sakit memiliki kebutuhan unik dalam mengelola fasilitas dan sumber daya manusia.
                        </p>
                        <p>
                            Dengan pengalaman bertahun-tahun di industri kesehatan, kami menghadirkan solusi teknologi yang tidak hanya canggih, tetapi juga mudah digunakan oleh tim medis dan administratif.
                        </p>
                        <p>
                            Tim kami terdiri dari para ahli teknologi informasi, manajemen rumah sakit, dan profesional kesehatan yang berkomitmen untuk meningkatkan kualitas pelayanan kesehatan di Indonesia.
                        </p>
                    </div>
                </div>

                <!-- Right Content - Company Image -->
                <div class="animate-slide-right">
                    <div class="relative">
                        <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-3xl p-8 shadow-2xl transform hover:scale-105 transition-transform duration-500">
                            <div class="bg-white rounded-2xl p-8">
                                <div class="text-center">
                                    <div class="w-20 h-20 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Visi Kami</h3>
                                    <p class="text-gray-600">
                                        Menjadi partner terpercaya dalam transformasi digital rumah sakit di Indonesia, 
                                        menghadirkan solusi inovatif yang meningkatkan efisiensi operasional dan kualitas pelayanan pasien.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- Decorative Elements -->
                        <div class="absolute -top-4 -right-4 w-16 h-16 bg-blue-brand rounded-full opacity-20 animate-pulse"></div>
                        <div class="absolute -bottom-4 -left-4 w-12 h-12 bg-green-500 rounded-full opacity-30 animate-bounce-gentle"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Values Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 gradient-bg">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white mb-4">
                    Misi & Nilai Perusahaan
                </h2>
                <p class="text-xl text-green-100 max-w-3xl mx-auto">
                    Komitmen kami dalam memberikan solusi terbaik untuk kemajuan pelayanan kesehatan Indonesia.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Mission -->
                <div class="group p-8 bg-white/90 backdrop-blur-sm rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-green-200 animate-scale-in">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Misi Kami</h3>
                    <p class="text-gray-600">
                        Mengembangkan dan mengimplementasikan sistem manajemen rumah sakit yang efektif, 
                        user-friendly, dan dapat diandalkan untuk mendukung pelayanan kesehatan yang berkualitas.
                    </p>
                </div>

                <!-- Innovation -->
                <div class="group p-8 bg-white/90 backdrop-blur-sm rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-green-200 animate-scale-in" style="animation-delay: 0.2s;">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-brand to-cyan-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Inovasi</h3>
                    <p class="text-gray-600">
                        Terus berinovasi dalam pengembangan teknologi dan metodologi konsultasi untuk 
                        memberikan solusi terdepan bagi klien kami.
                    </p>
                </div>

                <!-- Excellence -->
                <div class="group p-8 bg-white/90 backdrop-blur-sm rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-green-200 animate-scale-in" style="animation-delay: 0.4s;">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Keunggulan</h3>
                    <p class="text-gray-600">
                        Memberikan layanan konsultasi dan solusi teknologi dengan standar kualitas tinggi, 
                        dukungan profesional, dan komitmen jangka panjang.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Layanan Kami
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Solusi komprehensif untuk transformasi digital dan manajemen rumah sakit yang efektif.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div class="group p-8 bg-gradient-to-br from-green-50 to-green-100 rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-green-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Sistem Manajemen Rumah Sakit</h3>
                    <p class="text-gray-600">
                        Pengembangan sistem informasi manajemen rumah sakit (SIMRS) yang terintegrasi 
                        untuk meningkatkan efisiensi operasional dan pelayanan pasien.
                    </p>
                </div>

                <!-- Service 2 -->
                <div class="group p-8 bg-gradient-to-br from-blue-50 to-cyan-100 rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-blue-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-brand to-cyan-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Manajemen SDM Kesehatan</h3>
                    <p class="text-gray-600">
                        Konsultasi dan implementasi sistem manajemen sumber daya manusia khusus 
                        untuk tenaga kesehatan dengan fitur penjadwalan dan monitoring yang canggih.
                    </p>
                </div>

                <!-- Service 3 -->
                <div class="group p-8 bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-purple-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Optimasi Fasilitas</h3>
                    <p class="text-gray-600">
                        Analisis dan optimasi penggunaan fasilitas rumah sakit untuk memaksimalkan 
                        efisiensi ruang dan peralatan medis.
                    </p>
                </div>

                <!-- Service 4 -->
                <div class="group p-8 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-yellow-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Pelatihan & Support</h3>
                    <p class="text-gray-600">
                        Program pelatihan komprehensif untuk staff rumah sakit dan dukungan teknis 
                        berkelanjutan untuk memastikan implementasi yang sukses.
                    </p>
                </div>

                <!-- Service 5 -->
                <div class="group p-8 bg-gradient-to-br from-red-50 to-red-100 rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-red-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Keamanan Data</h3>
                    <p class="text-gray-600">
                        Implementasi sistem keamanan data yang robust untuk melindungi informasi 
                        sensitif pasien dan data operasional rumah sakit.
                    </p>
                </div>

                <!-- Service 6 -->
                <div class="group p-8 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-indigo-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Analisis & Reporting</h3>
                    <p class="text-gray-600">
                        Sistem pelaporan dan analisis data yang komprehensif untuk mendukung 
                        pengambilan keputusan strategis manajemen rumah sakit.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Perjalanan Perusahaan
                </h2>
                <p class="text-xl text-gray-600">
                    Milestone penting dalam perjalanan kami membangun solusi teknologi untuk rumah sakit Indonesia.
                </p>
            </div>

            <div class="space-y-8">
                <div class="timeline-item animate-slide-up">
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center mb-4">
                            <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">2020</span>
                            <h3 class="text-xl font-semibold text-gray-900 ml-4">Pendirian Perusahaan</h3>
                        </div>
                        <p class="text-gray-600">
                            Wahid Angkasa Paripurna didirikan dengan visi untuk mentransformasi manajemen rumah sakit 
                            di Indonesia melalui teknologi dan konsultasi yang inovatif.
                        </p>
                    </div>
                </div>

                <div class="timeline-item animate-slide-up" style="animation-delay: 0.2s;">
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center mb-4">
                            <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">2021</span>
                            <h3 class="text-xl font-semibold text-gray-900 ml-4">Proyek Pertama</h3>
                        </div>
                        <p class="text-gray-600">
                            Berhasil mengimplementasikan sistem manajemen staff dan ruangan pertama di rumah sakit 
                            regional, membuktikan efektivitas solusi kami.
                        </p>
                    </div>
                </div>

                <div class="timeline-item animate-slide-up" style="animation-delay: 0.4s;">
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center mb-4">
                            <span class="bg-purple-100 text-purple-800 text-sm font-medium px-3 py-1 rounded-full">2022</span>
                            <h3 class="text-xl font-semibold text-gray-900 ml-4">Ekspansi Layanan</h3>
                        </div>
                        <p class="text-gray-600">
                            Mengembangkan portfolio layanan dengan menambahkan konsultasi manajemen operasional 
                            dan sistem pelaporan terintegrasi.
                        </p>
                    </div>
                </div>

                <div class="timeline-item animate-slide-up" style="animation-delay: 0.6s;">
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center mb-4">
                            <span class="bg-yellow-100 text-yellow-800 text-sm font-medium px-3 py-1 rounded-full">2023</span>
                            <h3 class="text-xl font-semibold text-gray-900 ml-4">Pengembangan EasyManage</h3>
                        </div>
                        <p class="text-gray-600">
                            Meluncurkan platform EasyManage sebagai solusi komprehensif untuk manajemen ruangan 
                            dan staff rumah sakit dengan interface yang user-friendly.
                        </p>
                    </div>
                </div>

                <div class="timeline-item animate-slide-up" style="animation-delay: 0.8s;">
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center mb-4">
                            <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">2024</span>
                            <h3 class="text-xl font-semibold text-gray-900 ml-4">Pertumbuhan Signifikan</h3>
                        </div>
                        <p class="text-gray-600">
                            Berhasil melayani lebih dari 50 rumah sakit di seluruh Indonesia dengan tingkat 
                            kepuasan klien yang tinggi dan sistem yang stabil.
                        </p>
                    </div>
                </div>

                <div class="timeline-item animate-slide-up" style="animation-delay: 1s;">
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center mb-4">
                            <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">2025</span>
                            <h3 class="text-xl font-semibold text-gray-900 ml-4">Inovasi Berkelanjutan</h3>
                        </div>
                        <p class="text-gray-600">
                            Terus berinovasi dengan fitur-fitur baru dan ekspansi ke lebih banyak rumah sakit 
                            untuk mendukung digitalisasi sektor kesehatan Indonesia.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Tim Ahli Kami
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Didukung oleh tim profesional berpengalaman yang memahami industri kesehatan dan teknologi.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Team Member 1 -->
                <div class="group p-8 bg-gradient-to-br from-green-50 to-green-100 rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-green-200">
                    <div class="w-24 h-24 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="text-center">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Tim Teknologi</h3>
                        <p class="text-green-600 font-medium mb-4">Developer & System Architect</p>
                        <p class="text-gray-600">
                            Ahli dalam pengembangan sistem informasi kesehatan dengan pengalaman 
                            lebih dari 10 tahun di industri teknologi medis.
                        </p>
                    </div>
                </div>

                <!-- Team Member 2 -->
                <div class="group p-8 bg-gradient-to-br from-blue-50 to-cyan-100 rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-blue-200">
                    <div class="w-24 h-24 bg-gradient-to-r from-blue-brand to-cyan-500 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="text-center">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Tim Konsultan</h3>
                        <p class="text-blue-600 font-medium mb-4">Healthcare Management Expert</p>
                        <p class="text-gray-600">
                            Spesialis manajemen rumah sakit dengan latar belakang administrasi 
                            kesehatan dan pengalaman operasional di berbagai fasilitas kesehatan.
                        </p>
                    </div>
                </div>

                <!-- Team Member 3 -->
                <div class="group p-8 bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-purple-200">
                    <div class="w-24 h-24 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div class="text-center">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Tim Support</h3>
                        <p class="text-purple-600 font-medium mb-4">Customer Success & Training</p>
                        <p class="text-gray-600">
                            Berpengalaman dalam pelatihan dan pendampingan implementasi sistem 
                            dengan fokus pada kepuasan dan kesuksesan klien.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 px-4 sm:px-6 lg:px-8 gradient-bg">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl font-bold text-white mb-6">
                Mari Berkolaborasi
            </h2>
            <p class="text-xl text-green-100 mb-8">
                Tertarik untuk mengetahui lebih lanjut tentang solusi kami? 
                Tim ahli kami siap membantu transformasi digital rumah sakit Anda.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="px-8 py-4 bg-white text-green-600 font-semibold rounded-full hover:bg-green-50 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    Konsultasi Gratis
                </button>
                <button class="px-8 py-4 bg-green-600 text-white font-semibold rounded-full hover:bg-green-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl border-2 border-white">
                    Hubungi Kami
                </button>
            </div>

            <!-- Contact Info -->
            <div class="grid md:grid-cols-3 gap-8 mt-16">
                <div class="text-center">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Email</h3>
                    <p class="text-green-100">info@wahidangkasa.com</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Telepon</h3>
                    <p class="text-green-100">+62 21 1234 5678</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Alamat</h3>
                    <p class="text-green-100">Jakarta, Indonesia</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-green-800 text-white py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <span class="text-2xl font-bold" style="color: #0CC0DF;">EasyManage</span>
                    <p class="text-green-200 mt-4">
                        Dikembangkan oleh <strong>Wahid Angkasa Paripurna</strong> - 
                        Konsultan teknologi rumah sakit terpercaya Indonesia.
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Layanan</h4>
                    <ul class="space-y-2 text-green-200">
                        <li><a href="#" class="hover:text-white transition-colors">Sistem Manajemen RS</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Manajemen SDM</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Konsultasi Teknologi</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Perusahaan</h4>
                    <ul class="space-y-2 text-green-200">
                        <li><a href="#about" class="hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="#services" class="hover:text-white transition-colors">Layanan</a></li>
                        <li><a href="#contact" class="hover:text-white transition-colors">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Dukungan</h4>
                    <ul class="space-y-2 text-green-200">
                        <li><a href="#" class="hover:text-white transition-colors">Dokumentasi</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Pelatihan</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Technical Support</a></li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 border-t border-green-700 text-center text-green-200">
                <p>© 2025 Wahid Angkasa Paripurna. Semua hak dilindungi undang-undang.</p>
                <p class="mt-2 text-sm">Konsultan teknologi dan manajemen rumah sakit terpercaya di Indonesia.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-slide-up');
                }
            });
        }, observerOptions);

        // Observe all sections
        document.querySelectorAll('section').forEach(section => {
            observer.observe(section);
        });

        // Add hover effects for interactive elements
        document.querySelectorAll('.group').forEach(element => {
            element.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            element.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
</body>
</html>