<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tgl</th>
            <th>Staf yang Diobservasi</th>
            <th>Hasil Identifikasi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data['entries'] as $index => $entry)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ isset($entry['tgl']) ? \Carbon\Carbon::parse($entry['tgl'])->format('d-m-Y') : '' }}</td>
                <td>{{ $entry['staf'] ?? '' }}</td>
                <td>{{ ($entry['dilakukan'] ?? false) ? 'Dilakukan' : 'Tidak Dilakukan' }}</td>
            </tr>
        @empty
            <tr><td colspan="4" class="no-data">Tidak ada data.</td></tr>
        @endforelse
    </tbody>
</table>