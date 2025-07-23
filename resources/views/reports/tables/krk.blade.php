<tbody>
    @forelse($data['entries'] as $entry)
        <tr>
            <td>{{ $entry['tgl'] ?? '' }}</td>
            <td>{{ $entry['isi_komplain'] ?? '' }}</td>
            <td>{{ $entry['kategori_komplain'] ?? '' }}</td>
            <td>{{ ($entry['lisan'] ?? false) ? '✓' : '' }}</td>
            <td>{{ ($entry['tulisan'] ?? false) ? '✓' : '' }}</td>
            <td>{{ ($entry['media_masa'] ?? false) ? '✓' : '' }}</td>
            <td>{{ ($entry['grading_merah'] ?? false) ? '✓' : '' }}</td>
            <td>{{ ($entry['grading_kuning'] ?? false) ? '✓' : '' }}</td>
            <td>{{ ($entry['grading_hijau'] ?? false) ? '✓' : '' }}</td>
            <td>{{ $entry['waktu_tanggap'] ?? 0 }}</td>
            <td>{{ ($entry['penyelesaian_ya'] ?? false) ? '✓' : '' }}</td>
            <td>{{ ($entry['penyelesaian_tidak'] ?? false) ? '✓' : '' }}</td>
            <td>{{ $entry['ket'] ?? '' }}</td>
        </tr>
    @empty
        <tr><td colspan="13">Tidak ada entri data untuk form ini.</td></tr>
    @endforelse
</tbody>