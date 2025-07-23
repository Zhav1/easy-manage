<tbody>
    @forelse($data['entries'] as $index => $entry)
    <tr>
        <td style="border: 1px solid #000000;">{{ $index + 1 }}</td>
        <td style="border: 1px solid #000000;">{{ $entry['tgl'] ?? '' }}</td>
        <td style="border: 1px solid #000000;">{{ $entry['profesi'] ?? '' }}</td>
        <td style="border: 1px solid #000000;">{{ $entry['ruang'] ?? '' }}</td>
        <td style="border: 1px solid #000000;">{{ $entry['pelayanan'] ?? '' }}</td>
        <td style="border: 1px solid #000000;">{{ ($entry['sarung_tangan_y'] ?? false) ? '✓' : '' }}</td>
        <td style="border: 1px solid #000000;">{{ ($entry['sarung_tangan_t'] ?? false) ? '✓' : '' }}</td>
        <td style="border: 1px solid #000000;">{{ ($entry['masker_y'] ?? false) ? '✓' : '' }}</td>
        <td style="border: 1px solid #000000;">{{ ($entry['masker_t'] ?? false) ? '✓' : '' }}</td>
        <td style="border: 1px solid #000000;">{{ ($entry['topi_y'] ?? false) ? '✓' : '' }}</td>
        <td style="border: 1px solid #000000;">{{ ($entry['topi_t'] ?? false) ? '✓' : '' }}</td>
        <td style="border: 1px solid #000000;">{{ ($entry['google_y'] ?? false) ? '✓' : '' }}</td>
        <td style="border: 1px solid #000000;">{{ ($entry['google_t'] ?? false) ? '✓' : '' }}</td>
        <td style="border: 1px solid #000000;">{{ ($entry['pakaian_y'] ?? false) ? '✓' : '' }}</td>
        <td style="border: 1px solid #000000;">{{ ($entry['pakaian_t'] ?? false) ? '✓' : '' }}</td>
        <td style="border: 1px solid #000000;">{{ ($entry['sepatu_y'] ?? false) ? '✓' : '' }}</td>
        <td style="border: 1px solid #000000;">{{ ($entry['sepatu_t'] ?? false) ? '✓' : '' }}</td>
        <td style="border: 1px solid #000000;">{{ $entry['kepatuhan'] ?? '' }}</td>
        <td style="border: 1px solid #000000;">{{ $entry['ket'] ?? '' }}</td>
    </tr>
    @empty
        <tr><td colspan="19">Tidak ada entri data untuk form ini.</td></tr>
    @endforelse
</tbody>