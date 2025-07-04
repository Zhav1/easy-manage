<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Notifikasi - EasyManage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .notification-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateY(0);
        }
        
        .notification-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .tab-button {
            position: relative;
            overflow: hidden;
        }
        
        .tab-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .tab-button:hover::before {
            left: 100%;
        }
        
        .pulse-dot {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }
        
        .slide-in {
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translate(0, 0px); }
            50% { transform: translate(0, -10px); }
            100% { transform: translate(0, -0px); }
        }
        
        .ripple {
            position: relative;
            overflow: hidden;
        }
        
        .ripple:before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transition: width 0.6s, height 0.6s;
            transform: translate(-50%, -50%);
        }
        
        .ripple:active:before {
            width: 300px;
            height: 300px;
        }

        /* Mobile optimizations */
        @media (max-width: 768px) {
            .mobile-padding {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .mobile-mt {
                margin-top: 1rem;
            }
            
            .mobile-stack {
                flex-direction: column;
            }
            
            .mobile-center {
                text-align: center;
                justify-content: center;
            }
            
            .mobile-small-text {
                font-size: 0.875rem;
            }
            
            .notification-card {
                padding: 1rem;
            }
            
            .mobile-hidden {
                display: none;
            }
            
            .mobile-full-width {
                width: 100%;
            }
            
            .mobile-button-group {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .mobile-button-group button {
                width: 100%;
            }
        }
        ::-webkit-scrollbar {
  width: 0px;          /* hilang sama sekali */
  background: transparent;
}
    </style>
</head>
<body class="min-h-full bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100">
    @include('components.sidebar-navbar')
    
    <!-- Header with floating effect -->
    <div class="floating mt-16 md:mt-16">
        <div class="container mx-auto px-4 md:px-6 pt-4 md:pt-8 md:pl-60 md:pr-5">
            <div class="glass-effect rounded-2xl p-4 md:p-6 mb-6 md:mb-8 shadow-2xl">
                <div class="flex items-center justify-between mobile-stack">
                    <div class="flex items-center space-x-3 md:space-x-4 mobile-center">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 p-2 md:p-3 rounded-xl shadow-lg">
                            <i class="fas fa-bell text-white text-xl md:text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Notifikasi</h1>
                            <p class="text-gray-600 mt-1 mobile-small-text">Kelola pesan dan approval Anda</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3 mt-3 md:mt-0">
                        <div class="relative">
                            <div class="pulse-dot absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></div>
                            <div class="bg-white/20 p-2 rounded-lg">
                                <i class="fas fa-cog text-gray-700"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 md:px-6 pb-6 md:pb-8 md:pl-60 md:pr-5">
        <div class="glass-effect rounded-2xl shadow-2xl overflow-hidden">
            
            <!-- Tabs with modern design -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                <div class="flex overflow-x-auto">
                    <button onclick="showTab('reminder')" id="tab-reminder"
                        class="tab-button flex-1 py-3 md:py-4 px-4 md:px-6 text-center font-semibold text-blue-600 bg-white border-b-4 border-blue-500 relative min-w-max">
                        <i class="fas fa-clock mr-2"></i>
                        Reminder
                        <span class="absolute top-1 md:top-2 right-1 md:right-2 bg-blue-500 text-white text-xs rounded-full px-2 py-1">8</span>
                    </button>
                </div>
            </div>

            <!-- Content Area -->
            <div class="p-4 md:p-8">
                
                <!-- Tab Reminder -->
                <div id="content-reminder" class="slide-in">
                    <div class="space-y-4 md:space-y-6">
                        <!-- Jadwal Dinas Reminder -->
                        <div class="notification-card bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 p-4 md:p-6 rounded-xl shadow-lg">
                            <div class="flex items-start space-x-3 md:space-x-4">
                                <div class="bg-blue-500 p-2 md:p-3 rounded-full flex-shrink-0">
                                    <i class="fas fa-calendar-check text-white"></i>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex items-center justify-between mb-1 md:mb-2">
                                        <h3 class="font-semibold text-blue-800 text-sm md:text-base">Jadwal Dinas Besok</h3>
                                        <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded-full">Besok</span>
                                    </div>
                                    <p class="text-blue-700 mb-2 md:mb-3 text-sm md:text-base">Anda dijadwalkan dinas pagi mulai jam 07:00 - 15:00 di Ruang ICU.</p>
                                    <div class="flex space-x-2 mobile-button-group">
                                        <button class="text-blue-600 hover:text-blue-800 text-xs md:text-sm font-medium">
                                            <i class="fas fa-eye mr-1"></i>Lihat Jadwal
                                        </button>
                                        <button class="text-gray-500 hover:text-gray-700 text-xs md:text-sm">
                                            <i class="fas fa-bell-slash mr-1"></i>Matikan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Manajemen Logistik Reminder -->
                        <div class="notification-card bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 p-4 md:p-6 rounded-xl shadow-lg">
                            <div class="flex items-start space-x-3 md:space-x-4">
                                <div class="bg-yellow-500 p-2 md:p-3 rounded-full flex-shrink-0">
                                    <i class="fas fa-boxes text-white"></i>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex items-center justify-between mb-1 md:mb-2">
                                        <h3 class="font-semibold text-yellow-800 text-sm md:text-base">Stok Menipis</h3>
                                        <span class="text-xs text-yellow-600 bg-yellow-100 px-2 py-1 rounded-full">Urgent</span>
                                    </div>
                                    <p class="text-yellow-700 mb-2 md:mb-3 text-sm md:text-base">Sarung tangan medis tersisa 15 box. Segera lakukan pemesanan ulang.</p>
                                    <div class="flex space-x-2 mobile-button-group">
                                        <button class="text-yellow-600 hover:text-yellow-800 text-xs md:text-sm font-medium">
                                            <i class="fas fa-plus mr-1"></i>Pesan Sekarang
                                        </button>
                                        <button class="text-gray-500 hover:text-gray-700 text-xs md:text-sm">
                                            <i class="fas fa-clock mr-1"></i>Ingatkan Nanti
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PPI Reminder -->
                        <div class="notification-card bg-gradient-to-r from-teal-50 to-cyan-50 border border-teal-200 p-4 md:p-6 rounded-xl shadow-lg">
                            <div class="flex items-start space-x-3 md:space-x-4">
                                <div class="bg-teal-500 p-2 md:p-3 rounded-full flex-shrink-0">
                                    <i class="fas fa-shield-virus text-white"></i>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex items-center justify-between mb-1 md:mb-2">
                                        <h3 class="font-semibold text-teal-800 text-sm md:text-base">Audit PPI Mingguan</h3>
                                        <span class="text-xs text-teal-600 bg-teal-100 px-2 py-1 rounded-full">Minggu Ini</span>
                                    </div>
                                    <p class="text-teal-700 mb-2 md:mb-3 text-sm md:text-base">Waktu audit pengendalian infeksi rutin untuk area rawat inap.</p>
                                    <div class="flex space-x-2 mobile-button-group">
                                        <button class="text-teal-600 hover:text-teal-800 text-xs md:text-sm font-medium">
                                            <i class="fas fa-clipboard-check mr-1"></i>Mulai Audit
                                        </button>
                                        <button class="text-gray-500 hover:text-gray-700 text-xs md:text-sm">
                                            <i class="fas fa-calendar mr-1"></i>Reschedule
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kinerja Staff Reminder -->
                        <div class="notification-card bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 p-4 md:p-6 rounded-xl shadow-lg">
                            <div class="flex items-start space-x-3 md:space-x-4">
                                <div class="bg-green-500 p-2 md:p-3 rounded-full flex-shrink-0">
                                    <i class="fas fa-chart-line text-white"></i>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex items-center justify-between mb-1 md:mb-2">
                                        <h3 class="font-semibold text-green-800 text-sm md:text-base">Evaluasi Kinerja Bulanan</h3>
                                        <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">Deadline: 3 Hari</span>
                                    </div>
                                    <p class="text-green-700 mb-2 md:mb-3 text-sm md:text-base">Saatnya melakukan evaluasi kinerja untuk tim di unit Anda.</p>
                                    <div class="flex space-x-2 mobile-button-group">
                                        <button class="text-green-600 hover:text-green-800 text-xs md:text-sm font-medium">
                                            <i class="fas fa-star mr-1"></i>Mulai Evaluasi
                                        </button>
                                        <button class="text-gray-500 hover:text-gray-700 text-xs md:text-sm">
                                            <i class="fas fa-history mr-1"></i>Lihat Riwayat
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TNA Reminder -->
                        <div class="notification-card bg-gradient-to-r from-purple-50 to-violet-50 border border-purple-200 p-4 md:p-6 rounded-xl shadow-lg">
                            <div class="flex items-start space-x-3 md:space-x-4">
                                <div class="bg-purple-500 p-2 md:p-3 rounded-full flex-shrink-0">
                                    <i class="fas fa-book text-white"></i>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex items-center justify-between mb-1 md:mb-2">
                                        <h3 class="font-semibold text-purple-800 text-sm md:text-base">Training Needs Analysis</h3>
                                        <span class="text-xs text-purple-600 bg-purple-100 px-2 py-1 rounded-full">Pending</span>
                                    </div>
                                    <p class="text-purple-700 mb-2 md:mb-3 text-sm md:text-base">Lengkapi analisis kebutuhan pelatihan untuk staff baru.</p>
                                    <div class="flex space-x-2 mobile-button-group">
                                        <button class="text-purple-600 hover:text-purple-800 text-xs md:text-sm font-medium">
                                            <i class="fas fa-edit mr-1"></i>Lengkapi TNA
                                        </button>
                                        <button class="text-gray-500 hover:text-gray-700 text-xs md:text-sm">
                                            <i class="fas fa-download mr-1"></i>Download Template
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Indikator Mutu Reminder -->
                        <div class="notification-card bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 p-4 md:p-6 rounded-xl shadow-lg">
                            <div class="flex items-start space-x-3 md:space-x-4">
                                <div class="bg-indigo-500 p-2 md:p-3 rounded-full flex-shrink-0">
                                    <i class="fas fa-bullseye text-white"></i>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex items-center justify-between mb-1 md:mb-2">
                                        <h3 class="font-semibold text-indigo-800 text-sm md:text-base">Update Indikator Mutu</h3>
                                        <span class="text-xs text-indigo-600 bg-indigo-100 px-2 py-1 rounded-full">Bulanan</span>
                                    </div>
                                    <p class="text-indigo-700 mb-2 md:mb-3 text-sm md:text-base">Waktu untuk memperbarui data indikator mutu pelayanan bulan ini.</p>
                                    <div class="flex space-x-2 mobile-button-group">
                                        <button class="text-indigo-600 hover:text-indigo-800 text-xs md:text-sm font-medium">
                                            <i class="fas fa-chart-bar mr-1"></i>Input Data
                                        </button>
                                        <button class="text-gray-500 hover:text-gray-700 text-xs md:text-sm">
                                            <i class="fas fa-file-export mr-1"></i>Export Report
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Reminder -->
                        <div class="notification-card bg-gradient-to-r from-pink-50 to-rose-50 border border-pink-200 p-4 md:p-6 rounded-xl shadow-lg">
                            <div class="flex items-start space-x-3 md:space-x-4">
                                <div class="bg-pink-500 p-2 md:p-3 rounded-full flex-shrink-0">
                                    <i class="fas fa-calendar-alt text-white"></i>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex items-center justify-between mb-1 md:mb-2">
                                        <h3 class="font-semibold text-pink-800 text-sm md:text-base">Meeting Tim Medis</h3>
                                        <span class="text-xs text-pink-600 bg-pink-100 px-2 py-1 rounded-full">Hari Ini 14:00</span>
                                    </div>
                                    <p class="text-pink-700 mb-2 md:mb-3 text-sm md:text-base">Rapat koordinasi tim medis di ruang meeting lantai 2.</p>
                                    <div class="flex space-x-2 mobile-button-group">
                                        <button class="text-pink-600 hover:text-pink-800 text-xs md:text-sm font-medium">
                                            <i class="fas fa-video mr-1"></i>Join Meeting
                                        </button>
                                        <button class="text-gray-500 hover:text-gray-700 text-xs md:text-sm">
                                            <i class="fas fa-calendar-times mr-1"></i>Reschedule
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Laporan Reminder -->
                        <div class="notification-card bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 p-4 md:p-6 rounded-xl shadow-lg">
                            <div class="flex items-start space-x-3 md:space-x-4">
                                <div class="bg-red-500 p-2 md:p-3 rounded-full flex-shrink-0">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex items-center justify-between mb-1 md:mb-2">
                                        <h3 class="font-semibold text-red-800 text-sm md:text-base">Laporan Mingguan</h3>
                                        <span class="text-xs text-red-600 bg-red-100 px-2 py-1 rounded-full">Deadline: Besok</span>
                                    </div>
                                    <p class="text-red-700 mb-2 md:mb-3 text-sm md:text-base">Segera selesaikan laporan aktivitas mingguan untuk diserahkan ke supervisor.</p>
                                    <div class="flex space-x-2 mobile-button-group">
                                        <button class="text-red-600 hover:text-red-800 text-xs md:text-sm font-medium">
                                            <i class="fas fa-pen mr-1"></i>Buat Laporan
                                        </button>
                                        <button class="text-gray-500 hover:text-gray-700 text-xs md:text-sm">
                                            <i class="fas fa-template mr-1"></i>Gunakan Template
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('[id^="content-"]').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('bg-white', 'border-b-4', 'border-blue-500');
                button.classList.add('bg-gray-50');
            });
            
            // Show selected tab content
            document.getElementById(`content-${tabName}`).classList.remove('hidden');
            
            // Add active class to selected tab button
            document.getElementById(`tab-${tabName}`).classList.remove('bg-gray-50');
            document.getElementById(`tab-${tabName}`).classList.add('bg-white', 'border-b-4', 'border-blue-500');
        }
        
        // Initialize by showing the reminder tab
        document.addEventListener('DOMContentLoaded', function() {
            showTab('reminder');
        });
    </script>
</body>
</html>