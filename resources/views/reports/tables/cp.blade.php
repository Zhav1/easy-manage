<tbody>
    <tr><td colspan="16"><strong>Judul CP: {{ $data['judul_cp'] ?? 'N/A' }}</strong></td></tr>
    @forelse($data['entries'] as $entry)
        <tr>
            <td>{{ $entry['no_mr'] ?? '' }}</td>
            <td>{{ $entry['asesmen_p'] ?? 0 }}</td>
            <td>{{ $entry['asesmen_n'] ?? 0 }}</td>
            <td>{{ $entry['asesmen_c'] ?? 0 }}</td>
            <td>{{ $entry['fisik_p'] ?? 0 }}</td>
            <td>{{ $entry['fisik_n'] ?? 0 }}</td>
            <td>{{ $entry['fisik_c'] ?? 0 }}</td>
            <td>{{ $entry['penunjang_p'] ?? 0 }}</td>
            <td>{{ $entry['penunjang_n'] ?? 0 }}</td>
            <td>{{ $entry['penunjang_c'] ?? 0 }}</td>
            <td>{{ $entry['obat_p'] ?? 0 }}</td>
            <td>{{ $entry['obat_n'] ?? 0 }}</td>
            <td>{{ $entry['obat_c'] ?? 0 }}</td>
            <td>{{ $entry['total'] ?? 0 }}</td>
            <td>{{ $entry['varian'] ?? '' }}</td>
            <td>{{ $entry['ket'] ?? '' }}</td>
        </tr>
    @empty
        <tr><td colspan="16">Tidak ada entri data untuk form ini.</td></tr>
    @endforelse
</tbody>