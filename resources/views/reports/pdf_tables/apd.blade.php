<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tgl</th>
            <th>Profesi</th>
            <th>Ruang</th>
            <th>Kepatuhan</th>
        </tr>
    </thead>
    <tbody>
    @forelse($data['entries'] as $index => $entry)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ \Carbon\Carbon::parse($entry['tgl'])->format('d-m-Y') }}</td>
            <td>{{ $entry['profesi'] ?? '' }}</td>
            <td>{{ $entry['ruang'] ?? '' }}</td>
            <td>{{ $entry['kepatuhan'] ?? 'Tidak' }}</td>
        </tr>
    @empty
        <tr><td colspan="5" class="no-data">Tidak ada data.</td></tr>
    @endforelse
    </tbody>
</table>