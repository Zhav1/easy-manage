// --- Global Variables (always at the very top of your ppi.js file) ---
const API_BASE_URL = '/api/v1';
let currentUserToken = null;
let currentActiveInsertionFormId = null;
let currentActiveMaintenanceFormId = null;
let currentActiveInfectionReportId = null;

// Chart instances for destruction and re-creation
let infectionIncidentChartInstance = null;
let infectionLocationChartInstance = null;
let microorganismChartInstance = null;

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

// --- Utility Functions (Declared as top-level functions to ensure availability) ---
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
    if (!currentUserToken) {
        console.error('Authentication token is not available. Redirecting to login.');
        window.location.href = '/login';
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
            headers: isFormData ? { 'Authorization': `Bearer ${currentUserToken}` } : headers,
            body: body,
        });

        if (response.status === 204) {
            return null;
        }

        const responseData = await response.json();

        if (!response.ok) {
            console.error('API Error:', responseData.message || response.statusText, responseData.errors);
            throw new Error(responseData.message || 'Something went wrong with the API call.');
        }

        return responseData;
    } catch (error) {
        console.error('Fetch Error:', error);
        alert(`Error: ${error.message}`);
        throw error;
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

// --- Detail & Photo Modals Functions (Publicly exposed on window scope for HTML onclick) ---

// Generic close function for all modals
window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    // Clear content specific to detail modal body
    if (modalId === 'detailModal') {
        const detailModalBody = document.getElementById('detailModalBody');
        if (detailModalBody) detailModalBody.innerHTML = '';
    }
    // Clear content specific to photo modal body
    if (modalId === 'photoModal') {
        const photoModalBody = document.getElementById('photoModalBody');
        if (photoModalBody) photoModalBody.innerHTML = '';
    }
};

// Opens the main detail modal
window.showDetailModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
};

// Opens the photo modal
window.openPhotoModal = function(photoPath) {
    const photoModalBody = document.getElementById('photoModalBody');
    if (photoModalBody) {
        photoModalBody.innerHTML = `<img src="${photoPath}" alt="Dokumentasi Foto" class="max-w-full max-h-full object-contain mx-auto">`;
    }
    window.showDetailModal('photoModal');
};

// Function to switch to edit mode from detail modal
window.editFormFromModal = async function(formType, id) {
    window.closeModal('detailModal'); // Close the detail modal first

    try {
        const data = await apiCall(`cvc-${formType}s/${id}`);
        if (formType === 'insertion') {
            populateInsertionForm(data);
            window.switchTab('insersi', 'insersi-form');
        } else if (formType === 'maintenance') {
            populateMaintenanceForm(data);
            window.switchTab('maintenance', 'maintenance-form');
        } else if (formType === 'infection') {
            populateInfectionReportForm(data);
            window.switchTab('infeksi', 'infeksi-form');
        }
    } catch (error) {
        console.error(`Error loading ${formType} form for edit:`, error);
        alert(`Gagal memuat form ${formType} untuk diedit.`);
    }
};

// --- Photo Upload Feedback Logic ---
function setupPhotoInputFeedback(formType, index, existingPhotoPath = null) {
    const fileInput = document.getElementById(`${formType}-photo-${index}`);
    const label = fileInput?.nextElementSibling; // The <label> element
    const container = label?.closest('td'); // The <td> containing the input and label

    if (!fileInput || !label || !container) {
        return; // Exit if essential elements are not found
    }

    const currentPhotoInfoDivId = `photo-info-${formType}-${index}`;
    let currentPhotoInfoDiv = document.getElementById(currentPhotoInfoDivId);
    if (!currentPhotoInfoDiv) {
        currentPhotoInfoDiv = document.createElement('div');
        currentPhotoInfoDiv.id = currentPhotoInfoDivId;
        currentPhotoInfoDiv.classList.add('mt-1', 'text-xs', 'text-gray-500', 'flex', 'items-center', 'space-x-1');
        container.appendChild(currentPhotoInfoDiv);
    } else {
        currentPhotoInfoDiv.innerHTML = ''; // Clear previous content
    }

    const updateFeedback = (fileName, pathForView = null) => {
        let displayFileName = fileName;
        if (fileName.length > 15) { // Truncate long names for display
            displayFileName = fileName.substring(0, 12) + '...';
        }

        currentPhotoInfoDiv.innerHTML = `
            <span class="mr-1">${displayFileName}</span>
            ${pathForView ? `<button type="button" class="text-blue-500 hover:underline" onclick="openPhotoModal('${pathForView}')">Lihat</button>` : ''}
            <button type="button" class="text-red-500 hover:text-red-700" onclick="removePhoto('${formType}', ${index})">x</button>
            <input type="hidden" name="elements_data[${index}][photo_path_removed]" value="false"> `;
        // Re-append a hidden input to preserve the existing path if no new file is uploaded
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
        currentPhotoInfoDiv.innerHTML = ''; // Ensure no feedback if no file
    }

    // Clear previous onchange to prevent multiple listeners
    fileInput.onchange = null;
    fileInput.onchange = function() {
        if (this.files && this.files[0]) {
            updateFeedback(this.files[0].name);
        } else {
            // If file input manually cleared (e.g. by user interaction), hide feedback
            currentPhotoInfoDiv.innerHTML = '';
            // And also ensure the removal flag is set for backend processing if it's an existing file
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

// --- Photo Preview for Infection Report Form ---
function setupInfectionFormPhotoPreview() {
    const fileInput = document.getElementById('infectionFileUpload');
    const photoPreview = document.getElementById('infectionPhotoPreview');
    const photoPlaceholder = document.getElementById('infectionPhotoPlaceholder');
    const removePhotoButton = document.getElementById('removeInfectionPhoto');

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
            const fileInputElem = document.getElementById('infectionFileUpload');
            if (fileInputElem) fileInputElem.value = '';
            if (photoPreview) photoPreview.src = '#';
            if (photoPreview) photoPreview.classList.add('hidden');
            if (photoPlaceholder) photoPlaceholder.classList.remove('hidden');
            if (removePhotoButton) removePhotoButton.classList.add('hidden');
        });
    }
}

// Modified removePhoto function to trigger file input reset and UI update
window.removePhoto = function(formType, index) {
    const fileInput = document.getElementById(`${formType}-photo-${index}`);
    const currentPhotoInfoDiv = document.getElementById(`photo-info-${formType}-${index}`);
    const container = document.getElementById(`${formType}-photo-${index}`)?.closest('td');

    if (fileInput) fileInput.value = ''; // Clear the file input programmatically
    if (currentPhotoInfoDiv) currentPhotoInfoDiv.innerHTML = ''; // Clear the feedback display

    // Set a hidden input to signal backend for deletion
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


// --- Main Application Flow and Event Handlers (Declared as top-level functions) ---

// This function runs when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', async function() {
    currentUserToken = window.authToken;
    if (!currentUserToken) {
        console.error('Authentication token is not available. Redirecting to login.');
        window.location.href = '/login';
        return;
    }

    initTabs();
    await loadDashboardStats();
    setupFormEventListeners();
    setupInfectionFormPhotoPreview();

    resetInsertionForm();
    resetMaintenanceForm();
    resetInfectionForm();

    await loadInsertionHistory();
    await loadMaintenanceHistory();
    await loadInfectionHistory();

    // Setup modal close events for the generic detail modal
    const closeDetailModalButton = document.getElementById('closeDetailModal');
    if (closeDetailModalButton) {
        closeDetailModalButton.addEventListener('click', () => window.closeModal('detailModal'));
    }
    const detailModal = document.getElementById('detailModal');
    if (detailModal) {
        detailModal.addEventListener('click', function(e) {
            if (e.target === this) {
                window.closeModal('detailModal');
            }
        });
    }

    // Setup modal close events for the dedicated photo modal
    const closePhotoModalButton = document.getElementById('closePhotoModal');
    if (closePhotoModalButton) {
        closePhotoModalButton.addEventListener('click', () => window.closeModal('photoModal'));
    }
    const photoModal = document.getElementById('photoModal');
    if (photoModal) {
        photoModal.addEventListener('click', function(e) {
            if (e.target === this) {
                window.closeModal('photoModal');
            }
        });
    }

    // --- Event Delegation for Detail and Delete Buttons ---
    document.body.addEventListener('click', async function(event) {
        const target = event.target;

        // Handle Detail button clicks
        if (target.classList.contains('detail-button')) {
            const formId = target.dataset.id;
            const formType = target.dataset.type; // 'insertion', 'maintenance', or 'infection'

            if (formType === 'insertion') {
                await showInsertionDetailModal(formId);
            } else if (formType === 'maintenance') {
                await showMaintenanceDetailModal(formId);
            } else if (formType === 'infection') {
                await showInfectionDetailModal(formId);
            }
        }

        // Handle Delete button clicks
        if (target.classList.contains('delete-button')) {
            const formId = target.dataset.id;
            const formType = target.dataset.type; // 'insertion', 'maintenance', or 'infection'

            if (formType === 'insertion') {
                await deleteInsertionEntry(formId);
            } else if (formType === 'maintenance') {
                await deleteMaintenanceEntry(formId);
            } else if (formType === 'infection') {
                await deleteInfectionReport(formId);
            }
        }
    });
});


// Functions for tab switching and section toggling
function initTabs() {
    const initialTabs = [
        { section: 'insersi', tabId: 'insersi-form' },
        { section: 'maintenance', tabId: 'maintenance-form' },
        { section: 'infeksi', tabId: 'infeksi-form' }
    ];

    initialTabs.forEach(tab => {
        const activeTabButton = document.getElementById(`${tab.tabId}-tab`);
        if (activeTabButton) {
            const colors = {
                'insersi': ['border-blue-500', 'text-blue-600'],
                'maintenance': ['border-green-500', 'text-green-600'],
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
}

window.toggleSection = function(sectionId) {
    const section = document.getElementById(sectionId);
    const arrow = document.getElementById(`arrow-${sectionId}`);

    if (section) section.classList.toggle('hidden');
    if (arrow) arrow.classList.toggle('rotate-180');
};

window.switchTab = async function(section, tabId, event) {
    if (event) event.preventDefault();

    document.querySelectorAll(`.tab-content.${section}-tab`).forEach(tab => {
        tab.classList.add('hidden');
    });

    document.querySelectorAll(`button[data-section="${section}"]`).forEach(tabButton => {
        tabButton.classList.remove(
            'border-blue-500', 'text-blue-600',
            'border-green-500', 'text-green-600',
            'border-red-500', 'text-red-600',
            'active-tab'
        );
        tabButton.classList.add('border-transparent', 'text-gray-500');
    });

    const targetTab = document.getElementById(tabId);
    if (targetTab) {
        targetTab.classList.remove('hidden');
    } else {
        console.error(`Tab with id ${tabId} not found`);
    }

    const activeTabButton = event?.currentTarget || document.getElementById(`${tabId}-tab`);
    if (activeTabButton) {
        activeTabButton.classList.add('active-tab');

        if (section === 'insersi') {
            activeTabButton.classList.add('border-blue-500', 'text-blue-600');
        } else if (section === 'maintenance') {
            activeTabButton.classList.add('border-green-500', 'text-green-600');
        } else if (section === 'infeksi') {
            activeTabButton.classList.add('border-red-500', 'text-red-600');
            if (tabId === 'infeksi-analisis') {
                await loadInfectionAnalytics();
            }
        }
        activeTabButton.classList.remove('border-transparent', 'text-gray-500');
    }

    if (tabId === 'insersi-history') {
        await loadInsertionHistory();
    } else if (tabId === 'maintenance-history') {
        await loadMaintenanceHistory();
    } else if (tabId === 'infeksi-history') {
        await loadInfectionHistory();
    }
};

// --- Dashboard Stats Loading ---
async function loadDashboardStats() {
    try {
        const stats = await apiCall('cvc-infections/analytics');

        const insertionComplianceElem = document.getElementById('insertionCompliance');
        if (insertionComplianceElem) insertionComplianceElem.textContent = `${stats.total_insertions_today || 0} Form`;

        const maintenanceComplianceElem = document.getElementById('maintenanceCompliance');
        if (maintenanceComplianceElem) maintenanceComplianceElem.textContent = `${stats.total_maintenances_today || 0} Form`;

        const totalInfectionsElem = document.getElementById('totalInfections');
        if (totalInfectionsElem) totalInfectionsElem.textContent = `${stats.total_active_infections_overall || 0} Kasus`;

        const totalInsertionElementsElem = document.getElementById('totalInsertionElements');
        if (totalInsertionElementsElem) totalInsertionElementsElem.textContent = `${INSERTION_ELEMENTS.length} Elemen`;

        const totalMaintenanceElementsElem = document.getElementById('totalMaintenanceElements');
        if (totalMaintenanceElementsElem) totalMaintenanceElementsElem.textContent = `${MAINTENANCE_ELEMENTS.length} Elemen`;

        const totalInfectionCasesElem = document.getElementById('totalInfectionCases');
        if (totalInfectionCasesElem) totalInfectionCasesElem.textContent = `${stats.total_infections_today || 0} Kasus Hari Ini`;

    } catch (error) {
        console.error('Error loading dashboard stats:', error);
        const insertionComplianceElem = document.getElementById('insertionCompliance');
        if (insertionComplianceElem) insertionComplianceElem.textContent = `-- Form`;
        const maintenanceComplianceElem = document.getElementById('maintenanceCompliance');
        if (maintenanceComplianceElem) maintenanceComplianceElem.textContent = `-- Form`;
        const totalInfectionsElem = document.getElementById('totalInfections');
        if (totalInfectionsElem) totalInfectionsElem.textContent = `-- Kasus`;
    }
}

// Function to set up event listeners for forms and new form buttons
function setupFormEventListeners() {
    const insertionFormElem = document.getElementById('insertionForm');
    if (insertionFormElem) insertionFormElem.addEventListener('submit', handleInsertionFormSubmit);

    const maintenanceFormElem = document.getElementById('maintenanceForm');
    if (maintenanceFormElem) maintenanceFormElem.addEventListener('submit', handleMaintenanceFormSubmit);

    const infectionReportFormElem = document.getElementById('infectionReportForm');
    if (infectionReportFormElem) infectionReportFormElem.addEventListener('submit', handleInfectionReportFormSubmit);

    const newInsertionFormBtnElem = document.getElementById('newInsertionFormBtn');
    if (newInsertionFormBtnElem) newInsertionFormBtnElem.addEventListener('click', resetInsertionForm);

    const newMaintenanceFormBtnElem = document.getElementById('newMaintenanceFormBtn');
    if (newMaintenanceFormBtnElem) newMaintenanceFormBtnElem.addEventListener('click', resetMaintenanceForm);

    const newInfectionReportBtnElem = document.getElementById('newInfectionReportBtn');
    if (newInfectionReportBtnElem) newInfectionReportBtnElem.addEventListener('click', resetInfectionForm);
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

// Unified removePhoto for both insertion and maintenance element photos
window.removePhoto = function(formType, index) {
    const fileInput = document.getElementById(`${formType}-photo-${index}`);
    const currentPhotoInfoDiv = document.getElementById(`photo-info-${formType}-${index}`);
    const container = document.getElementById(`${formType}-photo-${index}`)?.closest('td');

    if (fileInput) fileInput.value = ''; // Clear the file input programmatically
    if (currentPhotoInfoDiv) currentPhotoInfoDiv.innerHTML = ''; // Clear the feedback display

    // Set a hidden input to signal backend for deletion
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

// Populate and Reset functions for forms
function populateInsertionForm(form) {
    currentActiveInsertionFormId = form.id;
    const formIdElem = document.getElementById('insertionFormId');
    if (formIdElem) formIdElem.value = form.id;
    const patientNameElem = document.getElementById('insertionPatientName');
    if (patientNameElem) patientNameElem.value = form.patient_name || '';
    const medicalRecordNumberElem = document.getElementById('insertionMedicalRecordNumber');
    if (medicalRecordNumberElem) medicalRecordNumberElem.value = form.medical_record_number || '';
    const dateElem = document.getElementById('insertionDate');
    if (dateElem) dateElem.value = form.insertion_date;
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
    if (dateElem) dateElem.value = new Date().toISOString().split('T')[0]; // Set to today's date
    renderInsertionFormElements([]); // Render empty elements with fresh photo feedback
}

function populateMaintenanceForm(form) {
    currentActiveMaintenanceFormId = form.id;
    const formIdElem = document.getElementById('maintenanceFormId');
    if (formIdElem) formIdElem.value = form.id;
    const patientNameElem = document.getElementById('maintenancePatientName');
    if (patientNameElem) patientNameElem.value = form.patient_name || '';
    const medicalRecordNumberElem = document.getElementById('maintenanceMedicalRecordNumber');
    if (medicalRecordNumberElem) medicalRecordNumberElem.value = form.medical_record_number || '';
    const dateElem = document.getElementById('maintenanceDate');
    if (dateElem) dateElem.value = form.maintenance_date;
    const nurseNameElem = document.getElementById('maintenanceNurseName');
    if (nurseNameElem) nurseNameElem.value = form.nurse_name || '';
    renderMaintenanceFormElements(form.elements_data); // Render elements with photo feedback
}

function resetMaintenanceForm() {
    const formElem = document.getElementById('maintenanceForm');
    if (formElem) formElem.reset();
    currentActiveMaintenanceFormId = null;
    const formIdElem = document.getElementById('maintenanceFormId');
    if (formIdElem) formIdElem.value = '';
    const dateElem = document.getElementById('maintenanceDate');
    if (dateElem) dateElem.value = new Date().toISOString().split('T')[0];
    renderMaintenanceFormElements([]); // Render empty elements with fresh photo feedback
}

function populateInfectionReportForm(report = {}) {
    currentActiveInfectionReportId = report.id;
    const reportIdElem = document.getElementById('infectionReportId');
    if (reportIdElem) reportIdElem.value = report.id || '';
    const patientNameElem = document.getElementById('infectionPatientName');
    if (patientNameElem) patientNameElem.value = report.patient_name || '';
    const medicalRecordNumberElem = document.getElementById('infectionMedicalRecordNumber');
    if (medicalRecordNumberElem) medicalRecordNumberElem.value = report.medical_record_number || '';
    const insertionDateElem = document.getElementById('infectionInsertionDate');
    if (insertionDateElem) insertionDateElem.value = report.insertion_date || '';
    const insertionLocationElem = document.getElementById('infectionInsertionLocation');
    if (insertionLocationElem) insertionLocationElem.value = report.insertion_location || '';
    const diagnosisDateElem = document.getElementById('infectionDiagnosisDate');
    if (diagnosisDateElem) diagnosisDateElem.value = report.infection_diagnosis_date || '';
    const typeElem = document.getElementById('infectionType');
    if (typeElem) typeElem.value = report.infection_type || '';
    const clinicalSymptomsElem = document.getElementById('clinicalSymptoms');
    if (clinicalSymptomsElem) clinicalSymptomsElem.value = report.clinical_symptoms || '';
    const microorganismElem = document.getElementById('microorganism');
    if (microorganismElem) microorganismElem.value = report.microorganism || '';
    const managementElem = document.getElementById('management');
    if (managementElem) managementElem.value = report.management || '';

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
        fileInput.value = ''; // Always clear file input when populating
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


// --- Form Submission Handlers ---
async function handleInsertionFormSubmit(event) {
    event.preventDefault();
    const formId = currentActiveInsertionFormId;
    const method = formId ? 'PUT' : 'POST';
    const endpoint = formId ? `cvc-insertions/${formId}` : 'cvc-insertions';

    const formData = new FormData();
    formData.append('patient_name', document.getElementById('insertionPatientName').value);
    formData.append('medical_record_number', document.getElementById('insertionMedicalRecordNumber').value);
    formData.append('insertion_date', document.getElementById('insertionDate').value);
    formData.append('insertion_location', document.getElementById('insertionLocation').value);
    formData.append('operator_name', document.getElementById('insertionOperatorName').value);

    INSERTION_ELEMENTS.forEach((elementDef, index) => {
        const statusElem = document.querySelector(`[name="elements_data[${index}][status]"]`);
        const notesElem = document.querySelector(`[name="elements_data[${index}][notes]"]`);
        const photoInput = document.getElementById(`insertion-photo-${index}`);
        const photoPathInput = document.querySelector(`input[name="elements_data[${index}][photo_path]"]`);
        const photoRemovedInput = document.querySelector(`input[name="elements_data[${index}][photo_path_removed]"]`);


        formData.append(`elements_data[${index}][description]`, elementDef.description);
        formData.append(`elements_data[${index}][detail]`, elementDef.detail);
        formData.append(`elements_data[${index}][status]`, statusElem ? statusElem.value : 'Tidak Dilakukan');
        formData.append(`elements_data[${index}][notes]`, notesElem ? notesElem.value : '');

        if (photoInput && photoInput.files.length > 0) {
            formData.append(`elements_data[${index}][photo]`, photoInput.files[0]);
        } else if (photoPathInput && photoRemovedInput?.value !== 'true') { // Only include path if not removed
            formData.append(`elements_data[${index}][photo_path]`, photoPathInput.value);
        } else if (photoRemovedInput?.value === 'true') {
            formData.append(`elements_data[${index}][photo_path_removed]`, 'true');
        }
    });

    if (method === 'PUT') {
        formData.append('_method', 'PUT');
    }

    try {
        const result = await apiCall(endpoint, method, formData, true);
        alert(result.message);
        await loadDashboardStats();
        resetInsertionForm();
        await loadInsertionHistory();
    } catch (error) {
        console.error('Error submitting insertion form:', error);
    }
}

async function handleMaintenanceFormSubmit(event) {
    event.preventDefault();
    const formId = currentActiveMaintenanceFormId;
    const method = formId ? 'PUT' : 'POST';
    const endpoint = formId ? `cvc-maintenances/${formId}` : 'cvc-maintenances';

    const formData = new FormData();
    formData.append('patient_name', document.getElementById('maintenancePatientName').value);
    formData.append('medical_record_number', document.getElementById('maintenanceMedicalRecordNumber').value);
    formData.append('maintenance_date', document.getElementById('maintenanceDate').value);
    formData.append('nurse_name', document.getElementById('maintenanceNurseName').value);

    MAINTENANCE_ELEMENTS.forEach((elementDef, index) => {
        const statusElem = document.querySelector(`[name="elements_data[${index}][status]"]`);
        const notesElem = document.querySelector(`[name="elements_data[${index}][notes]"]`);
        const photoInput = document.getElementById(`maintenance-photo-${index}`);
        const photoPathInput = document.querySelector(`input[name="elements_data[${index}][photo_path]"]`);
        const photoRemovedInput = document.querySelector(`input[name="elements_data[${index}][photo_path_removed]"]`);

        formData.append(`elements_data[${index}][description]`, elementDef.description);
        formData.append(`elements_data[${index}][detail]`, elementDef.detail);
        formData.append(`elements_data[${index}][status]`, statusElem ? statusElem.value : 'Tidak Dilakukan');
        formData.append(`elements_data[${index}][notes]`, notesElem ? notesElem.value : '');

        if (photoInput && photoInput.files.length > 0) {
            formData.append(`elements_data[${index}][photo]`, photoInput.files[0]);
        } else if (photoPathInput && photoRemovedInput?.value !== 'true') {
            formData.append(`elements_data[${index}][photo_path]`, photoPathInput.value);
        } else if (photoRemovedInput?.value === 'true') {
            formData.append(`elements_data[${index}][photo_path_removed]`, 'true');
        }
    });

    if (method === 'PUT') {
        formData.append('_method', 'PUT');
    }

    try {
        const result = await apiCall(endpoint, method, formData, true);
        alert(result.message);
        await loadDashboardStats();
        resetMaintenanceForm();
        await loadMaintenanceHistory();
    } catch (error) {
        console.error('Error submitting maintenance form:', error);
    }
}

async function handleInfectionReportFormSubmit(event) {
    event.preventDefault();
    const reportId = currentActiveInfectionReportId;
    const method = reportId ? 'PUT' : 'POST';
    const endpoint = reportId ? `cvc-infections/${reportId}` : 'cvc-infections';

    const formData = new FormData();
    formData.append('patient_name', document.getElementById('infectionPatientName').value);
    formData.append('medical_record_number', document.getElementById('infectionMedicalRecordNumber').value);
    formData.append('insertion_date', document.getElementById('infectionInsertionDate').value);
    formData.append('insertion_location', document.getElementById('infectionInsertionLocation').value);
    formData.append('infection_diagnosis_date', document.getElementById('infectionDiagnosisDate').value);
    formData.append('infection_type', document.getElementById('infectionType').value);
    formData.append('clinical_symptoms', document.getElementById('clinicalSymptoms').value);
    formData.append('microorganism', document.getElementById('microorganism').value);
    formData.append('management', document.getElementById('management').value);

    const photoInput = document.getElementById('infectionFileUpload');
    const photoPreview = document.getElementById('infectionPhotoPreview');

    if (photoInput && photoInput.files.length > 0) {
        formData.append('photo', photoInput.files[0]);
    } else if (photoPreview && photoPreview.classList.contains('hidden') && reportId) {
        formData.append('photo', '');
    }

    if (method === 'PUT') {
        formData.append('_method', 'PUT');
    }

    try {
        const result = await apiCall(endpoint, method, formData, true);
        alert(result.message);
        await loadDashboardStats();
        resetInfectionForm();
        await loadInfectionHistory();
    } catch (error) {
        console.error('Error submitting infection report:', error);
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
        const modalBody = document.getElementById('detailModalBody');
        if (!modalBody) { console.error("Element #detailModalBody not found."); return; }
        modalBody.innerHTML = `
            <h4 class="text-lg font-bold mb-2">Detail Form Insersi CVC</h4>
            <div class="space-y-2 text-gray-700 mb-4">
                <p><strong>Nama Pasien:</strong> ${form.patient_name}</p>
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
                <button onclick="closeModal('detailModal')" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">Tutup</button>
            </div>
        `;
        window.showDetailModal('detailModal');
    } catch (error) {
        console.error('Error showing insertion detail modal:', error);
        alert('Gagal memuat detail form insersi.');
    }
};
async function deleteInsertionEntry(formId) {
    if (confirm('Apakah Anda yakin ingin menghapus data insersi ini?')) {
        try {
            await apiCall(`cvc-insertions/${formId}`, 'DELETE');
            alert('Data insersi berhasil dihapus.');
            await loadInsertionHistory();
            await loadDashboardStats();
        } catch (error) {
            console.error('Error deleting insertion data:', error);
        }
    }
}
async function deleteInfectionReport(formId) {
    if (confirm('Apakah Anda yakin ingin menghapus data infeksi ini?')) {
        try {
            await apiCall(`cvc-infections/${formId}`, 'DELETE');
            alert('Data infeksi berhasil dihapus.');
            await loadInfectionHistory();
            await loadDashboardStats();
        } catch (error) {
            console.error('Error deleting infections data:', error);
        }
    }
}

async function loadInsertionHistory(page = 1) {
    try {
        const response = await apiCall(`cvc-insertions?page=${page}`);
        renderInsertionHistoryTable(response.data);
        renderPagination(response.links, response.meta, 'insertion');
    } catch (error) {
        console.error('Error loading insertion history:', error);
    }
}

function renderMaintenanceHistoryTable(data) {
    const tbody = document.getElementById('maintenanceHistoryTableBody');
    if (!tbody) { console.error("Element #maintenanceHistoryTableBody not found."); return; }
    tbody.innerHTML = '';
    if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-4 text-center text-black">Tidak ada riwayat maintenance.</td></tr>`; // Updated colspan
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
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Perawatan CVC</td>
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
        const modalBody = document.getElementById('detailModalBody');
        if (!modalBody) { console.error("Element #detailModalBody not found."); return; }
        modalBody.innerHTML = `
            <h4 class="text-lg font-bold mb-2">Detail Form Maintenance CVC</h4>
            <div class="space-y-2 text-gray-700 mb-4">
                <p><strong>Nama Pasien:</strong> ${form.patient_name}</p>
                <p><strong>Nomor RM:</strong> ${form.medical_record_number || '-'}</p>
                <p><strong>Tanggal Perawatan:</strong> ${formatDate(form.maintenance_date)}</p>
                <p><strong>Perawat:</strong> ${form.nurse_name || '-'}</p>
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
                <button onclick="closeModal('detailModal')" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">Tutup</button>
            </div>
        `;
        window.showDetailModal('detailModal');
    } catch (error) {
        console.error('Error showing maintenance detail modal:', error);
        alert('Gagal memuat detail form maintenance.');
    }
};
async function deleteMaintenanceEntry(formId) {
    if (confirm('Apakah Anda yakin ingin menghapus data maintenance ini?')) {
        try {
            await apiCall(`cvc-maintenances/${formId}`, 'DELETE');
            alert('Data maintenance berhasil dihapus.');
            await loadMaintenanceHistory();
            await loadDashboardStats();
        } catch (error) {
            console.error('Error deleting maintenance data:', error);
        }
    }
}


async function loadMaintenanceHistory(page = 1) {
    try {
        const response = await apiCall(`cvc-maintenances?page=${page}`);
        renderMaintenanceHistoryTable(response.data);
        renderPagination(response.links, response.meta, 'maintenance');
    }
    catch (error) {
        console.error('Error loading maintenance history:', error);
    }
}

function renderInfectionHistoryTable(data) {
    const tbody = document.getElementById('infectionHistoryTableBody');
    if (!tbody) { console.error("Element #infectionHistoryTableBody not found."); return; }
    tbody.innerHTML = '';
    if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-4 text-center text-black">Tidak ada riwayat infeksi.</td></tr>`;
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
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${report.microorganism || '-'}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${report.status === 'Aktif' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'}">
                    ${report.status || '-'}
                </span>
            </td>
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
    try {
        const response = await apiCall(`cvc-infections?page=${page}`);
        renderInfectionHistoryTable(response.data);
        renderPagination(response.links, response.meta, 'infection');
    } catch (error) {
        console.error('Error loading infection history:', error);
    }
}

window.showInfectionDetailModal = async function(reportId) {
    try {
        const report = await apiCall(`cvc-infections/${reportId}`);
        const modalBody = document.getElementById('detailModalBody');
        if (!modalBody) { console.error("Element #detailModalBody not found."); return; }
        modalBody.innerHTML = `
            <h4 class="text-lg font-bold mb-2">Detail Laporan Infeksi Terkait CVC</h4>
            <div class="space-y-2 text-gray-700 mb-4">
                <p><strong>Nama Pasien:</strong> ${report.patient_name}</p>
                <p><strong>Nomor RM:</strong> ${report.medical_record_number || '-'}</p>
                <p><strong>Tanggal Insersi CVC:</strong> ${formatDate(report.insertion_date || 'N/A')}</p>
                <p><strong>Lokasi Insersi:</strong> ${report.insertion_location || '-'}</p>
                <p><strong>Tanggal Diagnosis Infeksi:</strong> ${formatDate(report.infection_diagnosis_date)}</p>
                <p><strong>Jenis Infeksi:</strong> ${report.infection_type || '-'}</p>
                <p><strong>Gejala Klinis:</strong> ${report.clinical_symptoms || '-'}</p>
                <p><strong>Mikroorganisme:</strong> ${report.microorganism || '-'}</p>
                <p><strong>Tatalaksana:</strong> ${report.management || '-'}</p>
                <p><strong>Status Infeksi:</strong> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${report.status === 'Aktif' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'}">${report.status || '-'}</span></p>
                ${report.photo_path ? `<p><strong>Foto Dokumentasi:</strong> <button onclick="openPhotoModal('${report.photo_path}')" class="text-blue-500 hover:underline mt-2">Lihat Foto</button></p>` : ''}
                <p><strong>Dibuat Pada:</strong> ${formatDateTime(report.created_at)}</p>
                <p><strong>Terakhir Diperbarui:</strong> ${formatDateTime(report.updated_at)}</p>
            </div>
            <div class="mt-4 text-right">
                <button onclick="editFormFromModal('infection', ${report.id})" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 mr-2">Edit</button>
                <button onclick="closeModal('detailModal')" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">Tutup</button>
            </div>
        `;
        window.showDetailModal('detailModal');
    } catch (error) {
        console.error('Error showing infection detail modal:', error);
        alert('Gagal memuat detail laporan infeksi.');
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
                pageLink.classList.add('z-10', 'bg-blue-50', 'border-blue-500', 'text-blue-600', 'active');
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


// --- Chart Rendering for Analysis Tab ---
async function loadInfectionAnalytics() {
    try {
        const stats = await apiCall('cvc-infections/analytics');

        if (infectionIncidentChartInstance) infectionIncidentChartInstance.destroy();
        if (infectionLocationChartInstance) infectionLocationChartInstance.destroy();
        if (microorganismChartInstance) microorganismChartInstance.destroy();

        const incidentCtx = document.getElementById('infectionIncidentChart')?.getContext('2d');
        if (incidentCtx) {
            const incidentLabels = stats.infection_trend.map(item => {
                const [year, month] = item.month.split('-');
                const date = new Date(year, month - 1);
                return date.toLocaleString('id-ID', { month: 'short', year: '2-digit' });
            });
            const incidentData = stats.infection_trend.map(item => item.count);

            infectionIncidentChartInstance = new Chart(incidentCtx, {
                type: 'line',
                data: { labels: incidentLabels, datasets: [{ label: 'Jumlah Infeksi', data: incidentData, borderColor: 'rgb(255, 99, 132)', backgroundColor: 'rgba(255, 99, 132, 0.2)', tension: 0.1, fill: true }] },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, title: { display: true, text: 'Jumlah Kasus' } }, x: { title: { display: true, text: 'Bulan' } } }, plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(context) { return `Jumlah: ${context.raw}`; } } } } }
            });
        }

        const locationCtx = document.getElementById('infectionLocationChart')?.getContext('2d');
        if (locationCtx) {
            const locationLabels = stats.infection_by_location.map(item => item.insertion_location || 'Tidak Diketahui');
            const locationData = stats.infection_by_location.map(item => item.count);

            infectionLocationChartInstance = new Chart(locationCtx, {
                type: 'pie',
                data: { labels: locationLabels, datasets: [{ data: locationData, backgroundColor: [ 'rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)', 'rgba(153, 102, 255, 0.7)' ], borderColor: '#fff', borderWidth: 1 }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right' }, tooltip: { callbacks: { label: function(context) { let label = context.label || ''; if (label) { label += ': '; } label += context.raw + ' kasus'; return label; } } } } }
            });
        }

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

        // // Risk factors update (still placeholder data for now)
        // const riskFactorsContainer = document.getElementById('riskFactorsContainer');
        // if (riskFactorsContainer) {
        //     // These percentages are static as per discussion. For dynamic, backend must provide this.
        //     const riskFactor1 = 78; // Example: Durasi Kateter >7 hari
        //     const riskFactor2 = 65; // Example: Kepatuhan Hand Hygiene

        //     const riskFactor1PercentageElem = document.getElementById('riskFactor1Percentage');
        //     if (riskFactor1PercentageElem) riskFactor1PercentageElem.textContent = `${riskFactor1}%`;
        //     const riskFactor1ProgressBarElem = document.getElementById('riskFactor1ProgressBar');
        //     if (riskFactor1ProgressBarElem) riskFactor1ProgressBarElem.style.width = `${riskFactor1}%`;

        //     const riskFactor2PercentageElem = document.getElementById('riskFactor2Percentage');
        //     if (riskFactor2PercentageElem) riskFactor2PercentageElem.textContent = `${riskFactor2}%`;
        //     const riskFactor2ProgressBarElem = document.getElementById('riskFactor2ProgressBar');
        //     if (riskFactor2ProgressBarElem) riskFactor2ProgressBarElem.style.width = `${riskFactor2}%`;
        // }

    } catch (error) {
        console.error('Error loading infection analytics:', error);
        if (infectionIncidentChartInstance) infectionIncidentChartInstance.destroy();
        if (infectionLocationChartInstance) infectionLocationChartInstance.destroy();
        if (microorganismChartInstance) microorganismChartInstance.destroy();
    }
}