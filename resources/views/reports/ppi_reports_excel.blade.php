<table>
    <thead>
        <tr>
            <th colspan="9" style="font-size: 16px; font-weight: bold; text-align: center;">Laporan Lengkap PPI</th>
        </tr>
        <tr>
            <th colspan="9" style="font-size: 12px; text-align: center;">
                Periode: {{ $report_start_date }} {{ $report_end_date ? '- ' . $report_end_date : '' }}
            </th>
        </tr>
        <tr></tr> </thead>
</table>

<table>
    <thead>
        <tr>
            <th colspan="8" style="font-weight: bold; background-color: #E0E0E0;">DATA INSERSI CVC</th>
        </tr>
        <tr>
            <th style="font-weight: bold;">No</th>
            <th style="font-weight: bold;">Nama Pasien</th>
            <th style="font-weight: bold;">No. RM</th>
            <th style="font-weight: bold;">Tgl Insersi</th>
            <th style="font-weight: bold;">Lokasi Insersi</th>
            <th style="font-weight: bold;">Operator</th>
            <th style="font-weight: bold;">Kepatuhan</th>
            <th style="font-weight: bold;">Detail Elemen Penilaian</th>
        </tr>
    </thead>
    <tbody>
        @forelse($insertions as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->patient_name }}</td>
                <td>{{ $item->medical_record_number }}</td>
                <td>{{ \Carbon\Carbon::parse($item->insertion_date)->format('d-m-Y') }}</td>
                <td>{{ $item->insertion_location }}</td>
                <td>{{ $item->operator_name }}</td>
                <td>{{ $item->compliance_percentage }}%</td>
                <td>
                    @foreach($item->elements_data as $element)
                        {{ $element['description'] }}: <strong>{{ $element['status'] }}</strong><br>
                    @endforeach
                </td>
            </tr>
        @empty
            <tr><td colspan="8">Tidak ada data insersi pada periode ini.</td></tr>
        @endforelse
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th colspan="9" style="font-weight: bold; background-color: #E0E0E0;">DATA MAINTENANCE CVC</th>
        </tr>
        <tr>
            <th style="font-weight: bold;">No</th>
            <th style="font-weight: bold;">Nama Pasien</th>
            <th style="font-weight: bold;">No. RM</th>
            <th style="font-weight: bold;">Tgl Maintenance</th>
            <th style="font-weight: bold;">Lokasi</th>
            <th style="font-weight: bold;">Hari Terpasang</th>
            <th style="font-weight: bold;">Perawat</th>
            <th style="font-weight: bold;">Kepatuhan</th>
            <th style="font-weight: bold;">Detail Elemen Penilaian</th>
        </tr>
    </thead>
    <tbody>
        @forelse($maintenances as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->patient_name }}</td>
                <td>{{ $item->medical_record_number }}</td>
                <td>{{ \Carbon\Carbon::parse($item->maintenance_date)->format('d-m-Y') }}</td>
                <td>{{ $item->maintenance_location }}</td>
                <td>{{ $item->days_inserted }}</td>
                <td>{{ $item->nurse_name }}</td>
                <td>{{ $item->compliance_percentage }}%</td>
                <td>
                    @foreach($item->elements_data as $element)
                        {{ $element['description'] }}: <strong>{{ $element['status'] }}</strong><br>
                    @endforeach
                </td>
            </tr>
        @empty
            <tr><td colspan="9">Tidak ada data maintenance pada periode ini.</td></tr>
        @endforelse
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th colspan="9" style="font-weight: bold; background-color: #E0E0E0;">DATA LAPORAN INFEKSI</th>
        </tr>
        <tr>
            <th style="font-weight: bold;">No</th>
            <th style="font-weight: bold;">Nama Pasien</th>
            <th style="font-weight: bold;">No. RM</th>
            <th style="font-weight: bold;">Tgl Diagnosis</th>
            <th style="font-weight: bold;">Jenis Infeksi</th>
            <th style="font-weight: bold;">Mikroorganisme</th>
            <th style="font-weight: bold;">Gejala Klinis</th>
            <th style="font-weight: bold;">Manajemen</th>
            <th style="font-weight: bold;">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($infections as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->patient_name }}</td>
                <td>{{ $item->medical_record_number }}</td>
                <td>{{ \Carbon\Carbon::parse($item->infection_diagnosis_date)->format('d-m-Y') }}</td>
                <td>{{ $item->infection_type }}</td>
                <td>{{ $item->microorganism }}</td>
                <td>{{ $item->clinical_symptoms }}</td>
                <td>{{ $item->management }}</td>
                <td>{{ $item->status }}</td>
            </tr>
        @empty
            <tr><td colspan="9">Tidak ada data infeksi pada periode ini.</td></tr>
        @endforelse
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th colspan="9" style="font-weight: bold; background-color: #E0E0E0;">DATA TERTUSUK JARUM</th>
        </tr>
        <tr>
            <th style="font-weight: bold;">No</th>
            <th style="font-weight: bold;">Nama Petugas</th>
            <th style="font-weight: bold;">Jabatan</th>
            <th style="font-weight: bold;">Tgl Insiden</th>
            <th style="font-weight: bold;">Waktu</th>
            <th style="font-weight: bold;">Lokasi Insiden</th>
            <th style="font-weight: bold;">Unit</th>
            <th style="font-weight: bold;">Deskripsi</th>
            <th style="font-weight: bold;">Tindakan Lanjutan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($needlesticks as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->injured_person_name }}</td>
                <td>{{ $item->injured_person_position }}</td>
                <td>{{ \Carbon\Carbon::parse($item->incident_date)->format('d-m-Y') }}</td>
                <td>{{ $item->incident_time }}</td>
                <td>{{ $item->location }}</td>
                <td>{{ $item->department }}</td>
                <td>{{ $item->incident_description }}</td>
                <td>{{ $item->follow_up_actions }}</td>
            </tr>
        @empty
            <tr><td colspan="9">Tidak ada data tertusuk jarum pada periode ini.</td></tr>
        @endforelse
    </tbody>
</table>