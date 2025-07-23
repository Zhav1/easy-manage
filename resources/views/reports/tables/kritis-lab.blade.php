<tbody>
    @forelse($data['entries'] as $index => $entry)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $entry['tgl'] ?? '' }}</td>
            <td>{{ $entry['no_rm'] ?? '' }}</td>
            <td>{{ $entry['nama_pasien'] ?? '' }}</td>
            <td>{{ $entry['critical_value'] ?? '' }}</td>
            <td>{{ $entry['waktu_hasil_keluar'] ?? '' }}</td>
            <td>{{ $entry['waktu_dilaporkan'] ?? '' }}</td>
            <td>{{ $entry['nama_penerima'] ?? '' }}</td>
            <td>{{ $entry['respon_time'] ?? '' }}</td>
            <td>{{ $entry['pelaporan_status'] ?? '' }}</td>
        </tr>
    @empty
        <tr><td colspan="10">Tidak ada entri data untuk form ini.</td></tr>
    @endforelse
</tbody>