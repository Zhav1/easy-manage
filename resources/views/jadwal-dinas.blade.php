<!DOCTYPE html>
<html lang="en" class="h-full bg-white w-screen">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MovieStack</title>
    <script src={{ asset('js/dinas.js') }}></script>
    <style src={{ asset('css/dinas.css') }}></style>
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
    <div class="p-4 pt-20 pl-60 pr-5 animate-fadeIn">
        <div class="p-6 border border-gray-200 rounded-xl shadow-lg bg-white/80 backdrop-blur-sm dark:border-gray-700 dark:bg-gray-800/80">
            <!-- Enhanced Header with Animation -->
            <div class="text-center mb-10">
                <div class="inline-block p-4  transform hover:scale-105 transition-all duration-300">
                    <h1 class="text-3xl font-bold text-green-500 tracking-wide">Jadwal Dinas</h1>
                </div>
                
               <!-- logo -->
                <div class="flex justify-center">
                <img src="{{ asset('images/icon-jadwal-piket.png') }}" alt="Logo Jadwal Dinas"
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
                                    <span class="text-lg font-semibold text-gray-900">Tambah Jadwal Dinas</span>
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
                            <form>
                <div class="grid gap-6 mb-6 md:grid-cols-2">
                    <div>
                        <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Rumah Sakit</label>
                        <input type="text" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="John" required />
                    </div>
                    <div>
                        <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Ruangan</label>
                        <input type="text" id="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Doe" required />
                    </div>
                    <div class="pt-5 border-t border-gray-200 dark:border-gray-800 flex sm:flex-row flex-col sm:space-x-5 rtl:space-x-reverse">
                        <div  inline-datepicker datepicker-buttons datepicker-autoselect-today datepicker-today-btn-classes="!bg-green-600 hover:!bg-green-700 text-white dark:!bg-green-700 dark:hover:!bg-green-800" class="mx-auto sm:mx-0"></div>
                        <div class="sm:ms-7 sm:ps-5 sm:border-s border-gray-200 dark:border-gray-800 w-full sm:max-w-[15rem] mt-5 sm:mt-0">
                            <h3 class="text-gray-900 dark:text-white text-base font-medium mb-3 text-center">Wednesday 30 June 2024</h3>
                            <button type="button" data-collapse-toggle="timetable" class="inline-flex items-center w-full py-2 px-5 me-2 justify-center text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-green-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                <svg class="w-4 h-4 text-gray-800 dark:text-white me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                </svg>
                                Pick a time
                            </button>
                            <label class="sr-only">
                            Pick a time
                            </label>
                            <ul id="timetable" class="grid w-full grid-cols-2 gap-2 mt-5">
                                <li>
                                    <input type="radio" id="10-am" value="" class="hidden peer" name="timetable">
                                    <label for="10-am"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    10:00 AM
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="10-30-am" value="" class="hidden peer" name="timetable">
                                    <label for="10-30-am"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    10:30 AM
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="11-am" value="" class="hidden peer" name="timetable">
                                    <label for="11-am"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white  dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    11:00 AM
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="11-30-am" value="" class="hidden peer" name="timetable">
                                    <label for="11-30-am"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white  dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    11:30 AM
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="12-am" value="" class="hidden peer" name="timetable" checked>
                                    <label for="12-am"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white  dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    12:00 AM
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="12-30-pm" value="" class="hidden peer" name="timetable">
                                    <label for="12-30-pm"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white  dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    12:30 PM
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="1-pm" value="" class="hidden peer" name="timetable">
                                    <label for="1-pm"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white  dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    01:00 PM
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="1-30-pm" value="" class="hidden peer" name="timetable">
                                    <label for="1-30-pm"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white  dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    01:30 PM
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="2-pm" value="" class="hidden peer" name="timetable">
                                    <label for="2-pm"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white  dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    02:00 PM
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="2-30-pm" value="" class="hidden peer" name="timetable">
                                    <label for="2-30-pm"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white  dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    02:30 PM
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="3-pm" value="" class="hidden peer" name="timetable">
                                    <label for="3-pm"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white  dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    03:00 PM
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="3-30-pm" value="" class="hidden peer" name="timetable">
                                    <label for="3-30-pm"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white  dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    03:30 PM
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <button type="submit" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Submit</button>
            </form>
                        </div>
                    </div>
                </div>

                <!-- Jadwal Dinas -->
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
                                    <span class="text-lg font-semibold text-gray-900">Jadwal Dinas</span>
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
                        <div id="indicators-carousel" class="relative w-full" data-carousel="static">
                            <!-- Carousel wrapper -->
                            <div class="relative h-56 overflow-hidden rounded-lg h-full">
                                <!-- Item 1 -->
                                <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                                    <div class="bg-gradient-to-br  w-full from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                        <div class="flex items-center justify-between px-8">
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
                                </div>
                                <!-- Item 2 -->
                                <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                                    <div class="bg-gradient-to-br  w-full from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
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
                                </div>
                                <!-- Item 3 -->
                                <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                                    <div class="bg-gradient-to-br  w-full from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
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
                                </div>
                                <!-- Item 4 -->
                                <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                                    <div class="bg-gradient-to-br  w-full from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
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
                                </div>
                                <!-- Item 5 -->
                                <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                                    <div class="bg-gradient-to-br  w-full from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
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
                                </div>
                            </div>
                            <!-- Slider indicators -->
                            <div class="absolute z-30 flex -translate-x-1/2 space-x-3 rtl:space-x-reverse bottom-5 left-1/2">
                                <button type="button" class="w-3 h-3 rounded-full" aria-current="true" aria-label="Slide 1" data-carousel-slide-to="0"></button>
                                <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 2" data-carousel-slide-to="1"></button>
                                <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 3" data-carousel-slide-to="2"></button>
                                <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 4" data-carousel-slide-to="3"></button>
                                <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 5" data-carousel-slide-to="4"></button>
                            </div>
                            <!-- Slider controls -->
                            <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                    <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                                    </svg>
                                    <span class="sr-only">Previous</span>
                                </span>
                            </button>
                            <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                    <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <span class="sr-only">Next</span>
                                </span>
                            </button>
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
                <li aria-current="page">
                <div class="flex items-center">
                    <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Jadwal Dinas</span>
                </div>
                </li>
            </ol>
            </nav>
            <div class="font-medium dark:text-white text-black text-3xl">Tambah Jadwal Dinas</div>
            <form>
                <div class="grid gap-6 mb-6 md:grid-cols-2">
                    <div>
                        <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Rumah Sakit</label>
                        <input type="text" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="John" required />
                    </div>
                    <div>
                        <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Ruangan</label>
                        <input type="text" id="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Doe" required />
                    </div>
                    <div class="pt-5 border-t border-gray-200 dark:border-gray-800 flex sm:flex-row flex-col sm:space-x-5 rtl:space-x-reverse">
                        <div  inline-datepicker datepicker-buttons datepicker-autoselect-today datepicker-today-btn-classes="!bg-green-600 hover:!bg-green-700 text-white dark:!bg-green-700 dark:hover:!bg-green-800" class="mx-auto sm:mx-0"></div>
                        <div class="sm:ms-7 sm:ps-5 sm:border-s border-gray-200 dark:border-gray-800 w-full sm:max-w-[15rem] mt-5 sm:mt-0">
                            <h3 class="text-gray-900 dark:text-white text-base font-medium mb-3 text-center">Wednesday 30 June 2024</h3>
                            <button type="button" data-collapse-toggle="timetable" class="inline-flex items-center w-full py-2 px-5 me-2 justify-center text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-green-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                <svg class="w-4 h-4 text-gray-800 dark:text-white me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                </svg>
                                Pick a time
                            </button>
                            <label class="sr-only">
                            Pick a time
                            </label>
                            <ul id="timetable" class="grid w-full grid-cols-2 gap-2 mt-5">
                                <li>
                                    <input type="radio" id="10-am" value="" class="hidden peer" name="timetable">
                                    <label for="10-am"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    10:00 AM
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="10-30-am" value="" class="hidden peer" name="timetable">
                                    <label for="10-30-am"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    10:30 AM
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="11-am" value="" class="hidden peer" name="timetable">
                                    <label for="11-am"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white  dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    11:00 AM
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="11-30-am" value="" class="hidden peer" name="timetable">
                                    <label for="11-30-am"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white  dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    11:30 AM
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="12-am" value="" class="hidden peer" name="timetable" checked>
                                    <label for="12-am"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white  dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    12:00 AM
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="12-30-pm" value="" class="hidden peer" name="timetable">
                                    <label for="12-30-pm"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white  dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    12:30 PM
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="1-pm" value="" class="hidden peer" name="timetable">
                                    <label for="1-pm"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white  dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    01:00 PM
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="1-30-pm" value="" class="hidden peer" name="timetable">
                                    <label for="1-30-pm"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white  dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    01:30 PM
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="2-pm" value="" class="hidden peer" name="timetable">
                                    <label for="2-pm"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white  dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    02:00 PM
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="2-30-pm" value="" class="hidden peer" name="timetable">
                                    <label for="2-30-pm"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white  dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    02:30 PM
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="3-pm" value="" class="hidden peer" name="timetable">
                                    <label for="3-pm"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white  dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    03:00 PM
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="3-30-pm" value="" class="hidden peer" name="timetable">
                                    <label for="3-30-pm"
                                    class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-white border rounded-lg cursor-pointer text-green-600 border-green-600 dark:hover:text-white dark:border-blue-500 dark:peer-checked:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-600 hover:text-white peer-checked:text-white  dark:peer-checked:text-white hover:bg-green-500 dark:text-green-500 dark:bg-gray-900 dark:hover:bg-green-600 dark:hover:border-green-600 dark:peer-checked:bg-green-500">
                                    03:30 PM
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <button type="submit" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Submit</button>
            </form>

            <div class="font-medium dark:text-white text-black text-3xl">Jadwal Dinas</div>
            <div class="w-full p-4 text-left bg-white border border-gray-200 rounded-lg shadow-sm sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                <h5 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">RSUP Adam Malik</h5>
                <p class="mb-5 text-base text-gray-500 sm:text-lg dark:text-gray-400">Senin, 31 Juni 2025, 14:00 - 15:30</p>
                <div class="flex flex-row gap-6">
                <button type="button" class="w-full text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Red</button>
                <button type="button" class="w-full text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 shadow-lg shadow-green-500/50 dark:shadow-lg dark:shadow-green-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Green</button>
                </div>
            </div>
            <div class="w-full p-4 text-left bg-white border border-gray-200 rounded-lg shadow-sm sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                <h5 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">RSUP Adam Malik</h5>
                <p class="mb-5 text-base text-gray-500 sm:text-lg dark:text-gray-400">Senin, 31 Juni 2025, 14:00 - 15:30</p>
                <div class="flex flex-row gap-6">
                <button type="button" class="w-full text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Red</button>
                <button type="button" class="w-full text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 shadow-lg shadow-green-500/50 dark:shadow-lg dark:shadow-green-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Green</button>
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
