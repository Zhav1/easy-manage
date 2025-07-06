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
    setupEventListeners(); // Call event listener setup after initial data load
});

// Load initial data from API
async function loadInitialData() {
    showLoading(); // Show loading before initial data fetch
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
            fetch('/api/v1/staff', {headers}), // Fetch from your existing /api/v1/staff endpoint
            fetch('/api/v1/training-needs', {headers}),
            fetch('/api/v1/positions', {headers}),
            fetch('/api/v1/departments', {headers}),
        ]);

        staffMembers = await staffResponse.json();
        tnaRecords = await tnaRecordsResponse.json();
        positions = await positionsResponse.json();
        departments = await departmentsResponse.json();

        // Populate dropdowns and render tables only after data is available
        updateTnaStaffDropdown();
        // updateStaffPositionDropdown() is called when Staff Modal is opened now
        renderStaffTable();
        renderTnaRecordsTable();
        updateCardCounts();

    } catch (error) {
        console.error('Error loading initial data:', error);
        alert('Failed to load initial data. Please try again.');
    } finally {
        hideLoading(); // Always hide loading
    }
}

// Setup form event listeners
function setupEventListeners() {
    // Add event listeners only if elements exist and are found after DOM content is loaded
    
    // Staff Form (in Staff Management Modal)
    const staffForm = document.getElementById('staffForm');
    if (staffForm) {
        staffForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            await handleStaffFormSubmit();
        });
    } else {
        console.warn('Element #staffForm not found for event listener setup.');
    }

    // TNA Form (in TNA Modal)
    const tnaForm = document.getElementById('tnaForm');
    if (tnaForm) {
        tnaForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            await handleTnaFormSubmit();
        });
    } else {
        console.warn('Element #tnaForm not found for event listener setup.');
    }

    // Modal close on outside click (using event delegation for reliability if modals are dynamic)
    const staffModal = document.getElementById('staffModal');
    if (staffModal) {
        staffModal.addEventListener('click', function(e) {
            if (e.target === this) closeStaffModal();
        });
    } else {
        console.warn('Element #staffModal not found for event listener setup.');
    }

    const tnaModal = document.getElementById('tnaModal');
    if (tnaModal) {
        tnaModal.addEventListener('click', function(e) {
            if (e.target === this) closeTnaModal();
        });
    } else {
        console.warn('Element #tnaModal not found for event listener setup.');
    }

    // Buttons (add event listeners to specific IDs in main page content)
    const addStaffModalBtn = document.getElementById('openAddStaffModalBtn'); // Corrected ID
    if (addStaffModalBtn) {
        addStaffModalBtn.addEventListener('click', window.openAddStaffModal);
    } else {
        console.warn('Element #openAddStaffModalBtn not found for event listener setup.');
    }

    const addTnaModalBtn = document.getElementById('openAddTnaModalBtn'); // Corrected ID
    if (addTnaModalBtn) {
        addTnaModalBtn.addEventListener('click', window.openAddTnaModal);
    } else {
        console.warn('Element #openAddTnaModalBtn not found for event listener setup.');
    }

    // Delete buttons now have onclick on the element directly
    // This removes the need for window.confirmDeleteStaff / deleteTnaRecordConfirmation in setupEventListeners
    // as they are called from the onclick attribute in HTML directly.
}

// --- Staff Modal Functions (reusing your existing staff modal structure) ---
window.openAddStaffModal = function() {
    document.getElementById('staffModalTitle').textContent = 'Tambah Staff Baru';
    document.getElementById('staffId').value = '';
    document.getElementById('staffFullName').value = '';
    // Ensure these are correctly populated from currentUser global variable
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
    updateStaffPositionDropdown(); // Populate position dropdown when modal opens
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
    updateStaffPositionDropdown(); // Populate position dropdown when modal opens
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
    document.getElementById('seminarWorkshopWebinar').value = '';
    document.getElementById('pelatihan').value = '';
    document.getElementById('pendidikanLanjutan').value = '';
    document.getElementById('deleteTnaBtn').classList.add('hidden');
    document.getElementById('tnaModal').classList.remove('hidden');
    document.getElementById('tnaModal').classList.add('flex');
    updateTnaStaffDropdown(); // Populate staff dropdown when TNA modal opens
}

window.openEditTnaModal = function(tnaId) {
    const tna = tnaRecords.find(t => t.id == tnaId);
    if (!tna) return;

    document.getElementById('tnaModalTitle').textContent = 'Edit Data TNA';
    document.getElementById('tnaId').value = tna.id;
    document.getElementById('tnaStaffName').value = tna.staff_id;
    document.getElementById('seminarWorkshopWebinar').value = tna.seminar_workshop_webinar || '';
    document.getElementById('pelatihan').value = tna.pelatihan || '';
    document.getElementById('pendidikanLanjutan').value = tna.pendidikan_lanjutan || '';
    document.getElementById('deleteTnaBtn').classList.remove('hidden');
    document.getElementById('tnaModal').classList.remove('hidden');
    document.getElementById('tnaModal').classList.add('flex');
    updateTnaStaffDropdown(); // Populate staff dropdown when TNA modal opens
}

window.closeTnaModal = function() {
    document.getElementById('tnaModal').classList.add('hidden');
    document.getElementById('tnaModal').classList.remove('flex');
}

// --- Form Handlers ---
async function handleStaffFormSubmit() {
    showLoading(); // Show loading
    const formData = {
        id: document.getElementById('staffId').value,
        name: document.getElementById('staffFullName').value,
        position_id: document.getElementById('staffPosition').value,
        user_id: window.currentUser.id, // Using global window.currentUser
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
            throw new Error(errorData.message || 'Network response was not ok');
        }

        await loadInitialData(); // Refresh data
        closeStaffModal();
        alert('Data staff berhasil disimpan!');
    } catch (error) {
        console.error('Error saving staff:', error);
        alert('Gagal menyimpan data staff: ' + error.message);
    } finally {
        hideLoading(); // Hide loading
    }
}

async function handleTnaFormSubmit() {
    showLoading(); // Show loading
    const formData = {
        id: document.getElementById('tnaId').value,
        staff_id: document.getElementById('tnaStaffName').value,
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
            throw new Error(errorData.message || 'Network response was not ok');
        }

        await loadInitialData(); // Refresh data
        closeTnaModal();
        alert('Data TNA berhasil disimpan!');
    } catch (error) {
        console.error('Error saving TNA record:', error);
        alert('Gagal menyimpan data TNA: ' + error.message);
    } finally {
        hideLoading(); // Hide loading
    }
}

// --- Delete Functions ---
window.deleteStaff = async function() {
    const staffId = document.getElementById('staffId').value;
    if (!staffId || !confirm('Apakah Anda yakin ingin menghapus staff ini? Semua data TNA terkait juga akan dihapus.')) return;
    
    showLoading(); // Show loading
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
            throw new Error(errorData.message || 'Network response was not ok');
        }

        await loadInitialData(); // Refresh data
        closeStaffModal();
        alert('Data staff berhasil dihapus!');
    } catch (error) {
        console.error('Error deleting staff:', error);
        alert('Gagal menghapus staff: ' + error.message);
    } finally {
        hideLoading(); // Hide loading
    }
}

window.deleteTnaRecord = async function() {
    const tnaId = document.getElementById('tnaId').value;
    if (!tnaId || !confirm('Apakah Anda yakin ingin menghapus data TNA ini?')) return;

    showLoading(); // Show loading
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
            throw new Error(errorData.message || 'Network response was not ok');
        }

        await loadInitialData(); // Refresh data
        closeTnaModal();
        alert('Data TNA berhasil dihapus!');
    } catch (error) {
        console.error('Error deleting TNA record:', error);
        alert('Gagal menghapus data TNA: ' + error.message);
    } finally {
        hideLoading(); // Hide loading
    }
}

// --- Render Functions ---
function renderStaffTable() {
    const tbody = document.getElementById('staffTableBody');
    if (!tbody) {
        console.error('Staff table body element not found!');
        return;
    }

    tbody.innerHTML = ''; // Clear existing rows

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

    tbody.innerHTML = ''; // Clear existing rows

    if (!tnaRecords || tnaRecords.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = `<td colspan="5" class="text-center py-4">Tidak ada data pendidikan & pelatihan</td>`;
        tbody.appendChild(row);
        return;
    }

    tnaRecords.forEach(tna => {
        const staff = staffMembers.find(s => s.id === tna.staff_id);
        const staffName = staff ? staff.name : 'N/A';

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
    if (confirm('Are you sure you want to delete this staff member? This will also delete any associated TNA records.')) {
        document.getElementById('staffId').value = staffId; // Set ID for deletion
        window.deleteStaff(); // Call the actual delete function
    }
};

window.deleteTnaRecordConfirmation = function(tnaId) {
    if (confirm('Are you sure you want to delete this TNA record?')) {
        document.getElementById('tnaId').value = tnaId; // Set ID for deletion
        window.deleteTnaRecord(); // Call the actual delete function
    }
};

// --- Export Functions ---
window.exportToExcel = async function() {
    showLoading(); // Show loading for export
    try {
        const token = window.authToken;
        if (!token) {
            alert('Authentication token missing.');
            return;
        }

        const response = await fetch('/api/v1/training-needs', { // Fetch all TNA data
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        if (!response.ok) throw new Error('Failed to fetch TNA data for export.');

        const data = await response.json();

        // Prepare data for Excel (CSV in this basic implementation)
        const rows = [
            ['Nama Staff', 'Seminar / Workshop / Webinar', 'Pelatihan', 'Pendidikan Lanjutan']
        ];
        data.forEach(tna => {
            const staff = staffMembers.find(s => s.id === tna.staff_id);
            const staffName = staff ? staff.name : 'N/A';
            rows.push([
                staffName,
                tna.seminar_workshop_webinar || '',
                tna.pelatihan || '',
                tna.pendidikan_lanjutan || ''
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
        alert('Failed to export data to Excel.');
    } finally {
        hideLoading(); // Hide loading
    }
};

window.exportToPdf = async function() {
    showLoading(); // Show loading for export
    alert('Export PDF functionality is not yet implemented.');
    // For PDF export, you'd typically use a server-side solution or a more robust client-side library like jsPDF.
    hideLoading(); // Hide loading even if just an alert
};

// Mobile menu toggle function (from your Blade, made global)
window.toggleMobileMenu = function() {
    const sidebar = document.querySelector('aside.fixed'); // Select by tag if no ID
    if (sidebar) {
        sidebar.classList.toggle('mobile-show');
    } else {
        console.warn('Sidebar element not found for mobile menu toggle!');
    }
};

// Close mobile menu when clicking outside (using event delegation for robustness)
document.addEventListener('click', function(event) {
    const sidebar = document.querySelector('aside.fixed'); // Select sidebar
    const mobileBtn = document.querySelector('.mobile-menu-btn'); // Select mobile menu button

    // Only run if both elements exist
    if (sidebar && mobileBtn) {
        // If the click is outside the sidebar AND outside the mobile button
        if (!sidebar.contains(event.target) && !mobileBtn.contains(event.target)) {
            sidebar.classList.remove('mobile-show');
        }
    }
});