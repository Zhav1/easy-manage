<tbody>
    @forelse($data['entries'] as $index => $entry)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $entry['unit_kerja'] ?? '' }}</td>
            <td>{{ $entry['nama_pasien'] ?? '' }}</td>
            <td>{{ $entry['no_rm'] ?? '' }}</td>
            <td>{{ $entry['jumlah_resep'] ?? 0 }}</td>
            <td>{{ ($entry['formularium_nasional'] ?? false) ? '✓' : '' }}</td>
            <td>{{ ($entry['non_formularium'] ?? false) ? '✓' : '' }}</td>
        </tr>
    @empty
        <tr><td colspan="7">Tidak ada entri data untuk form ini.</td></tr>
    @endforelse
</tbody>