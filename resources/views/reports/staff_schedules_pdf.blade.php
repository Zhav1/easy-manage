<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
    /* Base Styles */
    body { 
        font-family: 'DejaVu Sans', sans-serif; 
        font-size: 9pt; /* Consistent units */
        margin: 0;
        padding: 0;
    }
    
    /* Header Styles */
    .header { 
        text-align: left; 
        margin-bottom: 20pt;
    }
    .header h1 { 
        font-size: 18pt; 
        margin-bottom: 5pt;
    }
    .header p { 
        margin: 2pt 0; 
        font-size: 10pt; 
    }
    .section-title { 
        font-size: 14pt; 
        font-weight: bold; 
        text-align: left; 
        margin: 15pt 0 8pt 0;
    }
    
    /* Table Styles */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20pt;
        table-layout: fixed;
    }
    th, td {
        border: 0.5pt solid #333;
        text-align: center;
        vertical-align: middle;
        word-wrap: break-word;
        height: 16pt;
    }
    th {
        background-color: #f2f2f2;
        font-weight: bold;
        padding: 3pt;
    }
    
    /* Staff Column */
    .staff-name-col {
        width: 75pt; /* Wider column */
        min-width: 75pt;
        text-align: left;
        font-weight: bold;
        font-size: 9pt;
        vertical-align: middle;
        padding: 3pt;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Date Headers */
    .header-day-date {
        width: 36pt; /* 3 x 12pt */
        font-size: 9pt;
        padding: 3pt;
    }
    .text-red { 
        color: #d32f2f; /* Darker red for better visibility */
    }
    
    /* Shift Cells */
    .shift-header-cell {
        width: 12pt;
        font-size: 7pt;
        padding: 2pt 0;
    }
    .shift-data-cell {
        width: 12pt;
        padding: 0;
    }
    
    /* Shift Colors */
    .shift-pagi { background-color: #d1e7ff; }
    .shift-siang { background-color: #fff3cd; }
    .shift-malam { background-color: #e9d5ff; }
    
    /* Page Layout */
    .page-break { 
        page-break-after: always; 
    }
    @page { 
        size: A3 landscape; 
        margin: 0.7in; 
    }
    
    /* Empty State */
    .empty-state {
        text-align: center; 
        padding: 20pt;
        color: #666;
    }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p><b>Tanggal Laporan:</b> {{ $date }}</p>
        <p><b>Periode:</b> {{ $report_period_title }}</p>
        <p><b>Rumah Sakit:</b> {{ $userInfo->hospital->name ?? 'N/A' }}</p>
        <p><b>Ruangan:</b> {{ $userInfo->department->name ?? 'N/A' }}</p>
        <p><b>Kepala Ruangan:</b> {{ $userInfo->name ?? 'N/A' }}</p>
    </div>

    @forelse($all_monthly_schedules_data as $monthData)
        @if(!$loop->first)
            <div class="page-break"></div>
        @endif

        @php
            $daysInMonth = $monthData['days_in_month'];
            $midPoint = 15;
        @endphp

        {{-- TABLE 1: DAYS 1-15 --}}
        <h2 class="section-title">{{ $monthData['month_name'] }} (Hari 1 - 15)</h2>
        <table>
            <thead>
                <tr>
                    <th rowspan="2" colspan="3" class="staff-name-col">Nama Staff</th>
                    @for ($i = 1; $i <= $midPoint; $i++)
                        @php $currentDate = \Carbon\Carbon::parse($monthData['start_date'])->setDay($i); @endphp
                        <th colspan="3" class="header-day-date @if($currentDate->isSunday()) text-red @endif">{{ $i }}</th>
                    @endfor
                </tr>
                <tr>
                    @for ($i = 1; $i <= $midPoint; $i++)
                        <th class="shift-header-cell">P</th>
                        <th class="shift-header-cell">S</th>
                        <th class="shift-header-cell">M</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @forelse($monthData['monthly_schedules'] as $staffRow)
                    <tr>
                        <td colspan="3" class="staff-name-col">
                            {{ \Illuminate\Support\Str::limit($staffRow['staff_name'] ?? 'N/A', 20, '...') }}
                        </td>
                        @for ($i = 1; $i <= $midPoint; $i++)
                            @php
                                $dateKey = \Carbon\Carbon::parse($monthData['start_date'])->setDay($i)->format('Y-m-d');
                                $shiftsForDay = $staffRow['schedules_by_date'][$dateKey] ?? ['Pagi' => false, 'Siang' => false, 'Malam' => false];
                            @endphp
                            <td class="shift-data-cell @if($shiftsForDay['Pagi']) shift-pagi @endif">&nbsp;</td>
                            <td class="shift-data-cell @if($shiftsForDay['Siang']) shift-siang @endif">&nbsp;</td>
                            <td class="shift-data-cell @if($shiftsForDay['Malam']) shift-malam @endif">&nbsp;</td>
                        @endfor
                    </tr>
                @empty
                    <tr><td colspan="{{ 1 + ($midPoint * 3) }}" class="empty-state">Tidak ada data jadwal untuk staff pada bulan ini.</td></tr>
                @endforelse
            </tbody>
        </table>

        @if ($daysInMonth > $midPoint)
            {{-- TABLE 2: DAYS 16-END --}}
            <h2 class="section-title">{{ $monthData['month_name'] }} (Hari 16 - {{ $daysInMonth }})</h2>
            <table>
                <thead>
                    <tr>
                        <th rowspan="2" colspan="3" class="staff-name-col">Nama Staff</th>
                        @for ($i = $midPoint + 1; $i <= $daysInMonth; $i++)
                            @php $currentDate = \Carbon\Carbon::parse($monthData['start_date'])->setDay($i); @endphp
                            <th colspan="3" class="header-day-date @if($currentDate->isSunday()) text-red @endif">{{ $i }}</th>
                        @endfor
                    </tr>
                    <tr>
                        @for ($i = $midPoint + 1; $i <= $daysInMonth; $i++)
                            <th class="shift-header-cell">P</th>
                            <th class="shift-header-cell">S</th>
                            <th class="shift-header-cell">M</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @forelse($monthData['monthly_schedules'] as $staffRow)
                        <tr>
                            <td colspan="3" class="staff-name-col">
                                {{ \Illuminate\Support\Str::limit($staffRow['staff_name'] ?? 'N/A', 20, '...') }}
                            </td>
                            @for ($i = $midPoint + 1; $i <= $daysInMonth; $i++)
                                @php
                                    $dateKey = \Carbon\Carbon::parse($monthData['start_date'])->setDay($i)->format('Y-m-d');
                                    $shiftsForDay = $staffRow['schedules_by_date'][$dateKey] ?? ['Pagi' => false, 'Siang' => false, 'Malam' => false];
                                @endphp
                                <td class="shift-data-cell @if($shiftsForDay['Pagi']) shift-pagi @endif">&nbsp;</td>
                                <td class="shift-data-cell @if($shiftsForDay['Siang']) shift-siang @endif">&nbsp;</td>
                                <td class="shift-data-cell @if($shiftsForDay['Malam']) shift-malam @endif">&nbsp;</td>
                            @endfor
                        </tr>
                    @empty
                        <tr><td colspan="{{ 1 + (($daysInMonth - $midPoint) * 3) }}" class="empty-state">Tidak ada data jadwal untuk staff pada bulan ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        @endif
    @empty
        <p class="empty-state">Tidak ada jadwal dinas untuk periode yang dipilih.</p>
    @endforelse
</body>
</html>