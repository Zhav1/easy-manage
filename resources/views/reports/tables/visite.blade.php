<tbody>
    @forelse($data['entries'] as $index => $entry)
        <tr>
            <td>{{ $index + 1 }}</td><td>{{ $entry['tgl_registrasi'] ?? '' }}</td>
            <td>{{ $entry['nama_pasien'] ?? '' }}</td><td>{{ $entry['no_rm'] ?? '' }}</td>
            <td>{{ $entry['ruangan'] ?? '' }}</td><td>{{ $entry['jml_hari_efektif'] ?? '' }}</td>
            <td>{{ $entry['jml_hari_rawat'] ?? '' }}</td><td>{{ $entry['dpjp_utama'] ?? '' }}</td>
            <td>{{ $entry['smf'] ?? '' }}</td><td>{{ $entry['tgl_visite'] ?? '' }}</td>
            <td>{{ $entry['jam'] ?? '' }}</td><td>{{ $entry['val_i'] ?? 0 }}</td>
            <td>{{ $entry['val_ii'] ?? 0 }}</td><td>{{ $entry['val_iii'] ?? 0 }}</td>
            <td>{{ $entry['val_iv'] ?? 0 }}</td><td>{{ $entry['total'] ?? 0 }}</td>
            <td>{{ $entry['jam_visite_akhir'] ?? '' }}</td>
        </tr>
    @empty
        <tr><td colspan="17">Tidak ada entri data untuk form ini.</td></tr>
    @endforelse
</tbody>