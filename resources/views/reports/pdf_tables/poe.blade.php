<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tgl</th>
            <th>Nama Pasien</th>
            <th>Tindakan Bedah</th>
            <th>Penundaan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data['entries'] as $index => $entry)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ isset($entry['tgl']) ? \Carbon\Carbon::parse($entry['tgl'])->format('d-m-Y') : '' }}</td>
                <td>{{ $entry['nama_pasien'] ?? '' }}</td>
                <td>{{ $entry['tindakan_bedah'] ?? '' }}</td>
                <td>{{ ($entry['penundaan_gt_1hr'] ?? false) ? '> 1 Jam' : '≤ 1 Jam' }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="no-data">Tidak ada data.</td></tr>
        @endforelse
    </tbody>
</table>