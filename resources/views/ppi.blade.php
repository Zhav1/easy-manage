<!DOCTYPE html>
<html lang="en" class="h-full bg-white w-full overflow-x-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PPI Monitoring System</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src="{{ asset('js/ppi.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/ppi.css') }}">
    @vite('resources/css/app.css')
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

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-md border border-blue-100 flex items-center hover:shadow-lg transition-shadow duration-300">
                    <div class="bg-blue-100 p-3 rounded-full mr-4">
                        <i class="fas fa-check-circle text-blue-800 text-xl"></i>
                        
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Insersi Hari Ini</h3>
                        <p class="text-2xl font-bold text-gray-800" id="insertionCompliance">-- Form <span class="text-green-500 text-sm"></span></p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border border-green-100 flex items-center hover:shadow-lg transition-shadow duration-300">
                    <div class="bg-gradient-to-br from-purple-100 to-purple-200 p-3 rounded-full mr-4">
                        <i class="fas fa-hand-holding-medical text-purple-800 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Maintenance Hari Ini</h3>
                        <p class="text-2xl font-bold text-gray-800" id="maintenanceCompliance">-- Form <span class="text-green-500 text-sm"></span></p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border border-red-100 flex items-center hover:shadow-lg transition-shadow duration-300">
                    <div class="bg-red-100 p-3 rounded-full mr-4">
                        <i class="fas fa-virus text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Infeksi Aktif</h3>
                        <p class="text-2xl font-bold text-gray-800" id="totalInfections">-- Kasus <span class="text-red-500 text-sm"></span></p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md border border-red-100 flex items-center hover:shadow-lg transition-shadow duration-300">
                    <div class="bg-gradient-to-br from-emerald-100 to-emerald-200 p-3 rounded-full mr-4">
                        <i class="fas fa-syringe text-emerald-800 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Tertusuk Jarum Hari Ini</h3>
                        <p class="text-2xl font-bold text-gray-800" id="totalNeedlestickCases">-- Kasus <span class="text-red-500 text-sm"></span></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden mb-6">
                <div class="p-6 cursor-pointer" onclick="toggleSection('needlestick-injury')">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-emerald-200 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                                <i class="fas fa-syringe text-emerald-800 text-xl"></i>
                            </div>
                            <div>
                                <span class="text-lg font-semibold text-gray-900">Pelaporan Tertusuk Jarum</span>
                                <div class="text-sm text-gray-500 mt-1">Pelaporan dan penanganan insiden tertusuk jarum</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm font-medium" id="totalNeedlestickCases">13 Elemen</div>
                            <svg class="w-6 h-6 text-gray-400 transform transition-transform duration-300" id="arrow-needlestick-injury" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div id="needlestick-injury" class="hidden border-t border-gray-100">
                    <div class="p-6 bg-gray-50 space-y-4">
                        <div class="border-b border-gray-200 flex justify-between items-center">
                            <nav class="-mb-px flex space-x-8">
                                <button onclick="switchTab('needlestick', 'needlestick-form', event)" id="needlestick-form-tab" class="border-b-2 font-medium text-sm tab-button border-emerald-500 text-emerald-600 whitespace-nowrap py-4 px-1" data-section="needlestick" data-tab-target="needlestick-form">
                                    Form Pelaporan
                                </button>
                                <button onclick="switchTab('needlestick', 'needlestick-history', event)" id="needlestick-history-tab" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button" data-section="needlestick" data-tab-target="needlestick-history">
                                    Riwayat
                                </button>
                                {{-- REMOVED: No separate analytics tab here --}}
                            </nav>
                            <button type="button" id="newNeedlestickReportBtn" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-500 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-400 transition-colors duration-200">
                                <i class="fas fa-plus mr-1"></i> Form Baru
                            </button>
                        </div>

                        <div id="needlestick-form" class="tab-content needlestick-tab pt-4">
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">Isi Form Pelaporan Tertusuk Jarum</h3>
                                <p class="text-gray-600 mb-4">Lengkapi form ini untuk setiap pelaporan insiden tertusuk jarum.</p>

                                <form id="needlestickReportForm" enctype="multipart/form-data">
                                    <input type="hidden" id="needlestickReportId">
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                        <div>
                                            <label for="needlestickDate" class="block text-sm font-medium text-gray-700">Tanggal Kejadian</label>
                                            <input type="date" name="incident_date" id="needlestickDate" class="mt-1 focus:ring-emerald-500 text-black focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                        </div>

                                        <div>
                                            <label for="needlestickTime" class="block text-sm font-medium text-gray-700">Waktu Kejadian</label>
                                            <input type="time" name="incident_time" id="needlestickTime" class="mt-1 focus:ring-emerald-500 text-black focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                        </div>

                                        <div>
                                            <label for="needlestickLocation" class="block text-sm font-medium text-gray-700">Lokasi Kejadian</label>
                                            <select id="needlestickLocation" name="location" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 text-black focus:border-emerald-500 sm:text-sm" required>
                                                <option value="">Pilih Lokasi</option>
                                                <option value="IGD">IGD</option>
                                                <option value="Ruang Rawat Inap">Ruang Rawat Inap</option>
                                                <option value="ICU">ICU</option>
                                                <option value="PICU">PICU</option>
                                                <option value="NICU">NICU</option>
                                                <option value="Ruang Operasi">Ruang Operasi</option>
                                                <option value="Ruang Isolasi">Ruang Isolasi</option>
                                                <option value="Ruang Hemodialisa">Ruang Hemodialisa</option>
                                                <option value="Ruang Perawatan Khusus">Ruang Perawatan Khusus</option>
                                                <option value="Ruang Bersalin">Ruang Bersalin</option>
                                                <option value="Ruang Radiologi">Ruang Radiologi</option>
                                                <option value="Ruang Laboratorium">Ruang Laboratorium</option>
                                                <option value="Ruang Farmasi">Ruang Farmasi</option>
                                                <option value="Lainnya">Lainnya</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label for="needlestickDepartment" class="block text-sm font-medium text-gray-700">Unit/Bagian</label>
                                            <select id="needlestickDepartment" name="department" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 text-black focus:border-emerald-500 sm:text-sm" required>
                                                <option value="">Pilih Unit/Bagian</option>
                                                <option value="Anestesi">Anestesi</option>
                                                <option value="Bedah">Bedah</option>
                                                <option value="Penyakit Dalam">Penyakit Dalam</option>
                                                <option value="Anak">Anak</option>
                                                <option value="Kebidanan & Kandungan">Kebidanan & Kandungan</option>
                                                <option value="Radiologi">Radiologi</option>
                                                <option value="Laboratorium">Laboratorium</option>
                                                <option value="Farmasi">Farmasi</option>
                                                <option value="Gizi">Gizi</option>
                                                <option value="Fisioterapi">Fisioterapi</option>
                                                <option value="Hemodialisa">Hemodialisa</option>
                                                <option value="ICU">ICU</option>
                                                <option value="NICU/PICU">NICU/PICU</option>
                                                <option value="IGD">IGD</option>
                                                <option value="OK">OK</option>
                                                <option value="Perawatan">Perawatan</option>
                                                <option value="Lainnya">Lainnya</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label for="injuredPersonName" class="block text-sm font-medium text-gray-700">Nama yang Tertusuk</label>
                                            <input type="text" name="injured_person_name" id="injuredPersonName" class="mt-1 focus:ring-emerald-500 text-black focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                        </div>

                                        <div>
                                            <label for="injuredPersonPosition" class="block text-sm font-medium text-gray-700">Jabatan</label>
                                            <select id="injuredPersonPosition" name="injured_person_position" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 text-black focus:border-emerald-500 sm:text-sm" required>
                                                <option value="">Pilih Jabatan</option>
                                                <option value="Dokter">Dokter</option>
                                                <option value="Perawat">Perawat</option>
                                                <option value="Bidan">Bidan</option>
                                                <option value="Apoteker">Apoteker</option>
                                                <option value="Analis Laboratorium">Analis Laboratorium</option>
                                                <option value="Radiografer">Radiografer</option>
                                                <option value="Fisioterapis">Fisioterapis</option>
                                                <option value="Ahli Gizi">Ahli Gizi</option>
                                                <option value="Petugas Kebersihan">Petugas Kebersihan</option>
                                                <option value="Petugas Laundry">Petugas Laundry</option>
                                                <option value="Petugas Sterilisasi">Petugas Sterilisasi</option>
                                                <option value="Petugas Administrasi">Petugas Administrasi</option>
                                                <option value="Lainnya">Lainnya</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label for="injuredPersonAge" class="block text-sm font-medium text-gray-700">Usia</label>
                                            <input type="number" name="injured_person_age" id="injuredPersonAge" class="mt-1 focus:ring-emerald-500 text-black focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                        </div>

                                        <div>
                                            <label for="injuredPersonGender" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                                            <select id="injuredPersonGender" name="injured_person_gender" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 text-black focus:border-emerald-500 sm:text-sm" required>
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="Laki-laki">Laki-laki</option>
                                                <option value="Perempuan">Perempuan</option>
                                            </select>
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label for="incidentDescription" class="block text-sm font-medium text-gray-700">Deskripsi Kejadian</label>
                                            <div class="mt-1">
                                                <textarea id="incidentDescription" name="incident_description" rows="3" class="shadow-sm focus:ring-emerald-500 text-black focus:border-emerald-500 block w-full sm:text-sm border-gray-300 rounded-md" required></textarea>
                                            </div>
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label for="sourcePatientStatus" class="block text-sm font-medium text-gray-700">Status Pasien Sumber</label>
                                            <div class="mt-1">
                                                <textarea id="sourcePatientStatus" name="source_patient_status" rows="2" class="shadow-sm focus:ring-emerald-500 text-black focus:border-emerald-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                                            </div>
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700">Tindakan Segera yang Dilakukan</label>
                                            <div class="mt-2 space-y-2">
                                                <div class="flex items-center">
                                                    <input id="immediateAction1" name="immediate_actions[]" type="checkbox" value="Cuci luka dengan air mengalir dan sabun" class="focus:ring-emerald-500 h-4 w-4 text-emerald-600 border-gray-300 rounded">
                                                    <label for="immediateAction1" class="ml-2 block text-sm text-gray-900">Cuci luka dengan air mengalir dan sabun</label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input id="immediateAction2" name="immediate_actions[]" type="checkbox" value="Peras darah dari luka" class="focus:ring-emerald-500 h-4 w-4 text-emerald-600 border-gray-300 rounded">
                                                    <label for="immediateAction2" class="ml-2 block text-sm text-gray-900">Peras darah dari luka</label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input id="immediateAction3" name="immediate_actions[]" type="checkbox" value="Berikan antiseptik" class="focus:ring-emerald-500 h-4 w-4 text-emerald-600 border-gray-300 rounded">
                                                    <label for="immediateAction3" class="ml-2 block text-sm text-gray-900">Berikan antiseptik</label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input id="immediateAction4" name="immediate_actions[]" type="checkbox" value="Lainnya" class="focus:ring-emerald-500 h-4 w-4 text-emerald-600 border-gray-300 rounded">
                                                    <label for="immediateAction4" class="ml-2 block text-sm text-gray-900">Lainnya</label>
                                                    <input type="text" id="otherImmediateAction" name="other_immediate_action" class="ml-2 focus:ring-emerald-500 text-black focus:border-emerald-500 block w-1/2 shadow-sm sm:text-sm border-gray-300 rounded-md hidden">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label for="followUpActions" class="block text-sm font-medium text-gray-700">Tindak Lanjut</label>
                                            <div class="mt-1">
                                                <textarea id="followUpActions" name="follow_up_actions" rows="3" class="shadow-sm focus:ring-emerald-500 text-black focus:border-emerald-500 block w-full sm:text-sm border-gray-300 rounded-md" required></textarea>
                                            </div>
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label for="needlestickPhoto" class="block text-sm font-medium text-gray-700">Foto Dokumentasi</label>
                                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                                <div class="space-y-1 text-center">
                                                    <img id="needlestickPhotoPreview" class="mx-auto h-24 w-auto object-cover hidden" src="#" alt="Photo preview">
                                                    <svg id="needlestickPhotoPlaceholder" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <div class="flex text-sm text-gray-600">
                                                        <label for="needlestickFileUpload" class="relative cursor-pointer bg-white rounded-md font-medium text-emerald-600 hover:text-emerald-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-emerald-500">
                                                            <span>Upload file</span>
                                                            <input id="needlestickFileUpload" name="photo" type="file" class="sr-only" accept="image/*">
                                                        </label>
                                                        <p class="pl-1">or drag and drop</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                                    <button type="button" id="removeNeedlestickPhoto" class="mt-2 text-xs text-emerald-500 hover:text-emerald-700 hidden">Hapus Foto</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex justify-end">
                                        <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500" onclick="resetNeedlestickForm()">
                                            Batal
                                        </button>
                                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div id="needlestick-history" class="tab-content needlestick-tab hidden pt-4">
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">Riwayat Pelaporan Tertusuk Jarum</h3>
                                <p class="text-gray-600 mb-4">Daftar insiden tertusuk jarum dalam 6 bulan terakhir.</p>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kejadian</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama yang Tertusuk</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi Kejadian</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit/Bagian</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tindakan</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Pada</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opsi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200" id="needlestickHistoryTableBody">
                                            </tbody>
                                    </table>
                                </div>

                                <div id="needlestickPagination" class="mt-4 flex items-center justify-between pagination-controls">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden mb-6">
                <div class="p-6 cursor-pointer" onclick="toggleSection('alat-kesehatan')">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                                <i class="fas fa-check-circle text-blue-800 text-xl"></i>
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
                        <div class="border-b border-gray-200 flex justify-between items-center">
                            <nav class="-mb-px flex space-x-8">
                                <button onclick="switchTab('insersi', 'insersi-form', event)" id="insersi-form-tab" class="border-b-2 font-medium text-sm tab-button border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1" data-section="insersi" data-tab-target="insersi-form">
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
                                            <input type="text" id="insertionPatientName" name="patient_name" class="mt-1 focus:ring-blue-500 text-black focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                        </div>
                                        <div>
                                            <label for="insertionMedicalRecordNumber" class="block text-sm font-medium text-gray-700">Nomor RM</label>
                                            <input type="text" id="insertionMedicalRecordNumber" name="medical_record_number" class="mt-1 focus:ring-blue-500 text-black focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                        </div>
                                        <div>
                                            <label for="insertionDate" class="block text-sm font-medium text-gray-700">Tanggal Insersi</label>
                                            <input type="date" id="insertionDate" name="insertion_date" class="mt-1 focus:ring-blue-500 text-black focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                        </div>
                                        <div>
                                            <label for="insertionLocation" class="block text-sm font-medium text-gray-700">Lokasi Insersi</label>
                                            <select id="insertionLocation" name="insertion_location" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 text-black focus:border-blue-500 sm:text-sm" required>
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
                                            <input type="text" id="insertionOperatorName" name="operator_name" class="mt-1 focus:ring-blue-500 text-black focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
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
                                                </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-6 flex justify-end items-center">
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
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden mb-6">
                <div class="p-6 cursor-pointer" onclick="toggleSection('maintenance-cvc')">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                                <i class="fas fa-hand-holding-medical text-purple-800 text-xl"></i>
                            </div>
                            <div>
                                <span class="text-lg font-semibold text-gray-900">Bundle Maintenance CVC</span>
                                <div class="text-sm text-gray-500 mt-1">Protokol perawatan dan pemantauan CVC</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium" id="totalMaintenanceElements">8 Elemen</div>
                            <svg class="w-6 h-6 text-gray-400 transform transition-transform duration-300" id="arrow-maintenance-cvc" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div id="maintenance-cvc" class="hidden border-t border-gray-100">
                    <div class="p-6 bg-gray-50 space-y-4">
                        <div class="border-b border-gray-200 flex justify-between items-center">
                            <nav class="-mb-px flex space-x-8">
                                <button onclick="switchTab('maintenance', 'maintenance-form', event)" id="maintenance-form-tab" class="border-b-2 font-medium text-sm tab-button border-purple-500 text-purple-600 whitespace-nowrap py-4 px-1" data-section="maintenance" data-tab-target="maintenance-form">
                                    Form Supervisi
                                </button>
                                <button onclick="switchTab('maintenance', 'maintenance-history', event)" id="maintenance-history-tab" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button" data-section="maintenance" data-tab-target="maintenance-history">
                                    Riwayat
                                </button>
                            </nav>
                            <button type="button" id="newMaintenanceFormBtn" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-500 hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-400 transition-colors duration-200">
                                <i class="fas fa-plus mr-1"></i> Form Baru
                            </button>
                        </div>

                        <div id="maintenance-form" class="tab-content maintenance-tab pt-4">
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">Isi Form Supervisi Bundle Maintenance CVC</h3>
                                <p class="text-gray-600 mb-4">Lengkapi form ini untuk setiap observasi perawatan CVC.</p>

                                <form id="maintenanceForm" enctype="multipart/form-data">
                                    <input type="hidden" id="maintenanceFormId">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label for="maintenancePatientName" class="block text-sm font-medium text-gray-700">Nama Pasien</label>
                                            <input type="text" id="maintenancePatientName" name="patient_name" class="mt-1 focus:ring-purple-500 text-black focus:border-purple-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                        </div>
                                        <div>
                                            <label for="maintenanceMedicalRecordNumber" class="block text-sm font-medium text-gray-700">Nomor RM</label>
                                            <input type="text" id="maintenanceMedicalRecordNumber" name="medical_record_number" class="mt-1 focus:ring-purple-500 text-black focus:border-purple-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                        </div>
                                        <div>
                                            <label for="maintenanceDate" class="block text-sm font-medium text-gray-700">Tanggal Observasi</label>
                                            <input type="date" id="maintenanceDate" name="maintenance_date" class="mt-1 focus:ring-purple-500 text-black focus:border-purple-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                        </div>
                                        <div>
                                            <label for="maintenanceLocation" class="block text-sm font-medium text-gray-700">Lokasi CVC</label>
                                            <select id="maintenanceLocation" name="maintenance_location" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-purple-500 text-black focus:border-purple-500 sm:text-sm" required>
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
                                            <label for="maintenanceDaysInserted" class="block text-sm font-medium text-gray-700">Hari Ke- (Setelah Insersi)</label>
                                            <input type="number" id="maintenanceDaysInserted" name="days_inserted" class="mt-1 focus:ring-purple-500 text-black focus:border-purple-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                        </div>
                                        <div>
                                            <label for="maintenanceNurseName" class="block text-sm font-medium text-gray-700">Nama Operator (Dokter/Perawat)</label>
                                            <input type="text" id="maintenanceNurseName" name="nurse_name" class="mt-1 focus:ring-purple-500 text-black focus:border-purple-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
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
                                                </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-6 flex justify-end items-center">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200 hover:scale-105 transform">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div id="maintenance-history" class="tab-content maintenance-tab hidden pt-4">
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">Riwayat Maintenance CVC</h3>
                                <p class="text-gray-600 mb-4">Daftar observasi perawatan CVC dalam 30 hari terakhir.</p>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Observasi</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi CVC</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari Ke- (Setelah Insersi)</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Operator</th> {{-- Updated header --}}
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kepatuhan</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Pada</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opsi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200" id="maintenanceHistoryTableBody">
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

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden mb-6">
                <div class="p-6 cursor-pointer" onclick="toggleSection('infeksi-cvc')">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-red-100 to-red-200 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                                <i class="fas fa-virus text-red-800 text-xl"></i>
                            </div>
                            <div>
                                <span class="text-lg font-semibold text-gray-900">Infeksi CVC</span>
                                <div class="text-sm text-gray-500 mt-1">Pelaporan dan pemantauan infeksi terkait CVC</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium" id="totalInfectionCases">10 Elemen</div>
                            <svg class="w-6 h-6 text-gray-400 transform transition-transform duration-300" id="arrow-infeksi-cvc" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div id="infeksi-cvc" class="hidden border-t border-gray-100">
                    <div class="p-6 bg-gray-50 space-y-4">
                        <div class="border-b border-gray-200 flex justify-between items-center">
                            <nav class="-mb-px flex space-x-8">
                                <button onclick="switchTab('infeksi', 'infeksi-form', event)" id="infeksi-form-tab" class="border-b-2 font-medium text-sm tab-button border-red-500 text-red-600 whitespace-nowrap py-4 px-1" data-section="infeksi" data-tab-target="infeksi-form">
                                    Form Pelaporan
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
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">Isi Form Pelaporan Infeksi CVC</h3>
                                <p class="text-gray-600 mb-4">Lengkapi form ini untuk setiap kasus infeksi yang terkait dengan CVC.</p>

                                <form id="infectionReportForm" enctype="multipart/form-data">
                                    <input type="hidden" id="infectionReportId">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label for="infectionPatientName" class="block text-sm font-medium text-gray-700">Nama Pasien</label>
                                            <input type="text" id="infectionPatientName" name="patient_name" class="mt-1 focus:ring-red-500 text-black focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                        </div>
                                        <div>
                                            <label for="infectionMedicalRecordNumber" class="block text-sm font-medium text-gray-700">Nomor RM</label>
                                            <input type="text" id="infectionMedicalRecordNumber" name="medical_record_number" class="mt-1 focus:ring-red-500 text-black focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                        </div>
                                        <div>
                                            <label for="infectionDate" class="block text-sm font-medium text-gray-700">Tanggal Diagnosa</label>
                                            <input type="date" id="infectionDate" name="infection_diagnosis_date" class="mt-1 focus:ring-red-500 text-black focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                        </div>
                                        <div>
                                            <label for="infectionType" class="block text-sm font-medium text-gray-700">Jenis Infeksi</label>
                                            <select id="infectionType" name="infection_type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-red-500 text-black focus:border-red-500 sm:text-sm" required>
                                                <option value="">Pilih Jenis Infeksi</option>
                                                <option value="CLABSI">CLABSI (Central Line-Associated Bloodstream Infection)</option>
                                                <option value="Exit Site Infection">Exit Site Infection</option>
                                                <option value="Tunnel Infection">Tunnel Infection</option>
                                                <option value="Pocket Infection">Pocket Infection</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="infectionLocation" class="block text-sm font-medium text-gray-700">Lokasi CVC</label>
                                            <select id="infectionLocation" name="insertion_location" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-red-500 text-black focus:border-red-500 sm:text-sm" required>
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
                                            <label for="infectionDaysInserted" class="block text-sm font-medium text-gray-700">Hari Ke- (Setelah Insersi)</label>
                                            <input type="number" id="infectionDaysInserted" name="days_inserted" class="mt-1 focus:ring-red-500 text-black focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                        </div>
                                        <div class="col-span-1 md:col-span-2">
                                            <label for="infectionSymptoms" class="block text-sm font-medium text-gray-700">Gejala Klinis</label>
                                            <textarea id="infectionSymptoms" name="clinical_symptoms" rows="3" class="shadow-sm focus:ring-red-500 text-black focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md" required></textarea>
                                        </div>
                                        <div class="col-span-1 md:col-span-2">
                                            <label for="infectionLabResults" class="block text-sm font-medium text-gray-700">Hasil Laboratorium (Mikroorganisme)</label>
                                            <textarea id="infectionLabResults" name="microorganism" rows="3" class="shadow-sm focus:ring-red-500 text-black focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md" required></textarea>
                                        </div>
                                        <div class="col-span-1 md:col-span-2">
                                            <label for="infectionTreatment" class="block text-sm font-medium text-gray-700">Penanganan</label>
                                            <textarea id="infectionTreatment" name="management" rows="3" class="shadow-sm focus:ring-red-500 text-black focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md" required></textarea>
                                        </div>
                                        <div class="col-span-1 md:col-span-2">
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

                        <div id="infeksi-history" class="tab-content infeksi-tab hidden pt-4">
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">Riwayat Infeksi CVC</h3>
                                <p class="text-gray-600 mb-4">Daftar kasus infeksi terkait CVC dalam 6 bulan terakhir.</p>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Diagnosa</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Infeksi</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi CVC</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari Ke- (Setelah Insersi)</th> 
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Pada</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opsi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200" id="infectionHistoryTableBody">
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

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden mb-6">
                <div class="p-6 cursor-pointer" onclick="toggleSection('analytics-dashboard')">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                                <i class="fas fa-chart-line text-indigo-800 text-xl"></i>
                            </div>
                            <div>
                                <span class="text-lg font-semibold text-gray-900">Analisis & Dashboard</span>
                                <div class="text-sm text-gray-500 mt-1">Visualisasi data dan analisis kinerja PPI</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-gray-400 transform transition-transform duration-300" id="arrow-analytics-dashboard" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div id="analytics-dashboard" class="hidden border-t border-gray-100">
                    <div class="p-6 bg-gray-50 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Kepatuhan Bundle Insersi CVC</h3>
                                <div class="h-64">
                                    <canvas id="insertionComplianceChart"></canvas>
                                </div>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Kepatuhan Bundle Maintenance CVC</h3>
                                <div class="h-64">
                                    <canvas id="maintenanceComplianceChart"></canvas>
                                </div>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Tren Insiden Tertusuk Jarum (6 Bulan Terakhir)</h3>
                                <div class="h-64">
                                    <canvas id="needlestickIncidentsChart"></canvas>
                                </div>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Kejadian Infeksi CVC (6 Bulan Terakhir)</h3>
                                <div class="h-64">
                                    <canvas id="infectionRatesChart"></canvas>
                                </div>
                            </div>
                            {{-- NEW ANALYTICS CHARTS FOR CONSOLIDATED DASHBOARD --}}
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Insiden Tertusuk Jarum Berdasarkan Unit/Bagian</h3>
                                <div class="h-64">
                                    <canvas id="needlestickByDepartmentChart"></canvas>
                                </div>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Insiden Tertusuk Jarum Berdasarkan Jabatan</h3>
                                <div class="h-64">
                                    <canvas id="needlestickByPositionChart"></canvas>
                                </div>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Infeksi CVC Berdasarkan Lokasi Insersi</h3>
                                <div class="h-64">
                                    <canvas id="infectionLocationChart"></canvas>
                                </div>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Infeksi CVC Berdasarkan Mikroorganisme</h3>
                                <div class="h-64">
                                    <canvas id="microorganismChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Indikator Kinerja Utama PPI</h3>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100">
                                    <div class="text-indigo-800 font-medium">CLABSI Rate</div>
                                    <div class="text-2xl font-bold text-indigo-900" id="clabsiRate">0.0</div>
                                    <div class="text-sm text-indigo-600">per 1000 hari pemasangan</div>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                                    <div class="text-green-800 font-medium">Kepatuhan Insersi</div>
                                    <div class="text-2xl font-bold text-green-900" id="insertionComplianceRate">0%</div>
                                    <div class="text-sm text-green-600">30 hari terakhir</div>
                                </div>
                                <div class="bg-purple-50 p-4 rounded-lg border border-purple-100">
                                    <div class="text-purple-800 font-medium">Kepatuhan Maintenance</div>
                                    <div class="text-2xl font-bold text-purple-900" id="maintenanceComplianceRate">0%</div>
                                    <div class="text-sm text-purple-600">30 hari terakhir</div>
                                </div>
                                <div class="bg-red-50 p-4 rounded-lg border border-red-100">
                                    <div class="text-red-800 font-medium">Tertusuk Jarum</div>
                                    <div class="text-2xl font-bold text-red-900" id="needlestickRate">0</div>
                                    <div class="text-sm text-red-600">30 hari terakhir</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="detailModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Detail</h3>
                            <div class="mt-2">
                                <div id="modalContent" class="text-sm text-gray-500">
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="closeModalBtn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="confirmationModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="confirmationModalTitle">Konfirmasi</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" id="confirmationModalMessage">Apakah Anda yakin ingin menghapus data ini?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="confirmDeleteBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Hapus
                    </button>
                    <button type="button" id="cancelDeleteBtn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
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

</body>
</html>