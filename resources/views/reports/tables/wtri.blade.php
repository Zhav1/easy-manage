<tbody>
    <tr><td colspan="9" style="text-align: left;"><strong>Unit Kerja: {{ $data['unit'] ?? 'N/A' }}</strong></td></tr>
    @forelse($data['entries'] as $index => $entry)
        <tr>
            <td style="border: 1px solid #000000;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['tgl'] ?? '' }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['no_rm'] ?? '' }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['nama_pasien'] ?? '' }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['jam_reg_pendaftaran'] ?? '' }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['jam_reg_poli'] ?? '' }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['jam_dilayani_dokter'] ?? '' }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['respon_time_ca'] ?? '' }}</td>
            <td style="border: 1px solid #000000;">{{ $entry['respon_time_cb'] ?? '' }}</td>
        </tr>
    @empty
        <tr><td colspan="9">Tidak ada entri data untuk form ini.</td></tr>
    @endforelse
</tbody>