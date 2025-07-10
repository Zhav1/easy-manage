<!DOCTYPE html>
<html lang="en" class="h-full bg-white">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TNA - Pendidikan &amp; Pelatihan</title>

    <!-- Icons & CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/tna.css') }}">
</head>
<script>
    // Variabel global
let daftarStaff = [];
let catatanTNA = [];
let daftarJabatan = [];
let daftarDepartemen = [];

// --- Fungsi Loading ---
function tampilkanLoading() {
    const overlay = document.getElementById('global-loading-overlay');
    if (overlay) {
        overlay.classList.remove('hidden');
    }
}

function sembunyikanLoading() {
    const overlay = document.getElementById('global-loading-overlay');
    if (overlay) {
        overlay.classList.add('hidden');
    }
}

// Inisialisasi aplikasi
document.addEventListener('DOMContentLoaded', function() {
    muatDataAwal();
    pasangEventListeners();
});

// Memuat data awal dari API
async function muatDataAwal() {
    tampilkanLoading();
    try {
        const token = window.authToken;
        if (!token) {
            console.error('Token autentikasi tidak ditemukan');
            return;
        }

        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        };

        const [responStaff, responTNA, responJabatan, responDepartemen] = await Promise.all([
            fetch('/api/v1/staff', {headers}),
            fetch('/api/v1/training-needs', {headers}),
            fetch('/api/v1/positions', {headers}),
            fetch('/api/v1/departments', {headers}),
        ]);

        daftarStaff = await responStaff.json();
        catatanTNA = await responTNA.json();
        daftarJabatan = await responJabatan.json();
        daftarDepartemen = await responDepartemen.json();

        perbaruiDropdownStaffTNA();
        renderTabelStaff();
        renderTabelTNA();
        perbaruiJumlahKartu();

    } catch (error) {
        console.error('Gagal memuat data awal:', error);
        alert('Gagal memuat data awal. Silakan coba lagi.');
    } finally {
        sembunyikanLoading();
    }
}

// Pasang event listener
function pasangEventListeners() {
    // Form Staff
    const formStaff = document.getElementById('staffForm');
    if (formStaff) {
        formStaff.addEventListener('submit', async function(e) {
            e.preventDefault();
            await handleSubmitFormStaff();
        });
    }

    // Form TNA
    const formTNA = document.getElementById('tnaForm');
    if (formTNA) {
        formTNA.addEventListener('submit', async function(e) {
            e.preventDefault();
            const berhasil = await handleSubmitFormTNA();
            if (!berhasil) {
                e.stopImmediatePropagation();
                return false;
            }
        });
    }

    // Modal close
    const modalStaff = document.getElementById('staffModal');
    if (modalStaff) {
        modalStaff.addEventListener('click', function(e) {
            if (e.target === this) tutupModalStaff();
        });
    }

    const modalTNA = document.getElementById('tnaModal');
    if (modalTNA) {
        modalTNA.addEventListener('click', function(e) {
            if (e.target === this) tutupModalTNA();
        });
    }

    // Tombol-tombol
    const tombolTambahStaff = document.getElementById('openAddStaffModalBtn');
    if (tombolTambahStaff) {
        tombolTambahStaff.addEventListener('click', window.bukaModalTambahStaff);
    }

    const tombolTambahTNA = document.getElementById('openAddTnaModalBtn');
    if (tombolTambahTNA) {
        tombolTambahTNA.addEventListener('click', window.bukaModalTambahTNA);
    }
}

// --- Fungsi Modal Staff ---
window.bukaModalTambahStaff = function() {
    document.getElementById('staffModalTitle').textContent = 'Tambah Staff Baru';
    document.getElementById('staffId').value = '';
    document.getElementById('staffFullName').value = '';
    
    const inputUserId = document.getElementById('userId');
    if (inputUserId) inputUserId.value = window.currentUser.id;
    
    const inputDepartemen = document.getElementById('staffDepartment');
    if (inputDepartemen) inputDepartemen.value = window.currentUser.department_id;
    
    const inputRumahSakit = document.getElementById('staffHospital');
    if (inputRumahSakit) inputRumahSakit.value = window.currentUser.hospital_id;

    document.getElementById('staffPosition').value = '';
    document.getElementById('staffStatus').value = 'Aktif';
    document.getElementById('deleteStaffBtn').classList.add('hidden');
    document.getElementById('staffModal').classList.remove('hidden');
    document.getElementById('staffModal').classList.add('flex');
    perbaruiDropdownJabatan();
}

window.bukaModalEditStaff = function(staffId) {
    const staff = daftarStaff.find(s => s.id == staffId);
    if (!staff) return;

    document.getElementById('staffModalTitle').textContent = 'Edit Staff';
    document.getElementById('staffId').value = staff.id;
    document.getElementById('staffFullName').value = staff.name;
    document.getElementById('staffPosition').value = staff.position_id;
    document.getElementById('staffStatus').value = staff.status;
    document.getElementById('deleteStaffBtn').classList.remove('hidden');
    document.getElementById('staffModal').classList.remove('hidden');
    document.getElementById('staffModal').classList.add('flex');
    perbaruiDropdownJabatan();
}

window.tutupModalStaff = function() {
    document.getElementById('staffModal').classList.add('hidden');
    document.getElementById('staffModal').classList.remove('flex');
}

// --- Fungsi Modal TNA ---
window.bukaModalTambahTNA = function() {
    document.getElementById('tnaModalTitle').textContent = 'Tambah Data TNA';
    document.getElementById('tnaId').value = '';
    document.getElementById('tnaStaffName').value = '';
    
    // Set tanggal default ke hari ini
    const hariIni = new Date().toISOString().split('T')[0];
    document.getElementById('tanggal').value = hariIni;
    
    document.getElementById('seminarWorkshopWebinar').value = '';
    document.getElementById('pelatihan').value = '';
    document.getElementById('pendidikanLanjutan').value = '';
    document.getElementById('deleteTnaBtn').classList.add('hidden');
    document.getElementById('tnaModal').classList.remove('hidden');
    document.getElementById('tnaModal').classList.add('flex');
    perbaruiDropdownStaffTNA();
}

window.bukaModalEditTNA = function(tnaId) {
    const tna = catatanTNA.find(t => t.id == tnaId);
    if (!tna) return;

    document.getElementById('tnaModalTitle').textContent = 'Edit Data TNA';
    document.getElementById('tnaId').value = tna.id;
    document.getElementById('tnaStaffName').value = tna.staff_id;
    
    // Format tanggal untuk input type="date" (YYYY-MM-DD)
    const tanggal = tna.tanggal ? new Date(tna.tanggal).toISOString().split('T')[0] : '';
    document.getElementById('tanggal').value = tanggal;
    
    document.getElementById('seminarWorkshopWebinar').value = tna.seminar_workshop_webinar || '';
    document.getElementById('pelatihan').value = tna.pelatihan || '';
    document.getElementById('pendidikanLanjutan').value = tna.pendidikan_lanjutan || '';
    document.getElementById('deleteTnaBtn').classList.remove('hidden');
    document.getElementById('tnaModal').classList.remove('hidden');
    document.getElementById('tnaModal').classList.add('flex');
    perbaruiDropdownStaffTNA();
}

window.tutupModalTNA = function() {
    document.getElementById('tnaModal').classList.add('hidden');
    document.getElementById('tnaModal').classList.remove('flex');
}

// --- Handler Form ---
async function handleSubmitFormStaff() {
    tampilkanLoading();
    const dataForm = {
        id: document.getElementById('staffId').value,
        name: document.getElementById('staffFullName').value,
        position_id: document.getElementById('staffPosition').value,
        user_id: window.currentUser.id,
        department_id: window.currentUser.department_id,
        hospital_id: window.currentUser.hospital_id,
        status: document.getElementById('staffStatus').value
    };

    try {
        const token = window.authToken;
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        };

        const url = dataForm.id ? `/api/v1/staff/${dataForm.id}` : '/api/v1/staff';
        const method = dataForm.id ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method: method,
            headers,
            body: JSON.stringify(dataForm)
        });

        if (!response.ok) {
            const errorData = await response.json();
            const pesanError = errorData.message || 
                             errorData.errors?.join(', ') || 
                             'Gagal menyimpan data';
            throw new Error(pesanError);
        }

        await muatDataAwal();
        tutupModalStaff();
        alert('Data staff berhasil disimpan!');
    } catch (error) {
        console.error('Gagal menyimpan staff:', error);
        alert('Gagal menyimpan data staff: ' + error.message);
    } finally {
        sembunyikanLoading();
    }
}

async function handleSubmitFormTNA() {
    tampilkanLoading();
    
    // Validasi field yang wajib diisi
    const inputTanggal = document.getElementById('tanggal');
    const selectStaff = document.getElementById('tnaStaffName');
    
    if (!inputTanggal.value) {
        alert('Harap isi tanggal terlebih dahulu!');
        inputTanggal.focus();
        sembunyikanLoading();
        return false;
    }
    
    if (!selectStaff.value) {
        alert('Harap pilih staff terlebih dahulu!');
        selectStaff.focus();
        sembunyikanLoading();
        return false;
    }

    const dataForm = {
        id: document.getElementById('tnaId').value,
        staff_id: selectStaff.value,
        tanggal: inputTanggal.value,
        seminar_workshop_webinar: document.getElementById('seminarWorkshopWebinar').value,
        pelatihan: document.getElementById('pelatihan').value,
        pendidikan_lanjutan: document.getElementById('pendidikanLanjutan').value,
    };

    try {
        const token = window.authToken;
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        };

        const url = dataForm.id ? `/api/v1/training-needs/${dataForm.id}` : '/api/v1/training-needs';
        const method = dataForm.id ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method: method,
            headers,
            body: JSON.stringify(dataForm)
        });

        if (!response.ok) {
            const errorData = await response.json();
            const pesanError = errorData.message || 
                             errorData.errors?.join(', ') || 
                             'Gagal menyimpan data';
            throw new Error(pesanError);
        }

        await muatDataAwal();
        tutupModalTNA();
        alert('Data TNA berhasil disimpan!');
    } catch (error) {
        console.error('Gagal menyimpan TNA:', error);
        alert('Gagal menyimpan data TNA: ' + error.message);
    } finally {
        sembunyikanLoading();
    }
    return true;
}

// --- Fungsi Hapus Data ---
window.hapusStaff = async function() {
    const staffId = document.getElementById('staffId').value;
    if (!staffId || !confirm('Apakah Anda yakin ingin menghapus staff ini? Semua data TNA terkait juga akan dihapus.')) return;
    
    tampilkanLoading();
    try {
        const token = window.authToken;
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        };

        const response = await fetch(`/api/v1/staff/${staffId}`, {
            method: 'DELETE',
            headers
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Gagal menghapus data');
        }

        await muatDataAwal();
        tutupModalStaff();
        alert('Data staff berhasil dihapus!');
    } catch (error) {
        console.error('Gagal menghapus staff:', error);
        alert('Gagal menghapus staff: ' + error.message);
    } finally {
        sembunyikanLoading();
    }
}

window.hapusDataTNA = async function() {
    const tnaId = document.getElementById('tnaId').value;
    if (!tnaId || !confirm('Apakah Anda yakin ingin menghapus data TNA ini?')) return;

    tampilkanLoading();
    try {
        const token = window.authToken;
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        };

        const response = await fetch(`/api/v1/training-needs/${tnaId}`, {
            method: 'DELETE',
            headers
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Gagal menghapus data');
        }

        await muatDataAwal();
        tutupModalTNA();
        alert('Data TNA berhasil dihapus!');
    } catch (error) {
        console.error('Gagal menghapus TNA:', error);
        alert('Gagal menghapus data TNA: ' + error.message);
    } finally {
        sembunyikanLoading();
    }
}

// --- Fungsi Render Tabel ---
function renderTabelStaff() {
    const tbody = document.getElementById('staffTableBody');
    if (!tbody) {
        console.error('Elemen tabel staff tidak ditemukan!');
        return;
    }

    tbody.innerHTML = '';

    if (!daftarStaff || daftarStaff.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = `<td colspan="6" class="text-center py-4">Tidak ada data staff</td>`;
        tbody.appendChild(row);
        return;
    }

    daftarStaff.forEach((staff, index) => {
        const departemen = daftarDepartemen.find(d => d.id === staff.department_id) || { name: '-' };
        const jabatan = daftarJabatan.find(p => p.id === staff.position_id) || { name: '-' };

        const row = document.createElement('tr');
        row.classList.add('hover:bg-white', 'transition-all', 'duration-300');
        row.innerHTML = `
            <td class="px-6 py-4">${index + 1}</td>
            <td class="px-6 py-4 flex items-center">
                <div class="w-10 h-10 bg-[#0CC0DF] rounded-full flex items-center justify-center text-white font-bold mr-3">${staff.name.charAt(0).toUpperCase()}</div>
                ${staff.name}
            </td>
            <td class="px-6 py-4">${jabatan.name}</td>
            <td class="px-6 py-4">${departemen.name}</td>
            <td class="px-6 py-4">
                <span class="px-2 py-1 rounded-full text-xs ${
                    staff.status === 'Aktif' ? 'bg-green-100 text-green-800' :
                    staff.status === 'Cuti' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'
                }">
                    ${staff.status}
                </span>
            </td>
            <td class="px-6 py-4 flex space-x-2">
                <button onclick="bukaModalEditStaff(${staff.id})" class="bg-white hover:bg-gray-100 text-black px-4 py-2 rounded-lg text-xs font-medium transition-all duration-300 flex items-center border border-[#0CC0DF] mr-2">
                    <i class="fas fa-pen mr-1 text-[#0CC0DF]"></i>Edit
                </button>
                <button onclick="konfirmasiHapusStaff(${staff.id})" class="bg-white hover:bg-gray-100 text-black px-4 py-2 rounded-lg text-xs font-medium transition-all duration-300 flex items-center border border-red-500">
                    <i class="fas fa-trash mr-1 text-red-500"></i>Hapus
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function renderTabelTNA() {
    const tbody = document.getElementById('tnaRecordsTableBody');
    if (!tbody) {
        console.error('Elemen tabel TNA tidak ditemukan!');
        return;
    }

    tbody.innerHTML = '';

    if (!catatanTNA || catatanTNA.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = `<td colspan="6" class="text-center py-4">Tidak ada data pendidikan & pelatihan</td>`;
        tbody.appendChild(row);
        return;
    }

    catatanTNA.forEach(tna => {
        const staff = daftarStaff.find(s => s.id === tna.staff_id);
        const namaStaff = staff ? staff.name : 'N/A';
        
        // Format tanggal untuk ditampilkan
        const tanggal = tna.tanggal ? new Date(tna.tanggal).toLocaleDateString('id-ID') : '-';

        const row = document.createElement('tr');
        row.classList.add('hover:bg-white', 'transition-all', 'duration-300');
        row.innerHTML = `
            <td class="px-4 py-4 flex items-center h-full">
                <div class="w-10 h-10 bg-[#0CC0DF] rounded-full flex items-center justify-center text-white font-bold mr-3">${namaStaff.charAt(0).toUpperCase()}</div>
                <div>${namaStaff}</div>
            </td>
            <td class="px-4 py-4">${tna.seminar_workshop_webinar || 'Belum Ada'}</td>
            <td class="px-4 py-4">${tna.pelatihan || 'Belum Ada'}</td>
            <td class="px-4 py-4">${tna.pendidikan_lanjutan || 'Belum Ada'}</td>
            <td class="px-4 py-4">${tanggal}</td>
            <td class="px-4 py-4 flex flex space-x-2">
                <button onclick="bukaModalEditTNA(${tna.id})" class="bg-white hover:bg-gray-100 text-black px-4 py-2 rounded-lg text-xs font-medium transition-all duration-300 flex items-center border border-[#0CC0DF] mr-2">
                    <i class="fas fa-pen mr-1 text-[#0CC0DF]"></i>Edit
                </button>
                <button onclick="konfirmasiHapusTNA(${tna.id})" class="bg-white hover:bg-gray-100 text-black px-4 py-2 rounded-lg text-xs font-medium transition-all duration-300 flex items-center border border-red-500">
                    <i class="fas fa-trash mr-1 text-red-500"></i>Hapus
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function perbaruiDropdownStaffTNA() {
    const selectStaff = document.getElementById('tnaStaffName');
    if (!selectStaff) {
        console.warn('Elemen #tnaStaffName tidak ditemukan!');
        return;
    }

    selectStaff.innerHTML = '<option value="">Pilih Staff</option>';
    daftarStaff.forEach(staff => {
        const option = document.createElement('option');
        option.value = staff.id;
        option.textContent = staff.name;
        selectStaff.appendChild(option);
    });
}

function perbaruiDropdownJabatan() {
    const selectJabatan = document.getElementById('staffPosition');
    if (!selectJabatan) {
        console.warn('Elemen #staffPosition tidak ditemukan!');
        return;
    }

    selectJabatan.innerHTML = '<option value="">Pilih Jabatan</option>';

    if (!daftarJabatan || !Array.isArray(daftarJabatan)) {
        console.error('Data jabatan tidak valid:', daftarJabatan);
        return;
    }

    daftarJabatan.forEach(jabatan => {
        if (!jabatan.id || !jabatan.name) {
            console.warn('Data jabatan tidak valid:', jabatan);
            return;
        }
        const option = document.createElement('option');
        option.value = jabatan.id;
        option.textContent = jabatan.name;
        selectJabatan.appendChild(option);
    });
}

function perbaruiJumlahKartu() {
    const elemTotalStaff = document.getElementById('totalStaffCount');
    if (elemTotalStaff) { elemTotalStaff.textContent = daftarStaff.length; }

    let totalSeminar = 0;
    let totalPelatihan = 0;
    let totalPendidikanLanjutan = 0;

    catatanTNA.forEach(tna => {
        if (tna.seminar_workshop_webinar && tna.seminar_workshop_webinar !== '') totalSeminar++;
        if (tna.pelatihan && tna.pelatihan !== '') totalPelatihan++;
        if (tna.pendidikan_lanjutan && tna.pendidikan_lanjutan !== '') totalPendidikanLanjutan++;
    });

    const elemTotalSeminar = document.getElementById('totalSeminarCount');
    if (elemTotalSeminar) { elemTotalSeminar.textContent = totalSeminar; }
    const elemTotalPelatihan = document.getElementById('totalPelatihanCount');
    if (elemTotalPelatihan) { elemTotalPelatihan.textContent = totalPelatihan; }
    const elemTotalPendidikan = document.getElementById('totalPendidikanLanjutanCount');
    if (elemTotalPendidikan) { elemTotalPendidikan.textContent = totalPendidikanLanjutan; }
}

// Fungsi Konfirmasi
window.konfirmasiHapusStaff = function(staffId) {
    if (confirm('Apakah Anda yakin ingin menghapus staff ini? Data TNA terkait juga akan terhapus.')) {
        document.getElementById('staffId').value = staffId;
        window.hapusStaff();
    }
};

window.konfirmasiHapusTNA = function(tnaId) {
    if (confirm('Apakah Anda yakin ingin menghapus data TNA ini?')) {
        document.getElementById('tnaId').value = tnaId;
        window.hapusDataTNA();
    }
};

// --- Fungsi Export ---
window.exportKeExcel = async function() {
    tampilkanLoading();
    try {
        const token = window.authToken;
        if (!token) {
            alert('Token autentikasi tidak ditemukan.');
            return;
        }

        const response = await fetch('/api/v1/training-needs', {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        if (!response.ok) throw new Error('Gagal mengambil data TNA untuk export.');

        const data = await response.json();

        // Siapkan data untuk Excel
        const baris = [
            ['Nama Staff', 'Seminar/Workshop/Webinar', 'Pelatihan', 'Pendidikan Lanjutan', 'Tanggal']
        ];
        
        data.forEach(tna => {
            const staff = daftarStaff.find(s => s.id === tna.staff_id);
            const namaStaff = staff ? staff.name : 'N/A';
            const tanggal = tna.tanggal ? new Date(tna.tanggal).toLocaleDateString('id-ID') : '-';
            
            baris.push([
                namaStaff,
                tna.seminar_workshop_webinar || '',
                tna.pelatihan || '',
                tna.pendidikan_lanjutan || '',
                tanggal
            ]);
        });

        let kontenCSV = "data:text/csv;charset=utf-8," + baris.map(e => e.join(",")).join("\n");
        var encodedUri = encodeURI(kontenCSV);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "rekap_tna_pendidikan_pelatihan.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        alert('Export Excel berhasil!');

    } catch (error) {
        console.error('Gagal export ke Excel:', error);
        alert('Gagal export data ke Excel: ' + error.message);
    } finally {
        sembunyikanLoading();
    }
};

window.exportKePDF = async function() {
    tampilkanLoading();
    alert('Fitur export PDF belum tersedia.');
    sembunyikanLoading();
};

// Fungsi toggle menu mobile
window.toggleMenuMobile = function() {
    const sidebar = document.querySelector('aside.fixed');
    if (sidebar) {
        sidebar.classList.toggle('mobile-show');
    }
};

// Tutup menu mobile ketika klik di luar
document.addEventListener('click', function(event) {
    const sidebar = document.querySelector('aside.fixed');
    const tombolMobile = document.querySelector('.mobile-menu-btn');

    if (sidebar && tombolMobile) {
        if (!sidebar.contains(event.target) && !tombolMobile.contains(event.target)) {
            sidebar.classList.remove('mobile-show');
        }
    }
});
</script>
<body class="min-h-full bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 text-gray-800">
    <!-- Global auth vars (pakai sebelum JS lain) -->
    <script>
        window.authToken = "{{ session('token') }}";
        window.currentUser = {
            id: {{ Auth::user()->id }},
            department_id: {{ Auth::user()->department_id ?? 'null' }},
            hospital_id: {{ Auth::user()->hospital_id ?? 'null' }}
        };
    </script>
   

    <!-- Mobile menu btn -->
    <button class="mobile-menu-btn p-2 rounded-md bg-white shadow-md text-gray-600" onclick="window.toggleMobileMenu()">
        <i class="fas fa-bars"></i>
    </button>

    @include('components.sidebar-navbar')

    <!-- MAIN -->
    <div class="p-4 md:p-0 mt-8">
        <main class="md:pl-60 pr-5 flex-1 px-4 md:px-6 py-4 md:py-8 mt-0 md:mt-8">
            <!-- Hero -->
            <div class="glass-effect rounded-3xl p-6 md:p-8 mb-6 md:mb-8 shadow-xl">
                <h1 class="text-3xl md:text-4xl font-bold text-black mb-3">
                    <i class="fas fa-graduation-cap mr-3 text-green-500"></i>Training Need Assessment (TNA)
                </h1>
                <p class="text-gray-600 text-base md:text-lg">Catat seminar, pelatihan, dan pendidikan lanjutan staf sebagai dasar perencanaan pengembangan SDM.</p>
            </div>

            <!-- KPI cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
                <!-- Total Staff -->
                <div class="bg-white text-gray-700 p-4 md:p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs md:text-sm font-medium uppercase tracking-wider">Total Staff</p>
                            <p class="text-2xl md:text-3xl font-bold mt-1 md:mt-2" id="totalStaffCount">0</p>
                            <p class="text-xs text-gray-400 mt-1">Personil aktif</p>
                        </div>
                        <div class="bg-blue-50 p-2 md:p-3 rounded-full text-blue-500">
                            <i class="fas fa-users text-lg md:text-xl"></i>
                        </div>
                    </div>
                </div>
                <!-- Seminar/Workshop -->
                <div class="bg-white text-gray-700 p-4 md:p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs md:text-sm font-medium uppercase tracking-wider">Seminar/Workshop</p>
                            <p class="text-2xl md:text-3xl font-bold mt-1 md:mt-2" id="totalSeminarCount">0</p>
                            <p class="text-xs text-gray-400 mt-1">Kegiatan tahun ini</p>
                        </div>
                        <div class="bg-green-50 p-2 md:p-3 rounded-full text-green-500">
                            <i class="fas fa-chalkboard-teacher text-lg md:text-xl"></i>
                        </div>
                    </div>
                </div>
                <!-- Pelatihan -->
                <div class="bg-white text-gray-700 p-4 md:p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs md:text-sm font-medium uppercase tracking-wider">Pelatihan</p>
                            <p class="text-2xl md:text-3xl font-bold mt-1 md:mt-2" id="totalPelatihanCount">0</p>
                            <p class="text-xs text-gray-400 mt-1">Program terselesaikan</p>
                        </div>
                        <div class="bg-amber-50 p-2 md:p-3 rounded-full text-amber-500">
                            <i class="fas fa-medal text-lg md:text-xl"></i>
                        </div>
                    </div>
                </div>
                <!-- Pendidikan Lanjutan -->
                <div class="bg-white text-gray-700 p-4 md:p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs md:text-sm font-medium uppercase tracking-wider">Pendidikan Lanjutan</p>
                            <p class="text-2xl md:text-3xl font-bold mt-1 md:mt-2" id="totalPendidikanLanjutanCount">0</p>
                            <p class="text-xs text-gray-400 mt-1">Staf berkembang</p>
                        </div>
                        <div class="bg-purple-50 p-2 md:p-3 rounded-full text-purple-500">
                            <i class="fas fa-user-graduate text-lg md:text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="flex flex-wrap gap-2 md:gap-4 mb-6 md:mb-8">
                <button onclick="openAddTnaModal()" id="openAddTnaModalBtn" class="btn-green-tna btn-mobile">
                    <i class="fas fa-plus mr-2"></i>Tambah Data TNA
                </button>
                <button onclick="openAddStaffModal()" id="openAddStaffModalBtn" class="btn-blue-tna btn-mobile">
                    <i class="fas fa-user-plus mr-2"></i>Tambah Staff
                </button>
                <button onclick="exportToExcel()" class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-4 py-2 md:px-6 md:py-3 rounded-xl font-semibold shadow-lg card-hover flex items-center btn-mobile">
                    <i class="fas fa-download mr-2"></i>Export Excel
                </button>
                <button onclick="exportToPdf()" class="bg-gradient-to-r from-orange-400 to-red-500 hover:from-orange-500 hover:to-red-600 text-white px-4 py-2 md:px-6 md:py-3 rounded-xl font-semibold shadow-lg card-hover flex items-center btn-mobile">
                    <i class="fas fa-file-pdf mr-2"></i>Export PDF
                </button>
            </div>

            <!-- Manajemen Staff table -->
            <div class="glass-effect rounded-3xl shadow-xl overflow-hidden card-hover bg-white mb-6 md:mb-8">
                <div class="bg-white p-4 md:p-6">
                    <h2 class="text-xl md:text-2xl font-bold text-black mb-3">
                        <i class="fas fa-users-cog mr-3 text-blue-500"></i>Manajemen Staff
                    </h2>
                </div>
                <div class="p-2 md:p-6 overflow-x-auto">
                    <div class="min-w-full inline-block align-middle">
                        <table class="management-table min-w-full text-sm bg-white rounded-2xl shadow-md">
                            <thead>
                                <tr class="bg-[#f9fcfe] text-black">
                                    <th class="px-3 py-3 text-left font-semibold rounded-tl-xl">No</th>
                                    <th class="px-3 py-3 text-left font-semibold"><i class="fas fa-user mr-2 text-[#0CC0DF]"></i>Nama</th>
                                    <th class="px-3 py-3 text-left font-semibold"><i class="fas fa-briefcase mr-2 text-[#0CC0DF]"></i>Jabatan</th>
                                    <th class="px-3 py-3 text-left font-semibold"><i class="fas fa-hospital-alt mr-2 text-[#0CC0DF]"></i>Ruangan</th>
                                    <th class="px-3 py-3 text-left font-semibold"><i class="fas fa-info-circle mr-2 text-[#0CC0DF]"></i>Status</th>
                                    <th class="px-3 py-3 text-left font-semibold rounded-tr-xl"><i class="fas fa-cogs mr-2 text-[#0CC0DF]"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="staffTableBody" class="bg-white divide-y divide-gray-200 text-gray-800"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- REKAP PENDIDIKAN & PELATIHAN -->
            <div class="glass-effect rounded-3xl shadow-xl overflow-hidden card-hover bg-white">
                <div class="bg-white p-4 md:p-6">
                    <h2 class="text-xl md:text-2xl font-bold text-black mb-3">
                        <i class="fas fa-chalkboard-teacher mr-3 text-green-500"></i>Rekap Pendidikan &amp; Pelatihan Staf
                    </h2>
                </div>
                <div class="p-2 md:p-6 overflow-x-auto">
                    <div class="min-w-full inline-block align-middle">
                        <table class="tna-table min-w-full text-sm bg-white rounded-2xl shadow-md">
                            <thead>
                                <tr class="bg-[#f9fcfe] text-black">
                                    <th class="px-3 py-3 text-left font-semibold rounded-tl-xl"><i class="fas fa-user mr-2 text-[#0CC0DF]"></i>Nama</th>
                                    <th class="px-3 py-3 text-left font-semibold"><i class="fas fa-microphone mr-2 text-[#0CC0DF]"></i>Seminar / Workshop</th>
                                    <th class="px-3 py-3 text-left font-semibold"><i class="fas fa-dumbbell mr-2 text-[#0CC0DF]"></i>Pelatihan</th>
                                    <th class="px-3 py-3 text-left font-semibold"><i class="fas fa-graduation-cap mr-2 text-[#0CC0DF]"></i>Pendidikan Lanjutan</th>
                                    <th class="px-3 py-3 text-left font-semibold"><i class="fas fa-calendar-alt mr-2 text-[#0CC0DF]"></i>Tanggal</th>
                                    <th class="px-3 py-3 text-left font-semibold rounded-tr-xl"><i class="fas fa-cogs mr-2 text-[#0CC0DF]"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tnaRecordsTableBody" class="bg-white divide-y divide-gray-200 text-gray-800"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- STAFF MODAL -->
    <div id="staffModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg p-5 w-full max-w-md">
            <div class="flex justify-between items-center mb-3">
                <h3 id="staffModalTitle" class="text-base md:text-lg font-bold">Tambah Staff Baru</h3>
                <button onclick="closeStaffModal()" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times"></i></button>
            </div>
            <form id="staffForm">
                <input type="hidden" id="staffId">
                <input type="hidden" id="userId" name="user_id" value="{{ Auth::user()->id }}">
                <input type="hidden" id="staffDepartment" value="{{ Auth::user()->department_id ?? 'null' }}">
                <input type="hidden" name="hospital_id" value="{{ Auth::user()->hospital_id ?? 'null' }}">
                <div class="mb-3">
                    <label class="block text-gray-700 text-xs md:text-sm mb-1" for="staffFullName">Nama Lengkap</label>
                    <input type="text" id="staffFullName" class="w-full px-3 py-2 border rounded-lg text-xs md:text-sm" required>
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 text-xs md:text-sm mb-1" for="staffPosition">Jabatan</label>
                    <select id="staffPosition" class="w-full px-3 py-2 border rounded-lg text-xs md:text-sm" required>
                        <option value="">Pilih Jabatan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 text-xs md:text-sm mb-1" for="staffStatus">Status</label>
                    <select id="staffStatus" class="w-full px-3 py-2 border rounded-lg text-xs md:text-sm" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                        <option value="Cuti">Cuti</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeStaffModal()" class="px-3 py-1 rounded-lg text-xs md:text-sm text-gray-700 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-3 py-1 rounded-lg text-xs md:text-sm text-white bg-green-500 hover:bg-green-600">Simpan</button>
                    <button type="button" id="deleteStaffBtn" onclick="deleteStaff()" class="px-3 py-1 rounded-lg text-xs md:text-sm text-white bg-red-500 hover:bg-red-600 hidden">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <!-- TNA MODAL -->
    <div id="tnaModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg p-5 w-full max-w-md">
            <div class="flex justify-between items-center mb-3">
                <h3 id="tnaModalTitle" class="text-lg md:text-xl font-bold text-gray-800">Tambah Data TNA</h3>
                <button type="button" onclick="closeTnaModal()" class="text-gray-500 hover:text-gray-700 text-lg"><i class="fas fa-times"></i></button>
            </div>
            <form id="tnaForm">
                <input type="hidden" id="tnaId">
                <!-- Staff -->
                <div class="mb-3 md:mb-4">
                    <label for="tnaStaffName" class="block text-gray-700 text-xs md:text-sm font-medium mb-1 md:mb-2">Pilih Staff</label>
                    <select id="tnaStaffName" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 text-sm" required>
                        <option value="">Pilih Staff</option>
                    </select>
                </div>
                <!-- Tanggal -->
                <div class="mb-3 md:mb-4">
    <label for="tanggal" class="block text-gray-700 text-xs md:text-sm font-medium mb-1 md:mb-2">Tanggal</label>
   <input type="date" id="tanggal" name="tanggal"
       value="{{ date('Y-m-d') }}" 
       class="w-full px-3 py-2 border rounded-lg text-sm" required>
</div>

                <!-- Seminar / Workshop / Webinar -->
               <div class="mb-3 md:mb-4">
    <label for="seminarWorkshopWebinar" class="block text-gray-700 text-xs md:text-sm font-medium mb-1 md:mb-2">
        Seminar / Workshop / Webinar
    </label>
    <select id="seminarWorkshopWebinar" onchange="toggleNamaKegiatanInput()" 
        class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 text-sm">
        <option value="">Pilih Kegiatan</option>
        <option value="Seminar">Seminar</option>
        <option value="Workshop">Workshop</option>
        <option value="Webinar">Webinar</option>
        <option value="Pelatihan Internal">Pelatihan Internal</option>
        <option value="Pelatihan Eksternal">Pelatihan Eksternal</option>
    </select>
</div>

<!-- Input tambahan untuk nama kegiatan -->
<div id="namaKegiatanContainer" class="mb-3 md:mb-4 hidden">
    <label for="namaKegiatan" class="block text-gray-700 text-xs md:text-sm font-medium mb-1 md:mb-2">
        Masukkan Nama Kegiatan
    </label>
    <input type="text" id="namaKegiatan" name="namaKegiatan" placeholder="Contoh: Seminar Manajemen Risiko"
        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 text-sm">
</div>

<script>
    function toggleNamaKegiatanInput() {
        const select = document.getElementById('seminarWorkshopWebinar');
        const inputContainer = document.getElementById('namaKegiatanContainer');
        
        // Daftar opsi yang butuh input tambahan
        const perluInput = ['Seminar', 'Workshop', 'Webinar', 'Pelatihan Internal', 'Pelatihan Eksternal'];

        if (perluInput.includes(select.value)) {
            inputContainer.classList.remove('hidden');
        } else {
            inputContainer.classList.add('hidden');
        }
    }
</script>

                <!-- Pelatihan -->
                <div class="mb-3 md:mb-4">
                    <label for="pelatihan" class="block text-gray-700 text-xs md:text-sm font-medium mb-1 md:mb-2">Pelatihan</label>
                    <select id="pelatihan" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 text-sm">
                        <option value="">Pilih Jenis Pelatihan</option>
                        <option value="K3">Keselamatan &amp; Kesehatan Kerja (K3)</option>
                        <option value="BTCLS">BTCLS</option>
                        <option value="PONEK">PONEK</option>
                        <option value="Manajemen Keperawatan">Manajemen Keperawatan</option>
                        <option value="ICT">Pelatihan ICT</option>
                    </select>
                </div>
                <!-- Pendidikan Lanjutan -->
                <div class="mb-4 md:mb-6">
                    <label for="pendidikanLanjutan" class="block text-gray-700 text-xs md:text-sm font-medium mb-1 md:mb-2">Pendidikan Lanjutan</label>
                    <select id="pendidikanLanjutan" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 text-sm">
                        <option value="">Pilih Jenjang</option>
                        <option value="D3">D3 Keperawatan</option>
                        <option value="D4">D4 Keperawatan</option>
                        <option value="S1/S.Kep">S1 / S.Kep</option>
                        <option value="S2/F.Kep">S2 / F.Kep</option>
                        <option value="Ners Spesialis">Ners Spesialis / Spesialis Keperawatan</option>
                        <option value="Doktor">Doktor</option>
                    </select>
                </div>
                <!-- Buttons -->
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeTnaModal()" class="animated-button bg-gray-200 text-gray-800 px-4 py-2 md:px-6 md:py-3 rounded-lg md:rounded-xl font-semibold hover:bg-gray-300 transition duration-300 text-xs md:text-sm">Batal</button>
                    <button type="submit" class="animated-button bg-purple-600 text-white px-4 py-2 md:px-6 md:py-3 rounded-lg md:rounded-xl font-semibold hover:bg-purple-700 transition duration-300 text-xs md:text-sm">Simpan</button>
                    <button type="button" id="deleteTnaBtn" onclick="deleteTnaRecord()" class="animated-button bg-red-600 text-white px-4 py-2 md:px-6 md:py-3 rounded-lg md:rounded-xl font-semibold hover:bg-red-700 transition duration-300 text-xs md:text-sm hidden">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <!-- GLOBAL LOADING OVERLAY -->
    <div id="global-loading-overlay" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-[9999] hidden">
        <div class="flex flex-col items-center space-y-4">
            <div class="relative w-20 h-20">
                <div class="absolute inset-0 border-4 border-blue-400 border-t-blue-600 rounded-full animate-spin"></div>
                <div class="absolute inset-2 border-4 border-white border-t-white rounded-full animate-spin-reverse" style="animation-duration: 1.5s;"></div>
                <div class="absolute inset-4 border-4 border-purple-400 border-t-purple-600 rounded-full animate-spin" style="animation-duration: 2s;"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="fas fa-sync-alt text-white text-3xl animate-pulse"></i>
                </div>
            </div>
            <p class="text-white text-lg font-semibold animate-pulse">Loading Data...</p>
        </div>
    </div>
</body>
</html>
