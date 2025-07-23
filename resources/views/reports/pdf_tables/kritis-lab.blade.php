<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tgl</th>
            <th>Critical Value</th>
            <th>Respon Time</th>
            <th>Status Lapor</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data['entries'] as $index => $entry)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ isset($entry['tgl']) ? \Carbon\Carbon::parse($entry['tgl'])->format('d-m-Y') : '' }}</td>
                <td>{{ $entry['critical_value'] ?? '' }}</td>
                <td>{{ $entry['respon_time'] ?? '0' }} Menit</td>
                <td>{{ $entry['pelaporan_status'] ?? '' }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="no-data">Tidak ada data.</td></tr>
        @endforelse
    </tbody>
</table>