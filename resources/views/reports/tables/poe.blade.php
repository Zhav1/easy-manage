<tbody>
    @forelse($data['entries'] as $entry)
        <tr>
            <td>{{ $entry['tgl'] ?? '' }}</td>
            <td>{{ $entry['nama_pasien'] ?? '' }}</td>
            <td>{{ $entry['no_rm'] ?? '' }}</td>
            <td>{{ $entry['ruangan'] ?? '' }}</td>
            <td>{{ $entry['diagnosa'] ?? '' }}</td>
            <td>{{ $entry['tindakan_bedah'] ?? '' }}</td>
            <td>{{ $entry['dpjp_bedah'] ?? '' }}</td>
            <td>{{ $entry['jam_rencana_operasi'] ?? '' }}</td>
            <td>{{ $entry['jam_insisi'] ?? '' }}</td>
            <td>{{ ($entry['penundaan_gt_1hr'] ?? false) ? '✓' : '' }}</td>
            <td>{{ ($entry['penundaan_lt_1hr'] ?? false) ? '✓' : '' }}</td>
            <td>{{ $entry['keterangan'] ?? '' }}</td>
        </tr>
    @empty
        <tr><td colspan="12">Tidak ada entri data untuk form ini.</td></tr>
    @endforelse
</tbody>