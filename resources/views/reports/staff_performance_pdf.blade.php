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
        .header h1 { margin: 0; font-size: 16px; } /* Slightly smaller header */
        .header p { margin: 0; font-size: 10px; }
        .user-info { margin-bottom: 15px; border: 1px solid #eee; padding: 8px; border-radius: 5px;}
        .user-info p { margin: 2px 0; }
        .section-title { font-size: 12px; font-weight: bold; margin-top: 15px; margin-bottom: 8px; }
        .score-status { font-weight: bold; }
        .notes-column { font-size: 7px; max-width: 100px; } /* Even smaller for notes, with max-width hint */

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

    <h2>Rekapitulasi Penilaian Kinerja Staff</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Staff</th>
                <th>Jabatan</th>
                <th>Kedisiplinan (%)</th>
                <th>Komunikasi (%)</th>
                <th>Komplain (Skor)</th>
                <th>Kepatuhan (%)</th>
                <th>Pencapaian Target (%)</th>
                <th>Skor Akhir (%)</th>
                <th>Status Kinerja</th>
                <th>Catatan</th>
                <th>Tanggal Evaluasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($performanceData as $index => $perf)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $perf['staff_name'] }}</td>
                    <td>{{ $perf['position_name'] }}</td>
                    <td>{{ $perf['discipline_score'] }}</td>
                    <td>{{ $perf['communication_score'] }}</td>
                    <td>{{ $perf['complaint_count'] }}</td>
                    <td>{{ $perf['compliance_score'] }}</td>
                    <td>{{ $perf['target_achievement'] }}</td>
                    <td>{{ $perf['overall_score'] }}</td>
                    <td class="score-status">{{ $perf['status_kinerja'] }}</td>
                    <td class="notes-column">{{ $perf['notes'] ?? '-' }}</td> {{-- Applied notes-column class --}}
                    <td>{{ \Carbon\Carbon::parse($perf['evaluation_date'])->format('d-m-Y H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" style="text-align: center;">Tidak ada data penilaian kinerja staff.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>