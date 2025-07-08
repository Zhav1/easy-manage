// Global variables
let staffMembers = [];
let tnaRecords = [];
let positions = [];
let departments = [];

// --- Global Loading Functions ---
function showLoading() {
    const overlay = document.getElementById('global-loading-overlay');
    if (overlay) {
        overlay.classList.remove('hidden');
    }
}

function hideLoading() {
    const overlay = document.getElementById('global-loading-overlay');
    if (overlay) {
        overlay.classList.add('hidden');
    }
}
// --- End Global Loading Functions ---

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    loadInitialData();
    setupEventListeners();
});

// Load initial data from API
async function loadInitialData() {
    showLoading();
    try {
        const token = window.authToken;
        if (!token) {
            console.error('No authentication token found');
            return;
        }

        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        };

        const [staffResponse, tnaRecordsResponse, positionsResponse, departmentsResponse] = await Promise.all([
            fetch('/api/v1/staff', {headers}),
            fetch('/api/v1/training-needs', {headers}),
            fetch('/api/v1/positions', {headers}),
            fetch('/api/v1/departments', {headers}),
        ]);

        staffMembers = await staffResponse.json();
        tnaRecords = await tnaRecordsResponse.json();
        positions = await positionsResponse.json();
        departments = await departmentsResponse.json();

        updateTnaStaffDropdown();
        renderStaffTable();
        renderTnaRecordsTable();
        updateCardCounts();

    } catch (error) {
        console.error('Error loading initial data:', error);
        alert('Gagal memuat data awal. Silakan coba lagi.');
    } finally {
        hideLoading();
    }
}

// Setup form event listeners
function setupEventListeners() {
    // Staff Form
    const staffForm = document.getElementById('staffForm');
    if (staffForm) {
        staffForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            await handleStaffFormSubmit();
        });
    }

    // TNA Form
    const tnaForm = document.getElementById('tnaForm');
    if (tnaForm) {
        tnaForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            await handleTnaFormSubmit();
        });
    }

    // Modal close on outside click
    const staffModal = document.getElementById('staffModal');
    if (staffModal) {
        staffModal.addEventListener('click', function(e) {
            if (e.target === this) closeStaffModal();
        });
    }

    const tnaModal = document.getElementById('tnaModal');
    if (tnaModal) {
        tnaModal.addEventListener('click', function(e) {
            if (e.target === this) closeTnaModal();
        });
    }

    // Buttons
    const addStaffModalBtn = document.getElementById('openAddStaffModalBtn');
    if (addStaffModalBtn) {
        addStaffModalBtn.addEventListener('click', window.openAddStaffModal);
    }

    const addTnaModalBtn = document.getElementById('openAddTnaModalBtn');
    if (addTnaModalBtn) {
        addTnaModalBtn.addEventListener('click', window.openAddTnaModal);
    }
}

// --- Staff Modal Functions ---
window.openAddStaffModal = function() {
    document.getElementById('staffModalTitle').textContent = 'Tambah Staff Baru';
    document.getElementById('staffId').value = '';
    document.getElementById('staffFullName').value = '';
    
    const userIdInput = document.getElementById('userId');
    if (userIdInput) userIdInput.value = window.currentUser.id;
    
    const staffDepartmentInput = document.getElementById('staffDepartment');
    if (staffDepartmentInput) staffDepartmentInput.value = window.currentUser.department_id;
    
    const staffHospitalInput = document.getElementById('staffHospital');
    if (staffHospitalInput) staffHospitalInput.value = window.currentUser.hospital_id;

    document.getElementById('staffPosition').value = '';
    document.getElementById('staffStatus').value = 'Aktif';
    document.getElementById('deleteStaffBtn').classList.add('hidden');
    document.getElementById('staffModal').classList.remove('hidden');
    document.getElementById('staffModal').classList.add('flex');
    updateStaffPositionDropdown();
}

window.openEditStaffModal = function(staffId) {
    const staff = staffMembers.find(s => s.id == staffId);
    if (!staff) return;

    document.getElementById('staffModalTitle').textContent = 'Edit Staff';
    document.getElementById('staffId').value = staff.id;
    document.getElementById('staffFullName').value = staff.name;
    document.getElementById('staffPosition').value = staff.position_id;
    document.getElementById('staffStatus').value = staff.status;
    document.getElementById('deleteStaffBtn').classList.remove('hidden');
    document.getElementById('staffModal').classList.remove('hidden');
    document.getElementById('staffModal').classList.add('flex');
    updateStaffPositionDropdown();
}

window.closeStaffModal = function() {
    document.getElementById('staffModal').classList.add('hidden');
    document.getElementById('staffModal').classList.remove('flex');
}

// --- TNA Modal Functions ---
window.openAddTnaModal = function() {
    document.getElementById('tnaModalTitle').textContent = 'Tambah Data TNA';
    document.getElementById('tnaId').value = '';
    document.getElementById('tnaStaffName').value = '';
    
    // Set tanggal default ke hari ini
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tanggal').value = today;
    
    document.getElementById('seminarWorkshopWebinar').value = '';
    document.getElementById('pelatihan').value = '';
    document.getElementById('pendidikanLanjutan').value = '';
    document.getElementById('deleteTnaBtn').classList.add('hidden');
    document.getElementById('tnaModal').classList.remove('hidden');
    document.getElementById('tnaModal').classList.add('flex');
    updateTnaStaffDropdown();
}

window.openEditTnaModal = function(tnaId) {
    const tna = tnaRecords.find(t => t.id == tnaId);
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
    updateTnaStaffDropdown();
}

window.closeTnaModal = function() {
    document.getElementById('tnaModal').classList.add('hidden');
    document.getElementById('tnaModal').classList.remove('flex');
}

// --- Form Handlers ---
async function handleStaffFormSubmit() {
    showLoading();
    const formData = {
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

        const url = formData.id ? `/api/v1/staff/${formData.id}` : '/api/v1/staff';
        const method = formData.id ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method: method,
            headers,
            body: JSON.stringify(formData)
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Gagal menyimpan data');
        }

        await loadInitialData();
        closeStaffModal();
        alert('Data staff berhasil disimpan!');
    } catch (error) {
        console.error('Error saving staff:', error);
        alert('Gagal menyimpan data staff: ' + error.message);
    } finally {
        hideLoading();
    }
}

async function handleTnaFormSubmit() {
    showLoading();
    
    // Validasi tanggal
    const tanggalInput = document.getElementById('tanggal');
    if (!tanggalInput.value) {
        alert('Harap isi tanggal terlebih dahulu!');
        hideLoading();
        return;
    }

    const formData = {
        id: document.getElementById('tnaId').value,
        staff_id: document.getElementById('tnaStaffName').value,
        tanggal: tanggalInput.value,
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

        const url = formData.id ? `/api/v1/training-needs/${formData.id}` : '/api/v1/training-needs';
        const method = formData.id ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method: method,
            headers,
            body: JSON.stringify(formData)
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Gagal menyimpan data');
        }

        await loadInitialData();
        closeTnaModal();
        alert('Data TNA berhasil disimpan!');
    } catch (error) {
        console.error('Error saving TNA record:', error);
        alert('Gagal menyimpan data TNA: ' + error.message);
    } finally {
        hideLoading();
    }
}

// --- Delete Functions ---
window.deleteStaff = async function() {
    const staffId = document.getElementById('staffId').value;
    if (!staffId || !confirm('Apakah Anda yakin ingin menghapus staff ini? Semua data TNA terkait juga akan dihapus.')) return;
    
    showLoading();
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

        await loadInitialData();
        closeStaffModal();
        alert('Data staff berhasil dihapus!');
    } catch (error) {
        console.error('Error deleting staff:', error);
        alert('Gagal menghapus staff: ' + error.message);
    } finally {
        hideLoading();
    }
}

window.deleteTnaRecord = async function() {
    const tnaId = document.getElementById('tnaId').value;
    if (!tnaId || !confirm('Apakah Anda yakin ingin menghapus data TNA ini?')) return;

    showLoading();
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

        await loadInitialData();
        closeTnaModal();
        alert('Data TNA berhasil dihapus!');
    } catch (error) {
        console.error('Error deleting TNA record:', error);
        alert('Gagal menghapus data TNA: ' + error.message);
    } finally {
        hideLoading();
    }
}

// --- Render Functions ---
function renderStaffTable() {
    const tbody = document.getElementById('staffTableBody');
    if (!tbody) {
        console.error('Staff table body element not found!');
        return;
    }

    tbody.innerHTML = '';

    if (!staffMembers || staffMembers.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = `<td colspan="6" class="text-center py-4">Tidak ada data staff</td>`;
        tbody.appendChild(row);
        return;
    }

    staffMembers.forEach((staff, index) => {
        const department = departments.find(d => d.id === staff.department_id) || { name: '-' };
        const position = positions.find(p => p.id === staff.position_id) || { name: '-' };

        const row = document.createElement('tr');
        row.classList.add('hover:bg-white', 'transition-all', 'duration-300');
        row.innerHTML = `
            <td class="px-6 py-4">${index + 1}</td>
            <td class="px-6 py-4 flex items-center">
                <div class="w-10 h-10 bg-[#0CC0DF] rounded-full flex items-center justify-center text-white font-bold mr-3">${staff.name.charAt(0).toUpperCase()}</div>
                ${staff.name}
            </td>
            <td class="px-6 py-4">${position.name}</td>
            <td class="px-6 py-4">${department.name}</td>
            <td class="px-6 py-4">
                <span class="px-2 py-1 rounded-full text-xs ${
                    staff.status === 'Aktif' ? 'bg-green-100 text-green-800' :
                    staff.status === 'Cuti' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'
                }">
                    ${staff.status}
                </span>
            </td>
            <td class="px-6 py-4 flex space-x-2">
                <button onclick="openEditStaffModal(${staff.id})" class="bg-white hover:bg-gray-100 text-black px-4 py-2 rounded-lg text-xs font-medium transition-all duration-300 flex items-center border border-[#0CC0DF] mr-2">
                    <i class="fas fa-pen mr-1 text-[#0CC0DF]"></i>Edit
                </button>
                <button onclick="deleteStaffConfirmation(${staff.id})" class="bg-white hover:bg-gray-100 text-black px-4 py-2 rounded-lg text-xs font-medium transition-all duration-300 flex items-center border border-red-500">
                    <i class="fas fa-trash mr-1 text-red-500"></i>Hapus
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function renderTnaRecordsTable() {
    const tbody = document.getElementById('tnaRecordsTableBody');
    if (!tbody) {
        console.error('TNA Records table body element not found!');
        return;
    }

    tbody.innerHTML = '';

    if (!tnaRecords || tnaRecords.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = `<td colspan="6" class="text-center py-4">Tidak ada data pendidikan & pelatihan</td>`;
        tbody.appendChild(row);
        return;
    }

    tnaRecords.forEach(tna => {
        const staff = staffMembers.find(s => s.id === tna.staff_id);
        const staffName = staff ? staff.name : 'N/A';
        
        // Format tanggal untuk ditampilkan
        const tanggal = tna.tanggal ? new Date(tna.tanggal).toLocaleDateString('id-ID') : '-';

        const row = document.createElement('tr');
        row.classList.add('hover:bg-white', 'transition-all', 'duration-300');
        row.innerHTML = `
            <td class="px-4 py-4 flex items-center h-full">
                <div class="w-10 h-10 bg-[#0CC0DF] rounded-full flex items-center justify-center text-white font-bold mr-3">${staffName.charAt(0).toUpperCase()}</div>
                <div>${staffName}</div>
            </td>
            <td class="px-4 py-4">${tna.seminar_workshop_webinar || 'Belum Ada'}</td>
            <td class="px-4 py-4">${tna.pelatihan || 'Belum Ada'}</td>
            <td class="px-4 py-4">${tna.pendidikan_lanjutan || 'Belum Ada'}</td>
            <td class="px-4 py-4">${tanggal}</td>
            <td class="px-4 py-4 flex flex space-x-2">
                <button onclick="openEditTnaModal(${tna.id})" class="bg-white hover:bg-gray-100 text-black px-4 py-2 rounded-lg text-xs font-medium transition-all duration-300 flex items-center border border-[#0CC0DF] mr-2">
                    <i class="fas fa-pen mr-1 text-[#0CC0DF]"></i>Edit
                </button>
                <button onclick="deleteTnaRecordConfirmation(${tna.id})" class="bg-white hover:bg-gray-100 text-black px-4 py-2 rounded-lg text-xs font-medium transition-all duration-300 flex items-center border border-red-500">
                    <i class="fas fa-trash mr-1 text-red-500"></i>Hapus
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function updateTnaStaffDropdown() {
    const staffSelect = document.getElementById('tnaStaffName');
    if (!staffSelect) {
        console.warn('Element #tnaStaffName not found!');
        return;
    }

    staffSelect.innerHTML = '<option value="">Pilih Staff</option>';
    staffMembers.forEach(staff => {
        const option = document.createElement('option');
        option.value = staff.id;
        option.textContent = staff.name;
        staffSelect.appendChild(option);
    });
}

function updateStaffPositionDropdown() {
    const posSelect = document.getElementById('staffPosition');
    if (!posSelect) {
        console.warn('Element #staffPosition not found!');
        return;
    }

    posSelect.innerHTML = '<option value="">Pilih Jabatan</option>';

    if (!positions || !Array.isArray(positions)) {
        console.error('Positions data is invalid:', positions);
        return;
    }

    positions.forEach(pos => {
        if (!pos.id || !pos.name) {
            console.warn('Invalid position data:', pos);
            return;
        }
        const option = document.createElement('option');
        option.value = pos.id;
        option.textContent = pos.name;
        posSelect.appendChild(option);
    });
}

function updateCardCounts() {
    const totalStaffCountElem = document.getElementById('totalStaffCount');
    if (totalStaffCountElem) { totalStaffCountElem.textContent = staffMembers.length; }

    let totalSeminar = 0;
    let totalPelatihan = 0;
    let totalPendidikanLanjutan = 0;

    tnaRecords.forEach(tna => {
        if (tna.seminar_workshop_webinar && tna.seminar_workshop_webinar !== '') totalSeminar++;
        if (tna.pelatihan && tna.pelatihan !== '') totalPelatihan++;
        if (tna.pendidikan_lanjutan && tna.pendidikan_lanjutan !== '') totalPendidikanLanjutan++;
    });

    const totalSeminarCountElem = document.getElementById('totalSeminarCount');
    if (totalSeminarCountElem) { totalSeminarCountElem.textContent = totalSeminar; }
    const totalPelatihanCountElem = document.getElementById('totalPelatihanCount');
    if (totalPelatihanCountElem) { totalPelatihanCountElem.textContent = totalPelatihan; }
    const totalPendidikanLanjutanCountElem = document.getElementById('totalPendidikanLanjutanCount');
    if (totalPendidikanLanjutanCountElem) { totalPendidikanLanjutanCountElem.textContent = totalPendidikanLanjutan; }
}

// Confirmation Dialogs
window.deleteStaffConfirmation = function(staffId) {
    if (confirm('Apakah Anda yakin ingin menghapus staff ini? Data TNA terkait juga akan terhapus.')) {
        document.getElementById('staffId').value = staffId;
        window.deleteStaff();
    }
};

window.deleteTnaRecordConfirmation = function(tnaId) {
    if (confirm('Apakah Anda yakin ingin menghapus data TNA ini?')) {
        document.getElementById('tnaId').value = tnaId;
        window.deleteTnaRecord();
    }
};

// --- Export Functions ---
window.exportToExcel = async function() {
    showLoading();
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
        const rows = [
            ['Nama Staff', 'Seminar/Workshop/Webinar', 'Pelatihan', 'Pendidikan Lanjutan', 'Tanggal']
        ];
        
        data.forEach(tna => {
            const staff = staffMembers.find(s => s.id === tna.staff_id);
            const staffName = staff ? staff.name : 'N/A';
            const tanggal = tna.tanggal ? new Date(tna.tanggal).toLocaleDateString('id-ID') : '-';
            
            rows.push([
                staffName,
                tna.seminar_workshop_webinar || '',
                tna.pelatihan || '',
                tna.pendidikan_lanjutan || '',
                tanggal
            ]);
        });

        let csvContent = "data:text/csv;charset=utf-8," + rows.map(e => e.join(",")).join("\n");
        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "rekap_tna_pendidikan_pelatihan.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        alert('Export Excel berhasil!');

    } catch (error) {
        console.error('Error exporting to Excel:', error);
        alert('Gagal export data ke Excel: ' + error.message);
    } finally {
        hideLoading();
    }
};

window.exportToPdf = async function() {
    showLoading();
    alert('Fitur export PDF belum tersedia.');
    hideLoading();
};

// Mobile menu toggle function
window.toggleMobileMenu = function() {
    const sidebar = document.querySelector('aside.fixed');
    if (sidebar) {
        sidebar.classList.toggle('mobile-show');
    }
};

// Close mobile menu when clicking outside
document.addEventListener('click', function(event) {
    const sidebar = document.querySelector('aside.fixed');
    const mobileBtn = document.querySelector('.mobile-menu-btn');

    if (sidebar && mobileBtn) {
        if (!sidebar.contains(event.target) && !mobileBtn.contains(event.target)) {
            sidebar.classList.remove('mobile-show');
        }
    }
});