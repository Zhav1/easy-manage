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
        html {
  scrollbar-width: none;
}

/* Untuk IE/Edge */
body {
  -ms-overflow-style: none;
}

/* Pastikan konten utama bisa scroll */
.main-content {
  overflow-y: scroll;
  -webkit-overflow-scrolling: touch; /* Untuk scroll halus di mobile */
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
    </style>
</head>
<body class="h-full border-gray-200">
     @include('components.sidebar-navbar')
     {{-- <!-- Header dengan efek floating -->
<div class="floating mt-16">
    <div class="container mx-auto px-6 pt-8 pl-60 pr-5">
        <div class="glass-effect rounded-2xl p-6 mb-8 shadow-2xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-3 rounded-xl shadow-lg">
                        <i class="fas fa-bell text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Notifikasi</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}
    <!-- Header dengan efek floating -->
    <div class="floating mt-16">
        <div class="container mx-auto px-6 pt-8 pl-60 pr-5 ">
            <div class="glass-effect rounded-2xl p-6 mb-8 shadow-2xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 p-3 rounded-xl shadow-lg">
                            <i class="fas fa-bell text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800">Notifikasi</h1>
                            <p class="text-gray-600 mt-1">Kelola pesan dan approval Anda</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
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
    <div class="container mx-auto px-6 pb-8 pl-60 pr-5 ">
        <div class="glass-effect rounded-2xl shadow-2xl overflow-hidden">
            
            <!-- Tabs dengan desain modern -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                <div class="flex">
                    <button onclick="showTab('pesan')" id="tab-pesan"
                        class="tab-button flex-1 py-4 px-6 text-center font-semibold text-blue-600 bg-white border-b-4 border-blue-500 relative">
                        <i class="fas fa-envelope mr-2"></i>
                        Pesan
                        <span class="absolute top-2 right-2 bg-blue-500 text-white text-xs rounded-full px-2 py-1">2</span>
                    </button>
                    <button onclick="showTab('approval')" id="tab-approval"
                        class="tab-button flex-1 py-4 px-6 text-center font-semibold text-gray-600 hover:text-green-600 hover:bg-white/50 transition-all duration-300 relative">
                        <i class="fas fa-check-circle mr-2"></i>
                        Approval
                        <span class="absolute top-2 right-2 bg-green-500 text-white text-xs rounded-full px-2 py-1">2</span>
                    </button>
                </div>
            </div>

            <!-- Content Area -->
            <div class="p-8">
                
                <!-- Tab Pesan -->
                <div id="content-pesan" class="slide-in">
                    <div class="space-y-6">
                        <div class="notification-card bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 p-6 rounded-xl shadow-lg">
                            <div class="flex items-start space-x-4">
                                <div class="bg-blue-500 p-3 rounded-full flex-shrink-0">
                                    <i class="fas fa-clipboard-check text-white"></i>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-semibold text-blue-800">Formulir Berhasil Disubmit</h3>
                                        <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded-full">10:45</span>
                                    </div>
                                    <p class="text-blue-700 mb-3">Anda telah berhasil mengisi formulir manajemen logistik.</p>
                                    <div class="flex space-x-2">
                                        <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            <i class="fas fa-eye mr-1"></i>Lihat Detail
                                        </button>
                                        <button class="text-gray-500 hover:text-gray-700 text-sm">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="notification-card bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 p-6 rounded-xl shadow-lg">
                            <div class="flex items-start space-x-4">
                                <div class="bg-purple-500 p-3 rounded-full flex-shrink-0">
                                    <i class="fas fa-calendar-alt text-white"></i>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-semibold text-purple-800">Jadwal Diperbarui</h3>
                                        <span class="text-xs text-purple-600 bg-purple-100 px-2 py-1 rounded-full">08:20</span>
                                    </div>
                                    <p class="text-purple-700 mb-3">Jadwal dinas Anda telah diperbarui untuk minggu ini.</p>
                                    <div class="flex space-x-2">
                                        <button class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                            <i class="fas fa-calendar mr-1"></i>Lihat Jadwal
                                        </button>
                                        <button class="text-gray-500 hover:text-gray-700 text-sm">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Approval -->
                <div id="content-approval" class="hidden">
                    <div class="space-y-6">
                        <div class="notification-card bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 p-6 rounded-xl shadow-lg">
                            <div class="flex items-start space-x-4">
                                <div class="bg-green-500 p-3 rounded-full flex-shrink-0">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-semibold text-green-800">Permintaan dari Staff Ruang A</h3>
                                        <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">Menunggu</span>
                                    </div>
                                    <p class="text-green-700 mb-4">Permintaan akses sistem untuk manajemen inventaris ruang operasi.</p>
                                    <div class="flex space-x-3">
                                        <button class="ripple bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-2 rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-300 font-medium shadow-lg">
                                            <i class="fas fa-check mr-2"></i>Setujui
                                        </button>
                                        <button class="ripple bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-2 rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-300 font-medium shadow-lg">
                                            <i class="fas fa-times mr-2"></i>Tolak
                                        </button>
                                        <button class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-all duration-300">
                                            <i class="fas fa-comment mr-2"></i>Komentar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="notification-card bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 p-6 rounded-xl shadow-lg">
                            <div class="flex items-start space-x-4">
                                <div class="bg-amber-500 p-3 rounded-full flex-shrink-0">
                                    <i class="fas fa-boxes text-white"></i>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-semibold text-amber-800">Permintaan Logistik dari Staff IGD</h3>
                                        <span class="text-xs text-amber-600 bg-amber-100 px-2 py-1 rounded-full">Urgent</span>
                                    </div>
                                    <p class="text-amber-700 mb-4">Permintaan tambahan peralatan medis untuk unit gawat darurat.</p>
                                    <div class="flex space-x-3">
                                        <button class="ripple bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-2 rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-300 font-medium shadow-lg">
                                            <i class="fas fa-check mr-2"></i>Setujui
                                        </button>
                                        <button class="ripple bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-2 rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-300 font-medium shadow-lg">
                                            <i class="fas fa-times mr-2"></i>Tolak
                                        </button>
                                        <button class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-all duration-300">
                                            <i class="fas fa-comment mr-2"></i>Komentar
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

    <div class="p-4 pt-20 pl-60 pr-5 animate-fadeIn">


    </div>

    

    <!-- Tab switching script -->
    <script>
        function showTab(tab) {
            const tabs = ['pesan', 'approval'];
            
            tabs.forEach(t => {
                const content = document.getElementById('content-' + t);
                const tabButton = document.getElementById('tab-' + t);
                
                content.classList.add('hidden');
                tabButton.classList.remove('text-blue-600', 'text-green-600', 'bg-white', 'border-blue-500', 'border-green-500');
                tabButton.classList.add('text-gray-600', 'border-transparent');
            });

            // Show selected tab
            const activeContent = document.getElementById('content-' + tab);
            const activeTab = document.getElementById('tab-' + tab);
            
            activeContent.classList.remove('hidden');
            activeContent.classList.add('slide-in');
            
            if (tab === 'pesan') {
                activeTab.classList.add('text-blue-600', 'bg-white', 'border-blue-500');
                activeTab.classList.remove('text-gray-600', 'border-transparent');
            } else {
                activeTab.classList.add('text-green-600', 'bg-white', 'border-green-500');
                activeTab.classList.remove('text-gray-600', 'border-transparent');
            }
        }

        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to notification cards
            const cards = document.querySelectorAll('.notification-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px) scale(1.02)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
        });
    </script>
</body>
</html>