<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Kepala Ruangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</head>
<body class="min-h-full  bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100">
     @include('components.sidebar-navbar')
    <div class="container mx-auto p-4 pt-20 pl-60 pr-5 ">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8 animate-fadeIn ">
            <h1 class="text-3xl font-bold text-gray-800">Profil Kepala Ruangan</h1>
            <div class="flex items-center space-x-4">
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-cog mr-2"></i>Pengaturan
                </button>
            </div>
        </div>

        <!-- Profile Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8 animate-fadeIn">
            <div class="md:flex">
                <div class="md:w-1/4 p-6 flex justify-center">
                    <div class="relative">
                        <img class="h-40 w-40 rounded-full object-cover border-4 border-green-500" 
                             src="images/foto-formal.png" alt="Profile Photo">
                       
                    </div>
                </div>
                <div class="md:w-3/4 p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Dr. Ahmad Budiman, S.Kep, Ns</h2>
                            <p class="text-gray-600 mb-2">Kepala Ruangan Rawat Inap</p>
                            <div class="flex items-center text-gray-600 mb-4">
                                <i class="fas fa-hospital mr-2"></i>
                                <span>RS Adam Malik Medan</span>
                            </div>
                        </div>
                        <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                            Aktif
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-500 text-sm">Total Ruangan</p>
                            <p class="text-2xl font-bold">1</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-500 text-sm">Total Staff</p>
                            <p class="text-2xl font-bold">15</p>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- Ruangan Sections -->
        <div class="space-y-6">
            <!-- Rawat Inap Section -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden animate-fadeIn">
                <div class="p-6 cursor-pointer" onclick="toggleSection('rawat-inap')">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-procedures mr-2 text-blue-600"></i>
                            Ruangan Rawat Inap
                        </h3>
                        <i id="arrow-rawat-inap" class="fas fa-chevron-down text-gray-500 transition-transform duration-200"></i>
                    </div>
                </div>
                
                <div id="rawat-inap" class="px-6 pb-6 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Staff Kasur -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="font-medium text-gray-800">Staff Kasur</h4>
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">10 Orang</span>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Suster A</span>
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Shift Pagi</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Suster B</span>
                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Shift Sore</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Suster C</span>
                                    <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">Shift Malam</span>
                                </div>
                                <button class="text-blue-600 text-sm mt-2 flex items-center">
                                    <i class="fas fa-plus-circle mr-1"></i> Edit
                                </button>
                            </div>
                        </div>
                        
                        <!-- Staff Kebidanan -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="font-medium text-gray-800">Staff Kebidanan</h4>
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">15 Orang</span>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Bidan X</span>
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Shift Pagi</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Bidan Y</span>
                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Shift Sore</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Bidan Z</span>
                                    <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">Shift Malam</span>
                                </div>
                                <button class="text-blue-600 text-sm mt-2 flex items-center">
                                    <i class="fas fa-plus-circle mr-1"></i> Edit
                                </button>
                            </div>
                        </div>
                        
                        <!-- Staff Administrasi -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="font-medium text-gray-800">Staff Administrasi</h4>
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">5 Orang</span>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Admin 1</span>
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Full Time</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Admin 2</span>
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Full Time</span>
                                </div>
                                <button class="text-blue-600 text-sm mt-2 flex items-center">
                                    <i class="fas fa-plus-circle mr-1"></i> Edit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <script>
        // Toggle section visibility
        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            const arrow = document.getElementById('arrow-' + sectionId);
            
            if (section.classList.contains('hidden')) {
                section.classList.remove('hidden');
                arrow.classList.add('rotate-180');
            } else {
                section.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }
        }

        // Modal functions
        function openAddItemModal() {
            document.getElementById('addItemModal').classList.remove('hidden');
            document.getElementById('addItemModal').classList.add('flex');
        }

        function closeAddItemModal() {
            document.getElementById('addItemModal').classList.add('hidden');
            document.getElementById('addItemModal').classList.remove('flex');
        }

        // Close modal when clicking outside
        document.getElementById('addItemModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddItemModal();
            }
        });

        // Add fade-in animation for elements
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.animate-fadeIn');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>

    <style>
        .animate-fadeIn {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.6s ease-out forwards;
        }

        .rotate-180 {
            transform: rotate(180deg);
            transition: transform 0.2s ease;
        }
    </style>
</body>
</html>