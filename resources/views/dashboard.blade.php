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
<body class="min-h-full bg-gray-100 text-gray-800">
    @include('components.sidebar-navbar')
    <div class="p-4 sm:ml-64 text-black">
        <!-- Main Content -->
        <main class="flex-1 px-6 py-8 mt-8">
        <!-- Welcome -->
        <div class="flex items-center gap-6 mb-10">
            <img src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="Foto Profil" class="w-20 h-20 rounded-full border-2 border-green-500 shadow-md" />
            <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-1">Selamat Pagi, Abidzar!</h1>
            <p class="text-sm text-gray-500">Senin, 23 Juni 2025</p>
            </div>
        </div>

        <!-- Shortcut Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Jadwal Dinas -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition">
            <div class="text-green-500 text-4xl mb-3"><i class="fas fa-calendar-check"></i></div>
            <h2 class="text-lg font-semibold mb-2">Jadwal Dinas</h2>
            <p class="text-sm text-gray-600 mb-3">Atur shift dan kunjungan dinas dengan praktis.</p>
            <a href="#" class="text-green-600 font-medium hover:underline">Lihat Jadwal</a>
            </div>

            <!-- Logistik -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition">
            <div class="text-yellow-500 text-4xl mb-3"><i class="fas fa-boxes"></i></div>
            <a href="/manajemen-logistik"><h2 class="text-lg font-semibold mb-2">Manajemen Logistik</h2></a>
         
            <p class="text-sm text-gray-600 mb-3">Pantau stok, alat kesehatan, linen, dan lainnya.</p>
            <a href="#" class="text-yellow-600 font-medium hover:underline">Lihat Inventaris</a>
            </div>

            <!-- PPI -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition">
            <div class="text-blue-500 text-4xl mb-3"><i class="fas fa-shield-virus"></i></div>
            <h2 class="text-lg font-semibold mb-2">PPI</h2>
            <p class="text-sm text-gray-600 mb-3">Kegiatan pengendalian dan pencegahan infeksi.</p>
            <a href="#" class="text-blue-600 font-medium hover:underline">Lihat Monitoring</a>
            </div>
        </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
