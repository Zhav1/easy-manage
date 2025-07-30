<!DOCTYPE html>
<html lang="en" class="h-full bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 overflow-x-hidden  ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <title>Manajemen Logistik RS</title>
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
</head>
<body class="min-h-full  bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100">
    @include('components.sidebar-navbar')

   <div class="p-4 md:p-0 mt-8">
    <main class="md:pl-60 pr-5 flex-1 px-4 md:px-6 py-4 md:py-8 mt-0 md:mt-8">
        <!-- Hero -->
        <div class="glass-effect bg-white rounded-3xl p-6 md:p-8 mb-6 md:mb-8 shadow-xl">
            <div class="flex flex-row items-center gap-3">
                <img src="{{ asset('images/l1.png') }}" alt="Logo Manajemen Logistik"
                     class="h-10 w-auto rounded-lg transition-transform duration-300 hover:scale-105" />
                <h1 class="text-3xl md:text-4xl font-bold text-black tracking-wide">
                    Manajemen Logistik
                </h1>
            </div>
            <p class="text-gray-600 text-base md:text-lg mt-2">Sistem Informasi Pengelolaan dan Distribusi Logistik Rumah Sakit</p>
        </div>


            <!-- Tombol Tambah Barang dan Lihat Barang -->
            <div class="mb-6 text-center space-x-4">
                <button onclick="window.location.href='{{ route('logistics.index') }}'" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-2 rounded-full font-medium transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <i class="fas fa-eye mr-2"></i> Lihat Barang
                </button>
            </div>

            <!-- Summary Cards -->
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Stok Tersedia</h3>
                            <p class="text-3xl font-bold">{{ $totalStock }}</p>
                            <p class="text-green-100 text-sm mt-1">Items dalam kondisi baik</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-check-circle text-2xl"></i>
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

            <!-- Form Tambah Barang Langsung Tampil -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 mb-8 overflow-hidden">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Tambah Barang Baru</h2>
                    <form action="{{ route('logistics.store') }}" method="POST" class="space-y-4" id="logisticForm">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kolom 1 -->
                            <div class="space-y-4">
                                <input type="hidden" name="department_id" value="{{ auth()->user()->department_id }}">
                                
                                <!-- Kategori -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        Kategori
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <select name="category" id="category" class="w-full p-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-green-500 focus:border-transparent" required onchange="updateItemNames()">
                                        <option value="" disabled selected>Pilih kategori</option>
                                        <option value="Alat Kesehatan">Alat Kesehatan</option>
                                        <option value="Barang Habis Pakai">Barang Habis Pakai</option>
                                    </select>
                                </div>
                                
                                <!-- Nama Barang/Alat -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        Nama Barang/Alat
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <select name="item_name" id="item_name" class="w-full p-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-gray-800 focus:border-transparent" required onchange="handleItemNameChange()">
                                        <option value="" disabled selected>Pilih nama barang</option>
                                        <!-- Options will be populated by JavaScript -->
                                    </select>
                                    <input type="text" id="custom_item_name" name="custom_item_name" 
                                           class="w-full p-3 border border-gray-300 rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-gray-800 focus:border-transparent mt-2 hidden" 
                                           placeholder="Masukkan nama barang baru">
                                </div>
                                
                                <!-- Merk -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Merk</label>
                                    <input type="text" name="brand" 
                                           class="w-full p-3 border border-gray-300 rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-gray-800 focus:border-transparent" 
                                           placeholder="Masukkan merk barang">
                                </div>
                                
                                <!-- Kode Barang/SN -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Barang/SN</label>
                                    <input type="text" name="item_code" 
                                           class="w-full p-3 border border-gray-300 rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-gray-800 focus:border-transparent" 
                                           placeholder="Masukkan kode barang/serial number">
                                </div>
                            </div>
                            
                            <!-- Kolom 2 -->
                            <div class="space-y-4">
                                <!-- Jumlah Stok -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        Jumlah Stok
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <input type="number" name="stock" min="0" 
                                           class="w-full p-3 border border-gray-300 rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-gray-800 focus:border-transparent" 
                                           placeholder="0" 
                                           required>
                                </div>
                                
                                <!-- Satuan -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        Satuan
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <select name="unit_of_measure" class="w-full p-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                        <option value="" disabled selected>Pilih satuan</option>
                                        <option value="unit">Unit</option>
                                        <option value="buah">Buah</option>
                                        <option value="set">Set</option>
                                        <option value="box">Box</option>
                                        <option value="pack">Pack</option>
                                    </select>
                                </div>
                                
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
                                
                                <!-- Catatan -->
                              
                            </div>
                        </div>
                        
                        <!-- Kalibrasi Fields (only shown for Alat Kesehatan) -->
                        <div id="calibrationFields" class="hidden grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <!-- Tanggal Kalibrasi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kalibrasi</label>
                                <input type="date" name="calibration_date" 
                                       class="w-full p-3 border border-gray-300 rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-gray-800 focus:border-transparent" 
                                       placeholder="dd/mm/yyyy">
                            </div>
                            
                            <!-- Kadaluarsa Kalibrasi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kadaluarsa Kalibrasi</label>
                                <input type="date" name="calibration_expiry_date" 
                                       class="w-full p-3 border border-gray-300 rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-gray-800 focus:border-transparent" 
                                       placeholder="dd/mm/yyyy">
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex space-x-3 pt-6">
                            <button type="reset" onclick="resetForm()" class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition-colors duration-200">
                                <i class="fas fa-times mr-2"></i> Reset
                            </button>
                            <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-lg font-medium transition-all duration-200 transform hover:scale-105">
                                <i class="fas fa-save mr-2"></i> Simpan Barang
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Kategori Logistik -->
            <div class="space-y-6">
                @foreach(['Alat Kesehatan', 'Barang Habis Pakai'] as $category)
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
                                            @if($category == 'Alat Kesehatan') fa-medkit 
                                            @elseif($category == 'Barang Habis Pakai') fa-boxes 
                                            @endif
                                            text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <span class="text-lg font-semibold text-gray-900">{{ $category }}</span>
                                        <div class="text-sm text-gray-500 mt-1">
                                            @if($category == 'Alat Kesehatan') Medical Equipment 
                                            @elseif($category == 'Barang Habis Pakai') Consumable Items 
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
                                                @if($category == 'Alat Kesehatan')
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kalibrasi</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($items as $item)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->item_name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->brand ?? '-' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->stock }} {{ $item->unit_of_measure }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($item->stock > 10)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Tersedia</span>
                                                    @elseif($item->stock > 5)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Terbatas</span>
                                                    
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Menipis/Habis</span>
                                                    
                                                        @endif
                                                     
                                                </td>
                                                @if($category == 'Alat Kesehatan')
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    @if($item->calibration_expiry_date)
                                                        {{ \Carbon\Carbon::parse($item->calibration_expiry_date)->format('d/m/Y') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                @endif
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
        </div>
    </div>

    <script>
        // Item names for each category
      const itemNames = {
    'Alat Kesehatan': [
        'ECG Machine',
        'Defibrillator',
        'Infusion Pump',
        'Syringe Pump',
        'Patient Monitor',
        'Ventilator',
        'Ultrasound Machine',
        'X-ray Machine',
        'Autoclave',
        'Stethoscope',
        'Nebulizer',
        'Otoscope',
        'Ophthalmoscope',
        'Timbangan Digital',
        'Tensimeter Digital',
        'Tensimeter Manual',
        'Thermometer Infrared',
        'Thermometer Digital',
        'USG Portable',
        'CT Scan',
        'MRI',
        'Dental Unit',
        'Laryngoscope',
        'Suction Pump',
        'Lifepack',
        'AED (Automated External Defibrillator)',
        'Incubator Bayi',
        'Infant Warmer',
        'Doppler Janin',
        'Lampu Operasi',
        'Meja Operasi',
        'Meja Periksa',
        'Tempat Tidur Pasien Elektrik',
        'Tempat Tidur Pasien Manual',
        'Kursi Roda',
        'Brankar Pasien',
        'Alat Sterilisasi Uap (Autoclave)',
        'Alat Sterilisasi Kering',
        'Lampu Pemeriksaan',
        'Alat Cek Gula Darah',
        'Alat Cek Kolesterol',
        'Alat Cek Asam Urat'
    ],
    'Barang Habis Pakai': [
        'Masker Bedah',
        'Sarung Tangan',
        'Jarum Suntik',
        'Kassa Steril',
        'Plaster Luka',
        'Kapas Alkohol',
        'Infus Set',
        'Kateter',
        'Perban',
        'Plester',
        'Spuit (Syringe)',
        'Alkohol Swab',
        'Urine Bag',
        'Suction Catheter',
        'Nasal Cannula',
        'Oxygen Mask',
        'Ringer Laktat',
        'NaCl 0.9%',
        'Glukosa 5%',
        'Betadine',
        'Hand Sanitizer',
        'Tisu Alkohol',
        'Kantong Mayat',
        'Apron Sekali Pakai',
        'Penampung Urin',
        'Lubrikan Gel',
        'Tabung Oksigen Portable',
        'Reservoir Bag',
        'Tape Medis',
        'Masker N95',
        'Gown Medis Sekali Pakai',
        'Spon Steril',
        'Silet Bedah',
        'Bisturi Sekali Pakai',
        'Benang Jahit Bedah',
        'Cairan Desinfektan',
        'Cairan Pembersih Alat Medis'
    ]
};

        

        // Update item names dropdown based on selected category
        function updateItemNames() {
            const categorySelect = document.getElementById('category');
            const itemNameSelect = document.getElementById('item_name');
            const calibrationFields = document.getElementById('calibrationFields');
            const customItemNameInput = document.getElementById('custom_item_name');
            
            // Reset custom input
            customItemNameInput.value = '';
            customItemNameInput.classList.add('hidden');
            
            // Clear existing options
            itemNameSelect.innerHTML = '<option value="" disabled selected>Pilih nama barang</option>';
            
            // Add new options based on category
            const selectedCategory = categorySelect.value;
            if (selectedCategory && itemNames[selectedCategory]) {
                itemNames[selectedCategory].forEach(item => {
                    const option = document.createElement('option');
                    option.value = item;
                    option.textContent = item;
                    itemNameSelect.appendChild(option);
                });
                
                // Add "Lainnya" option
                const otherOption = document.createElement('option');
                otherOption.value = 'other';
                otherOption.textContent = 'Lainnya...';
                itemNameSelect.appendChild(otherOption);
            }
            
            // Show/hide calibration fields
            if (selectedCategory === 'Alat Kesehatan') {
                calibrationFields.classList.remove('hidden');
            } else {
                calibrationFields.classList.add('hidden');
            }
        }

        // Handle when item name selection changes
        function handleItemNameChange() {
            const itemNameSelect = document.getElementById('item_name');
            const customItemNameInput = document.getElementById('custom_item_name');
            
            if (itemNameSelect.value === 'other') {
                // Show custom input field
                customItemNameInput.classList.remove('hidden');
                customItemNameInput.required = true;
                customItemNameInput.focus();
            } else {
                // Hide custom input field
                customItemNameInput.classList.add('hidden');
                customItemNameInput.required = false;
            }
        }

        // Reset form including the item names dropdown
        function resetForm() {
            const itemNameSelect = document.getElementById('item_name');
            const calibrationFields = document.getElementById('calibrationFields');
            const customItemNameInput = document.getElementById('custom_item_name');
            
            // Reset item names dropdown
            itemNameSelect.innerHTML = '<option value="" disabled selected>Pilih nama barang</option>';
            
            // Reset custom input
            customItemNameInput.value = '';
            customItemNameInput.classList.add('hidden');
            
            // Hide calibration fields
            calibrationFields.classList.add('hidden');
        }

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

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize animations
            const elements = document.querySelectorAll('.animate-fadeIn');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 100);
            });
            
            // Initialize category change listener
            document.getElementById('category').addEventListener('change', updateItemNames);
            
            // Initialize item name change listener
            document.getElementById('item_name').addEventListener('change', handleItemNameChange);
            
            // Handle form submission to use custom name if selected
            document.getElementById('logisticForm').addEventListener('submit', function(e) {
                const itemNameSelect = document.getElementById('item_name');
                const customItemNameInput = document.getElementById('custom_item_name');
                
                if (itemNameSelect.value === 'other' && customItemNameInput.value.trim() !== '') {
                    // Replace the select value with the custom input value
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'item_name';
                    hiddenInput.value = customItemNameInput.value.trim();
                    this.appendChild(hiddenInput);
                    
                    // Disable the original select to prevent it from being submitted
                    itemNameSelect.disabled = true;
                }
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