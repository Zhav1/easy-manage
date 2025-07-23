<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tgl</th>
            <th>Nama Pasien</th>
            <th>Respon Time (C-A)</th>
            <th>Respon Time (C-B)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data['entries'] as $index => $entry)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ isset($entry['tgl']) ? \Carbon\Carbon::parse($entry['tgl'])->format('d-m-Y') : '' }}</td>
                <td>{{ $entry['nama_pasien'] ?? '' }}</td>
                <td>{{ $entry['respon_time_ca'] ?? '0' }} Menit</td>
                <td>{{ $entry['respon_time_cb'] ?? '0' }} Menit</td>
            </tr>
        @empty
            <tr><td colspan="5" class="no-data">Tidak ada data.</td></tr>
        @endforelse
    </tbody>
</table>