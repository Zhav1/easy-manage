// Global variables
let staffMembers = [];
let performanceEvaluations = [];
let positions = [];
let departments = []; // Ensure departments is declared globally

// --- Global Loading Functions (for HCI principle) ---
// (These are global so they can be called from anywhere in your scripts)
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

function showConfirmationModal({ title, message, confirmText = 'Ya, Hapus', cancelText = 'Batal' }) {
    // Get all the modal elements
    const modal = document.getElementById('myConfirmationModal');
    const modalBox = document.getElementById('confirmationModalBox'); // The inner box for animation
    const titleEl = document.getElementById('confirmationTitle');
    const messageEl = document.getElementById('confirmationMessage');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    const cancelBtn = document.getElementById('confirmCancelBtn');

    if (!modal || !modalBox || !titleEl || !messageEl || !confirmBtn || !cancelBtn) {
        console.error('One or more confirmation modal elements are missing from the HTML!');
        return Promise.resolve(false); // Can't proceed
    }

    // Set the text content
    titleEl.textContent = title;
    messageEl.innerHTML = message; // Use innerHTML to allow for bolding or other tags
    confirmBtn.textContent = confirmText;
    cancelBtn.textContent = cancelText;

    // Show the modal with animation
    modal.classList.add('modal-visible'); 
    setTimeout(() => { // A tiny delay allows the CSS transition to work
        modal.style.opacity = '1';
        modalBox.style.opacity = '1';
        modalBox.style.transform = 'scale(1)';
    }, 10);


    return new Promise((resolve) => {
        // Function to close the modal and resolve the promise
        const closeModal = (result) => {
            modal.style.opacity = '0';
            modalBox.style.opacity = '0';
            modalBox.style.transform = 'scale(0.95)';

            // Wait for the animation to finish before adding 'hidden'
            setTimeout(() => {
                modal.classList.remove('modal-visible');
                resolve(result);
            }, 300); // Match this timeout to your CSS transition duration
        };

        // Assign clean, new onclick handlers
        confirmBtn.onclick = () => closeModal(true);
        cancelBtn.onclick = () => closeModal(false);

        // Allow closing by clicking the background overlay
        modal.onclick = (event) => {
            if (event.target === modal) {
                closeModal(false);
            }
        };
    });
}

function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;
    const template = document.getElementById('toast-template');
    if (!template) return;

    const newToast = template.cloneNode(true);
    newToast.id = '';
    newToast.classList.remove('hidden');
    newToast.classList.add('flex');

    const iconDiv = newToast.querySelector('#toast-icon');
    const messageDiv = newToast.querySelector('#toast-message');
    iconDiv.innerHTML = '';
    messageDiv.textContent = message;

    if (type === 'success') {
        iconDiv.innerHTML = '<i class="fas fa-check"></i>';
        iconDiv.className = 'inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg';
    } else if (type === 'error') {
        iconDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
        iconDiv.className = 'inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg';
    }
    container.appendChild(newToast);

    setTimeout(() => {
        newToast.style.transition = 'opacity 0.5s ease';
        newToast.style.opacity = '0';
        setTimeout(() => newToast.remove(), 500);
    }, 5000);
}
// --- End Global Loading Functions ---


// Initialize the application
document.addEventListener('DOMContentLoaded', () => {
    // Initial data load
    loadInitialKinerjaStaffData();

    // Setup main event listener for all clicks on the page
    document.body.addEventListener('click', (event) => {
        const target = event.target.closest('button'); // Find the button that was clicked
        if (!target) return; // Exit if the click wasn't on a button

        // --- Handle Main Action Buttons ---
        if (target.id === 'addPenilaianBtn') {
            openAddPerformanceEvaluationModal();
        }
        if (target.id === 'addStaffBtn') {
            openAddStaffModal();
        }

        // --- Handle Table Row Buttons ---
        const staffId = target.dataset.staffId;
        const evaluationId = target.dataset.evaluationId;
        
        if (target.matches('[data-action="edit-staff"]')) {
            openEditStaffModal(staffId);
        }
        if (target.matches('[data-action="delete-staff"]')) {
            deleteStaffManagement(staffId);
        }
        if (target.matches('[data-action="edit-evaluation"]')) {
            openEditPerformanceEvaluationModal(evaluationId);
        }
        if (target.matches('[data-action="detail-evaluation"]')) {
            openDetailPerformanceEvaluationModal(evaluationId);
        }
        if (target.matches('[data-action="delete-evaluation"]')) {
            deletePerformanceEvaluation(evaluationId);
        }
    });

    // --- Setup Listeners for Modals and Filters ---
    document.getElementById('performanceEvaluationForm').addEventListener('submit', handlePerformanceEvaluationFormSubmit);
    document.getElementById('staffManagementForm').addEventListener('submit', handleStaffManagementFormSubmit);
    
    document.getElementById('rekaptitulasiSearchInput')?.addEventListener('input', filterPerformanceEvaluations);
    document.getElementById('rekaptitulasiFilterSelect')?.addEventListener('change', filterPerformanceEvaluations);

    // Add other non-button listeners here if needed
});

// Load initial data for Kinerja Staff page
async function loadInitialKinerjaStaffData(forceRefresh = false) {
    showLoading();

    // Group keys for easier management
    const cacheKeys = {
        staff: 'prefetched_dinas_staff', // Re-use from dinas page
        positions: 'prefetched_dinas_positions', // Re-use from dinas page
        departments: 'prefetched_dinas_departments', // Re-use from dinas page
        evaluations: 'prefetched_kinerja_evaluations'
    };

    const cachedStaff = sessionStorage.getItem(cacheKeys.staff);
    const cachedPositions = sessionStorage.getItem(cacheKeys.positions);
    const cachedDepartments = sessionStorage.getItem(cacheKeys.departments);
    const cachedEvaluations = sessionStorage.getItem(cacheKeys.evaluations);

    // Use cache if all data is available and not forcing a refresh
    if (cachedStaff && cachedPositions && cachedDepartments && cachedEvaluations && !forceRefresh) {
        console.log('⚡️ Loading Kinerja Staff data from cache.');
        try {
            staffMembers = JSON.parse(cachedStaff);
            positions = JSON.parse(cachedPositions);
            departments = JSON.parse(cachedDepartments);
            performanceEvaluations = JSON.parse(cachedEvaluations);

            // Render UI immediately from cache
            renderStaffManagementTable();
            renderPerformanceEvaluationTable();
            updateStaffDropdownForEvaluation();
            updatePositionDropdownForStaffManagement();
            updateKinerjaStatistics();
            hideLoading();
            return;
        } catch (e) {
            console.error("Failed to parse cached Kinerja data, fetching from API.", e);
        }
    }
    
    // Fallback: If no cache or forceRefresh is true, fetch from API
    if (forceRefresh) console.log('🔄 Forcing refresh of Kinerja Staff data...');
    else console.log('No cache found. Fetching Kinerja Staff data from API...');

    try {
        const token = window.authToken;
        if (!token) throw new Error('Bearer token missing');
        
        const headers = { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` };

        const [staffResponse, positionsResponse, departmentsResponse, evaluationsResponse] = await Promise.all([
            fetch('/api/v1/staff', { headers }),
            fetch('/api/v1/positions', { headers }),
            fetch('/api/v1/departments', { headers }),
            fetch('/api/v1/performance-evaluations', { headers })
        ]);

        staffMembers = await staffResponse.json();
        positions = await positionsResponse.json();
        departments = await departmentsResponse.json();
        performanceEvaluations = await evaluationsResponse.json();
        
        // **NEW**: Cache the freshly fetched data
        sessionStorage.setItem(cacheKeys.staff, JSON.stringify(staffMembers));
        sessionStorage.setItem(cacheKeys.positions, JSON.stringify(positions));
        sessionStorage.setItem(cacheKeys.departments, JSON.stringify(departments));
        sessionStorage.setItem(cacheKeys.evaluations, JSON.stringify(performanceEvaluations));
        console.log('✅ Kinerja Staff data has been cached.');

        // Render UI with fresh data
        renderStaffManagementTable();
        renderPerformanceEvaluationTable();
        updateStaffDropdownForEvaluation();
        updatePositionDropdownForStaffManagement();
        updateKinerjaStatistics();

    } catch (error) {
        console.error('Error loading initial data for Kinerja Staf:', error);
        showToast('Gagal memuat data. Silakan coba lagi.', 'error');
    } finally {
        hideLoading();
    }
}

// Setup Event Listeners for Kinerja Staff page
function setupKinerjaStaffEventListeners() {
    // Add Penilaian button
    document.getElementById('addPenilaianBtn').addEventListener('click', openAddPerformanceEvaluationModal);
    // Add Staff button
    document.getElementById('addStaffBtn').addEventListener('click', openAddStaffModal);

    // Modals
    document.getElementById('performanceEvaluationModal').addEventListener('click', function(e) {
        if (e.target === this) closePerformanceEvaluationModal();
    });
    document.getElementById('staffManagementModal').addEventListener('click', function(e) {
        if (e.target === this) closeStaffManagementModal();
    });
    document.getElementById('performanceDetailModal').addEventListener('click', function(e) {
        if (e.target === this) closePerformanceDetailModal();
    });

    // Form Submissions
    document.getElementById('performanceEvaluationForm').addEventListener('submit', handlePerformanceEvaluationFormSubmit);
    document.getElementById('staffManagementForm').addEventListener('submit', handleStaffManagementFormSubmit);

    // Search and Filter for Rekapitulasi Penilaian Staf
    const rekaptitulasiSearchInput = document.getElementById('rekaptitulasiSearchInput');
    const rekaptitulasiFilterSelect = document.getElementById('rekaptitulasiFilterSelect');

    if (rekaptitulasiSearchInput) rekaptitulasiSearchInput.addEventListener('input', filterPerformanceEvaluations);
    if (rekaptitulasiFilterSelect) rekaptitulasiFilterSelect.addEventListener('change', filterPerformanceEvaluations);
}

// --- Modals for Kinerja Staf Page ---

// Performance Evaluation Modals
function openAddPerformanceEvaluationModal() {
    document.getElementById('performanceEvaluationModalTitle').textContent = 'Tambah Penilaian Staf Baru';
    document.getElementById('evaluationId').value = '';
    document.getElementById('staffSelect').value = '';
    document.getElementById('kedisiplinan').value = '';
    document.getElementById('komunikasi').value = '';
    document.getElementById('komplain').value = '';
    document.getElementById('kepatuhan').value = '';
    document.getElementById('targetKerja').value = '';
    document.getElementById('notes').value = '';
    
    document.getElementById('performanceEvaluationModal').classList.remove('hidden');
    document.getElementById('performanceEvaluationModal').classList.add('flex');
    updateStaffDropdownForEvaluation(); // Ensure dropdown is populated when adding
}

function openEditPerformanceEvaluationModal(evaluationId) {
    const evaluation = performanceEvaluations.find(e => e.id == evaluationId);
    if (!evaluation) return;

    document.getElementById('performanceEvaluationModalTitle').textContent = 'Edit Penilaian Staf';
    document.getElementById('evaluationId').value = evaluation.id;
    document.getElementById('staffSelect').value = evaluation.staff_id;
    document.getElementById('kedisiplinan').value = evaluation.kedisiplinan;
    document.getElementById('komunikasi').value = evaluation.komunikasi;
    document.getElementById('komplain').value = evaluation.komplain;
    document.getElementById('kepatuhan').value = evaluation.kepatuhan;
    document.getElementById('targetKerja').value = evaluation.target_kerja;
    document.getElementById('notes').value = evaluation.notes;
    document.getElementById('performanceEvaluationModal').classList.remove('hidden');
    document.getElementById('performanceEvaluationModal').classList.add('flex');
    updateStaffDropdownForEvaluation(); // Ensure dropdown is populated when editing
}

function openDetailPerformanceEvaluationModal(evaluationId) {
    const evaluation = performanceEvaluations.find(e => e.id == evaluationId);
    if (!evaluation) return;

    const staffName = evaluation.staff ? evaluation.staff.name : 'N/A';
    const positionName = evaluation.staff && evaluation.staff.position ? evaluation.staff.position.name : 'N/A';
    const departmentName = evaluation.staff && evaluation.staff.department ? evaluation.staff.department.name : 'N/A';

    document.getElementById('detailStaffName').textContent = staffName;
    document.getElementById('detailPosition').textContent = positionName;
    document.getElementById('detailDepartment').textContent = departmentName;
    document.getElementById('detailKedisiplinan').textContent = evaluation.kedisiplinan;
    document.getElementById('detailKomunikasi').textContent = evaluation.komunikasi;
    document.getElementById('detailKomplain').textContent = evaluation.komplain;
    document.getElementById('detailKepatuhan').textContent = evaluation.kepatuhan;
    document.getElementById('detailTargetKerja').textContent = evaluation.target_kerja;
    document.getElementById('detailStatusKinerja').textContent = evaluation.status_kinerja;
    document.getElementById('detailNotes').textContent = evaluation.notes || 'Tidak ada catatan.';
    document.getElementById('detailCreatedAt').textContent = new Date(evaluation.created_at).toLocaleString('id-ID');
    document.getElementById('detailUpdatedAt').textContent = new Date(evaluation.updated_at).toLocaleString('id-ID');

    document.getElementById('performanceDetailModal').classList.remove('hidden');
    document.getElementById('performanceDetailModal').classList.add('flex');
}


function closePerformanceEvaluationModal() {
    document.getElementById('performanceEvaluationModal').classList.add('hidden');
    document.getElementById('performanceEvaluationModal').classList.remove('flex');
}

function closePerformanceDetailModal() {
    document.getElementById('performanceDetailModal').classList.add('hidden');
    document.getElementById('performanceDetailModal').classList.remove('flex');
}

// Staff Management Modals (similar to dinas.js but for this page's context)
function openAddStaffModal() {
    document.getElementById('staffManagementModalTitle').textContent = 'Tambah Staff Baru';
    document.getElementById('staffManagementId').value = '';
    document.getElementById('staffManagementFullName').value = '';
    document.getElementById('staffManagementPosition').value = '';
    document.getElementById('staffManagementStatus').value = 'Aktif';
    document.getElementById('staffManagementModal').classList.remove('hidden');
    document.getElementById('staffManagementModal').classList.add('flex');
    updatePositionDropdownForStaffManagement(); // Populate position dropdown
}

function openEditStaffModal(staffId) {
    const staff = staffMembers.find(s => s.id == staffId);
    if (!staff) return;

    document.getElementById('staffManagementModalTitle').textContent = 'Edit Staff';
    document.getElementById('staffManagementId').value = staff.id;
    document.getElementById('staffManagementFullName').value = staff.name;
    document.getElementById('staffManagementPosition').value = staff.position_id;
    document.getElementById('staffManagementStatus').value = staff.status;
    document.getElementById('staffManagementModal').classList.remove('hidden');
    document.getElementById('staffManagementModal').classList.add('flex');
    updatePositionDropdownForStaffManagement(); // Populate position dropdown
}

function closeStaffManagementModal() {
    document.getElementById('staffManagementModal').classList.add('hidden');
    document.getElementById('staffManagementModal').classList.remove('flex');
}

// --- Form Handlers ---

async function handlePerformanceEvaluationFormSubmit(e) {
    e.preventDefault();
    showLoading(); // Show loading
    const formData = {
        id: document.getElementById('evaluationId').value,
        staff_id: document.getElementById('staffSelect').value,
        kedisiplinan: parseInt(document.getElementById('kedisiplinan').value),
        komunikasi: parseInt(document.getElementById('komunikasi').value),
        komplain: parseInt(document.getElementById('komplain').value),
        kepatuhan: parseInt(document.getElementById('kepatuhan').value),
        target_kerja: parseInt(document.getElementById('targetKerja').value),
        notes: document.getElementById('notes').value,
        // Add user, department, hospital IDs from global window.currentUser for new entries
        user_id: window.currentUser.id,
        department_id: window.currentUser.department_id,
        hospital_id: window.currentUser.hospital_id
    };

    try {
        const token = window.authToken;
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        };

        const url = formData.id ? `/api/v1/performance-evaluations/${formData.id}` : '/api/v1/performance-evaluations';
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

        await loadInitialKinerjaStaffData(true); // Refresh data
        closePerformanceEvaluationModal();
        showToast('Penilaian berhasil disimpan!', 'success');
    } catch (error) {
        console.error('Error saving performance evaluation:', error);
        showToast('Gagal menyimpan penilaian: ' + error.message, 'error');
    } finally {
        hideLoading(); // Hide loading
    }
}

async function handleStaffManagementFormSubmit(e) {
    e.preventDefault();
    showLoading(); // Show loading

    // Read values from hidden inputs by their 'name' attribute for consistency
    // window.currentUser already provides these for a cleaner approach
    const userId = window.currentUser.id;
    const departmentId = window.currentUser.department_id;
    const hospitalId = window.currentUser.hospital_id;

    const formData = {
        id: document.getElementById('staffManagementId').value,
        name: document.getElementById('staffManagementFullName').value,
        position_id: document.getElementById('staffManagementPosition').value,
        user_id: userId,
        department_id: departmentId,
        hospital_id: hospitalId,
        status: document.getElementById('staffManagementStatus').value
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

        await loadInitialKinerjaStaffData(true); // Refresh data for both tables
        closeStaffManagementModal();
        showToast('Data staff berhasil disimpan!', 'success');
    } catch (error) {
        console.error('Error saving staff:', error);
        showToast('Gagal menyimpan data staff: ' + error.message, 'error');
    } finally {
        hideLoading(); // Hide loading
    }
}

// --- Delete Functions ---

async function deletePerformanceEvaluation (evaluationId) {
    if (!evaluationId) return;

    const isConfirmed = await showConfirmationModal({
        title: 'Hapus Penilaian',
        message: 'Apakah Anda yakin ingin menghapus penilaian ini?'
    });

    if (!isConfirmed) return;

    showLoading();
    try {
        const token = window.authToken;
        const headers = { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` };
        const response = await fetch(`/api/v1/performance-evaluations/${evaluationId}`, { method: 'DELETE', headers });

        if (!response.ok) throw new Error((await response.json()).message || 'Gagal menghapus');

        await loadInitialKinerjaStaffData(true);
        closePerformanceEvaluationModal();
        showToast('Penilaian berhasil dihapus!', 'success');
    } catch (error) {
        showToast('Gagal menghapus penilaian: ' + error.message, 'error');
    } finally {
        hideLoading();
    }
}

async function deleteStaffManagement (staffId) {
    if (!staffId) return;

    const isConfirmed = await showConfirmationModal({
        title: 'Hapus Staff',
        message: 'Apakah Anda yakin ingin menghapus staff ini? Semua penilaian kinerja terkait juga akan dihapus.'
    });

    if (!isConfirmed) return;

    showLoading();
    try {
        const token = window.authToken;
        const headers = { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` };
        const response = await fetch(`/api/v1/staff/${staffId}`, { method: 'DELETE', headers });

        if (!response.ok) throw new Error((await response.json()).message || 'Gagal menghapus');

        await loadInitialKinerjaStaffData(true); // Refresh all data
        closeStaffManagementModal();
        showToast('Data staff berhasil dihapus!', 'success');
    } catch (error) {
        showToast('Gagal menghapus staff: ' + error.message, 'error');
    } finally {
        hideLoading();
    }
}

// --- Render Functions ---

function renderStaffManagementTable() {
    const tbody = document.getElementById('staffManagementTableBody');
    if (!tbody) {
        console.error('Staff management table body element not found!');
        return;
    }

    tbody.innerHTML = ''; // Clear existing rows

    if (!staffMembers || staffMembers.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = `<td colspan="6" class="text-center py-4 text-gray-500">Tidak ada data staff.</td>`;
        tbody.appendChild(row);
        return;
    }

    staffMembers.forEach((staff, index) => {
        const department = departments.find(d => d.id === staff.department_id) || {};
        const position = positions.find(p => p.id === staff.position_id) || {};
        
        const row = document.createElement('tr');
        row.classList.add('table-row');
        row.innerHTML = `
            <td class="px-6 py-4">${index + 1}</td>
            <td class="px-6 py-4">${staff.name || '-'}</td>
            <td class="px-6 py-4">${position.name || '-'}</td>
            <td class="px-6 py-4">${department.name || '-'}</td>
            <td class="px-6 py-4">
                <span class="px-2 py-1 rounded-full text-xs font-medium ${
                    staff.status === 'Aktif' ? 'bg-green-100 text-green-800' : 
                    staff.status === 'Cuti' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'
                }">
                    ${staff.status || '-'}
                </span>
            </td>
            <td class="px-6 py-4">
                <div class="flex space-x-2">
                    <button data-action="edit-staff" data-staff-id="${staff.id}" class="animated-button bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-lg text-xs font-semibold">
                        <i class="fas fa-pen mr-1 text-blue-500"></i>Edit
                    </button>
                    <button data-action="delete-staff" data-staff-id="${staff.id}" class="animated-button bg-white border border-red-500 text-red-500 px-4 py-2 rounded-lg text-xs font-semibold">
                        <i class="fas fa-trash mr-1 text-red-500"></i>Hapus
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}


function renderPerformanceEvaluationTable() {
    const tbody = document.getElementById('performanceEvaluationTableBody');
    if (!tbody) {
        console.error('Performance evaluation table body element not found!');
        return;
    }

    tbody.innerHTML = ''; // Clear existing rows

    const searchTerm = document.getElementById('rekaptitulasiSearchInput').value.toLowerCase();
    const filterStatus = document.getElementById('rekaptitulasiFilterSelect').value;


    const filteredEvaluations = performanceEvaluations.filter(evaluation => {
        const matchesSearch = evaluation.staff && evaluation.staff.name.toLowerCase().includes(searchTerm);
        const matchesStatus = filterStatus === 'Semua Status' || evaluation.status_kinerja === filterStatus;
        return matchesSearch && matchesStatus;
    });

    if (!filteredEvaluations || filteredEvaluations.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = `<td colspan="8" class="text-center py-4 text-gray-500">Tidak ada data penilaian.</td>`;
        tbody.appendChild(row);
        return;
    }

    filteredEvaluations.forEach(evaluation => {
        const staff = evaluation.staff; // Staff object is eager loaded

        const row = document.createElement('tr');
        row.classList.add('table-row');
        row.innerHTML = `
            <td class="px-6 py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">${staff ? staff.name.charAt(0).toUpperCase() : 'N/A'}</div>
                    <div>
                        <p class="font-semibold text-black">${staff ? staff.name : 'N/A'}</p>
                        <p class="text-xs text-gray-500">Staff ID: ${staff ? staff.id : 'N/A'}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <span class="status-indicator" style="background:${getRatingColor(evaluation.kedisiplinan)}"></span>
                    <span class="${getRatingTextColor(evaluation.kedisiplinan)} font-medium">${getRatingDescription(evaluation.kedisiplinan)}</span>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <span class="status-indicator" style="background:${getRatingColor(evaluation.komunikasi)}"></span>
                    <span class="${getRatingTextColor(evaluation.komunikasi)} font-medium">${getRatingDescription(evaluation.komunikasi)}</span>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <span class="status-indicator" style="background:${getRatingColor(evaluation.komplain)}"></span>
                    <span class="${getRatingTextColor(evaluation.komplain)} font-medium">${getRatingDescription(evaluation.komplain)}</span>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <span class="status-indicator" style="background:${getRatingColor(evaluation.kepatuhan)}"></span>
                    <span class="${getRatingTextColor(evaluation.kepatuhan)} font-medium">${getRatingDescription(evaluation.kepatuhan)}</span>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <span class="status-indicator" style="background:${getRatingColor(evaluation.target_kerja)}"></span>
                    <span class="${getRatingTextColor(evaluation.target_kerja)} font-medium">${getRatingDescription(evaluation.target_kerja)}</span>
                </div>
            </td>
            <td class="px-6 py-4">
                <span class="performance-badge" style="background:${getPerformanceBadgeColor(evaluation.status_kinerja)}">
                    ${evaluation.status_kinerja || 'N/A'}
                </span>
            </td>
            <td class="px-6 py-4">
                <div class="flex space-x-2">
                    <button data-action="edit-evaluation" data-evaluation-id="${evaluation.id}" class="animated-button bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-lg text-xs font-semibold">
                        <i class="fas fa-pen mr-1 text-blue-500"></i>Edit
                    </button>
                    <button data-action="detail-evaluation" data-evaluation-id="${evaluation.id}" class="animated-button bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-lg text-xs font-semibold">
                        <i class="fas fa-eye mr-1 text-blue-500"></i>Detail
                    </button>
                    <button data-action="delete-evaluation" data-evaluation-id="${evaluation.id}" class="animated-button bg-white border border-red-500 text-red-500 px-4 py-2 rounded-lg text-xs font-semibold">
                        <i class="fas fa-trash mr-1 text-red-500"></i>Hapus
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function updateStaffDropdownForEvaluation() {
    const staffSelect = document.getElementById('staffSelect');
    if (!staffSelect) return;

    staffSelect.innerHTML = '<option value="">Pilih Staff</option>';
    staffMembers.forEach(staff => {
        const option = document.createElement('option');
        option.value = staff.id;
        option.textContent = staff.name;
        staffSelect.appendChild(option);
    });
}

function updatePositionDropdownForStaffManagement() {
    const posSelect = document.getElementById('staffManagementPosition');
    if (!posSelect) {
        console.error('Staff management position dropdown element not found!');
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

function getPerformanceBadgeColor(status) {
    switch (status) {
        case 'Sangat Baik': return '#10b981'; // Green
        case 'Baik': return '#3b82f6';      // Blue
        case 'Cukup': return '#f59e0b';     // Yellow/Orange
        case 'Kurang': return '#ef4444';    // Red
        case 'Sangat Kurang': return '#b91c1c'; // Dark Red
        default: return '#6b7280';          // Gray
    }
}

// Helper functions for rating colors and descriptions for Rekapitulasi Penilaian Staff table
function getRatingColor(rating) {
    if (rating >= 86) return '#10b981'; // Green for high (Excellent/Good)
    if (rating >= 76) return '#3b82f6'; // Blue for medium (Good/Fair)
    if (rating >= 61) return '#f59e0b'; // Orange for low-medium (Needs Mentoring)
    return '#ef4444'; // Red for low (Needs Improvement)
}

function getRatingTextColor(rating) {
    if (rating >= 86) return 'text-green-700';
    if (rating >= 76) return 'text-blue-600';
    if (rating >= 61) return 'text-yellow-600';
    return 'text-red-600';
}

function getRatingDescription(rating) {
    
 if (rating >= 86) return 'Sangat Baik';
    if (rating >= 76) return 'Baik';
    if (rating >= 61) return 'Cukup';
    return 'Kurang';
}


function updateKinerjaStatistics() {
    // These variable names remain the same
    let excellentCount = 0; // Represents "Sangat Baik"
    let goodCount = 0;      // Represents "Baik"
    let mentoringCount = 0; // Represents "Cukup"
    let improvementCount = 0; // Represents "Kurang"

    performanceEvaluations.forEach(evaluation => {
        // Correctly check for Indonesian status strings
        switch (evaluation.status_kinerja) {
            case 'Sangat Baik':
                excellentCount++;
                break;
            case 'Baik':
                goodCount++;
                break;
            case 'Cukup':
                mentoringCount++;
                break;
            case 'Kurang':
                improvementCount++;
                break;
        }
    });

    // The IDs here match the HTML, so this part is fine
    document.getElementById('excellentPerformanceCount').textContent = excellentCount;
    document.getElementById('goodPerformanceCount').textContent = goodCount;
    document.getElementById('needMentoringCount').textContent = mentoringCount;
    document.getElementById('needImprovementCount').textContent = improvementCount;
}

function filterPerformanceEvaluations() {
    renderPerformanceEvaluationTable(); // Re-render table with current filters
}


// Make functions globally accessible if needed by inline HTML event handlers
window.openAddPerformanceEvaluationModal = openAddPerformanceEvaluationModal;
window.openEditPerformanceEvaluationModal = openEditPerformanceEvaluationModal;
window.openDetailPerformanceEvaluationModal = openDetailPerformanceEvaluationModal;
window.closePerformanceEvaluationModal = closePerformanceEvaluationModal;
window.closePerformanceDetailModal = closePerformanceDetailModal;
window.openAddStaffModal = openAddStaffModal;
window.openEditStaffModal = openEditStaffModal;
window.closeStaffManagementModal = closeStaffManagementModal;
window.deletePerformanceEvaluation = deletePerformanceEvaluation;
window.deleteStaffManagement = deleteStaffManagement;