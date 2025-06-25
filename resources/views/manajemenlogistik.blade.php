<!DOCTYPE html>
<html lang="en" class="h-full bg-white w-screen">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MovieStack</title>
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
</head>
<body class="min-h-full bg-gradient-to-br from-slate-50 to-blue-50">
     @include('components.sidebar-navbar')
   

    <div class="p-4 pt-20 pl-60 pr-5 animate-fadeIn">
        <div class="p-6 border border-gray-200 rounded-xl shadow-lg bg-white/80 backdrop-blur-sm dark:border-gray-700 dark:bg-gray-800/80">
            <!-- Enhanced Header with Animation -->
            <div class="text-center mb-10">
                <div class="inline-block p-4  transform hover:scale-105 transition-all duration-300">
                    <h1 class="text-3xl font-bold text-green-500 tracking-wide">Manajemen Logistik</h1>
                </div>
                
               <!-- logo -->
                <div class="flex justify-center">
                <img src="{{ asset('images/l1.png') }}" alt="Logo Manajemen Logistik"
                     class="h-24 w-auto rounded-lg transition-transform duration-300 hover:scale-105" />
            </div>
        </div>
<div class="mb-6">
 <button class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-2 rounded-full font-medium transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl" onclick="openAddItemModal()">
                <svg class="w-4 h-4 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                </svg>
                Tambahkan Barang
            </button>
        </div>
            <!-- Enhanced Logistics Menu with Better Styling -->
            <div class="space-y-6">
                <!-- Alat Kesehatan -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                    <div class="p-6 cursor-pointer" onclick="toggleSection('alat-kesehatan')">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 2L3 7v11c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V7l-7-5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-lg font-semibold text-gray-900">Alat Kesehatan</span>
                                    <div class="text-sm text-gray-500 mt-1">Medical Equipment Inventory</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">148 Items</div>
                                <svg class="w-6 h-6 text-gray-400 transform transition-transform duration-300" id="arrow-alat-kesehatan" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div id="alat-kesehatan" class="hidden border-t border-gray-100">
                        <div class="p-6 bg-gray-50 space-y-3">
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <span class="font-medium text-gray-900">Stetoskop</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">Stok: <span class="font-semibold text-green-600">15 unit</span></span>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Tersedia</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <span class="font-medium text-gray-900">Termometer Digital</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">Stok: <span class="font-semibold text-green-600">25 unit</span></span>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Tersedia</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></div>
                                    <span class="font-medium text-gray-900">Tensimeter</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">Stok: <span class="font-semibold text-yellow-600">8 unit</span></span>
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Terbatas</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <span class="font-medium text-gray-900">Alat Suntik</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">Stok: <span class="font-semibold text-green-600">100 unit</span></span>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Tersedia</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Linen -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                    <div class="p-6 cursor-pointer" onclick="toggleSection('linen')">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-lg font-semibold text-gray-900">Linen</span>
                                    <div class="text-sm text-gray-500 mt-1">Textile & Bedding Supplies</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">180 Items</div>
                                <svg class="w-6 h-6 text-gray-400 transform transition-transform duration-300" id="arrow-linen" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div id="linen" class="hidden border-t border-gray-100">
                        <div class="p-6 bg-gray-50 space-y-3">
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <span class="font-medium text-gray-900">Sprei Pasien</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">Stok: <span class="font-semibold text-green-600">50 lembar</span></span>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Tersedia</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <span class="font-medium text-gray-900">Sarung Bantal</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">Stok: <span class="font-semibold text-green-600">75 buah</span></span>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Tersedia</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <span class="font-medium text-gray-900">Handuk</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">Stok: <span class="font-semibold text-green-600">30 buah</span></span>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Tersedia</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <span class="font-medium text-gray-900">Selimut</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">Stok: <span class="font-semibold text-green-600">25 buah</span></span>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Tersedia</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Floor Stock -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                    <div class="p-6 cursor-pointer" onclick="toggleSection('floor-stock')">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-lg font-semibold text-gray-900">Floor Stock</span>
                                    <div class="text-sm text-gray-500 mt-1">Medications & Medical Supplies</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">545 Items</div>
                                <svg class="w-6 h-6 text-gray-400 transform transition-transform duration-300" id="arrow-floor-stock" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div id="floor-stock" class="hidden border-t border-gray-100">
                        <div class="p-6 bg-gray-50 space-y-3">
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <span class="font-medium text-gray-900">Obat Generik</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">Stok: <span class="font-semibold text-green-600">200 box</span></span>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Tersedia</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <span class="font-medium text-gray-900">Vitamin</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">Stok: <span class="font-semibold text-green-600">150 botol</span></span>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Tersedia</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <span class="font-medium text-gray-900">Antibiotik</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">Stok: <span class="font-semibold text-green-600">75 box</span></span>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Tersedia</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <span class="font-medium text-gray-900">Infus Set</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">Stok: <span class="font-semibold text-green-600">120 set</span></span>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Tersedia</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alat Rumah Tangga -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                    <div class="p-6 cursor-pointer" onclick="toggleSection('alat-rumah-tangga')">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-orange-100 to-orange-200 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                                    <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-lg font-semibold text-gray-900">Alat Rumah Tangga</span>
                                    <div class="text-sm text-gray-500 mt-1">Household & Cleaning Supplies</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">55 Items</div>
                                <svg class="w-6 h-6 text-gray-400 transform transition-transform duration-300" id="arrow-alat-rumah-tangga" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div id="alat-rumah-tangga" class="hidden border-t border-gray-100">
                        <div class="p-6 bg-gray-50 space-y-3">
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-red-500 rounded-full mr-3"></div>
                                    <span class="font-medium text-gray-900">Sapu</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">Stok: <span class="font-semibold text-red-600">10 buah</span></span>
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Menipis</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-red-500 rounded-full mr-3"></div>
                                    <span class="font-medium text-gray-900">Pel</span>
                      </div>
<div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">Stok: <span class="font-semibold text-red-600">8 buah</span></span>
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Menipis</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <span class="font-medium text-gray-900">Deterjen</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">Stok: <span class="font-semibold text-green-600">20 botol</span></span>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Tersedia</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></div>
                                    <span class="font-medium text-gray-900">Tissue</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">Stok: <span class="font-semibold text-yellow-600">12 pack</span></span>
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Terbatas</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <span class="font-medium text-gray-900">Sabun Cuci Tangan</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">Stok: <span class="font-semibold text-green-600">15 botol</span></span>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Tersedia</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alat Keselamatan -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                    <div class="p-6 cursor-pointer" onclick="toggleSection('alat-keselamatan')">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-red-100 to-red-200 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-lg font-semibold text-gray-900">Alat Keselamatan</span>
                                    <div class="text-sm text-gray-500 mt-1">Safety & Emergency Equipment</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">42 Items</div>
                                <svg class="w-6 h-6 text-gray-400 transform transition-transform duration-300" id="arrow-alat-keselamatan" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div id="alat-keselamatan" class="hidden border-t border-gray-100">
                        <div class="p-6 bg-gray-50 space-y-3">
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <span class="font-medium text-gray-900">Alat Pemadam Kebakaran</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">Stok: <span class="font-semibold text-green-600">6 unit</span></span>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Tersedia</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <span class="font-medium text-gray-900">Masker N95</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">Stok: <span class="font-semibold text-green-600">500 buah</span></span>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Tersedia</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></div>
                                    <span class="font-medium text-gray-900">Sarung Tangan</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">Stok: <span class="font-semibold text-yellow-600">80 box</span></span>
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Terbatas</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <span class="font-medium text-gray-900">Eyewash Station</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">Stok: <span class="font-semibold text-green-600">4 unit</span></span>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Tersedia</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid md:grid-cols-3 gap-6 mt-8">
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Total Stok Tersedia</h3>
                            <p class="text-3xl font-bold">870</p>
                            <p class="text-green-100 text-sm mt-1">Items dalam kondisi baik</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Stok Terbatas</h3>
                            <p class="text-3xl font-bold">28</p>
                            <p class="text-yellow-100 text-sm mt-1">Perlu segera diisi ulang</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Stok Menipis</h3>
                            <p class="text-3xl font-bold">18</p>
                            <p class="text-red-100 text-sm mt-1">Butuh perhatian urgent</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Adding Items -->
    <div id="addItemModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl transform transition-all duration-300">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Tambahkan Barang Baru</h2>
                <p class="text-gray-600 mt-2">Masukkan detail barang yang akan ditambahkan</p>
            </div>
            
            <form class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
    <select class="w-full p-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-green-500 focus:border-transparent">
        <option value="" disabled selected>Pilih kategori</option>
        <option>Alat Kesehatan</option>
        <option>Linen</option>
        <option>Floor Stock</option>
        <option>Alat Rumah Tangga</option>
        <option>Alat Keselamatan</option>
    </select>
</div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Barang</label>
                   <input type="text" 
           class="w-full p-3 border border-gray-300 rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-gray-800 focus:border-transparent" 
           placeholder="Masukkan nama barang">
</div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Stok</label>
<input type="number" class="w-full p-3 border border-gray-300 rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-gray-800 focus:border-transparent"  placeholder="0">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Satuan</label>
                    <input type="text" 
           class="w-full p-3 border border-gray-300 rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-gray-800 focus:border-transparent" *
           placeholder="unit, buah, box, dll">
</div>

<div class="flex space-x-3 pt-4">
    <button type="button" 
            onclick="closeAddItemModal()" 
            class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition-colors duration-200">
        Batal
    </button>
    <button type="submit" 
            class="flex-1 px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-lg font-medium transition-all duration-200 transform hover:scale-105">
        Tambahkan
    </button>
</div>
            </form>
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
            animation: fadeIn 0.6s ease-out;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>
