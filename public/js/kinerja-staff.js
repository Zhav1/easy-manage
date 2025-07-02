// Global variables
let staffMembers = [];
let performanceEvaluations = [];
let positions = [];
let departments = [];

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    loadInitialKinerjaStaffData();
    setupKinerjaStaffEventListeners();
});

// Load initial data for Kinerja Staff page
async function loadInitialKinerjaStaffData() {
    try {
        const token = window.authToken;
        if (!token) {
            console.error('Bearer token missing');
            return;
        }

        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        };

        // Fetch staff, positions, departments, and performance evaluations concurrently
        const [staffResponse, positionsResponse, departmentsResponse, evaluationsResponse] = await Promise.all([
            fetch('/api/v1/staff', { headers }),
            fetch('/api/v1/positions', { headers }),
            fetch('/api/v1/departments', { headers }), // Fetch departments
            fetch('/api/v1/performance-evaluations', { headers })
        ]);

        staffMembers = await staffResponse.json();
        positions = await positionsResponse.json();
        departments = await departmentsResponse.json(); // Store departments
        performanceEvaluations = await evaluationsResponse.json();

        console.log('Staff Members:', staffMembers);
        console.log('Positions:', positions);
        console.log('Departments:', departments);
        console.log('Performance Evaluations:', performanceEvaluations);

        renderStaffManagementTable();
        renderPerformanceEvaluationTable();
        updateStaffDropdownForEvaluation();
        updateKinerjaStatistics();
    } catch (error) {
        console.error('Error loading initial data for Kinerja Staf:', error);
        alert('Gagal memuat data. Silakan coba lagi.');
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
    document.getElementById('deleteEvaluationBtn').classList.add('hidden');
    document.getElementById('performanceEvaluationModal').classList.remove('hidden');
    document.getElementById('performanceEvaluationModal').classList.add('flex');
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
    document.getElementById('deleteEvaluationBtn').classList.remove('hidden');
    document.getElementById('performanceEvaluationModal').classList.remove('hidden');
    document.getElementById('performanceEvaluationModal').classList.add('flex');
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
    document.getElementById('deleteStaffManagementBtn').classList.add('hidden'); // Hide delete for add
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
    document.getElementById('deleteStaffManagementBtn').classList.remove('hidden'); // Show delete for edit
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
    const formData = {
        id: document.getElementById('evaluationId').value,
        staff_id: document.getElementById('staffSelect').value,
        kedisiplinan: parseInt(document.getElementById('kedisiplinan').value),
        komunikasi: parseInt(document.getElementById('komunikasi').value),
        komplain: parseInt(document.getElementById('komplain').value),
        kepatuhan: parseInt(document.getElementById('kepatuhan').value),
        target_kerja: parseInt(document.getElementById('targetKerja').value),
        notes: document.getElementById('notes').value,
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

        await loadInitialKinerjaStaffData(); // Refresh data
        closePerformanceEvaluationModal();
        alert('Penilaian berhasil disimpan!');
    } catch (error) {
        console.error('Error saving performance evaluation:', error);
        alert('Gagal menyimpan penilaian: ' + error.message);
    }
}

async function handleStaffManagementFormSubmit(e) {
    e.preventDefault();

    // Read values from hidden inputs by their 'name' attribute for consistency
    const userId = document.querySelector('#staffManagementForm input[name="user_id"]').value;
    const departmentId = document.querySelector('#staffManagementForm input[name="department_id"]').value;
    const hospitalId = document.querySelector('#staffManagementForm input[name="hospital_id"]').value;

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

        await loadInitialKinerjaStaffData(); // Refresh data for both tables
        closeStaffManagementModal();
        alert('Data staff berhasil disimpan!');
    } catch (error) {
        console.error('Error saving staff:', error);
        alert('Gagal menyimpan data staff: ' + error.message);
    }
}

// --- Delete Functions ---

window.deletePerformanceEvaluation = async function() {
    const evaluationId = document.getElementById('evaluationId').value;
    if (!evaluationId || !confirm('Apakah Anda yakin ingin menghapus penilaian ini?')) return;

    try {
        const token = window.authToken;
        const headers = {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        };

        const response = await fetch(`/api/v1/performance-evaluations/${evaluationId}`, {
            method: 'DELETE',
            headers
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Network response was not ok');
        }

        await loadInitialKinerjaStaffData(); // Refresh data
        closePerformanceEvaluationModal();
        alert('Penilaian berhasil dihapus!');
    } catch (error) {
        console.error('Error deleting performance evaluation:', error);
        alert('Gagal menghapus penilaian: ' + error.message);
    }
}

window.deleteStaffManagement = async function() {
    const staffId = document.getElementById('staffManagementId').value;
    if (!staffId || !confirm('Apakah Anda yakin ingin menghapus staff ini? Semua penilaian terkait juga akan terhapus.')) return;

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
            throw new Error(errorData.message || 'Network response was not ok');
        }

        await loadInitialKinerjaStaffData(); // Refresh data
        closeStaffManagementModal();
        alert('Data staff berhasil dihapus!');
    } catch (error) {
        console.error('Error deleting staff:', error);
        alert('Gagal menghapus staff: ' + error.message);
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
        const position = positions.find(p => p.id === staff.position_id);
        const department = departments.find(d => d.id === staff.department_id); // Get department

        const row = document.createElement('tr');
        row.classList.add('table-row');
        row.innerHTML = `
            <td class="px-6 py-4">${index + 1}</td>
            <td class="px-6 py-4">${staff.name || '-'}</td>
            <td class="px-6 py-4">${position ? position.name : '-'}</td>
            <td class="px-6 py-4">${department ? department.name : '-'}</td>
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
                    <button onclick="openEditStaffModal(${staff.id})" class="animated-button bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-lg text-xs font-semibold">
                        <i class="fas fa-pen mr-1 text-blue-500"></i>Edit
                    </button>
                    <button onclick="deleteStaffManagementById(${staff.id})" class="animated-button bg-white border border-red-500 text-red-500 px-4 py-2 rounded-lg text-xs font-semibold">
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
                    <button onclick="openEditPerformanceEvaluationModal(${evaluation.id})" class="animated-button bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-lg text-xs font-semibold">
                        <i class="fas fa-pen mr-1 text-blue-500"></i>Edit
                    </button>
                    <button onclick="openDetailPerformanceEvaluationModal(${evaluation.id})" class="animated-button bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-lg text-xs font-semibold">
                        <i class="fas fa-eye mr-1 text-blue-500"></i>Detail
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
        case 'Excellent Performance': return '#10b981'; // Green
        case 'Good Performance': return '#3b82f6';    // Blue
        case 'Need Mentoring': return '#f59e0b';     // Yellow/Orange
        case 'Need Improvement': return '#ef4444';   // Red
        default: return '#6b7280'; // Gray
    }
}

// Helper functions for rating colors and descriptions for Rekapitulasi Penilaian Staff table
function getRatingColor(rating) {
    if (rating >= 4) return '#10b981'; // Green for high (Excellent/Good)
    if (rating >= 3) return '#3b82f6'; // Blue for medium (Good/Fair)
    if (rating >= 2) return '#f59e0b'; // Orange for low-medium (Needs Mentoring)
    return '#ef4444'; // Red for low (Needs Improvement)
}

function getRatingTextColor(rating) {
    if (rating >= 4) return 'text-green-700';
    if (rating >= 3) return 'text-blue-600';
    if (rating >= 2) return 'text-yellow-600';
    return 'text-red-600';
}

function getRatingDescription(rating) {
    switch(rating) {
        case 5: return 'Sangat Baik';
        case 4: return 'Baik';
        case 3: return 'Cukup';
        case 2: return 'Kurang';
        case 1: return 'Sangat Kurang';
        default: return '-';
    }
}


function updateKinerjaStatistics() {
    let excellentCount = 0;
    let goodCount = 0;
    let mentoringCount = 0;
    let improvementCount = 0;

    performanceEvaluations.forEach(evaluation => {
        switch (evaluation.status_kinerja) {
            case 'Excellent Performance':
                excellentCount++;
                break;
            case 'Good Performance':
                goodCount++;
                break;
            case 'Need Mentoring':
                mentoringCount++;
                break;
            case 'Need Improvement':
                improvementCount++;
                break;
        }
    });

    document.getElementById('excellentPerformanceCount').textContent = excellentCount;
    document.getElementById('goodPerformanceCount').textContent = goodCount;
    document.getElementById('needMentoringCount').textContent = mentoringCount;
    document.getElementById('needImprovementCount').textContent = improvementCount;
}

function filterPerformanceEvaluations() {
    renderPerformanceEvaluationTable(); // Re-render table with current filters
}

// Helper function to call deleteStaffManagement with a specific ID
// This is used by the inline onclick in the staff management table
window.deleteStaffManagementById = function(staffId) {
    // Set the staffId in the hidden input of the staff modal temporarily
    // This mimics opening the edit modal and then hitting delete, but directly handles deletion.
    document.getElementById('staffManagementId').value = staffId;
    deleteStaffManagement(); // Call the main delete function
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
// deleteStaffManagementById is already global via window.deleteStaffManagementById