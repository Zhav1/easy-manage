<!DOCTYPE html>
<html lang="en" class="h-full bg-white w-screen">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Schedule - Catatan Pribadi</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/schedule.css') }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</head>
<body class="min-h-full bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 text-gray-800">
  @include('components.sidebar-navbar')
  <div class="p-4">
    <main class="pl-60 pr-5 flex-1 px-6 py-8 mt-8">
      <div class="glass-effect rounded-3xl p-8 mb-8 shadow-xl animate-fade-in">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-4xl font-bold text-black mb-3">
              <i class="fas fa-calendar-alt mr-3 text-green-500"></i>
              Catatan Pribadi & Jadwal Harian
            </h1>
            <p class="text-gray-600 text-lg">Input dan kelola kegiatan harian untuk pengingat, pelaporan, dan supervisi.</p>
          </div>
          <div class="hidden md:block">
            <div class="w-24 h-24 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg">
              <i class="fas fa-clipboard-list text-white text-3xl"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Input Manual Form -->
      <div class="card-hover bg-white rounded-3xl shadow-xl p-8 mb-10 border border-gray-100 animate-fade-in">
        <div class="mb-6">
          <h2 class="text-2xl font-bold text-gray-800 mb-2">
            <i class="fas fa-edit mr-3 text-emerald-500"></i>
            Input Kegiatan Baru
          </h2>
          <p class="text-gray-500">Tambahkan kegiatan atau catatan baru ke dalam jadwal harian Anda</p>
        </div>
        
        <form class="space-y-8">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-clock mr-2 text-blue-500"></i>Tanggal & Jam
              </label>
              <input type="datetime-local" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-blue-500 focus:border-blue-500 py-3 px-4 bg-gray-50" />
            </div>
            
            <div class="space-y-2">
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-sun mr-2 text-amber-500"></i>Briefing Pagi
              </label>
              <select class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-amber-500 focus:border-amber-500 py-3 px-4 bg-gray-50">
                <option>Ya</option>
                <option>Tidak</option>
              </select>
            </div>
            
            <div class="space-y-2">
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-users mr-2 text-orange-500"></i>Rapat
              </label>
              <select class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-orange-500 focus:border-orange-500 py-3 px-4 bg-gray-50">
                <option>Ya</option>
                <option>Tidak</option>
              </select>
            </div>
            
            <div class="space-y-2">
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-eye mr-2 text-cyan-500"></i>Supervisi
              </label>
              <select class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-cyan-500 focus:border-cyan-500 py-3 px-4 bg-gray-50">
                <option>Ya</option>
                <option>Tidak</option>
              </select>
            </div>
            
            <div class="space-y-2">
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-user-md mr-2 text-purple-500"></i>Handover Pasien
              </label>
              <select class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-purple-500 focus:border-purple-500 py-3 px-4 bg-gray-50">
                <option>Ya</option>
                <option>Tidak</option>
              </select>
            </div>
            
            <div class="space-y-2">
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>Tugas Luar
              </label>
              <input type="text" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-red-500 focus:border-red-500 py-3 px-4 bg-gray-50" placeholder="Keterangan tugas luar" />
            </div>
            
            <div class="md:col-span-2 space-y-2">
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-sticky-note mr-2 text-teal-500"></i>Catatan Laporan
              </label>
              <textarea rows="4" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-teal-500 focus:border-teal-500 py-3 px-4 bg-gray-50" placeholder="Tuliskan laporan atau catatan penting..."></textarea>
            </div>
          </div>
          
          <div class="flex justify-end pt-4">
            <button type="submit" class="btn-gradient inline-flex items-center px-8 py-4 text-white rounded-xl shadow-lg font-semibold text-lg">
              <i class="fas fa-save mr-3"></i>Simpan Catatan
            </button>
          </div>
        </form>
      </div>

      <!-- Tabel Input Manual -->
      <div class="card-hover bg-white rounded-3xl shadow-xl p-8 border border-gray-100 animate-fade-in">
        <div class="mb-6">
          <h2 class="text-2xl font-bold text-gray-800 mb-2">
            <i class="fas fa-table mr-3 text-emerald-500"></i>
            Riwayat Kegiatan
          </h2>
          <p class="text-gray-500">Daftar kegiatan yang telah diinput sebelumnya</p>
        </div>
        
        <div class="overflow-hidden rounded-xl border border-gray-200">
          <table class="min-w-full">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
              <tr>
                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">
                  <i class="fas fa-calendar mr-2 text-blue-500"></i>Tanggal
                </th>
                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">
                  <i class="fas fa-tasks mr-2 text-green-500"></i>Kegiatan
                </th>
                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">
                  <i class="fas fa-info-circle mr-2 text-orange-500"></i>Keterangan
                </th>
                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">
                  <i class="fas fa-cog mr-2 text-purple-500"></i>Aksi
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr class="hover:bg-gray-50 transition-colors duration-200">
                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                  <span class="status-badge bg-gray-100 text-gray-600">
                    <i class="fas fa-minus mr-1"></i>Tidak ada data
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                  <span class="status-badge bg-gray-100 text-gray-600">
                    <i class="fas fa-minus mr-1"></i>Tidak ada data
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                  <span class="status-badge bg-gray-100 text-gray-600">
                    <i class="fas fa-minus mr-1"></i>Tidak ada data
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <button class="inline-flex items-center px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
          <div class="flex items-center">
            <i class="fas fa-info-circle text-blue-500 mr-3"></i>
            <p class="text-blue-700 text-sm">
              <span class="font-semibold">Tips:</span> Data kegiatan yang Anda input akan muncul di tabel ini. Gunakan tombol Edit untuk mengubah data yang sudah tersimpan.
            </p>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>