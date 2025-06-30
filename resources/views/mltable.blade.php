<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Logistik - Tabel Lengkap</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
    </style>
</head>
<body class="bg-white text-gray-900">
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
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Jadwal Pemeliharaan</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Tanggal Kalibrasi</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Kadaluarsa Kalibrasi</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Stok</th>
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
                            <td class="px-4 py-3 whitespace-nowrap text-gray-900">{{ $item->maintenance_schedule ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-900">{{ $item->calibration_date ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-900">{{ $item->calibration_expiry_date ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-900">{{ $item->stock ?? '0' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-900">{{ $item->unit_of_measure ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($item->stock > 10)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Tersedia</span>
                                @elseif($item->stock > 5)
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Terbatas</span>
                                @else
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Menipis</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex space-x-2">
                     
                                    
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
                    <div>
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
                    </div>
                    
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
function openAddItemModal() {
    document.getElementById('addItemModal').classList.remove('hidden');
    document.getElementById('addItemModal').classList.add('flex');
}

function closeAddItemModal() {
    document.getElementById('addItemModal').classList.add('hidden');
    document.getElementById('addItemModal').classList.remove('flex');
}
</script>
<script>
        // Confirm before deleting
        function confirmDelete(event) {
            if (!confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                event.preventDefault();
            }
        }
    </script>
</body>
</html>