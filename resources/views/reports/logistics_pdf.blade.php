<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { 
            font-family: 'DejaVu Sans', sans-serif; 
            font-size: 8px; /* Reduced font size */
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

        /* Landscape orientation for dompdf */
        @page { 
            size: A4 landscape; /* Set page to A4 landscape */
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Tanggal Laporan: {{ $date }}</p>
        <p>Rumah Sakit: {{ $userInfo->hospital->name ?? 'N/A' }}</p>
        <p>Ruangan: {{ $userInfo->department->name ?? 'N/A' }}</p>
        <p>Kepala Ruangan: {{ $userInfo->name ?? 'N/A' }}</p>
    </div>

    <div class="user-info">
        <p><strong>Kepala Ruangan:</strong> {{ $userInfo->name ?? 'N/A' }}</p>
        <p><strong>Jabatan:</strong> Kepala Ruangan {{ $userInfo->department->name ?? 'N/A' }}</p>
        <p><strong>Rumah Sakit:</strong> {{ $userInfo->hospital->name ?? 'N/A' }}</p>
    </div>

    <h2>Inventaris Logistik Ruangan</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Merk</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Status</th>
                <th>Kode Barang</th>
                <th>Jadwal Maint.</th> {{-- Abbreviated --}}
                <th>Tgl Kalibrasi</th> {{-- Abbreviated --}}
                <th>Kadaluarsa Kalibrasi</th> {{-- Abbreviated --}}
                <th>Catatan</th>
                <th>Terakhir Diperbarui</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logistics as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->category }}</td>
                    <td>{{ $item->brand ?? '-' }}</td>
                    <td>{{ $item->stock }}</td>
                    <td>{{ $item->unit_of_measure ?? '-' }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->item_code ?? '-' }}</td>
                    <td>{{ $item->maintenance_schedule ?? '-' }}</td>
                    <td>{{ $item->calibration_date ? \Carbon\Carbon::parse($item->calibration_date)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $item->calibration_expiry_date ? \Carbon\Carbon::parse($item->calibration_expiry_date)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $item->notes ?? '-' }}</td>
                    <td>{{ $item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->format('d-m-Y H:i') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" style="text-align: center;">Tidak ada data logistik.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>