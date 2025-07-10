<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Kinerja Staf</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <link href="{{ asset('css/kinerja-staff.css') }}" rel="stylesheet"></link>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <style>
        /* Hide scrollbars but keep functionality */
        ::-webkit-scrollbar {
            display: none;
        }
        
        /* Mobile optimizations */
        /* Navbar mobile optimization */
/* Mobile optimization */
@media (max-width: 768px) {
    /* Adjust the main content spacing */
    main {
        padding-top: 1rem !important; /* Reduce top padding */
    }

    /* Header card adjustments */
    .glass-effect {
        margin-top: 0.5rem !important;
        padding: 1rem !important;
    }

    /* Stats cards grid */
    .grid.grid-cols-2 {
        gap: 0.5rem !important;
        margin-bottom: 1rem !important;
    }

    /* Section titles */
    h2.text-xl {
        font-size: 1.1rem !important;
        margin-bottom: 0.75rem !important;
    }

    /* Reduce space between sections */
    .card-hover {
        margin-bottom: 1rem !important;
    }

    /* Table adjustments */
    table {
        font-size: 0.8rem !important;
    }

    /* Navbar fixes */
    .navbar {
        height: 50px !important;
        padding: 0 1rem !important;
    }

    /* Content adjustments to compensate for navbar */
    .mt-12 {
        margin-top: 3rem !important;
    }
}

/* Ensure navbar stays fixed on mobile */
@media (max-width: 768px) {
    .navbar {
        position: fixed !important;
        top: 0;
        left: 0;
        right: 0;
        z-index: 40;
    }
}
        
        /* Global scrollbar hiding */
        html {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        
        /* Animation improvements */
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Card hover effect */
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        /* Navbar date fix - keep date inline */
.navbar-date-container {
    display: flex;
    flex-direction: row !important;
    align-items: center;
    gap: 0.5rem;
}
        /* Button animations */
        .animated-button {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .animated-button:hover {
            transform: translateY(-2px);
        }
        
        .animated-button:active {
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 text-gray-800 overflow-x-hidden">
    <script>
        window.authToken = "{{ session('token') }}";
        window.currentUser = {
            id: {{ Auth::user()->id }},
            department_id: {{ Auth::user()->department_id }},
            hospital_id: {{ Auth::user()->hospital_id }}
        };
    </script>
    @include('components.sidebar-navbar')
    
    <div class="p-4 md:p-0 mt-8">
        <main class="md:pl-60 md:pr-5 flex-1 px-4 md:px-6 py-4 md:py-8 md:mt-0">
            <!-- Header Card -->
            <div class="glass-effect rounded-3xl p-6 md:p-8 mb-6 md:mb-8 shadow-xl animate-fade-in-up">
                <div class="flex items-center justify-between flex-col md:flex-row gap-4 md:gap-0">
                    <div class="text-center md:text-left">
                        <h1 class="text-2xl md:text-4xl font-bold text-black mb-2 md:mb-3">
                            <i class="fas fa-chart-line mr-2 md:mr-3 text-green-500"></i>Kinerja Staf
                        </h1>
                        <p class="text-gray-600 text-sm md:text-lg">Lihat dan catat penilaian kinerja staf Anda berdasarkan indikator yang tersedia.</p>
                    </div>
                    <div class="flex space-x-2 md:space-x-4 w-full md:w-auto">
                        <button id="addPenilaianBtn" class="animated-button bg-white border border-blue-500 text-blue-500 px-4 py-2 md:px-6 md:py-3 rounded-xl md:rounded-2xl font-semibold text-sm md:text-base">
                            <i class="fas fa-plus mr-1 md:mr-2 text-blue-500"></i>Tambah Penilaian
                        </button>
                        <button id="addStaffBtn" class="animated-button bg-white border border-blue-500 text-blue-500 px-4 py-2 md:px-6 md:py-3 rounded-xl md:rounded-2xl font-semibold text-sm md:text-base">
                            <i class="fas fa-plus mr-1 md:mr-2 text-blue-500"></i>Tambah Staff
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6 mb-6 md:mb-8">
                <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 text-gray-700 shadow hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs md:text-sm font-medium text-gray-500">Excellent</p>
                            <p class="text-xl md:text-3xl font-bold text-gray-700" id="excellentPerformanceCount">0</p>
                        </div>
                        <div class="w-8 h-8 md:w-12 md:h-12 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-star text-lg md:text-2xl text-yellow-500"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 text-gray-700 shadow hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs md:text-sm font-medium text-gray-500">Good</p>
                            <p class="text-xl md:text-3xl font-bold text-gray-700" id="goodPerformanceCount">0</p>
                        </div>
                        <div class="w-8 h-8 md:w-12 md:h-12 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-thumbs-up text-lg md:text-2xl text-blue-500"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 text-gray-700 shadow hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs md:text-sm font-medium text-gray-500">Need Mentoring</p>
                            <p class="text-xl md:text-3xl font-bold text-gray-700" id="needMentoringCount">0</p>
                        </div>
                        <div class="w-8 h-8 md:w-12 md:h-12 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-lg md:text-2xl text-yellow-500"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 text-gray-700 shadow hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs md:text-sm font-medium text-gray-500">Need Improvement</p>
                            <p class="text-xl md:text-3xl font-bold text-gray-700" id="needImprovementCount">0</p>
                        </div>
                        <div class="w-8 h-8 md:w-12 md:h-12 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-arrow-up text-lg md:text-2xl text-red-500"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Staff Management Table -->
            <div class="card-hover bg-white rounded-2xl md:rounded-3xl shadow-lg md:shadow-xl p-4 md:p-8 mb-6 md:mb-8 animate-fade-in-up">
                <div class="flex items-center justify-between mb-4 md:mb-6 flex-col md:flex-row gap-3 md:gap-0">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">
                        <i class="fas fa-user-tie mr-2 md:mr-3 text-purple-500"></i>Manajemen Staff
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm" style="min-width: 800px">
                        <thead>
                            <tr class="bg-gray-100 text-black">
                                <th class="px-4 py-2 md:px-6 md:py-4 text-left font-semibold text-xs md:text-sm">No</th>
                                <th class="px-4 py-2 md:px-6 md:py-4 text-left font-semibold text-xs md:text-sm">Nama</th>
                                <th class="px-4 py-2 md:px-6 md:py-4 text-left font-semibold text-xs md:text-sm">Jabatan</th>
                                <th class="px-4 py-2 md:px-6 md:py-4 text-left font-semibold text-xs md:text-sm">Ruangan</th>
                                <th class="px-4 py-2 md:px-6 md:py-4 text-left font-semibold text-xs md:text-sm">Status</th>
                                <th class="px-4 py-2 md:px-6 md:py-4 text-left font-semibold text-xs md:text-sm">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="staffManagementTableBody" class="divide-y divide-gray-100">
                            <!-- Content will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Performance Evaluation Table -->
            <div class="card-hover bg-white rounded-2xl md:rounded-3xl shadow-lg md:shadow-xl p-4 md:p-8 animate-fade-in-up">
                <div class="flex items-center justify-between mb-4 md:mb-6 flex-col md:flex-row gap-3 md:gap-0">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">
                        <i class="fas fa-users mr-2 md:mr-3 text-blue-500"></i>Rekapitulasi Penilaian
                    </h2>
                    <div class="flex items-center space-y-2 md:space-y-0 md:space-x-4 w-full md:w-auto flex-col md:flex-row">
                        <div class="relative w-full md:w-auto">
                            <input type="text" id="rekaptitulasiSearchInput" placeholder="Cari staff..." class="pl-8 pr-3 py-2 md:pl-10 md:pr-4 md:py-2 border border-gray-200 rounded-lg md:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm w-full">
                            <i class="fas fa-search absolute left-2 top-2.5 md:left-3 md:top-3 text-blue-500 text-sm"></i>
                        </div>
                        <select id="rekaptitulasiFilterSelect" class="px-3 py-2 md:px-4 md:py-2 border border-gray-200 rounded-lg md:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm w-full">
                            <option>Semua Status</option>
                            <option>Excellent Performance</option>
                            <option>Good Performance</option>
                            <option>Need Mentoring</option>
                            <option>Need Improvement</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm bg-white text-black rounded-lg">
                        <thead>
                            <tr class="bg-gray-100 text-black">
                                <th class="px-4 py-2 md:px-6 md:py-4 text-left font-semibold text-xs md:text-sm">Nama</th>
                                <th class="px-4 py-2 md:px-6 md:py-4 text-left font-semibold text-xs md:text-sm">Kedisiplinan</th>
                                <th class="px-4 py-2 md:px-6 md:py-4 text-left font-semibold text-xs md:text-sm">Komunikasi</th>
                                <th class="px-4 py-2 md:px-6 md:py-4 text-left font-semibold text-xs md:text-sm">Komplain</th>
                                <th class="px-4 py-2 md:px-6 md:py-4 text-left font-semibold text-xs md:text-sm">Kepatuhan</th>
                                <th class="px-4 py-2 md:px-6 md:py-4 text-left font-semibold text-xs md:text-sm">Target Kerja</th>
                                <th class="px-4 py-2 md:px-6 md:py-4 text-left font-semibold text-xs md:text-sm">Status</th>
                                <th class="px-4 py-2 md:px-6 md:py-4 text-left font-semibold text-xs md:text-sm">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="performanceEvaluationTableBody" class="divide-y divide-gray-100">
                            <!-- Content will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Performance Evaluation Modal -->
    <div id="performanceEvaluationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg p-4 md:p-6 w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-3 md:mb-4">
                <h3 id="performanceEvaluationModalTitle" class="text-lg md:text-xl font-bold text-gray-800">Tambah Penilaian Staf Baru</h3>
                <button type="button" onclick="closePerformanceEvaluationModal()" class="text-gray-500 hover:text-gray-700 text-lg">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="performanceEvaluationForm">
                <input type="hidden" id="evaluationId">
                <div class="mb-3 md:mb-4">
                    <label for="staffSelect" class="block text-gray-700 text-xs md:text-sm font-medium mb-1 md:mb-2">Pilih Staff</label>
                    <select id="staffSelect" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                        <option value="">Pilih Staff</option>
                    </select>
                </div>
                <div class="mb-3 md:mb-4">
                    <label for="kedisiplinan" class="block text-gray-700 text-xs md:text-sm font-medium mb-1 md:mb-2">Kedisiplinan (10-100)</label>
                    <input type="number" id="kedisiplinan" min="10" max="100" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>
                <div class="mb-3 md:mb-4">
                    <label for="komunikasi" class="block text-gray-700 text-xs md:text-sm font-medium mb-1 md:mb-2">Komunikasi (10-100)</label>
                    <input type="number" id="komunikasi" min="10" max="100" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>
                <div class="mb-3 md:mb-4">
                    <label for="komplain" class="block text-gray-700 text-xs md:text-sm font-medium mb-1 md:mb-2">Komplain (10-100)</label>
                    <input type="number" id="komplain" min="10" max="100" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>
                <div class="mb-3 md:mb-4">
                    <label for="kepatuhan" class="block text-gray-700 text-xs md:text-sm font-medium mb-1 md:mb-2">Kepatuhan (10-100)</label>
                    <input type="number" id="kepatuhan" min="10" max="100" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>
                <div class="mb-4 md:mb-6">
                    <label for="targetKerja" class="block text-gray-700 text-xs md:text-sm font-medium mb-1 md:mb-2">Target Kerja (10-100)</label>
                    <input type="number" id="targetKerja" min="10" max="100" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>
                <div class="mb-4 md:mb-6">
                    <label for="notes" class="block text-gray-700 text-xs md:text-sm font-medium mb-1 md:mb-2">Catatan (Opsional)</label>
                    <textarea id="notes" rows="3" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm"></textarea>
                </div>
                <div class="flex justify-end space-x-2 md:space-x-3">
                    <button type="button" onclick="closePerformanceEvaluationModal()" class="animated-button bg-gray-200 text-gray-800 px-4 py-2 md:px-6 md:py-3 rounded-lg md:rounded-xl font-semibold hover:bg-gray-300 transition duration-300 text-xs md:text-sm">Batal</button>
                    <button type="submit" class="animated-button bg-blue-600 text-white px-4 py-2 md:px-6 md:py-3 rounded-lg md:rounded-xl font-semibold hover:bg-blue-700 transition duration-300 text-xs md:text-sm">Simpan</button>
                    <button type="button" id="deleteEvaluationBtn" onclick="deletePerformanceEvaluation()" class="animated-button bg-red-600 text-white px-4 py-2 md:px-6 md:py-3 rounded-lg md:rounded-xl font-semibold hover:bg-red-700 transition duration-300 text-xs md:text-sm hidden">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Performance Detail Modal -->
    <div id="performanceDetailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg p-4 md:p-6 w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-3 md:mb-4">
                <h3 class="text-lg md:text-xl font-bold text-gray-800"><i class="fas fa-info-circle mr-2 text-blue-500"></i>Detail Penilaian Staf</h3>
                <button type="button" onclick="closePerformanceDetailModal()" class="text-gray-500 hover:text-gray-700 text-lg">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="grid grid-cols-2 gap-y-2 md:gap-y-3 gap-x-3 md:gap-x-4 text-gray-700 text-xs md:text-sm mb-4 md:mb-6">
                <div class="col-span-2">
                    <p class="font-semibold">Nama Staff:</p>
                    <p id="detailStaffName" class="text-sm md:text-base font-medium"></p>
                </div>
                <div>
                    <p class="font-semibold">Jabatan:</p>
                    <p id="detailPosition"></p>
                </div>
                <div>
                    <p class="font-semibold">Ruangan:</p>
                    <p id="detailDepartment"></p>
                </div>
                <div>
                    <p class="font-semibold">Kedisiplinan:</p>
                    <p id="detailKedisiplinan"></p>
                </div>
                <div>
                    <p class="font-semibold">Komunikasi:</p>
                    <p id="detailKomunikasi"></p>
                </div>
                <div>
                    <p class="font-semibold">Komplain:</p>
                    <p id="detailKomplain"></p>
                </div>
                <div>
                    <p class="font-semibold">Kepatuhan:</p>
                    <p id="detailKepatuhan"></p>
                </div>
                <div>
                    <p class="font-semibold">Target Kerja:</p>
                    <p id="detailTargetKerja"></p>
                </div>
                <div class="col-span-2">
                    <p class="font-semibold">Status Kinerja:</p>
                    <p id="detailStatusKinerja" class="text-sm md:text-base font-bold"></p>
                </div>
                <div class="col-span-2">
                    <p class="font-semibold">Catatan:</p>
                    <p id="detailNotes" class="bg-gray-50 p-2 md:p-3 rounded-lg border border-gray-200 text-xs md:text-sm"></p>
                </div>
                <div>
                    <p class="font-semibold">Dibuat pada:</p>
                    <p id="detailCreatedAt"></p>
                </div>
                <div>
                    <p class="font-semibold">Terakhir Diperbarui:</p>
                    <p id="detailUpdatedAt"></p>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closePerformanceDetailModal()" class="animated-button bg-blue-600 text-white px-4 py-2 md:px-6 md:py-3 rounded-lg md:rounded-xl font-semibold hover:bg-blue-700 transition duration-300 text-xs md:text-sm">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Staff Management Modal -->
    <div id="staffManagementModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg p-4 md:p-5 w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-3">
                <h3 id="staffManagementModalTitle" class="text-base md:text-lg font-bold">Tambah Staff Baru</h3>
                <button onclick="closeStaffManagementModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="staffManagementForm">
                <input type="hidden" id="staffManagementId">
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                <input type="hidden" name="department_id" value="{{ Auth::user()->department_id }}">
                <input type="hidden" name="hospital_id" value="{{ Auth::user()->hospital_id }}">
                <div class="mb-3">
                    <label class="block text-gray-700 text-xs md:text-sm mb-1" for="staffManagementFullName">Nama Lengkap</label>
                    <input type="text" id="staffManagementFullName" class="w-full px-3 py-2 border rounded-lg text-xs md:text-sm" required>
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 text-xs md:text-sm mb-1" for="staffManagementPosition">Jabatan</label>
                    <select id="staffManagementPosition" class="w-full px-3 py-2 border rounded-lg text-xs md:text-sm" required>
                        <option value="">Pilih Jabatan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 text-xs md:text-sm mb-1" for="staffManagementStatus">Status</label>
                    <select id="staffManagementStatus" class="w-full px-3 py-2 border rounded-lg text-xs md:text-sm" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                        <option value="Cuti">Cuti</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeStaffManagementModal()" class="px-3 py-1 rounded-lg text-xs md:text-sm text-gray-700 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-3 py-1 rounded-lg text-xs md:text-sm text-white bg-green-500 hover:bg-green-600">Simpan</button>
                    <button type="button" id="deleteStaffManagementBtn" onclick="deleteStaffManagement()" class="px-3 py-1 rounded-lg text-xs md:text-sm text-white bg-red-500 hover:bg-red-600 hidden">Hapus</button>
                </div>
            </form>
        </div>
    </div>
    <div id="global-loading-overlay" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-[9999] hidden">
        <div class="flex flex-col items-center space-y-4">
            <div class="relative w-20 h-20">
                <div class="absolute inset-0 border-4 border-blue-400 border-t-blue-600 rounded-full animate-spin"></div>
                <div class="absolute inset-2 border-4 border-white border-t-white rounded-full animate-spin-reverse" style="animation-duration: 1.5s;"></div>
                <div class="absolute inset-4 border-4 border-purple-400 border-t-purple-600 rounded-full animate-spin" style="animation-duration: 2s;"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="fas fa-sync-alt text-white text-3xl animate-pulse"></i>
                </div>
            </div>
            <p class="text-white text-lg font-semibold animate-pulse">Loading Data...</p>
        </div>
    </div>

    <script>
        // Global variables
let staffMembers = [];
let performanceEvaluations = [];
let positions = [];
let departments = []; // Ensure departments is declared globally

// --- Global Loading Functions (for HCI principle) ---
// (These are global so they can be called from anywhere in your scripts)
function showLoading() {
    const overlay = document.getElementById('global-loading-overlay');
    if (overlay) {
        overlay.classList.remove('hidden');
    }
}

function hideLoading() {
    const overlay = document.getElementById('global-loading-overlay');
    if (overlay) {
        overlay.classList.add('hidden');
    }
}
// --- End Global Loading Functions ---


// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    loadInitialKinerjaStaffData();
    setupKinerjaStaffEventListeners();
});

// Load initial data for Kinerja Staff page
async function loadInitialKinerjaStaffData() {
    showLoading(); // Show loading for initial data fetch
    try {
        const token = window.authToken;
        if (!token) {
            console.error('Bearer token missing');
            return;
        }

        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        };

        // Fetch staff, positions, departments, and performance evaluations concurrently
        const [staffResponse, positionsResponse, departmentsResponse, evaluationsResponse] = await Promise.all([
            fetch('/api/v1/staff', { headers }),
            fetch('/api/v1/positions', { headers }),
            fetch('/api/v1/departments', { headers }), // Fetch departments
            fetch('/api/v1/performance-evaluations', { headers })
        ]);

        staffMembers = await staffResponse.json();
        positions = await positionsResponse.json();
        departments = await departmentsResponse.json(); // Store departments
        performanceEvaluations = await evaluationsResponse.json();

        console.log('Staff Members:', staffMembers);
        console.log('Positions:', positions);
        console.log('Departments:', departments);
        console.log('Performance Evaluations:', performanceEvaluations);

        renderStaffManagementTable();
        renderPerformanceEvaluationTable();
        updateStaffDropdownForEvaluation();
        updatePositionDropdownForStaffManagement(); // Call this to populate dropdowns in modals
        updateKinerjaStatistics();
    } catch (error) {
        console.error('Error loading initial data for Kinerja Staf:', error);
        alert('Gagal memuat data. Silakan coba lagi.');
    } finally {
        hideLoading(); // Always hide loading
    }
}

// Setup Event Listeners for Kinerja Staff page
function setupKinerjaStaffEventListeners() {
    // Add Penilaian button
    document.getElementById('addPenilaianBtn').addEventListener('click', openAddPerformanceEvaluationModal);
    // Add Staff button
    document.getElementById('addStaffBtn').addEventListener('click', openAddStaffModal);

    // Modals
    document.getElementById('performanceEvaluationModal').addEventListener('click', function(e) {
        if (e.target === this) closePerformanceEvaluationModal();
    });
    document.getElementById('staffManagementModal').addEventListener('click', function(e) {
        if (e.target === this) closeStaffManagementModal();
    });
    document.getElementById('performanceDetailModal').addEventListener('click', function(e) {
        if (e.target === this) closePerformanceDetailModal();
    });

    // Form Submissions
    document.getElementById('performanceEvaluationForm').addEventListener('submit', handlePerformanceEvaluationFormSubmit);
    document.getElementById('staffManagementForm').addEventListener('submit', handleStaffManagementFormSubmit);

    // Search and Filter for Rekapitulasi Penilaian Staf
    const rekaptitulasiSearchInput = document.getElementById('rekaptitulasiSearchInput');
    const rekaptitulasiFilterSelect = document.getElementById('rekaptitulasiFilterSelect');

    if (rekaptitulasiSearchInput) rekaptitulasiSearchInput.addEventListener('input', filterPerformanceEvaluations);
    if (rekaptitulasiFilterSelect) rekaptitulasiFilterSelect.addEventListener('change', filterPerformanceEvaluations);
}

// --- Modals for Kinerja Staf Page ---

// Performance Evaluation Modals
function openAddPerformanceEvaluationModal() {
    document.getElementById('performanceEvaluationModalTitle').textContent = 'Tambah Penilaian Staf Baru';
    document.getElementById('evaluationId').value = '';
    document.getElementById('staffSelect').value = '';
    document.getElementById('kedisiplinan').value = '';
    document.getElementById('komunikasi').value = '';
    document.getElementById('komplain').value = '';
    document.getElementById('kepatuhan').value = '';
    document.getElementById('targetKerja').value = '';
    document.getElementById('notes').value = '';
    document.getElementById('deleteEvaluationBtn').classList.add('hidden');
    document.getElementById('performanceEvaluationModal').classList.remove('hidden');
    document.getElementById('performanceEvaluationModal').classList.add('flex');
    updateStaffDropdownForEvaluation(); // Ensure dropdown is populated when adding
}

function openEditPerformanceEvaluationModal(evaluationId) {
    const evaluation = performanceEvaluations.find(e => e.id == evaluationId);
    if (!evaluation) return;

    document.getElementById('performanceEvaluationModalTitle').textContent = 'Edit Penilaian Staf';
    document.getElementById('evaluationId').value = evaluation.id;
    document.getElementById('staffSelect').value = evaluation.staff_id;
    document.getElementById('kedisiplinan').value = evaluation.kedisiplinan;
    document.getElementById('komunikasi').value = evaluation.komunikasi;
    document.getElementById('komplain').value = evaluation.komplain;
    document.getElementById('kepatuhan').value = evaluation.kepatuhan;
    document.getElementById('targetKerja').value = evaluation.target_kerja;
    document.getElementById('notes').value = evaluation.notes;
    document.getElementById('deleteEvaluationBtn').classList.remove('hidden');
    document.getElementById('performanceEvaluationModal').classList.remove('hidden');
    document.getElementById('performanceEvaluationModal').classList.add('flex');
    updateStaffDropdownForEvaluation(); // Ensure dropdown is populated when editing
}

function openDetailPerformanceEvaluationModal(evaluationId) {
    const evaluation = performanceEvaluations.find(e => e.id == evaluationId);
    if (!evaluation) return;

    const staffName = evaluation.staff ? evaluation.staff.name : 'N/A';
    const positionName = evaluation.staff && evaluation.staff.position ? evaluation.staff.position.name : 'N/A';
    const departmentName = evaluation.staff && evaluation.staff.department ? evaluation.staff.department.name : 'N/A';

    document.getElementById('detailStaffName').textContent = staffName;
    document.getElementById('detailPosition').textContent = positionName;
    document.getElementById('detailDepartment').textContent = departmentName;
    document.getElementById('detailKedisiplinan').textContent = evaluation.kedisiplinan;
    document.getElementById('detailKomunikasi').textContent = evaluation.komunikasi;
    document.getElementById('detailKomplain').textContent = evaluation.komplain;
    document.getElementById('detailKepatuhan').textContent = evaluation.kepatuhan;
    document.getElementById('detailTargetKerja').textContent = evaluation.target_kerja;
    document.getElementById('detailStatusKinerja').textContent = evaluation.status_kinerja;
    document.getElementById('detailNotes').textContent = evaluation.notes || 'Tidak ada catatan.';
    document.getElementById('detailCreatedAt').textContent = new Date(evaluation.created_at).toLocaleString('id-ID');
    document.getElementById('detailUpdatedAt').textContent = new Date(evaluation.updated_at).toLocaleString('id-ID');

    document.getElementById('performanceDetailModal').classList.remove('hidden');
    document.getElementById('performanceDetailModal').classList.add('flex');
}


function closePerformanceEvaluationModal() {
    document.getElementById('performanceEvaluationModal').classList.add('hidden');
    document.getElementById('performanceEvaluationModal').classList.remove('flex');
}

function closePerformanceDetailModal() {
    document.getElementById('performanceDetailModal').classList.add('hidden');
    document.getElementById('performanceDetailModal').classList.remove('flex');
}

// Staff Management Modals (similar to dinas.js but for this page's context)
function openAddStaffModal() {
    document.getElementById('staffManagementModalTitle').textContent = 'Tambah Staff Baru';
    document.getElementById('staffManagementId').value = '';
    document.getElementById('staffManagementFullName').value = '';
    document.getElementById('staffManagementPosition').value = '';
    document.getElementById('staffManagementStatus').value = 'Aktif';
    document.getElementById('deleteStaffManagementBtn').classList.add('hidden'); // Hide delete for add
    document.getElementById('staffManagementModal').classList.remove('hidden');
    document.getElementById('staffManagementModal').classList.add('flex');
    updatePositionDropdownForStaffManagement(); // Populate position dropdown
}

function openEditStaffModal(staffId) {
    const staff = staffMembers.find(s => s.id == staffId);
    if (!staff) return;

    document.getElementById('staffManagementModalTitle').textContent = 'Edit Staff';
    document.getElementById('staffManagementId').value = staff.id;
    document.getElementById('staffManagementFullName').value = staff.name;
    document.getElementById('staffManagementPosition').value = staff.position_id;
    document.getElementById('staffManagementStatus').value = staff.status;
    document.getElementById('deleteStaffManagementBtn').classList.remove('hidden'); // Show delete for edit
    document.getElementById('staffManagementModal').classList.remove('hidden');
    document.getElementById('staffManagementModal').classList.add('flex');
    updatePositionDropdownForStaffManagement(); // Populate position dropdown
}

function closeStaffManagementModal() {
    document.getElementById('staffManagementModal').classList.add('hidden');
    document.getElementById('staffManagementModal').classList.remove('flex');
}

// --- Form Handlers ---

async function handlePerformanceEvaluationFormSubmit(e) {
    e.preventDefault();
    showLoading(); // Show loading
    const formData = {
        id: document.getElementById('evaluationId').value,
        staff_id: document.getElementById('staffSelect').value,
        kedisiplinan: parseInt(document.getElementById('kedisiplinan').value),
        komunikasi: parseInt(document.getElementById('komunikasi').value),
        komplain: parseInt(document.getElementById('komplain').value),
        kepatuhan: parseInt(document.getElementById('kepatuhan').value),
        target_kerja: parseInt(document.getElementById('targetKerja').value),
        notes: document.getElementById('notes').value,
        // Add user, department, hospital IDs from global window.currentUser for new entries
        user_id: window.currentUser.id,
        department_id: window.currentUser.department_id,
        hospital_id: window.currentUser.hospital_id
    };

    try {
        const token = window.authToken;
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        };

        const url = formData.id ? `/api/v1/performance-evaluations/${formData.id}` : '/api/v1/performance-evaluations';
        const method = formData.id ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method: method,
            headers,
            body: JSON.stringify(formData)
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Network response was not ok');
        }

        await loadInitialKinerjaStaffData(); // Refresh data
        closePerformanceEvaluationModal();
        alert('Penilaian berhasil disimpan!');
    } catch (error) {
        console.error('Error saving performance evaluation:', error);
        alert('Gagal menyimpan penilaian: ' + error.message);
    } finally {
        hideLoading(); // Hide loading
    }
}

async function handleStaffManagementFormSubmit(e) {
    e.preventDefault();
    showLoading(); // Show loading

    // Read values from hidden inputs by their 'name' attribute for consistency
    // window.currentUser already provides these for a cleaner approach
    const userId = window.currentUser.id;
    const departmentId = window.currentUser.department_id;
    const hospitalId = window.currentUser.hospital_id;

    const formData = {
        id: document.getElementById('staffManagementId').value,
        name: document.getElementById('staffManagementFullName').value,
        position_id: document.getElementById('staffManagementPosition').value,
        user_id: userId,
        department_id: departmentId,
        hospital_id: hospitalId,
        status: document.getElementById('staffManagementStatus').value
    };

    try {
        const token = window.authToken;
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        };

        const url = formData.id ? `/api/v1/staff/${formData.id}` : '/api/v1/staff';
        const method = formData.id ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method: method,
            headers,
            body: JSON.stringify(formData)
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Network response was not ok');
        }

        await loadInitialKinerjaStaffData(); // Refresh data for both tables
        closeStaffManagementModal();
        alert('Data staff berhasil disimpan!');
    } catch (error) {
        console.error('Error saving staff:', error);
        alert('Gagal menyimpan data staff: ' + error.message);
    } finally {
        hideLoading(); // Hide loading
    }
}

// --- Delete Functions ---

window.deletePerformanceEvaluation = async function() {
    const evaluationId = document.getElementById('evaluationId').value;
    if (!evaluationId || !confirm('Apakah Anda yakin ingin menghapus penilaian ini?')) return;
    
    showLoading(); // Show loading
    try {
        const token = window.authToken;
        const headers = {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        };

        const response = await fetch(`/api/v1/performance-evaluations/${evaluationId}`, {
            method: 'DELETE',
            headers
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Network response was not ok');
        }

        await loadInitialKinerjaStaffData(); // Refresh data
        closePerformanceEvaluationModal();
        alert('Penilaian berhasil dihapus!');
    } catch (error) {
        console.error('Error deleting performance evaluation:', error);
        alert('Gagal menghapus penilaian: ' + error.message);
    } finally {
        hideLoading(); // Hide loading
    }
}

window.deleteStaffManagement = async function() {
    const staffId = document.getElementById('staffManagementId').value;
    if (!staffId || !confirm('Apakah Anda yakin ingin menghapus staff ini? Semua penilaian terkait juga akan terhapus.')) return;
    
    showLoading(); // Show loading
    try {
        const token = window.authToken;
        const headers = {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        };

        const response = await fetch(`/api/v1/staff/${staffId}`, {
            method: 'DELETE',
            headers
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Network response was not ok');
        }

        await loadInitialKinerjaStaffData(); // Refresh data
        closeStaffManagementModal();
        alert('Data staff berhasil dihapus!');
    } catch (error) {
        console.error('Error deleting staff:', error);
        alert('Gagal menghapus staff: ' + error.message);
    } finally {
        hideLoading(); // Hide loading
    }
}

// --- Render Functions ---

function renderStaffManagementTable() {
    const tbody = document.getElementById('staffManagementTableBody');
    if (!tbody) {
        console.error('Staff management table body element not found!');
        return;
    }

    tbody.innerHTML = ''; // Clear existing rows

    if (!staffMembers || staffMembers.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = `<td colspan="6" class="text-center py-4 text-gray-500">Tidak ada data staff.</td>`;
        tbody.appendChild(row);
        return;
    }

    staffMembers.forEach((staff, index) => {
        const department = departments.find(d => d.id === staff.department_id) || {};
        const position = positions.find(p => p.id === staff.position_id) || {};
        
        const row = document.createElement('tr');
        row.classList.add('table-row');
        row.innerHTML = `
            <td class="px-6 py-4">${index + 1}</td>
            <td class="px-6 py-4">${staff.name || '-'}</td>
            <td class="px-6 py-4">${position.name || '-'}</td>
            <td class="px-6 py-4">${department.name || '-'}</td>
            <td class="px-6 py-4">
                <span class="px-2 py-1 rounded-full text-xs font-medium ${
                    staff.status === 'Aktif' ? 'bg-green-100 text-green-800' : 
                    staff.status === 'Cuti' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'
                }">
                    ${staff.status || '-'}
                </span>
            </td>
            <td class="px-6 py-4">
                <div class="flex space-x-2">
                    <button onclick="openEditStaffModal(${staff.id})" class="animated-button bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-lg text-xs font-semibold">
                        <i class="fas fa-pen mr-1 text-blue-500"></i>Edit
                    </button>
                    <button onclick="deleteStaffManagementById(${staff.id})" class="animated-button bg-white border border-red-500 text-red-500 px-4 py-2 rounded-lg text-xs font-semibold">
                        <i class="fas fa-trash mr-1 text-red-500"></i>Hapus
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}


function renderPerformanceEvaluationTable() {
    const tbody = document.getElementById('performanceEvaluationTableBody');
    if (!tbody) {
        console.error('Performance evaluation table body element not found!');
        return;
    }

    tbody.innerHTML = ''; // Clear existing rows

    const searchTerm = document.getElementById('rekaptitulasiSearchInput').value.toLowerCase();
    const filterStatus = document.getElementById('rekaptitulasiFilterSelect').value;


    const filteredEvaluations = performanceEvaluations.filter(evaluation => {
        const matchesSearch = evaluation.staff && evaluation.staff.name.toLowerCase().includes(searchTerm);
        const matchesStatus = filterStatus === 'Semua Status' || evaluation.status_kinerja === filterStatus;
        return matchesSearch && matchesStatus;
    });

    if (!filteredEvaluations || filteredEvaluations.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = `<td colspan="8" class="text-center py-4 text-gray-500">Tidak ada data penilaian.</td>`;
        tbody.appendChild(row);
        return;
    }

    filteredEvaluations.forEach(evaluation => {
        const staff = evaluation.staff; // Staff object is eager loaded

        const row = document.createElement('tr');
        row.classList.add('table-row');
        row.innerHTML = `
            <td class="px-6 py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">${staff ? staff.name.charAt(0).toUpperCase() : 'N/A'}</div>
                    <div>
                        <p class="font-semibold text-black">${staff ? staff.name : 'N/A'}</p>
                        <p class="text-xs text-gray-500">Staff ID: ${staff ? staff.id : 'N/A'}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <span class="status-indicator" style="background:${getRatingColor(evaluation.kedisiplinan)}"></span>
                    <span class="${getRatingTextColor(evaluation.kedisiplinan)} font-medium">${getRatingDescription(evaluation.kedisiplinan)}</span>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <span class="status-indicator" style="background:${getRatingColor(evaluation.komunikasi)}"></span>
                    <span class="${getRatingTextColor(evaluation.komunikasi)} font-medium">${getRatingDescription(evaluation.komunikasi)}</span>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <span class="status-indicator" style="background:${getRatingColor(evaluation.komplain)}"></span>
                    <span class="${getRatingTextColor(evaluation.komplain)} font-medium">${getRatingDescription(evaluation.komplain)}</span>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <span class="status-indicator" style="background:${getRatingColor(evaluation.kepatuhan)}"></span>
                    <span class="${getRatingTextColor(evaluation.kepatuhan)} font-medium">${getRatingDescription(evaluation.kepatuhan)}</span>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <span class="status-indicator" style="background:${getRatingColor(evaluation.target_kerja)}"></span>
                    <span class="${getRatingTextColor(evaluation.target_kerja)} font-medium">${getRatingDescription(evaluation.target_kerja)}</span>
                </div>
            </td>
            <td class="px-6 py-4">
                <span class="performance-badge" style="background:${getPerformanceBadgeColor(evaluation.status_kinerja)}">
                    ${evaluation.status_kinerja || 'N/A'}
                </span>
            </td>
            <td class="px-6 py-4">
                <div class="flex space-x-2">
                    <button onclick="openEditPerformanceEvaluationModal(${evaluation.id})" class="animated-button bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-lg text-xs font-semibold">
                        <i class="fas fa-pen mr-1 text-blue-500"></i>Edit
                    </button>
                    <button onclick="openDetailPerformanceEvaluationModal(${evaluation.id})" class="animated-button bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-lg text-xs font-semibold">
                        <i class="fas fa-eye mr-1 text-blue-500"></i>Detail
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function updateStaffDropdownForEvaluation() {
    const staffSelect = document.getElementById('staffSelect');
    if (!staffSelect) return;

    staffSelect.innerHTML = '<option value="">Pilih Staff</option>';
    staffMembers.forEach(staff => {
        const option = document.createElement('option');
        option.value = staff.id;
        option.textContent = staff.name;
        staffSelect.appendChild(option);
    });
}

function updatePositionDropdownForStaffManagement() {
    const posSelect = document.getElementById('staffManagementPosition');
    if (!posSelect) {
        console.error('Staff management position dropdown element not found!');
        return;
    }

    posSelect.innerHTML = '<option value="">Pilih Jabatan</option>';

    if (!positions || !Array.isArray(positions)) {
        console.error('Positions data is invalid:', positions);
        return;
    }

    positions.forEach(pos => {
        if (!pos.id || !pos.name) {
            console.warn('Invalid position data:', pos);
            return;
        }
        const option = document.createElement('option');
        option.value = pos.id;
        option.textContent = pos.name;
        posSelect.appendChild(option);
    });
}

function getPerformanceBadgeColor(status) {
    switch (status) {
        case 'Excellent Performance': return '#10b981'; // Green
        case 'Good Performance': return '#3b82f6';    // Blue
        case 'Need Mentoring': return '#f59e0b';    // Yellow/Orange
        case 'Need Improvement': return '#ef4444';  // Red
        default: return '#6b7280'; // Gray
    }
}

// Helper functions for rating colors and descriptions for Rekapitulasi Penilaian Staff table
function getRatingColor(rating) {
    if (rating >= 86) return '#10b981'; // Green for high (Excellent/Good)
    if (rating >= 76) return '#3b82f6'; // Blue for medium (Good/Fair)
    if (rating >= 61) return '#f59e0b'; // Orange for low-medium (Needs Mentoring)
    return '#ef4444'; // Red for low (Needs Improvement)
}

function getRatingTextColor(rating) {
    if (rating >= 86) return 'text-green-700';
    if (rating >= 76) return 'text-blue-600';
    if (rating >= 61) return 'text-yellow-600';
    return 'text-red-600';
}

function getRatingDescription(rating) {
    
 if (rating >= 86) return 'Sangat Baik';
    if (rating >= 76) return 'Baik';
    if (rating >= 61) return 'Cukup';
    return 'Kurang';
}


function updateKinerjaStatistics() {
    let excellentCount = 0;
    let goodCount = 0;
    let mentoringCount = 0;
    let improvementCount = 0;

    performanceEvaluations.forEach(evaluation => {
        switch (evaluation.status_kinerja) {
            case 'Excellent Performance':
                excellentCount++;
                break;
            case 'Good Performance':
                goodCount++;
                break;
            case 'Need Mentoring':
                mentoringCount++;
                break;
            case 'Need Improvement':
                improvementCount++;
                break;
        }
    });

    document.getElementById('excellentPerformanceCount').textContent = excellentCount;
    document.getElementById('goodPerformanceCount').textContent = goodCount;
    document.getElementById('needMentoringCount').textContent = mentoringCount;
    document.getElementById('needImprovementCount').textContent = improvementCount;
}

function filterPerformanceEvaluations() {
    renderPerformanceEvaluationTable(); // Re-render table with current filters
}

// Helper function to call deleteStaffManagement with a specific ID
// This is used by the inline onclick in the staff management table
window.deleteStaffManagementById = function(staffId) {
    // Set the staffId in the hidden input of the staff modal temporarily
    // This mimics opening the edit modal and then hitting delete, but directly handles deletion.
    document.getElementById('staffManagementId').value = staffId;
    deleteStaffManagement(); // Call the main delete function
}

// Make functions globally accessible if needed by inline HTML event handlers
window.openAddPerformanceEvaluationModal = openAddPerformanceEvaluationModal;
window.openEditPerformanceEvaluationModal = openEditPerformanceEvaluationModal;
window.openDetailPerformanceEvaluationModal = openDetailPerformanceEvaluationModal;
window.closePerformanceEvaluationModal = closePerformanceEvaluationModal;
window.closePerformanceDetailModal = closePerformanceDetailModal;
window.openAddStaffModal = openAddStaffModal;
window.openEditStaffModal = openEditStaffModal;
window.closeStaffManagementModal = closeStaffManagementModal;
window.deletePerformanceEvaluation = deletePerformanceEvaluation;
// deleteStaffManagementById is already global via window.deleteStaffManagementById
    </script>
</body>
</html>