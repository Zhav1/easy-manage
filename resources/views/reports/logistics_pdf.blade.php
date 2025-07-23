<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8px; /* Reduced font size for more content */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px; /* Slightly reduced margin */
        }
        th, td {
            border: 1px solid #ddd;
            padding: 4px; /* Reduced padding */
            text-align: left;
            vertical-align: top;
            word-wrap: break-word; /* Allow long words to break */
        }
        th {
            background-color: #f2f2f2;
            white-space: nowrap; /* Prevent headings from wrapping if they are short, or explicitly control */
        }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; }
        .header p { margin: 0; font-size: 10px; }
        .user-info { margin-bottom: 15px; border: 1px solid #eee; padding: 8px; border-radius: 5px;}
        .user-info p { margin: 2px 0; }
        .section-title { font-size: 12px; font-weight: bold; margin-top: 15px; margin-bottom: 8px; }
        .page-break { page-break-after: always; } /* Optional page break */
        .status-badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-align: center;
        }
        .status-tersedia { background-color: #d4edda; color: #155724; }
        .status-terbatas { background-color: #fff3cd; color: #856404; }
        .status-menipis { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Tanggal Laporan: {{ $date }}</p>
        <p>Rentang Data Konsumsi: {{ $report_start_date }} hingga {{ $report_end_date }}</p>
        <p>Rumah Sakit: {{ $userInfo->hospital->name ?? 'N/A' }}</p>
        <p>Ruangan: {{ $userInfo->department->name ?? 'N/A' }}</p>
        <p>Kepala Ruangan: {{ $userInfo->name ?? 'N/A' }}</p>
    </div>

    <div class="user-info">
        <p><strong>Kepala Ruangan:</strong> {{ $userInfo->name ?? 'N/A' }}</p>
        <p><strong>Jabatan:</strong> Kepala Ruangan {{ $userInfo->department->name ?? 'N/A' }}</p>
        <p><strong>Rumah Sakit:</strong> {{ $userInfo->hospital->name ?? 'N/A' }}</p>
    </div>

    <h2 class="section-title">Inventaris: Alat Kesehatan</h2> <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Merk</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Status</th>
                <th>Kode Barang</th>
                <th>Jadwal Maint.</th>
                <th>Tgl Kalibrasi</th>
                <th>Kadaluarsa Kalibrasi</th>
                <th>Catatan</th>
                <th>Terakhir Diperbarui</th>
            </tr>
        </thead>
        <tbody>
            @forelse($alatKesehatanItems as $index => $item) <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->brand ?? '-' }}</td>
                    <td>{{ $item->stock }}</td>
                    <td>{{ $item->unit_of_measure ?? '-' }}</td>
                    <td>
                        <span class="status-badge @if($item->status == 'Tersedia') status-tersedia @elseif($item->status == 'Terbatas') status-terbatas @else status-menipis @endif">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td>{{ $item->item_code ?? '-' }}</td>
                    <td>{{ $item->maintenance_schedule ?? '-' }}</td>
                    <td>{{ $item->calibration_date ? \Carbon\Carbon::parse($item->calibration_date)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $item->calibration_expiry_date ? \Carbon\Carbon::parse($item->calibration_expiry_date)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $item->notes ?? '-' }}</td>
                    <td>{{ $item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->format('d-m-Y H:i') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" style="text-align: center;">Tidak ada data Alat Kesehatan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="page-break"></div>

    <h2 class="section-title">Inventaris: Barang Habis Pakai</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Merk</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Status</th>
                <th>Kode Barang</th>
                <th>Jadwal Maint.</th>
                <th>Tgl Kalibrasi</th>
                <th>Kadaluarsa Kalibrasi</th>
                <th>Catatan</th>
                <th>Terakhir Diperbarui</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangHabisPakaiItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->brand ?? '-' }}</td>
                    <td>{{ $item->stock }}</td>
                    <td>{{ $item->unit_of_measure ?? '-' }}</td>
                    <td>
                        <span class="status-badge @if($item->status == 'Tersedia') status-tersedia @elseif($item->status == 'Terbatas') status-terbatas @else status-menipis @endif">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td>{{ $item->item_code ?? '-' }}</td>
                    <td>{{ $item->maintenance_schedule ?? '-' }}</td>
                    <td>{{ $item->calibration_date ? \Carbon\Carbon::parse($item->calibration_date)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $item->calibration_expiry_date ? \Carbon\Carbon::parse($item->calibration_expiry_date)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $item->notes ?? '-' }}</td>
                    <td>{{ $item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->format('d-m-Y H:i') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" style="text-align: center;">Tidak ada data Barang Habis Pakai.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="page-break"></div>

    <h2 class="section-title">Laporan Konsumsi Barang (Berdasarkan Penggunaan)</h2>
    <p>Rentang Data: {{ $report_start_date }} hingga {{ $report_end_date }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Merk</th>
                <th>Digunakan</th> <th>Satuan</th>
                <th>Stok Tersisa</th> <th>Status</th>
                <th>Terakhir Diperbarui</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($consumptionItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->category }}</td>
                    <td>{{ $item->brand ?? '-' }}</td>
                    <td>{{ $item->used }}</td> <td>{{ $item->unit_of_measure ?? '-' }}</td>
                    <td>{{ $item->stock }}</td> <td>
                        <span class="status-badge @if($item->status == 'Tersedia') status-tersedia @elseif($item->status == 'Terbatas') status-terbatas @else status-menipis @endif">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td>{{ $item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->format('d-m-Y H:i') : '-' }}</td>
                    <td>{{ $item->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center;">Tidak ada data konsumsi barang dalam rentang tanggal ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>