<tbody>
    @forelse($data['entries'] as $index => $entry)
        <tr>
            <td style="border: 1px solid #000000;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['nama_pasien'] ?? '' }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['no_rm'] ?? '' }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['assessment_awal'] ?? '' }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['assessment_ulang'] ?? '' }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['intervensi'] ?? '' }}</td>
            <td style="border: 1px solid #000000;">{{ ($entry['ketiga_upaya_ya'] ?? false) ? '✓' : '' }}</td>
            <td style="border: 1px solid #000000;">{{ ($entry['ketiga_upaya_tidak'] ?? false) ? '✓' : '' }}</td>
        </tr>
    @empty
        <tr><td colspan="8">Tidak ada entri data untuk form ini.</td></tr>
    @endforelse
</tbody>