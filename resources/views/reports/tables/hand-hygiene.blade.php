<tbody>
    @forelse($data['entries'] as $entry)
        <tr>
            <td style="border: 1px solid #000000;">{{ $entry['tgl'] ?? '' }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['sesi'] ?? '' }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['dpjp_kesempatan'] ?? 0 }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['dpjp_handwash'] ?? 0 }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['dpjp_handrub'] ?? 0 }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['perawat_kesempatan'] ?? 0 }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['perawat_handwash'] ?? 0 }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['perawat_handrub'] ?? 0 }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['pendidikan_kesempatan'] ?? 0 }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['pendidikan_handwash'] ?? 0 }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['pendidikan_handrub'] ?? 0 }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['lain_kesempatan'] ?? 0 }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['lain_handwash'] ?? 0 }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['lain_handrub'] ?? 0 }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['total_kesempatan'] ?? 0 }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['total_handwash'] ?? 0 }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['total_handrub'] ?? 0 }}</td>
        </tr>
    @empty
        <tr><td colspan="17">Tidak ada entri data untuk form ini.</td></tr>
    @endforelse
</tbody>