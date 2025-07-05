<!DOCTYPE html>
<html lang="en" class="h-full bg-white w-full overflow-x-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PPI Monitoring System</title>
    {{-- Include Chart.js before your custom ppi.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- Font Awesome for Icons (like the plus icon) - IMPORTANT for new form button --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    {{-- Your ppi.js script (ensure this path is correct) --}}
    <script src="{{ asset('js/ppi.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/ppi.css') }}">
    @vite('resources/css/app.css')
    {{-- <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script> --}}
    {{-- Inline styles (can be moved to ppi.css for better organization) --}}
</head>
<body class="min-h-full w-full bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 overflow-x-hidden">
    <script>
        window.authToken = "{{ session('token') }}";
    </script>
    @include('components.sidebar-navbar')

    <div class="p-4 pt-20 pl-60 pr-5 animate-fadeIn">
        <div class="bg-white p-6 border border-gray-200 rounded-xl shadow-lg backdrop-blur-sm dark:border-gray-700 dark:bg-gray-800/80">
            <div class="text-center mb-10">
                <div class="inline-block p-4 transform hover:scale-105 transition-all duration-300">
                    <h1 class="text-4xl font-bold text-black mb-3">Pengendalian dan Pencegahan Infeksi</h1>
                    <p class="text-gray-600 mt-2">Sistem Monitoring Bundle CVC Terintegrasi</p>
                </div>

                <div class="flex justify-center">
                    <img src="{{ asset('images/icon-suntik.png') }}" alt="Logo Pengendalian dan Pencegahan Infeksi"
                         class="h-24 w-auto rounded-lg transition-transform duration-300 hover:scale-105" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-md border border-blue-100 flex items-center hover:shadow-lg transition-shadow duration-300">
                    <div class="bg-blue-100 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Insersi Hari Ini</h3>
                        <p class="text-2xl font-bold text-gray-800" id="insertionCompliance">-- Form <span class="text-green-500 text-sm"></span></p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border border-green-100 flex items-center hover:shadow-lg transition-shadow duration-300">
                    <div class="bg-green-100 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Maintenance Hari Ini</h3>
                        <p class="text-2xl font-bold text-gray-800" id="maintenanceCompliance">-- Form <span class="text-green-500 text-sm"></span></p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border border-red-100 flex items-center hover:shadow-lg transition-shadow duration-300">
                    <div class="bg-red-100 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016zM12 9v2m0 4h.01" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Infeksi Aktif</h3>
                        <p class="text-2xl font-bold text-gray-800" id="totalInfections">-- Kasus <span class="text-red-500 text-sm"></span></p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                    <div class="p-6 cursor-pointer" onclick="toggleSection('alat-kesehatan')">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6 text-blue-800">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-lg font-semibold text-gray-900">Bundle Insersi CVC</span>
                                    <div class="text-sm text-gray-500 mt-1">Protokol lengkap untuk pemasangan CVC steril</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium" id="totalInsertionElements">12 Elemen</div>
                                <svg class="w-6 h-6 text-gray-400 transform transition-transform duration-300" id="arrow-alat-kesehatan" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div id="alat-kesehatan" class="hidden border-t border-gray-100">
                        <div class="p-6 bg-gray-50 space-y-4">
                            {{-- Removed: Progress Bar section --}}

                            <div class="border-b border-gray-200 flex justify-between items-center">
                                <nav class="-mb-px flex space-x-8">
                                    <button onclick="switchTab('insersi', 'insersi-form', event)" id="insersi-form-tab" class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button" data-section="insersi" data-tab-target="insersi-form">
                                        Form Supervisi
                                    </button>
                                    <button onclick="switchTab('insersi', 'insersi-history', event)" id="insersi-history-tab" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button" data-section="insersi" data-tab-target="insersi-history">
                                        Riwayat
                                    </button>
                                </nav>
                                <button type="button" id="newInsertionFormBtn" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 transition-colors duration-200">
                                    <i class="fas fa-plus mr-1"></i> Form Baru
                                </button>
                            </div>

                            <div id="insersi-form" class="tab-content insersi-tab pt-4">
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Isi Form Supervisi Bundle Insersi CVC</h3>
                                    <p class="text-gray-600 mb-4">Lengkapi form ini untuk setiap pemasangan CVC yang diobservasi.</p>

                                    <form id="insertionForm" enctype="multipart/form-data">
                                        <input type="hidden" id="insertionFormId">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label for="insertionPatientName" class="block text-sm font-medium text-gray-700">Nama Pasien</label>
                                                <input type="text" id="insertionPatientName" class="mt-1 focus:ring-blue-500 text-black focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                            </div>
                                            <div>
                                                <label for="insertionMedicalRecordNumber" class="block text-sm font-medium text-gray-700">Nomor RM</label>
                                                <input type="text" id="insertionMedicalRecordNumber" class="mt-1 focus:ring-blue-500 text-black focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                            </div>
                                            <div>
                                                <label for="insertionDate" class="block text-sm font-medium text-gray-700">Tanggal Insersi</label>
                                                <input type="date" id="insertionDate" class="mt-1 focus:ring-blue-500 text-black focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                            </div>
                                            <div>
                                                <label for="insertionLocation" class="block text-sm font-medium text-gray-700">Lokasi Insersi</label>
                                                <select id="insertionLocation" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 text-black focus:border-blue-500 sm:text-sm" required>
                                                    <option value="">Pilih Lokasi</option>
                                                    <option value="V. Subklavia Kanan">V. Subklavia Kanan</option>
                                                    <option value="V. Subklavia Kiri">V. Subklavia Kiri</option>
                                                    <option value="V. Jugularis Interna Kanan">V. Jugularis Interna Kanan</option>
                                                    <option value="V. Jugularis Interna Kiri">V. Jugularis Interna Kiri</option>
                                                    <option value="V. Femoralis Kanan">V. Femoralis Kanan</option>
                                                    <option value="V. Femoralis Kiri">V. Femoralis Kiri</option>
                                                    <option value="Lainnya">Lainnya</option>
                                                </select>
                                            </div>
                                            <div class="col-span-1 md:col-span-2">
                                                <label for="insertionOperatorName" class="block text-sm font-medium text-gray-700">Operator (Dokter/Perawat)</label>
                                                <input type="text" id="insertionOperatorName" class="mt-1 focus:ring-blue-500 text-black focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            </div>
                                        </div>

                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Element Observasi</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200" id="insertionElementsTableBody">
                                                    {{-- Dynamic rows will be inserted here by JavaScript --}}
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="mt-6 flex justify-end items-center">
                                            {{-- Removed: "Terakhir diperbarui" span --}}
                                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 hover:scale-105 transform">
                                                Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div id="insersi-history" class="tab-content insersi-tab hidden pt-4">
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Riwayat Insersi CVC</h3>
                                    <p class="text-gray-600 mb-4">Daftar insersi CVC yang telah dilakukan dalam 30 hari terakhir.</p>

                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Insersi</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Operator</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kepatuhan</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Pada</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opsi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200" id="insertionHistoryTableBody">
                                                    {{-- History data will be loaded here --}}
                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="insertionPagination" class="mt-4 flex items-center justify-between pagination-controls">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                    <div class="p-6 cursor-pointer" onclick="toggleSection('bundle-maintenance')">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-lg font-semibold text-gray-900">Bundle Maintenance CVC</span>
                                    <div class="text-sm text-gray-500 mt-1">Protokol perawatan harian untuk mencegah infeksi</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium" id="totalMaintenanceElements">8 Elemen</div>
                                <svg class="w-6 h-6 text-gray-400 transform transition-transform duration-300" id="arrow-bundle-maintenance" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div id="bundle-maintenance" class="hidden border-t border-gray-100">
                        <div class="p-6 bg-gray-50 space-y-4">
                            {{-- Removed: Progress Bar section --}}

                            <div class="border-b border-gray-200 flex justify-between items-center">
                                <nav class="-mb-px flex space-x-8">
                                    <button onclick="switchTab('maintenance', 'maintenance-form', event)" id="maintenance-form-tab" class="border-green-500 text-green-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button" data-section="maintenance" data-tab-target="maintenance-form">
                                        Form Supervisi
                                    </button>
                                    <button onclick="switchTab('maintenance', 'maintenance-history', event)" id="maintenance-history-tab" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button" data-section="maintenance" data-tab-target="maintenance-history">
                                        Riwayat
                                    </button>
                                </nav>
                                <button type="button" id="newMaintenanceFormBtn" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-400 transition-colors duration-200">
                                    <i class="fas fa-plus mr-1"></i> Form Baru
                                </button>
                            </div>

                            <div id="maintenance-form" class="tab-content maintenance-tab pt-4">
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Isi Form Supervisi Bundle Maintenance CVC</h3>
                                    <p class="text-gray-600 mb-4">Lengkapi form ini untuk setiap perawatan CVC yang diobservasi.</p>

                                    <form id="maintenanceForm" enctype="multipart/form-data">
                                        <input type="hidden" id="maintenanceFormId">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label for="maintenancePatientName" class="block text-sm font-medium text-gray-700">Nama Pasien</label>
                                                <input type="text" id="maintenancePatientName" class="mt-1 focus:ring-green-500 text-black focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                            </div>
                                            <div>
                                                <label for="maintenanceMedicalRecordNumber" class="block text-sm font-medium text-gray-700">Nomor RM</label>
                                                <input type="text" id="maintenanceMedicalRecordNumber" class="mt-1 focus:ring-green-500 text-black focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            </div>
                                            <div>
                                                <label for="maintenanceDate" class="block text-sm font-medium text-gray-700">Tanggal Perawatan</label>
                                                <input type="date" id="maintenanceDate" class="mt-1 focus:ring-green-500 text-black focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                            </div>
                                            <div class="col-span-1 md:col-span-2">
                                                <label for="maintenanceNurseName" class="block text-sm font-medium text-gray-700">Perawat Penanggung Jawab</label>
                                                <input type="text" id="maintenanceNurseName" class="mt-1 focus:ring-green-500 text-black focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            </div>
                                        </div>

                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Element Observasi</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200" id="maintenanceElementsTableBody">
                                                    {{-- Dynamic rows will be inserted here by JavaScript --}}
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="mt-6 flex justify-end items-center">
                                            {{-- Removed: "Terakhir diperbarui" span --}}
                                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 hover:scale-105 transform">
                                                Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div id="maintenance-history" class="tab-content maintenance-tab hidden pt-4">
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Riwayat Maintenance CVC</h3>
                                    <p class="text-gray-600 mb-4">Daftar perawatan CVC yang telah dilakukan dalam 30 hari terakhir.</p>

                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Perawatan</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tindakan</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perawat</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kepatuhan</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Pada</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opsi</th> {{-- Changed from Aksi to Opsi --}}
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200" id="maintenanceHistoryTableBody">
                                                    {{-- History data will be loaded here --}}
                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="maintenancePagination" class="mt-4 flex items-center justify-between pagination-controls">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                    <div class="p-6 cursor-pointer" onclick="toggleSection('infeksi-terkait')">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-red-100 to-red-200 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6 text-red-800">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-lg font-semibold text-gray-900">Monitoring Infeksi Terkait CVC</span>
                                    <div class="text-sm text-gray-500 mt-1">Pelaporan dan analisis infeksi aliran darah terkait kateter</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium" id="totalInfectionCases">5 Kasus</div>
                                <svg class="w-6 h-6 text-gray-400 transform transition-transform duration-300" id="arrow-infeksi-terkait" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div id="infeksi-terkait" class="hidden border-t border-gray-100">
                        <div class="p-6 bg-gray-50 space-y-4">
                            <div class="border-b border-gray-200 flex justify-between items-center">
                                <nav class="-mb-px flex space-x-8">
                                    <button onclick="switchTab('infeksi', 'infeksi-form', event)" id="infeksi-form-tab" class="border-red-500 text-red-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button" data-section="infeksi" data-tab-target="infeksi-form">
                                        Form Pelaporan
                                    </button>
                                    <button onclick="switchTab('infeksi', 'infeksi-analisis', event)" id="infeksi-analisis-tab" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button" data-section="infeksi" data-tab-target="infeksi-analisis">
                                        Analisis
                                    </button>
                                    <button onclick="switchTab('infeksi', 'infeksi-history', event)" id="infeksi-history-tab" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button" data-section="infeksi" data-tab-target="infeksi-history">
                                        Riwayat
                                    </button>
                                </nav>
                                <button type="button" id="newInfectionReportBtn" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400 transition-colors duration-200">
                                    <i class="fas fa-plus mr-1"></i> Form Baru
                                </button>
                            </div>

                            <div id="infeksi-form" class="tab-content infeksi-tab pt-4">
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Isi Form Pelaporan Infeksi Terkait CVC</h3>
                                    <p class="text-gray-600 mb-4">Lengkapi form ini untuk setiap pelaporan infeksi.</p>

                                    <form id="infectionReportForm" enctype="multipart/form-data">
                                        <input type="hidden" id="infectionReportId">
                                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                            <div>
                                                <label for="infectionPatientName" class="block text-sm font-medium text-gray-700">Nama Pasien</label>
                                                <input type="text" name="patient_name" id="infectionPatientName" class="mt-1 focus:ring-red-500 text-black focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                            </div>

                                            <div>
                                                <label for="infectionMedicalRecordNumber" class="block text-sm font-medium text-gray-700">Nomor RM</label>
                                                <input type="text" name="medical_record_number" id="infectionMedicalRecordNumber" class="mt-1 focus:ring-red-500 text-black focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            </div>

                                            <div>
                                                <label for="infectionInsertionDate" class="block text-sm font-medium text-gray-700">Tanggal Insersi CVC</label>
                                                <input type="date" name="insertion_date" id="infectionInsertionDate" class="mt-1 focus:ring-red-500 text-black focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            </div>

                                            <div>
                                                <label for="infectionInsertionLocation" class="block text-sm font-medium text-gray-700">Lokasi Insersi</label>
                                                <select id="infectionInsertionLocation" name="insertion_location" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-red-500 text-black focus:border-red-500 sm:text-sm">
                                                    <option value="">Pilih Lokasi</option>
                                                    <option value="V. Subklavia Kanan">V. Subklavia Kanan</option>
                                                    <option value="V. Subklavia Kiri">V. Subklavia Kiri</option>
                                                    <option value="V. Jugularis Interna Kanan">V. Jugularis Interna Kanan</option>
                                                    <option value="V. Jugularis Interna Kiri">V. Jugularis Interna Kiri</option>
                                                    <option value="V. Femoralis Kanan">V. Femoralis Kanan</option>
                                                    <option value="V. Femoralis Kiri">V. Femoralis Kiri</option>
                                                    <option value="Lainnya">Lainnya</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label for="infectionDiagnosisDate" class="block text-sm font-medium text-gray-700">Tanggal Diagnosis Infeksi</label>
                                                <input type="date" name="infection_diagnosis_date" id="infectionDiagnosisDate" class="mt-1 focus:ring-red-500 text-black focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                            </div>

                                            <div>
                                                <label for="infectionType" class="block text-sm font-medium text-gray-700">Jenis Infeksi</label>
                                                <select id="infectionType" name="infection_type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-red-500 text-black focus:border-red-500 sm:text-sm" required>
                                                    <option value="">Pilih Jenis Infeksi</option>
                                                    <option value="CLABSI (Central Line Associated Bloodstream Infection)">CLABSI (Central Line Associated Bloodstream Infection)</option>
                                                    <option value="Exit Site Infection">Exit Site Infection</option>
                                                    <option value="Tunnel Infection">Tunnel Infection</option>
                                                    <option value="Pocket Infection">Pocket Infection</option>
                                                </select>
                                            </div>

                                            <div class="sm:col-span-2">
                                                <label for="clinicalSymptoms" class="block text-sm font-medium text-gray-700">Gejala Klinis</label>
                                                <div class="mt-1">
                                                    <textarea id="clinicalSymptoms" name="clinical_symptoms" rows="3" class="shadow-sm focus:ring-red-500 text-black focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                                                </div>
                                            </div>

                                            <div class="sm:col-span-2">
                                                <label for="microorganism" class="block text-sm font-medium text-gray-700">Hasil Mikrobiologi (e.g., Staphylococcus aureus)</label>
                                                <div class="mt-1">
                                                    <input type="text" id="microorganism" name="microorganism" class="mt-1 focus:ring-red-500 text-black focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Contoh: Staphylococcus aureus">
                                                </div>
                                            </div>

                                            <div class="sm:col-span-2">
                                                <label for="management" class="block text-sm font-medium text-gray-700">Tatalaksana</label>
                                                <div class="mt-1">
                                                    <textarea id="management" name="management" rows="3" class="shadow-sm focus:ring-red-500 text-black focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                                                </div>
                                            </div>

                                            <div class="sm:col-span-2">
                                                <label for="infectionPhoto" class="block text-sm font-medium text-gray-700">Foto Dokumentasi</label>
                                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                                    <div class="space-y-1 text-center">
                                                        <img id="infectionPhotoPreview" class="mx-auto h-24 w-auto object-cover hidden" src="#" alt="Photo preview">
                                                        <svg id="infectionPhotoPlaceholder" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        <div class="flex text-sm text-gray-600">
                                                            <label for="infectionFileUpload" class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500">
                                                                <span>Upload file</span>
                                                                <input id="infectionFileUpload" name="photo" type="file" class="sr-only" accept="image/*">
                                                            </label>
                                                            <p class="pl-1">or drag and drop</p>
                                                        </div>
                                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                                        <button type="button" id="removeInfectionPhoto" class="mt-2 text-xs text-red-500 hover:text-red-700 hidden">Hapus Foto</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-6 flex justify-end">
                                            <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="resetInfectionForm()">
                                                Batal
                                            </button>
                                            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div id="infeksi-analisis" class="tab-content infeksi-tab hidden pt-4">
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Analisis Infeksi Terkait CVC</h3>
                                    <p class="text-gray-600 mb-4">Data dan grafik analisis insiden infeksi terkait CVC.</p>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                                            <h4 class="text-sm font-semibold text-gray-500 mb-3">INSIDEN INFEKSI 6 BULAN TERAKHIR</h4>
                                            <div class="h-64 flex items-center justify-center">
                                                <canvas id="infectionIncidentChart"></canvas>
                                            </div>
                                        </div>
                                        <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                                            <h4 class="text-sm font-semibold text-gray-500 mb-3">LOKASI INSERSI TERKAIT INFEKSI</h4>
                                            <div class="h-64 flex items-center justify-center">
                                                <canvas id="infectionLocationChart"></canvas>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                                        <h4 class="text-sm font-semibold text-gray-500 mb-3">MIKROORGANISME PENYEBAB TERBANYAK</h4>
                                        <div class="h-64 flex items-center justify-center">
                                            <canvas id="microorganismChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="infeksi-history" class="tab-content infeksi-tab hidden pt-4">
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Riwayat Infeksi Terkait CVC</h3>
                                    <p class="text-gray-600 mb-4">Daftar kasus infeksi terkait CVC dalam 6 bulan terakhir.</p>

                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Diagnosis</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Infeksi</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mikroorganisme</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Pada</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opsi</th> {{-- Changed from Aksi to Opsi --}}
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200" id="infectionHistoryTableBody">
                                                    {{-- History data will be loaded here --}}
                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="infectionPagination" class="mt-4 flex items-center justify-between pagination-controls">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="detailModal" class="modal hidden">
        <div class="modal-content">
            <button class="modal-close-button" id="closeDetailModal">&times;</button>
            <div id="detailModalBody">
                </div>
        </div>
    </div>

    <div id="photoModal" class="modal hidden">
        <div class="modal-content">
            <button class="modal-close-button" id="closePhotoModal">&times;</button>
            <div id="photoModalBody" class="w-full h-full flex justify-center items-center">
                </div>
        </div>
    </div>
</body>
</html>