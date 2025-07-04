<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Kinerja Staf</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
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
                    <label for="kedisiplinan" class="block text-gray-700 text-xs md:text-sm font-medium mb-1 md:mb-2">Kedisiplinan (1-5)</label>
                    <input type="number" id="kedisiplinan" min="1" max="5" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>
                <div class="mb-3 md:mb-4">
                    <label for="komunikasi" class="block text-gray-700 text-xs md:text-sm font-medium mb-1 md:mb-2">Komunikasi (1-5)</label>
                    <input type="number" id="komunikasi" min="1" max="5" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>
                <div class="mb-3 md:mb-4">
                    <label for="komplain" class="block text-gray-700 text-xs md:text-sm font-medium mb-1 md:mb-2">Komplain (1-5)</label>
                    <input type="number" id="komplain" min="1" max="5" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>
                <div class="mb-3 md:mb-4">
                    <label for="kepatuhan" class="block text-gray-700 text-xs md:text-sm font-medium mb-1 md:mb-2">Kepatuhan (1-5)</label>
                    <input type="number" id="kepatuhan" min="1" max="5" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>
                <div class="mb-4 md:mb-6">
                    <label for="targetKerja" class="block text-gray-700 text-xs md:text-sm font-medium mb-1 md:mb-2">Target Kerja (1-5)</label>
                    <input type="number" id="targetKerja" min="1" max="5" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm" required>
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

    <script src="{{ asset('js/kinerja-staff.js') }}"></script>
</body>
</html>