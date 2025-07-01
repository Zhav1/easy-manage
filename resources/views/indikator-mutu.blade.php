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
{{-- Add Moment.js for date calculations --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="{{ asset('js/indikator-mutu.js') }}"></script>
</head>

<body>
    <script>
        window.authToken = "{{ session('token') }}";
    </script>
    @include('components.sidebar-navbar')
    <div class="p-4 pt-20 pl-60 pr-5" >
    <div class="header">
        <h1 style="color:black"><i class="fas fa-chart-line mr-3 text-green-500" style="color:"></i>Dashboard Indikator Mutu</h1>
        <p style="color:gray">Sistem Monitoring Kualitas Pelayanan Rumah Sakit</p>
    </div>

    <div class="indicator-table-container">
        <table class="indicator-table">
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

    <div class="main-grid">
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

        <div class="sidebar">
            <div class="legend-card">
                <h3><i class="fas fa-info-circle"></i> Kategori Indikator</h3>
                <div class="legend-item">
                    <div class="legend-color kebersihan"></div>
                    <span>Kebersihan Tangan</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color apd"></div>
                    <span>APD</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color identifikasi"></div>
                    <span>Identifikasi</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color seksio"></div>
                    <span>Seksio</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color wtri"></div>
                    <span>WTRI</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color poe"></div>
                    <span>POE</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color visite"></div>
                    <span>Visite</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color kritis"></div>
                    <span>Kritis Lab</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color fornas"></div>
                    <span>FORNAS</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color cp"></div>
                    <span>CP</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color sc"></div>
                    <span>SC</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color risiko"></div>
                    <span>Risiko Jatuh</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color krk"></div>
                    <span>KRK</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color kepuasan"></div>
                    <span>Kepuasan Pasien</span>
                </div>
            </div>
        </div>
    </div>

    <div class="data-forms overflow-x: auto;" style="display: none;">
        <div class="form-card overflow-x: auto;" id="kebersihan-form" style="display: none;">
            <h3><i class="fas fa-hands-wash "></i> LEMBAR PENGUMPUL DATA KEPATUHAN KEBERSIHAN TANGAN</h3>
            <div style="overflow-x: auto;">

                <table class="form-table">
                    <thead>
                        <tr>
                            <th rowspan="2">Bulan</th>
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
                        <tr>
                            <td>
                                <select class="bulan-select" name="bulan">
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                            </td>
                            <td><input type="number" min="1" value="1" class="sesi-input" name="sesi" /></td>

                            <td><input type="number" min="0" value="0" name="dpjp_kesempatan" /></td>
                            <td><input type="number" min="0" value="0" name="dpjp_handwash" /></td>
                            <td><input type="number" min="0" value="0" name="dpjp_handrub" /></td>

                            <td><input type="number" min="0" value="0" name="perawat_kesempatan" /></td>
                            <td><input type="number" min="0" value="0" name="perawat_handwash" /></td>
                            <td><input type="number" min="0" value="0" name="perawat_handrub" /></td>

                            <td><input type="number" min="0" value="0" name="pendidikan_kesempatan" /></td>
                            <td><input type="number" min="0" value="0" name="pendidikan_handwash" /></td>
                            <td><input type="number" min="0" value="0" name="pendidikan_handrub" /></td>

                            <td><input type="number" min="0" value="0" name="lain_kesempatan" /></td>
                            <td><input type="number" min="0" value="0" name="lain_handwash" /></td>
                            <td><input type="number" min="0" value="0" name="lain_handrub" /></td>

                            <td><input type="number" min="0" value="0" readonly name="total_kesempatan" /></td>
                            <td><input type="number" min="0" value="0" readonly name="total_handwash" /></td>
                            <td><input type="number" min="0" value="0" readonly name="total_handrub" /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-actions mt-4">
                <button class="save-btn px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 mr-2"><i class="fas fa-save"></i> Simpan</button>
                <button class="cancel-btn px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" onclick="backToList()"><i class="fas fa-times"></i> Batal</button>
            </div>
        </div>

        <div class="form-card" id="apd-form" style="display: none;">
            <h3><i class="fas fa-shield-alt"></i> Kepatuhan Penggunaan APD</h3>
            <table class="form-table">
                <thead>
                    <tr>
                        <th>TGL</th>
                        <th>PROFESI</th>
                        <th>RUANG</th>
                        <th>DAKAN/PELAYAN</th>
                        <th colspan="2">S.Tangan</th>
                        <th colspan="2">Masker</th>
                        <th colspan="2">Topi</th>
                        <th colspan="2">Google</th>
                        <th colspan="2">Pakaian</th>
                        <th colspan="2">Sepatu</th>
                        <th>KEPATUHAN</th>
                        <th>KET</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
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
                        <th>Patuh/Tidak</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="date" name="apd_tgl_1" /></td>
                        <td><input type="text" placeholder="Profesi" name="apd_profesi_1" /></td>
                        <td><input type="text" placeholder="Ruang" name="apd_ruang_1" /></td>
                        <td><input type="text" placeholder="Pelayanan" name="apd_pelayanan_1" /></td>
                        <td><input type="checkbox" name="apd_st_y_1" /></td>
                        <td><input type="checkbox" name="apd_st_t_1" /></td>
                        <td><input type="checkbox" name="apd_masker_y_1" /></td>
                        <td><input type="checkbox" name="apd_masker_t_1" /></td>
                        <td><input type="checkbox" name="apd_topi_y_1" /></td>
                        <td><input type="checkbox" name="apd_topi_t_1" /></td>
                        <td><input type="checkbox" name="apd_google_y_1" /></td>
                        <td><input type="checkbox" name="apd_google_t_1" /></td>
                        <td><input type="checkbox" name="apd_pakaian_y_1" /></td>
                        <td><input type="checkbox" name="apd_pakaian_t_1" /></td>
                        <td><input type="checkbox" name="apd_sepatu_y_1" /></td>
                        <td><input type="checkbox" name="apd_sepatu_t_1" /></td>
                        <td><select name="apd_kepatuhan_1"><option>Patuh</option><option>Tidak</option></select></td>
                        <td><input type="text" placeholder="Keterangan" name="apd_ket_1" /></td>
                    </tr>
                </tbody>
            </table>
            <button id="add-apd-row" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mt-2">Add Row</button> {{-- Add Row button for APD --}}
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
        <div class="form-card" id="identifikasi-form" style="display: none;">
            <h3><i class="fas fa-id-card"></i> Kepatuhan Identifikasi Pasien</h3>

            <div class="form-section">
                <div class="form-section-title">Unit Kerja:
                    <select name="identifikasi_unit_kerja">
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

                <table class="form-table">
                    <colgroup>
                        <col style="width:60px"><col style="width:110px"><col style="width:200px"><col span="11"></colgroup>

                    <thead>
                        <tr>
                            <th rowspan="2">No (D)</th>
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
                        <tr>
                            <td><input type="number" value="1" name="identifikasi_no_1" /></td>
                            <td><input type="date" name="identifikasi_tgl_1" /></td>
                            <td><input type="text" placeholder="Nama Staf" name="identifikasi_staf_1" /></td>

                            <td><input type="checkbox" name="identifikasi_obat_1" /></td>
                            <td><input type="checkbox" name="identifikasi_darah_1" /></td>
                            <td><input type="checkbox" name="identifikasi_diet_1" /></td>
                            <td><input type="checkbox" name="identifikasi_spesimen_1" /></td>
                            <td><input type="checkbox" name="identifikasi_diagnostik_1" /></td>

                            <td><input type="checkbox" name="identifikasi_verbal_nama_1" /></td>
                            <td><input type="checkbox" name="identifikasi_verbal_tgl_lahir_1" /></td>

                            <td><input type="checkbox" name="identifikasi_visual_nama_1" /></td>
                            <td><input type="checkbox" name="identifikasi_visual_rm_1" /></td>

                            <td><input type="checkbox" name="identifikasi_dilakukan_1" /></td>
                            <td><input type="checkbox" name="identifikasi_tidak_dilakukan_1" /></td>
                        </tr>

                        <tr>
                            <td><input type="number" value="2" name="identifikasi_no_2" /></td>
                            <td><input type="date" name="identifikasi_tgl_2" /></td>
                            <td><input type="text" placeholder="Nama Staf" name="identifikasi_staf_2" /></td>

                            <td><input type="checkbox" name="identifikasi_obat_2" /></td>
                            <td><input type="checkbox" name="identifikasi_darah_2" /></td>
                            <td><input type="checkbox" name="identifikasi_diet_2" /></td>
                            <td><input type="checkbox" name="identifikasi_spesimen_2" /></td>
                            <td><input type="checkbox" name="identifikasi_diagnostik_2" /></td>

                            <td><input type="checkbox" name="identifikasi_verbal_nama_2" /></td>
                            <td><input type="checkbox" name="identifikasi_verbal_tgl_lahir_2" /></td>

                            <td><input type="checkbox" name="identifikasi_visual_nama_2" /></td>
                            <td><input type="checkbox" name="identifikasi_visual_rm_2" /></td>

                            <td><input type="checkbox" name="identifikasi_dilakukan_2" /></td>
                            <td><input type="checkbox" name="identifikasi_tidak_dilakukan_2" /></td>
                        </tr>

                        <tr>
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
        <div class="form-card" id="wtri-form" style="display: none;">
            <h3><i class="fas fa-clock"></i> Waktu Tunggu Pelayanan Rawat Jalan (WTPR)</h3>
            <div style="margin-bottom: 20px;">
                <strong>Unit Kerja:</strong>
                <div style="margin: 10px 0;">
                    <label><input type="radio" name="wtri_unit" value="IRJ"> 1. IRJ</label><br>
                    <label><input type="radio" name="wtri_unit" value="PJT"> 2. PJT</label><br>
                    <label><input type="radio" name="wtri_unit" value="EKSEKUTIF"> 3. EKSEKUTIF</label>
                </div>
                <strong>Undangan: Rekam Medik</strong>
            </div>
            <table class="form-table">
                <thead>
                    <tr>
                        <th>No (D)</th>
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
                    <tr>
                        <td><input type="number" name="wtri_no_1" /></td>
                        <td><input type="date" name="wtri_tgl_1" /></td>
                        <td><input type="text" name="wtri_no_rm_1" /></td>
                        <td><input type="text" name="wtri_nama_pasien_1" /></td>
                        <td><input type="time" name="wtri_jam_reg_pendaftaran_1" /></td>
                        <td><input type="time" name="wtri_jam_reg_poli_1" /></td>
                        <td><input type="time" name="wtri_jam_dilayani_dokter_1" /></td>
                        <td><input type="number" value="40" name="wtri_respon_time_ca_1" readonly/></td>
                        <td><input type="number" value="1" name="wtri_pelayanan_percent_ca_1" /></td>
                        <td><input type="number" value="70" name="wtri_respon_time_cb_1" /></td>
                        <td><input type="number" value="0" name="wtri_pelayanan_percent_cb_1" /></td>
                    </tr>
                </tbody>
            </table>
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

        <div class="form-card" id="kritis-form" style="display: none;">
            <h3><i class="fas fa-flask"></i> Waktu Lapor Hasil Tes Kritis Laboratorium</h3>
            <div style="margin-bottom: 20px;">
                <strong>Unit Kerja: PK, PA, MIKROBIOLOGI</strong>
            </div>
            <table class="form-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tgl</th>
                        <th>No RM</th>
                        <th>Nama Pasien</th>
                        <th>Critical Value</th>
                        <th>Waktu Hasil Pemeriksaan Keluar dan telah dibaca (A)</th>
                        <th>Waktu Dilaporkan (B)</th>
                        <th>Nama Penerima Laporan</th>
                        <th>Respon Time (B - A)</th>
                        <th>Waktu Pelaporan Laboratorium Kritis</th>
                    </tr>
                    <tr>
                        <th>(D)</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>≤ 30 Menit (N) | > 30 Menit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="number" name="kritis_no_1" /></td>
                        <td><input type="date" name="kritis_tgl_1" /></td>
                        <td><input type="text" name="kritis_no_rm_1" /></td>
                        <td><input type="text" name="kritis_nama_pasien_1" /></td>
                        <td><input type="text" name="kritis_critical_value_1" /></td>
                        <td><input type="time" name="kritis_waktu_hasil_keluar_1" /></td>
                        <td><input type="time" name="kritis_waktu_dilaporkan_1" /></td>
                        <td><input type="text" name="kritis_nama_penerima_1" /></td>
                        <td><input type="number" name="kritis_respon_time_1" /></td>
                        <td><select name="kritis_pelaporan_status_1"><option>≤ 30 Menit</option><option>> 30 Menit</option></select></td>
                    </tr>
                </tbody>
            </table>
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

        <div class="form-card" id="fornas-form" style="display: none;">
            <h3><i class="fas fa-pills"></i> Kepatuhan Penggunaan Formularium Nasional (FORNAS)</h3>
            <div class="form-section">
                <div class="form-section-title">Unit Kerja: Seluruh Depo Farmasi</div>
                <table class="form-table">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Unit Kerja</th>
                            <th rowspan="2">Nama Pasien</th>
                            <th rowspan="2">RM</th>
                            <th rowspan="2">Jumlah Resep</th>
                            <th colspan="2">Formularium & Non Formularium (D)</th>
                        </tr>
                        <tr>
                            <th>Formularium (N)</th>
                            <th>Non Formularium</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><input type="text" placeholder="Unit Kerja" name="fornas_unit_kerja_1" /></td>
                            <td><input type="text" placeholder="Nama Pasien" name="fornas_nama_pasien_1" /></td>
                            <td><input type="text" placeholder="No. RM" name="fornas_no_rm_1" /></td>
                            <td><input type="number" name="fornas_jumlah_resep_1" /></td>
                            <td><input type="number" name="fornas_formularium_n_1" /></td>
                            <td><input type="number" name="fornas_non_formularium_1" /></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td><input type="text" name="fornas_unit_kerja_2" /></td>
                            <td><input type="text" name="fornas_nama_pasien_2" /></td>
                            <td><input type="text" name="fornas_no_rm_2" /></td>
                            <td><input type="number" name="fornas_jumlah_resep_2" /></td>
                            <td><input type="number" name="fornas_formularium_n_2" /></td>
                            <td><input type="number" name="fornas_non_formularium_2" /></td>
                        </tr>

                        <tr>
                            <td colspan="5" style="text-align: center;">Total:</td>
                            <td colspan="2"><strong>N/D × 100%</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
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

        <div class="form-card" id="visite-form" style="display: none;">
            <h3><i class="fas fa-user-md"></i> Kepatuhan Waktu Visite Dokter</h3>
            <div class="form-section">
                <div class="form-section-title">Bulan: <input type="month" name="visite_bulan" /></div>
                <table class="form-table">
                    <thead>
                        <tr>
                            <th>No</th>
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
                            <th>Jam Visite</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><input type="date" name="visite_tgl_registrasi_1" /></td>
                            <td><input type="text" name="visite_nama_pasien_1" /></td>
                            <td><input type="text" name="visite_no_rm_1" /></td>
                            <td><input type="text" name="visite_ruangan_1" /></td>
                            <td><input type="number" name="visite_jml_hari_efektif_1" /></td>
                            <td><input type="number" name="visite_jml_hari_rawat_1" /></td>
                            <td><input type="text" name="visite_dpjp_utama_1" /></td>
                            <td><input type="text" name="visite_smf_1" /></td>
                            <td><input type="date" name="visite_tgl_visite_1" /></td>
                            <td><input type="time" name="visite_jam_1" /></td>
                            <td><input type="number" name="visite_val_i_1" /></td>
                            <td><input type="number" name="visite_val_ii_1" /></td>
                            <td><input type="number" name="visite_val_iii_1" /></td>
                            <td><input type="number" name="visite_val_iv_1" /></td>
                            <td><input type="number" readonly name="visite_total_1" /></td>
                            <td><input type="time" name="visite_jam_visite_akhir_1" /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
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

        <div class="form-card" id="jatuh-form" style="display: none;">
            <h3><i class="fas fa-procedures"></i> Kepatuhan Upaya Pencegahan Risiko Jatuh</h3>
            <div class="form-section">
                <div class="form-section-title">Pasien Rawat Inap Berisiko Tinggi Jatuh</div>
                <table class="form-table">
                    <thead>
                        <tr>
                            <th rowspan="2">No (D)</th>
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
                        <tr>
                            <td>1</td>
                            <td><input type="text" placeholder="Nama Pasien" name="jatuh_nama_pasien_1" /></td>
                            <td><input type="text" placeholder="No. RM" name="jatuh_no_rm_1" /></td>
                            <td><select name="jatuh_assessment_awal_1"><option>Ya</option><option>Tidak</option></select></td>
                            <td><select name="jatuh_assessment_ulang_1"><option>Ya</option><option>Tidak</option></select></td>
                            <td><select name="jatuh_intervensi_1"><option>Ya</option><option>Tidak</option></select></td>
                            <td><input type="checkbox" name="jatuh_ketiga_upaya_ya_1" /></td>
                            <td><input type="checkbox" name="jatuh_ketiga_upaya_tidak_1" /></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td><input type="text" name="jatuh_nama_pasien_2" /></td>
                            <td><input type="text" name="jatuh_no_rm_2" /></td>
                            <td><select name="jatuh_assessment_awal_2"><option>Ya</option><option>Tidak</option></select></td>
                            <td><select name="jatuh_assessment_ulang_2"><option>Ya</option><option>Tidak</option></select></td>
                            <td><select name="jatuh_intervensi_2"><option>Ya</option><option>Tidak</option></select></td>
                            <td><input type="checkbox" name="jatuh_ketiga_upaya_ya_2" /></td>
                            <td><input type="checkbox" name="jatuh_ketiga_upaya_tidak_2" /></td>
                        </tr>
                        <tr>
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

        <div class="form-card" id="cp-form" style="display:none;">
            <h3><i class="fas fa-clipboard-list"></i> Kepatuhan Terhadap Clinical Pathway (Medis)</h3>

            <div class="form-section">
                <div class="form-header">
                    <div class="form-field">
                        <label>Bulan:</label>
                        <input type="month" name="cp_bulan" />
                    </div>
                    <div class="form-field">
                        <label>Ruangan:</label>
                        <input type="text" name="cp_ruangan" />
                    </div>
                    <div class="form-field">
                        <label>Judul CP:</label>
                        <input type="text" name="cp_judul_cp" />
                    </div>
                </div>

                <table class="form-table">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
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
                        <tr>
                            <td>1</td>
                            <td><input type="text" placeholder="No. MR" name="cp_no_mr_1" /></td>

                            <td><input type="number" name="cp_asesmen_p_1" /></td>
                            <td><input type="number" name="cp_asesmen_n_1" /></td>
                            <td><input type="number" name="cp_asesmen_c_1" /></td>

                            <td><input type="number" name="cp_fisik_p_1" /></td>
                            <td><input type="number" name="cp_fisik_n_1" /></td>
                            <td><input type="number" name="cp_fisik_c_1" /></td>

                            <td><input type="number" name="cp_penunjang_p_1" /></td>
                            <td><input type="number" name="cp_penunjang_n_1" /></td>
                            <td><input type="number" name="cp_penunjang_c_1" /></td>

                            <td><input type="number" name="cp_obat_p_1" /></td>
                            <td><input type="number" name="cp_obat_n_1" /></td>
                            <td><input type="number" name="cp_obat_c_1" /></td>

                            <td><input type="number" name="cp_total_1" /></td>
                            <td><input type="text" name="cp_varian_1" /></td>
                            <td><input type="text" name="cp_ket_1" /></td>
                        </tr>

                        <tr>
                            <td>2</td>
                            <td><input type="text" name="cp_no_mr_2" /></td>

                            <td><input type="number" name="cp_asesmen_p_2" /></td>
                            <td><input type="number" name="cp_asesmen_n_2" /></td>
                            <td><input type="number" name="cp_asesmen_c_2" /></td>

                            <td><input type="number" name="cp_fisik_p_2" /></td>
                            <td><input type="number" name="cp_fisik_n_2" /></td>
                            <td><input type="number" name="cp_fisik_c_2" /></td>

                            <td><input type="number" name="cp_penunjang_p_2" /></td>
                            <td><input type="number" name="cp_penunjang_n_2" /></td>
                            <td><input type="number" name="cp_penunjang_c_2" /></td>

                            <td><input type="number" name="cp_obat_p_2" /></td>
                            <td><input type="number" name="cp_obat_n_2" /></td>
                            <td><input type="number" name="cp_obat_c_2" /></td>

                            <td><input type="number" name="cp_total_2" /></td>
                            <td><input type="text" name="cp_varian_2" /></td>
                            <td><input type="text" name="cp_ket_2" /></td>
                        </tr>

                        <tr>
                            <td colspan="2">TOTAL</td>

                            <td><input type="number" readonly name="cp_total_asesmen_p" /></td>
                            <td><input type="number" readonly name="cp_total_asesmen_n" /></td>
                            <td><input type="number" readonly name="cp_total_asesmen_c" /></td>

                            <td><input type="number" readonly name="cp_total_fisik_p" /></td>
                            <td><input type="number" readonly name="cp_total_fisik_n" /></td>
                            <td><input type="number" readonly name="cp_total_fisik_c" /></td>

                            <td><input type="number" readonly name="cp_total_penunjang_p" /></td>
                            <td><input type="number" readonly name="cp_total_penunjang_n" /></td>
                            <td><input type="number" readonly name="cp_total_penunjang_c" /></td>

                            <td><input type="number" readonly name="cp_total_obat_p" /></td>
                            <td><input type="number" readonly name="cp_total_obat_n" /></td>
                            <td><input type="number" readonly name="cp_total_obat_c" /></td>

                            <td><input type="number" readonly name="cp_grand_total" /></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td colspan="14" style="text-align:right;">Rata‑Rata (Kepatuhan):</td>
                            <td colspan="3"><input type="text" readonly name="cp_rata_rata_kepatuhan" /></td>
                        </tr>
                    </tbody>
                </table>
            </div>

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


        <div class="form-card" id="kepuasan-form" style="display: none;">
            <h3><i class="fas fa-smile"></i> Kepuasan Pasien</h3>
            <div class="form-section">
                <div class="form-section-title">Survey Kepuasan Pasien</div>
                <table class="form-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Unit Kerja</th>
                            <th>Nilai IKM</th>
                            <th>Jenis Pelayanan</th>
                            <th>Nilai Kepuasan (1-5)</th>
                            <th>Komentar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><input type="date" name="kepuasan_tanggal_1" /></td>
                            <td><input type="text" name="kepuasan_unit_kerja_1" /></td>
                            <td><input type="text" name="kepuasan_nilai_ikm_1" /></td>
                            <td><select name="kepuasan_jenis_pelayanan_1">
                                <option>Rawat Jalan</option>
                                <option>Rawat Inap</option>
                                <option>IGD</option>
                                <option>Farmasi</option>
                                <option>Laboratorium</option>
                            </select></td>
                            <td><select name="kepuasan_nilai_kepuasan_1">
                                <option>1 (Sangat Tidak Puas)</option>
                                <option>2 (Tidak Puas)</option>
                                <option>3 (Cukup Puas)</option>
                                <option>4 (Puas)</option>
                                <option>5 (Sangat Puas)</option>
                            </select></td>
                            <td><input type="text" name="kepuasan_komentar_1" /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
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

        <div class="form-card" id="krk-form" style="display: none;">
            <h3><i class="fas fa-exclamation-triangle"></i> Kecepatan Waktu Tanggap Komplain (KRK)</h3>
            <div class="form-section">
                <table class="form-table">
                    <thead>
                        <tr>
                            <th rowspan="2">No (D)</th>
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
                        <tr>
                            <td><input type="number" name="krk_no_1" /></td>
                            <td><input type="date" name="krk_tgl_1" /></td>
                            <td><input type="text" name="krk_isi_komplain_1" /></td>
                            <td><input type="text" name="krk_kategori_komplain_1" /></td>
                            <td><input type="checkbox" name="krk_lisan_1" /></td>
                            <td><input type="checkbox" name="krk_tulisan_1" /></td>
                            <td><input type="checkbox" name="krk_media_masa_1" /></td>
                            <td><input type="checkbox" name="krk_grading_merah_1" /></td>
                            <td><input type="checkbox" name="krk_grading_kuning_1" /></td>
                            <td><input type="checkbox" name="krk_grading_hijau_1" /></td>
                            <td><input type="number" name="krk_waktu_tanggap_1" /></td>
                            <td><input type="checkbox" name="krk_penyelesaian_ya_1" /></td>
                            <td><input type="checkbox" name="krk_penyelesaian_tidak_1" /></td>
                            <td><input type="text" name="krk_ket_1" /></td>
                        </tr>
                        <tr>
                            <td colspan="14" style="text-align: left;"><strong>Total</strong></td>
                        </tr>
                    </tbody>
                </table>
                <div style="text-align: center; margin: 10px 0;">
                    <strong>Total: N/D × 100%</strong>
                </div>
            </div>
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

        <div class="form-card" id="poe-form" style="display: none;">
            <h3><i class="fas fa-calendar-times"></i> Penundaan Operasi Elektif</h3>
            <div class="form-section">
                <div class="form-section-title">Unit: Instalasi Bedah Sentral</div>
                <table class="form-table">
                    <thead>
                        <tr>
                            <th rowspan="2">No (D)</th>
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
                        <tr>
                            <td>1</td>
                            <td><input type="date" name="poe_tgl_1" /></td>
                            <td><input type="text" name="poe_nama_pasien_1" /></td>
                            <td><input type="text" name="poe_no_rm_1" /></td>
                            <td><input type="text" name="poe_ruangan_1" /></td>
                            <td><input type="text" name="poe_diagnosa_1" /></td>
                            <td><input type="text" name="poe_tindakan_bedah_1" /></td>
                            <td><input type="text" name="poe_dpjp_bedah_1" /></td>
                            <td><input type="time" name="poe_jam_rencana_1" /></td>
                            <td><input type="time" name="poe_jam_insisi_1" /></td>
                            <td><input type="checkbox" name="poe_penundaan_gt_1hr_1" /></td>
                            <td><input type="checkbox" name="poe_penundaan_lt_1hr_1" /></td>
                            <td><input type="text" name="poe_keterangan_1" /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
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

        <div class="form-card" id="sc-form" style="display: none;">
            <h3><i class="fas fa-baby"></i> Waktu Tanggap Operasi Seksio Sesarea Emergensi</h3>
            <div class="form-section">
                <div class="form-section-title">Unit: IGD Lt 1, IGD Lt 3, IBS</div>
                <table class="form-table">
                    <thead>
                        <tr>
                            <th>No (D)</th>
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
                        <tr>
                            <td>1</td>
                            <td><input type="text" name="sc_nama_pasien_1" /></td>
                            <td><input type="text" name="sc_no_rm_1" /></td>
                            <td><select name="sc_diagnosa_kategori_1">
                                <option>Kategori I</option>
                                <option>Kategori II</option>
                                <option>Kategori III</option>
                            </select></td>
                            <td><input type="time" name="sc_jam_tiba_igd_1" /></td>
                            <td><input type="time" name="sc_jam_diputuskan_operasi_1" /></td>
                            <td><input type="time" name="sc_jam_mulai_insisi_1" /></td>
                            <td><input type="number" name="sc_waktu_tanggap_1" /></td>
                            <td><select name="sc_gt_30_menit_1"><option>Ya</option><option>Tidak</option></select></td>
                            <td><input type="text" name="sc_keterangan_1" /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
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