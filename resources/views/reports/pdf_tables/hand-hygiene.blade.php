<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tgl</th>
            <th>Total Sesi</th>
            <th>Kepatuhan DPJP</th>
            <th>Kepatuhan Perawat</th>
            <th>Kepatuhan Pendidikan</th>
            <th>Kepatuhan Nakes Lain</th>
        </tr>
    </thead>
    <tbody>
    @forelse($data['entries'] as $index => $entry)
        @php
            $dpjp_n = $entry['dpjp_kesempatan'] ?? 0;
            $dpjp_c = ($entry['dpjp_handwash'] ?? 0) + ($entry['dpjp_handrub'] ?? 0);
            $perawat_n = $entry['perawat_kesempatan'] ?? 0;
            $perawat_c = ($entry['perawat_handwash'] ?? 0) + ($entry['perawat_handrub'] ?? 0);
            $pendidikan_n = $entry['pendidikan_kesempatan'] ?? 0;
            $pendidikan_c = ($entry['pendidikan_handwash'] ?? 0) + ($entry['pendidikan_handrub'] ?? 0);
            $lain_n = $entry['lain_kesempatan'] ?? 0;
            $lain_c = ($entry['lain_handwash'] ?? 0) + ($entry['lain_handrub'] ?? 0);
        @endphp
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ \Carbon\Carbon::parse($entry['tgl'])->format('d-m-Y') }}</td>
            <td>{{ $entry['sesi'] ?? 0 }}</td>
            <td>{{ $dpjp_n > 0 ? round(min($dpjp_c, $dpjp_n) / $dpjp_n * 100) : 0 }}%</td>
            <td>{{ $perawat_n > 0 ? round(min($perawat_c, $perawat_n) / $perawat_n * 100) : 0 }}%</td>
            <td>{{ $pendidikan_n > 0 ? round(min($pendidikan_c, $pendidikan_n) / $pendidikan_n * 100) : 0 }}%</td>
            <td>{{ $lain_n > 0 ? round(min($lain_c, $lain_n) / $lain_n * 100) : 0 }}%</td>
        </tr>
    @empty
        <tr><td colspan="7" class="no-data">Tidak ada data.</td></tr>
    @endforelse
    </tbody>
</table>