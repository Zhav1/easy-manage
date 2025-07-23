<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        @page { margin: 20px 25px; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; }
        
        /* General Layout */
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; }
        .header p { margin: 2px 0; font-size: 11px; }
        .section-title { font-size: 14px; font-weight: bold; margin-top: 15px; margin-bottom: 10px; padding-bottom: 3px; border-bottom: 1px solid #333; }
        .page-break { page-break-after: always; }
        .no-data { text-align: center; padding: 20px; font-style: italic; color: #888; }
        hr { border: 0; border-top: 1px dashed #ccc; margin: 15px 0; }

        /* Record Block for each entry */
        .record-block {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 15px;
            page-break-inside: avoid; /* Try to keep each block on one page */
        }
        .record-block h3 {
            font-size: 12px;
            background-color: #f2f2f2;
            padding: 5px;
            margin-top: 0;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }

        /* Tables for details */
        .detail-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .detail-table th, .detail-table td { border: 1px solid #ddd; padding: 6px; text-align: left; vertical-align: top; }
        .detail-table th { background-color: #f9f9f9; font-weight: bold; }
        
        /* Two-column summary table */
        .summary-table { width: 100%; }
        .summary-table td { border: none; padding: 3px 0; }
        .summary-table td:first-child { font-weight: bold; width: 180px; }

        /* Chart Page Specifics */
        .chart-grid { width: 100%; border: none; }
        .chart-grid td { border: none; padding: 10px; vertical-align: top; width: 50%; }
        .chart-img { max-width: 100%; height: auto; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>
            Periode Laporan: {{ $report_start_date }} {{ $report_end_date ? '- ' . $report_end_date : '' }}
        </p>
        <p>Rumah Sakit: {{ $userInfo->hospital->name ?? 'N/A' }} | Ruangan: {{ $userInfo->department->name ?? 'N/A' }}</p>
    </div>

    <h2 class="section-title">Dashboard Visual PPI</h2>
    <table class="chart-grid">
        <tr>
            <td class="chart-cell">
                <h3 style="text-align:center;">Tren Infeksi CVC (6 Bulan)</h3>
                @if(isset($chartImages['infectionTrend']))
                    <img src="{{ $chartImages['infectionTrend'] }}" class="chart-img">
                @else
                    <p class="no-data">Data tidak tersedia</p>
                @endif
            </td>
            <td class="chart-cell">
                <h3 style="text-align:center;">Tren Insiden Tertusuk Jarum (6 Bulan)</h3>
                @if(isset($chartImages['needlestickTrend']))
                    <img src="{{ $chartImages['needlestickTrend'] }}" class="chart-img">
                @else
                    <p class="no-data">Data tidak tersedia</p>
                @endif
            </td>
        </tr>
        <tr>
            <td class="chart-cell">
                <h3 style="text-align:center;">Infeksi Berdasarkan Lokasi Insersi</h3>
                @if(isset($chartImages['infectionLocation']))
                    <img src="{{ $chartImages['infectionLocation'] }}" class="chart-img">
                @else
                    <p class="no-data">Data tidak tersedia</p>
                @endif
            </td>
            <td class="chart-cell">
                <h3 style="text-align:center;">Infeksi Berdasarkan Mikroorganisme</h3>
                @if(isset($chartImages['microorganism']))
                    <img src="{{ $chartImages['microorganism'] }}" class="chart-img">
                @else
                    <p class="no-data">Data tidak tersedia</p>
                @endif
            </td>
        </tr>
        <tr>
            <td class="chart-cell">
                <h3 style="text-align:center;">Insiden Berdasarkan Unit/Bagian</h3>
                @if(isset($chartImages['needlestickDepartment']))
                    <img src="{{ $chartImages['needlestickDepartment'] }}" class="chart-img">
                @else
                    <p class="no-data">Data tidak tersedia</p>
                @endif
            </td>
            <td class="chart-cell">
                <h3 style="text-align:center;">Insiden Berdasarkan Jabatan</h3>
                @if(isset($chartImages['needlestickPosition']))
                    <img src="{{ $chartImages['needlestickPosition'] }}" class="chart-img">
                @else
                    <p class="no-data">Data tidak tersedia</p>
                @endif
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    <h2 class="section-title">Laporan Detail Insersi CVC</h2>
    @forelse($insertions as $item)
        <div class="record-block">
            <h3>Detail untuk Pasien: {{ $item->patient_name }} ({{ \Carbon\Carbon::parse($item->insertion_date)->format('d-m-Y') }})</h3>
            <table class="summary-table">
                <tr><td>No. Rekam Medis:</td><td>{{ $item->medical_record_number }}</td></tr>
                <tr><td>Lokasi Insersi:</td><td>{{ $item->insertion_location }}</td></tr>
                <tr><td>Operator:</td><td>{{ $item->operator_name }}</td></tr>
                <tr><td>Tingkat Kepatuhan:</td><td><strong>{{ $item->compliance_percentage }}%</strong></td></tr>
            </table>

            <h4>Detail Elemen Penilaian:</h4>
            <table class="detail-table">
                <thead><tr><th>Deskripsi</th><th>Status</th><th>Catatan</th></tr></thead>
                <tbody>
                @foreach($item->elements_data as $element)
                    <tr>
                        <td>{{ $element['description'] }}</td>
                        <td>{{ $element['status'] }}</td>
                        <td>{{ $element['notes'] ?? '-' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @if(!$loop->last)<hr>@endif
    @empty
        <p class="no-data">Tidak ada data insersi pada periode ini.</p>
    @endforelse

    <div class="page-break"></div>

    <h2 class="section-title">Laporan Detail Maintenance CVC</h2>
    @forelse($maintenances as $item)
        <div class="record-block">
            <h3>Detail untuk Pasien: {{ $item->patient_name }} ({{ \Carbon\Carbon::parse($item->maintenance_date)->format('d-m-Y') }})</h3>
            <table class="summary-table">
                <tr><td>No. Rekam Medis:</td><td>{{ $item->medical_record_number }}</td></tr>
                <tr><td>Lokasi Maintenance:</td><td>{{ $item->maintenance_location }}</td></tr>
                <tr><td>Hari Terpasang:</td><td>{{ $item->days_inserted }}</td></tr>
                <tr><td>Perawat:</td><td>{{ $item->nurse_name }}</td></tr>
                <tr><td>Tingkat Kepatuhan:</td><td><strong>{{ $item->compliance_percentage }}%</strong></td></tr>
            </table>

            <h4>Detail Elemen Penilaian:</h4>
            <table class="detail-table">
                <thead><tr><th>Deskripsi</th><th>Status</th><th>Catatan</th></tr></thead>
                <tbody>
                @foreach($item->elements_data as $element)
                    <tr>
                        <td>{{ $element['description'] }}</td>
                        <td>{{ $element['status'] }}</td>
                        <td>{{ $element['notes'] ?? '-' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @if(!$loop->last)<hr>@endif
    @empty
        <p class="no-data">Tidak ada data maintenance pada periode ini.</p>
    @endforelse

    <div class="page-break"></div>

    <h2 class="section-title">Laporan Detail Infeksi</h2>
    @forelse($infections as $item)
        <div class="record-block">
            <h3>Detail untuk Pasien: {{ $item->patient_name }} ({{ \Carbon\Carbon::parse($item->infection_diagnosis_date)->format('d-m-Y') }})</h3>
            <table class="summary-table">
                <tr><td>No. Rekam Medis:</td><td>{{ $item->medical_record_number }}</td></tr>
                <tr><td>Tanggal Diagnosis:</td><td>{{ \Carbon\Carbon::parse($item->infection_diagnosis_date)->format('d F Y') }}</td></tr>
                <tr><td>Jenis Infeksi:</td><td>{{ $item->infection_type }}</td></tr>
                <tr><td>Mikroorganisme:</td><td>{{ $item->microorganism ?? '-' }}</td></tr>
                <tr><td>Gejala Klinis:</td><td>{{ $item->clinical_symptoms ?? '-' }}</td></tr>
                <tr><td>Manajemen/Tindakan:</td><td>{{ $item->management ?? '-' }}</td></tr>
                <tr><td>Status:</td><td><strong>{{ $item->status }}</strong></td></tr>
            </table>
        </div>
        @if(!$loop->last)<hr>@endif
    @empty
        <p class="no-data">Tidak ada data infeksi pada periode ini.</p>
    @endforelse

    <div class="page-break"></div>

    <h2 class="section-title">Laporan Detail Tertusuk Jarum</h2>
    @forelse($needlesticks as $item)
        <div class="record-block">
            <h3>Detail untuk Petugas: {{ $item->injured_person_name }} ({{ \Carbon\Carbon::parse($item->incident_date)->format('d-m-Y') }})</h3>
            <table class="summary-table">
                <tr><td>Tanggal Insiden:</td><td>{{ \Carbon\Carbon::parse($item->incident_date)->format('d F Y') }} pukul {{ $item->incident_time }}</td></tr>
                <tr><td>Nama Petugas:</td><td>{{ $item->injured_person_name }}</td></tr>
                <tr><td>Jabatan:</td><td>{{ $item->injured_person_position }}</td></tr>
                <tr><td>Lokasi Insiden:</td><td>{{ $item->location }}</td></tr>
                <tr><td>Unit/Departemen:</td><td>{{ $item->department }}</td></tr>
                <tr><td>Deskripsi Insiden:</td><td>{{ $item->incident_description }}</td></tr>
                <tr><td>Tindakan Segera:</td><td>{{ implode(', ', $item->immediate_actions ?? []) }}</td></tr>
                <tr><td>Tindakan Lanjutan:</td><td>{{ $item->follow_up_actions }}</td></tr>
            </table>
        </div>
        @if(!$loop->last)<hr>@endif
    @empty
        <p class="no-data">Tidak ada data tertusuk jarum pada periode ini.</p>
    @endforelse
</body>
</html>