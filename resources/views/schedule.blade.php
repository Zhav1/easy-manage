<!DOCTYPE html>
<html lang="en" class="h-full bg-white w-screen">
<head>
    <meta charset="UTF-8" />
    <!-- Ganti viewport meta untuk kontrol yang lebih baik -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Schedule - Catatan Pribadi</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/schedule.css') }}">
    <script src={{ asset('js/schedule.js') }} defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <style>
        /* Add these styles to your schedule.css if preferred */
        .special-case-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1;
            white-space: nowrap;
        }

        .special-case-high-risk {
            background-color: #fee2e2; /* Red-100 */
            color: #ef4444; /* Red-500 */
        }

        .special-case-complex {
            background-color: #e0f2fe; /* Blue-100 */
            color: #3b82f6; /* Blue-500 */
        }

        .special-case-rare {
            background-color: #fef3c7; /* Amber-100 */
            color: #f59e0b; /* Amber-500 */
        }
        .special-case-info {
            background-color: #dbeafe; /* Blue-100 */
            color: #2563eb; /* Blue-600 */
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.6rem;
            border-radius: 0.375rem; /* rounded-md */
            font-size: 0.75rem; /* text-xs */
            font-weight: 500; /* font-medium */
            line-height: 1;
        }
    </style>
</head>
<body class="min-h-full bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100">
    <script>
        window.authToken = "{{ session('token') }}";
    </script>
    @include('components.sidebar-navbar')
    <div class="p-4">
      <main class="md:pl-60 pr-5 flex-1 px-4 sm:px-6 py-4 sm:py-8 mt-8">
           <div class="glass-effect rounded-3xl p-4 sm:p-6 md:p-8 mb-4 sm:mb-6 md:mb-8 shadow-xl animate-fade-in">
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
            <div class="card-hover bg-white rounded-3xl shadow-xl p-8 mb-10 border border-gray-100 animate-fade-in">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">
                        <i class="fas fa-edit mr-3 text-emerald-500"></i>
                        Input Kegiatan Baru
                    </h2>
                    <p class="text-gray-500">Tambahkan kegiatan atau catatan baru ke dalam jadwal harian Anda</p>
                </div>
                
                <form class="space-y-8" id="privateScheduleForm">
                   <div class="grid grid-cols-1 gap-4 sm:gap-6 md:gap-8 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-clock mr-2 text-blue-500"></i>Tanggal & Jam
                            </label>
                            <input name="scheduled_at" type="datetime-local" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-blue-500 focus:border-blue-500 py-3 px-4 bg-gray-50" />
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-sun mr-2 text-amber-500"></i>Briefing Pagi
                            </label>
                            <select name="briefing" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-amber-500 focus:border-amber-500 py-3 px-4 bg-gray-50">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-users mr-2 text-orange-500"></i>Rapat
                            </label>
                            <select name="meeting" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-orange-500 focus:border-orange-500 py-3 px-4 bg-gray-50">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-eye mr-2 text-cyan-500"></i>Supervisi
                            </label>
                            <select name="supervision" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-cyan-500 focus:border-cyan-500 py-3 px-4 bg-gray-50">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user-md mr-2 text-purple-500"></i>Handover Pasien
                            </label>
                            <select name="handover" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-purple-500 focus:border-purple-500 py-3 px-4 bg-gray-50">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>Tugas Luar
                            </label>
                            <select name="external_task" id="external_task" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-red-500 focus:border-red-500 py-3 px-4 bg-gray-50" onchange="checkOther(this)">
                                <option value="" disabled selected>Pilih jenis tugas luar</option>
                                <option value="Webinar">Webinar</option>
                                <option value="Pelatihan">Pelatihan</option>
                                <option value="Seminar">Seminar</option>
                                <option value="other">Lainnya</option>
                            </select>
                            
                            <div id="other_input_container" class="hidden mt-2">
                                <input type="text" name="external_task_other" id="external_task_other" class="input-focus block w-full rounded-xl border-gray-200 shadow-sm focus:ring-red-500 focus:border-red-500 py-3 px-4 bg-gray-50" placeholder="Keterangan tugas luar">
                            </div>
                        </div>
                        
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-sticky-note mr-2 text-teal-500"></i>Catatan Laporan
                            </label>
                            <textarea name="note" rows="4" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-teal-500 focus:border-teal-500 py-3 px-4 bg-gray-50" placeholder="Tuliskan laporan atau catatan penting..."></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end pt-4">
                        <button type="button" id="submitScheduleBtn" class="btn-gradient inline-flex items-center px-8 py-4 text-white rounded-xl shadow-lg font-semibold text-lg">
                            <i class="fas fa-save mr-3"></i>Simpan Catatan
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-hover bg-white rounded-3xl shadow-xl p-8 mt-10 border border-gray-100 animate-fade-in">
                            <div class="mb-6">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                                    <i class="fas fa-user-injured mr-3 text-red-500"></i>
                                    Input Pasien/Kasus Butuh Perhatian Khusus
                                </h2>
                                <p class="text-gray-500">Tambahkan detail pasien atau kasus yang membutuhkan perhatian khusus.</p>
                            </div>
                            
                            <form class="space-y-8" id="specialCaseForm">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>Tanggal Kasus
                                        </label>
                                        <input name="case_date" type="datetime-local" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-blue-500 focus:border-blue-500 py-3 px-4 bg-gray-50" required />
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fas fa-user-injured mr-2 text-red-500"></i>Nama Pasien
                                        </label>
                                        <input name="patient_name" type="text" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-red-500 focus:border-red-500 py-3 px-4 bg-gray-50" placeholder="Nama lengkap pasien" required />
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fas fa-procedures mr-2 text-purple-500"></i>Jenis Kasus
                                        </label>
                                        <select name="case_type" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-purple-500 focus:border-purple-500 py-3 px-4 bg-gray-50" required>
                                            <option value="" disabled selected>Pilih jenis kasus</option>
                                            <option value="Resiko Tinggi">Resiko Tinggi</option>
                                            <option value="Kompleks">Kompleks</option>
                                            <option value="Kasus Langka">Kasus Langka</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fas fa-info-circle mr-2 text-cyan-500"></i>Detail Kasus
                                        </label>
                                        <textarea name="details" rows="3" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-cyan-500 focus:border-cyan-500 py-3 px-4 bg-gray-50" placeholder="Jelaskan detail kasus (misal: riwayat penyakit, kondisi medis)"></textarea>
                                    </div>
                                    
                                    <div class="md:col-span-2 space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fas fa-stethoscope mr-2 text-green-500"></i>Tindakan yang Telah Dilakukan
                                        </label>
                                        <textarea name="action_taken" rows="4" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-green-500 focus:border-green-500 py-3 px-4 bg-gray-50" placeholder="Tuliskan tindakan atau penanganan yang sudah diberikan"></textarea>
                                    </div>
                                </div>
                                
                                <div class="flex justify-end pt-4">
                                    <button type="button" id="submitSpecialCaseBtn" class="btn-gradient inline-flex items-center px-8 py-4 text-white rounded-xl shadow-lg font-semibold text-lg bg-red-500 hover:bg-red-600">
                                        <i class="fas fa-plus-circle mr-3"></i>Tambah Kasus Khusus
                                    </button>
                                </div>
                            </form>
            </div>
            <div class="card-hover bg-white rounded-3xl shadow-xl mt-10 p-8 border border-gray-100 animate-fade-in">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">
                        <i class="fas fa-table mr-3 text-emerald-500"></i>
                        Riwayat Kegiatan
                    </h2>
                    <p class="text-gray-500">Daftar kegiatan yang telah diinput sebelumnya</p>
                </div>
                
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-4 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                    <i class="fas fa-calendar mr-2 text-blue-500"></i>Tanggal
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                    <i class="fas fa-sun mr-2 text-amber-500"></i>Briefing
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                    <i class="fas fa-users mr-2 text-orange-500"></i>Rapat
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                    <i class="fas fa-eye mr-2 text-cyan-500"></i>Supervisi
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                    <i class="fas fa-user-md mr-2 text-purple-500"></i>Handover
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>Tugas Luar
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                    <i class="fas fa-sticky-note mr-2 text-teal-500"></i>Catatan
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                    <i class="fas fa-cog mr-2 text-purple-500"></i>Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="scheduleTableBody">
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
            <div class="card-hover bg-white rounded-3xl shadow-xl flex-1 p-8 mt-10 border border-gray-100 animate-fade-in ">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">
                        <i class="fas fa-exclamation-triangle mr-3 text-yellow-500"></i>
                        Pasien/Kasus Butuh Perhatian Khusus
                    </h2>
                    <p class="text-gray-500">Daftar pasien atau kasus yang membutuhkan perhatian khusus</p>
                </div>
                
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-4 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                    <i class="fas fa-calendar mr-2 text-blue-500"></i>Tanggal
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                    <i class="fas fa-user-injured mr-2 text-red-500"></i>Nama Pasien
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                    <i class="fas fa-procedures mr-2 text-purple-500"></i>Jenis Kasus
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                    <i class="fas fa-info-circle mr-2 text-cyan-500"></i>Detail
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                    <i class="fas fa-stethoscope mr-2 text-green-500"></i>Tindakan
                                </th>
                                <th class="px-4 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                    <i class="fas fa-cog mr-2 text-purple-500"></i>Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="specialCasesTableBody">
                            </tbody>
                    </table>
                </div>
                
                <div class="mt-6 p-4 bg-yellow-50 rounded-xl border border-yellow-200">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-yellow-500 mr-3"></i>
                        <p class="text-yellow-700 text-sm">
                            <span class="font-semibold">Catatan:</span> Tabel ini menampilkan pasien atau kasus yang membutuhkan perhatian dan penanganan khusus dari tim medis.
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="scheduleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl shadow-xl p-8 w-full max-w-2xl">
            <div class="flex justify-between items-center mb-6">
                <h2 id="modalTitle" class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-edit mr-3 text-emerald-500"></i>
                    Edit Jadwal Kegiatan
                </h2>
                <button onclick="closePrivateScheduleModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="modalPrivateScheduleForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-clock mr-2 text-blue-500"></i>Tanggal & Jam
                        </label>
                        <input name="scheduled_at" type="datetime-local" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-blue-500 focus:border-blue-500 py-3 px-4 bg-gray-50" required />
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-sun mr-2 text-amber-500"></i>Briefing Pagi
                        </label>
                        <select name="briefing" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-amber-500 focus:border-amber-500 py-3 px-4 bg-gray-50">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-users mr-2 text-orange-500"></i>Rapat
                        </label>
                        <select name="meeting" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-orange-500 focus:border-orange-500 py-3 px-4 bg-gray-50">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-eye mr-2 text-cyan-500"></i>Supervisi
                        </label>
                        <select name="supervision" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-cyan-500 focus:border-cyan-500 py-3 px-4 bg-gray-50">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user-md mr-2 text-purple-500"></i>Handover Pasien
                        </label>
                        <select name="handover" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-purple-500 focus:border-purple-500 py-3 px-4 bg-gray-50">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>Tugas Luar
                        </label>
                        <select name="external_task" id="modal_external_task" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-red-500 focus:border-red-500 py-3 px-4 bg-gray-50" onchange="checkOther(this)">
                            <option value="" disabled selected>Pilih jenis tugas luar</option>
                            <option value="Webinar">Webinar</option>
                            <option value="Pelatihan">Pelatihan</option>
                            <option value="Seminar">Seminar</option>
                            <option value="other">Lainnya</option>
                        </select>
                        <div id="modal_other_input_container" class="hidden mt-2">
                            <input type="text" name="external_task_other" id="modal_external_task_other" class="input-focus block w-full rounded-xl border-gray-200 shadow-sm focus:ring-red-500 focus:border-red-500 py-3 px-4 bg-gray-50" placeholder="Keterangan tugas luar">
                        </div>
                    </div>
                    
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-sticky-note mr-2 text-teal-500"></i>Catatan Laporan
                        </label>
                        <textarea name="note" rows="4" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-teal-500 focus:border-teal-500 py-3 px-4 bg-gray-50" placeholder="Tuliskan laporan atau catatan penting..."></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end pt-4 space-x-3">
                    <button type="button" id="deleteBtn" onclick="confirmDeletePrivateSchedule(this.getAttribute('data-id'))" class="btn-delete inline-flex items-center px-6 py-3 text-white rounded-xl shadow-lg font-semibold text-lg bg-red-500 hover:bg-red-600 hidden">
                        <i class="fas fa-trash-alt mr-3"></i>Hapus
                    </button>
                    <button type="button" onclick="closePrivateScheduleModal()" class="btn-cancel inline-flex items-center px-6 py-3 text-gray-700 rounded-xl shadow-lg font-semibold text-lg border border-gray-300">
                        <i class="fas fa-times mr-3"></i>Batal
                    </button>
                    <button type="submit" id="modalSubmitBtn" class="btn-gradient inline-flex items-center px-6 py-3 text-white rounded-xl shadow-lg font-semibold text-lg">
                        <i class="fas fa-save mr-3"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="specialCaseModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl shadow-xl p-8 w-full max-w-2xl">
            <div class="flex justify-between items-center mb-6">
                <h2 id="modalSpecialCaseTitle" class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-edit mr-3 text-red-500"></i>
                    Edit Kasus Perhatian Khusus
                </h2>
                <button onclick="closeSpecialCaseModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="modalSpecialCaseForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>Tanggal Kasus
                        </label>
                        <input name="case_date" type="datetime-local" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-blue-500 focus:border-blue-500 py-3 px-4 bg-gray-50" required />
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user-injured mr-2 text-red-500"></i>Nama Pasien
                        </label>
                        <input name="patient_name" type="text" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-red-500 focus:border-red-500 py-3 px-4 bg-gray-50" placeholder="Nama lengkap pasien" required />
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-procedures mr-2 text-purple-500"></i>Jenis Kasus
                        </label>
                        <select name="case_type" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-purple-500 focus:border-purple-500 py-3 px-4 bg-gray-50" required>
                            <option value="" disabled selected>Pilih jenis kasus</option>
                            <option value="Resiko Tinggi">Resiko Tinggi</option>
                            <option value="Kompleks">Kompleks</option>
                            <option value="Kasus Langka">Kasus Langka</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-info-circle mr-2 text-cyan-500"></i>Detail Kasus
                        </label>
                        <textarea name="details" rows="3" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-cyan-500 focus:border-cyan-500 py-3 px-4 bg-gray-50" placeholder="Jelaskan detail kasus (misal: riwayat penyakit, kondisi medis)"></textarea>
                    </div>
                    
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-stethoscope mr-2 text-green-500"></i>Tindakan yang Telah Dilakukan
                        </label>
                        <textarea name="action_taken" rows="4" class="input-focus mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:ring-green-500 focus:border-green-500 py-3 px-4 bg-gray-50" placeholder="Tuliskan tindakan atau penanganan yang sudah diberikan"></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end pt-4 space-x-3">
                    <button type="button" id="deleteSpecialCaseBtn" onclick="confirmDeleteSpecialCase(this.getAttribute('data-id'))" class="btn-delete inline-flex items-center px-6 py-3 text-white rounded-xl shadow-lg font-semibold text-lg bg-red-500 hover:bg-red-600 hidden">
                        <i class="fas fa-trash-alt mr-3"></i>Hapus
                    </button>
                    <button type="button" onclick="closeSpecialCaseModal()" class="btn-cancel inline-flex items-center px-6 py-3 text-gray-700 rounded-xl shadow-lg font-semibold text-lg border border-gray-300">
                        <i class="fas fa-times mr-3"></i>Batal
                    </button>
                    <button type="submit" id="modalSubmitSpecialCaseBtn" class="btn-gradient inline-flex items-center px-6 py-3 text-white rounded-xl shadow-lg font-semibold text-lg bg-red-500 hover:bg-red-600">
                        <i class="fas fa-save mr-3"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="global-loading-overlay" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-[9999] hidden">
        <div class="flex flex-col items-center space-y-4">
            <div class="relative w-20 h-20">
                <div class="absolute inset-0 border-4 border-blue-400 border-t-blue-600 rounded-full animate-spin"></div>
                <div class="absolute inset-2 border-4 border-white border-t-white rounded-full animate-spin-reverse" style="animation-duration: 1.5s;"></div>
                <div class="absolute inset-4 border-4 border-purple-400 border-t-purple-600 rounded-full animate-spin" style="animation-duration: 2s;"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="fas fa-sync-alt text-white text-3xl animate-pulse"></i>
                </div>
            </div>
            <p class="text-white text-lg font-semibold animate-pulse">Loading Data...</p>
        </div>
    </div>
    <div id="notification-container" class="fixed top-4 right-4 z-[99999] space-y-2">
    </div>
</body>
</html>