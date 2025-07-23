// --- Global Variables (always at the very top of your ppi.js file) ---
const API_BASE_URL = '/api/v1';
let currentUserToken = null;
let currentActiveInsertionFormId = null;
let currentActiveMaintenanceFormId = null;
let currentActiveInfectionReportId = null;
let currentActiveNeedlestickReportId = null;

// Chart instances for destruction and re-creation (for the consolidated dashboard)
let insertionComplianceChartInstance = null;
let maintenanceComplianceChartInstance = null;
let infectionIncidentChartInstance = null;
let infectionLocationChartInstance = null;
let microorganismChartInstance = null;
let needlestickIncidentsChartInstance = null;
let needlestickByDepartmentChartInstance = null;
let needlestickByPositionChartInstance = null;


// Define the elements for the forms as constants
const INSERTION_ELEMENTS = [
    { id: 'element-ins-1', description: 'Hand hygiene dilakukan sebelum prosedur', detail: 'WHO 5 Moment' },
    { id: 'element-ins-2', description: 'Pasien disiapkan dengan antiseptik (chlorhexidine 2% atau povidone iodine 10%)', detail: 'Diberikan waktu kontak sesuai rekomendasi' },
    { id: 'element-ins-3', description: 'Area tindakan ditutup drape steril menyeluruh', detail: 'Mencakup seluruh area kerja' },
    { id: 'element-ins-4', description: 'Operator menggunakan APD lengkap (masker, cap, sarung tangan steril, gown)', detail: 'Termasuk pelindung mata jika diperlukan' },
    { id: 'element-ins-5', description: 'Lokasi insersi dipilih secara tepat', detail: 'Subklavia/jugularis lebih disukai daripada femoralis' },
    { id: 'element-ins-6', description: 'Penggunaan sarung tangan steril', detail: 'Teknik steril saat memasang CVC' },
    { id: 'element-ins-7', description: 'Teknik aseptik dijaga selama prosedur', detail: 'Tidak ada kontaminasi' },
    { id: 'element-ins-8', description: 'Fiksasi kateter yang adekuat', detail: 'Mencegah gerakan dan trauma' },
    { id: 'element-ins-9', description: 'Penutupan area insersi dengan dressing steril', detail: 'Sesuai protokol rumah sakit' },
    { id: 'element-ins-10', description: 'Verifikasi posisi kateter pasca-insersi (mis. rontgen)', detail: 'Memastikan ujung kateter benar' },
    { id: 'element-ins-11', description: 'Edukasi pasien/keluarga tentang perawatan CVC', detail: 'Penting untuk kepatuhan' },
    { id: 'element-ins-12', description: 'Pencatatan lengkap di rekam medis', detail: 'Termasuk tanggal, lokasi, operator, dan komplikasi' }
];

const MAINTENANCE_ELEMENTS = [
    { id: 'element-maint-1', description: 'Hand hygiene dilakukan sebelum perawatan', detail: 'Menggunakan teknik yang benar' },
    { id: 'element-maint-2', description: 'Pemeriksaan area insersi setiap hari', detail: 'Tanda-tanda infeksi (eritema, edema, nyeri, drainase)' },
    { id: 'element-maint-3', description: 'Perawatan area insersi dengan antiseptik', detail: 'Chlorhexidine 2% atau povidone iodine 10%' },
    { id: 'element-maint-4', description: 'Penggantian dressing sesuai jadwal', detail: 'Transparan setiap 7 hari, kasa setiap 2 hari' },
    { id: 'element-maint-5', description: 'Pemeriksaan kebutuhan kateter setiap hari', detail: 'Evaluasi kelanjutan pemakaian' },
    { id: 'element-maint-6', description: 'Penggantian set infus dan konektor tanpa jarum sesuai jadwal', detail: 'Meminimalkan risiko kontaminasi' },
    { id: 'element-maint-7', description: 'Cuci lumen kateter dengan saline setelah setiap penggunaan', detail: 'Mencegah oklusi dan infeksi' },
    { id: 'element-maint-8', description: 'Penggunaan teknik aseptik saat mengakses lumen kateter', detail: 'Mengurangi risiko kontaminasi' }
];

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


// --- Utility Functions ---
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
}

function getAuthHeaders() {
    return {
        'Accept': 'application/json',
        'Authorization': `Bearer ${currentUserToken}`,
    };
}

async function apiCall(endpoint, method = 'GET', data = null, isFormData = false) {
    showLoading();
    if (!currentUserToken) {
        showToast('Token autentikasi tidak ditemukan. Harap login kembali.', 'error');
        window.location.href = '/login';
        hideLoading();
        return Promise.reject(new Error('Authentication token missing.'));
    }

    const headers = getAuthHeaders();
    let body = null;

    if (data) {
        if (isFormData) {
            body = data;
        } else {
            headers['Content-Type'] = 'application/json';
            body = JSON.stringify(data);
        }
    }

    try {
        const response = await fetch(`${API_BASE_URL}/${endpoint}`, {
            method: method,
            headers: isFormData ? { 'Authorization': `Bearer ${currentUserToken}`, 'Accept': 'application/json' } : headers,
            body: body,
        });

        if (response.status === 204) return null;
        
        const responseData = await response.json();

        if (!response.ok) {
            const errorMessage = responseData.message || `API error: ${response.status}`;
            throw new Error(errorMessage);
        }

        return responseData;
    } catch (error) {
        showToast(`Error: ${error.message}`, 'error');
        throw error;
    } finally {
        hideLoading();
    }
}

// --- NEW HELPER FUNCTIONS ---
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const template = document.getElementById('toast-template');
    if (!container || !template) return;

    const newToast = template.cloneNode(true);
    newToast.id = '';
    newToast.classList.remove('hidden');
    newToast.classList.add('flex');

    const iconDiv = newToast.querySelector('#toast-icon');
    const messageDiv = newToast.querySelector('#toast-message');
    iconDiv.innerHTML = ''; // Clear previous icon
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

    setTimeout(() => {
        newToast.style.transition = 'opacity 0.5s ease';
        newToast.style.opacity = '0';
        setTimeout(() => newToast.remove(), 500);
    }, 5000);
}

function showConfirmationModal({ title, message, confirmText = 'Ya, Hapus', cancelText = 'Batal' }) {
    const modal = document.getElementById('myConfirmationModal');
    const modalBox = document.getElementById('confirmationModalBox');
    const titleEl = document.getElementById('confirmationTitle');
    const messageEl = document.getElementById('confirmationMessage');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    const cancelBtn = document.getElementById('confirmCancelBtn');

    titleEl.textContent = title;
    messageEl.innerHTML = message;
    confirmBtn.textContent = confirmText;
    cancelBtn.textContent = cancelText;

    // To SHOW, we just add our new class
    modal.classList.add('modal-visible');

    setTimeout(() => {
       modalBox.classList.remove('scale-95', 'opacity-0');
       modalBox.classList.add('scale-100', 'opacity-100');
    }, 10);

    return new Promise((resolve) => {
        const closeModal = (result) => {
            // To HIDE, we just remove our new class
            modal.classList.remove('modal-visible');
            modalBox.classList.add('scale-95', 'opacity-0');
            resolve(result);
        };

        confirmBtn.onclick = () => closeModal(true);
        cancelBtn.onclick = () => closeModal(false);
        modal.onclick = (e) => {
             if(e.target === modal) {
                closeModal(false);
             }
        }
    });
}

async function deleteEntry(formType, formId) {
    const isConfirmed = await showConfirmationModal({
        title: `Konfirmasi Hapus Data`,
        message: `Anda yakin ingin menghapus data ${formType.replace('-', ' ')} ini? Tindakan ini tidak dapat dibatalkan.`
    });

    if (!isConfirmed) return;

    try {
        const endpointMap = {
            'insertion': `cvc-insertions/${formId}`,
            'maintenance': `cvc-maintenances/${formId}`,
            'infection': `cvc-infections/${formId}`,
            'needlestick': `needlestick-reports/${formId}`
        };
        const endpoint = endpointMap[formType];
        if (!endpoint) throw new Error('Jenis form tidak valid');
        
        await apiCall(endpoint, 'DELETE');
        showToast(`Data ${formType.replace('-', ' ')} berhasil dihapus.`, 'success');

        // Refresh relevant parts of the UI
        await window[`load${capitalizeFirstLetter(formType)}History`]();
        await loadDashboardStats(true); // Force refresh analytics
    } catch (error) {
        // The error toast is already shown in apiCall
        console.error(`Error deleting ${formType} data:`, error);
    }
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
}

function formatDateTime(dateTimeString) {
    if (!dateTimeString) return '-';
    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    return new Date(dateTimeString).toLocaleDateString('id-ID', options);
}

function getComplianceBadgeClass(percentage) {
    if (percentage >= 90) return 'bg-green-100 text-green-800';
    if (percentage >= 70) return 'bg-yellow-100 text-yellow-800';
    return 'bg-red-100 text-red-800';
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}


// --- Detail & Photo Modals Functions ---
window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    // Clear content of the detail modal body when it's closed
    if (modalId === 'detailModal') {
        const detailModalBody = document.getElementById('modalContent');
        if (detailModalBody) detailModalBody.innerHTML = '';
    }
};

window.showDetailModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
};

// Re-using the detail modal for photo display
window.openPhotoModal = function(photoPath) {
    const modalTitle = document.getElementById('modalTitle');
    const modalContent = document.getElementById('modalContent');

    if (modalTitle && modalContent) {
        modalTitle.textContent = 'Foto Dokumentasi';
        modalContent.innerHTML = `<img src="${photoPath}" alt="Dokumentasi Foto" class="max-w-full max-h-full object-contain mx-auto">`;
        window.showDetailModal('detailModal');
    }
};

window.editFormFromModal = async function(formType, id) {
    window.closeModal('detailModal');
    // showLoading(); // Already covered by apiCall, but could be added here for *very* quick UI changes
    try {
        let data;
        if (formType === 'insertion') {
            data = await apiCall(`cvc-insertions/${id}`);
            populateInsertionForm(data);
            window.switchTab('insersi', 'insersi-form');
        } else if (formType === 'maintenance') {
            data = await apiCall(`cvc-maintenances/${id}`);
            populateMaintenanceForm(data);
            window.switchTab('maintenance', 'maintenance-form');
        } else if (formType === 'infection') {
            data = await apiCall(`cvc-infections/${id}`);
            populateInfectionReportForm(data);
            window.switchTab('infeksi', 'infeksi-form');
        } else if (formType === 'needlestick') {
            data = await apiCall(`needlestick-reports/${id}`);
            populateNeedlestickReportForm(data);
            window.switchTab('needlestick', 'needlestick-form');
        }
    } catch (error) {
        console.error(`Error loading ${formType} form for edit:`, error);
    }
    // No need for hideLoading() here, as apiCall handles it.
};

// --- Photo Upload Feedback Logic ---
function setupPhotoInputFeedback(formType, index, existingPhotoPath = null) {
    const fileInput = document.getElementById(`${formType}-photo-${index}`);
    const label = fileInput?.nextElementSibling;
    const container = label?.closest('td');

    if (!fileInput || !label || !container) {
        return;
    }

    const currentPhotoInfoDivId = `photo-info-${formType}-${index}`;
    let currentPhotoInfoDiv = document.getElementById(currentPhotoInfoDivId);
    if (!currentPhotoInfoDiv) {
        currentPhotoInfoDiv = document.createElement('div');
        currentPhotoInfoDiv.id = currentPhotoInfoDivId;
        currentPhotoInfoDiv.classList.add('mt-1', 'text-xs', 'text-gray-500', 'flex', 'items-center', 'space-x-1');
        container.appendChild(currentPhotoInfoDiv);
    } else {
        currentPhotoInfoDiv.innerHTML = '';
    }

    const updateFeedback = (fileName, pathForView = null) => {
        let displayFileName = fileName;
        if (fileName.length > 15) {
            displayFileName = fileName.substring(0, 12) + '...';
        }

        currentPhotoInfoDiv.innerHTML = `
            <span class="mr-1">${displayFileName}</span>
            ${pathForView ? `<button type="button" class="text-blue-500 hover:underline" onclick="openPhotoModal('${pathForView}')">Lihat</button>` : ''}
            <button type="button" class="text-red-500 hover:text-red-700" onclick="removePhoto('${formType}', ${index})">x</button>
            <input type="hidden" name="elements_data[${index}][photo_path_removed]" value="false"> `;
        if (pathForView && !fileInput.files.length) {
            const hiddenPathInput = document.createElement('input');
            hiddenPathInput.type = 'hidden';
            hiddenPathInput.name = `elements_data[${index}][photo_path]`;
            hiddenPathInput.value = pathForView;
            currentPhotoInfoDiv.appendChild(hiddenPathInput);
        }
    };

    if (existingPhotoPath) {
        const fileName = existingPhotoPath.substring(existingPhotoPath.lastIndexOf('/') + 1);
        updateFeedback(fileName, existingPhotoPath);
    } else {
        currentPhotoInfoDiv.innerHTML = '';
    }

    fileInput.onchange = null;
    fileInput.onchange = function() {
        if (this.files && this.files[0]) {
            updateFeedback(this.files[0].name);
        } else {
            currentPhotoInfoDiv.innerHTML = '';
            const hiddenRemovedInput = container.querySelector(`input[name="elements_data[${index}][photo_path_removed]"]`);
            if (hiddenRemovedInput) hiddenRemovedInput.value = 'true';
            else {
                const newHiddenRemovedInput = document.createElement('input');
                newHiddenRemovedInput.type = 'hidden';
                newHiddenRemovedInput.name = `elements_data[${index}][photo_path_removed]`;
                newHiddenRemovedInput.value = 'true';
                container.appendChild(newHiddenRemovedInput);
            }
        }
    };
}

// --- Photo Preview for main forms (Needlestick and Infection) ---
function setupMainFormPhotoPreview(fileInputId, photoPreviewId, photoPlaceholderId, removePhotoButtonId) {
    const fileInput = document.getElementById(fileInputId);
    const photoPreview = document.getElementById(photoPreviewId);
    const photoPlaceholder = document.getElementById(photoPlaceholderId);
    const removePhotoButton = document.getElementById(removePhotoButtonId);

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (photoPreview) photoPreview.src = e.target.result;
                    if (photoPreview) photoPreview.classList.remove('hidden');
                    if (photoPlaceholder) photoPlaceholder.classList.add('hidden');
                    if (removePhotoButton) removePhotoButton.classList.remove('hidden');
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                if (photoPreview) photoPreview.src = '#';
                if (photoPreview) photoPreview.classList.add('hidden');
                if (photoPlaceholder) photoPlaceholder.classList.remove('hidden');
                if (removePhotoButton) removePhotoButton.classList.add('hidden');
            }
        });
    }

    if (removePhotoButton) {
        removePhotoButton.addEventListener('click', function() {
            const fileInputElem = document.getElementById(fileInputId);
            if (fileInputElem) fileInputElem.value = '';
            if (photoPreview) photoPreview.src = '#';
            if (photoPreview) photoPreview.classList.add('hidden');
            if (photoPlaceholder) photoPlaceholder.classList.remove('hidden');
            if (removePhotoButton) removePhotoButton.classList.add('hidden');
        });
    }
}

// Unified removePhoto for both insertion and maintenance element photos
window.removePhoto = function(formType, index) {
    const fileInput = document.getElementById(`${formType}-photo-${index}`);
    const currentPhotoInfoDiv = document.getElementById(`photo-info-${formType}-${index}`);
    const container = document.getElementById(`${formType}-photo-${index}`)?.closest('td');

    if (fileInput) fileInput.value = '';
    if (currentPhotoInfoDiv) currentPhotoInfoDiv.innerHTML = '';

    if (container) {
        let hiddenRemovedInput = container.querySelector(`input[name="elements_data[${index}][photo_path_removed]"]`);
        if (!hiddenRemovedInput) {
            hiddenRemovedInput = document.createElement('input');
            hiddenRemovedInput.type = 'hidden';
            hiddenRemovedInput.name = `elements_data[${index}][photo_path_removed]`;
            container.appendChild(hiddenRemovedInput);
        }
        hiddenRemovedInput.value = 'true';
    }
};


// --- Main Application Flow and Event Handlers ---

document.addEventListener('DOMContentLoaded', async function() {
    currentUserToken = window.authToken;
    if (!currentUserToken) {
        console.error('Authentication token is not available. Redirecting to login.');
        window.location.href = '/login';
        return;
    }

    // Initial load/setup
    initTabs();
    await loadDashboardStats();
    setupFormEventListeners();
    
    // Setup photo previews
    setupMainFormPhotoPreview('infectionFileUpload', 'infectionPhotoPreview', 'infectionPhotoPlaceholder', 'removeInfectionPhoto');
    setupMainFormPhotoPreview('needlestickFileUpload', 'needlestickPhotoPreview', 'needlestickPhotoPlaceholder', 'removeNeedlestickPhoto');

    // Reset all forms initially
    resetInsertionForm();
    resetMaintenanceForm();
    resetInfectionForm();
    resetNeedlestickForm();

    // Load initial history tables
    await Promise.all([
        loadInsertionHistory(),
        loadMaintenanceHistory(),
        loadInfectionHistory(),
        loadNeedlestickHistory()
    ]);

    // Setup modal close listeners
    document.getElementById('closeModalBtn')?.addEventListener('click', () => window.closeModal('detailModal'));
    document.getElementById('detailModal')?.addEventListener('click', (e) => {
        if (e.target.id === 'detailModal') window.closeModal('detailModal');
    });

    // Main event delegation for detail and delete buttons
    // This is the clean way to handle clicks without conflicts
    document.body.addEventListener('click', async function(event) {
        // Use .closest() to find the button, even if the icon inside is clicked
        const target = event.target.closest('.detail-button, .delete-button');
        if (!target) return; // Exit if the click wasn't on one of our buttons

        const formId = target.dataset.id;
        const formType = target.dataset.type;

        if (target.classList.contains('detail-button')) {
            try {
                await window[`show${capitalizeFirstLetter(formType)}DetailModal`](formId);
            } catch (error) {
                console.error(`Error showing detail for ${formType} ID ${formId}:`, error);
            }
        } else if (target.classList.contains('delete-button')) {
            await deleteEntry(formType, formId);
        }
    });

    // Listener for "Lainnya" checkbox in Needlestick form
    document.getElementById('immediateAction4')?.addEventListener('change', function() {
        const otherInput = document.getElementById('otherImmediateAction');
        if (this.checked) {
            otherInput.classList.remove('hidden');
            otherInput.setAttribute('required', 'true');
        } else {
            otherInput.classList.add('hidden');
            otherInput.removeAttribute('required');
            otherInput.value = '';
        }
    });
});



// --- Functions for tab switching and section toggling ---
function initTabs() {
    const initialTabs = [
        { section: 'needlestick', tabId: 'needlestick-form' },
        { section: 'insersi', tabId: 'insersi-form' },
        { section: 'maintenance', tabId: 'maintenance-form' },
        { section: 'infeksi', tabId: 'infeksi-form' }
    ];

    initialTabs.forEach(tab => {
        const activeTabButton = document.getElementById(`${tab.tabId}-tab`);
        if (activeTabButton) {
            const colors = {
                'needlestick': ['border-emerald-500', 'text-emerald-600'],
                'insersi': ['border-blue-500', 'text-blue-600'],
                'maintenance': ['border-purple-500', 'text-purple-600'],
                'infeksi': ['border-red-500', 'text-red-600']
            };

            activeTabButton.classList.add(...colors[tab.section]);
            activeTabButton.classList.remove('border-transparent', 'text-gray-500');

            document.getElementById(tab.tabId)?.classList.remove('hidden');
        }
    });

    document.querySelectorAll('[data-section][data-tab-target]').forEach(button => {
        button.addEventListener('click', function(e) {
            const section = this.getAttribute('data-section');
            const tabId = this.getAttribute('data-tab-target');
            window.switchTab(section, tabId, e);
        });
    });
    // Removed: Dynamic addition of analytics tab for Needlestick from initTabs as it's now consolidated
}

window.toggleSection = function(sectionId) {
    const section = document.getElementById(sectionId);
    const arrow = document.getElementById(`arrow-${sectionId}`);

    if (section) section.classList.toggle('hidden');
    if (arrow) arrow.classList.toggle('rotate-180');
    // Important: When the analytics-dashboard section is opened, re-load its data to refresh charts
    if (sectionId === 'analytics-dashboard' && !section.classList.contains('hidden')) {
        loadDashboardStats();
    }
};

window.switchTab = async function(section, tabId, event) {
    if (event) event.preventDefault();

    // Hide all tab content for the current section
    document.querySelectorAll(`.tab-content.${section}-tab`).forEach(tab => {
        tab.classList.add('hidden');
    });

    // Deactivate all tab buttons for the current section
    document.querySelectorAll(`button[data-section="${section}"]`).forEach(tabButton => {
        tabButton.classList.remove(
            'border-emerald-500', 'text-emerald-600',
            'border-blue-500', 'text-blue-600',
            'border-purple-500', 'text-purple-600',
            'border-red-500', 'text-red-600',
            'active-tab'
        );
        tabButton.classList.add('border-transparent', 'text-gray-500');
    });

    // Show the target tab content
    const targetTab = document.getElementById(tabId);
    if (targetTab) {
        targetTab.classList.remove('hidden');
    } else {
        console.error(`Tab with id ${tabId} not found`);
    }

    // Activate the clicked tab button
    const activeTabButton = event?.currentTarget || document.getElementById(`${tabId}-tab`);
    if (activeTabButton) {
        activeTabButton.classList.add('active-tab');

        // Apply specific colors based on section
        if (section === 'needlestick') {
            activeTabButton.classList.add('border-emerald-500', 'text-emerald-600');
        } else if (section === 'insersi') {
            activeTabButton.classList.add('border-blue-500', 'text-blue-600');
        } else if (section === 'maintenance') {
            activeTabButton.classList.add('border-purple-500', 'text-purple-600');
        } else if (section === 'infeksi') {
            activeTabButton.classList.add('border-red-500', 'text-red-600');
        }
        activeTabButton.classList.remove('border-transparent', 'text-gray-500');
    }

    // Load history data when switching to history tabs
    if (tabId === 'needlestick-history') {
        await loadNeedlestickHistory();
    } else if (tabId === 'insersi-history') {
        await loadInsertionHistory();
    } else if (tabId === 'maintenance-history') {
        await loadMaintenanceHistory();
    } else if (tabId === 'infeksi-history') {
        await loadInfectionHistory();
    }
};


// --- Dashboard Stats Loading (MAIN ANALYTICS FUNCTION) ---
async function loadDashboardStats(forceRefresh = false) {
    showLoading();
    let stats; // <-- FIX #2: Declare 'stats' once at the top level of the function

    try {
        const cachedAnalytics = sessionStorage.getItem('prefetched_ppi_analytics');
        
        if (cachedAnalytics && !forceRefresh) { 
            console.log('⚡️ Loading PPI analytics data from cache.');
            stats = JSON.parse(cachedAnalytics); // Use the top-level 'stats'
        } else {
            if (forceRefresh) {
                console.log('🔄 Forcing refresh of PPI analytics data from API...');
            } else {
                console.log('No cache found. Fetching PPI analytics data from API...');
            }
            stats = await apiCall('cvc-infections/analytics');
            if (stats) sessionStorage.setItem('prefetched_ppi_analytics', JSON.stringify(stats));
        }

        if (!stats) {
            throw new Error("Analytics data is null or undefined after fetching/caching.");
        }

        // --- The rest of the function (rendering UI and charts) remains unchanged ---
        // It will now use the correct 'stats' object (either fresh or cached).
        
        // Update top cards
        document.getElementById('insertionCompliance').textContent = `${stats.total_insertions_today || 0} Form`;
        document.getElementById('maintenanceCompliance').textContent = `${stats.total_maintenances_today || 0} Form`;
        document.getElementById('totalInfections').textContent = `${stats.total_infections_today || 0} Kasus`;
        document.getElementById('totalNeedlestickCases').textContent = `${stats.total_needlestick_cases_today || 0} Kasus`;

        // Update KPI section for analytics
        document.getElementById('insertionComplianceRate').textContent = `${stats.insertion_compliance_rate || 0}%`;
        document.getElementById('maintenanceComplianceRate').textContent = `${stats.maintenance_compliance_rate || 0}%`;
        document.getElementById('needlestickRate').textContent = `${stats.needlestick_rate_30_days || 0}`;

        // Destroy all previous chart instances
        if (insertionComplianceChartInstance) insertionComplianceChartInstance.destroy();
        if (maintenanceComplianceChartInstance) maintenanceComplianceChartInstance.destroy();
        if (infectionIncidentChartInstance) infectionIncidentChartInstance.destroy();
        if (infectionLocationChartInstance) infectionLocationChartInstance.destroy();
        if (microorganismChartInstance) microorganismChartInstance.destroy();
        if (needlestickIncidentsChartInstance) needlestickIncidentsChartInstance.destroy();
        if (needlestickByDepartmentChartInstance) needlestickByDepartmentChartInstance.destroy();
        if (needlestickByPositionChartInstance) needlestickByPositionChartInstance.destroy();

        // 1. CVC Insertion Compliance Chart
        const insertionComplianceCtx = document.getElementById('insertionComplianceChart')?.getContext('2d');
        if (insertionComplianceCtx) {
            insertionComplianceChartInstance = new Chart(insertionComplianceCtx, {
                type: 'bar',
                data: {
                    labels: ['Kepatuhan Insersi'],
                    datasets: [{
                        label: 'Kepatuhan (%)',
                        data: [stats.insertion_compliance_rate || 0],
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgb(54, 162, 235)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, max: 100, title: { display: true, text: 'Persentase Kepatuhan' } }
                    },
                    plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(context) { return `Kepatuhan: ${context.raw}%`; } } } }
                }
            });
        }

        // 2. CVC Maintenance Compliance Chart
        const maintenanceComplianceCtx = document.getElementById('maintenanceComplianceChart')?.getContext('2d');
        if (maintenanceComplianceCtx) {
            maintenanceComplianceChartInstance = new Chart(maintenanceComplianceCtx, {
                type: 'bar',
                data: {
                    labels: ['Kepatuhan Maintenance'],
                    datasets: [{
                        label: 'Kepatuhan (%)',
                        data: [stats.maintenance_compliance_rate || 0],
                        backgroundColor: 'rgba(153, 102, 255, 0.7)',
                        borderColor: 'rgb(153, 102, 255)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, max: 100, title: { display: true, text: 'Persentase Kepatuhan' } }
                    },
                    plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(context) { return `Kepatuhan: ${context.raw}%`; } } } }
                }
            });
        }

        // 3. Needlestick Incidents Chart (Trend)
        const needlestickIncidentsChartCtx = document.getElementById('needlestickIncidentsChart')?.getContext('2d');
        if (needlestickIncidentsChartCtx) {
            const needlestickTrendLabels = stats.needlestick_trend.map(item => {
                const [year, month] = item.month.split('-');
                const date = new Date(year, month - 1);
                return date.toLocaleString('id-ID', { month: 'short', year: '2-digit' });
            });
            const needlestickTrendData = stats.needlestick_trend.map(item => item.count);

            needlestickIncidentsChartInstance = new Chart(needlestickIncidentsChartCtx, {
                type: 'line',
                data: {
                    labels: needlestickTrendLabels,
                    datasets: [{
                        label: 'Jumlah Kasus Tertusuk Jarum',
                        data: needlestickTrendData,
                        borderColor: 'rgb(6, 182, 212)',
                        backgroundColor: 'rgba(6, 182, 212, 0.2)',
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, title: { display: true, text: 'Jumlah Kasus' } },
                        x: { title: { display: true, text: 'Bulan' } }
                    },
                    plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(context) { return `Jumlah: ${context.raw}`; } } } }
                }
            });
        }

        // 4. CVC Infection Rates Chart (Trend)
        const infectionRatesCtx = document.getElementById('infectionRatesChart')?.getContext('2d');
        if (infectionRatesCtx) {
            const infectionTrendLabels = stats.infection_trend.map(item => {
                const [year, month] = item.month.split('-');
                const date = new Date(year, month - 1);
                return date.toLocaleString('id-ID', { month: 'short', year: '2-digit' });
            });
            const infectionTrendData = stats.infection_trend.map(item => item.count);

            infectionIncidentChartInstance = new Chart(infectionRatesCtx, {
                type: 'line',
                data: {
                    labels: infectionTrendLabels,
                    datasets: [{
                        label: 'Jumlah Infeksi',
                        data: infectionTrendData,
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, title: { display: true, text: 'Jumlah Kasus' } },
                        x: { title: { display: true, text: 'Bulan' } }
                    },
                    plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(context) { return `Jumlah: ${context.raw}`; } } } }
                }
            });
        }

        // 5. Needlestick by Department Chart
        const needlestickByDepartmentCtx = document.getElementById('needlestickByDepartmentChart')?.getContext('2d');
        if (needlestickByDepartmentCtx) {
            const deptLabels = stats.needlestick_by_department.map(item => item.department || 'Tidak Diketahui');
            const deptData = stats.needlestick_by_department.map(item => item.count);

            needlestickByDepartmentChartInstance = new Chart(needlestickByDepartmentCtx, {
                type: 'pie',
                data: {
                    labels: deptLabels,
                    datasets: [{
                        data: deptData,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)', 'rgba(153, 102, 255, 0.7)', 'rgba(255, 159, 64, 0.7)',
                            'rgba(199, 199, 199, 0.7)'
                        ],
                        borderColor: '#fff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right' },
                        tooltip: { callbacks: { label: function(context) { let label = context.label || ''; if (label) { label += ': '; } label += context.raw + ' kasus'; return label; } } }
                    }
                }
            });
        }

        // 6. Needlestick by Position Chart
        const needlestickByPositionCtx = document.getElementById('needlestickByPositionChart')?.getContext('2d');
        if (needlestickByPositionCtx) {
            const positionLabels = stats.needlestick_by_position.map(item => item.injured_person_position || 'Tidak Diketahui');
            const positionData = stats.needlestick_by_position.map(item => item.count);

            needlestickByPositionChartInstance = new Chart(needlestickByPositionCtx, {
                type: 'bar',
                data: {
                    labels: positionLabels,
                    datasets: [{
                        label: 'Jumlah Kasus',
                        data: positionData,
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgb(75, 192, 192)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    scales: {
                        x: { beginAtZero: true, title: { display: true, text: 'Jumlah Kasus' } },
                        y: { title: { display: true, text: 'Jabatan' } }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: function(context) { return `Kasus: ${context.raw}`; } } }
                    }
                }
            });
        }

        // 7. Infection by Location Chart
        const infectionLocationCtx = document.getElementById('infectionLocationChart')?.getContext('2d');
        if (infectionLocationCtx) {
            const locationLabels = stats.infection_by_location.map(item => item.insertion_location || 'Tidak Diketahui');
            const locationData = stats.infection_by_location.map(item => item.count);

            infectionLocationChartInstance = new Chart(infectionLocationCtx, {
                type: 'pie',
                data: { labels: locationLabels, datasets: [{ data: locationData, backgroundColor: [ 'rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)', 'rgba(153, 102, 255, 0.7)' ], borderColor: '#fff', borderWidth: 1 }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right' }, tooltip: { callbacks: { label: function(context) { let label = context.label || ''; if (label) { label += ': '; } label += context.raw + ' kasus'; return label; } } } } }
            });
        }

        // 8. Infection by Microorganism Chart
        const microorganismCtx = document.getElementById('microorganismChart')?.getContext('2d');
        if (microorganismCtx) {
            const microorganismLabels = stats.infection_by_microorganism.map(item => item.microorganism);
            const microorganismData = stats.infection_by_microorganism.map(item => item.count);

            microorganismChartInstance = new Chart(microorganismCtx, {
                type: 'bar',
                data: { labels: microorganismLabels, datasets: [{ label: 'Jumlah Kasus', data: microorganismData, backgroundColor: 'rgba(153, 102, 255, 0.7)', borderColor: 'rgb(153, 102, 255)', borderWidth: 1 }] },
                options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y', scales: { x: { beginAtZero: true, title: { display: true, text: 'Jumlah Kasus' } }, y: { title: { display: true, text: 'Mikroorganisme' } } }, plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(context) { return `Kasus: ${context.raw}`; } } } } }
            });
        }

    } catch (error) {
        console.error('Error loading dashboard stats:', error);
        // Set fallback UI on error and destroy charts
        const idsToReset = ['insertionCompliance', 'maintenanceCompliance', 'totalInfections', 'totalNeedlestickCases', 'clabsiRate', 'insertionComplianceRate', 'maintenanceComplianceRate', 'needlestickRate'];
        idsToReset.forEach(id => {
            const elem = document.getElementById(id);
            if (elem) elem.textContent = '--';
        });


        if (insertionComplianceChartInstance) insertionComplianceChartInstance.destroy();
        if (maintenanceComplianceChartInstance) maintenanceComplianceChartInstance.destroy();
        if (infectionIncidentChartInstance) infectionIncidentChartInstance.destroy();
        if (infectionLocationChartInstance) infectionLocationChartInstance.destroy();
        if (microorganismChartInstance) microorganismChartInstance.destroy();
        if (needlestickIncidentsChartInstance) needlestickIncidentsChartInstance.destroy();
        if (needlestickByDepartmentChartInstance) needlestickByDepartmentChartInstance.destroy();
        if (needlestickByPositionChartInstance) needlestickByPositionChartInstance.destroy();
    } finally {
        hideLoading();
    }
}

// Function to set up event listeners for forms and new form buttons (Moved to top-level)
function setupFormEventListeners() {
    const insertionFormElem = document.getElementById('insertionForm');
    if (insertionFormElem) insertionFormElem.addEventListener('submit', handleInsertionFormSubmit);

    const maintenanceFormElem = document.getElementById('maintenanceForm');
    if (maintenanceFormElem) maintenanceFormElem.addEventListener('submit', handleMaintenanceFormSubmit);

    const infectionReportFormElem = document.getElementById('infectionReportForm');
    if (infectionReportFormElem) infectionReportFormElem.addEventListener('submit', handleInfectionReportFormSubmit);

    const needlestickReportFormElem = document.getElementById('needlestickReportForm');
    if (needlestickReportFormElem) needlestickReportFormElem.addEventListener('submit', handleNeedlestickReportFormSubmit);

    const newInsertionFormBtnElem = document.getElementById('newInsertionFormBtn');
    if (newInsertionFormBtnElem) newInsertionFormBtnElem.addEventListener('click', resetInsertionForm);

    const newMaintenanceFormBtnElem = document.getElementById('newMaintenanceFormBtn');
    if (newMaintenanceFormBtnElem) newMaintenanceFormBtnElem.addEventListener('click', resetMaintenanceForm);

    const newInfectionReportBtnElem = document.getElementById('newInfectionReportBtn');
    if (newInfectionReportBtnElem) newInfectionReportBtnElem.addEventListener('click', resetInfectionForm);

    const newNeedlestickReportBtnElem = document.getElementById('newNeedlestickReportBtn');
    if (newNeedlestickReportBtnElem) newNeedlestickReportBtnElem.addEventListener('click', resetNeedlestickForm);
}

// Functions to render form elements (with photo feedback setup)
function renderInsertionFormElements(elementsData = []) {
    const tbody = document.getElementById('insertionElementsTableBody');
    if (!tbody) { console.error("Element #insertionElementsTableBody not found."); return; }
    tbody.innerHTML = '';

    INSERTION_ELEMENTS.forEach((elementDef, index) => {
        const savedElement = elementsData[index] || {};
        const row = document.createElement('tr');
        row.classList.add('hover:bg-gray-50', 'transition-colors', 'duration-150');
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${index + 1}</td>
            <td class="px-6 py-4 text-sm text-gray-500">
                <div class="font-medium">${elementDef.description}</div>
                <div class="text-xs text-gray-400 mt-1">${elementDef.detail}</div>
                <input type="hidden" name="elements_data[${index}][description]" value="${elementDef.description}">
                <input type="hidden" name="elements_data[${index}][detail]" value="${elementDef.detail}">
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <select name="elements_data[${index}][status]" class="block w-full pl-3 pr-10 py-2 text-black border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="Ya" ${savedElement.status === 'Ya' ? 'selected' : ''}>Ya</option>
                    <option value="Tidak" ${savedElement.status === 'Tidak' ? 'selected' : ''}>Tidak</option>
                    <option value="Tidak Dilakukan" ${savedElement.status === 'Tidak Dilakukan' ? 'selected' : ''}>Tidak Dilakukan</option>
                </select>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="text" name="elements_data[${index}][notes]" placeholder="Tambahkan catatan" class="shadow-sm focus:ring-blue-500 text-black focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" value="${savedElement.notes || ''}">
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="file" name="elements_data[${index}][photo]" class="hidden" id="insertion-photo-${index}" accept="image/*">
                <label for="insertion-photo-${index}" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Upload
                </label>
                <div id="photo-info-insertion-${index}" class="mt-1 text-xs text-gray-500 flex items-center space-x-1"></div>
            </td>
        `;
        tbody.appendChild(row);
        setupPhotoInputFeedback('insertion', index, savedElement.photo_path);
    });
}

function renderMaintenanceFormElements(elementsData = []) {
    const tbody = document.getElementById('maintenanceElementsTableBody');
    if (!tbody) { console.error("Element #maintenanceElementsTableBody not found."); return; }
    tbody.innerHTML = '';

    MAINTENANCE_ELEMENTS.forEach((elementDef, index) => {
        const savedElement = elementsData[index] || {};
        const row = document.createElement('tr');
        row.classList.add('hover:bg-gray-50', 'transition-colors', 'duration-150');
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${index + 1}</td>
            <td class="px-6 py-4 text-sm text-gray-500">
                <div class="font-medium">${elementDef.description}</div>
                <div class="text-xs text-gray-400 mt-1">${elementDef.detail}</div>
                <input type="hidden" name="elements_data[${index}][description]" value="${elementDef.description}">
                <input type="hidden" name="elements_data[${index}][detail]" value="${elementDef.detail}">
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <select name="elements_data[${index}][status]" class="block w-full pl-3 pr-10 py-2 text-black border-gray-300 focus:outline-none focus:ring-green-500 text-black focus:border-green-500 sm:text-sm rounded-md">
                    <option value="Ya" ${savedElement.status === 'Ya' ? 'selected' : ''}>Ya</option>
                    <option value="Tidak" ${savedElement.status === 'Tidak' ? 'selected' : ''}>Tidak</option>
                    <option value="Tidak Dilakukan" ${savedElement.status === 'Tidak Dilakukan' ? 'selected' : ''}>Tidak Dilakukan</option>
                </select>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="text" name="elements_data[${index}][notes]" placeholder="Tambahkan catatan" class="shadow-sm focus:ring-green-500 text-black focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md" value="${savedElement.notes || ''}">
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="file" name="elements_data[${index}][photo]" class="hidden" id="maintenance-photo-${index}" accept="image/*">
                <label for="maintenance-photo-${index}" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Upload
                </label>
                <div id="photo-info-maintenance-${index}" class="mt-1 text-xs text-gray-500 flex items-center space-x-1"></div>
            </td>
        `;
        tbody.appendChild(row);
        setupPhotoInputFeedback('maintenance', index, savedElement.photo_path);
    });
}

// Populate and Reset functions for forms
function populateInsertionForm(form) {
    currentActiveInsertionFormId = form.id;
    const formIdElem = document.getElementById('insertionFormId');
    if (formIdElem) formIdElem.value = form.id || ''; // Defensive check
    const patientNameElem = document.getElementById('insertionPatientName');
    if (patientNameElem) patientNameElem.value = form.patient_name || '';
    const medicalRecordNumberElem = document.getElementById('insertionMedicalRecordNumber');
    if (medicalRecordNumberElem) medicalRecordNumberElem.value = form.medical_record_number || '';
    const dateElem = document.getElementById('insertionDate');
    if (dateElem) dateElem.value = form.insertion_date || ''; // Ensure it's not null before setting
    const locationElem = document.getElementById('insertionLocation');
    if (locationElem) locationElem.value = form.insertion_location || '';
    const operatorNameElem = document.getElementById('insertionOperatorName');
    if (operatorNameElem) operatorNameElem.value = form.operator_name || '';
    renderInsertionFormElements(form.elements_data);
}

function resetInsertionForm() {
    const formElem = document.getElementById('insertionForm');
    if (formElem) formElem.reset();
    currentActiveInsertionFormId = null;
    const formIdElem = document.getElementById('insertionFormId');
    if (formIdElem) formIdElem.value = '';
    const dateElem = document.getElementById('insertionDate');
    if (dateElem) dateElem.value = new Date().toISOString().split('T')[0];
    renderInsertionFormElements([]);
}

function populateMaintenanceForm(form) {
    currentActiveMaintenanceFormId = form.id;
    const formIdElem = document.getElementById('maintenanceFormId');
    if (formIdElem) formIdElem.value = form.id || ''; // Defensive check
    const patientNameElem = document.getElementById('maintenancePatientName');
    if (patientNameElem) patientNameElem.value = form.patient_name || '';
    const medicalRecordNumberElem = document.getElementById('maintenanceMedicalRecordNumber');
    if (medicalRecordNumberElem) medicalRecordNumberElem.value = form.medical_record_number || '';
    const dateElem = document.getElementById('maintenanceDate');
    if (dateElem) dateElem.value = form.maintenance_date || ''; // Defensive check
    const locationElem = document.getElementById('maintenanceLocation');
    if (locationElem) locationElem.value = form.maintenance_location || ''; // Handle new field
    const daysInsertedElem = document.getElementById('maintenanceDaysInserted');
    if (daysInsertedElem) daysInsertedElem.value = form.days_inserted || ''; // Handle new field
    const nurseNameElem = document.getElementById('maintenanceNurseName'); // Correct ID for operator name
    if (nurseNameElem) nurseNameElem.value = form.nurse_name || ''; // Handle new/corrected field
    renderMaintenanceFormElements(form.elements_data);
}

function resetMaintenanceForm() {
    const formElem = document.getElementById('maintenanceForm');
    if (formElem) formElem.reset();
    currentActiveMaintenanceFormId = null;
    const formIdElem = document.getElementById('maintenanceFormId');
    if (formIdElem) formIdElem.value = '';
    const dateElem = document.getElementById('maintenanceDate');
    if (dateElem) dateElem.value = new Date().toISOString().split('T')[0];
    renderMaintenanceFormElements([]);
}

function populateInfectionReportForm(report = {}) {
    currentActiveInfectionReportId = report.id;
    // Defensive checks for all elements
    const reportIdElem = document.getElementById('infectionReportId');
    if (reportIdElem) reportIdElem.value = report.id || '';
    const patientNameElem = document.getElementById('infectionPatientName');
    if (patientNameElem) patientNameElem.value = report.patient_name || '';
    const medicalRecordNumberElem = document.getElementById('infectionMedicalRecordNumber');
    if (medicalRecordNumberElem) medicalRecordNumberElem.value = report.medical_record_number || '';
    const infectionDateElem = document.getElementById('infectionDate');
    if (infectionDateElem) infectionDateElem.value = report.infection_diagnosis_date || ''; // Maps to diagnosis date
    const infectionTypeElem = document.getElementById('infectionType');
    if (infectionTypeElem) infectionTypeElem.value = report.infection_type || '';
    const infectionLocationElem = document.getElementById('infectionLocation');
    if (infectionLocationElem) infectionLocationElem.value = report.insertion_location || '';
    const infectionDaysInsertedElem = document.getElementById('infectionDaysInserted');
    if (infectionDaysInsertedElem) infectionDaysInsertedElem.value = report.days_inserted || ''; // Handle new field
    const infectionSymptomsElem = document.getElementById('infectionSymptoms');
    if (infectionSymptomsElem) infectionSymptomsElem.value = report.clinical_symptoms || '';
    const infectionLabResultsElem = document.getElementById('infectionLabResults');
    if (infectionLabResultsElem) infectionLabResultsElem.value = report.microorganism || '';
    const infectionTreatmentElem = document.getElementById('infectionTreatment');
    if (infectionTreatmentElem) infectionTreatmentElem.value = report.management || '';

    const photoPreview = document.getElementById('infectionPhotoPreview');
    const photoPlaceholder = document.getElementById('infectionPhotoPlaceholder');
    const removePhotoButton = document.getElementById('removeInfectionPhoto');
    const fileInput = document.getElementById('infectionFileUpload');

    if (photoPreview && photoPlaceholder && removePhotoButton && fileInput) {
        if (report.photo_path) {
            photoPreview.src = report.photo_path;
            photoPreview.classList.remove('hidden');
            photoPlaceholder.classList.add('hidden');
            removePhotoButton.classList.remove('hidden');
        } else {
            photoPreview.classList.add('hidden');
            photoPlaceholder.classList.remove('hidden');
            removePhotoButton.classList.add('hidden');
            photoPreview.src = '#';
        }
        fileInput.value = '';
    }
}

function resetInfectionForm() {
    const formElem = document.getElementById('infectionReportForm');
    if (formElem) formElem.reset();
    currentActiveInfectionReportId = null;
    const reportIdElem = document.getElementById('infectionReportId');
    if (reportIdElem) reportIdElem.value = '';
    const fileUploadElem = document.getElementById('infectionFileUpload');
    if (fileUploadElem) fileUploadElem.value = '';

    const photoPreview = document.getElementById('infectionPhotoPreview');
    const photoPlaceholder = document.getElementById('infectionPhotoPlaceholder');
    const removePhotoButton = document.getElementById('removeInfectionPhoto');

    if (photoPreview && photoPlaceholder && removePhotoButton) {
        photoPreview.classList.add('hidden');
        photoPlaceholder.classList.remove('hidden');
        removePhotoButton.classList.add('hidden');
        photoPreview.src = '#';
    }
}

function populateNeedlestickReportForm(report = {}) {
    currentActiveNeedlestickReportId = report.id;
    // Defensive checks for all elements
    const reportIdElem = document.getElementById('needlestickReportId');
    if (reportIdElem) reportIdElem.value = report.id || '';
    const needlestickDateElem = document.getElementById('needlestickDate');
    if (needlestickDateElem) needlestickDateElem.value = report.incident_date || '';
    const needlestickTimeElem = document.getElementById('needlestickTime');
    if (needlestickTimeElem) needlestickTimeElem.value = report.incident_time || '';
    const needlestickLocationElem = document.getElementById('needlestickLocation');
    if (needlestickLocationElem) needlestickLocationElem.value = report.location || '';
    const needlestickDepartmentElem = document.getElementById('needlestickDepartment');
    if (needlestickDepartmentElem) needlestickDepartmentElem.value = report.department || '';
    const injuredPersonNameElem = document.getElementById('injuredPersonName');
    if (injuredPersonNameElem) injuredPersonNameElem.value = report.injured_person_name || '';
    const injuredPersonPositionElem = document.getElementById('injuredPersonPosition');
    if (injuredPersonPositionElem) injuredPersonPositionElem.value = report.injured_person_position || '';
    const injuredPersonAgeElem = document.getElementById('injuredPersonAge');
    if (injuredPersonAgeElem) injuredPersonAgeElem.value = report.injured_person_age || '';
    const injuredPersonGenderElem = document.getElementById('injuredPersonGender');
    if (injuredPersonGenderElem) injuredPersonGenderElem.value = report.injured_person_gender || '';
    const incidentDescriptionElem = document.getElementById('incidentDescription');
    if (incidentDescriptionElem) incidentDescriptionElem.value = report.incident_description || '';
    const sourcePatientStatusElem = document.getElementById('sourcePatientStatus');
    if (sourcePatientStatusElem) sourcePatientStatusElem.value = report.source_patient_status || '';
    const followUpActionsElem = document.getElementById('followUpActions');
    if (followUpActionsElem) followUpActionsElem.value = report.follow_up_actions || '';

    // Handle immediate_actions checkboxes and "Lainnya" input
    document.querySelectorAll('#needlestickReportForm input[name="immediate_actions[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });

    const otherImmediateActionCheckbox = document.getElementById('immediateAction4');
    const otherImmediateActionInput = document.getElementById('otherImmediateAction');
    if (otherImmediateActionInput) { // Defensive check for otherImmediateActionInput
        otherImmediateActionInput.classList.add('hidden');
        otherImmediateActionInput.removeAttribute('required');
        otherImmediateActionInput.value = '';
    }


    if (report.immediate_actions && Array.isArray(report.immediate_actions)) {
        report.immediate_actions.forEach(action => {
            let matched = false;
            document.querySelectorAll('#needlestickReportForm input[name="immediate_actions[]"]').forEach(checkbox => {
                if (checkbox.value === action) {
                    checkbox.checked = true;
                    matched = true;
                }
            });
            if (!matched && !['Cuci luka dengan air mengalir dan sabun', 'Peras darah dari luka', 'Berikan antiseptik', 'Lainnya'].includes(action)) {
                if (otherImmediateActionCheckbox) otherImmediateActionCheckbox.checked = true; // Defensive check
                if (otherImmediateActionInput) { // Defensive check
                    otherImmediateActionInput.classList.remove('hidden');
                    otherImmediateActionInput.setAttribute('required', 'true');
                    otherImmediateActionInput.value = action;
                }
            }
        });
    }

    const photoPreview = document.getElementById('needlestickPhotoPreview');
    const photoPlaceholder = document.getElementById('needlestickPhotoPlaceholder');
    const removePhotoButton = document.getElementById('removeNeedlestickPhoto');
    const fileInput = document.getElementById('needlestickFileUpload');

    if (photoPreview && photoPlaceholder && removePhotoButton && fileInput) {
        if (report.photo_path) {
            photoPreview.src = report.photo_path;
            photoPreview.classList.remove('hidden');
            photoPlaceholder.classList.add('hidden');
            removePhotoButton.classList.remove('hidden');
        } else {
            photoPreview.classList.add('hidden');
            photoPlaceholder.classList.remove('hidden');
            removePhotoButton.classList.add('hidden');
            photoPreview.src = '#';
        }
        fileInput.value = '';
    }
}

window.resetNeedlestickForm = function() {
    const formElem = document.getElementById('needlestickReportForm');
    if (formElem) formElem.reset();
    currentActiveNeedlestickReportId = null;
    const reportIdElem = document.getElementById('needlestickReportId');
    if (reportIdElem) reportIdElem.value = '';
    const fileUploadElem = document.getElementById('needlestickFileUpload');
    if (fileUploadElem) fileUploadElem.value = '';

    const photoPreview = document.getElementById('needlestickPhotoPreview');
    const photoPlaceholder = document.getElementById('needlestickPhotoPlaceholder');
    const removePhotoButton = document.getElementById('removeNeedlestickPhoto');
    if (photoPreview && photoPlaceholder && removePhotoButton) {
        photoPreview.classList.add('hidden');
        photoPlaceholder.classList.remove('hidden');
        removePhotoButton.classList.add('hidden');
        photoPreview.src = '#';
    }

    const otherImmediateActionInput = document.getElementById('otherImmediateAction');
    const immediateAction4Checkbox = document.getElementById('immediateAction4');
    if (otherImmediateActionInput) { // Defensive check
        otherImmediateActionInput.classList.add('hidden');
        otherImmediateActionInput.removeAttribute('required');
        otherImmediateActionInput.value = '';
    }
    if (immediateAction4Checkbox) { // Defensive check
        immediateAction4Checkbox.checked = false;
    }
};

// --- Form Submission Handlers ---
async function handleInsertionFormSubmit(event) {
    event.preventDefault();
    const formId = currentActiveInsertionFormId;
    const method = formId ? 'POST' : 'POST';
    const endpoint = formId ? `cvc-insertions/${formId}` : 'cvc-insertions';
    const formData = new FormData(document.getElementById('insertionForm'));
    if (formId) formData.append('_method', 'PUT');

    try {
        const result = await apiCall(endpoint, method, formData, true);
        showToast(result.message, 'success');
        await loadDashboardStats(true);
        resetInsertionForm();
        await loadInsertionHistory();
    } catch (error) {
        console.error('Error submitting insertion form:', error);
    }
}

async function handleMaintenanceFormSubmit(event) {
    event.preventDefault();
    const formId = currentActiveMaintenanceFormId;
    const method = formId ? 'POST' : 'POST';
    const endpoint = formId ? `cvc-maintenances/${formId}` : 'cvc-maintenances';
    const formData = new FormData(document.getElementById('maintenanceForm'));
    if (formId) formData.append('_method', 'PUT');

    try {
        const result = await apiCall(endpoint, method, formData, true);
        showToast(result.message, 'success');
        await loadDashboardStats(true);
        resetMaintenanceForm();
        await loadMaintenanceHistory();
    } catch (error) {
        console.error('Error submitting maintenance form:', error);
    }
}

async function handleInfectionReportFormSubmit(event) {
    event.preventDefault();
    const reportId = currentActiveInfectionReportId;
    const method = reportId ? 'POST' : 'POST';
    const endpoint = reportId ? `cvc-infections/${reportId}` : 'cvc-infections';
    const formData = new FormData(document.getElementById('infectionReportForm'));
    if (reportId) formData.append('_method', 'PUT');

    try {
        const result = await apiCall(endpoint, method, formData, true);
        showToast(result.message, 'success');
        await loadDashboardStats(true);
        resetInfectionForm();
        await loadInfectionHistory();
    } catch (error) {
        console.error('Error submitting infection report:', error);
    }
}

async function handleNeedlestickReportFormSubmit(event) {
    event.preventDefault();
    const reportId = currentActiveNeedlestickReportId;
    const method = reportId ? 'POST' : 'POST';
    const endpoint = reportId ? `needlestick-reports/${reportId}` : 'needlestick-reports';
    const formData = new FormData(document.getElementById('needlestickReportForm'));
    if (reportId) formData.append('_method', 'PUT');
    
    try {
        const result = await apiCall(endpoint, method, formData, true);
        showToast(result.message, 'success');
        await loadDashboardStats(true);
        resetNeedlestickForm();
        await loadNeedlestickHistory();
    } catch (error) {
        console.error('Error submitting needlestick report:', error);
    }
}


// --- History Table Loading and Pagination ---
function renderInsertionHistoryTable(data) {
    const tbody = document.getElementById('insertionHistoryTableBody');
    if (!tbody) { console.error("Element #insertionHistoryTableBody not found."); return; }
    tbody.innerHTML = '';
    if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-4 text-center text-black">Tidak ada riwayat insersi.</td></tr>`;
        return;
    }
    data.forEach(form => {
        const row = document.createElement('tr');
        row.classList.add('hover:bg-gray-50', 'transition-colors', 'duration-150');
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(form.insertion_date)}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${form.patient_name}</div>
                <div class="text-sm text-gray-500">RM ${form.medical_record_number || '-'}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${form.insertion_location || '-'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${form.operator_name || '-'}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getComplianceBadgeClass(form.compliance_percentage)}">
                    ${form.compliance_percentage}%
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDateTime(form.created_at)}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button class="text-blue-600 hover:text-blue-900 mr-3 detail-button" data-type="insertion" data-id="${form.id}">Detail</button>
                <button class="text-red-600 hover:text-red-900 delete-button" data-type="insertion" data-id="${form.id}">Hapus</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}
window.showInsertionDetailModal = async function(formId) {
    try {
        const form = await apiCall(`cvc-insertions/${formId}`);
        const modalContent = document.getElementById('modalContent');
        const modalTitle = document.getElementById('modalTitle');
        if (!modalContent || !modalTitle) { console.error("Modal elements not found."); return; }
        modalTitle.textContent = 'Detail Form Insersi CVC';
        modalContent.innerHTML = `
            <h4 class="text-lg font-bold mb-2">Detail Form Insersi CVC</h4>
            <div class="space-y-2 text-gray-700 mb-4">
                <p><strong>Nama Pasien:</strong> ${form.patient_name || '-'}</p>
                <p><strong>Nomor RM:</strong> ${form.medical_record_number || '-'}</p>
                <p><strong>Tanggal Insersi:</strong> ${formatDate(form.insertion_date)}</p>
                <p><strong>Lokasi Insersi:</strong> ${form.insertion_location || '-'}</p>
                <p><strong>Operator:</strong> ${form.operator_name || '-'}</p>
                <p><strong>Kepatuhan:</strong> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getComplianceBadgeClass(form.compliance_percentage)}">${form.compliance_percentage}%</span></p>
                <p><strong>Dibuat Pada:</strong> ${formatDateTime(form.created_at)}</p>
            </div>
            <h5 class="text-md font-bold mb-2">Elemen Observasi:</h5>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Element</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Foto</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        ${form.elements_data.map((element, idx) => `
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">${idx + 1}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">${element.description || 'N/A'}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">${element.status || '-'}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">${element.notes || '-'}</td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    ${element.photo_path ? `<button onclick="openPhotoModal('${element.photo_path}')" class="text-blue-500 hover:underline">Lihat</button>` : '-'}
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <button onclick="editFormFromModal('insertion', ${form.id})" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 mr-2">Edit</button>
            </div>
        `;
        window.showDetailModal('detailModal');
    } catch (error) {
        console.error('Error showing insertion detail modal:', error);
    }
};


async function loadInsertionHistory(page = 1) {
    showLoading();
    try {
        const response = await apiCall(`cvc-insertions?page=${page}`);
        renderInsertionHistoryTable(response.data);
        renderPagination(response.links, response.meta, 'insertion');
    } catch (error) {
        console.error('Error loading insertion history:', error);
    } finally {
        hideLoading();
    }
}

function renderMaintenanceHistoryTable(data) {
    const tbody = document.getElementById('maintenanceHistoryTableBody');
    if (!tbody) { console.error("Element #maintenanceHistoryTableBody not found."); return; }
    tbody.innerHTML = '';
    if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="px-6 py-4 text-center text-black">Tidak ada riwayat maintenance.</td></tr>`; // Corrected colspan to 8
        return;
    }
    data.forEach(form => {
        const row = document.createElement('tr');
        row.classList.add('hover:bg-gray-50', 'transition-colors', 'duration-150');
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(form.maintenance_date)}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${form.patient_name}</div>
                <div class="text-sm text-gray-500">RM ${form.medical_record_number || '-'}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${form.maintenance_location || '-'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${form.days_inserted || '-'} Hari Ke-</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${form.nurse_name || '-'}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getComplianceBadgeClass(form.compliance_percentage)}">
                    ${form.compliance_percentage}%
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDateTime(form.created_at)}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button data-type="maintenance" data-id="${form.id}" class="detail-button text-blue-600 hover:text-blue-900 mr-3">Detail</button>
                <button data-type="maintenance" class="text-red-600 hover:text-red-900 delete-button" data-id="${form.id}">Hapus</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}
window.showMaintenanceDetailModal = async function(formId) {
    try {
        const form = await apiCall(`cvc-maintenances/${formId}`);
        const modalContent = document.getElementById('modalContent');
        const modalTitle = document.getElementById('modalTitle');
        if (!modalContent || !modalTitle) { console.error("Modal elements not found."); return; }
        modalTitle.textContent = 'Detail Form Maintenance CVC';
        modalContent.innerHTML = `
            <h4 class="text-lg font-bold mb-2">Detail Form Maintenance CVC</h4>
            <div class="space-y-2 text-gray-700 mb-4">
                <p><strong>Nama Pasien:</strong> ${form.patient_name || '-'}</p>
                <p><strong>Nomor RM:</strong> ${form.medical_record_number || '-'}</p>
                <p><strong>Tanggal Observasi:</strong> ${formatDate(form.maintenance_date)}</p>
                <p><strong>Lokasi CVC:</strong> ${form.maintenance_location || '-'}</p>
                <p><strong>Hari Ke- (Setelah Insersi):</strong> ${form.days_inserted || '-'}</p>
                <p><strong>Nama Operator (Dokter/Perawat):</strong> ${form.nurse_name || '-'}</p>
                <p><strong>Kepatuhan:</strong> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getComplianceBadgeClass(form.compliance_percentage)}">${form.compliance_percentage}%</span></p>
                <p><strong>Dibuat Pada:</strong> ${formatDateTime(form.created_at)}</p>
            </div>
            <h5 class="text-md font-bold mb-2">Elemen Observasi:</h5>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Element</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Foto</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        ${form.elements_data.map((element, idx) => `
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">${idx + 1}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">${element.description || 'N/A'}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">${element.status || '-'}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">${element.notes || '-'}</td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    ${element.photo_path ? `<button onclick="openPhotoModal('${element.photo_path}')" class="text-blue-500 hover:underline">Lihat</button>` : '-'}
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <button onclick="editFormFromModal('maintenance', ${form.id})" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 mr-2">Edit</button>
            </div>
        `;
        window.showDetailModal('detailModal');
    } catch (error) {
        console.error('Error showing maintenance detail modal:', error);
    }
};


async function loadMaintenanceHistory(page = 1) {
    showLoading();
    try {
        const response = await apiCall(`cvc-maintenances?page=${page}`);
        renderMaintenanceHistoryTable(response.data);
        renderPagination(response.links, response.meta, 'maintenance');
    }
    catch (error) {
        console.error('Error loading maintenance history:', error);
    } finally {
        hideLoading();
    }
}

function renderInfectionHistoryTable(data) {
    const tbody = document.getElementById('infectionHistoryTableBody');
    if (!tbody) { console.error("Element #infectionHistoryTableBody not found."); return; }
    tbody.innerHTML = '';
    if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="px-6 py-4 text-center text-black">Tidak ada riwayat infeksi.</td></tr>`; // Corrected colspan to 8
        return;
    }
    data.forEach(report => {
        const row = document.createElement('tr');
        row.classList.add('hover:bg-gray-50', 'transition-colors', 'duration-150');
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(report.infection_diagnosis_date)}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${report.patient_name}</div>
                <div class="text-sm text-gray-500">RM ${report.medical_record_number || '-'}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${report.infection_type || '-'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${report.insertion_location || '-'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${report.days_inserted || '-'} Hari Ke-</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDateTime(report.created_at)}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button class="text-blue-600 hover:text-blue-900 mr-3 detail-button" data-type="infection" data-id="${report.id}">Detail</button>
                <button data-type="infection" data-id="${report.id}" class="text-red-600 hover:text-red-900 delete-button">Hapus</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

async function loadInfectionHistory(page = 1) {
    showLoading();
    try {
        const response = await apiCall(`cvc-infections?page=${page}`);
        renderInfectionHistoryTable(response.data);
        renderPagination(response.links, response.meta, 'infection');
    } catch (error) {
        console.error('Error loading infection history:', error);
    } finally {
        hideLoading();
    }
}

window.showInfectionDetailModal = async function(reportId) {
    try {
        const report = await apiCall(`cvc-infections/${reportId}`);
        const modalContent = document.getElementById('modalContent');
        const modalTitle = document.getElementById('modalTitle');
        if (!modalContent || !modalTitle) { console.error("Modal elements not found."); return; }
        modalTitle.textContent = 'Detail Laporan Infeksi Terkait CVC';
        modalContent.innerHTML = `
            <h4 class="text-lg font-bold mb-2">Detail Laporan Infeksi Terkait CVC</h4>
            <div class="space-y-2 text-gray-700 mb-4">
                <p><strong>Nama Pasien:</strong> ${report.patient_name || '-'}</p>
                <p><strong>Nomor RM:</strong> ${report.medical_record_number || '-'}</p>
                <p><strong>Tanggal Insersi CVC:</strong> ${formatDate(report.insertion_date || 'N/A')}</p>
                <p><strong>Lokasi Insersi:</strong> ${report.insertion_location || '-'}</p>
                <p><strong>Hari Ke- (Setelah Insersi):</strong> ${report.days_inserted || '-'}</p>
                <p><strong>Tanggal Diagnosis Infeksi:</strong> ${formatDate(report.infection_diagnosis_date)}</p>
                <p><strong>Jenis Infeksi:</strong> ${report.infection_type || '-'}</p>
                <p><strong>Gejala Klinis:</strong> ${report.clinical_symptoms || '-'}</p>
                <p><strong>Mikroorganisme:</strong> ${report.microorganism || '-'}</p>
                <p><strong>Penanganan:</strong> ${report.management || '-'}</p>
                ${report.photo_path ? `<p><strong>Foto Dokumentasi:</strong> <button onclick="openPhotoModal('${report.photo_path}')" class="text-blue-500 hover:underline mt-2">Lihat Foto</button></p>` : ''}
                <p><strong>Dibuat Pada:</strong> ${formatDateTime(report.created_at)}</p>
                <p><strong>Terakhir Diperbarui:</strong> ${formatDateTime(report.updated_at)}</p>
            </div>
            <div class="mt-4 text-right">
                <button onclick="editFormFromModal('infection', ${report.id})" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 mr-2">Edit</button>
            </div>
        `;
        window.showDetailModal('detailModal');
    } catch (error) {
        console.error('Error showing infection detail modal:', error);
    }
};



function renderNeedlestickHistoryTable(data) {
    const tbody = document.getElementById('needlestickHistoryTableBody');
    if (!tbody) { console.error("Element #needlestickHistoryTableBody not found."); return; }
    tbody.innerHTML = '';
    if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-4 text-center text-black">Tidak ada riwayat pelaporan tertusuk jarum.</td></tr>`;
        return;
    }
    data.forEach(report => {
        const row = document.createElement('tr');
        row.classList.add('hover:bg-gray-50', 'transition-colors', 'duration-150');
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(report.incident_date)}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${report.injured_person_name || '-'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${report.location || '-'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${report.department || '-'}</td>
            <td class="px-6 py-4 text-sm text-gray-500">${(report.immediate_actions && Array.isArray(report.immediate_actions)) ? report.immediate_actions.join(', ') : '-'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDateTime(report.created_at)}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button class="text-blue-600 hover:text-blue-900 mr-3 detail-button" data-type="needlestick" data-id="${report.id}">Detail</button>
                <button class="text-red-600 hover:text-red-900 delete-button" data-type="needlestick" data-id="${report.id}">Hapus</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

async function loadNeedlestickHistory(page = 1) {
    showLoading();
    try {
        const response = await apiCall(`needlestick-reports?page=${page}`);
        renderNeedlestickHistoryTable(response.data);
        renderPagination(response.links, response.meta, 'needlestick');
    } catch (error) {
        console.error('Error loading needlestick history:', error);
    } finally {
        hideLoading();
    }
}

window.showNeedlestickDetailModal = async function(reportId) {
    try {
        const report = await apiCall(`needlestick-reports/${reportId}`);
        const modalContent = document.getElementById('modalContent');
        const modalTitle = document.getElementById('modalTitle');
        if (!modalContent || !modalTitle) { console.error("Modal elements not found."); return; }
        modalTitle.textContent = 'Detail Pelaporan Tertusuk Jarum';
        modalContent.innerHTML = `
            <h4 class="text-lg font-bold mb-2">Detail Pelaporan Tertusuk Jarum</h4>
            <div class="space-y-2 text-gray-700 mb-4">
                <p><strong>Tanggal Kejadian:</strong> ${formatDate(report.incident_date)}</p>
                <p><strong>Waktu Kejadian:</strong> ${report.incident_time || '-'}</p>
                <p><strong>Lokasi Kejadian:</strong> ${report.location || '-'}</p>
                <p><strong>Unit/Bagian:</strong> ${report.department || '-'}</p>
                <p><strong>Nama yang Tertusuk:</strong> ${report.injured_person_name || '-'}</p>
                <p><strong>Jabatan:</strong> ${report.injured_person_position || '-'}</p>
                <p><strong>Usia:</strong> ${report.injured_person_age || '-'} tahun</p>
                <p><strong>Jenis Kelamin:</strong> ${report.injured_person_gender || '-'}</p>
                <p><strong>Deskripsi Kejadian:</strong> ${report.incident_description || '-'}</p>
                <p><strong>Status Pasien Sumber:</strong> ${report.source_patient_status || '-'}</p>
                <p><strong>Tindakan Segera yang Dilakukan:</strong> ${ (report.immediate_actions && Array.isArray(report.immediate_actions)) ? report.immediate_actions.join(', ') : '-' }</p>
                <p><strong>Tindak Lanjut:</strong> ${report.follow_up_actions || '-'}</p>
                ${report.photo_path ? `<p><strong>Foto Dokumentasi:</strong> <button onclick="openPhotoModal('${report.photo_path}')" class="text-blue-500 hover:underline mt-2">Lihat Foto</button></p>` : ''}
                <p><strong>Dibuat Pada:</strong> ${formatDateTime(report.created_at)}</p>
                <p><strong>Terakhir Diperbarui:</strong> ${formatDateTime(report.updated_at)}</p>
            </div>
            <div class="mt-4 text-right">
                <button onclick="editFormFromModal('needlestick', ${report.id})" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 mr-2">Edit</button>
            </div>
        `;
        window.showDetailModal('detailModal');
    } catch (error) {
        console.error('Error showing needlestick detail modal:', error)
    }
};



function renderPagination(links, meta, section) {
    const paginationContainer = document.getElementById(`${section}Pagination`);
    if (!paginationContainer) { console.error(`Element #${section}Pagination not found.`); return; }
    paginationContainer.innerHTML = '';

    if (!meta || meta.last_page <= 1) return;

    const prevButton = document.createElement('a');
    prevButton.href = '#';
    prevButton.classList.add('relative', 'inline-flex', 'items-center', 'px-2', 'py-2', 'rounded-l-md', 'border', 'border-gray-300', 'bg-white', 'text-sm', 'font-medium', 'text-gray-500', 'hover:bg-gray-50');
    prevButton.innerHTML = `<span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>`;
    if (!links.prev) {
        prevButton.classList.add('opacity-50', 'cursor-not-allowed');
        prevButton.setAttribute('disabled', 'true');
    } else {
        prevButton.onclick = (e) => { e.preventDefault(); window[`load${capitalizeFirstLetter(section)}History`](meta.current_page - 1); };
    }
    paginationContainer.appendChild(prevButton);

    meta.links.forEach(link => {
        if (link.url && link.label !== '&laquo; Previous' && link.label !== 'Next &raquo;') {
            const pageLink = document.createElement('a');
            pageLink.href = '#';
            pageLink.classList.add('relative', 'inline-flex', 'items-center', 'px-4', 'py-2', 'border', 'border-gray-300', 'bg-white', 'text-sm', 'font-medium', 'text-gray-700', 'hover:bg-gray-50');
            if (link.active) {
                if (section === 'insertion') pageLink.classList.add('z-10', 'bg-blue-50', 'border-blue-500', 'text-blue-600', 'active');
                if (section === 'maintenance') pageLink.classList.add('z-10', 'bg-purple-50', 'border-purple-500', 'text-purple-600', 'active');
                if (section === 'infection') pageLink.classList.add('z-10', 'bg-red-50', 'border-red-500', 'text-red-600', 'active');
                if (section === 'needlestick') pageLink.classList.add('z-10', 'bg-emerald-50', 'border-emerald-500', 'text-emerald-600', 'active');
            }
            pageLink.textContent = link.label;
            pageLink.onclick = (e) => { e.preventDefault(); window[`load${capitalizeFirstLetter(section)}History`](parseInt(link.label)); };
            paginationContainer.appendChild(pageLink);
        }
    });

    const nextButton = document.createElement('a');
    nextButton.href = '#';
    nextButton.classList.add('relative', 'inline-flex', 'items-center', 'px-2', 'py-2', 'rounded-r-md', 'border', 'border-gray-300', 'bg-white', 'text-sm', 'font-medium', 'text-gray-500', 'hover:bg-gray-50');
    nextButton.innerHTML = `<span class="sr-only">Next</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>`;
    if (!links.next) {
        nextButton.classList.add('opacity-50', 'cursor-not-allowed');
        nextButton.setAttribute('disabled', 'true');
    } else {
        nextButton.onclick = (e) => { e.preventDefault(); window[`load${capitalizeFirstLetter(section)}History`](meta.current_page + 1); };
    }
    paginationContainer.appendChild(nextButton);
}