<!DOCTYPE html>
<html lang="en" class="h-full bg-white w-screen">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Indikator Mutu - Dashboard Rumah Sakit</title>
    @vite('resources/css/app.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ffffff 100%);
            min-height: 100vh;
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            text-align: center;
        }

        .header h1 {
            color: white;
            font-size: 2.5em;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            margin-bottom: 10px;
        }

        .header p {
            color: rgba(255,255,255,0.9);
            font-size: 1.1em;
            font-weight: 300;
        }

        .main-grid {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 30px;
            margin-bottom: 30px;
        }

        .indicators-section {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }

        .indicators-section h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 1.8em;
            font-weight: 600;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }

        .indicator-item {
            display: grid;
            grid-template-columns: 4fr 1fr 1fr 1fr 1fr 1fr 3fr;
            gap: 15px;
            align-items: center;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .indicator-item:hover {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            transform: translateX(5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .indicator-item.completed {
            border-left-color: #27ae60;
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        }

        .indicator-item.in-progress {
            border-left-color: #f39c12;
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        }

        .indicator-item.pending {
            border-left-color: #e74c3c;
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        }

        .indicator-title {
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.95em;
        }

        .unit-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            text-align: center;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-completed {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
        }

        .status-progress {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .status-pending {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .action-btn {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9em;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .legend-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .legend-card h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.3em;
            font-weight: 600;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 12px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .legend-item:hover {
            background: #f8f9fa;
            transform: translateX(5px);
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .legend-color.kebersihan { background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%); }
        .legend-color.apd { background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); }
        .legend-color.identifikasi { background: linear-gradient(135deg, #45b7d1 0%, #96c93d 100%); }
        .legend-color.seksio { background: linear-gradient(135deg, #f9ca24 0%, #f0932b 100%); }
        .legend-color.wtri { background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%); }
        .legend-color.poe { background: linear-gradient(135deg, #00b894 0%, #00cec9 100%); }
        .legend-color.visite { background: linear-gradient(135deg, #e84393 0%, #fd79a8 100%); }
        .legend-color.kritis { background: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%); }
        .legend-color.fornas { background: linear-gradient(135deg, #00cec9 0%, #55efc4 100%); }
        .legend-color.cp { background: linear-gradient(135deg, #a29bfe 0%, #6c5ce7 100%); }
        .legend-color.risiko { background: linear-gradient(135deg, #fd79a8 0%, #fdcb6e 100%); }
        .legend-color.krk { background: linear-gradient(135deg, #ff7675 0%, #d63031 100%); }
        .legend-color.kepuasan { background: linear-gradient(135deg, #00b894 0%, #55efc4 100%); }

        .data-forms {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .form-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .form-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .form-card h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.3em;
            font-weight: 600;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        .form-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        .form-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 8px;
            font-size: 0.85em;
            font-weight: 600;
            text-align: center;
            border: none;
        }

        .form-table th:first-child {
            border-radius: 8px 0 0 0;
        }

        .form-table th:last-child {
            border-radius: 0 8px 0 0;
        }

        .form-table td {
            padding: 10px 8px;
            border: 1px solid #e9ecef;
            text-align: center;
            font-size: 0.9em;
        }

        .form-table input[type="number"],
        .form-table input[type="text"],
        .form-table input[type="date"],
        .form-table input[type="time"],
        .form-table select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 6px;
            text-align: center;
            font-size: 0.9em;
            transition: all 0.3s ease;
        }

        .form-table input[type="number"]:focus,
        .form-table input[type="text"]:focus,
        .form-table input[type="date"]:focus,
        .form-table input[type="time"]:focus,
        .form-table select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
            outline: none;
        }

        .formula-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 15px;
            border-radius: 10px;
            margin: 15px 0;
            border-left: 4px solid #3498db;
        }

        .formula-box .formula-text {
            font-size: 0.9em;
            color: #495057;
            font-style: italic;
        }

        .navigation-tabs {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            border-radius: 50px;
            padding: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            display: flex;
            gap: 5px;
            z-index: 1000;
        }

        .nav-tab {
            padding: 12px 16px;
            border-radius: 25px;
            font-size: 0.8em;
            font-weight: 600;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            background: transparent;
            color: #666;
        }

        .nav-tab.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .nav-tab:hover:not(.active) {
            background: #f8f9fa;
            color: #333;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .stat-number {
            font-size: 2.5em;
            font-weight: 700;
            color: #3498db;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 0.9em;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        @media (max-width: 768px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
            
            .indicator-item {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .data-forms {
                grid-template-columns: 1fr;
            }
            
            .navigation-tabs {
                flex-wrap: wrap;
                justify-content: center;
            }
        }

   
        /* Additional styles for new forms */
        .form-card .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .form-card .form-actions {
            display: flex;
            gap: 10px;
        }
        
        .form-card .form-actions button {
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.8em;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .form-card .form-actions .save-btn {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            border: none;
        }
        
        .form-card .form-actions .cancel-btn {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            border: none;
        }
        
        .form-card .form-actions button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        
        .form-card .form-note {
            font-size: 0.8em;
            color: #7f8c8d;
            margin-top: 10px;
            font-style: italic;
        }
        
        .form-card .form-section {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .form-card .form-section-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .form-card .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .form-card .checkbox-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .form-card .checkbox-item input {
            margin: 0;
        }

        /* Styles for indicator acronyms */
/* Styles for indicator table */
.indicator-table-container {
    background: white;
    border-radius: 15px;
    padding: 15px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    overflow-x: auto;
}

.indicator-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 8px;
}

.indicator-table td {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 12px 8px;
    border-radius: 8px;
    text-align: center;
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.9em;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    cursor: pointer;
    min-width: 80px;
}

.indicator-table td:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.1);
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
    color: #3498db;
}

.list-item {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    z-index: 1;
}

/* Hover animation for all items */
.indicator-table td:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

/* Additional hover effect for LIST button */
.list-item:hover {
    background: linear-gradient(135deg, #3a9bee 0%, #00d9e6 100%);
}


/* Navigation tabs styles (existing) */
.navigation-tabs {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: white;
    border-radius: 50px;
    padding: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    display: flex;
    gap: 5px;
    z-index: 1000;
}

.nav-tab {
    padding: 12px 16px;
    border-radius: 25px;
    font-size: 0.8em;
    font-weight: 600;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    background: transparent;
    color: #666;
}

.nav-tab.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.nav-tab:hover:not(.active) {
    background: #f8f9fa;
    color: #333;
}

@media (max-width: 768px) {
    .indicator-table {
        display: block;
        white-space: nowrap;
    }
    
    .navigation-tabs {
        flex-wrap: wrap;
        justify-content: center;
        bottom: 10px;
        padding: 8px;
    }
    
    .nav-tab {
        padding: 8px 12px;
        font-size: 0.7em;
    }
}
    </style>
    </head>
    <body>
     @include('components.sidebar-navbar')
    <div class="p-4 pt-20 pl-60 pr-5" >
        <!-- Header -->
        <div class="header">
    <h1 style="color:black"><i class="fas fa-chart-line mr-3 text-green-500" style="color:"></i>Dashboard Indikator Mutu</h1>
    <p style="color:gray">Sistem Monitoring Kualitas Pelayanan Rumah Sakit</p>
</div>

<!-- Indicator Acronyms Table -->
<div class="indicator-table-container">
    <table class="indicator-table">
        <tr>
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
        </tr>
    </table>
</div>

{{-- <!-- Navigation Tabs -->
<div class="navigation-tabs">
    <button class="nav-tab active" onclick="showSection('list')">LIST</button>
    <button class="nav-tab" onclick="showSection('hand-hygiene')">Hand Hygiene</button>
    <button class="nav-tab" onclick="showSection('apd')">APD</button>
    <button class="nav-tab" onclick="showSection('identifikasi')">Identifikasi</button>
    <button class="nav-tab" onclick="showSection('wtri')">WTRI</button>
    <button class="nav-tab" onclick="showSection('kritis-lab')">Kritis Lab</button>
    <button class="nav-tab" onclick="showSection('fornas')">FORNAS</button>
    <button class="nav-tab" onclick="showSection('visite')">VISITE</button>
    <button class="nav-tab" onclick="showSection('jatuh')">JATUH</button>
    <button class="nav-tab" onclick="showSection('cp')">CP</button>
    <button class="nav-tab" onclick="showSection('kepuasan')">Kepuasan</button>
    <button class="nav-tab" onclick="showSection('krk')">KRK</button>
    <button class="nav-tab" onclick="showSection('poe')">POE</button>
    <button class="nav-tab" onclick="showSection('sc')">SC</button>
</div> --}}

        <!-- Statistics Overview -->
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

        <!-- Main Content Grid -->
        <div class="main-grid">
            <!-- Indicators Section -->
            <div class="indicators-section">
                <h2><i class="fas fa-tasks"></i> Daftar Indikator Mutu</h2>
                
                <div class="indicator-item completed">
                    <div class="indicator-title">Kepatuhan Kebersihan Tangan (≥80%)</div>
                    <div class="unit-badge">PPI</div>
                    <div class="status-badge status-completed">✓</div>
                    <div class="status-badge status-completed">Selesai</div>
                    <div class="status-badge status-completed">✓</div>
                    <div class="status-badge status-completed">Tidak Ada</div>
                    <button class="action-btn" onclick="openForm('kebersihan')">Lihat Detail</button>
                </div>

                <div class="indicator-item completed">
                    <div class="indicator-title">Kepatuhan Identifikasi pasien (≥80%)</div>
                    <div class="unit-badge">PPI</div>
                    <div class="status-badge status-completed">✓</div>
                    <div class="status-badge status-completed">Selesai</div>
                    <div class="status-badge status-completed">✓</div>
                    <div class="status-badge status-completed">Tidak Ada</div>
                    <button class="action-btn" onclick="openForm('identifikasi')">Lihat Detail</button>
                </div>

                <div class="indicator-item completed">
                    <div class="indicator-title">Kepatuhan Penggunaan Alat Pelindung Diri (100%)</div>
                    <div class="unit-badge">YANMED</div>
                    <div class="status-badge status-completed">✓</div>
                    <div class="status-badge status-completed">Selesai</div>
                    <div class="status-badge status-completed">✓</div>
                    <div class="status-badge status-completed">Tidak Ada</div>
                    <button class="action-btn" onclick="openForm('apd')">Lihat Detail</button>
                </div>

                <div class="indicator-item in-progress">
                    <div class="indicator-title">Waktu tanggap seksio sesarea emergency (≥30%)</div>
                    <div class="unit-badge">IBS</div>
                    <div class="status-badge status-progress">Saat Kam</div>
                    <div class="status-badge status-progress">Dalam Proses</div>
                    <div class="status-badge status-progress">Belum Ada</div>
                    <div class="status-badge status-progress">Tidak Ada</div>
                    <button class="action-btn" onclick="openForm('seksio')">Input Data</button>
                </div>

                <div class="indicator-item completed">
                    <div class="indicator-title">Waktu tunggu rawat jalan (≥80%)</div>
                    <div class="unit-badge">YANMED</div>
                    <div class="status-badge status-completed">Bulan X</div>
                    <div class="status-badge status-completed">Selesai</div>
                    <div class="status-badge status-completed">✓</div>
                    <div class="status-badge status-completed">Tidak Ada</div>
                    <button class="action-btn" onclick="openForm('wtri')">Lihat Detail</button>
                </div>

                <div class="indicator-item pending">
                    <div class="indicator-title">Penundaan operasi elektif (<5%) </div>
                    <div class="unit-badge">IBS</div>
                    <div class="status-badge status-pending">Saat Kam</div>
                    <div class="status-badge status-pending">Belum Dimulai</div>
                    <div class="status-badge status-pending">Belum Ada</div>
                    <div class="status-badge status-pending">Tidak Ada</div>
                    <button class="action-btn" onclick="openForm('operasi')">Mulai Input</button>
                </div>

                <div class="indicator-item completed">
                    <div class="indicator-title">Kepatuhan waktu visite dokter (≥80%)</div>
                    <div class="unit-badge">YANMED/SIRS</div>
                    <div class="status-badge status-completed">Persiapan</div>
                    <div class="status-badge status-completed">Selesai</div>
                    <div class="status-badge status-completed">✓</div>
                    <div class="status-badge status-completed">Tidak Ada</div>
                    <button class="action-btn" onclick="openForm('visite')">Lihat Detail</button>
                </div>

                <div class="indicator-item completed">
                    <div class="indicator-title">Kepatuhan hasil kritis laboratorium (≥80%)</div>
                    <div class="unit-badge">PK</div>
                    <div class="status-badge status-completed">✓</div>
                    <div class="status-badge status-completed">Selesai</div>
                    <div class="status-badge status-completed">✓</div>
                    <div class="status-badge status-completed">Tidak Ada</div>
                    <button class="action-btn" onclick="openForm('kritis')">Lihat Detail</button>
                </div>

                <div class="indicator-item completed">
                    <div class="indicator-title">Kepatuhan penggunaan Formularium (≥80%)</div>
                    <div class="unit-badge">FARMASI</div>
                    <div class="status-badge status-completed">✓</div>
                    <div class="status-badge status-completed">Selesai</div>
                    <div class="status-badge status-completed">✓</div>
                    <div class="status-badge status-completed">Tidak Ada</div>
                    <button class="action-btn" onclick="openForm('fornas')">Lihat Detail</button>
                </div>

                <div class="indicator-item in-progress">
                    <div class="indicator-title">Kepatuhan terhadap clinical pathway (≥80%)</div>
                    <div class="unit-badge">KOMITE</div>
                    <div class="status-badge status-progress">Lengkap</div>
                    <div class="status-badge status-progress">MD berbeda</div>
                    <div class="status-badge status-progress">Belum Ada</div>
                    <div class="status-badge status-progress">Tidak Ada</div>
                    <button class="action-btn" onclick="openForm('cp')">Input Data</button>
                </div>

                <div class="indicator-item completed">
                    <div class="indicator-title">Kepatuhan upaya pencegahan risiko pasien jatuh (100%)</div>
                    <div class="unit-badge">YANMED</div>
                    <div class="status-badge status-completed">✓</div>
                    <div class="status-badge status-completed">Selesai</div>
                    <div class="status-badge status-completed">✓</div>
                    <div class="status-badge status-completed">Tidak Ada</div>
                    <button class="action-btn" onclick="openForm('risiko')">Lihat Detail</button>
                </div>

                <div class="indicator-item completed">
                    <div class="indicator-title">Kecepatan waktu tanggap terhadap komplain (≥80%)</div>
                    <div class="unit-badge">ADMISI</div>
                    <div class="status-badge status-completed">✓</div>
                    <div class="status-badge status-completed">Selesai</div>
                    <div class="status-badge status-completed">✓</div>
                    <div class="status-badge status-completed">Tidak Ada</div>
                    <button class="action-btn" onclick="openForm('komplain')">Lihat Detail</button>
                </div>

                <div class="indicator-item pending">
                    <div class="indicator-title">Kepuasan pasien (≥76.61%)</div>
                    <div class="unit-badge">ADMISI</div>
                    <div class="status-badge status-pending">✓</div>
                    <div class="status-badge status-pending">Belum Dimulai</div>
                    <div class="status-badge status-pending">Belum Ada</div>
                    <div class="status-badge status-pending">Tidak Ada</div>
                    <button class="action-btn" onclick="openForm('kepuasan')">Mulai Input</button>
                </div>
            </div>

            <!-- Sidebar -->
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

        <!-- Data Forms Section -->
        <div class="data-forms">
            <!-- Kebersihan Tangan Form -->
            <div class="form-card" id="kebersihan-form" style="display: none;">
                <h3><i class="fas fa-hands-wash"></i> Kepatuhan Kebersihan Tangan</h3>
                <table class="form-table">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Dokter</th>
                            <th>Perawat</th>
                            <th>Pendidikan</th>
                            <th>Tenaga Kesehatan Lain</th>
                            <th>Total Per Sesi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="3">Januari</td>
                            <td>Kepatuhan (%)</td>
                            <td><input type="number" value="85" /></td>
                            <td><input type="number" value="90" /></td>
                            <td><input type="number" value="88" /></td>
                            <td><input type="number" value="87.7" readonly /></td>
                        </tr>
                        <tr>
                            <td>Handwash (%)</td>
                            <td><input type="number" value="75" /></td>
                            <td><input type="number" value="80" /></td>
                            <td><input type="number" value="78" /></td>
                            <td><input type="number" value="77.7" readonly /></td>
                        </tr>
                        <tr>
                            <td>Kepatuhan (%)</td>
                            <td><input type="number" value="92" /></td>
                            <td><input type="number" value="88" /></td>
                            <td><input type="number" value="90" /></td>
                            <td><input type="number" value="90" readonly /></td>
                        </tr>
                    </tbody>
                </table>
                <div class="formula-box">
                    <div class="formula-text">
                        Formula = (Total kepatuhan kebersihan tangan yang dilakukan / Petugas kebersihan tangan yang seharusnya menggunakan APD sesuai SPO pada saat melayani pasien) × 100%
                    </div>
                </div>
            </div>

            <!-- APD Form -->
            <div class="form-card" id="apd-form" style="display: none;">
                <h3><i class="fas fa-shield-alt"></i> Kepatuhan Penggunaan APD</h3>
                <table class="form-table">
                    <thead>
                        <tr>
                            <th>TGL</th>
                            <th>PROFESI</th>
                            <th>RUANG</th>
                            <th>AKAN/PELAY</th>
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
                            <td><input type="date" /></td>
                            <td><input type="text" placeholder="Profesi" /></td>
                            <td><input type="text" placeholder="Ruang" /></td>
                            <td><input type="text" placeholder="Pelayanan" /></td>
                            <td><input type="checkbox" /></td>
                            <td><input type="checkbox" /></td>
                            <td><input type="checkbox" /></td>
                            <td><input type="checkbox" /></td>
                            <td><input type="checkbox" /></td>
                            <td><input type="checkbox" /></td>
                            <td><input type="checkbox" /></td>
                            <td><input type="checkbox" /></td>
                            <td><input type="checkbox" /></td>
                            <td><input type="checkbox" /></td>
                            <td><input type="checkbox" /></td>
                            <td><input type="checkbox" /></td>
                            <td><select><option>Patuh</option><option>Tidak</option></select></td>
                            <td><input type="text" placeholder="Keterangan" /></td>
                        </tr>
                    </tbody>
                </table>
                <div class="formula-box">
                    <div class="formula-text">
                        Formula = (Jumlah petugas kesehatan yang patuh menggunakan APD sesuai SPO / Jumlah seluruh petugas kesehatan yang seharusnya menggunakan APD sesuai SPO pada saat melayani pasien) × 100%
                    </div>
                </div>
            </div>
<!-- Identifikasi Pasien Form -->
<div class="form-card" id="identifikasi-form" style="display: none;">
    <h3><i class="fas fa-id-card"></i> Kepatuhan Identifikasi Pasien</h3>
    
    <div class="form-section">
        <div class="form-section-title">Unit Kerja: 
            <select>
                <option>PJT</option>
                <option>RA</option>
                <option>GIZI</option>
                <option>REHAB ME</option>
                <option>RADIOTERAPI</option>
                <option>PAVILI</option>
                <option>RB</option>
                <option>IRJ</option>
                <option>UTD</option>
                <option>KEDOKTERAN NUKLIR</option>
                <option>ICD</option>
                <option>PK</option>
                <option>IBS</option>
                <option>RADIOLOGI</option>
                <option>ICU</option>
                <option>PA</option>
                <option>IDT</option>
                <option>MIKROBIOLOGI</option>
            </select>
        </div>
        
        <table class="form-table">
            <thead>
                <tr>
                    <th rowspan="2">No (D)</th>
                    <th rowspan="2">Tgl</th>
                    <th rowspan="2">Staf yang Diobservasi</th>
                    <th colspan="6">Parameter Disk</th>
                    <th rowspan="2">Verbal</th>
                    <th rowspan="2">Visual</th>
                    <th rowspan="2">Identifikasi</th>
                    <th rowspan="2">Tidak Dilakukan</th>
                </tr>
                <tr>
                    <th>Parameter Diagnostik/Tindakan</th>
                    <th>Nama</th>
                    <th>Tanggal Lahir</th>
                    <th>No. RM</th>
                    <th>Alamat</th>
                    <th>Lainnya</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="number" value="1" /></td>
                    <td><input type="date" /></td>
                    <td><input type="text" placeholder="Nama Staf" /></td>
                    <td><input type="checkbox" /></td>
                    <td><input type="checkbox" /></td>
                    <td><input type="checkbox" /></td>
                    <td><input type="checkbox" /></td>
                    <td><input type="checkbox" /></td>
                    <td><input type="checkbox" /></td>
                    <td><input type="checkbox" /></td>
                    <td><input type="checkbox" /></td>
                    <td><input type="checkbox" /></td>
                    <td><input type="checkbox" /></td>
                </tr>
                <tr>
                    <td><input type="number" value="2" /></td>
                    <td><input type="date" /></td>
                    <td><input type="text" placeholder="Nama Staf" /></td>
                    <td><input type="checkbox" /></td>
                    <td><input type="checkbox" /></td>
                    <td><input type="checkbox" /></td>
                    <td><input type="checkbox" /></td>
                    <td><input type="checkbox" /></td>
                    <td><input type="checkbox" /></td>
                    <td><input type="checkbox" /></td>
                    <td><input type="checkbox" /></td>
                    <td><input type="checkbox" /></td>
                    <td><input type="checkbox" /></td>
                </tr>
                <!-- NB row -->
                <tr>
                    <td><strong>NB</strong></td>
                    <td colspan="11">
                        <div style="display: flex; justify-content: space-between;">
                            <div>
                                <input type="checkbox" id="verbal-visual" />
                                <label for="verbal-visual">Verbal & Visual</label>
                            </div>
                            <div>
                                <input type="checkbox" id="dua-parameter" />
                                <label for="dua-parameter">2 Parameter</label>
                            </div>
                            <div>
                                <input type="checkbox" id="satu-parameter" />
                                <label for="satu-parameter">1 Parameter</label>
                            </div>
                            <div>
                                <input type="checkbox" id="tidak-dilakukan" />
                                <label for="tidak-dilakukan">Tidak Dilakukan</label>
                            </div>
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
    
    <div class="form-actions">
        <button class="save-btn" onclick="saveFormData('identifikasi')"><i class="fas fa-save"></i> Simpan</button>
        <button class="cancel-btn" onclick="backToList()"><i class="fas fa-times"></i> Batal</button>
    </div>
</div>
            <!-- Waktu Tunggu Rawat Jalan Form -->
            <div class="form-card" id="wtri-form" style="display: none;">
                <h3><i class="fas fa-clock"></i> Waktu Tunggu Pelayanan Rawat Jalan (WTPR)</h3>
                <div style="margin-bottom: 20px;">
                    <strong>Unit Kerja:</strong>
                    <div style="margin: 10px 0;">
                        <label><input type="radio" name="unit" value="IRJ"> 1. IRJ</label><br>
                        <label><input type="radio" name="unit" value="PJT"> 2. PJT</label><br>
                        <label><input type="radio" name="unit" value="EKSEKUTIF"> 3. EKSEKUTIF</label>
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
                            <td><input type="number" /></td>
                            <td><input type="date" /></td>
                            <td><input type="text" /></td>
                            <td><input type="text" /></td>
                            <td><input type="time" /></td>
                            <td><input type="time" /></td>
                            <td><input type="time" /></td>
                            <td><input type="number" value="40" /></td>
                            <td><input type="number" value="1" /></td>
                            <td><input type="number" value="70" /></td>
                            <td><input type="number" value="0" /></td>
                        </tr>
                    </tbody>
                </table>
                <div class="formula-box">
                    <div class="formula-text">
                        Formula = (Jumlah pasien rawat jalan dengan waktu tunggu ≤ 60 menit / Jumlah pasien rawat jalan yang diobservasi) × 100%
                    </div>
                </div>
            </div>

            <!-- Kritis Laboratorium Form -->
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
                            <td><input type="number" /></td>
                            <td><input type="date" /></td>
                            <td><input type="text" /></td>
                            <td><input type="text" /></td>
                            <td><input type="text" /></td>
                            <td><input type="time" /></td>
                            <td><input type="time" /></td>
                            <td><input type="text" /></td>
                            <td><input type="number" /></td>
                            <td><select><option>≤ 30 Menit</option><option>> 30 Menit</option></select></td>
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
            </div>

            <!-- FORNAS Form -->
            <div class="form-card" id="fornas-form" style="display: none;">
                <h3><i class="fas fa-pills"></i> Kepatuhan Penggunaan Formularium Nasional (FORNAS)</h3>
                <div class="form-section">
                    <div class="form-section-title">Unit Kerja: Seluruh Depo Farmasi</div>
                    <table class="form-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Unit Kerja</th>
                                <th>Nama Pasien</th>
                                <th>No. RM</th>
                                <th>Jumlah Resep</th>
                                <th>Formularium & Non Formularium (D)</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Formularium (N)</th>
                                <th>Non Formularium</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><input type="text" placeholder="Unit Kerja" /></td>
                                <td><input type="text" placeholder="Nama Pasien" /></td>
                                <td><input type="text" placeholder="No. RM" /></td>
                                <td><input type="number" /></td>
                                <td><input type="number" /></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td><input type="text" /></td>
                                <td><input type="text" /></td>
                                <td><input type="text" /></td>
                                <td><input type="number" /></td>
                                <td><input type="number" /></td>
                            </tr>
                            <tr>
                                <td colspan="4">TOTAL</td>
                                <td><input type="number" readonly /></td>
                                <td>N/D × 100%</td>
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
            </div>

            <!-- VISITE Form -->
            <div class="form-card" id="visite-form" style="display: none;">
                <h3><i class="fas fa-user-md"></i> Kepatuhan Waktu Visite Dokter</h3>
                <div class="form-section">
                    <div class="form-section-title">Bulan: <input type="month" /></div>
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
                                <td><input type="date" /></td>
                                <td><input type="text" /></td>
                                <td><input type="text" /></td>
                                <td><input type="text" /></td>
                                <td><input type="number" /></td>
                                <td><input type="number" /></td>
                                <td><input type="text" /></td>
                                <td><input type="text" /></td>
                                <td><input type="date" /></td>
                                <td><input type="time" /></td>
                                <td><input type="number" /></td>
                                <td><input type="number" /></td>
                                <td><input type="number" /></td>
                                <td><input type="number" /></td>
                                <td><input type="number" readonly /></td>
                                <td><input type="time" /></td>
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
            </div>

            <!-- JATUH Form -->
            <div class="form-card" id="jatuh-form" style="display: none;">
                <h3><i class="fas fa-procedures"></i> Kepatuhan Upaya Pencegahan Risiko Jatuh</h3>
                <div class="form-section">
                    <div class="form-section-title">Pasien Rawat Inap Berisiko Tinggi Jatuh</div>
                    <table class="form-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Pasien</th>
                                <th>No. RM</th>
                                <th>Ruangan</th>
                                <th>Skor Risiko Jatuh</th>
                                <th> Risiko</th>
                                <th>Intervensi</th>
                                <th>Evaluasi</th>
                                <th>Kepatuhan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><input type="date" /></td>
                                <td><input type="text" /></td>
                                <td><input type="text" /></td>
                                <td><input type="text" /></td>
                                <td><input type="number" /></td>
                                <td><select><option>Ya</option><option>Tidak</option></select></td>
                                <td><select><option>Ya</option><option>Tidak</option></select></td>
                                <td><select><option>Ya</option><option>Tidak</option></select></td>
                                <td><select><option>Patuh</option><option>Tidak Patuh</option></select></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="formula-box">
                    <div class="formula-text">
                        Formula: Jumlah pasien rawat inap berisiko tinggi jatuh yang mendapatkan ketiga upaya (identifikasi, intervensi, evaluasi) / Jumlah pasien rawat inap berisiko tinggi jatuh × 100%
                    </div>
                </div>
            </div>

            <!-- CP (Clinical Pathway) Form -->
            <div class="form-card" id="cp-form" style="display: none;">
                <h3><i class="fas fa-clipboard-list"></i> Kepatuhan Terhadap Clinical Pathway</h3>
                <div class="form-section">
                    <div class="form-section-title">Data Kepatuhan Clinical Pathway</div>
                    <table class="form-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Pasien</th>
                                <th>No. RM</th>
                                <th>Diagnosa</th>
                                <th>Clinical Pathway</th>
                                <th>Kesesuaian</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><input type="date" /></td>
                                <td><input type="text" /></td>
                                <td><input type="text" /></td>
                                <td><input type="text" /></td>
                                <td><select><option>Ya</option><option>Tidak</option></select></td>
                                <td><select><option>Sesuai</option><option>Tidak Sesuai</option></select></td>
                                <td><input type="text" /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="formula-box">
                    <div class="formula-text">
                        Formula: (Jumlah pasien dengan clinical pathway yang sesuai standar / Jumlah pasien yang diobservasi) × 100%
                    </div>
                </div>
            </div>

            <!-- KEPUASAN Form -->
            <div class="form-card" id="kepuasan-form" style="display: none;">
                <h3><i class="fas fa-smile"></i> Kepuasan Pasien</h3>
                <div class="form-section">
                    <div class="form-section-title">Survey Kepuasan Pasien</div>
                    <table class="form-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Pasien</th>
                                <th>No. RM</th>
                                <th>Jenis Pelayanan</th>
                                <th>Nilai Kepuasan (1-5)</th>
                                <th>Komentar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><input type="date" /></td>
                                <td><input type="text" /></td>
                                <td><input type="text" /></td>
                                <td><select>
                                    <option>Rawat Jalan</option>
                                    <option>Rawat Inap</option>
                                    <option>IGD</option>
                                    <option>Farmasi</option>
                                    <option>Laboratorium</option>
                                </select></td>
                                <td><select>
                                    <option>1 (Sangat Tidak Puas)</option>
                                    <option>2 (Tidak Puas)</option>
                                    <option>3 (Cukup Puas)</option>
                                    <option>4 (Puas)</option>
                                    <option>5 (Sangat Puas)</option>
                                </select></td>
                                <td><input type="text" /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="formula-box">
                    <div class="formula-text">
                        Formula: (Jumlah responden yang memberikan nilai ≥4 / Jumlah total responden) × 100% ≥ 76.61%
                    </div>
                </div>
            </div>

            <!-- KRK Form -->
            <div class="form-card" id="krk-form" style="display: none;">
                <h3><i class="fas fa-exclamation-triangle"></i> Kecepatan Waktu Tanggap Komplain (KRK)</h3>
                <div class="form-section">
                    <table class="form-table">
                        <thead>
                            <tr>
                                <th>No (D)</th>
                                <th>Tgl</th>
                                <th>Isi Komplain</th>
                                <th>Kategori Komplain</th>
                                <th>Pelaporan Komplain</th>
                                <th>Grading Komplain</th>
                                <th>Waktu tanggap komplain (hari)</th>
                                <th>Penyelesaian komplain sesuai grading</th>
                                <th>Ket.</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Lisan</th>
                                <th>Tulisan</th>
                                <th>Media Masa</th>
                                <th>Merah (max 1x24 jam)</th>
                                <th>Kuning (max 3 hari)</th>
                                <th>Hijau (max 7 hari)</th>
                                <th>Ya (N)</th>
                                <th>Tidak</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><input type="date" /></td>
                                <td><input type="text" /></td>
                                <td><input type="text" /></td>
                                <td><input type="checkbox" /></td>
                                <td><input type="checkbox" /></td>
                                <td><input type="checkbox" /></td>
                                <td><input type="checkbox" /></td>
                                <td><input type="checkbox" /></td>
                                <td><input type="checkbox" /></td>
                                <td><input type="checkbox" /></td>
                                <td><input type="checkbox" /></td>
                                <td><input type="text" /></td>
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
            </div>

            <!-- POE (Penundaan Operasi Elektif) Form -->
            <div class="form-card" id="poe-form" style="display: none;">
                <h3><i class="fas fa-calendar-times"></i> Penundaan Operasi Elektif</h3>
                <div class="form-section">
                    <div class="form-section-title">Unit: Instalasi Bedah Sentral</div>
                    <table class="form-table">
                        <thead>
                            <tr>
                                <th>No (D)</th>
                                <th>Hari/Tanggal</th>
                                <th>Nama Pasien</th>
                                <th>No. RM</th>
                                <th>Ruangan</th>
                                <th>Diagnosa</th>
                                <th>Tindakan Bedah</th>
                                <th>DPJP Bedah</th>
                                <th>Jam Rencana Operasi</th>
                                <th>Jam Insisi</th>
                                <th>Penundaan Operasi</th>
                                <th>Keterangan</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>>1 jam (N)</th>
                                <th>≤ 1 jam</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><input type="date" /></td>
                                <td><input type="text" /></td>
                                <td><input type="text" /></td>
                                <td><input type="text" /></td>
                                <td><input type="text" /></td>
                                <td><input type="text" /></td>
                                <td><input type="text" /></td>
                                <td><input type="time" /></td>
                                <td><input type="time" /></td>
                                <td><input type="checkbox" /></td>
                                <td><input type="checkbox" /></td>
                                <td><input type="text" /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="formula-box">
                    <div class="formula-text">
                        Formula: (Jumlah pasien yang jadwal operasinya tertunda > 1 jam / Jumlah pasien operasi elektif) × 100% < 5%
                    </div>
                </div>
            </div>

            <!-- SC (Seksio Sesarea) Form -->
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
                                <td><input type="text" /></td>
                                <td><input type="text" /></td>
                                <td><select>
                                    <option>Kategori I</option>
                                    <option>Kategori II</option>
                                    <option>Kategori III</option>
                                </select></td>
                                <td><input type="time" /></td>
                                <td><input type="time" /></td>
                                <td><input type="number" /></td>
                                <td><select><option>Ya</option><option>Tidak</option></select></td>
                                <td><input type="text" /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="formula-box">
                    <div class="formula-text">
                        Formula: (Jumlah pasien yang diputuskan tindakan operasi Seksio Sesarea kategori I yang mendapatkan tindakan seksio sesarea emergensi ≤ 30 menit / Jumlah pasien yang diputuskan tindakan operasi seksio sesarea emergensi kategori I) × 100% ≥ 30%
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Spinner -->
        <div class="loading-spinner" id="loading">
            <div class="spinner"></div>
         
        </div>

        {{-- <!-- Navigation Tabs -->
        <div class="navigation-tabs">
            <button class="nav-tab active" onclick="showSection('list')">LIST</button>
            <button class="nav-tab" onclick="showSection('hand-hygiene')">Hand Hygiene</button>
            <button class="nav-tab" onclick="showSection('apd')">APD</button>
            <button class="nav-tab" onclick="showSection('identifikasi')">Identifikasi</button>
            <button class="nav-tab" onclick="showSection('wtri')">WTRI</button>
            <button class="nav-tab" onclick="showSection('kritis-lab')">Kritis Lab</button>
            <button class="nav-tab" onclick="showSection('fornas')">FORNAS</button>
            <button class="nav-tab" onclick="showSection('visite')">VISITE</button>
            <button class="nav-tab" onclick="showSection('jatuh')">JATUH</button>
            <button class="nav-tab" onclick="showSection('cp')">CP</button>
            <button class="nav-tab" onclick="showSection('kepuasan')">Kepuasan</button>
            <button class="nav-tab" onclick="showSection('krk')">KRK</button>
            <button class="nav-tab" onclick="showSection('poe')">POE</button>
            <button class="nav-tab" onclick="showSection('sc')">SC</button>
        </div>
    </div> --}}

    <script>
        // Global variables
        let currentSection = 'list';
        let indicators = [];

        // Initialize the application
        document.addEventListener('DOMContentLoaded', function() {
            initializeData();
            updateStatistics();
            showSection('list');
        });

        // Initialize data
        function initializeData() {
            indicators = [
                { id: 1, name: 'Kepatuhan Kebersihan Tangan', unit: 'PPI', status: 'completed', progress: 100 },
                { id: 2, name: 'Kepatuhan Identifikasi Pasien', unit: 'PPI', status: 'completed', progress: 100 },
                { id: 3, name: 'Kepatuhan Penggunaan APD', unit: 'YANMED', status: 'completed', progress: 100 },
                { id: 4, name: 'Waktu Tanggap Seksio Sesarea', unit: 'IBS', status: 'in-progress', progress: 60 },
                { id: 5, name: 'Waktu Tunggu Rawat Jalan', unit: 'YANMED', status: 'completed', progress: 100 },
                { id: 6, name: 'Penundaan Operasi Elektif', unit: 'IBS', status: 'pending', progress: 0 },
                { id: 7, name: 'Kepatuhan Waktu Visite Dokter', unit: 'YANMED/SIRS', status: 'completed', progress: 100 },
                { id: 8, name: 'Kepatuhan Hasil Kritis Laboratorium', unit: 'PK', status: 'completed', progress: 100 },
                { id: 9, name: 'Kepatuhan Penggunaan Formularium', unit: 'FARMASI', status: 'completed', progress: 100 },
                { id: 10, name: 'Kepatuhan Clinical Pathway', unit: 'KOMITE', status: 'in-progress', progress: 75 },
                { id: 11, name: 'Kepatuhan Pencegahan Risiko Jatuh', unit: 'YANMED', status: 'completed', progress: 100 },
                { id: 12, name: 'Kecepatan Tanggap Komplain', unit: 'ADMISI', status: 'completed', progress: 100 },
                { id: 13, name: 'Kepuasan Pasien', unit: 'ADMISI', status: 'pending', progress: 0 }
            ];
        }

        // Update statistics
        function updateStatistics() {
            const completed = indicators.filter(i => i.status === 'completed').length;
            const inProgress = indicators.filter(i => i.status === 'in-progress').length;
            const pending = indicators.filter(i => i.status === 'pending').length;
            
            document.querySelector('.stat-card:nth-child(2) .stat-number').textContent = completed;
            document.querySelector('.stat-card:nth-child(3) .stat-number').textContent = inProgress;
            document.querySelector('.stat-card:nth-child(4) .stat-number').textContent = pending;
        }

        // Show loading spinner
       
        // Show specific section
        function showSection(section) {
            
            
            // Update active tab
            document.querySelectorAll('.nav-tab').forEach(tab => {
                tab.classList.remove('active');
                if (tab.textContent.toLowerCase().replace(' ', '-') === section.toLowerCase()) {
                    tab.classList.add('active');
                }
            });
            
            // Hide all forms
            document.querySelectorAll('.form-card').forEach(form => {
                form.style.display = 'none';
            });
            
            // Show main grid or specific form
            const mainGrid = document.querySelector('.main-grid');
            const statsGrid = document.querySelector('.stats-grid');
            
            if (section === 'list') {
                mainGrid.style.display = 'grid';
                statsGrid.style.display = 'grid';
            } else {
                mainGrid.style.display = 'none';
                statsGrid.style.display = 'none';
                
                // Show specific form based on section
                const formMap = {
                    'hand-hygiene': 'kebersihan-form',
                    'identifikasi': 'identifikasi-form',
                    'apd': 'apd-form',
                    'wtri': 'wtri-form',
                    'kritis-lab': 'kritis-form',
                    'fornas': 'fornas-form',
                    'visite': 'visite-form',
                    'jatuh': 'jatuh-form',
                    'cp': 'cp-form',
                    'kepuasan': 'kepuasan-form',
                    'krk': 'krk-form',
                    'poe': 'poe-form',
                    'sc': 'sc-form'
                };
                
                const formId = formMap[section];
                if (formId) {
                    setTimeout(() => {
                        document.getElementById(formId).style.display = 'block';
                        document.getElementById(formId).scrollIntoView({ behavior: 'smooth' });
                    }, 1000);
                }
            }
            
            currentSection = section;
        }

        // Open specific form
        function openForm(formType) {
            
            
            // Hide main grid
            document.querySelector('.main-grid').style.display = 'none';
            document.querySelector('.stats-grid').style.display = 'none';
            
            // Show specific form
            setTimeout(() => {
                const formId = formType + '-form';
                const form = document.getElementById(formId);
                if (form) {
                    form.style.display = 'block';
                    form.scrollIntoView({ behavior: 'smooth' });
                    
                    // Update navigation tab
                    document.querySelectorAll('.nav-tab').forEach(tab => {
                        tab.classList.remove('active');
                    });
                    
                    // Find and activate corresponding tab
                    const tabMap = {
                        'kebersihan': 'hand-hygiene',
                        'apd': 'apd',
                        'identifikasi': 'identifikasi',
                        'wtri': 'wtri',
                        'kritis': 'kritis-lab',
                        'fornas': 'fornas',
                        'visite': 'visite',
                        'risiko': 'jatuh',
                        'cp': 'cp',
                        'komplain': 'krk',
                        'kepuasan': 'kepuasan',
                        'operasi': 'poe',
                        'seksio': 'sc'
                    };
                    
                    const tabName = tabMap[formType] || formType;
                    document.querySelectorAll('.nav-tab').forEach(tab => {
                        if (tab.textContent.toLowerCase().replace(' ', '-') === tabName.toLowerCase()) {
                            tab.classList.add('active');
                        }
                    });
                }
            }, 1000);
        }

        // Back to main list
        function backToList() {
            showSection('list');
        }

        // Save form data
        function saveFormData(formType) {
            ading();
            
            // Simulate saving data
            setTimeout(() => {
                showNotification('Data berhasil disimpan!', 'success');
                updateStatistics();
            }, 1000);
        }

        // Export data
        function exportData() {
            const data = {
                indicators: indicators,
                timestamp: new Date().toISOString(),
                exported_by: 'System Administrator'
            };
            
            const blob = new Blob([JSON.stringify(data, null, 2)], {
                type: 'application/json'
            });
            
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'indikator-mutu-' + new Date().toISOString().split('T')[0] + '.json';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            showNotification('Data berhasil diekspor!', 'success');
        }

        // Print report
        function printReport() {
            window.print();
        }

        // Auto-calculate response time for WTPR
        function calculateResponseTime() {
            const regTime = document.querySelector('input[type="time"]:nth-of-type(1)');
            const poliTime = document.querySelector('input[type="time"]:nth-of-type(2)');
            const serviceTime = document.querySelector('input[type="time"]:nth-of-type(3)');
            
            if (regTime && serviceTime && regTime.value && serviceTime.value) {
                const reg = new Date('2000-01-01 ' + regTime.value);
                const service = new Date('2000-01-01 ' + serviceTime.value);
                const diff = (service - reg) / (1000 * 60); // difference in minutes
                
                const responseField = document.querySelector('input[type="number"]:nth-of-type(4)');
                if (responseField) {
                    responseField.value = Math.round(diff);
                }
            }
        }

        // Add event listeners for time inputs
        document.addEventListener('change', function(e) {
            if (e.target.type === 'time') {
                calculateResponseTime();
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey) {
                switch(e.key) {
                    case 's':
                        e.preventDefault();
                        if (currentSection !== 'list') {
                            saveFormData(currentSection);
                        }
                        break;
                    case 'e':
                        e.preventDefault();
                        exportData();
                        break;
                    case 'p':
                        e.preventDefault();
                        printReport();
                        break;
                    case 'Escape':
                        showSection('list');
                        break;
                }
            }
        });

        // Auto-save functionality
        let autoSaveTimer;
        document.addEventListener('input', function(e) {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'SELECT') {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(() => {
                    // Auto-save logic here
                    console.log('Auto-saving data...');
                }, 5000);
            }
        });

        // Progress tracking
        function updateProgress(indicatorId, progress) {
            const indicator = indicators.find(i => i.id === indicatorId);
            if (indicator) {
                indicator.progress = progress;
                if (progress === 100) {
                    indicator.status = 'completed';
                } else if (progress > 0) {
                    indicator.status = 'in-progress';
                } else {
                    indicator.status = 'pending';
                }
                updateStatistics();
            }
        }

        // Notification system
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <i class="fas fa-${getNotificationIcon(type)}"></i>
                <span>${message}</span>
                <button onclick="this.parentElement.remove()">×</button>
            `;
            
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: white;
                padding: 15px 20px;
                border-radius: 10px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                z-index: 10000;
                display: flex;
                align-items: center;
                gap: 10px;
                max-width: 300px;
                animation: slideIn 0.3s ease;
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }

        function getNotificationIcon(type) {
            const icons = {
                'info': 'info-circle',
                'success': 'check-circle',
                'warning': 'exclamation-triangle',
                'error': 'times-circle'
            };
            return icons[type] || 'info-circle';
        }

        // Add CSS for notifications
        const notificationStyles = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            .notification-info { border-left: 4px solid #3498db; }
            .notification-success { border-left: 4px solid #27ae60; }
            .notification-warning { border-left: 4px solid #f39c12; }
            .notification-error { border-left: 4px solid #e74c3c; }
        `;
        
        const styleSheet = document.createElement('style');
        styleSheet.textContent = notificationStyles;
        document.head.appendChild(styleSheet);

        // Welcome message
        setTimeout(() => {
            showNotification('Selamat datang di Dashboard Indikator Mutu!', 'info');
        }, 1000);
    </script>
</body>
</html>