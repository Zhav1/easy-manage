<!DOCTYPE html>
<html lang="en" class="h-full bg-white w-screen">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Indikator Mutu - Dashboard Rumah Sakit</title>
<link rel="stylesheet" href="{{ asset('css/indikator-mutu.css') }}">
@vite('resources/css/app.css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
{{-- Add Moment.js for date calculations --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="{{ asset('js/indikator-mutu.js') }}"></script>
</head>
<style>
    @media (max-width: 768px) {
    .pl-60 {
        padding-left: 1rem;
    }
    .pr-5 {
        padding-right: 1rem;
    }
}



</style>
<body class="bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100">
    <script>
        // This token is used for Bearer token authentication.
        // For strict HTTPOnly cookie usage with Laravel Sanctum,
        // you would typically remove this line and rely on Laravel's
        // session cookies for stateful authentication from your frontend.
        // See the HTTPOnly section in the response for more details.
        window.authToken = "{{ session('token') }}";
    </script>
    @include('components.sidebar-navbar')
    <div class="p-4 pt-20 pl-60" >
        <main class="flex-1">
            <div class="rounded-3xl p-8 mb-8 shadow-xl bg-white">
                <h1 class="text-4xl font-bold text-black mb-3">
                    <i class="fas fa-chart-line mr-3 text-green-500"></i>Indikator Mutu
                </h1>
                <p class="text-gray-600 text-lg">Sistem Monitoring Kualitas Pelayanan Rumah Sakit</p>
            </div>
        </main>

    <div class="indicator-table-container overflow-x-auto">
        <table class="indicator-table min-w-full">
            <tr>
                {{-- Updated onclick to use new showSection for all --}}
                <td onclick="showSection('list')" class="list-button">LIST</td>
                <td onclick="showSection('hand-hygiene')">HAND HYGIENE</td>
                <td onclick="showSection('apd')">APD</td>
                <td onclick="showSection('identifikasi')">IDENTIFIKASI</td>
                <td onclick="showSection('wtri')">WTRI</td>
                <td onclick="showSection('kritis-lab')">KRITIS LAB</td>
                <td onclick="showSection('fornas')">FORNAS</td>
                <td onclick="showSection('visite')">VISITE</td>
                <td onclick="showSection('jatuh')">JATUH</td>
                <td onclick="showSection('cp')">CP</td>
                <td onclick="showSection('kepuasan')">KEPUASAN</td>
                <td onclick="showSection('krk')">KRK</td>
                <td onclick="showSection('poe')">POE</td>
                <td onclick="showSection('sc')">SC</td>
                <td onclick="showSection('history')" class="history-button">HISTORY</td> {{-- New History Button --}}
            </tr>
        </table>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">13</div>
            <div class="stat-label">Total Indikator</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">8</div>
            <div class="stat-label">Selesai</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">3</div>
            <div class="stat-label">Dalam Proses</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">2</div>
            <div class="stat-label">Belum Dimulai</div>
        </div>
    </div>

    <div class="main-grid flex">
        <div class="indicators-section">
            <h2><i class="fas fa-tasks"></i> Daftar Indikator Mutu</h2>
            {{-- This section will be populated dynamically by JavaScript based on 'indicators' array --}}
            {{-- This is a placeholder for initial render, JS will update statuses --}}
            <div class="indicator-item completed" id="indicator-hand-hygiene">
                <div class="indicator-title">Kepatuhan Kebersihan Tangan (≥80%)</div>
                <div class="unit-badge">PPI</div>
                <div class="status-badge status-completed">Selesai</div>
                <button class="action-btn" onclick="openForm('hand-hygiene')">Lihat Detail</button>
            </div>
            <div class="indicator-item completed" id="indicator-identifikasi">
                <div class="indicator-title">Kepatuhan Identifikasi pasien (≥80%)</div>
                <div class="unit-badge">PPI</div>
                <div class="status-badge status-completed">Selesai</div>
                <button class="action-btn" onclick="openForm('identifikasi')">Lihat Detail</button>
            </div>
            <div class="indicator-item completed" id="indicator-apd">
                <div class="indicator-title">Kepatuhan Penggunaan Alat Pelindung Diri (100%)</div>
                <div class="unit-badge">YANMED</div>
                <div class="status-badge status-completed">Selesai</div>
                <button class="action-btn" onclick="openForm('apd')">Lihat Detail</button>
            </div>
            <div class="indicator-item in-progress" id="indicator-sc">
                <div class="indicator-title">Waktu tanggap seksio sesarea emergency (≥30%)</div>
                <div class="unit-badge">IBS</div>
                <div class="status-badge status-progress">Dalam Proses</div>
                <button class="action-btn" onclick="openForm('sc')">Input Data</button>
            </div>
            <div class="indicator-item completed" id="indicator-wtri">
                <div class="indicator-title">Waktu tunggu rawat jalan (≥80%)</div>
                <div class="unit-badge">YANMED</div>
                <div class="status-badge status-completed">Selesai</div>
                <button class="action-btn" onclick="openForm('wtri')">Lihat Detail</button>
            </div>
            <div class="indicator-item pending" id="indicator-poe">
                <div class="indicator-title">Penundaan operasi elektif (<5%) </div>
                <div class="unit-badge">IBS</div>
                <div class="status-badge status-pending">Belum Dimulai</div>
                <button class="action-btn" onclick="openForm('poe')">Mulai Input</button>
            </div>
            <div class="indicator-item completed" id="indicator-visite">
                <div class="indicator-title">Kepatuhan waktu visite dokter (≥80%)</div>
                <div class="unit-badge">YANMED/SIRS</div>
                <div class="status-badge status-completed">Selesai</div>
                <button class="action-btn" onclick="openForm('visite')">Lihat Detail</button>
            </div>
            <div class="indicator-item completed" id="indicator-kritis-lab">
                <div class="indicator-title">Kepatuhan hasil kritis laboratorium (≥80%)</div>
                <div class="unit-badge">PK</div>
                <div class="status-badge status-completed">Selesai</div>
                <button class="action-btn" onclick="openForm('kritis-lab')">Lihat Detail</button>
            </div>
            <div class="indicator-item completed" id="indicator-fornas">
                <div class="indicator-title">Kepatuhan penggunaan Formularium (≥80%)</div>
                <div class="unit-badge">FARMASI</div>
                <div class="status-badge status-completed">Selesai</div>
                <button class="action-btn" onclick="openForm('fornas')">Lihat Detail</button>
            </div>
            <div class="indicator-item in-progress" id="indicator-cp">
                <div class="indicator-title">Kepatuhan terhadap clinical pathway (≥80%)</div>
                <div class="unit-badge">KOMITE</div>
                <div class="status-badge status-progress">Dalam Proses</div>
                <button class="action-btn" onclick="openForm('cp')">Input Data</button>
            </div>
            <div class="indicator-item completed" id="indicator-jatuh">
                <div class="indicator-title">Kepatuhan upaya pencegahan risiko pasien jatuh (100%)</div>
                <div class="unit-badge">YANMED</div>
                <div class="status-badge status-completed">Selesai</div>
                <button class="action-btn" onclick="openForm('jatuh')">Lihat Detail</button>
            </div>
            <div class="indicator-item completed" id="indicator-krk">
                <div class="indicator-title">Kecepatan waktu tanggap terhadap komplain (≥80%)</div>
                <div class="unit-badge">ADMISI</div>
                <div class="status-badge status-completed">Selesai</div>
                <button class="action-btn" onclick="openForm('krk')">Lihat Detail</button>
            </div>
            <div class="indicator-item pending" id="indicator-kepuasan">
                <div class="indicator-title">Kepuasan pasien (≥76.61%)</div>
                <div class="unit-badge">ADMISI</div>
                <div class="status-badge status-pending">Belum Dimulai</div>
                <button class="action-btn" onclick="openForm('kepuasan')">Mulai Input</button>
            </div>
        </div>
    </div>

    <div class="data-forms" style="display: none;">
        <div class="form-card overflow-x-auto" id="kebersihan-form" style="display: none;">
            <h3><i class="fas fa-hands-wash "></i> LEMBAR PENGUMPUL DATA KEPATUHAN KEBERSIHAN TANGAN</h3>
            <div style="overflow-x: auto;">
                <table class="form-table min-w-max">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th> <th rowspan="2">Bulan</th>
                            <th rowspan="2">Sesi (n)</th>
                            <th colspan="3">DPJP</th>
                            <th colspan="3">Perawat</th>
                            <th colspan="3">Pendidikan</th>
                            <th colspan="3">Tenaga Kesehatan Lain</th>
                            <th colspan="3">Total Per Sesi</th>
                        </tr>
                        <tr>
                            <th>Kesempatan (n)</th>
                            <th>Handwash (n)</th>
                            <th>Handrub (n)</th>

                            <th>Kesempatan (n)</th>
                            <th>Handwash (n)</th>
                            <th>Handrub (n)</th>

                            <th>Kesempatan (n)</th>
                            <th>Handwash (n)</th>
                            <th>Handrub (n)</th>

                            <th>Kesempatan (n)</th>
                            <th>Handwash (n)</th>
                            <th>Handrub (n)</th>

                            <th>Kesempatan (n)</th>
                            <th>Handwash (n)</th>
                            <th>Handrub (n)</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Rows will be added dynamically by JavaScript --}}
                    </tbody>
                </table>
            </div>
            <button id="add-hand-hygiene-row" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mt-2">Add Row</button>
            <div class="form-actions mt-4">
                <button class="save-btn px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 mr-2"><i class="fas fa-save"></i> Simpan</button>
                <button class="cancel-btn px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" onclick="backToList()"><i class="fas fa-times"></i> Batal</button>
            </div>
        </div>

        <div class="form-card overflow-x-auto" id="apd-form" style="display: none;">
            <h3><i class="fas fa-shield-alt"></i> Kepatuhan Penggunaan APD</h3>
            <div style="overflow-x: auto;">
                <table class="form-table min-w-max">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th> <!-- Added No column -->
                            <th rowspan="2">TGL</th>
                            <th rowspan="2">PROFESI</th>
                            <th rowspan="2">RUANG</th>
                            <th rowspan="2">DAKAN/PELAYAN</th>
                            <th colspan="2">S.Tangan</th>
                            <th colspan="2">Masker</th>
                            <th colspan="2">Topi</th>
                            <th colspan="2">Google</th>
                            <th colspan="2">Pakaian</th>
                            <th colspan="2">Sepatu</th>
                            <th rowspan="2">KEPATUHAN</th>
                            <th rowspan="2">KET</th>
                        </tr>
                        <tr>
                            <th>Y</th>
                            <th>T</th>
                            <th>Y</th>
                            <th>T</th>
                            <th>Y</th>
                            <th>T</th>
                            <th>Y</th>
                            <th>T</th>
                            <th>Y</th>
                            <th>T</th>
                            <th>Y</th>
                            <th>T</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Rows will be added by JavaScript --}}
                    </tbody>
                </table>
            </div>
            <button id="add-apd-row" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mt-2">Add Row</button>
            <div class="formula-box">
                <div class="formula-text">
                    Formula = (Jumlah petugas kesehatan yang patuh menggunakan APD sesuai SPO / Jumlah seluruh petugas kesehatan yang seharusnya menggunakan APD sesuai SPO pada saat melayani pasien) × 100%
                </div>
            </div>
            <div class="form-actions mt-4">
                <button class="save-btn px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 mr-2"><i class="fas fa-save"></i> Simpan</button>
                <button class="cancel-btn px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" onclick="backToList()"><i class="fas fa-times"></i> Batal</button>
            </div>
        </div>

        <div class="form-card overflow-x-auto" id="identifikasi-form" style="display: none;">
            <h3><i class="fas fa-id-card"></i> Kepatuhan Identifikasi Pasien</h3>
            <div class="form-section">
                <div class="form-section-title">Bulan: <input type="month" name="identifikasi_bulan" value="{{ date('Y-m') }}" required /></div>
                <div class="form-section-title">Unit Kerja:
                    <select name="identifikasi_unit_kerja" required>
                        <option value="">Pilih Unit Kerja</option>
                        <option value="PJT">PJT</option>
                        <option value="RA">RA</option>
                        <option value="GIZI">GIZI</option>
                        <option value="REHAB ME">REHAB ME</option>
                        <option value="RADIOTERAPI">RADIOTERAPI</option>
                        <option value="PAVILI">PAVILI</option>
                        <option value="RB">RB</option>
                        <option value="IRJ">IRJ</option>
                        <option value="UTD">UTD</option>
                        <option value="KEDOKTERAN NUKLIR">KEDOKTERAN NUKLIR</option>
                        <option value="ICD">ICD</option>
                        <option value="PK">PK</option>
                        <option value="IBS">IBS</option>
                        <option value="RADIOLOGI">RADIOLOGI</option>
                        <option value="ICU">ICU</option>
                        <option value="PA">PA</option>
                        <option value="IDT">IDT</option>
                        <option value="MIKROBIOLOGI">MIKROBIOLOGI</option>
                    </select>
                </div>
                <div style="overflow-x: auto;">
                    <table class="form-table min-w-max">
                        <thead>
                            <tr>
                                <th rowspan="2">No</th> <!-- Added No column -->
                                <th rowspan="2">Tgl</th>
                                <th rowspan="2">Staf yang<br>Diobservasi</th>

                                <th colspan="5">Tindakan</th>
                                <th colspan="2">Verbal</th>
                                <th colspan="2">Visual</th>
                                <th colspan="2">Identifikasi</th>
                            </tr>
                            <tr>
                                <th>Pemberian<br>Obat</th>
                                <th>Transfusi<br>Darah</th>
                                <th>Pemberian<br>Diet</th>
                                <th>Pengambilan<br>Spesimen</th>
                                <th>Pemeriksaan<br>Diagnostik/Tindakan</th>

                                <th>Nama</th>
                                <th>Tgl Lahir</th>

                                <th>Nama</th>
                                <th>RM</th>

                                <th>Dilakukan (N)</th>
                                <th>Tidak Dilakukan</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Rows will be added by JavaScript --}}
                            <tr class="nb-row">
                                <td><strong>NB</strong></td>
                                <td colspan="13">
                                    <div style="display:flex;gap:2rem;flex-wrap:wrap;">
                                        <label><input type="checkbox" name="identifikasi_nb_verbal_visual"> Verbal & Visual</label>
                                        <label><input type="checkbox" name="identifikasi_nb_2_parameter"> 2 Parameter</label>
                                        <label><input type="checkbox" name="identifikasi_nb_1_parameter"> 1 Parameter</label>
                                        <label><input type="checkbox" name="identifikasi_nb_tidak_dilakukan"> Tidak Dilakukan</label>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <button id="add-identifikasi-row" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mt-2">Add Row</button>
            <div class="formula-box">
                <div class="formula-text">
                    Formula: (Jumlah identifikasi pasien yang dilakukan dengan benar sesuai SPO / Jumlah pasien yang diobservasi) x 100% ≥ 80%
                </div>
            </div>
            <div class="form-actions mt-4">
                <button class="save-btn px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 mr-2"><i class="fas fa-save"></i> Simpan</button>
                <button class="cancel-btn px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" onclick="backToList()"><i class="fas fa-times"></i> Batal</button>
            </div>
        </div>

        <div class="form-card overflow-x-auto" id="wtri-form" style="display: none;">
            <h3><i class="fas fa-clock"></i> Waktu Tunggu Pelayanan Rawat Jalan (WTPR)</h3>
            <div style="margin-bottom: 20px;">
                <strong>Unit Kerja:</strong>
                <div style="margin: 10px 0;">
                    <label><input type="radio" name="wtri_unit" value="IRJ" required> 1. IRJ</label><br>
                    <label><input type="radio" name="wtri_unit" value="PJT" required> 2. PJT</label><br>
                    <label><input type="radio" name="wtri_unit" value="EKSEKUTIF" required> 3. EKSEKUTIF</label>
                </div>
                <strong>Undangan: Rekam Medik</strong>
            </div>
            <div style="overflow-x: auto;">
                <table class="form-table min-w-max">
                    <thead>
                        <tr>
                            <th>No</th> <!-- Added No column -->
                            <th>Tgl</th>
                            <th>No. RM</th>
                            <th>Nama Pasien</th>
                            <th>Jam Reg Pendaftaran (A)</th>
                            <th>Jam Reg Poli (B)</th>
                            <th>Jam Dilayani Dokter (C)</th>
                            <th>Respon time (C-A) menit INM</th>
                            <th>Pelayanan %</th>
                            <th>Respon time (C-B) menit INM Dirut/Unit Kerja/ satker BLU</th>
                            <th>Pelayanan %</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Rows will be added by JavaScript --}}
                    </tbody>
                </table>
            </div>
            <button id="add-wtri-row" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mt-2">Add Row</button>
            <div class="formula-box">
                <div class="formula-text">
                    Formula = (Jumlah pasien rawat jalan dengan waktu tunggu ≤ 60 menit / Jumlah pasien rawat jalan yang diobservasi) × 100%
                </div>
            </div>
            <div class="form-actions mt-4">
                <button class="save-btn px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 mr-2"><i class="fas fa-save"></i> Simpan</button>
                <button class="cancel-btn px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" onclick="backToList()"><i class="fas fa-times"></i> Batal</button>
            </div>
        </div>

        <div class="form-card overflow-x-auto" id="kritis-form" style="display: none;">
            <h3><i class="fas fa-flask"></i> Waktu Lapor Hasil Tes Kritis Laboratorium</h3>
            <div style="margin-bottom: 20px;">
                <strong>Unit Kerja: PK, PA, MIKROBIOLOGI</strong>
            </div>
            <div style="overflow-x: auto;">
                <table class="form-table min-w-max">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th> <!-- Added No column -->
                            <th rowspan="2">Tgl</th>
                            <th rowspan="2">No RM</th>
                            <th rowspan="2">Nama Pasien</th>
                            <th rowspan="2">Critical Value</th>
                            <th rowspan="2">Waktu Hasil Pemeriksaan Keluar dan telah dibaca (A)</th>
                            <th rowspan="2">Waktu Dilaporkan (B)</th>
                            <th rowspan="2">Nama Penerima Laporan</th>
                            <th rowspan="2">Respon Time (B - A)</th>
                            <th colspan="1">Waktu Pelaporan Laboratorium Kritis</th>
                        </tr>
                        <tr>
                            <th>≤ 30 Menit (N) | > 30 Menit</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Rows will be added by JavaScript --}}
                    </tbody>
                </table>
            </div>
            <button id="add-kritis-lab-row" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mt-2">Add Row</button>
            <div style="text-align: center; margin: 20px 0;">
                <strong>TOTAL: N/D × 100%</strong>
            </div>
            <div class="formula-box">
                <div class="formula-text">
                    Formula = (Jumlah hasil kritis laboratorium yang dilaporkan < 30 menit / Jumlah pasien rawat jalan yang diobservasi) × 100%
                </div>
            </div>
            <div class="form-actions mt-4">
                <button class="save-btn px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 mr-2"><i class="fas fa-save"></i> Simpan</button>
                <button class="cancel-btn px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" onclick="backToList()"><i class="fas fa-times"></i> Batal</button>
            </div>
        </div>

        <div class="form-card overflow-x-auto" id="fornas-form" style="display: none;">
            <h3><i class="fas fa-pills"></i> Kepatuhan Penggunaan Formularium Nasional (FORNAS)</h3>
            <div class="form-section">
                <div class="form-section-title">Unit Kerja: Seluruh Depo Farmasi</div>
                <div style="overflow-x: auto;">
                    <table class="form-table min-w-max">
                        <thead>
                            <tr>
                                <th rowspan="2">No</th> <!-- Added No column -->
                                <th rowspan="2">Unit Kerja</th>
                                <th rowspan="2">Nama Pasien</th>
                                <th rowspan="2">RM</th>
                                <th rowspan="2">Jumlah Resep</th>
                                <th colspan="2">Formularium & Non Formularium (D)</th>
                            </tr>
                            <tr>
                                <th>Formularium Nasional</th> <!-- Changed name -->
                                <th>Non Formularium</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Rows will be added by JavaScript --}}
                            <tr class="total-row">
                                <td colspan="5" style="text-align: center;">Total:</td>
                                <td colspan="2"><strong>N/D × 100%</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <button id="add-fornas-row" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mt-2">Add Row</button>
            <div class="formula-box">
                <div class="formula-text">
                    Formula = (Jumlah R/ recipe dalam lembar resep yang sesuai dengan formularium nasional / Jumlah R/ recipe dalam lembar resep yang diobservasi) × 100%
                </div>
            </div>
            <div class="form-note">
                Kriteria Eksklusi:<br>
                1. Obat yang direcephan di luar FORNAS tetapi dibutuhkan pasien dan telah mendapatkan persetujuan komite medik dan direktur.<br>
                2. Bila dalam resep terdapat obat di luar FORNAS karena stok obat nasional berdasarkan e-handag habis/kosong.
            </div>
            <div class="form-actions mt-4">
                <button class="save-btn px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 mr-2"><i class="fas fa-save"></i> Simpan</button>
                <button class="cancel-btn px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" onclick="backToList()"><i class="fas fa-times"></i> Batal</button>
            </div>
        </div>

        <div class="form-card overflow-x-auto" id="visite-form" style="display: none;">
            <h3><i class="fas fa-user-md"></i> Kepatuhan Waktu Visite Dokter</h3>
            <div class="form-section">
                <div class="form-section-title">Bulan: <input type="month" name="visite_bulan" value="{{ date('Y-m') }}" required /></div>
                <div style="overflow-x: auto;">
                    <table class="form-table min-w-max">
                        <thead>
                            <tr>
                                <th>No</th> <!-- Added No column -->
                                <th>Tanggal Registrasi</th>
                                <th>Nama Pasien</th>
                                <th>No. RM</th>
                                <th>Ruangan</th>
                                <th>Jumlah Hari Efektif (D)</th>
                                <th>Jumlah Hari Rawat</th>
                                <th>DPJP Utama</th>
                                <th>SMF</th>
                                <th>Tgl Visite</th>
                                <th>Jam</th>
                                <th>≤10.00 x 100 (I)</th>
                                <th>>10.00-12.00 x 50 (II)</th>
                                <th>>12.00-14.00 x 25 (III)</th>
                                <th>>14.00 x 0 (IV)</th>
                                <th>Total (I+II+III+IV)</th>
                                <th>Jam Visite Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Rows will be added by JavaScript --}}
                        </tbody>
                    </table>
                </div>
            </div>
            <button id="add-visite-row" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mt-2">Add Row</button>
            <div class="formula-box">
                <div class="formula-text">
                    Formula INM = (Jumlah pasien yang di-visite dokter pada pukul 06.00 - 14.00 / Jumlah pasien yang diobservasi) × 100% ≥ 100%
                </div>
            </div>
            <div class="formula-box">
                <div class="formula-text">
                    Formula IKT = (Jumlah visite pasien ≤ pukul 10.00 × 100) + (Jumlah visite pasien >10.00-12.00 × 50) + (Jumlah visite pasien >12.00-14.00 × 25) + (Visite pasien >14.00 × 0) pada hari efektif minimal pasien rawat inap yang menjadi tanggung jawab DPJP yang di-visite pada hari efektif
                </div>
            </div>
            <div class="form-note">
                Eksklusi INM:<br>
                - Pasien yang baru masuk rawat inap hari itu<br>
                - Pasien konsul<br><br>
                Eksklusi IKT:<br>
                - Pasien yang baru masuk rawat inap pada hari visite tersebut<br>
                - Pasien yang sudah dinyatakan pulang pada H-1<br>
                - Pasien yang divisite pada hari Libur
            </div>
            <div class="form-actions mt-4">
                <button class="save-btn px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 mr-2"><i class="fas fa-save"></i> Simpan</button>
                <button class="cancel-btn px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" onclick="backToList()"><i class="fas fa-times"></i> Batal</button>
            </div>
        </div>

        <div class="form-card overflow-x-auto" id="jatuh-form" style="display: none;">
            <h3><i class="fas fa-procedures"></i> Kepatuhan Upaya Pencegahan Risiko Jatuh</h3>
            <div class="form-section">
                <div class="form-section-title">Pasien Rawat Inap Berisiko Tinggi Jatuh</div>
                <div style="overflow-x: auto;">
                    <table class="form-table min-w-max">
                        <thead>
                            <tr>
                                <th rowspan="2">No</th> <!-- Added No column -->
                                <th rowspan="2">Nama Pasien</th>
                                <th rowspan="2">No. RM</th>
                                <th colspan="3">Upaya pencegahan risiko tinggi pasien jatuh</th>
                                <th colspan="2">Pasien yang mendapat ketiga upaya pencegahan risiko jatuh</th>
                            </tr>
                            <tr>
                                <th>Assessment awal risiko jatuh</th>
                                <th>Assessment ulang risiko jatuh</th>
                                <th>Intervensi pencegahan risiko jatuh</th>
                                <th>Ya (N)</th>
                                <th>Tidak</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Rows will be added by JavaScript --}}
                            <tr class="total-row">
                                <td colspan="3">TOTAL</td>
                                <td><input type="number" readonly name="jatuh_total_assessment_awal" /></td>
                                <td><input type="number" readonly name="jatuh_total_assessment_ulang" /></td>
                                <td><input type="number" readonly name="jatuh_total_intervensi" /></td>
                                <td><input type="number" readonly name="jatuh_total_ketiga_upaya_ya" /></td>
                                <td><input type="number" readonly name="jatuh_total_ketiga_upaya_tidak" /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <button id="add-jatuh-row" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mt-2">Add Row</button>
            <div class="formula-box">
                <div class="formula-text">
                    Formula: (Jumlah pasien yang mendapat ketiga upaya pencegahan risiko jatuh (Ya) / Jumlah pasien berisiko tinggi jatuh yang diobservasi) × 100%
                </div>
            </div>
            <div class="form-actions mt-4">
                <button class="save-btn px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 mr-2"><i class="fas fa-save"></i> Simpan</button>
                <button class="cancel-btn px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" onclick="backToList()"><i class="fas fa-times"></i> Batal</button>
            </div>
        </div>

        <div class="form-card overflow-x-auto" id="cp-form" style="display: none;">
            <h3><i class="fas fa-clipboard-list"></i> Kepatuhan Terhadap Clinical Pathway (Medis)</h3>
            <div class="form-section">
                <div class="form-header">
                    <div class="form-field">
                        <label>Bulan:</label>
                        <input type="month" name="cp_bulan" value="{{ date('Y-m') }}" required />
                    </div>
                    <div class="form-field">
                        <label>Ruangan:</label>
                        <input type="text" name="cp_ruangan" required />
                    </div>
                    <div class="form-field">
                        <label>Judul CP:</label>
                        <input type="text" name="cp_judul_cp" required />
                    </div>
                </div>
                <div style="overflow-x: auto;">
                    <table class="form-table min-w-max">
                        <thead>
                            <tr>
                                <th rowspan="2">No</th> <!-- Added No column -->
                                <th rowspan="2">No. MR</th>

                                <th colspan="3">Asesmen Klinis (3 item)</th>

                                <th colspan="3">Pemeriksaan Fisik (...)</th>
                                <th colspan="3">Pemeriksaan Penunjang (...)</th>
                                <th colspan="3">Obat‑Obatan (...)</th>

                                <th rowspan="2">Total</th>
                                <th rowspan="2">Varian</th>
                                <th rowspan="2">Ket</th>
                            </tr>
                            <tr>
                                <th>P</th>
                                <th>N</th>
                                <th>C</th>
                                <th>P</th>
                                <th>N</th>
                                <th>C</th>
                                <th>P</th>
                                <th>N</th>
                                <th>C</th>
                                <th>P</th>
                                <th>N</th>
                                <th>C</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Rows will be added by JavaScript --}}

                            <tr class="total-row">
                                <td colspan="2" style="text-align: center;"><strong>TOTAL</strong></td>
                                <td><input type="number" readonly name="cp_total_asesmen_p" value="0" /></td>
                                <td><input type="number" readonly name="cp_total_asesmen_n" value="0" /></td>
                                <td><input type="number" readonly name="cp_total_asesmen_c" value="0" /></td>
                                <td><input type="number" readonly name="cp_total_fisik_p" value="0" /></td>
                                <td><input type="number" readonly name="cp_total_fisik_n" value="0" /></td>
                                <td><input type="number" readonly name="cp_total_fisik_c" value="0" /></td>
                                <td><input type="number" readonly name="cp_total_penunjang_p" value="0" /></td>
                                <td><input type="number" readonly name="cp_total_penunjang_n" value="0" /></td>
                                <td><input type="number" readonly name="cp_total_penunjang_c" value="0" /></td>
                                <td><input type="number" readonly name="cp_total_obat_p" value="0" /></td>
                                <td><input type="number" readonly name="cp_total_obat_n" value="0" /></td>
                                <td><input type="number" readonly name="cp_total_obat_c" value="0" /></td>
                                <td><input type="number" readonly name="cp_grand_total" value="0" /></td>
                                <td colspan="2"></td> {{-- Span Varian and Ket columns for layout --}}
                            </tr>
                            <tr class="rata-rata-row">
                                <td colspan="15" style="text-align: right;"><strong>Rata-Rata Kepatuhan:</strong></td>
                                <td colspan="2"><input type="text" readonly name="cp_rata_rata_kepatuhan" value="0%" /></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <button id="add-cp-row" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mt-2">Add Row</button>
            <div class="formula-box">
                <div class="formula-text">
                    Formula: (Total item yang sesuai dengan CP / Total item yang diobservasi) × 100%
                </div>
            </div>
            <style>
                .form-header {
                    display: flex;
                    gap: 20px;
                    margin-bottom: 15px;
                    flex-wrap: wrap;
                }

                .form-field {
                    display: flex;
                    align-items: center;
                    gap: 5px;
                }

                .form-field label {
                    font-weight: bold;
                }

                .form-table th,
                .form-table td {
                    text-align: center;
                    vertical-align: middle;
                }

                .form-table input[type="number"],
                .form-table input[type="text"] {
                    width: 50px;
                    text-align: center;
                    padding: 5px;
                }
            </style>
            <div class="form-actions mt-4">
                <button class="save-btn px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 mr-2"><i class="fas fa-save"></i> Simpan</button>
                <button class="cancel-btn px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" onclick="backToList()"><i class="fas fa-times"></i> Batal</button>
            </div>
        </div>

        <div class="form-card overflow-x-auto" id="kepuasan-form" style="display: none;">
            <h3><i class="fas fa-smile"></i> Kepuasan Pasien</h3>
            <div class="form-section">
                <div class="form-section-title">Survey Kepuasan Pasien</div>
                <div style="overflow-x: auto;">
                    <table class="form-table min-w-max">
                        <thead>
                            <tr>
                                <th>No</th> <!-- Added No column -->
                                <th>Tanggal</th>
                                <th>Unit Kerja</th>
                                <th>Nilai IKM</th>
                                <th>Jenis Pelayanan</th>
                                <th>Nilai Kepuasan (1-5)</th>
                                <th>Komentar</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Rows will be added by JavaScript --}}
                        </tbody>
                    </table>
                </div>
            </div>
            <button id="add-kepuasan-row" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mt-2">Add Row</button>
            <div class="formula-box">
                <div class="formula-text">
                    Formula: (Jumlah responden yang memberikan nilai ≥4 / Jumlah total responden) × 100% ≥ 76.61%
                </div>
            </div>
            <div class="form-actions mt-4">
                <button class="save-btn px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 mr-2"><i class="fas fa-save"></i> Simpan</button>
                <button class="cancel-btn px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" onclick="backToList()"><i class="fas fa-times"></i> Batal</button>
            </div>
        </div>

        <div class="form-card overflow-x-auto" id="krk-form" style="display: none;">
            <h3><i class="fas fa-exclamation-triangle"></i> Kecepatan Waktu Tanggap Komplain (KRK)</h3>
            <div class="form-section">
                <div style="overflow-x: auto;">
                    <table class="form-table min-w-max">
                        <thead>
                            <tr>
                                <th rowspan="2">No</th> <!-- Added No column -->
                                <th rowspan="2">Tgl</th>
                                <th rowspan="2">Isi Komplain</th>
                                <th rowspan="2">Kategori Komplain</th>
                                <th colspan="3">Pelaporan Komplain</th>
                                <th colspan="3">Grading Komplain</th>
                                <th rowspan="2">Waktu tanggap komplain (hari)</th>
                                <th colspan="2">Penyelesaian komplain sesuai grading</th>
                                <th rowspan="2">Ket.</th>
                            </tr>
                            <tr>
                                <th>Lisan</th>
                                <th>Tulisan</th>
                                <th>Media Masa</th>
                                <th>Merah (max 1x24 jam)</th>
                                <th>Kuning (max 3 hari)</th>
                                <th>Hijau (max 7 hari)</th>
                                <th>Ya (N)</th>
                                <th>Tidak</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Rows will be added by JavaScript --}}
                            <tr class="total-row">
                                <td colspan="14" style="text-align: left;"><strong>Total</strong></td>
                                <td></td> {{-- Placeholder for total --}}
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="text-align: center; margin: 10px 0;">
                    <strong>Total: N/D × 100%</strong>
                </div>
            </div>
            <button id="add-krk-row" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mt-2">Add Row</button>
            <div class="formula-box">
                <div class="formula-text">
                    Formula: (Jumlah komplain yang ditanggapi sesuai grading waktu / Jumlah total komplain) × 100% ≥ 80%
                </div>
            </div>
            <div class="form-actions mt-4">
                <button class="save-btn px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 mr-2"><i class="fas fa-save"></i> Simpan</button>
                <button class="cancel-btn px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" onclick="backToList()"><i class="fas fa-times"></i> Batal</button>
            </div>
        </div>

        <div class="form-card overflow-x-auto" id="poe-form" style="display: none;">
            <h3><i class="fas fa-calendar-times"></i> Penundaan Operasi Elektif</h3>
            <div class="form-section">
                <div class="form-section-title">Unit: Instalasi Bedah Sentral</div>
                <div style="overflow-x: auto;">
                    <table class="form-table min-w-max">
                        <thead>
                            <tr>
                                <th rowspan="2">No</th> <!-- Added No column -->
                                <th rowspan="2">Hari/Tanggal</th>
                                <th rowspan="2">Nama Pasien</th>
                                <th rowspan="2">No. RM</th>
                                <th rowspan="2">Ruangan</th>
                                <th rowspan="2">Diagnosa</th>
                                <th rowspan="2">Tindakan Bedah</th>
                                <th rowspan="2">DPJP Bedah</th>
                                <th rowspan="2">Jam Rencana Operasi</th>
                                <th rowspan="2">Jam Insisi</th>
                                <th colspan="2">Penundaan Operasi</th>
                                <th rowspan="2">Keterangan</th>
                            </tr>
                            <tr>
                                <th>>1 jam (N)</th>
                                <th>≤ 1 jam</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Rows will be added by JavaScript --}}
                        </tbody>
                    </table>
                </div>
            </div>
            <button id="add-poe-row" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mt-2">Add Row</button>
            <div class="formula-box">
                <div class="formula-text">
                    Formula: (Jumlah pasien yang jadwal operasinya tertunda > 1 jam / Jumlah pasien operasi elektif) × 100% < 5%
                </div>
            </div>
            <div class="form-actions mt-4">
                <button class="save-btn px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 mr-2"><i class="fas fa-save"></i> Simpan</button>
                <button class="cancel-btn px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" onclick="backToList()"><i class="fas fa-times"></i> Batal</button>
            </div>
        </div>

        <div class="form-card overflow-x-auto" id="sc-form" style="display: none;">
            <h3><i class="fas fa-baby"></i> Waktu Tanggap Operasi Seksio Sesarea Emergensi</h3>
            <div class="form-section">
                <div class="form-section-title">Unit: IGD Lt 1, IGD Lt 3, IBS</div>
                <div style="overflow-x: auto;">
                    <table class="form-table min-w-max">
                        <thead>
                            <tr>
                                <th>No</th> <!-- Added No column -->
                                <th>Nama Pasien</th>
                                <th>No. RM</th>
                                <th>Diagnosa Kategori</th>
                                <th>Jam Tiba di IGD</th>
                                <th>Jam Diputuskan Operasi</th>
                                <th>Jam Mulai Insisi</th>
                                <th>Waktu Tanggap Operasi SC</th>
                                <th>> 30 menit</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Rows will be added by JavaScript --}}
                        </tbody>
                    </table>
                </div>
            </div>
            <button id="add-sc-row" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mt-2">Add Row</button>
            <div class="formula-box">
                <div class="formula-text">
                    Formula: (Jumlah pasien yang diputuskan tindakan operasi Seksio Sesarea kategori I yang mendapatkan tindakan seksio sesarea emergensi ≤ 30 menit / Jumlah pasien yang diputuskan tindakan operasi seksio sesarea emergensi kategori I) × 100% ≥ 30%
                </div>
            </div>
            <div class="form-actions mt-4">
                <button class="save-btn px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 mr-2"><i class="fas fa-save"></i> Simpan</button>
                <button class="cancel-btn px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" onclick="backToList()"><i class="fas fa-times"></i> Batal</button>
            </div>
        </div>
    </div>

    <div class="form-card" id="history-section" style="display: none;">
        <h2 class="text-2xl font-bold mb-4"><i class="fas fa-history"></i> Riwayat Pengisian Form</h2>
        <div id="history-section-content" class="mt-4">
            {{-- History data will be rendered here by JavaScript --}}
        </div>
        <button class="cancel-btn px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 mt-4" onclick="backToList()"><i class="fas fa-times"></i> Kembali</button>
    </div>

    <div class="loading-spinner" id="loading" style="display: none;">
        <div class="spinner"></div>
    </div>
</body>
</html>
