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
<body class="min-h-full">
    @include('components.sidebar-navbar')
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed flex flex-col gap-8 rounded-lg dark:border-gray-700 mt-14">
            <!-- Breadcrumb -->
            <nav class="flex px-5 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                <a href="#" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                    <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    Dashboard
                </a>
                </li>
                <li class="inline-flex items-center">
                    <a href="#" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                    <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ms-1 text-sm font-medium text-gray-700 md:ms-2 dark:text-gray-400">Pengendalian dan Pencegahan Infeksi</span>
                    </a>
                </li>
                <li aria-current="page">
                <div class="flex items-center">
                    <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Bundle Maintenance RSUP Adam Malik</span>
                </div>
                </li>
            </ol>
            </nav>
            <div class="font-medium dark:text-white text-black text-3xl">Bundle Maintenance RSUP Adam Malik</div>
            <main class="p-4 h-auto">
                <div class="grid grid-cols-2 sm:grid-cols-1 gap-8 mb-4">
                    <div class=" rounded-lg gap-3 flex flex-col">
                        <div class="text-bold dark:text-white text-gray-700 text-xl ">Mengobservasi rutin kateter vena sentral setiap hari</div>
                        <div class="grid grid-cols-2 gap-4 w-full">
                            <div class="flex items-center w-full ps-4 border border-gray-200 rounded-md dark:border-gray-700">
                                <input id="bordered-radio-13" type="radio" value="" name="observasi-kateter"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="bordered-radio-13"
                                class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                            </div>
                            <div class="flex items-center w-full ps-4 border border-gray-200 rounded-md dark:border-gray-700">
                                <input checked id="bordered-radio-14" type="radio" value="" name="observasi-kateter"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="bordered-radio-14"
                                class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ya</label>
                            </div>
                        </div>
                    </div>
                    <div class=" rounded-lg gap-3 flex flex-col">
                        <div class="text-bold dark:text-white text-gray-700 text-xl ">Menggunakan handscone steril saat melakukan perawatan kateterisasi</div>
                        <div class="grid grid-cols-2 gap-4 w-full">
                            <div class="flex items-center w-full ps-4 border border-gray-200 rounded-md dark:border-gray-700">
                                <input id="bordered-radio-15" type="radio" value="" name="penggunaan-handscone"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="bordered-radio-15"
                                class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                            </div>
                            <div class="flex items-center w-full ps-4 border border-gray-200 rounded-md dark:border-gray-700">
                                <input checked id="bordered-radio-16" type="radio" value="" name="penggunaan-handscone"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="bordered-radio-16"
                                class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ya</label>
                            </div>
                        </div>
                    </div>
                    <div class=" rounded-lg gap-3 flex flex-col">
                        <div class="text-bold dark:text-white text-gray-700 text-xl ">Menggunakan topi, masker, gaun steril, dan sarung tangan steril</div>
                        <div class="grid grid-cols-2 gap-4 w-full">
                            <div class="flex items-center w-full ps-4 border border-gray-200 rounded-md dark:border-gray-700">
                                <input id="bordered-radio-5" type="radio" value="" name="pakaian-steril"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="bordered-radio-5"
                                class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                            </div>
                            <div class="flex items-center w-full ps-4 border border-gray-200 rounded-md dark:border-gray-700">
                                <input checked id="bordered-radio-6" type="radio" value="" name="pakaian-steril"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="bordered-radio-6"
                                class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ya</label>
                            </div>
                        </div>
                    </div>
                    <div class=" rounded-lg gap-3 flex flex-col">
                        <div class="text-bold dark:text-white text-gray-700 text-xl ">Memasang Drape (Doek bolong steril) pada area insersi</div>
                        <div class="grid grid-cols-2 gap-4 w-full">
                            <div class="flex items-center w-full ps-4 border border-gray-200 rounded-md dark:border-gray-700">
                                <input id="bordered-radio-7" type="radio" value="" name="pemasangan-drape"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="bordered-radio-7"
                                class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                            </div>
                            <div class="flex items-center w-full ps-4 border border-gray-200 rounded-md dark:border-gray-700">
                                <input checked id="bordered-radio-8" type="radio" value="" name="pemasangan-drape"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="bordered-radio-8"
                                class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ya</label>
                            </div>
                        </div>
                    </div>
                    <div class=" rounded-lg gap-3 flex flex-col">
                        <div class="text-bold dark:text-white text-gray-700 text-xl ">Menggunakan cairan antiseptik (alkohol 70% atau chlorexidine 2-4%) untuk area kulit sekitar insersi</div>
                        <div class="grid grid-cols-2 gap-4 w-full">
                            <div class="flex items-center w-full ps-4 border border-gray-200 rounded-md dark:border-gray-700">
                                <input id="bordered-radio-9" type="radio" value="" name="penggunaan-antiseptik"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="bordered-radio-9"
                                class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                            </div>
                            <div class="flex items-center w-full ps-4 border border-gray-200 rounded-md dark:border-gray-700">
                                <input checked id="bordered-radio-10" type="radio" value="" name="penggunaan-antiseptik"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="bordered-radio-10"
                                class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ya</label>
                            </div>
                        </div>
                    </div>
                    <div class=" rounded-lg gap-3 flex flex-col">
                        <div class="text-bold dark:text-white text-gray-700 text-xl ">Memilih lokasi insersi kateter yang beresiko rendah</div>
                        <div class="grid grid-cols-2 gap-4 w-full">
                            <div class="flex items-center w-full ps-4 border border-gray-200 rounded-md dark:border-gray-700">
                                <input id="bordered-radio-11" type="radio" value="" name="lokasi-kateter"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="bordered-radio-11"
                                class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                            </div>
                            <div class="flex items-center w-full ps-4 border border-gray-200 rounded-md dark:border-gray-700">
                                <input checked id="bordered-radio-12" type="radio" value="" name="lokasi-kateter"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="bordered-radio-12"
                                class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ya</label>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
            </main>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
