<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        /* Paste the standard PDF styling here */
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 0; font-size: 12px; }
        .user-info { margin-bottom: 20px; border: 1px solid #eee; padding: 10px; border-radius: 5px;}
        .user-info p { margin: 2px 0; }
        .section-title { font-size: 14px; font-weight: bold; margin-top: 20px; margin-bottom: 10px; }
        .small-text { font-size: 9px; }
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

    <h2 class="section-title">Detail Aktivitas PPI</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis Form</th>
                <th>Tanggal Aktivitas</th>
                <th>Pasien/Nama Terluka</th>
                <th>No. Rekam Medis</th>
                <th>Detail Singkat</th>
                <th>Waktu Input</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ppiActivities as $index => $activity)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $activity['form_type'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($activity['activity_date'])->format('d-m-Y') }}</td>
                    <td>{{ $activity['patient_name'] }}</td>
                    <td>{{ $activity['medical_record_number'] }}</td>
                    <td class="small-text">{{ $activity['details'] }}</td> {{-- Use small-text for potentially long details --}}
                    <td>{{ \Carbon\Carbon::parse($activity['submitted_at'])->format('d-m-Y H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada aktivitas PPI terbaru.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>