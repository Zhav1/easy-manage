<!DOCTYPE html>
<html lang="en" class="h-full bg-white w-screen">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Kinerja Staf</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
  <style>
    .card-hover {
      transition: all 0.3s ease;
    }
    .card-hover:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    .glass-effect {
      backdrop-filter: blur(16px) saturate(180%);
      background-color: rgba(255, 255, 255, 0.75);
      border: 1px solid rgba(209, 213, 219, 0.3);
    }
  </style>
</head>
<body class="min-h-full bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 text-gray-800">
    @include('components.sidebar-navbar')
  <div class="p-4">
    <main class="pl-60 pr-5  flex-1 px-6 py-8 mt-8">
      <div class="glass-effect rounded-2xl p-8 mb-8 shadow-lg">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Kinerja Staf</h1>
        <p class="text-gray-500 text-sm">Lihat dan catat penilaian kinerja staf Anda berdasarkan indikator yang tersedia.</p>
      </div>

      <!-- Tabel Kinerja Staf -->
      <div class="card-hover bg-white rounded-2xl shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4"><i class="fas fa-chart-line mr-2 text-purple-500"></i>Rekapitulasi Penilaian Staf</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm border border-gray-200">
            <thead class="bg-gray-100 text-gray-700">
              <tr>
                <th class="px-4 py-2 text-left">Nama</th>
                <th class="px-4 py-2 text-left">Kedisiplinan</th>
                <th class="px-4 py-2 text-left">Komunikasi</th>
                <th class="px-4 py-2 text-left">Komplain</th>
                <th class="px-4 py-2 text-left">Kepatuhan</th>
                <th class="px-4 py-2 text-left">Target Kerja</th>
                <th class="px-4 py-2 text-left">Keterangan</th>
                <th class="px-4 py-2 text-left">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              <tr>
                <td class="px-4 py-2">Yanti</td>
                <td class="px-4 py-2">Tepat Waktu</td>
                <td class="px-4 py-2">Baik</td>
                <td class="px-4 py-2">Tidak Ada</td>
                <td class="px-4 py-2">Sangat Patuh</td>
                <td class="px-4 py-2">Tercapai</td>
                <td class="px-4 py-2 text-green-600 font-semibold">Good Performance</td>
                <td class="px-4 py-2">
                  <button class="text-indigo-600 hover:underline text-xs"><i class="fas fa-pen mr-1"></i>Edit</button>
                </td>
              </tr>
              <tr>
                <td class="px-4 py-2">Budi</td>
                <td class="px-4 py-2">Sering Telat</td>
                <td class="px-4 py-2">Cukup</td>
                <td class="px-4 py-2">1 Komplain</td>
                <td class="px-4 py-2">Perlu Pendampingan</td>
                <td class="px-4 py-2">Belum Tercapai</td>
                <td class="px-4 py-2 text-red-600 font-semibold">Perlu Perbaikan</td>
                <td class="px-4 py-2">
                  <button class="text-indigo-600 hover:underline text-xs"><i class="fas fa-pen mr-1"></i>Edit</button>
                </td>
              </tr>
              <tr>
                <td class="px-4 py-2">Siti</td>
                <td class="px-4 py-2">Tepat Waktu</td>
                <td class="px-4 py-2">Sangat Baik</td>
                <td class="px-4 py-2">Tidak Ada</td>
                <td class="px-4 py-2">Patuh</td>
                <td class="px-4 py-2">Tercapai</td>
                <td class="px-4 py-2 text-green-600 font-semibold">Rajin & Inisiatif</td>
                <td class="px-4 py-2">
                  <button class="text-indigo-600 hover:underline text-xs"><i class="fas fa-pen mr-1"></i>Edit</button>
                </td>
              </tr>
              <tr>
                <td class="px-4 py-2">Rina</td>
                <td class="px-4 py-2">Kadang Terlambat</td>
                <td class="px-4 py-2">Cukup</td>
                <td class="px-4 py-2">2 Komplain</td>
                <td class="px-4 py-2">Sedang</td>
                <td class="px-4 py-2">Progres</td>
                <td class="px-4 py-2 text-yellow-600 font-semibold">Perlu Pendampingan</td>
                <td class="px-4 py-2">
                  <button class="text-indigo-600 hover:underline text-xs"><i class="fas fa-pen mr-1"></i>Edit</button>
                </td>
              </tr>
              <tr>
                <td class="px-4 py-2">Anton</td>
                <td class="px-4 py-2">Tepat Waktu</td>
                <td class="px-4 py-2">Baik</td>
                <td class="px-4 py-2">Tidak Ada</td>
                <td class="px-4 py-2">Sangat Patuh</td>
                <td class="px-4 py-2">Tercapai</td>
                <td class="px-4 py-2 text-green-600 font-semibold">Teladan</td>
                <td class="px-4 py-2">
                  <button class="text-indigo-600 hover:underline text-xs"><i class="fas fa-pen mr-1"></i>Edit</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
