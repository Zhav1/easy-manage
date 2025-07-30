<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; margin: 20px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed; /* <-- ADD THIS LINE */
        }
        th, td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word; /* <-- AND ADD THIS LINE */
        }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 0; font-size: 12px; }
        .user-info { margin-bottom: 20px; border: 1px solid #eee; padding: 10px; border-radius: 5px;}
        .user-info p { margin: 2px 0; }
        .section-title { font-size: 14px; font-weight: bold; margin-top: 20px; margin-bottom: 10px; }
        .page-break { page-break-after: always; }
        .status-badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-align: center;
        }
        .status-ya { background-color: #d4edda; color: #155724; }
        .status-tidak { background-color: #f8d7da; color: #721c24; }
        .status-na { background-color: #e2e3e5; color: #383d41; }
        .case-type-resiko-tinggi { background-color: #f8d7da; color: #721c24; }
        .case-type-kompleks { background-color: #fff3cd; color: #856404; }
        .case-type-kasus-langka { background-color: #e0b0ff; color: #4b0082; }
        .case-type-default { background-color: #e2e3e5; color: #383d41; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Tanggal Laporan: {{ $date }}</p>
        <p>Rentang Data Catatan Kegiatan: {{ $report_start_date }} hingga {{ $report_end_date }}</p>
        <p>Rumah Sakit: {{ $userInfo->hospital->name ?? 'N/A' }}</p>
        <p>Ruangan: {{ $userInfo->department->name ?? 'N/A' }}</p>
        <p>Kepala Ruangan: {{ $userInfo->name ?? 'N/A' }}</p>
    </div>

    <div class="user-info">
        <p><strong>Kepala Ruangan:</strong> {{ $userInfo->name ?? 'N/A' }}</p>
        <p><strong>Jabatan:</strong> Kepala Ruangan {{ $userInfo->department->name ?? 'N/A' }}</p>
        <p><strong>Rumah Sakit:</strong> {{ $userInfo->hospital->name ?? 'N/A' }}</p>
    </div>

    <h2 class="section-title">Catatan Harian Kegiatan (Jadwal Pribadi)</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Tanggal & Waktu</th> 
                <th style="width: 10%;">Briefing</th>
                <th style="width: 10%;">Rapat</th>
                <th style="width: 10%;">Supervisi</th>
                <th style="width: 10%;">Handover</th>
                <th style="width: 20%;">Tugas Luar</th>
                <th style="width: 25%;">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($privateSchedules as $log)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($log->scheduled_at)->format('d-m-Y H:i') }}</td>
                    <td><span class="status-badge @if($log->briefing) status-ya @else status-tidak @endif">{{ $log->briefing ? 'Ya' : 'Tidak' }}</span></td>
                    <td><span class="status-badge @if($log->meeting) status-ya @else status-tidak @endif">{{ $log->meeting ? 'Ya' : 'Tidak' }}</span></td>
                    <td><span class="status-badge @if($log->supervision) status-ya @else status-tidak @endif">{{ $log->supervision ? 'Ya' : 'Tidak' }}</span></td>
                    <td><span class="status-badge @if($log->handover) status-ya @else status-tidak @endif">{{ $log->handover ? 'Ya' : 'Tidak' }}</span></td>
                    <td>{{ $log->external_task ?: '-' }}</td>
                    <td>{{ $log->note ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada catatan kegiatan harian untuk rentang tanggal ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="page-break"></div>
    <h2 class="section-title">Kasus Perhatian Khusus (Semua Data)</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Tanggal Kasus</th>
                <th style="width: 20%;">Nama Pasien</th>
                <th style="width: 15%;">Jenis Kasus</th>
                <th style="width: 25%;">Detail</th>
                <th style="width: 25%;">Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($specialCases as $case)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($case->case_date)->format('d-m-Y H:i') }}</td>
                    <td>{{ $case->patient_name ?: '-' }}</td>
                    <td>
                        @php
                            $caseTypeClass = '';
                            switch ($case->case_type) {
                                case 'Resiko Tinggi': $caseTypeClass = 'case-type-resiko-tinggi'; break;
                                case 'Kompleks': $caseTypeClass = 'case-type-kompleks'; break;
                                case 'Kasus Langka': $caseTypeClass = 'case-type-kasus-langka'; break;
                                default: $caseTypeClass = 'case-type-default'; break;
                            }
                        @endphp
                        <span class="status-badge {{ $caseTypeClass }}">{{ $case->case_type ?: '-' }}</span>
                    </td>
                    <td>{{ $case->details ?: '-' }}</td>
                    <td>{{ $case->action_taken ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada kasus perhatian khusus.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>