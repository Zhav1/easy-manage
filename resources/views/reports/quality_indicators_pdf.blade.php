<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        /* Standard PDF styling */
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; } /* Slightly smaller font for more data */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; vertical-align: top; } /* Smaller padding */
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 0; font-size: 12px; }
        .user-info { margin-bottom: 20px; border: 1px solid #eee; padding: 10px; border-radius: 5px;}
        .user-info p { margin: 2px 0; }
        .section-title { font-size: 14px; font-weight: bold; margin-top: 20px; margin-bottom: 10px; }
        .page-break { page-break-after: always; } /* Force page break for next section/table */
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

    <h2 class="section-title">Rekapitulasi Data Semua Formulir Indikator Mutu</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Formulir</th>
                <th>Tanggal Aktivitas</th>
                <th>Pasien/Entitas</th>
                <th>Skor/Kepatuhan</th>
                <th>Catatan</th>
                <th>Detail Formulir</th>
                <th>Waktu Input</th>
            </tr>
        </thead>
        <tbody>
            {{-- Loop through the `allQualityEntries` variable, which will contain the combined data --}}
            @forelse($allQualityEntries as $index => $entry)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $entry['form_name'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($entry['activity_date'])->format('d-m-Y') }}</td>
                    <td>{{ $entry['patient_entity'] }}</td>
                    <td>{{ $entry['score'] }}</td>
                    <td>{{ $entry['notes'] }}</td>
                    <td>{{ $entry['details_summary'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($entry['submitted_at'])->format('d-m-Y H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data formulir indikator mutu.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>