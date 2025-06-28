<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Logistik - Tabel Lengkap</title>
    @vite('resources/css/app.css')
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
    </style>
</head>
<body class="bg-white text-gray-900">
    @include('components.sidebar-navbar')
    
    <div class="p-4 pt-20 pl-60 pr-5">
        <div class="p-6 border border-gray-300 rounded-xl shadow-sm bg-white">
            <h1 class="text-2xl font-bold mb-6 text-gray-900">Tabel Detail Alat Kesehatan & Logistik RS</h1>
            
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logistics as $item)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-gray-900">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-900">{{ $item->unit ?? '-' }}</td>
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
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>