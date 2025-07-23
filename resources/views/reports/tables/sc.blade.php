<tbody>
    @forelse($data['entries'] as $index => $entry)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $entry['nama_pasien'] ?? '' }}</td>
            <td>{{ $entry['no_rm'] ?? '' }}</td>
            <td>{{ $entry['diagnosa_kategori'] ?? '' }}</td>
            <td>{{ $entry['jam_tiba_igd'] ?? '' }}</td>
            <td>{{ $entry['jam_diputuskan_operasi'] ?? '' }}</td>
            <td>{{ $entry['jam_mulai_insisi'] ?? '' }}</td>
            <td>{{ $entry['waktu_tanggap'] ?? '' }}</td>
            <td>{{ $entry['gt_30_menit'] ?? '' }}</td>
            <td>{{ $entry['keterangan'] ?? '' }}</td>
        </tr>
    @empty
        <tr><td colspan="10">Tidak ada entri data untuk form ini.</td></tr>
    @endforelse
</tbody>