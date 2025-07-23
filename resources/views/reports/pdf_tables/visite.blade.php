<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tgl Visite</th>
            <th>Nama Pasien</th>
            <th>DPJP Utama</th>
            <th>Jam Visite</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data['entries'] as $index => $entry)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ isset($entry['tgl_visite']) ? \Carbon\Carbon::parse($entry['tgl_visite'])->format('d-m-Y') : '' }}</td>
                <td>{{ $entry['nama_pasien'] ?? '' }}</td>
                <td>{{ $entry['dpjp_utama'] ?? '' }}</td>
                <td>{{ $entry['jam_visite_akhir'] ?? '' }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="no-data">Tidak ada data.</td></tr>
        @endforelse
    </tbody>
</table>