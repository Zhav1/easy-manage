<!DOCTYPE html>
<html lang="en" class="h-full bg-white w-screen">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Kinerja Staf</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('css/kinerja-staff.css') }}" rel="stylesheet"></link>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</head>
<body class="bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 text-gray-800">
    <script>
        window.authToken = "{{ session('token') }}";
        // Pass user data to JS, ensure it's secure
        window.currentUser = {
            id: {{ Auth::user()->id }},
            department_id: {{ Auth::user()->department_id }},
            hospital_id: {{ Auth::user()->hospital_id }}
        };
    </script>
    @include('components.sidebar-navbar')
    <div class="p-4">
        <main class="pl-60 pr-5 flex-1 px-6 py-8 mt-8">
            <div class="glass-effect rounded-3xl p-8 mb-8 shadow-xl animate-fade-in-up">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-black mb-3">
                            <i class="fas fa-chart-line mr-3 text-green-500"></i>Kinerja Staf
                        </h1>
                        <p class="text-gray-600 text-lg">Lihat dan catat penilaian kinerja staf Anda berdasarkan indikator yang tersedia.</p>
                    </div>
                    <div class="flex space-x-4">
                        <button id="addPenilaianBtn" class="animated-button bg-white border border-blue-500 text-blue-500 px-6 py-3 rounded-2xl font-semibold">
                            <i class="fas fa-plus mr-2 text-blue-500"></i>Tambah Penilaian
                        </button>
                        <button id="addStaffBtn" class="animated-button bg-white border border-blue-500 text-blue-500 px-6 py-3 rounded-2xl font-semibold">
                            <i class="fas fa-plus mr-2 text-blue-500"></i>Tambah Staff
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl p-6 text-gray-700 shadow-lg hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Excellent Performance</p>
                            <p class="text-3xl font-bold text-gray-700" id="excellentPerformanceCount">0</p>
                        </div>
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-star text-2xl text-yellow-500"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 text-gray-700 shadow-lg hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Good Performance</p>
                            <p class="text-3xl font-bold text-gray-700" id="goodPerformanceCount">0</p>
                        </div>
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-thumbs-up text-2xl text-blue-500"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 text-gray-700 shadow-lg hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Need Mentoring</p>
                            <p class="text-3xl font-bold text-gray-700" id="needMentoringCount">0</p>
                        </div>
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-2xl text-yellow-500"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 text-gray-700 shadow-lg hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Need Improvement</p>
                            <p class="text-3xl font-bold text-gray-700" id="needImprovementCount">0</p>
                        </div>
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-arrow-up text-2xl text-red-500"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-hover bg-white rounded-3xl shadow-xl p-8 mb-8 animate-fade-in-up">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-user-tie mr-3 text-purple-500"></i>Manajemen Staff
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm bg-white text-black rounded-xl">
                        <thead>
                            <tr class="bg-gray-100 text-black">
                                <th class="px-6 py-4 text-left font-semibold">No</th>
                                <th class="px-6 py-4 text-left font-semibold">Nama</th>
                                <th class="px-6 py-4 text-left font-semibold">Jabatan</th>
                                <th class="px-6 py-4 text-left font-semibold">Ruangan</th>
                                <th class="px-6 py-4 text-left font-semibold">Status</th>
                                <th class="px-6 py-4 text-left font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="staffManagementTableBody" class="divide-y divide-gray-100">
                            </tbody>
                    </table>
                </div>
            </div>

            <div class="card-hover bg-white rounded-3xl shadow-xl p-8 animate-fade-in-up">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-users mr-3 text-blue-500"></i>Rekapitulasi Penilaian Staf
                    </h2>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" id="rekaptitulasiSearchInput" placeholder="Cari staff..." class="pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-3 text-blue-500"></i>
                        </div>
                        <select id="rekaptitulasiFilterSelect" class="px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option>Semua Status</option>
                            <option>Excellent Performance</option>
                            <option>Good Performance</option>
                            <option>Need Mentoring</option>
                            <option>Need Improvement</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm bg-white text-black rounded-xl">
                        <thead>
                            <tr class="bg-gray-100 text-black">
                                <th class="px-6 py-4 text-left font-semibold">Nama</th>
                                <th class="px-6 py-4 text-left font-semibold">Kedisiplinan</th>
                                <th class="px-6 py-4 text-left font-semibold">Komunikasi</th>
                                <th class="px-6 py-4 text-left font-semibold">Komplain</th>
                                <th class="px-6 py-4 text-left font-semibold">Kepatuhan</th>
                                <th class="px-6 py-4 text-left font-semibold">Target Kerja</th>
                                <th class="px-6 py-4 text-left font-semibold">Status</th>
                                <th class="px-6 py-4 text-left font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="performanceEvaluationTableBody" class="divide-y divide-gray-100">
                            </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div id="performanceEvaluationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-5 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 id="performanceEvaluationModalTitle" class="text-xl font-bold text-gray-800">Tambah Penilaian Staf Baru</h3>
                <button type="button" onclick="closePerformanceEvaluationModal()" class="text-gray-500 hover:text-gray-700 text-lg">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="performanceEvaluationForm">
                <input type="hidden" id="evaluationId">
                <div class="mb-4">
                    <label for="staffSelect" class="block text-gray-700 text-sm font-medium mb-2">Pilih Staff</label>
                    <select id="staffSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Pilih Staff</option>
                        </select>
                </div>
                <div class="mb-4">
                    <label for="kedisiplinan" class="block text-gray-700 text-sm font-medium mb-2">Kedisiplinan (1-5)</label>
                    <input type="number" id="kedisiplinan" min="1" max="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="komunikasi" class="block text-gray-700 text-sm font-medium mb-2">Komunikasi (1-5)</label>
                    <input type="number" id="komunikasi" min="1" max="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="komplain" class="block text-gray-700 text-sm font-medium mb-2">Komplain (1-5)</label>
                    <input type="number" id="komplain" min="1" max="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="kepatuhan" class="block text-gray-700 text-sm font-medium mb-2">Kepatuhan (1-5)</label>
                    <input type="number" id="kepatuhan" min="1" max="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-6">
                    <label for="targetKerja" class="block text-gray-700 text-sm font-medium mb-2">Target Kerja (1-5)</label>
                    <input type="number" id="targetKerja" min="1" max="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-6">
                    <label for="notes" class="block text-gray-700 text-sm font-medium mb-2">Catatan (Opsional)</label>
                    <textarea id="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closePerformanceEvaluationModal()" class="animated-button bg-gray-200 text-gray-800 px-6 py-3 rounded-xl font-semibold hover:bg-gray-300 transition duration-300">Batal</button>
                    <button type="submit" class="animated-button bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition duration-300">Simpan Penilaian</button>
                    <button type="button" id="deleteEvaluationBtn" onclick="deletePerformanceEvaluation()" class="animated-button bg-red-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-red-700 transition duration-300 hidden">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <div id="performanceDetailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-bold text-gray-800"><i class="fas fa-info-circle mr-2 text-blue-500"></i>Detail Penilaian Staf</h3>
                <button type="button" onclick="closePerformanceDetailModal()" class="text-gray-500 hover:text-gray-700 text-lg">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="grid grid-cols-2 gap-y-3 gap-x-4 text-gray-700 text-sm mb-6">
                <div class="col-span-2">
                    <p class="font-semibold">Nama Staff:</p>
                    <p id="detailStaffName" class="text-base font-medium"></p>
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
                    <p id="detailStatusKinerja" class="text-base font-bold"></p>
                </div>
                <div class="col-span-2">
                    <p class="font-semibold">Catatan:</p>
                    <p id="detailNotes" class="bg-gray-50 p-3 rounded-lg border border-gray-200"></p>
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
                <button type="button" onclick="closePerformanceDetailModal()" class="animated-button bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition duration-300">Tutup</button>
            </div>
        </div>
    </div>


    <div id="staffManagementModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-5 w-full max-w-md">
            <div class="flex justify-between items-center mb-3">
                <h3 id="staffManagementModalTitle" class="text-lg font-bold">Tambah Staff Baru</h3>
                <button onclick="closeStaffManagementModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="staffManagementForm">
                <input type="hidden" id="staffManagementId">
                {{-- These hidden fields are populated by JS from window.currentUser --}}
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                <input type="hidden" name="department_id" value="{{ Auth::user()->department_id }}">
                <input type="hidden" name="hospital_id" value="{{ Auth::user()->hospital_id }}">
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1" for="staffManagementFullName">Nama Lengkap</label>
                    <input type="text" id="staffManagementFullName" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1" for="staffManagementPosition">Jabatan</label>
                    <select id="staffManagementPosition" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                        <option value="">Pilih Jabatan</option>
                        </select>
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm mb-1" for="staffManagementStatus">Status</label>
                    <select id="staffManagementStatus" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                        <option value="Cuti">Cuti</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeStaffManagementModal()" class="px-3 py-1 rounded-lg text-sm text-gray-700 hover:bg-gray-100 btn-outline-green">Batal</button>
                    <button type="submit" class="px-3 py-1 rounded-lg text-sm text-white bg-green-500 hover:bg-green-600">Simpan</button>
                    <button type="button" id="deleteStaffManagementBtn" onclick="deleteStaffManagement()" class="px-3 py-1 rounded-lg text-sm text-white btn-red hidden">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/kinerja-staff.js') }}"></script>
</body>
</html>