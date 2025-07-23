<table>
    <thead>
        <tr>
            <th colspan="94" style="font-size: 16px; font-weight: bold; text-align: center;">Laporan Jadwal Dinas</th>
        </tr>
        <tr>
            <th colspan="94" style="font-size: 12px; text-align: center;">Periode: {{ $report_period_title }}</th>
        </tr>
        <tr></tr> <!-- Blank row for spacing -->
    </thead>
    <tbody>
    @foreach($all_monthly_schedules_data as $monthData)
        @php
            $daysInMonth = $monthData['days_in_month'];
            // Calculate the total number of columns for this month's table
            $totalColumns = 1 + ($daysInMonth * 3);
        @endphp
        
        {{-- Month Header --}}
        <tr>
            <td colspan="{{ $totalColumns }}" style="font-weight: bold; background-color: #E0E0E0; text-align: left; border: 1px solid #000000;">
                Jadwal Dinas Staff {{ $monthData['month_name'] }}
            </td>
        </tr>

        {{-- Date and Shift Headers --}}
        <tr>
            <td rowspan="2" style="font-weight: bold; vertical-align: middle; text-align: left; border: 1px solid #000000;">Nama Staff</td>
            @for ($i = 1; $i <= $daysInMonth; $i++)
                <td colspan="3" style="font-weight: bold; text-align: center; border: 1px solid #000000;">{{ $i }}</td>
            @endfor
        </tr>
        <tr>
            @for ($i = 1; $i <= $daysInMonth; $i++)
                <td style="font-weight: bold; text-align: center; border: 1px solid #000000;">P</td>
                <td style="font-weight: bold; text-align: center; border: 1px solid #000000;">S</td>
                <td style="font-weight: bold; text-align: center; border: 1px solid #000000;">M</td>
            @endfor
        </tr>

        {{-- Data Rows --}}
        @forelse($monthData['monthly_schedules'] as $staffRow)
            <tr>
                <td style="text-align: left; border: 1px solid #000000;">{{ $staffRow['staff_name'] }}</td>
                @for ($dayIndex = 1; $dayIndex <= $daysInMonth; $dayIndex++)
                    @php
                        $dateKey = \Carbon\Carbon::parse($monthData['start_date'])->setDay($dayIndex)->format('Y-m-d');
                        $shifts = $staffRow['schedules_by_date'][$dateKey] ?? ['Pagi' => false, 'Siang' => false, 'Malam' => false];
                    @endphp
                    <td style="background-color: {{ $shifts['Pagi'] ? '#d1e7ff' : '#ffffff' }}; border: 1px solid #000000;"></td>
                    <td style="background-color: {{ $shifts['Siang'] ? '#fff3cd' : '#ffffff' }}; border: 1px solid #000000;"></td>
                    <td style="background-color: {{ $shifts['Malam'] ? '#e9d5ff' : '#ffffff' }}; border: 1px solid #000000;"></td>
                @endfor
            </tr>
        @empty
            <tr>
                <td colspan="{{ $totalColumns }}">Tidak ada data jadwal untuk bulan ini.</td>
            </tr>
        @endforelse
        <tr></tr> <!-- Spacer row after each month's data -->
    @endforeach
    </tbody>
</table>