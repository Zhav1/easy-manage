<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Logistik - Tabel Lengkap</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <!-- Add these inside the <head> section -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
    <style>
        /* Remove any blue link colors */
        a {
            color: inherit;
            text-decoration: none;
        }
        
        a:hover {
            color: inherit;
            text-decoration: underline;
        }
        html, body {
            background-color: #ffffff !important;
        }
        /* Better table scrolling */
        .table-container {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }
        /* Edit button styling */
        .edit-btn {
            transition: all 0.2s ease;
        }
        .edit-btn:hover {
            transform: scale(1.1);
        }
        /* Status badges */
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-weight: 600;
        }
        .status-available {
            background-color: #DCFCE7;
            color: #166534;
        }
        .status-limited {
            background-color: #FEF9C3;
            color: #854D0E;
        }
        .status-low {
            background-color: #FEE2E2;
            color: #991B1B;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100">
    @include('components.sidebar-navbar')
    
    <div class="p-4 pt-20 pl-60 pr-5">
        <div class="p-6 border border-gray-300 rounded-xl shadow-sm bg-white">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Tabel Detail Alat Kesehatan & Logistik RS</h1>
                <button onclick="openAddItemModal()" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-2 rounded-full font-medium transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus mr-2"></i> Tambahkan Barang
                </button>
                 
   
            </div>
           
            <div class="table-container">
                <table class="min-w-full bg-white">
                    <thead class="sticky top-0 bg-white">
                        <tr class="border-b-2 border-gray-300">
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Unit</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Kategori</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Jenis Alat/Logistik</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Merk</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Kode Barang</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Tanggal Kalibrasi</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Kadaluarsa Kalibrasi</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Stok</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Terpakai</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Satuan</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logistics as $item)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-gray-900">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-900">{{ $item->department->name ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-900">{{ $item->category ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-900">{{ $item->item_name ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-900">{{ $item->brand ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-900">{{ $item->item_code ?? '-' }}</td>
                        
                            <td class="px-4 py-3 whitespace-nowrap text-gray-900">{{ $item->calibration_date ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-900">{{ $item->calibration_expiry_date ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-900">{{ $item->stock ?? '0' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-900">{{ $item->used ?? '0' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-900">{{ $item->unit_of_measure ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($item->stock > 10)
                                    <span class="status-badge status-available">Tersedia</span>
                                @elseif($item->stock > 5)
                                    <span class="status-badge status-limited">Terbatas</span>
                                @else
                                    <span class="status-badge status-low">Menipis</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex space-x-2">
                                    <!-- Use Item Button -->
                                    <button onclick="openUseItemModal('{{ $item->id }}', '{{ $item->item_name }}', {{ $item->stock }})" 
                                            class="edit-btn text-blue-500 hover:text-blue-700"
                                            title="Gunakan Barang">
                                        <i class="fas fa-minus-circle"></i>
                                    </button>
                                    
                                    <!-- Delete Button -->
                                    <form action="{{ route('logistics.destroy', $item->id) }}" method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus item ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="edit-btn text-red-500 hover:text-red-700"
                                                title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                            <textarea name="notes" rows="2" 
                                      class="w-full p-3 border border-gray-300 rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-gray-800 focus:border-transparent" 
                                      placeholder="Catatan tambahan..."></textarea>
                        </div>
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

    <!-- Modal Gunakan Barang -->
    <div id="useItemModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl transform transition-all duration-300">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-minus-circle text-white text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Gunakan Barang</h2>
                <p id="useItemName" class="text-gray-600 mt-2">Menggunakan <span class="font-semibold">[Nama Barang]</span></p>
            </div>
            
            <form id="useItemForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="item_id" id="useItemId">
                
                <!-- Jumlah yang Digunakan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        Jumlah yang Digunakan
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="number" name="quantity" id="useItemQuantity" min="1" 
                           class="w-full p-3 border border-gray-300 rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           placeholder="0" 
                           required>
                    <p id="maxQuantityText" class="text-sm text-gray-500 mt-1">Stok tersedia: <span id="availableStock">0</span></p>
                </div>
                
                <!-- Catatan Penggunaan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Penggunaan</label>
                    <textarea name="usage_notes" rows="3" 
                              class="w-full p-3 border border-gray-300 rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                              placeholder="Contoh: Digunakan untuk pasien ICU"></textarea>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex space-x-3 pt-6">
                    <button type="button" onclick="closeUseItemModal()" class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i> Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-lg font-medium transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-check-circle mr-2"></i> Konfirmasi
                    </button>
                </div>
            </form>
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
                'Stethoscope'
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
                'Plester'
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

        // Modal functions for Add Item
        function openAddItemModal() {
            document.getElementById('addItemModal').classList.remove('hidden');
            document.getElementById('addItemModal').classList.add('flex');
            resetForm(); // Reset form when opening modal
        }

        function closeAddItemModal() {
            document.getElementById('addItemModal').classList.add('hidden');
            document.getElementById('addItemModal').classList.remove('flex');
        }

        // Modal functions for Use Item
        function openUseItemModal(itemId, itemName, availableStock) {
            document.getElementById('useItemModal').classList.remove('hidden');
            document.getElementById('useItemModal').classList.add('flex');
            
            // Set form values
            document.getElementById('useItemId').value = itemId;
            document.getElementById('useItemName').innerHTML = `Menggunakan <span class="font-semibold">${itemName}</span>`;
            document.getElementById('availableStock').textContent = availableStock;
            document.getElementById('useItemQuantity').max = availableStock;
            document.getElementById('useItemQuantity').value = 1;
            
            // Set form action
            document.getElementById('useItemForm').action = `/logistics/${itemId}/use`;
        }

        function closeUseItemModal() {
            document.getElementById('useItemModal').classList.add('hidden');
            document.getElementById('useItemModal').classList.remove('flex');
        }

        // Close modals when clicking outside
        document.getElementById('addItemModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddItemModal();
            }
        });

        document.getElementById('useItemModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeUseItemModal();
            }
        });

        // Confirm before deleting
        function confirmDelete(event) {
            if (!confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                event.preventDefault();
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize category change listener
            document.getElementById('category').addEventListener('change', updateItemNames);
            
            // Initialize item name change listener
            document.getElementById('item_name').addEventListener('change', handleItemNameChange);
            
            // Handle form submission to use custom name if selected
            document.querySelector('form').addEventListener('submit', function(e) {
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
            
            // Handle use item form submission
            document.getElementById('useItemForm').addEventListener('submit', function(e) {
                const quantity = parseInt(document.getElementById('useItemQuantity').value);
                const maxQuantity = parseInt(document.getElementById('availableStock').textContent);
                
                if (quantity > maxQuantity) {
                    e.preventDefault();
                    alert('Jumlah yang digunakan tidak boleh melebihi stok yang tersedia!');
                    return false;
                }
                
                return true;
            });
        });
    </script>

    <style>
        @media (max-width: 768px) {
            .pl-60 {
                padding-left: 1rem;
            }
            .pr-5 {
                padding-right: 1rem;
            }
        }
    </style>
</body>
</html>