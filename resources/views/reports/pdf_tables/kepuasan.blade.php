<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Unit Kerja</th>
            <th>Jenis Pelayanan</th>
            <th>Nilai Kepuasan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data['entries'] as $index => $entry)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ isset($entry['tanggal']) ? \Carbon\Carbon::parse($entry['tanggal'])->format('d-m-Y') : '' }}</td>
                <td>{{ $entry['unit_kerja'] ?? '' }}</td>
                <td>{{ $entry['jenis_pelayanan'] ?? '' }}</td>
                <td>{{ $entry['nilai_kepuasan'] ?? '' }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="no-data">Tidak ada data.</td></tr>
        @endforelse
    </tbody>
</table>