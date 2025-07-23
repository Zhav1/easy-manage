<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Pasien</th>
            <th>Kategori</th>
            <th>Waktu Tanggap (Menit)</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data['entries'] as $index => $entry)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $entry['nama_pasien'] ?? '' }}</td>
                <td>{{ $entry['diagnosa_kategori'] ?? '' }}</td>
                <td>{{ $entry['waktu_tanggap'] ?? '' }}</td>
                <td>{{ ($entry['gt_30_menit'] ?? 'Tidak') === 'Ya' ? 'Luar Target (>30 Menit)' : 'Dalam Target (≤30 Menit)' }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="no-data">Tidak ada data.</td></tr>
        @endforelse
    </tbody>
</table>