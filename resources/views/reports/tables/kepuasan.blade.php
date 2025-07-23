<tbody>
    @forelse($data['entries'] as $index => $entry)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $entry['tanggal'] ?? '' }}</td>
            <td>{{ $entry['unit_kerja'] ?? '' }}</td>
            <td>{{ $entry['nilai_ikm'] ?? '' }}</td>
            <td>{{ $entry['jenis_pelayanan'] ?? '' }}</td>
            <td>{{ $entry['nilai_kepuasan'] ?? '' }}</td>
            <td>{{ $entry['komentar'] ?? '' }}</td>
        </tr>
    @empty
        <tr><td colspan="7">Tidak ada entri data untuk form ini.</td></tr>
    @endforelse
</tbody>