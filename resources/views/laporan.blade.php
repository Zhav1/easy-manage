<!DOCTYPE html>
<html lang="en" class="h-full bg-white w-screen">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Laporan Kepala Ruangan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/laporan.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/laporan.js') }}"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-full bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 text-gray-800">
    {{-- Pass the authentication token to JavaScript --}}
    <script>
        window.authToken = "{{ session('token') }}";
    </script>
    @include('components.sidebar-navbar')
    <div class="p-4 text-black">
        <main class="pl-60 pr-5 flex-1 px-6 py-8 mt-8">
            <div class="glass-effect rounded-2xl p-8 mb-8 shadow-lg">
                <div class="flex items-center gap-6">
                    <div class="relative">
                        <img src="images/foto-formal.png"
                             alt="Foto Profil"
                             class="w-24 h-24 rounded-full border-4 border-white shadow-xl ring-4 ring-green-100" />
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 rounded-full border-4 border-white flex items-center justify-center">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent mb-2">
                            Laporan Kepala Ruangan
                        </h1>
                        <p class="text-gray-600" id="headerDate">Loading date...</p>
                        <p class="text-sm text-gray-500 mt-1">Rekap aktivitas, kinerja staf, dan informasi penting ruangan</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="text-center p-4 bg-green-100 rounded-lg">
                            <div class="text-2xl font-bold text-green-600" id="activeStaffCount">Loading...</div>
                            <div class="text-sm text-green-700">Staf Aktif</div>
                        </div>
                        {{-- <div class="text-center p-4 bg-blue-100 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600" id="complianceRate">Loading...</div>
                            <div class="text-sm text-blue-700">Kepatuhan</div>
                        </div> --}}
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow mb-8">
                <div class="flex border-b overflow-x-auto">
                    <button onclick="showTab('catatan')" data-tab="catatan" class="tab-btn px-6 py-4 font-medium text-blue-600 border-b-2 border-blue-600 whitespace-nowrap">
                        <i class="fas fa-calendar-check mr-2"></i>Catatan Harian
                    </button>
                    <button onclick="showTab('jadwal')" data-tab="jadwal" class="tab-btn px-6 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                        <i class="fas fa-clock mr-2"></i>Jadwal Dinas
                    </button>
                    <button onclick="showTab('logistik')" data-tab="logistik" class="tab-btn px-6 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                        <i class="fas fa-boxes mr-2"></i>Manajemen Logistik
                    </button>
                    <button onclick="showTab('ppi')" data-tab="ppi" class="tab-btn px-6 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                        <i class="fas fa-shield-alt mr-2"></i>PPI
                    </button>
                    <button onclick="showTab('kinerja')" data-tab="kinerja" class="tab-btn px-6 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                        <i class="fas fa-user-cog mr-2"></i>Kinerja Staff
                    </button>
                    <button onclick="showTab('tna')" data-tab="tna" class="tab-btn px-6 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                        <i class="fas fa-graduation-cap mr-2"></i>TNA
                    </button>
                    <button onclick="showTab('mutu')" data-tab="mutu" class="tab-btn px-6 py-4 font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">
                        <i class="fas fa-award mr-2"></i>Indikator Mutu
                    </button>
                </div>

                <div id="catatan" class="tab-content active p-6"></div>
                <div id="jadwal" class="tab-content p-6"></div>
                <div id="logistik" class="tab-content p-6"></div>
                <div id="ppi" class="tab-content p-6"></div>
                <div id="kinerja" class="tab-content p-6"></div>
                <div id="tna" class="tab-content p-6"></div>
                <div id="mutu" class="tab-content p-6"></div>
            </div>
        </main>
    </div>

    
</body>
</html>