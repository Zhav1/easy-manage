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
                    <h1 class="text-3xl font-bold text-green-500 tracking-wide">Pengendalian dan Pencegahan Infeksi</h1>
                </div>
                
               <!-- logo -->
                <div class="flex justify-center">
                <img src="{{ asset('images/icon-suntik.png') }}" alt="Logo Pengendalian dan Pencegahan Infeksi"
                     class="h-24 w-auto rounded-lg transition-transform duration-300 hover:scale-105" />
            </div>
        </div>

            <!-- Enhanced Logistics Menu with Better Styling -->
            <div class="space-y-6">
                <!-- Alat Kesehatan -->
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
                                    <span class="text-lg font-semibold text-gray-900">Bundle Insersi</span>
                                    <div class="text-sm text-gray-500 mt-1">Protokol lengkap untuk pemasangan CVC steril</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">6 Pertanyaan</div>
                                <svg class="w-6 h-6 text-gray-400 transform transition-transform duration-300" id="arrow-alat-kesehatan" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div id="alat-kesehatan" class="hidden border-t border-gray-100">
                        <div class="p-6 bg-gray-50 space-y-3">
                            <div class=" gap-3 flex flex-col border-b pb-4 border-gray-200 dark:border-gray-700">
                                <div class="font-semibold dark:text-white text-gray-700 text-xl ">Melakukan kebersihan tangan (5 Moment)</div>
                                <div class="grid grid-cols-2 gap-4 w-full">
                                    <div class="flex items-center w-full ps-4 bg-white bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                        <input id="bordered-radio-1" type="radio" value="" name="kebersihan-tangan"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="bordered-radio-1"
                                        class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                                    </div>
                                    <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                        <input checked id="bordered-radio-2" type="radio" value="" name="kebersihan-tangan"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="bordered-radio-2"
                                        class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ya</label>
                                    </div>
                                </div>
                            </div>
                            <div class=" gap-3 flex flex-col border-b pb-4 border-gray-200 dark:border-gray-700">
                                <div class="font-semibold dark:text-white text-gray-700 text-xl ">Melakukan pemasangan CVC yang dilakukan di ruangan tindakan</div>
                                <div class="grid grid-cols-2 gap-4 w-full">
                                    <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                        <input id="bordered-radio-3" type="radio" value="" name="pemasangan-cvc"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="bordered-radio-3"
                                        class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                                    </div>
                                    <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                        <input checked id="bordered-radio-4" type="radio" value="" name="pemasangan-cvc"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="bordered-radio-4"
                                        class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ya</label>
                                    </div>
                                </div>
                            </div>
                            <div class=" gap-4 flex flex-col border-b pb-4 border-gray-200 dark:border-gray-700">
                                <div class="font-semibold dark:text-white text-gray-700 text-xl ">Menggunakan Alat Pelindung Diri (APD)</div>
                                <div class=" gap-3 flex flex-col pl-8">
                                    <div class=" dark:text-white text-gray-700 text-xl ">Menggunakan topi, masker, gaun steril, dan sarung tangan steril</div>
                                    <div class="grid grid-cols-2 gap-4 w-full">
                                        <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                            <input id="bordered-radio-5" type="radio" value="" name="pakaian-steril"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="bordered-radio-5"
                                            class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                                        </div>
                                        <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                            <input checked id="bordered-radio-6" type="radio" value="" name="pakaian-steril"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="bordered-radio-6"
                                            class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ya</label>
                                        </div>
                                    </div>
                                    <div class=" dark:text-white text-gray-700 text-xl ">Memasang Drape (Doek bolong steril) pada area insersi</div>
                                    <div class="grid grid-cols-2 gap-4 w-full">
                                        <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                            <input id="bordered-radio-7" type="radio" value="" name="pemasangan-drape"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="bordered-radio-7"
                                            class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                                        </div>
                                        <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                            <input checked id="bordered-radio-8" type="radio" value="" name="pemasangan-drape"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="bordered-radio-8"
                                            class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ya</label>
                                        </div>
                                    </div>
                                </div>          
                            </div>
                            <div class=" gap-3 flex flex-col border-b pb-4 border-gray-200 dark:border-gray-700">
                                <div class="font-semibold dark:text-white text-gray-700 text-xl ">Menggunakan cairan antiseptik (alkohol 70% atau chlorexidine 2-4%) untuk area kulit sekitar insersi</div>
                                <div class="grid grid-cols-2 gap-4 w-full">
                                    <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                        <input id="bordered-radio-9" type="radio" value="" name="penggunaan-antiseptik"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="bordered-radio-9"
                                        class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                                    </div>
                                    <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                        <input checked id="bordered-radio-10" type="radio" value="" name="penggunaan-antiseptik"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="bordered-radio-10"
                                        class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ya</label>
                                    </div>
                                </div>
                            </div>
                            <div class=" gap-3 flex flex-col border-b pb-4 border-gray-200 dark:border-gray-700">
                                <div class="font-semibold dark:text-white text-gray-700 text-xl ">Memilih lokasi insersi kateter yang beresiko rendah</div>
                                <div class="grid grid-cols-2 gap-4 w-full">
                                    <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                        <input id="bordered-radio-11" type="radio" value="" name="lokasi-kateter"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="bordered-radio-11"
                                        class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                                    </div>
                                    <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                        <input checked id="bordered-radio-12" type="radio" value="" name="lokasi-kateter"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="bordered-radio-12"
                                        class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ya</label>
                                    </div>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6 text-green-800">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>

                                </div>
                                <div>
                                    <span class="text-lg font-semibold text-gray-900">Bundle Maintenance</span>
                                    <div class="text-sm text-gray-500 mt-1">Prosedur perawatan harian kateter untuk cegah infeksi</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">8 Pertanyaan</div>
                                <svg class="w-6 h-6 text-gray-400 transform transition-transform duration-300" id="arrow-linen" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div id="linen" class="hidden border-t border-gray-100">
                        <div class="p-6 bg-gray-50 space-y-3">
                            <div class=" gap-3 flex flex-col border-b pb-4 border-gray-200 dark:border-gray-700">
                                <div class="font-semibold dark:text-white text-gray-700 text-xl ">Mengobservasi rutin kateter vena sentral setiap hari</div>
                                <div class="grid grid-cols-2 gap-4 w-full">
                                    <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                        <input id="bordered-radio-13" type="radio" value="" name="observasi-kateter"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="bordered-radio-13"
                                        class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                                    </div>
                                    <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                        <input checked id="bordered-radio-14" type="radio" value="" name="observasi-kateter"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="bordered-radio-14"
                                        class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ya</label>
                                    </div>
                                </div>
                            </div>
                            <div class=" gap-3 flex flex-col border-b pb-4 border-gray-200 dark:border-gray-700">
                                <div class="font-semibold dark:text-white text-gray-700 text-xl ">Menggunakan handscone steril saat melakukan perawatan kateterisasi</div>
                                <div class="grid grid-cols-2 gap-4 w-full">
                                    <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                        <input id="bordered-radio-15" type="radio" value="" name="penggunaan-handscone"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="bordered-radio-15"
                                        class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                                    </div>
                                    <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                        <input checked id="bordered-radio-16" type="radio" value="" name="penggunaan-handscone"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="bordered-radio-16"
                                        class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ya</label>
                                    </div>
                                </div>
                            </div>
                            <div class=" gap-4 flex flex-col border-b pb-4 border-gray-200 dark:border-gray-700">
                                <div class="font-semibold dark:text-white text-gray-700 text-xl ">Perawatan Luka Kateterisasi</div>
                                <div class=" gap-3 flex flex-col pl-8">
                                    <div class=" dark:text-white text-gray-700 text-xl ">Menggunakan antiseptik pada daerah luka kateterisasi</div>
                                    <div class="grid grid-cols-2 gap-4 w-full">
                                        <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                            <input id="bordered-radio-17" type="radio" value="" name="antiseptik-kateterisasi"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="bordered-radio-17"
                                            class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                                        </div>
                                        <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                            <input checked id="bordered-radio-18" type="radio" value="" name="antiseptik-kateterisasi"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="bordered-radio-18"
                                            class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ya</label>
                                        </div>
                                    </div>
                                    <div class=" dark:text-white text-gray-700 text-xl ">Membersihkan kateter dan kulit di sekitar insersi dengan alkohol 70%</div>
                                    <div class="grid grid-cols-2 gap-4 w-full">
                                        <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                            <input id="bordered-radio-19" type="radio" value="" name="bersihkan-kateter"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="bordered-radio-19"
                                            class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                                        </div>
                                        <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                            <input checked id="bordered-radio-20" type="radio" value="" name="bersihkan-kateter"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="bordered-radio-20"
                                            class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ya</label>
                                        </div>
                                    </div>
                                    <div class=" dark:text-white text-gray-700 text-xl ">Tidak melakukan palpasi pada lokasi setelah kulit dibersihkan dengan antiseptik</div>
                                    <div class="grid grid-cols-2 gap-4 w-full">
                                        <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                            <input id="bordered-radio-21" type="radio" value="" name="tidak-palpasi"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="bordered-radio-21"
                                            class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                                        </div>
                                        <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                            <input checked id="bordered-radio-22" type="radio" value="" name="tidak-palpasi"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="bordered-radio-22"
                                            class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ya</label>
                                        </div>
                                    </div>
                                    <div class=" dark:text-white text-gray-700 text-xl ">Menggunakan transparan dressing untuk menutupi lokasi insersi</div>
                                    <div class="grid grid-cols-2 gap-4 w-full">
                                        <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                            <input id="bordered-radio-23" type="radio" value="" name="transparan-dressing"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="bordered-radio-23"
                                            class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                                        </div>
                                        <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                            <input checked id="bordered-radio-24" type="radio" value="" name="transparan-dressing"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="bordered-radio-24"
                                            class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ya</label>
                                        </div>
                                    </div>
                                    <div class=" dark:text-white text-gray-700 text-xl ">Menghindari sentuhan yang dapat mengontaminasi lokasi kateter saat mengganti verband</div>
                                    <div class="grid grid-cols-2 gap-4 w-full">
                                        <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                            <input id="bordered-radio-25" type="radio" value="" name="menghindari-sentuhan"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="bordered-radio-25"
                                            class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                                        </div>
                                        <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                            <input checked id="bordered-radio-26" type="radio" value="" name="menghindari-sentuhan"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="bordered-radio-26"
                                            class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ya</label>
                                        </div>
                                    </div>
                                </div>          
                            </div>
                            <div class=" gap-3 flex flex-col border-b pb-4 border-gray-200 dark:border-gray-700">
                                <div class="font-semibold dark:text-white text-gray-700 text-xl ">Membersihkan Treeway injeksi dengan alkohol 70% sebelum mengakses sistem</div>
                                <div class="grid grid-cols-2 gap-4 w-full">
                                    <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                        <input id="bordered-radio-27" type="radio" value="" name="membersihkan-treeway"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="bordered-radio-27"
                                        class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                                    </div>
                                    <div class="flex items-center w-full ps-4 bg-white border border-gray-200 rounded-md dark:border-gray-700">
                                        <input checked id="bordered-radio-28" type="radio" value="" name="membersihkan-treeway"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="bordered-radio-28"
                                        class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ya</label>
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
                    <select class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option>Alat Kesehatan</option>
                        <option>Linen</option>
                        <option>Floor Stock</option>
                        <option>Alat Rumah Tangga</option>
                        <option>Alat Keselamatan</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Barang</label>
                    <input type="text" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Masukkan nama barang">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Stok</label>
                    <input type="number" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="0">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Satuan</label>
                    <input type="text" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="unit, buah, box, dll">
                </div>
                
                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="closeAddItemModal()" class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition-colors duration-200">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-lg font-medium transition-all duration-200 transform hover:scale-105">
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
