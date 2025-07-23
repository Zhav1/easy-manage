<tbody>
    @forelse($data['entries'] as $entry)
        <tr>
            <td>{{ $entry['tgl'] ?? '' }}</td>
            <td>{{ $entry['staf'] ?? '' }}</td>
            <td>{{ ($entry['obat'] ?? false) ? '✓' : '' }}</td>
            <td>{{ ($entry['darah'] ?? false) ? '✓' : '' }}</td>
            <td>{{ ($entry['diet'] ?? false) ? '✓' : '' }}</td>
            <td>{{ ($entry['spesimen'] ?? false) ? '✓' : '' }}</td>
            <td>{{ ($entry['diagnostik'] ?? false) ? '✓' : '' }}</td>
            <td>{{ ($entry['verbal_nama'] ?? false) ? '✓' : '' }}</td>
            <td>{{ ($entry['verbal_tgl_lahir'] ?? false) ? '✓' : '' }}</td>
            <td>{{ ($entry['visual_nama'] ?? false) ? '✓' : '' }}</td>
            <td>{{ ($entry['visual_rm'] ?? false) ? '✓' : '' }}</td>
            <td>{{ ($entry['dilakukan'] ?? false) ? '✓' : '' }}</td>
            <td>{{ ($entry['tidak_dilakukan'] ?? false) ? '✓' : '' }}</td>
        </tr>
    @empty
        <tr><td colspan="13">Tidak ada entri data untuk form ini.</td></tr>
    @endforelse
</tbody>