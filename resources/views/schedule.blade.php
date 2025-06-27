<!DOCTYPE html>
<html lang="en" class="h-full bg-white w-screen">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Schedule - Catatan Pribadi</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
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
    <main class="pl-60 pr-5  flex-1 px-6 py-8 mt-8 ">
      <div class="glass-effect rounded-2xl p-8 mb-8 shadow-lg">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Catatan Pribadi & Jadwal Harian</h1>
        <p class="text-gray-500 text-sm">Input dan kelola kegiatan harian untuk pengingat, pelaporan, dan supervisi.</p>
      </div>

      <!-- Input Manual Form -->
      <div class="card-hover bg-white rounded-2xl shadow p-6 mb-10">
        <form class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700">Tanggal & Jam</label>
              <input type="datetime-local" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Briefing Pagi</label>
              <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
                <option>Ya</option>
                <option>Tidak</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Rapat</label>
              <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                <option>Ya</option>
                <option>Tidak</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Supervisi</label>
              <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option>Ya</option>
                <option>Tidak</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Handover Pasien</label>
              <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-purple-500 focus:border-purple-500">
                <option>Ya</option>
                <option>Tidak</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Tugas Luar</label>
              <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-rose-500 focus:border-rose-500" placeholder="Keterangan tugas luar" />
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700">Catatan Laporan</label>
              <textarea rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-sky-500 focus:border-sky-500" placeholder="Tuliskan laporan atau catatan penting..."></textarea>
            </div>
          </div>
          <div>
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
              <i class="fas fa-save mr-2"></i>Simpan Catatan
            </button>
          </div>
        </form>
      </div>

      <!-- Tabel Input Manual Kosong -->
      <div class="card-hover bg-white rounded-2xl shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4"><i class="fas fa-table mr-2 text-teal-500"></i>Input Manual Tambahan</h2>
        <table class="min-w-full text-sm">
          <thead class="bg-gray-100 text-gray-700">
            <tr>
              <th class="px-4 py-2">Tanggal</th>
              <th class="px-4 py-2">Kegiatan</th>
              <th class="px-4 py-2">Keterangan</th>
              <th class="px-4 py-2">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-t">
              <td class="px-4 py-2">-</td>
              <td class="px-4 py-2">-</td>
              <td class="px-4 py-2">-</td>
              <td class="px-4 py-2">
                <button class="text-blue-600 hover:underline text-xs"><i class="fas fa-edit mr-1"></i>Edit</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</body>
</html>
