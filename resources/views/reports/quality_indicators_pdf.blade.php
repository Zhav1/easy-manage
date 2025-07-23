<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        @page { margin: 25px; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; }
        .header p { margin: 2px 0; font-size: 11px; }
        .page-break { page-break-after: always; }
        .section-title { font-size: 14px; font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #333; padding-bottom: 5px; }
        .compliance-info { font-size: 12px; font-weight: bold; margin-bottom: 15px; background-color: #f2f2f2; padding: 8px; border-radius: 4px; }
        .chart-container { text-align: center; margin-bottom: 15px; }
        .chart-container img {
            width: 100%; /* Make the image fill the container's width */
            height: auto; /* The height will now be consistent because the aspect ratio is fixed */
            border: 1px solid #eee;
            padding: 5px;
        }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 8px; }
        th, td { border: 1px solid #ccc; padding: 4px; text-align: center; vertical-align: middle; word-wrap: break-word; }
        th { background-color: #f2f2f2; }
        .no-data { text-align: center; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Tanggal Laporan: {{ $date }}</p>
        <p>Rumah Sakit: {{ $userInfo->hospital->name ?? 'N/A' }} | Ruangan: {{ $userInfo->department->name ?? 'N/A' }}</p>
    </div>

    @foreach($reportData as $formType => $formReport)
        <div>
            <h2 class="section-title">{{ $formReport['name'] }}</h2>
            <div class="compliance-info">
                Tingkat Kepatuhan Keseluruhan: {{ $formReport['compliance'] }}%
            </div>
            
            <div class="chart-container">
                @if(isset($chartImages[$formType]) && $chartImages[$formType])
                    <img src="{{ $chartImages[$formType] }}">
                @else
                    <p class="no-data">Grafik tidak tersedia.</p>
                @endif
            </div>

            <h3 style="font-size: 11px; font-weight: bold; margin-bottom: 5px;">Ringkasan Data:</h3>
            @includeFirst(['reports.pdf_tables.' . $formType, 'reports.pdf_tables.default'], ['data' => $formReport['data']])
        </div>

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>