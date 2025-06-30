<!DOCTYPE html>
<html lang="en" class="h-full bg-white w-screen">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PPI Monitoring System</title>
    <script src={{ asset('js/dashboard.js') }}></script>
    @vite('resources/css/app.css') 
    <script>
        if (localStorage.getItem('color-theme') === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
        }
    </script>
      <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <style>
        .tab-content {
    display: none;
}

.tab-content:not(.hidden) {
    display: block;
}
        .active-tab {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .tab-button:hover {
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        .checklist-item:hover {
            background-color: #f8fafc;
            transform: translateX(2px);
            transition: all 0.2s ease;
        }
    </style>
</head>
<body class="min-h-full  bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100">
     @include('components.sidebar-navbar')
    
    <div class="p-4 pt-20 pl-60 pr-5 animate-fadeIn">
        <div class="bg-white p-6 border border-gray-200 rounded-xl shadow-lg  backdrop-blur-sm dark:border-gray-700 dark:bg-gray-800/80">
            <!-- Enhanced Header with Animation -->
            <div class="text-center mb-10">
                <div class="inline-block p-4 transform hover:scale-105 transition-all duration-300">
                    <h1 class="text-4xl font-bold text-black mb-3">Pengendalian dan Pencegahan Infeksi</h1>
                    <p class="text-gray-600 mt-2">Sistem Monitoring Bundle CVC Terintegrasi</p>
                </div>
                
               <!-- logo -->
                <div class="flex justify-center">
                <img src="{{ asset('images/icon-suntik.png') }}" alt="Logo Pengendalian dan Pencegahan Infeksi"
                     class="h-24 w-auto rounded-lg transition-transform duration-300 hover:scale-105" />
            </div>
        </div>

            <!-- Dashboard Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-md border border-blue-100 flex items-center hover:shadow-lg transition-shadow duration-300">
                    <div class="bg-blue-100 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Kepatuhan Insersi</h3>
                        <p class="text-2xl font-bold text-gray-800">87% <span class="text-green-500 text-sm">↑ 2.5%</span></p>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-md border border-green-100 flex items-center hover:shadow-lg transition-shadow duration-300">
                    <div class="bg-green-100 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Kepatuhan Maintenance</h3>
                        <p class="text-2xl font-bold text-gray-800">92% <span class="text-green-500 text-sm">↑ 1.8%</span></p>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-md border border-red-100 flex items-center hover:shadow-lg transition-shadow duration-300">
                    <div class="bg-red-100 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016zM12 9v2m0 4h.01" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Infeksi Terkait</h3>
                        <p class="text-2xl font-bold text-gray-800">3 <span class="text-red-500 text-sm">↓ 1</span></p>
                    </div>
                </div>
            </div>

            <!-- Enhanced Bundle Sections -->
            <div class="space-y-6">
                <!-- Bundle Insersi with Timeline -->
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
                                <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">12 Elemen</div>
                                <svg class="w-6 h-6 text-gray-400 transform transition-transform duration-300" id="arrow-alat-kesehatan" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div id="alat-kesehatan" class="hidden border-t border-gray-100">
                        <div class="p-6 bg-gray-50 space-y-4">
                            <!-- Progress Timeline -->
                            <div class="mb-6">
                                <h4 class="text-sm font-semibold text-gray-500 mb-3">PROGRESS INSERSI HARI INI</h4>
                                <div class="relative">
                                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-blue-200">
                                        <div style="width:75%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500"></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-600">
                                        <span>0%</span>
                                        <span>25%</span>
                                        <span>50%</span>
                                        <span>75%</span>
                                        <span>100%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Tab Navigation -->
                            <div class="border-b border-gray-200">
                                <nav class="-mb-px flex space-x-8">
                                    <button onclick="switchTab('insersi', 'insersi-form', event)" id="insersi-form-tab" class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button">
                                        Form Supervisi
                                    </button>
                                    <button onclick="switchTab('insersi', 'insersi-checklist', event)" id="insersi-checklist-tab" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button">
                                        Checklist Pra-Prosedur
                                    </button>
                                    <button onclick="switchTab('insersi', 'insersi-history', event)" id="insersi-history-tab" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button">
                                        Riwayat
                                    </button>
                                </nav>
                            </div>
                            
                            <!-- Tab Content -->
                            <div id="insersi-form" class="tab-content insersi-tab pt-4">
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Format Supervisi Bundle Insersi CVC</h3>
                                    <p class="text-gray-600 mb-4">Menilai kepatuhan prosedur pemasangan CVC steril untuk mencegah infeksi aliran darah terkait penggunaan kateter.</p>
                                    
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
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">1</td>
                                                    <td class="px-6 py-4 text-sm text-gray-500">
                                                        <div class="font-medium">Hand hygiene dilakukan sebelum prosedur</div>
                                                        <div class="text-xs text-gray-400 mt-1">WHO 5 Moment</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <select class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                                            <option>Ya</option>
                                                            <option>Tidak</option>
                                                            <option>Tidak Dilakukan</option>
                                                        </select>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <input type="text" placeholder="Tambahkan catatan" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <button class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            Upload
                                                        </button>
                                                    </td>
                                                </tr>
                                                <!-- Additional rows with more detailed elements -->
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">2</td>
                                                    <td class="px-6 py-4 text-sm text-gray-500">
                                                        <div class="font-medium">Pasien disiapkan dengan antiseptik (chlorhexidine 2% atau povidone iodine 10%)</div>
                                                        <div class="text-xs text-gray-400 mt-1">Diberikan waktu kontak sesuai rekomendasi</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <select class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                                            <option>Ya</option>
                                                            <option>Tidak</option>
                                                            <option>Tidak Dilakukan</option>
                                                        </select>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <input type="text" placeholder="Jenis antiseptik yang digunakan" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <button class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            Upload
                                                        </button>
                                                    </td>
                                                </tr>
                                                <!-- 10 more detailed rows -->
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">3</td>
                                                    <td class="px-6 py-4 text-sm text-gray-500">
                                                        <div class="font-medium">Area tindakan ditutup drape steril menyeluruh</div>
                                                        <div class="text-xs text-gray-400 mt-1">Mencakup seluruh area kerja</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <select class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                                            <option>Ya</option>
                                                            <option>Tidak</option>
                                                            <option>Tidak Dilakukan</option>
                                                        </select>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <input type="text" placeholder="Jenis drape yang digunakan" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <button class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            Upload
                                                        </button>
                                                    </td>
                                                </tr>
                                                <!-- Additional rows continue... -->
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">4</td>
                                                    <td class="px-6 py-4 text-sm text-gray-500">
                                                        <div class="font-medium">Operator menggunakan APD lengkap (masker, cap, sarung tangan steril, gown)</div>
                                                        <div class="text-xs text-gray-400 mt-1">Termasuk pelindung mata jika diperlukan</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <select class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                                            <option>Ya</option>
                                                            <option>Tidak</option>
                                                            <option>Tidak Dilakukan</option>
                                                        </select>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <input type="text" placeholder="Jenis APD yang digunakan" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <button class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            Upload
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">5</td>
                                                    <td class="px-6 py-4 text-sm text-gray-500">
                                                        <div class="font-medium">Lokasi insersi dipilih secara tepat</div>
                                                        <div class="text-xs text-gray-400 mt-1">Subklavia/jugularis lebih disukai daripada femoralis</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <select class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                                            <option>Ya</option>
                                                            <option>Tidak</option>
                                                            <option>Tidak Dilakukan</option>
                                                        </select>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <input type="text" placeholder="Lokasi insersi yang dipilih" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <button class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            Upload
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="mt-6 flex justify-between items-center">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-sm text-gray-500">Terakhir diperbarui: 27 Juni 2024, 14:30</span>
                                        </div>
                                        <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 hover:scale-105 transform">
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Checklist Tab Content (hidden by default) -->
                            <div id="insersi-checklist" class="tab-content insersi-tab hidden pt-4">
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Checklist Pra-Prosedur Insersi CVC</h3>
                                    <p class="text-gray-600 mb-4">Verifikasi kesiapan sebelum memulai prosedur insersi CVC.</p>
                                    
                                    <div class="space-y-4">
                                        <div class="flex items-start checklist-item p-3 rounded-lg transition-all duration-200">
                                            <div class="flex items-center h-5">
                                                <input id="checklist-1" name="checklist-1" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="checklist-1" class="font-medium text-gray-700">Konsul dan persetujuan tindakan sudah lengkap</label>
                                                <p class="text-gray-500">Termasuk penjelasan risiko dan komplikasi</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start checklist-item p-3 rounded-lg transition-all duration-200">
                                            <div class="flex items-center h-5">
                                                <input id="checklist-2" name="checklist-2" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="checklist-2" class="font-medium text-gray-700">Pemeriksaan laboratorium prasyarat lengkap</label>
                                                <p class="text-gray-500">PT, aPTT, trombosit, dan fungsi ginjal</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Additional checklist items -->
                                        <div class="flex items-start checklist-item p-3 rounded-lg transition-all duration-200">
                                            <div class="flex items-center h-5">
                                                <input id="checklist-3" name="checklist-3" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="checklist-3" class="font-medium text-gray-700">Alat dan bahan steril siap pakai</label>
                                                <p class="text-gray-500">Termasuk kit CVC, antiseptik, dan APD lengkap</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start checklist-item p-3 rounded-lg transition-all duration-200">
                                            <div class="flex items-center h-5">
                                                <input id="checklist-4" name="checklist-4" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="checklist-4" class="font-medium text-gray-700">Pasien dalam posisi yang tepat</label>
                                                <p class="text-gray-500">Trendelenburg untuk V. jugularis/subklavia</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start checklist-item p-3 rounded-lg transition-all duration-200">
                                            <div class="flex items-center h-5">
                                                <input id="checklist-5" name="checklist-5" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="checklist-5" class="font-medium text-gray-700">Monitor pasien terpasang</label>
                                                <p class="text-gray-500">EKG, NIBP, dan pulse oximetry</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start checklist-item p-3 rounded-lg transition-all duration-200">
                                            <div class="flex items-center h-5">
                                                <input id="checklist-6" name="checklist-6" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="checklist-6" class="font-medium text-gray-700">Obat emergensi tersedia</label>
                                                <p class="text-gray-500">Termasuk atropin dan adrenalin</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-6">
                                        <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 hover:scale-105 transform">
                                            Konfirmasi Checklist
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- History Tab Content (hidden by default) -->
                            <div id="insersi-history" class="tab-content insersi-tab hidden pt-4">
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Riwayat Insersi CVC</h3>
                                    <p class="text-gray-600 mb-4">Daftar insersi CVC yang telah dilakukan dalam 30 hari terakhir.</p>
                                    
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Operator</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kepatuhan</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">26 Jun 2024</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">Budi Santoso</div>
                                                        <div class="text-sm text-gray-500">RM 123456</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">V. Subklavia Kanan</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Dr. Andi</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">100%</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                                                        <a href="#" class="text-red-600 hover:text-red-900">Hapus</a>
                                                    </td>
                                                </tr>
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">24 Jun 2024</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">Ani Wijaya</div>
                                                        <div class="text-sm text-gray-500">RM 123457</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">V. Jugularis Interna Kiri</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Dr. Budi</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">80%</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                                                        <a href="#" class="text-red-600 hover:text-red-900">Hapus</a>
                                                    </td>
                                                </tr>
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">20 Jun 2024</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">Citra Dewi</div>
                                                        <div class="text-sm text-gray-500">RM 123458</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">V. Femoralis Kanan</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Dr. Citra</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">95%</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                                                        <a href="#" class="text-red-600 hover:text-red-900">Hapus</a>
                                                    </td>
                                                </tr>
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">18 Jun 2024</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">Doni Prasetyo</div>
                                                        <div class="text-sm text-gray-500">RM 123459</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">V. Subklavia Kiri</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Dr. Dian</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">60%</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                                                        <a href="#" class="text-red-600 hover:text-red-900">Hapus</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="mt-4 flex items-center justify-between">
                                        <div class="flex-1 flex justify-between sm:hidden">
                                            <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"> Previous </a>
                                            <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"> Next </a>
                                        </div>
                                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                            <div>
                                                <p class="text-sm text-gray-700">
                                                    Showing <span class="font-medium">1</span> to <span class="font-medium">4</span> of <span class="font-medium">12</span> results
                                                </p>
                                            </div>
                                            <div>
                                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                        <span class="sr-only">Previous</span>
                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                    </a>
                                                    <a href="#" aria-current="page" class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium"> 1 </a>
                                                    <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium"> 2 </a>
                                                    <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium"> 3 </a>
                                                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                        <span class="sr-only">Next</span>
                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                    </a>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bundle Maintenance Section -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                    <div class="p-6 cursor-pointer" onclick="toggleSection('bundle-maintenance')">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6 text-green-800">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-lg font-semibold text-gray-900">Bundle Maintenance CVC</span>
                                    <div class="text-sm text-gray-500 mt-1">Protokol perawatan harian untuk mencegah infeksi</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">8 Elemen</div>
                                <svg class="w-6 h-6 text-gray-400 transform transition-transform duration-300" id="arrow-bundle-maintenance" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div id="bundle-maintenance" class="hidden border-t border-gray-100">
                        <div class="p-6 bg-gray-50 space-y-4">
                            <!-- Progress Timeline -->
                            <div class="mb-6">
                                <h4 class="text-sm font-semibold text-gray-500 mb-3">PROGRESS MAINTENANCE HARI INI</h4>
                                <div class="relative">
                                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-green-200">
                                        <div style="width:92%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500"></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-600">
                                        <span>0%</span>
                                        <span>25%</span>
                                        <span>50%</span>
                                        <span>75%</span>
                                        <span>100%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Tab Navigation -->
                            <div class="border-b border-gray-200">
                                <nav class="-mb-px flex space-x-8">
                                    <button onclick="switchTab('maintenance', 'maintenance-form', event)" id="maintenance-form-tab" class="border-green-500 text-green-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button">
                                        Form Supervisi
                                    </button>
                                    <button onclick="switchTab('maintenance', 'maintenance-checklist', event)" id="maintenance-checklist-tab" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button">
                                        Checklist Harian
                                    </button>
                                    <button onclick="switchTab('maintenance', 'maintenance-history', event)" id="maintenance-history-tab" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button">
                                        Riwayat
                                    </button>
                                </nav>
                            </div>
                            
                            <!-- Tab Content -->
                            <div id="maintenance-form" class="tab-content maintenance-tab pt-4">
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Format Supervisi Bundle Maintenance CVC</h3>
                                    <p class="text-gray-600 mb-4">Menilai kepatuhan prosedur perawatan CVC untuk mencegah infeksi aliran darah terkait penggunaan kateter.</p>
                                    
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
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">1</td>
                                                    <td class="px-6 py-4 text-sm text-gray-500">
                                                        <div class="font-medium">Hand hygiene dilakukan sebelum perawatan</div>
                                                        <div class="text-xs text-gray-400 mt-1">WHO 5 Moment</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <select class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                                                            <option>Ya</option>
                                                            <option>Tidak</option>
                                                            <option>Tidak Dilakukan</option>
                                                        </select>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <input type="text" placeholder="Tambahkan catatan" class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <button class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            Upload
                                                        </button>
                                                    </td>
                                                </tr>
                                                <!-- Additional rows with more detailed elements -->
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">2</td>
                                                    <td class="px-6 py-4 text-sm text-gray-500">
                                                        <div class="font-medium">Perawatan area insersi dengan antiseptik</div>
                                                        <div class="text-xs text-gray-400 mt-1">Chlorhexidine 2% atau povidone iodine 10%</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <select class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                                                            <option>Ya</option>
                                                            <option>Tidak</option>
                                                            <option>Tidak Dilakukan</option>
                                                        </select>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <input type="text" placeholder="Jenis antiseptik yang digunakan" class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <button class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            Upload
                                                        </button>
                                                    </td>
                                                </tr>
                                                <!-- 6 more detailed rows -->
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">3</td>
                                                    <td class="px-6 py-4 text-sm text-gray-500">
                                                        <div class="font-medium">Pemeriksaan kebutuhan kateter setiap hari</div>
                                                        <div class="text-xs text-gray-400 mt-1">Evaluasi kelanjutan pemakaian</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <select class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                                                            <option>Ya</option>
                                                            <option>Tidak</option>
                                                            <option>Tidak Dilakukan</option>
                                                        </select>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <input type="text" placeholder="Catatan evaluasi" class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <button class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            Upload
                                                        </button>
                                                    </td>
                                                </tr>
                                                <!-- Additional rows continue... -->
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">4</td>
                                                    <td class="px-6 py-4 text-sm text-gray-500">
                                                        <div class="font-medium">Penggantian dressing sesuai jadwal</div>
                                                        <div class="text-xs text-gray-400 mt-1">Transparan setiap 7 hari, kasa setiap 2 hari</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <select class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                                                            <option>Ya</option>
                                                            <option>Tidak</option>
                                                            <option>Tidak Dilakukan</option>
                                                        </select>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <input type="text" placeholder="Jenis dressing" class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <button class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            Upload
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="mt-6 flex justify-between items-center">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-sm text-gray-500">Terakhir diperbarui: 27 Juni 2024, 10:15</span>
                                        </div>
                                        <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 hover:scale-105 transform">
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Checklist Tab Content (hidden by default) -->
                            <div id="maintenance-checklist" class="tab-content maintenance-tab hidden pt-4">
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Checklist Harian Maintenance CVC</h3>
                                    <p class="text-gray-600 mb-4">Verifikasi perawatan harian CVC untuk memastikan kepatuhan protokol.</p>
                                    
                                    <div class="space-y-4">
                                        <div class="flex items-start checklist-item p-3 rounded-lg transition-all duration-200">
                                            <div class="flex items-center h-5">
                                                <input id="maintenance-checklist-1" name="maintenance-checklist-1" type="checkbox" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="maintenance-checklist-1" class="font-medium text-gray-700">Hand hygiene sebelum perawatan</label>
                                                <p class="text-gray-500">Menggunakan teknik yang benar</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start checklist-item p-3 rounded-lg transition-all duration-200">
                                            <div class="flex items-center h-5">
                                                <input id="maintenance-checklist-2" name="maintenance-checklist-2" type="checkbox" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="maintenance-checklist-2" class="font-medium text-gray-700">Pemeriksaan area insersi</label>
                                                <p class="text-gray-500">Tanda-tanda infeksi (eritema, edema, nyeri, drainase)</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Additional checklist items -->
                                        <div class="flex items-start checklist-item p-3 rounded-lg transition-all duration-200">
                                            <div class="flex items-center h-5">
                                                <input id="maintenance-checklist-3" name="maintenance-checklist-3" type="checkbox" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="maintenance-checklist-3" class="font-medium text-gray-700">Perawatan area insersi dengan antiseptik</label>
                                                <p class="text-gray-500">Chlorhexidine 2% atau povidone iodine 10%</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start checklist-item p-3 rounded-lg transition-all duration-200">
                                            <div class="flex items-center h-5">
                                                <input id="maintenance-checklist-4" name="maintenance-checklist-4" type="checkbox" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="maintenance-checklist-4" class="font-medium text-gray-700">Penggantian dressing sesuai indikasi</label>
                                                <p class="text-gray-500">Transparan setiap 7 hari, kasa setiap 2 hari</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start checklist-item p-3 rounded-lg transition-all duration-200">
                                            <div class="flex items-center h-5">
                                                <input id="maintenance-checklist-5" name="maintenance-checklist-5" type="checkbox" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="maintenance-checklist-5" class="font-medium text-gray-700">Evaluasi kebutuhan kateter</label>
                                                <p class="text-gray-500">Apakah kateter masih diperlukan?</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-6">
                                        <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 hover:scale-105 transform">
                                            Konfirmasi Checklist
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- History Tab Content (hidden by default) -->
                            <div id="maintenance-history" class="tab-content maintenance-tab hidden pt-4">
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Riwayat Maintenance CVC</h3>
                                    <p class="text-gray-600 mb-4">Daftar perawatan CVC yang telah dilakukan dalam 30 hari terakhir.</p>
                                    
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tindakan</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perawat</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kepatuhan</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">26 Jun 2024</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">Budi Santoso</div>
                                                        <div class="text-sm text-gray-500">RM 123456</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Ganti Dressing</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Nurse Ani</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">100%</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                                                        <a href="#" class="text-red-600 hover:text-red-900">Hapus</a>
                                                    </td>
                                                </tr>
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">24 Jun 2024</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">Ani Wijaya</div>
                                                        <div class="text-sm text-gray-500">RM 123457</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Evaluasi Kateter</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Nurse Budi</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">80%</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                                                        <a href="#" class="text-red-600 hover:text-red-900">Hapus</a>
                                                    </td>
                                                </tr>
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">20 Jun 2024</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">Citra Dewi</div>
                                                        <div class="text-sm text-gray-500">RM 123458</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Perawatan Insersi</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Nurse Citra</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">95%</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                                                        <a href="#" class="text-red-600 hover:text-red-900">Hapus</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="mt-4 flex items-center justify-between">
                                        <div class="flex-1 flex justify-between sm:hidden">
                                            <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"> Previous </a>
                                            <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"> Next </a>
                                        </div>
                                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                            <div>
                                                <p class="text-sm text-gray-700">
                                                    Showing <span class="font-medium">1</span> to <span class="font-medium">3</span> of <span class="font-medium">8</span> results
                                                </p>
                                            </div>
                                            <div>
                                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                        <span class="sr-only">Previous</span>
                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                    </a>
                                                    <a href="#" aria-current="page" class="z-10 bg-green-50 border-green-500 text-green-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium"> 1 </a>
                                                    <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium"> 2 </a>
                                                    <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium"> 3 </a>
                                                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                        <span class="sr-only">Next</span>
                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                    </a>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Infeksi Terkait Section -->
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
                                <div class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">5 Kasus</div>
                                <svg class="w-6 h-6 text-gray-400 transform transition-transform duration-300" id="arrow-infeksi-terkait" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div id="infeksi-terkait" class="hidden border-t border-gray-100">
                        <div class="p-6 bg-gray-50 space-y-4">
                            <!-- Tab Navigation -->
                            <div class="border-b border-gray-200">
                                <nav class="-mb-px flex space-x-8">
                                    <button onclick="switchTab('infeksi', 'infeksi-form', event)" id="infeksi-form-tab" class="border-red-500 text-red-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button">
                                        Form Pelaporan
                                    </button>
                                    <button onclick="switchTab('infeksi', 'infeksi-analisis', event)" id="infeksi-analisis-tab" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button">
                                        Analisis
                                    </button>
                                    <button onclick="switchTab('infeksi', 'infeksi-history', event)" id="infeksi-history-tab" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button">
                                        Riwayat
                                    </button>
                                </nav>
                            </div>
                            
                            <!-- Tab Content -->
                            <div id="infeksi-form" class="tab-content infeksi-tab pt-4">
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Form Pelaporan Infeksi Terkait CVC</h3>
                                    <p class="text-gray-600 mb-4">Laporkan kasus infeksi aliran darah terkait penggunaan kateter vena sentral.</p>
                                    
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                        <div>
                                            <label for="nama-pasien" class="block text-sm font-medium text-gray-700">Nama Pasien</label>
                                            <input type="text" name="nama-pasien" id="nama-pasien" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        
                                        <div>
                                            <label for="rm" class="block text-sm font-medium text-gray-700">Nomor RM</label>
                                            <input type="text" name="rm" id="rm" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        
                                        <div>
                                            <label for="tanggal-insersi" class="block text-sm font-medium text-gray-700">Tanggal Insersi CVC</label>
                                            <input type="date" name="tanggal-insersi" id="tanggal-insersi" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        
                                        <div>
                                            <label for="lokasi-insersi" class="block text-sm font-medium text-gray-700">Lokasi Insersi</label>
                                            <select id="lokasi-insersi" name="lokasi-insersi" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                                <option>V. Subklavia</option>
                                                <option>V. Jugularis Interna</option>
                                                <option>V. Femoralis</option>
                                                <option>Lainnya</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label for="tanggal-infeksi" class="block text-sm font-medium text-gray-700">Tanggal Diagnosis Infeksi</label>
                                            <input type="date" name="tanggal-infeksi" id="tanggal-infeksi" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        
                                        <div>
                                            <label for="jenis-infeksi" class="block text-sm font-medium text-gray-700">Jenis Infeksi</label>
                                            <select id="jenis-infeksi" name="jenis-infeksi" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                                <option>CLABSI (Central Line Associated Bloodstream Infection)</option>
                                                <option>Exit Site Infection</option>
                                                <option>Tunnel Infection</option>
                                                <option>Pocket Infection</option>
                                            </select>
                                        </div>
                                        
                                        <div class="sm:col-span-2">
                                            <label for="gejala-klinis" class="block text-sm font-medium text-gray-700">Gejala Klinis</label>
                                            <div class="mt-1">
                                                <textarea id="gejala-klinis" name="gejala-klinis" rows="3" class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="sm:col-span-2">
                                            <label for="mikrobiologi" class="block text-sm font-medium text-gray-700">Hasil Mikrobiologi</label>
                                            <div class="mt-1">
                                                <textarea id="mikrobiologi" name="mikrobiologi" rows="3" class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="sm:col-span-2">
                                            <label for="tatalaksana" class="block text-sm font-medium text-gray-700">Tatalaksana</label>
                                            <div class="mt-1">
                                                <textarea id="tatalaksana" name="tatalaksana" rows="3" class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="sm:col-span-2">
                                            <label for="foto" class="block text-sm font-medium text-gray-700">Foto Dokumentasi</label>
                                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                                <div class="space-y-1 text-center">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <div class="flex text-sm text-gray-600">
                                                        <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500">
                                                            <span>Upload file</span>
                                                            <input id="file-upload" name="file-upload" type="file" class="sr-only">
                                                        </label>
                                                        <p class="pl-1">or drag and drop</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-6 flex justify-end">
                                        <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            Batal
                                        </button>
                                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            Laporkan Infeksi
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Analisis Tab Content (hidden by default) -->
                            <div id="infeksi-analisis" class="tab-content infeksi-tab hidden pt-4">
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Analisis Infeksi Terkait CVC</h3>
                                    <p class="text-gray-600 mb-4">Data dan grafik analisis insiden infeksi terkait CVC.</p>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                                            <h4 class="text-sm font-semibold text-gray-500 mb-3">INSIDEN INFEKSI 6 BULAN TERAKHIR</h4>
                                            <div class="h-64 flex items-center justify-center">
                                                <img src="{{ asset('images/chart-infeksi.png') }}" alt="Grafik Insiden Infeksi" class="h-full w-auto">
                                            </div>
                                        </div>
                                        <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                                            <h4 class="text-sm font-semibold text-gray-500 mb-3">LOKASI INSERSI TERKAIT INFEKSI</h4>
                                            <div class="h-64 flex items-center justify-center">
                                                <img src="{{ asset('images/chart-lokasi.png') }}" alt="Grafik Lokasi Insersi" class="h-full w-auto">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                                        <h4 class="text-sm font-semibold text-gray-500 mb-3">FAKTOR RISIKO UTAMA</h4>
                                        <div class="space-y-4">
                                            <div>
                                                <div class="flex justify-between mb-1">
                                                    <span class="text-sm font-medium text-gray-700">Durasi Kateter >7 hari</span>
                                                    <span class="text-sm font-medium text-gray-700">78%</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                    <div class="bg-red-600 h-2.5 rounded-full" style="width: 78%"></div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="flex justify-between mb-1">
                                                    <span class="text-sm font-medium text-gray-700">Kepatuhan Hand Hygiene</span>
                                                    <span class="text-sm font-medium text-gray-700">65%</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                    <div class="bg-orange-500 h-2.5 rounded-full" style="width: 65%"></div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="flex justify-between mb-1">
                                                    <span class="text-sm font-medium text-gray-700">Penggunaan Dressing Transparan</span>
                                                    <span class="text-sm font-medium text-gray-700">42%</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                    <div class="bg-yellow-500 h-2.5 rounded-full" style="width: 42%"></div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="flex justify-between mb-1">
                                                    <span class="text-sm font-medium text-gray-700">Evaluasi Harian Kateter</span>
                                                    <span class="text-sm font-medium text-gray-700">35%</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                    <div class="bg-blue-500 h-2.5 rounded-full" style="width: 35%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- History Tab Content (hidden by default) -->
                            <div id="infeksi-history" class="tab-content infeksi-tab hidden pt-4">
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Riwayat Infeksi Terkait CVC</h3>
                                    <p class="text-gray-600 mb-4">Daftar kasus infeksi terkait CVC dalam 6 bulan terakhir.</p>
                                    
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Infeksi</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mikroorganisme</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">25 Jun 2024</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">Budi Santoso</div>
                                                        <div class="text-sm text-gray-500">RM 123456</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">CLABSI</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Staphylococcus aureus</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Aktif</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                                                        <a href="#" class="text-red-600 hover:text-red-900">Hapus</a>
                                                    </td>
                                                </tr>
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">15 Mei 2024</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">Ani Wijaya</div>
                                                        <div class="text-sm text-gray-500">RM 123457</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Exit Site Infection</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Pseudomonas aeruginosa</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                                                        <a href="#" class="text-red-600 hover:text-red-900">Hapus</a>
                                                    </td>
                                                </tr>
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10 Apr 2024</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">Citra Dewi</div>
                                                        <div class="text-sm text-gray-500">RM 123458</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">CLABSI</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Klebsiella pneumoniae</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                                                        <a href="#" class="text-red-600 hover:text-red-900">Hapus</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="mt-4 flex items-center justify-between">
                                        <div class="flex-1 flex justify-between sm:hidden">
                                            <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"> Previous </a>
                                            <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"> Next </a>
                                        </div>
                                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                            <div>
                                                <p class="text-sm text-gray-700">
                                                    Showing <span class="font-medium">1</span> to <span class="font-medium">3</span> of <span class="font-medium">5</span> results
                                                </p>
                                            </div>
                                            <div>
                                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                        <span class="sr-only">Previous</span>
                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                    </a>
                                                    <a href="#" aria-current="page" class="z-10 bg-red-50 border-red-500 text-red-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium"> 1 </a>
                                                    <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium"> 2 </a>
                                                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                        <span class="sr-only">Next</span>
                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                    </a>
                                                </nav>
                                            </div>
                                        </div>
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
        // Toggle section function
        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            const arrow = document.getElementById(`arrow-${sectionId}`);
            
            section.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        }
        // Switch tab function - improved version
function switchTab(section, tabId, event) {
    if (event) event.preventDefault();
    
    console.log(`Switching to tab: ${tabId}`); // Debugging
    
    // Hide all tab contents in this section
    document.querySelectorAll(`.tab-content.${section}-tab`).forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remove active class from all tab buttons in this section
    document.querySelectorAll(`button[onclick*="switchTab('${section}'"]`).forEach(tab => {
        tab.classList.remove(
            'border-blue-500', 'text-blue-600',
            'border-green-500', 'text-green-600',
            'border-red-500', 'text-red-600',
            'active-tab'
        );
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    const targetTab = document.getElementById(tabId);
    if (targetTab) {
        targetTab.classList.remove('hidden');
    } else {
        console.error(`Tab with id ${tabId} not found`);
    }
    
    // Add active class to selected tab button
    const activeTab = event?.currentTarget || document.getElementById(`${tabId}-tab`);
    if (activeTab) {
        activeTab.classList.add('active-tab');
        
        // Set appropriate border and text color based on section
        if (section === 'insersi') {
            activeTab.classList.add('border-blue-500', 'text-blue-600');
        } else if (section === 'maintenance') {
            activeTab.classList.add('border-green-500', 'text-green-600');
        } else if (section === 'infeksi') {
            activeTab.classList.add('border-red-500', 'text-red-600');
        }
        
        activeTab.classList.remove('border-transparent', 'text-gray-500');
    }
}
        
        // Initialize first tab as active
   document.addEventListener('DOMContentLoaded', function() {
    // Initialize tabs
    const initialTabs = [
        {section: 'insersi', tabId: 'insersi-form'},
        {section: 'maintenance', tabId: 'maintenance-form'},
        {section: 'infeksi', tabId: 'infeksi-form'}
    ];
    
    initialTabs.forEach(tab => {
        const activeTab = document.getElementById(`${tab.tabId}-tab`);
        if (activeTab) {
            const colors = {
                'insersi': ['border-blue-500', 'text-blue-600'],
                'maintenance': ['border-green-500', 'text-green-600'],
                'infeksi': ['border-red-500', 'text-red-600']
            };
            
            activeTab.classList.add(...colors[tab.section]);
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            
            // Add event listener
            activeTab.addEventListener('click', function(e) {
                switchTab(tab.section, tab.tabId, e);
            });
        }
    });
    
    // Add event listeners to all tab buttons
    document.querySelectorAll('[data-section]').forEach(button => {
        button.addEventListener('click', function(e) {
            const section = this.getAttribute('data-section');
            const tabId = this.getAttribute('data-tab-target');
            switchTab(section, tabId, e);
        });
    });
});
    </script>
</body>
</html>