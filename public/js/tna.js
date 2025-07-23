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
async function muatDataAwal(forceRefresh = false) {
    tampilkanLoading();

    const cacheKeys = {
        staff: 'prefetched_dinas_staff',
        tna: 'prefetched_tna_records',
        positions: 'prefetched_dinas_positions',
        departments: 'prefetched_dinas_departments'
    };

    const cachedStaff = sessionStorage.getItem(cacheKeys.staff);
    const cachedTNA = sessionStorage.getItem(cacheKeys.tna);
    const cachedPositions = sessionStorage.getItem(cacheKeys.positions);
    const cachedDepartments = sessionStorage.getItem(cacheKeys.departments);

    if (cachedStaff && cachedTNA && cachedPositions && cachedDepartments && !forceRefresh) {
        console.log('⚡️ Memuat data TNA dari cache.');
        try {
            daftarStaff = JSON.parse(cachedStaff);
            catatanTNA = JSON.parse(cachedTNA);
            daftarJabatan = JSON.parse(cachedPositions);
            daftarDepartemen = JSON.parse(cachedDepartments);

            // Render UI immediately from cache
            perbaruiDropdownStaffTNA();
            renderTabelStaff();
            renderTabelTNA();
            perbaruiJumlahKartu();
            sembunyikanLoading();
            return;
        } catch (e) {
            console.error("Gagal mem-parsing data TNA dari cache, mengambil dari API.", e);
        }
    }

    if (forceRefresh) console.log('🔄 Memaksa pembaruan data TNA dari API...');
    else console.log('Cache tidak ditemukan. Mengambil data TNA dari API...');

    try {
        const token = window.authToken;
        if (!token) throw new Error('Token autentikasi tidak ditemukan');

        const headers = {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        };

        const [responStaff, responTNA, responJabatan, responDepartemen] = await Promise.all([
            fetch('/api/v1/staff', { headers }),
            fetch('/api/v1/training-needs', { headers }),
            fetch('/api/v1/positions', { headers }),
            fetch('/api/v1/departments', { headers }),
        ]);

        daftarStaff = await responStaff.json();
        catatanTNA = await responTNA.json();
        daftarJabatan = await responJabatan.json();
        daftarDepartemen = await responDepartemen.json();

        // **NEW**: Cache the freshly fetched data
        sessionStorage.setItem(cacheKeys.staff, JSON.stringify(daftarStaff));
        sessionStorage.setItem(cacheKeys.tna, JSON.stringify(catatanTNA));
        sessionStorage.setItem(cacheKeys.positions, JSON.stringify(daftarJabatan));
        sessionStorage.setItem(cacheKeys.departments, JSON.stringify(daftarDepartemen));
        console.log('✅ Data TNA berhasil disimpan di cache.');

        // Render UI with fresh data
        perbaruiDropdownStaffTNA();
        renderTabelStaff();
        renderTabelTNA();
        perbaruiJumlahKartu();

    } catch (error) {
        console.error('Gagal memuat data awal:', error);
        showToast('Gagal memuat data awal. Silakan coba lagi.', 'error');
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

        await muatDataAwal(true);
        tutupModalStaff();
        showToast('Data staff berhasil disimpan!', 'success');
    } catch (error) {
        console.error('Gagal menyimpan staff:', error);
        showToast('Gagal menyimpan data staff: ' + error.message, 'error');
    } finally {
        sembunyikanLoading();
    }
}

async function handleSubmitFormTNA() {
    tampilkanLoading();

    const inputTanggal = document.getElementById('tanggal');
    const selectStaff = document.getElementById('tnaStaffName');

    // --- REPLACEMENT for alert() ---
    if (!inputTanggal.value) {
        showToast('Harap isi tanggal terlebih dahulu!', 'error');
        inputTanggal.focus();
        sembunyikanLoading();
        return false;
    }

    // --- REPLACEMENT for alert() ---
    if (!selectStaff.value) {
        showToast('Harap pilih staff terlebih dahulu!', 'error');
        selectStaff.focus();
        sembunyikanLoading();
        return false;
    }

    const seminarWorkshopWebinarValue = document.getElementById('seminarWorkshopWebinar').value;
    const namaKegiatanValue = document.getElementById('namaKegiatan').value;
    let finalSeminarWorkshopWebinar = seminarWorkshopWebinarValue;
    const perluInput = ['Seminar', 'Workshop', 'Webinar', 'Pelatihan Internal', 'Pelatihan Eksternal'];
    if (perluInput.includes(seminarWorkshopWebinarValue) && namaKegiatanValue) {
        finalSeminarWorkshopWebinar = `${seminarWorkshopWebinarValue}: ${namaKegiatanValue}`;
    }

    const dataForm = {
        id: document.getElementById('tnaId').value,
        staff_id: selectStaff.value,
        tanggal: inputTanggal.value,
        seminar_workshop_webinar: finalSeminarWorkshopWebinar,
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
            const pesanError = errorData.message || errorData.errors?.join(', ') || 'Gagal menyimpan data';
            throw new Error(pesanError);
        }

        await muatDataAwal(true);
        tutupModalTNA();
        // --- REPLACEMENT for alert() ---
        showToast('Data TNA berhasil disimpan!', 'success');
    } catch (error) {
        console.error('Gagal menyimpan TNA:', error);
        // --- REPLACEMENT for alert() ---
        showToast('Gagal menyimpan data TNA: ' + error.message, 'error');
    } finally {
        sembunyikanLoading();
    }
    return true;
}
// --- Fungsi Hapus Data ---
async function hapusStaff(staffId) {
    const staff = daftarStaff.find(s => s.id === staffId);
    if (!staff) {
        showToast('Staff tidak ditemukan.', 'error');
        return;
    }

    const isConfirmed = await showConfirmationModal({
        title: 'Konfirmasi Hapus Staff',
        message: `Anda akan menghapus staff: <strong>${staff.name}</strong>. Semua data TNA terkait juga akan dihapus. <br><br> Tindakan ini tidak dapat dibatalkan.`
    });

    if (!isConfirmed) return;

    tampilkanLoading();
    try {
        const token = window.authToken;
        const headers = {
            'Accept': 'application/json',
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

        await muatDataAwal(true); // Refresh data from API
        tutupModalStaff(); // Close modal if it was open
        showToast('Data staff berhasil dihapus!', 'success');
    } catch (error) {
        console.error('Gagal menghapus staff:', error);
        showToast('Gagal menghapus staff: ' + error.message, 'error');
    } finally {
        sembunyikanLoading();
    }
}

// --- REPLACEMENT for the old window.hapusDataTNA ---
async function hapusDataTNA(tnaId) {
    const tnaRecord = catatanTNA.find(t => t.id === tnaId);
    if (!tnaRecord) {
        showToast('Data TNA tidak ditemukan.', 'error');
        return;
    }
    const staff = daftarStaff.find(s => s.id === tnaRecord.staff_id);
    const staffName = staff ? staff.name : 'data ini';
    const tanggal = tnaRecord.tanggal ? new Date(tnaRecord.tanggal).toLocaleDateString('id-ID') : 'N/A';

    // --- REPLACEMENT for confirm() ---
    const isConfirmed = await showConfirmationModal({
        title: 'Konfirmasi Hapus Data TNA',
        message: `Anda akan menghapus data TNA untuk staff <strong>${staffName}</strong> yang tercatat pada tanggal <strong>${tanggal}</strong>. <br><br> Tindakan ini tidak dapat dibatalkan.`
    });

    if (!isConfirmed) return;

    tampilkanLoading();
    try {
        const token = window.authToken;
        const headers = {
            'Accept': 'application/json',
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

        await muatDataAwal(true);
        tutupModalTNA();
        // --- REPLACEMENT for alert() ---
        showToast('Data TNA berhasil dihapus!', 'success');
    } catch (error) {
        console.error('Gagal menghapus TNA:', error);
        // --- REPLACEMENT for alert() ---
        showToast('Gagal menghapus TNA: ' + error.message, 'error');
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
                <button onclick="hapusStaff(${staff.id})" class="bg-white hover:bg-gray-100 text-black px-4 py-2 rounded-lg text-xs font-medium transition-all duration-300 flex items-center border border-red-500">
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
                <button onclick="hapusDataTNA(${tna.id})" class="bg-white hover:bg-gray-100 text-black px-4 py-2 rounded-lg text-xs font-medium transition-all duration-300 flex items-center border border-red-500">
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

// --- Fungsi Export ---
window.exportKeExcel = async function() {
    tampilkanLoading();
    try {
        const token = window.authToken;
        if (!token) {
            showToast('Token autentikasi tidak ditemukan.', 'error'); 
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
        showToast('Export Excel berhasil!', 'success');

    } catch (error) {
        console.error('Gagal export ke Excel:', error);
        showToast('Gagal export data ke Excel: ' + error.message, 'error');
    } finally {
        sembunyikanLoading();
    }
};

window.exportKePDF = async function() {
    tampilkanLoading();
    showToast('Fitur export PDF belum tersedia.', 'info'); 
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

// --- Fungsi Notifikasi Toast ---
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const template = document.getElementById('toast-template');
    if (!container || !template) return;

    const newToast = template.cloneNode(true);
    newToast.id = ''; // Remove template ID
    newToast.classList.remove('hidden');
    newToast.classList.add('flex');

    const iconDiv = newToast.querySelector('#toast-icon');
    const messageDiv = newToast.querySelector('#toast-message');
    messageDiv.textContent = message;

    if (type === 'success') {
        iconDiv.innerHTML = '<i class="fas fa-check"></i>';
        iconDiv.className = 'inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg';
    } else if (type === 'error') {
        iconDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
        iconDiv.className = 'inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg';
    } else { // Info
        iconDiv.innerHTML = '<i class="fas fa-info-circle"></i>';
        iconDiv.className = 'inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg';
    }

    container.appendChild(newToast);

    // Automatically remove the toast after 5 seconds
    setTimeout(() => {
        newToast.style.transition = 'opacity 0.5s ease';
        newToast.style.opacity = '0';
        setTimeout(() => newToast.remove(), 500);
    }, 5000);
}

// --- Fungsi Konfirmasi Modal ---
function showConfirmationModal({ title, message, confirmText = 'Ya, Hapus', cancelText = 'Batal' }) {
    const modal = document.getElementById('confirmationModal');
    const modalBox = document.getElementById('confirmationModalBox');
    const titleEl = document.getElementById('confirmationTitle');
    const messageEl = document.getElementById('confirmationMessage');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    const cancelBtn = document.getElementById('confirmCancelBtn');

    titleEl.textContent = title;
    messageEl.innerHTML = message; // Use innerHTML to allow for bolding, etc.
    confirmBtn.textContent = confirmText;
    cancelBtn.textContent = cancelText;

    modal.classList.remove('hidden');
    setTimeout(() => {
       modalBox.classList.remove('scale-95', 'opacity-0');
       modalBox.classList.add('scale-100', 'opacity-100');
    }, 10); // Small delay for transition to work

    return new Promise((resolve) => {
        confirmBtn.onclick = () => {
            modal.classList.add('hidden');
            modalBox.classList.add('scale-95', 'opacity-0');
            resolve(true);
        };

        cancelBtn.onclick = () => {
            modal.classList.add('hidden');
            modalBox.classList.add('scale-95', 'opacity-0');
            resolve(false);
        };

        modal.onclick = (e) => {
             if(e.target === modal) {
                modal.classList.add('hidden');
                modalBox.classList.add('scale-95', 'opacity-0');
                resolve(false);
             }
        }
    });
}