<!DOCTYPE html>
<html lang="en" class="h-full bg-white">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laporan Kepala Ruangan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/laporan.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/laporan.js') }}"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
</head>
<body class="min-h-full bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 text-gray-800">
    {{-- Pass the authentication token to JavaScript --}}
    <script>
        window.authToken = "{{ session('token') }}";
    </script>
    @include('components.sidebar-navbar')
    <div class="p-4 text-black">
        <main class="px-4 sm:pl-60 sm:pr-5 flex-1 py-8 mt-8">
            <div class="glass-effect rounded-2xl p-4 sm:p-8 mb-8 shadow-lg">
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <div class="relative">
                        <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('images/p.png') }}"
                             alt="Foto Profil"
                             class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-4 border-white shadow-xl ring-4 ring-green-100" />
                        <div class="absolute -bottom-2 -right-2 w-6 h-6 sm:w-8 sm:h-8 bg-green-500 rounded-full border-4 border-white flex items-center justify-center">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                    </div>
                    <div class="flex-1 text-center sm:text-left mt-4 sm:mt-0">
                        <h1 class="text-2xl sm:text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent mb-2">
                            Laporan Kepala Ruangan
                        </h1>
                        <p class="text-gray-600 text-sm sm:text-base" id="headerDate">Loading date...</p>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Rekap aktivitas, kinerja staf, dan informasi penting ruangan</p>
                    </div>
                    <div class="flex gap-2 sm:gap-4 mt-4 sm:mt-0">
                        <div class="text-center p-2 sm:p-4 bg-green-100 rounded-lg">
                            <div class="text-xl sm:text-2xl font-bold text-green-600" id="activeStaffCount">Loading...</div>
                            <div class="text-xs sm:text-sm text-green-700">Staf Aktif</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow mb-8 overflow-x-auto">
                <div class="flex border-b" style="min-width: 600px;">
                    <button onclick="showTab('catatan')" data-tab="catatan" class="tab-btn px-4 py-3 sm:px-6 sm:py-4 font-medium text-blue-600 border-b-2 border-blue-600 whitespace-nowrap">
                        <i class="fas fa-calendar-check mr-2"></i>Catatan Harian
                    </button>
                    <button onclick="showTab('jadwal')" data-tab="jadwal" class="tab-btn px-4 py-3 sm:px-6 sm:py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                        <i class="fas fa-clock mr-2"></i>Jadwal Dinas
                    </button>
                    <button onclick="showTab('logistik')" data-tab="logistik" class="tab-btn px-4 py-3 sm:px-6 sm:py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                        <i class="fas fa-boxes mr-2"></i>Manajemen Logistik
                    </button>
                    <button onclick="showTab('ppi')" data-tab="ppi" class="tab-btn px-4 py-3 sm:px-6 sm:py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                        <i class="fas fa-shield-alt mr-2"></i>PPI
                    </button>
                    <button onclick="showTab('kinerja')" data-tab="kinerja" class="tab-btn px-4 py-3 sm:px-6 sm:py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                        <i class="fas fa-user-cog mr-2"></i>Kinerja Staff
                    </button>
                    <button onclick="showTab('tna')" data-tab="tna" class="tab-btn px-4 py-3 sm:px-6 sm:py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                        <i class="fas fa-graduation-cap mr-2"></i>TNA
                    </button>
                    <button onclick="showTab('mutu')" data-tab="mutu" class="tab-btn px-4 py-3 sm:px-6 sm:py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                        <i class="fas fa-award mr-2"></i>Indikator Mutu
                    </button>
                </div>

                <div id="catatan" class="tab-content active p-4 sm:p-6"></div>
                <div id="jadwal" class="tab-content p-4 sm:p-6"></div>
                <div id="logistik" class="tab-content p-4 sm:p-6"></div>
                <div id="ppi" class="tab-content p-4 sm:p-6"></div>
                <div id="kinerja" class="tab-content p-4 sm:p-6"></div>
                <div id="tna" class="tab-content p-4 sm:p-6"></div>
                <div id="mutu" class="tab-content p-4 sm:p-6"></div>
            </div>
        </main>
    </div>
    <div id="exportDateRangeModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-[9999] overflow-y-auto overflow-x-hidden justify-center items-center">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow"> <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t"> <h3 class="text-xl font-semibold text-gray-900"> Pilih Rentang Tanggal Laporan
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="exportDateRangeModal" onclick="window.hideDateRangeExportModal()"> <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Tutup modal</span>
                    </button>
                </div>
                <div class="p-4 md:p-5">
                    <form class="space-y-4" id="dateRangeExportForm">
                        <div>
                            <label for="modal_start_date" class="block mb-2 text-sm font-medium text-gray-900">Dari Tanggal:</label> <input type="text" name="start_date" id="modal_start_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="YYYY-MM-DD"> </div>
                        <div>
                            <label for="modal_end_date" class="block mb-2 text-sm font-medium text-gray-900">Sampai Tanggal:</label> <input type="text" name="end_date" id="modal_end_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="YYYY-MM-DD"> </div>
                        <div class="flex items-center">
                            <input id="all_time_checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"> <label for="all_time_checkbox" class="ms-2 text-sm font-medium text-gray-900">Sepanjang Waktu (Abaikan tanggal)</label> </div>
                        <button type="submit" id="confirmExportBtn" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"> Ekspor Laporan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="jadwalDinasExportModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-[9999] overflow-y-auto overflow-x-hidden justify-center items-center">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Pilih Rentang Bulan untuk Laporan Jadwal Dinas
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="jadwalDinasExportModal" onclick="window.hideJadwalDinasExportModal()">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Tutup modal</span>
                    </button>
                </div>
                <div class="p-4 md:p-5">
                    <form class="space-y-4" id="jadwalDinasExportForm">
                        <div>
                            <label for="jadwal_from_month" class="block mb-2 text-sm font-medium text-gray-900">Dari Bulan:</label>
                            <select id="jadwal_from_month" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        <div>
                            <label for="jadwal_from_year" class="block mb-2 text-sm font-medium text-gray-900">Dari Tahun:</label>
                            <select id="jadwal_from_year" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </select>
                        </div>

                        <div>
                            <label for="jadwal_to_month" class="block mb-2 text-sm font-medium text-gray-900">Sampai Bulan:</label>
                            <select id="jadwal_to_month" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        <div>
                            <label for="jadwal_to_year" class="block mb-2 text-sm font-medium text-gray-900">Sampai Tahun:</label>
                            <select id="jadwal_to_year" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </select>
                        </div>

                        <button type="submit" id="confirmJadwalDinasExportBtn" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            Ekspor Jadwal
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="toast-container" class="fixed top-5 right-5 z-[10000] w-full max-w-xs space-y-3">
        <div id="toast-template" class="hidden items-center w-full p-4 text-gray-500 bg-white rounded-lg shadow-lg" role="alert">
            <div id="toast-icon" class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg"></div>
            <div id="toast-message" class="ms-3 text-sm font-normal"></div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100" onclick="this.parentElement.remove()">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
            </button>
        </div>
    </div>
</body>
</html>