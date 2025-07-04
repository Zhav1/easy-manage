<!DOCTYPE html>
<html lang="en" class="h-full bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 overflow-x-hidden  ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Logistik RS</title>
    <script src={{ asset('js/dashboard.js') }}></script>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <style>
    /* Chrome, Safari, Opera */
::-webkit-scrollbar {
  width: 0px;          /* hilang sama sekali */
  background: transparent;
}

/* Firefox */
html, body {
  scrollbar-width: none;      /* hilang di Firefox */
  -ms-overflow-style: none;   /* hilang di Edge lama/IE */
}

   </style>
   <script>
        if (localStorage.getItem('color-theme') === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
        }
    </script>
</head>
<body class="min-h-full  bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100">
    @include('components.sidebar-navbar')

    <div class="p-4 pt-20 pl-60 pr-5 animate-fadeIn">
        <div class="p-6 border border-gray-200 rounded-xl shadow-lg bg-white  backdrop-blur-sm dark:border-gray-700 dark:bg-gray-800/80">
            <!-- Header -->
            <div class="text-center mb-10">
                <div class="inline-block p-4 transform hover:scale-105 transition-all duration-300">
                    <h1 class="text-3xl font-bold text-black tracking-wide">Manajemen Logistik</h1>
                </div>
                
                <!-- Logo -->
                <div class="flex justify-center">
                    <img src="images/l1.png" alt="Logo Manajemen Logistik"
                         class="h-24 w-auto rounded-lg transition-transform duration-300 hover:scale-105" />
                </div>
            </div>

            <!-- Tombol Tambah Barang -->
           <!-- Tombol Tambah Barang dan Lihat Barang -->
<div class="mb-6 text-center space-x-4">
    <button onclick="openAddItemModal()" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-2 rounded-full font-medium transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
        <i class="fas fa-plus mr-2"></i> Tambahkan Barang
    </button>
    <button onclick="window.location.href='{{ route('logistics.index') }}'" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-2 rounded-full font-medium transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
        <i class="fas fa-eye mr-2"></i> Lihat Barang
    </button>
</div>
            <!-- Summary Cards -->
<!-- Summary Cards -->
<div class="grid md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold mb-2">Total Stok Tersedia</h3>
                <p class="text-3xl font-bold">{{ $totalStock }}</p>
                <p class="text-green-100 text-sm mt-1">Items dalam kondisi baik</p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold mb-2">Stok Terbatas</h3>
                <p class="text-3xl font-bold">{{ $limitedStock }}</p>
                <p class="text-yellow-100 text-sm mt-1">Perlu segera diisi ulang</p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold mb-2">Stok Menipis</h3>
                <p class="text-3xl font-bold">{{ $lowStock }}</p>
                <p class="text-red-100 text-sm mt-1">Butuh perhatian urgent</p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                <i class="fas fa-times-circle text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Kategori Logistik -->
<div class="space-y-6">
    @foreach(['Alat Medis', 'Alat Kesehatan', 'Linen', 'Floor Stock', 'Obat'] as $category)
        @php
            $items = \App\Models\Logistic::where('category', $category)
                ->where('department_id', auth()->user()->department_id)
                ->orderBy('item_name')
                ->limit(5)
                ->get();
            $count = \App\Models\Logistic::where('category', $category)
                ->where('department_id', auth()->user()->department_id)
                ->count();
        @endphp
        
        @if($count > 0)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
            <div class="p-6 cursor-pointer" onclick="toggleSection('{{ Str::slug($category) }}')">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                            <i class="fas 
                                @if($category == 'Alat Medis') fa-medkit 
                                @elseif($category == 'Alat Kesehatan') fa-stethoscope 
                                @elseif($category == 'Linen') fa-bed 
                                @elseif($category == 'Floor Stock') fa-boxes 
                                @elseif($category == 'Obat') fa-pills 
                                @endif
                                text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <span class="text-lg font-semibold text-gray-900">{{ $category }}</span>
                            <div class="text-sm text-gray-500 mt-1">
                                @if($category == 'Alat Medis') Medical Equipment 
                                @elseif($category == 'Alat Kesehatan') Health Tools 
                                @elseif($category == 'Linen') Textile & Bedding 
                                @elseif($category == 'Floor Stock') Floor Supplies 
                                @elseif($category == 'Obat') Medicines 
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">{{ $count }} Items</div>
                        <i class="fas fa-chevron-down text-gray-400 transform transition-transform duration-300" id="arrow-{{ Str::slug($category) }}"></i>
                    </div>
                </div>
            </div>
            <div id="{{ Str::slug($category) }}" class="hidden border-t border-gray-100">
                <div class="p-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Merk</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->item_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->brand ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->stock }} {{ $item->unit_of_measure }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->status == 'Tersedia')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Tersedia</span>
                                        @elseif($item->status == 'Terbatas')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Terbatas</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Menipis</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($count > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('logistics.index') }}?category={{ urlencode($category) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Lihat semua {{ $count }} item <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    @endforeach
</div>

                <!-- Tambahkan kategori lainnya dengan pola yang sama -->
            </div>
        </div>
    </div>

    <!-- Modal Tambah Barang -->
  <!-- Modal Tambah Barang -->
<div id="addItemModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-2xl w-full mx-4 shadow-2xl transform transition-all duration-300 overflow-y-auto" style="max-height: 90vh;">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-plus text-white text-2xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Tambahkan Barang/Alat Kesehatan</h2>
            <p class="text-gray-600 mt-2">Lengkapi data berikut untuk menambahkan inventaris baru</p>
        </div>
        
        <form action="{{ route('logistics.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom 1 -->
                <div class="space-y-4">
                    <!-- Unit/Bagian -->
                    {{-- <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            Unit/Bagian
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <select name="unit" class="w-full p-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                            <option value="" disabled selected>Pilih unit</option>
                            <option value="IGD" selected>IGD</option>
                            <option value="Rawat Inap">Rawat Inap</option>
                            <option value="Rawat Jalan">Rawat Jalan</option>
                            <option value="ICU">ICU</option>
                            <option value="Laboratorium">Laboratorium</option>
                            <option value="Radiologi">Radiologi</option>
                        </select>
                    </div> --}}
                    <input type="hidden" name="department_id" value="{{ auth()->user()->department_id }}">
                    
                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            Kategori
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <select name="category" id="category" class="w-full p-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                            <option value="" disabled selected>Pilih kategori</option>
                            <option value="Alat Medis" selected>Alat Medis</option>
                            <option value="Alat Kesehatan">Alat Kesehatan</option>
                            <option value="Linen">Linen</option>
                            <option value="Floor Stock">Floor Stock</option>
                            <option value="Obat">Obat</option>
                        </select>
                    </div>
                    
                    <!-- Nama Barang/Alat -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            Nama Barang/Alat
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" name="item_name" 
                               class="w-full p-3 border border-gray-300 rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-gray-800 focus:border-transparent" 
                               placeholder="Masukkan nama barang/alat" 
                               required
                               value="X">
                    </div>
                    
                    <!-- Merk -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Merk</label>
                        <input type="text" name="brand" 
                               class="w-full p-3 border border-gray-300 rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-gray-800 focus:border-transparent" 
                               placeholder="Masukkan merk barang"
                               value="X">
                    </div>
                </div>
                
                <!-- Kolom 2 -->
                <div class="space-y-4">
                    <!-- Kode Barang/SN -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Barang/SN</label>
                        <input type="text" name="item_code" 
                               class="w-full p-3 border border-gray-300 rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-gray-800 focus:border-transparent" 
                               placeholder="Masukkan kode barang/serial number"
                               value="S">
                    </div>
                    
                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="notes" rows="2" 
                                  class="w-full p-3 border border-gray-300 rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-gray-800 focus:border-transparent" 
                                  placeholder="Catatan tambahan...">Catatan tambahan...</textarea>
                    </div>
                    
                    <!-- Jumlah Stok -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            Jumlah Stok
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="number" name="stock" min="0" 
                               class="w-full p-3 border border-gray-300 rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-gray-800 focus:border-transparent" 
                               placeholder="0" 
                               required
                               value="4">
                    </div>
                    
                    <!-- Satuan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            Satuan
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <select name="unit_of_measure" class="w-full p-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                            <option value="" disabled>Pilih satuan</option>
                            <option value="unit" selected>Unit</option>
                            <option value="buah">Buah</option>
                            <option value="set">Set</option>
                            <option value="box">Box</option>
                            <option value="pack">Pack</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Baris Bawah (Tanggal Kalibrasi) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <!-- Kondisi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        Kondisi
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <select name="condition" class="w-full p-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                        <option value="Baik" selected>Baik</option>
                        <option value="Rusak Ringan">Rusak Ringan</option>
                        <option value="Rusak Berat">Rusak Berat</option>
                        <option value="Perlu Kalibrasi">Perlu Kalibrasi</option>
                    </select>
                </div>
                
                <!-- Tanggal Kalibrasi -->
                <div id="calibrationField">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kalibrasi</label>
                    <input type="date" name="calibration_date" 
                           class="w-full p-3 border border-gray-300 rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-gray-800 focus:border-transparent" 
                           placeholder="dd/mm/yyyy">
                </div>
                
                <!-- Kadaluarsa Kalibrasi -->
                <div id="expiryField" class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kadaluarsa Kalibrasi</label>
                    <input type="date" name="calibration_expiry_date" 
                           class="w-full p-3 border border-gray-300 rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-gray-800 focus:border-transparent" 
                           placeholder="dd/mm/yyyy">
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex space-x-3 pt-6">
                <button type="button" onclick="closeAddItemModal()" class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i> Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-lg font-medium transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i> Simpan Barang
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

        // Tampilkan field kalibrasi
        document.getElementById('category').addEventListener('change', function() {
            const calibrationField = document.getElementById('calibrationField');
            const expiryField = document.getElementById('expiryField');
            const selectedCategory = this.value;
            calibrationField?.classList.remove('hidden');
            expiryField?.classList.remove('hidden');
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

        .rotate-180 {
            transform: rotate(180deg);
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
        /* Responsive adjustments */
@media (max-width: 640px) {
    .fc-header-toolbar {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .fc-toolbar-chunk {
        margin-bottom: 0.5rem;
    }
    
    .fc-today-button {
        margin-top: 0.5rem;
    }
    
    .fc-col-header-cell-cushion {
        font-size: 0.7rem;
        padding: 2px;
    }
    
    .fc-daygrid-day-number {
        font-size: 0.7rem;
    }
    
    .fc-event-time, .fc-event-title {
        font-size: 0.6rem;
    }
}

/* Mobile sidebar adjustment */
@media (max-width: 768px) {
    .pl-60 {
        padding-left: 1rem;
    }
    .pr-5 {
        padding-right: 1rem;
    }
}



@media (max-width: 640px) {
    .staff-table th, .staff-table td {
        padding: 6px 4px;
        font-size: 0.75rem;
    }
}
    </style>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>