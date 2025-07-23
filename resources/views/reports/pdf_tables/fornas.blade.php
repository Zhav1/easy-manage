<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Unit Kerja</th>
            <th>Jumlah Resep</th>
            <th>Sesuai Fornas</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data['entries'] as $index => $entry)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $entry['unit_kerja'] ?? '' }}</td>
                <td>{{ $entry['jumlah_resep'] ?? 0 }}</td>
                <td>{{ ($entry['formularium_nasional'] ?? false) ? 'Ya' : 'Tidak' }}</td>
            </tr>
        @empty
            <tr><td colspan="4" class="no-data">Tidak ada data.</td></tr>
        @endforelse
    </tbody>
</table>