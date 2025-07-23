<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tgl</th>
            <th>Isi Komplain</th>
            <th>Grading</th>
            <th>Waktu Tanggap</th>
            <th>Selesai Sesuai Waktu</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data['entries'] as $index => $entry)
            @php
                $grading = '';
                if($entry['grading_merah'] ?? false) $grading = 'Merah';
                if($entry['grading_kuning'] ?? false) $grading = 'Kuning';
                if($entry['grading_hijau'] ?? false) $grading = 'Hijau';
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ isset($entry['tgl']) ? \Carbon\Carbon::parse($entry['tgl'])->format('d-m-Y') : '' }}</td>
                <td>{{ \Illuminate\Support\Str::limit($entry['isi_komplain'] ?? '', 50) }}</td>
                <td>{{ $grading }}</td>
                <td>{{ $entry['waktu_tanggap'] ?? '' }} hari</td>
                <td>{{ ($entry['penyelesaian_ya'] ?? false) ? 'Ya' : 'Tidak' }}</td>
            </tr>
        @empty
            <tr><td colspan="6" class="no-data">Tidak ada data.</td></tr>
        @endforelse
    </tbody>
</table>