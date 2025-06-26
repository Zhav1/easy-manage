<!DOCTYPE html>
<html lang="en" class="h-full bg-white w-screen">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>TNA - Pendidikan & Pelatihan</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
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
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Training Need Assessment (TNA)</h1>
        <p class="text-gray-500 text-sm">Catat seminar, pelatihan, dan pendidikan lanjutan staf sebagai dasar perencanaan pengembangan SDM.</p>
      </div>

      <!-- Tabel TNA -->
      <div class="card-hover bg-white rounded-2xl shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4"><i class="fas fa-chalkboard-teacher mr-2 text-indigo-500"></i>Rekap Pendidikan & Pelatihan Staf</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm border border-gray-200">
            <thead class="bg-gray-100 text-gray-700">
              <tr>
                <th class="px-4 py-2 text-left">Nama</th>
                <th class="px-4 py-2 text-left">Seminar / Workshop / Webinar</th>
                <th class="px-4 py-2 text-left">Pelatihan</th>
                <th class="px-4 py-2 text-left">Pendidikan Lanjutan</th>
                <th class="px-4 py-2 text-left">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              <tr>
                <td class="px-4 py-2">Yanti</td>
                <td class="px-4 py-2">Webinar ICU Nasional 2024</td>
                <td class="px-4 py-2">Pelatihan Manajemen IGD</td>
                <td class="px-4 py-2">S2 Keperawatan</td>
                <td class="px-4 py-2">
                  <button class="text-indigo-600 hover:underline text-xs"><i class="fas fa-pen mr-1"></i>Edit</button>
                </td>
              </tr>
              <tr>
                <td class="px-4 py-2">Budi</td>
                <td class="px-4 py-2">Workshop Komunikasi Efektif</td>
                <td class="px-4 py-2">Pelatihan Basic Life Support</td>
                <td class="px-4 py-2">D3 Keperawatan</td>
                <td class="px-4 py-2">
                  <button class="text-indigo-600 hover:underline text-xs"><i class="fas fa-pen mr-1"></i>Edit</button>
                </td>
              </tr>
              <tr>
                <td class="px-4 py-2">Siti</td>
                <td class="px-4 py-2">Seminar Gizi Anak 2025</td>
                <td class="px-4 py-2">Pelatihan Keperawatan Anak</td>
                <td class="px-4 py-2">S1 Keperawatan</td>
                <td class="px-4 py-2">
                  <button class="text-indigo-600 hover:underline text-xs"><i class="fas fa-pen mr-1"></i>Edit</button>
                </td>
              </tr>
              <tr>
                <td class="px-4 py-2">Rina</td>
                <td class="px-4 py-2">Webinar Keselamatan Pasien</td>
                <td class="px-4 py-2">Pelatihan Pencegahan Infeksi</td>
                <td class="px-4 py-2">Belum Ada</td>
                <td class="px-4 py-2">
                  <button class="text-indigo-600 hover:underline text-xs"><i class="fas fa-pen mr-1"></i>Edit</button>
                </td>
              </tr>
              <tr>
                <td class="px-4 py-2">Anton</td>
                <td class="px-4 py-2">Seminar Manajemen Kritis</td>
                <td class="px-4 py-2">Pelatihan Leadership Tim</td>
                <td class="px-4 py-2">S2 Manajemen RS</td>
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
