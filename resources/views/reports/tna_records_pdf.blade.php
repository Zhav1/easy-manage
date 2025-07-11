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

    <h2 class="section-title">Rekapitulasi Training Need Assessment (TNA)</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Staff</th>
                <th>Jabatan</th>
                <th>Seminar / Workshop / Webinar</th>
                <th>Pelatihan</th>
                <th>Pendidikan Lanjutan</th>
                <th>Tanggal Input</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tnaRecords as $index => $tna)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $tna->staff->name ?? 'N/A' }}</td>
                    <td>{{ $tna->staff->position->name ?? 'N/A' }}</td>
                    <td class="small-text">{{ $tna->seminar_workshop_webinar ?? 'Belum Ada' }}</td>
                    <td class="small-text">{{ $tna->pelatihan ?? 'Belum Ada' }}</td>
                    <td class="small-text">{{ $tna->pendidikan_lanjutan ?? 'Belum Ada' }}</td>
                    <td>{{ \Carbon\Carbon::parse($tna->created_at)->format('d-m-Y H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data TNA.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>