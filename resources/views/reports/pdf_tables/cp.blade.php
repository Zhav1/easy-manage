<table>
    <thead>
        <tr>
            <th>No</th>
            <th>No. MR</th>
            <th>Total Kepatuhan per Pasien</th>
            <th>Varian</th>
        </tr>
    </thead>
    <tbody>
        <tr><td colspan="4"><strong>Judul CP: {{ $data['judul_cp'] ?? 'N/A' }}</strong></td></tr>
    @forelse($data['entries'] as $index => $entry)
        @php
            $p = ($entry['asesmen_p'] ?? 0) + ($entry['fisik_p'] ?? 0) + ($entry['penunjang_p'] ?? 0) + ($entry['obat_p'] ?? 0);
            $n = ($entry['asesmen_n'] ?? 0) + ($entry['fisik_n'] ?? 0) + ($entry['penunjang_n'] ?? 0) + ($entry['obat_n'] ?? 0);
            $c = ($entry['asesmen_c'] ?? 0) + ($entry['fisik_c'] ?? 0) + ($entry['penunjang_c'] ?? 0) + ($entry['obat_c'] ?? 0);
            $total_observed = $p + $n + $c;
        @endphp
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $entry['no_mr'] ?? '' }}</td>
            <td>{{ $total_observed > 0 ? round($p / $total_observed * 100) : 0 }}%</td>
            <td>{{ $entry['varian'] ?? '' }}</td>
        </tr>
    @empty
        <tr><td colspan="4" class="no-data">Tidak ada data.</td></tr>
    @endforelse
    </tbody>
</table>