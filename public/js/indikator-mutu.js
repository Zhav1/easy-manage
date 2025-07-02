// Global variables
let currentSection = 'list';
let formCurrentData = {}; // Stores current week's data for each form type
let formHistoryData = {}; // Stores history data for each form type

const API_BASE_URL = '/api/v1/quality-inspection';

const formIdMap = {
    'hand-hygiene': 'kebersihan-form',
    'apd': 'apd-form',
    'identifikasi': 'identifikasi-form',
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

// Define your indicators, their properties, and initial status
let indicators = [
    { id: 'hand-hygiene', name: 'Kepatuhan Kebersihan Tangan', unit: 'PPI', target: '≥80%', form_type: 'hand-hygiene', status: 'pending' },
    { id: 'identifikasi', name: 'Kepatuhan Identifikasi Pasien', unit: 'PPI', target: '≥80%', form_type: 'identifikasi', status: 'pending' },
    { id: 'apd', name: 'Kepatuhan Penggunaan Alat Pelindung Diri', unit: 'YANMED', target: '100%', form_type: 'apd', status: 'pending' },
    { id: 'sc', name: 'Waktu tanggap seksio sesarea emergency', unit: 'IBS', target: '≥30%', form_type: 'sc', status: 'pending' },
    { id: 'wtri', name: 'Waktu tunggu rawat jalan', unit: 'YANMED', target: '≥80%', form_type: 'wtri', status: 'pending' },
    { id: 'poe', name: 'Penundaan operasi elektif', unit: 'IBS', target: '<5%', form_type: 'poe', status: 'pending' },
    { id: 'visite', name: 'Kepatuhan waktu visite dokter', unit: 'YANMED/SIRS', target: '≥80%', form_type: 'visite', status: 'pending' },
    { id: 'kritis-lab', name: 'Kepatuhan hasil kritis laboratorium', unit: 'PK', target: '≥80%', form_type: 'kritis-lab', status: 'pending' },
    { id: 'fornas', name: 'Kepatuhan penggunaan Formularium', unit: 'FARMASI', target: '≥80%', form_type: 'fornas', status: 'pending' },
    { id: 'cp', name: 'Kepatuhan terhadap clinical pathway', unit: 'KOMITE', target: '≥80%', form_type: 'cp', status: 'pending' },
    { id: 'jatuh', name: 'Kepatuhan upaya pencegahan risiko pasien jatuh', unit: 'YANMED', target: '100%', form_type: 'jatuh', status: 'pending' },
    { id: 'krk', name: 'Kecepatan waktu tanggap terhadap komplain', unit: 'ADMISI', target: '≥80%', form_type: 'krk', status: 'pending' },
    { id: 'kepuasan', name: 'Kepuasan pasien', unit: 'ADMISI', target: '≥76.61%', form_type: 'kepuasan', status: 'pending' }
];


// --- Utility Functions ---

/**
 * Helper function for authenticated fetch requests.
 * Includes CSRF token and Authorization header.
 * @param {string} url - The URL to fetch.
 * @param {object} options - Fetch options.
 * @returns {Promise<Response>}
 */

 /**
 * General helper to find the correct insertion index for a new data row.
 * It will insert before the first static row, or at the very end of the tbody.
 * @param {HTMLElement} tbody
 * @param {string[]} excludeClasses Classes of static rows (e.g., ['total-row', 'rata-rata-row', 'nb-row'])
 * @returns {number} The index within the tbody where a new data row should be inserted.
 */
function getInsertionIndex(tbody, excludeClasses) {
    const rows = Array.from(tbody.children);
    for (let i = 0; i < rows.length; i++) {
        let isStatic = false;
        for (const cls of excludeClasses) {
            if (rows[i].classList.contains(cls)) {
                isStatic = true;
                break;
            }
        }
        if (isStatic) {
            return i; // Found the first static row, insert before it
        }
    }
    return -1; // If no static rows found, append at the very end
}

async function authenticatedFetch(url, options = {}) {
    const token = window.authToken;
    const headers = {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        ...options.headers, // Allow overriding or adding more headers
    };

    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }

    return fetch(url, { ...options, headers });
}

/**
 * Shows the loading spinner.
 */
function showLoading() {
    document.getElementById('loading').style.display = 'flex';
}

/**
 * Hides the loading spinner.
 */
function hideLoading() {
    document.getElementById('loading').style.display = 'none';
}

/**
 * Displays a notification message.
 * @param {string} message - The message to display.
 * @param {'info'|'success'|'warning'|'error'} type - The type of notification.
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`; // Uses CSS classes for styling
    notification.innerHTML = `
        <i class="fas fa-${getNotificationIcon(type)}"></i>
        <span>${message}</span>
        <button class="close-btn" onclick="this.parentElement.remove()">×</button>
    `;
    // Apply Tailwind-like styles directly for immediate visibility if CSS not loaded
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
        animation: slideIn 0.3s ease forwards; /* Use forwards to keep final state */
    `;

    // Add specific color for different types
    if (type === 'success') {
        notification.style.borderColor = '#4CAF50';
        notification.style.color = '#1E8449';
    } else if (type === 'error') {
        notification.style.borderColor = '#F44336';
        notification.style.color = '#C0392B';
    } else if (type === 'warning') {
        notification.style.borderColor = '#FFC107';
        notification.style.color = '#D68910';
    } else { // info
        notification.style.borderColor = '#2196F3';
        notification.style.color = '#2874A6';
    }


    document.body.appendChild(notification);

    // Optional: Add keyframes directly if global CSS animations are an issue in Canvas
    const styleSheet = document.styleSheets[0] || document.head.appendChild(document.createElement('style')).sheet;
    if (!styleSheet.cssRules || !Array.from(styleSheet.cssRules).some(rule => rule.name === 'slideIn')) {
        styleSheet.insertRule(`
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
        `, styleSheet.cssRules.length);
    }

    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease forwards';
        styleSheet.insertRule(`
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `, styleSheet.cssRules.length);
        notification.addEventListener('animationend', () => notification.remove());
    }, 5000); // Notification disappears after 5 seconds
}

/**
 * Returns the Font Awesome icon class based on notification type.
 * @param {string} type - Notification type.
 * @returns {string} Icon class.
 */
function getNotificationIcon(type) {
    const icons = {
        'info': 'info-circle',
        'success': 'check-circle',
        'warning': 'exclamation-triangle',
        'error': 'times-circle'
    };
    return icons[type] || 'info-circle';
}

/**
 * Checks if all required fields in a form entry are filled.
 * @param {HTMLElement} row - The table row element.
 * @returns {boolean} True if all required inputs in the row are filled, false otherwise.
 */
function isRowComplete(row) {
    let allFilled = true;
    // Select all required inputs within this specific row
    row.querySelectorAll('input[required], select[required], textarea[required]').forEach(input => {
        // Exclude readonly inputs from the 'required check' if their value is automatically filled
        // If it's readonly, we assume its value will be set by JS logic, so we check if it has *any* value.
        if (input.readOnly) {
            if (input.value.trim() === '' || input.value === '0') { // '0' might be a valid, but empty-like, state for numbers
                allFilled = false;
            }
        } else if (input.type === 'checkbox' || input.type === 'radio') {
            // For simplicity, for single required checkboxes, it must be checked.
            if (input.required && !input.checked) {
                allFilled = false;
            }
            // For radio buttons in a group, you'd typically check the group, not individual radios.
            // Given your structure, a simple `input.checked` will work if they're individually required.
        } else if (input.value.trim() === '') {
            allFilled = false;
        }
        if (!allFilled) {
            console.log(`Incomplete field: ${input.name} (value: '${input.value}') on row`, row);
        }
    });
    return allFilled;
}


/**
 * Determines if a form is considered "complete" based on its required fields.
 * If some data exists but not all required fields are filled, it's "in-progress".
 * If no data exists, it's "pending".
 * @param {string} formType - The ID of the form.
 * @param {object} formData - The data object for the form.
 * @returns {boolean} True if complete, false otherwise.
 */
function isFormComplete(formType, formData) {
    // If no data or empty entries, it's not complete
    if (!formData || Object.keys(formData).length === 0 || (formData.entries && formData.entries.length === 0)) {
        return false;
    }

    const formElement = document.getElementById(formIdMap[formType]);
    if (!formElement) return false;

    // Check if the overall month selection is made (if applicable)
    const monthInput = formElement.querySelector('input[name$="_bulan"]'); // Check for any input named _bulan
    if (monthInput && monthInput.value.trim() === '') {
        return false; // Month not selected, so form is not complete
    }

    // For forms with 'entries', check if *all* entries are fully complete
    const formTypesWithEntries = Object.keys(formIdMap); // All your forms use entries
    if (formTypesWithEntries.includes(formType)) {
        const tbody = formElement.querySelector('.form-table tbody');
        if (tbody) {
            const dataRows = Array.from(tbody.children).filter(row =>
                !row.classList.contains('total-row') &&
                !row.classList.contains('rata-rata-row') &&
                !row.classList.contains('nb-row')
            );

            // A form with entries is "complete" if ALL data rows are fully complete AND there's at least one data row.
            if (dataRows.length === 0) {
                return false; // No data rows means not complete
            }
            return dataRows.every(row => isRowComplete(row));
        }
        return false; // If no tbody or data rows found, not complete
    }

    // For other forms (if any, though most are now entry-based)
    // This fallback logic might not be strictly needed if all forms use 'entries'
    let allTopLevelFieldsFilled = true;
    formElement.querySelectorAll('input[required], select[required], textarea[required]').forEach(input => {
        if (!input.closest('tr.nb-row') && !input.closest('tr.total-row') && !input.closest('tr.rata-rata-row')) { // Exclude static row fields
            if (input.type === 'checkbox' || input.type === 'radio') {
                if (input.required && !input.checked) {
                    allTopLevelFieldsFilled = false;
                }
            } else if (input.value.trim() === '') {
                allTopLevelFieldsFilled = false;
            }
        }
    });
    return allTopLevelFieldsFilled;
}


// --- DOM Manipulation and Section Switching ---

/**
 * Switches the displayed section of the page.
 * @param {string} section - The ID of the section to show ('list', 'history', or a formType like 'hand-hygiene').
 */
function showSection(section) {
    console.log(`Attempting to show section: ${section}`);
    
    // Update active tab
    document.querySelectorAll('.indicator-table td').forEach(tab => {
        tab.classList.remove('active', 'bg-blue-500', 'text-white');
        const onclickAttr = tab.getAttribute('onclick');
        if (onclickAttr && onclickAttr.includes(`'${section}'`)) {
            tab.classList.add('active', 'bg-blue-500', 'text-white');
        }
    });

    // Hide all sections
    document.querySelector('.main-grid').style.display = 'none';
    document.querySelector('.stats-grid').style.display = 'none';
    document.querySelector('.data-forms').style.display = 'none';
    document.getElementById('history-section').style.display = 'none';

    // Hide all form cards
    document.querySelectorAll('.form-card').forEach(form => {
        form.style.display = 'none';
    });

    // Show requested section
    if (section === 'list') {
        document.querySelector('.main-grid').style.display = 'grid';
        document.querySelector('.stats-grid').style.display = 'grid';
        // Re-run status check on list view to ensure current week's status is reflected
        updateStatisticsDisplay();
    } else if (section === 'history') {
        document.getElementById('history-section').style.display = 'block';
        renderHistorySection();
    } else {
        const formId = formIdMap[section];
        if (formId) {
            const form = document.getElementById(formId);
            if (form) {
                console.log(`Showing form ${formId} for section ${section}`);
                document.querySelector('.data-forms').style.display = 'block';
                form.style.display = 'block';
                
                // Special handling for CP form
                if (section === 'cp') {
                    console.log('Initializing CP form with data:', formCurrentData['cp']?.data || {});
                }
                
                // Populate the form with current data
                populateForm(form, formCurrentData[section]?.data || {}, section);
                form.scrollIntoView({ behavior: 'smooth' });
            } else {
                console.error(`Form element with ID '${formId}' not found`);
            }
        } else {
            console.error(`No form ID mapped for section: ${section}`);
        }
    }
    currentSection = section;
}

/**
 * Opens a specific form section.
 * @param {string} formType - The ID of the form to open.
 */
function openForm(formType) {
    showSection(formType);
}

/**
 * Returns to the list view.
 */
function backToList() {
    showSection('list');
}


// --- Form Data Population & Collection Logic ---

/**
 * Populates a form element with provided data based on its type.
 * @param {HTMLElement} formElement - The HTML element of the form.
 * @param {object} data - The data object to populate the form with.
 * @param {string} formType - The type of the form (e.g., 'hand-hygiene').
 */
function populateForm(formElement, data, formType) {
    if (!formElement) return;

    const tbody = formElement.querySelector('.form-table tbody');
    if (!tbody) return;

    // Handle month input (already correctly implemented)
    const monthInput = formElement.querySelector('input[type="month"][name$="_bulan"]');
    if (monthInput) {
        monthInput.value = data.bulan || moment().format('YYYY-MM');
        // ... (event listener setup)
    }

    // Preserve static rows (already correctly implemented)
    const rowsToPreserve = Array.from(tbody.children).filter(row =>
        row.classList.contains('total-row') ||
        row.classList.contains('rata-rata-row') ||
        row.classList.contains('nb-row')
    );
    tbody.innerHTML = ''; // Clear all content, including any previous dynamic rows
    rowsToPreserve.forEach(row => tbody.appendChild(row)); // Re-append preserved rows

    // Populate common fields outside entries (e.g., unit_kerja for identifikasi, ruangan/judul for cp)
    if (formType === 'identifikasi') {
        const unitKerjaSelect = formElement.querySelector('select[name="identifikasi_unit_kerja"]');
        if (unitKerjaSelect) {
            unitKerjaSelect.value = data.unit_kerja || '';
        }
        if (data.nb) { // Handle NB checkboxes for Identifikasi
            formElement.querySelector('input[name="identifikasi_nb_verbal_visual"]').checked = data.nb.verbal_visual || false;
            formElement.querySelector('input[name="identifikasi_nb_2_parameter"]').checked = data.nb['2_parameter'] || false;
            formElement.querySelector('input[name="identifikasi_nb_1_parameter"]').checked = data.nb['1_parameter'] || false;
            formElement.querySelector('input[name="identifikasi_nb_tidak_dilakukan"]').checked = data.nb.tidak_dilakukan || false;
        }
    } else if (formType === 'wtri') {
        formElement.querySelectorAll('input[name="wtri_unit"]').forEach(radio => {
            radio.checked = (radio.value === data.unit);
        });
    } else if (formType === 'cp') {
        formElement.querySelector('input[name="cp_ruangan"]').value = data.ruangan || '';
        formElement.querySelector('input[name="cp_judul_cp"]').value = data.judul_cp || '';
        // CRITICAL: Ensure inputs for totals in CP form are populated here too if they are outside dynamic rows
        if (data.totals) {
            formElement.querySelector('input[name="cp_total_asesmen_p"]').value = data.totals.asesmen_p || 0;
            formElement.querySelector('input[name="cp_total_asesmen_n"]').value = data.totals.asesmen_n || 0;
            formElement.querySelector('input[name="cp_total_asesmen_c"]').value = data.totals.asesmen_c || 0;
            formElement.querySelector('input[name="cp_total_fisik_p"]').value = data.totals.fisik_p || 0;
            formElement.querySelector('input[name="cp_total_fisik_n"]').value = data.totals.fisik_n || 0;
            formElement.querySelector('input[name="cp_total_fisik_c"]').value = data.totals.fisik_c || 0;
            formElement.querySelector('input[name="cp_total_penunjang_p"]').value = data.totals.penunjang_p || 0;
            formElement.querySelector('input[name="cp_total_penunjang_n"]').value = data.totals.penunjang_n || 0;
            formElement.querySelector('input[name="cp_total_penunjang_c"]').value = data.totals.penunjang_c || 0;
            formElement.querySelector('input[name="cp_total_obat_p"]').value = data.totals.obat_p || 0;
            formElement.querySelector('input[name="cp_total_obat_n"]').value = data.totals.obat_n || 0;
            formElement.querySelector('input[name="cp_total_obat_c"]').value = data.totals.obat_c || 0;
            formElement.querySelector('input[name="cp_grand_total"]').value = data.totals.grand_total || 0;
        }
        if (data.rata_rata_kepatuhan) {
             formElement.querySelector('input[name="cp_rata_rata_kepatuhan"]').value = data.rata_rata_kepatuhan;
        }
    }

    // THIS IS THE CRITICAL LOGIC BLOCK:
    // We should ALWAYS try to populate from data.entries if it exists and is an array.
    // If it doesn't exist, or is empty, THEN we add default empty rows.
    if (data.entries && Array.isArray(data.entries) && data.entries.length > 0) {
        // Sort entries to ensure ascending order for display
        let sortedEntries = [...data.entries].sort((a, b) => (a.no || 0) - (b.no || 0));

        sortedEntries.forEach((entry, index) => {
            const newIndex = index + 1; // Ensure sequential numbering starting from 1
            // Use a switch-case or if-else if structure to call the correct add*Row function
            // This part is already correct in your existing code for calling the specific add*Row functions.
            if (formType === 'hand-hygiene') {
                addHandHygieneRow(tbody, newIndex, entry);
            } else if (formType === 'apd') {
                addApdRow(tbody, newIndex, entry);
            } else if (formType === 'identifikasi') {
                addIdentifikasiRow(tbody, newIndex, entry);
            } else if (formType === 'wtri') {
                addWtriRow(tbody, newIndex, entry);
            } else if (formType === 'kritis-lab') {
                addKritisLabRow(tbody, newIndex, entry);
            } else if (formType === 'fornas') {
                addFornasRow(tbody, newIndex, entry);
            } else if (formType === 'visite') {
                addVisiteRow(tbody, newIndex, entry);
            } else if (formType === 'jatuh') {
                addJatuhRow(tbody, newIndex, entry);
            } else if (formType === 'cp') {
                addCpRow(tbody, newIndex, entry);
            } else if (formType === 'kepuasan') {
                addKepuasanRow(tbody, newIndex, entry);
            } else if (formType === 'krk') {
                addKrkRow(tbody, newIndex, entry);
            } else if (formType === 'poe') {
                addPoeRow(tbody, newIndex, entry);
            } else if (formType === 'sc') {
                addScRow(tbody, newIndex, entry);
            }
        });
    } else {
        // ONLY if there are NO saved entries, then add default empty ones
        if (['identifikasi', 'fornas', 'jatuh', 'cp'].includes(formType)) {
            for (let i = 1; i <= 2; i++) { // Add 2 empty rows as template
                if (formType === 'identifikasi') addIdentifikasiRow(tbody, i);
                else if (formType === 'fornas') addFornasRow(tbody, i);
                else if (formType === 'jatuh') addJatuhRow(tbody, i);
                else if (formType === 'cp') addCpRow(tbody, i);
            }
        } else {
            // All other forms get one empty row by default
            if (formType === 'hand-hygiene') addHandHygieneRow(tbody, 1);
            else if (formType === 'apd') addApdRow(tbody, 1);
            else if (formType === 'wtri') addWtriRow(tbody, 1);
            else if (formType === 'kritis-lab') addKritisLabRow(tbody, 1);
            else if (formType === 'visite') addVisiteRow(tbody, 1);
            else if (formType === 'kepuasan') addKepuasanRow(tbody, 1);
            else if (formType === 'krk') addKrkRow(tbody, 1);
            else if (formType === 'poe') addPoeRow(tbody, 1);
            else if (formType === 'sc') addScRow(tbody, 1);
        }
    }

    // IMPORTANT: Re-number rows (already simplified)
    // ... (rest of renumbering and total updates)
    if (formType === 'identifikasi') {
        renumberTableRows(tbody, ['nb-row']);
    } else if (['fornas', 'jatuh', 'krk'].includes(formType)) {
        renumberTableRows(tbody, ['total-row']);
    } else if (formType === 'cp') {
        renumberTableRows(tbody, ['total-row', 'rata-rata-row']);
    } else {
        renumberTableRows(tbody, []);
    }

    // Re-run totals if the form has them (e.g., CP, Jatuh, Hand Hygiene)
    if (formType === 'cp') {
        updateCpTotals(formElement);
    } else if (formType === 'jatuh') {
        updateJatuhTotals(formElement);
    } else if (formType === 'hand-hygiene') {
        updateHandHygieneTotals(formElement);
    }
}


/**
 * Extracts data from a form element based on its type.
 * @param {string} formType - The type of the form.
 * @returns {object} The extracted form data.
 */
function getFormData(formType) {
    const formElement = document.getElementById(formIdMap[formType]);
    const formData = {};

    if (!formElement) return formData;

    // Helper to get input value within a given row. Now uses simple name.
    const getInputValue = (row, name) => row.querySelector(`[name="${name}"]`)?.value;
    // Helper to get checked status within a given row. Now uses simple name.
    const getCheckedValue = (row, name) => row.querySelector(`[name="${name}"]`)?.checked;
    const getParsedInt = (row, name) => parseInt(getInputValue(row, name)) || 0;

    // Get month input for all forms that have it
    const monthInput = formElement.querySelector('input[type="month"][name$="_bulan"]');
    if (monthInput) {
        formData.bulan = monthInput.value;
    }

    switch (formType) {
        case 'hand-hygiene':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row):not(.rata-rata-row):not(.nb-row)').forEach(row => {
                // IMPORTANT CHANGE: Input names in addHandHygieneRow are now simpler, e.g., 'bulan', not 'bulan_1'.
                // So, we don't need to append index here.
                const entry = {
                    bulan: getInputValue(row, `bulan`),
                    sesi: getParsedInt(row, `sesi`),
                    dpjp_kesempatan: getParsedInt(row, `dpjp_kesempatan`),
                    dpjp_handwash: getParsedInt(row, `dpjp_handwash`),
                    dpjp_handrub: getParsedInt(row, `dpjp_handrub`),
                    perawat_kesempatan: getParsedInt(row, `perawat_kesempatan`),
                    perawat_handwash: getParsedInt(row, `perawat_handwash`),
                    perawat_handrub: getParsedInt(row, `perawat_handrub`),
                    pendidikan_kesempatan: getParsedInt(row, `pendidikan_kesempatan`),
                    pendidikan_handwash: getParsedInt(row, `pendidikan_handwash`),
                    pendidikan_handrub: getParsedInt(row, `pendidikan_handrub`),
                    lain_kesempatan: getParsedInt(row, `lain_kesempatan`),
                    lain_handwash: getParsedInt(row, `lain_handwash`),
                    lain_handrub: getParsedInt(row, `lain_handrub`),
                    total_kesempatan: getParsedInt(row, `total_kesempatan`),
                    total_handwash: getParsedInt(row, `total_handwash`),
                    total_handrub: getParsedInt(row, `total_handrub`),
                };
                formData.entries.push(entry);
            });
            break;

        case 'apd':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row):not(.rata-rata-row):not(.nb-row)').forEach(row => {
                // IMPORTANT CHANGE: Input names in addApdRow are now simpler, e.g., 'tgl', not 'apd_tgl_1'.
                const entry = {
                    tgl: getInputValue(row, `tgl`),
                    profesi: getInputValue(row, `profesi`),
                    ruang: getInputValue(row, `ruang`),
                    pelayanan: getInputValue(row, `pelayanan`),
                    sarung_tangan_y: getCheckedValue(row, `st_y`),
                    sarung_tangan_t: getCheckedValue(row, `st_t`),
                    masker_y: getCheckedValue(row, `masker_y`),
                    masker_t: getCheckedValue(row, `masker_t`),
                    topi_y: getCheckedValue(row, `topi_y`),
                    topi_t: getCheckedValue(row, `topi_t`),
                    google_y: getCheckedValue(row, `google_y`),
                    google_t: getCheckedValue(row, `google_t`),
                    pakaian_y: getCheckedValue(row, `pakaian_y`),
                    pakaian_t: getCheckedValue(row, `pakaian_t`),
                    sepatu_y: getCheckedValue(row, `sepatu_y`),
                    sepatu_t: getCheckedValue(row, `sepatu_t`),
                    kepatuhan: getInputValue(row, `kepatuhan`),
                    ket: getInputValue(row, `ket`)
                };
                formData.entries.push(entry);
            });
            break;

        case 'identifikasi':
            formData.unit_kerja = formElement.querySelector('select[name="identifikasi_unit_kerja"]')?.value;
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.nb-row)').forEach((row) => {
                // IMPORTANT CHANGE: Input names in addIdentifikasiRow are now simpler, e.g., 'tgl'.
                const entry = {
                    tgl: getInputValue(row, `tgl`),
                    staf: getInputValue(row, `staf`),
                    obat: getCheckedValue(row, `obat`),
                    darah: getCheckedValue(row, `darah`),
                    diet: getCheckedValue(row, `diet`),
                    spesimen: getCheckedValue(row, `spesimen`),
                    diagnostik: getCheckedValue(row, `diagnostik`),
                    verbal_nama: getCheckedValue(row, `verbal_nama`),
                    verbal_tgl_lahir: getCheckedValue(row, `verbal_tgl_lahir`),
                    visual_nama: getCheckedValue(row, `visual_nama`),
                    visual_rm: getCheckedValue(row, `visual_rm`),
                    dilakukan: getCheckedValue(row, `dilakukan`),
                    tidak_dilakukan: getCheckedValue(row, `tidak_dilakukan`),
                };
                formData.entries.push(entry);
            });
            // Extract NB data from the specific NB row (names here are still prefixed as they are not dynamic rows)
            formData.nb = {
                verbal_visual: getCheckedValue(formElement, 'identifikasi_nb_verbal_visual'),
                '2_parameter': getCheckedValue(formElement, 'identifikasi_nb_2_parameter'),
                '1_parameter': getCheckedValue(formElement, 'identifikasi_nb_1_parameter'),
                tidak_dilakukan: getCheckedValue(formElement, 'identifikasi_nb_tidak_dilakukan'),
            };
            break;


        case 'wtri':
            formData.unit = formElement.querySelector('input[name="wtri_unit"]:checked')?.value;
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row):not(.rata-rata-row):not(.nb-row)').forEach(row => {
                // IMPORTANT CHANGE: Input names in addWtriRow are now simpler.
                const entry = {
                    tgl: getInputValue(row, `tgl`),
                    no_rm: getInputValue(row, `no_rm`),
                    nama_pasien: getInputValue(row, `nama_pasien`),
                    jam_reg_pendaftaran: getInputValue(row, `jam_reg_pendaftaran`),
                    jam_reg_poli: getInputValue(row, `jam_reg_poli`),
                    jam_dilayani_dokter: getInputValue(row, `jam_dilayani_dokter`),
                    respon_time_ca: getParsedInt(row, `respon_time_ca`),
                    pelayanan_percent_ca: getParsedInt(row, `pelayanan_percent_ca`),
                    respon_time_cb: getParsedInt(row, `respon_time_cb`),
                    pelayanan_percent_cb: getParsedInt(row, `pelayanan_percent_cb`),
                };
                formData.entries.push(entry);
            });
            break;

        case 'kritis-lab':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row):not(.rata-rata-row):not(.nb-row)').forEach(row => {
                // IMPORTANT CHANGE: Input names in addKritisLabRow are now simpler.
                const entry = {
                    tgl: getInputValue(row, `tgl`),
                    no_rm: getInputValue(row, `no_rm`),
                    nama_pasien: getInputValue(row, `nama_pasien`),
                    critical_value: getInputValue(row, `critical_value`),
                    waktu_hasil_keluar: getInputValue(row, `waktu_hasil_keluar`),
                    waktu_dilaporkan: getInputValue(row, `waktu_dilaporkan`),
                    nama_penerima: getInputValue(row, `nama_penerima`),
                    respon_time: getParsedInt(row, `respon_time`),
                    pelaporan_status: getInputValue(row, `pelaporan_status`),
                };
                formData.entries.push(entry);
            });
            break;

        case 'fornas':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row)').forEach((row) => { // Exclude total row
                // IMPORTANT CHANGE: Input names in addFornasRow are now simpler.
                const entry = {
                    unit_kerja: getInputValue(row, `unit_kerja`),
                    nama_pasien: getInputValue(row, `nama_pasien`),
                    no_rm: getInputValue(row, `no_rm`),
                    jumlah_resep: getParsedInt(row, `jumlah_resep`),
                    formularium_nasional: getCheckedValue(row, `formularium_nasional`),
                    non_formularium: getCheckedValue(row, `non_formularium`),
                };
                formData.entries.push(entry);
            });
            break;

        case 'visite':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row):not(.rata-rata-row):not(.nb-row)').forEach(row => {
                // IMPORTANT CHANGE: Input names in addVisiteRow are now simpler.
                const entry = {
                    tgl_registrasi: getInputValue(row, `tgl_registrasi`),
                    nama_pasien: getInputValue(row, `nama_pasien`),
                    no_rm: getInputValue(row, `no_rm`),
                    ruangan: getInputValue(row, `ruangan`),
                    jml_hari_efektif: getParsedInt(row, `jml_hari_efektif`),
                    jml_hari_rawat: getParsedInt(row, `jml_hari_rawat`),
                    dpjp_utama: getInputValue(row, `dpjp_utama`),
                    smf: getInputValue(row, `smf`),
                    tgl_visite: getInputValue(row, `tgl_visite`),
                    jam: getInputValue(row, `jam`),
                    val_i: getParsedInt(row, `val_i`),
                    val_ii: getParsedInt(row, `val_ii`),
                    val_iii: getParsedInt(row, `val_iii`),
                    val_iv: getParsedInt(row, `val_iv`),
                    total: getParsedInt(row, `total`),
                    jam_visite_akhir: getInputValue(row, `jam_visite_akhir`),
                };
                formData.entries.push(entry);
            });
            break;

        case 'jatuh':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row)').forEach((row) => { // Exclude total row
                // IMPORTANT CHANGE: Input names in addJatuhRow are now simpler.
                const entry = {
                    nama_pasien: getInputValue(row, `nama_pasien`),
                    no_rm: getInputValue(row, `no_rm`),
                    assessment_awal: getInputValue(row, `assessment_awal`),
                    assessment_ulang: getInputValue(row, `assessment_ulang`),
                    intervensi: getInputValue(row, `intervensi`),
                    ketiga_upaya_ya: getCheckedValue(row, `ketiga_upaya_ya`),
                    ketiga_upaya_tidak: getCheckedValue(row, `ketiga_upaya_tidak`),
                };
                formData.entries.push(entry);
            });
            formData.totals = {
                assessment_awal: parseInt(formElement.querySelector('input[name="jatuh_total_assessment_awal"]')?.value) || 0,
                assessment_ulang: parseInt(formElement.querySelector('input[name="jatuh_total_assessment_ulang"]')?.value) || 0,
                intervensi: parseInt(formElement.querySelector('input[name="jatuh_total_intervensi"]')?.value) || 0,
                ketiga_upaya_ya: parseInt(formElement.querySelector('input[name="jatuh_total_ketiga_upaya_ya"]')?.value) || 0,
                ketiga_upaya_tidak: parseInt(formElement.querySelector('input[name="jatuh_total_ketiga_upaya_tidak"]')?.value) || 0,
            };
            break;

        case 'cp':
            formData.ruangan = getInputValue(formElement, 'cp_ruangan');
            formData.judul_cp = getInputValue(formElement, 'cp_judul_cp');
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row):not(.rata-rata-row)').forEach((row) => { // Exclude total/rata-rata rows
                // IMPORTANT CHANGE: Input names in addCpRow are now simpler.
                const entry = {
                    no_mr: getInputValue(row, `no_mr`),
                    asesmen_p: getParsedInt(row, `asesmen_p`),
                    asesmen_n: getParsedInt(row, `asesmen_n`),
                    asesmen_c: getParsedInt(row, `asesmen_c`),
                    fisik_p: getParsedInt(row, `fisik_p`),
                    fisik_n: getParsedInt(row, `fisik_n`),
                    fisik_c: getParsedInt(row, `fisik_c`),
                    penunjang_p: getParsedInt(row, `penunjang_p`),
                    penunjang_n: getParsedInt(row, `penunjang_n`),
                    penunjang_c: getParsedInt(row, `penunjang_c`),
                    obat_p: getParsedInt(row, `obat_p`),
                    obat_n: getParsedInt(row, `obat_n`),
                    obat_c: getParsedInt(row, `obat_c`),
                    total: getParsedInt(row, `total`),
                    varian: getInputValue(row, `varian`),
                    ket: getInputValue(row, `ket`),
                };
                formData.entries.push(entry);
            });
            formData.totals = {
                asesmen_p: parseInt(formElement.querySelector('input[name="cp_total_asesmen_p"]')?.value) || 0,
                asesmen_n: parseInt(formElement.querySelector('input[name="cp_total_asesmen_n"]')?.value) || 0,
                asesmen_c: parseInt(formElement.querySelector('input[name="cp_total_asesmen_c"]')?.value) || 0,
                fisik_p: parseInt(formElement.querySelector('input[name="cp_total_fisik_p"]')?.value) || 0,
                fisik_n: parseInt(formElement.querySelector('input[name="cp_total_fisik_n"]')?.value) || 0,
                fisik_c: parseInt(formElement.querySelector('input[name="cp_total_fisik_c"]')?.value) || 0,
                penunjang_p: parseInt(formElement.querySelector('input[name="cp_total_penunjang_p"]')?.value) || 0,
                penunjang_n: parseInt(formElement.querySelector('input[name="cp_total_penunjang_n"]')?.value) || 0,
                penunjang_c: parseInt(formElement.querySelector('input[name="cp_total_penunjang_c"]')?.value) || 0,
                obat_p: parseInt(formElement.querySelector('input[name="cp_total_obat_p"]')?.value) || 0,
                obat_n: parseInt(formElement.querySelector('input[name="cp_total_obat_n"]')?.value) || 0,
                obat_c: parseInt(formElement.querySelector('input[name="cp_obat_c"]')?.value) || 0, // This name should also be fixed in Blade
                grand_total: parseInt(formElement.querySelector('input[name="cp_grand_total"]')?.value) || 0, // This name should also be fixed in Blade
            };
            formData.rata_rata_kepatuhan = getInputValue(formElement, 'cp_rata_rata_kepatuhan');
            break;

        case 'kepuasan':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row):not(.rata-rata-row):not(.nb-row)').forEach(row => {
                // IMPORTANT CHANGE: Input names in addKepuasanRow are now simpler.
                const entry = {
                    tanggal: getInputValue(row, `tanggal`),
                    unit_kerja: getInputValue(row, `unit_kerja`),
                    nilai_ikm: getInputValue(row, `nilai_ikm`),
                    jenis_pelayanan: getInputValue(row, `jenis_pelayanan`),
                    nilai_kepuasan: getInputValue(row, `nilai_kepuasan`),
                    komentar: getInputValue(row, `komentar`),
                };
                formData.entries.push(entry);
            });
            break;

        case 'krk':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row)').forEach((row) => { // Exclude total row
                // IMPORTANT CHANGE: Input names in addKrkRow are now simpler.
                const entry = {
                    tgl: getInputValue(row, `tgl`),
                    isi_komplain: getInputValue(row, `isi_komplain`),
                    kategori_komplain: getInputValue(row, `kategori_komplain`),
                    lisan: getCheckedValue(row, `lisan`),
                    tulisan: getCheckedValue(row, `tulisan`),
                    media_masa: getCheckedValue(row, `media_masa`),
                    grading_merah: getCheckedValue(row, `grading_merah`),
                    grading_kuning: getCheckedValue(row, `grading_kuning`),
                    grading_hijau: getCheckedValue(row, `grading_hijau`),
                    waktu_tanggap: getParsedInt(row, `waktu_tanggap`),
                    penyelesaian_ya: getCheckedValue(row, `penyelesaian_ya`),
                    penyelesaian_tidak: getCheckedValue(row, `penyelesaian_tidak`),
                    ket: getInputValue(row, `ket`),
                };
                formData.entries.push(entry);
            });
            break;

        case 'poe':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row):not(.rata-rata-row):not(.nb-row)').forEach(row => {
                // IMPORTANT CHANGE: Input names in addPoeRow are now simpler.
                const entry = {
                    tgl: getInputValue(row, `tgl`),
                    nama_pasien: getInputValue(row, `nama_pasien`),
                    no_rm: getInputValue(row, `no_rm`),
                    ruangan: getInputValue(row, `ruangan`),
                    diagnosa: getInputValue(row, `diagnosa`),
                    tindakan_bedah: getInputValue(row, `tindakan_bedah`),
                    dpjp_bedah: getInputValue(row, `dpjp_bedah`),
                    jam_rencana_operasi: getInputValue(row, `jam_rencana_operasi`),
                    jam_insisi: getInputValue(row, `jam_insisi`),
                    penundaan_gt_1hr: getCheckedValue(row, `penundaan_gt_1hr`),
                    penundaan_lt_1hr: getCheckedValue(row, `penundaan_lt_1hr`),
                    keterangan: getInputValue(row, `keterangan`),
                };
                formData.entries.push(entry);
            });
            break;

        case 'sc':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row):not(.rata-rata-row):not(.nb-row)').forEach(row => {
                // IMPORTANT CHANGE: Input names in addScRow are now simpler.
                const entry = {
                    nama_pasien: getInputValue(row, `nama_pasien`),
                    no_rm: getInputValue(row, `no_rm`),
                    diagnosa_kategori: getInputValue(row, `diagnosa_kategori`),
                    jam_tiba_igd: getInputValue(row, `jam_tiba_igd`),
                    jam_diputuskan_operasi: getInputValue(row, `jam_diputuskan_operasi`),
                    jam_mulai_insisi: getInputValue(row, `jam_mulai_insisi`),
                    waktu_tanggap: getParsedInt(row, `waktu_tanggap`),
                    gt_30_menit: getInputValue(row, `gt_30_menit`),
                    keterangan: getInputValue(row, `keterangan`),
                };
                formData.entries.push(entry);
            });
            break;

        default:
            // Generic data extraction for any top-level inputs if they exist (not in table rows)
            formElement.querySelectorAll('input, select, textarea').forEach(input => {
                // Exclude inputs that are part of dynamic rows, as they are handled in 'entries'
                if (input.name && !input.closest('tbody tr')) {
                    if (input.type === 'checkbox') {
                        formData[input.name] = input.checked;
                    } else if (input.type === 'radio') {
                        if (input.checked) {
                            formData[input.name] = input.value;
                        }
                    } else {
                        formData[input.name] = input.value;
                    }
                }
            });
            break;
    }
    return formData;
}


// --- Form-specific row handlers and calculations ---

/**
 * Adds a new row to the Hand Hygiene form table.
 * @param {HTMLElement} tbody - The tbody element of the table.
 * @param {number} index - The index for the new row.
 * @param {object} [entry={}] - Optional initial data for the row.
 */
function addHandHygieneRow(tbody, index, entry = {}) {
    const newRow = tbody.insertRow();
    newRow.innerHTML = `
        <td>${index}</td>
        <td>
            <input type="text" class="month-year-input" name="bulan" value="${entry.bulan || moment().format('YYYY-MM')}" placeholder="YYYY-MM" required />
        </td>
        <td><input type="number" min="1" value="${entry.sesi || 1}" class="sesi-input" name="sesi" required /></td>

        <td><input type="number" min="0" value="${entry.dpjp_kesempatan || 0}" name="dpjp_kesempatan" required /></td>
        <td><input type="number" min="0" value="${entry.dpjp_handwash || 0}" name="dpjp_handwash" required /></td>
        <td><input type="number" min="0" value="${entry.dpjp_handrub || 0}" name="dpjp_handrub" required /></td>

        <td><input type="number" min="0" value="${entry.perawat_kesempatan || 0}" name="perawat_kesempatan" required /></td>
        <td><input type="number" min="0" value="${entry.perawat_handwash || 0}" name="perawat_handwash" required /></td>
        <td><input type="number" min="0" value="${entry.perawat_handrub || 0}" name="perawat_handrub" required /></td>

        <td><input type="number" min="0" value="${entry.pendidikan_kesempatan || 0}" name="pendidikan_kesempatan" required /></td>
        <td><input type="number" min="0" value="${entry.pendidikan_handwash || 0}" name="pendidikan_handwash" required /></td>
        <td><input type="number" min="0" value="${entry.pendidikan_handrub || 0}" name="pendidikan_handrub" required /></td>

        <td><input type="number" min="0" value="${entry.lain_kesempatan || 0}" name="lain_kesempatan" required /></td>
        <td><input type="number" min="0" value="${entry.lain_handwash || 0}" name="lain_handwash" required /></td>
        <td><input type="number" min="0" value="${entry.lain_handrub || 0}" name="lain_handrub" required /></td>

        <td><input type="number" min="0" value="${entry.total_kesempatan || 0}" readonly name="total_kesempatan" /></td>
        <td><input type="number" min="0" value="${entry.total_handwash || 0}" readonly name="total_handwash" /></td>
        <td><input type="number" min="0" value="${entry.total_handrub || 0}" readonly name="total_handrub" /></td>
    `;

    // Attach event listeners for dynamic calculation
    newRow.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', function() {
            updateHandHygieneTotals(input.closest('.form-card')); // Pass the form element to update all row totals
        });
    });
    // Trigger initial calculation if data is provided
    updateHandHygieneTotals(newRow.closest('.form-card'));
}


/**
 * Adds a new row to the APD form table.
 * @param {HTMLElement} tbody - The tbody element of the table.
 * @param {number} index - The index for the new row.
 * @param {object} [entry={}] - Optional initial data for the row.
 */
function addApdRow(tbody, index, entry = {}) {
    const newRow = tbody.insertRow();
    newRow.innerHTML = `
        <td>${index}</td> <td><input type="date" name="tgl" value="${entry.tgl || ''}" required /></td>
        <td><input type="text" placeholder="Profesi" name="profesi" value="${entry.profesi || ''}" required /></td>
        <td><input type="text" placeholder="Ruang" name="ruang" value="${entry.ruang || ''}" required /></td>
        <td><input type="text" placeholder="Pelayanan" name="pelayanan" value="${entry.pelayanan || ''}" required /></td>
        <td><input type="checkbox" name="st_y" ${entry.sarung_tangan_y ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="st_t" ${entry.sarung_tangan_t ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="masker_y" ${entry.masker_y ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="masker_t" ${entry.masker_t ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="topi_y" ${entry.topi_y ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="topi_t" ${entry.topi_t ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="google_y" ${entry.google_y ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="google_t" ${entry.google_t ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="pakaian_y" ${entry.pakaian_y ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="pakaian_t" ${entry.pakaian_t ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="sepatu_y" ${entry.sepatu_y ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="sepatu_t" ${entry.sepatu_t ? 'checked' : ''} /></td>
        <td>
            <select name="kepatuhan" required>
                <option value="">Pilih</option>
                <option value="Patuh" ${entry.kepatuhan === 'Patuh' ? 'selected' : ''}>Patuh</option>
                <option value="Tidak" ${entry.kepatuhan === 'Tidak' ? 'selected' : ''}>Tidak</option>
            </select>
        </td>
        <td><input type="text" placeholder="Keterangan" name="ket" value="${entry.ket || ''}" required /></td>
    `;
}

/**
 * Adds a new row to the Identifikasi form table (excluding the NB row).
 * @param {HTMLElement} tbody - The tbody element of the table.
 * @param {number} index - The index for the new row.
 * @param {object} [entry={}] - Optional initial data for the row.
 */
function addIdentifikasiRow(tbody, index, entry = {}) {
    const insertIndex = getInsertionIndex(tbody, ['nb-row']); // Identifikasi has 'nb-row'
    const newRow = tbody.insertRow(insertIndex);
    newRow.innerHTML = `
        <td>${index}</td> <td><input type="date" name="tgl" value="${entry.tgl || ''}" required /></td>
        <td><input type="text" placeholder="Nama Staf" name="staf" value="${entry.staf || ''}" required /></td>
        <td><input type="checkbox" name="obat" ${entry.obat ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="darah" ${entry.darah ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="diet" ${entry.diet ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="spesimen" ${entry.spesimen ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="diagnostik" ${entry.diagnostik ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="verbal_nama" ${entry.verbal_nama ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="verbal_tgl_lahir" ${entry.verbal_tgl_lahir ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="visual_nama" ${entry.visual_nama ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="visual_rm" ${entry.visual_rm ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="dilakukan" ${entry.dilakukan ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="tidak_dilakukan" ${entry.tidak_dilakukan ? 'checked' : ''} /></td>
    `;
}

/**
 * Adds a new row to the WTRI form table.
 * @param {HTMLElement} tbody - The tbody element of the table.
 * @param {number} index - The index for the new row.
 * @param {object} [entry={}] - Optional initial data for the row.
 */
function addWtriRow(tbody, index, entry = {}) {
    const newRow = tbody.insertRow();
    newRow.innerHTML = `
        <td>${index}</td> <td><input type="date" name="tgl" value="${entry.tgl || ''}" required /></td>
        <td><input type="text" name="no_rm" value="${entry.no_rm || ''}" required /></td>
        <td><input type="text" name="nama_pasien" value="${entry.nama_pasien || ''}" required /></td>
        <td><input type="time" name="jam_reg_pendaftaran" value="${entry.jam_reg_pendaftaran || ''}" required /></td>
        <td><input type="time" name="jam_reg_poli" value="${entry.jam_reg_poli || ''}" required /></td>
        <td><input type="time" name="jam_dilayani_dokter" value="${entry.jam_dilayani_dokter || ''}" required /></td>
        <td><input type="number" value="${entry.respon_time_ca || 0}" name="respon_time_ca" readonly /></td>
        <td><input type="number" value="${entry.pelayanan_percent_ca || 0}" name="pelayanan_percent_ca" required /></td>
        <td><input type="number" value="${entry.respon_time_cb || 0}" name="respon_time_cb" required /></td>
        <td><input type="number" value="${entry.pelayanan_percent_cb || 0}" name="pelayanan_percent_cb" required /></td>
    `;
    const jamRegPendaftaranInput = newRow.querySelector(`input[name="jam_reg_pendaftaran"]`);
    const jamDilayaniDokterInput = newRow.querySelector(`input[name="jam_dilayani_dokter"]`);
    const responTimeCaInput = newRow.querySelector(`input[name="respon_time_ca"]`);

    const updateWtriResponTime = () => {
        if (jamRegPendaftaranInput.value && jamDilayaniDokterInput.value) {
            try {
                const reg = moment(`2000-01-01T${jamRegPendaftaranInput.value}:00`);
                const service = moment(`2000-01-01T${jamDilayaniDokterInput.value}:00`);
                if (reg.isValid() && service.isValid()) {
                    const diff = service.diff(reg, 'minutes');
                    responTimeCaInput.value = diff;
                } else {
                    responTimeCaInput.value = '';
                }
            } catch (error) {
                console.error("Error calculating WTPR response time:", error);
                responTimeCaInput.value = '';
            }
        } else {
            responTimeCaInput.value = '';
        }
    };
    jamRegPendaftaranInput.addEventListener('input', updateWtriResponTime);
    jamDilayaniDokterInput.addEventListener('input', updateWtriResponTime);
    updateWtriResponTime(); // Initial calculation
}

/**
 * Adds a new row to the Kritis Lab form table.
 * @param {HTMLElement} tbody - The tbody element of the table.
 * @param {number} index - The index for the new row.
 * @param {object} [entry={}] - Optional initial data for the row.
 */
function addKritisLabRow(tbody, index, entry = {}) {
    const newRow = tbody.insertRow();
    newRow.innerHTML = `
        <td>${index}</td> <td><input type="date" name="tgl" value="${entry.tgl || ''}" required /></td>
        <td><input type="text" name="no_rm" value="${entry.no_rm || ''}" required /></td>
        <td><input type="text" name="nama_pasien" value="${entry.nama_pasien || ''}" required /></td>
        <td><input type="text" name="critical_value" value="${entry.critical_value || ''}" required /></td>
        <td><input type="time" name="waktu_hasil_keluar" value="${entry.waktu_hasil_keluar || ''}" required /></td>
        <td><input type="time" name="waktu_dilaporkan" value="${entry.waktu_dilaporkan || ''}" required /></td>
        <td><input type="text" name="nama_penerima" value="${entry.nama_penerima || ''}" required /></td>
        <td><input type="number" name="respon_time" value="${entry.respon_time || 0}" readonly /></td>
        <td><select name="pelaporan_status" required>
            <option value="">Pilih</option>
            <option value="≤ 30 Menit" ${entry.pelaporan_status === '≤ 30 Menit' ? 'selected' : ''}>≤ 30 Menit</option>
            <option value="> 30 Menit" ${entry.pelaporan_status === '> 30 Menit' ? 'selected' : ''}>> 30 Menit</option>
        </select></td>
    `;

    const waktuHasilKeluarInput = newRow.querySelector(`input[name="waktu_hasil_keluar"]`);
    const waktuDilaporkanInput = newRow.querySelector(`input[name="waktu_dilaporkan"]`);
    const responTimeInput = newRow.querySelector(`input[name="respon_time"]`);
    const pelaporanStatusSelect = newRow.querySelector(`select[name="pelaporan_status"]`);

    const updateKritisLabResponTime = () => {
        if (waktuHasilKeluarInput.value && waktuDilaporkanInput.value) {
            try {
                const start = moment(`2000-01-01T${waktuHasilKeluarInput.value}:00`);
                const end = moment(`2000-01-01T${waktuDilaporkanInput.value}:00`);
                if (start.isValid() && end.isValid()) {
                    const diff = end.diff(start, 'minutes');
                    responTimeInput.value = diff;
                    pelaporanStatusSelect.value = diff <= 30 ? '≤ 30 Menit' : '> 30 Menit';
                } else {
                    responTimeInput.value = '';
                    pelaporanStatusSelect.value = '';
                }
            } catch (error) {
                console.error("Error calculating Kritis Lab response time:", error);
                responTimeInput.value = '';
                pelaporanStatusSelect.value = '';
            }
        } else {
            responTimeInput.value = '';
            pelaporanStatusSelect.value = '';
        }
    };
    waktuHasilKeluarInput.addEventListener('input', updateKritisLabResponTime);
    waktuDilaporkanInput.addEventListener('input', updateKritisLabResponTime);
    updateKritisLabResponTime(); // Initial calculation
}

/**
 * Adds a new row to the FORNAS form table.
 * @param {HTMLElement} tbody - The tbody element of the table.
 * @param {number} index - The index for the new row.
 * @param {object} [entry={}] - Optional initial data for the row.
 */
function addFornasRow(tbody, index, entry = {}) {
    const insertIndex = getInsertionIndex(tbody, ['total-row']); // Only exclude total-row for Fornas
    const newRow = tbody.insertRow(insertIndex); 
    newRow.innerHTML = `
        <td>${index}</td> <td><input type="text" placeholder="Unit Kerja" name="unit_kerja" value="${entry.unit_kerja || ''}" required /></td>
        <td><input type="text" placeholder="Nama Pasien" name="nama_pasien" value="${entry.nama_pasien || ''}" required /></td>
        <td><input type="text" placeholder="No. RM" name="no_rm" value="${entry.no_rm || ''}" required /></td>
        <td><input type="number" name="jumlah_resep" value="${entry.jumlah_resep || 0}" required /></td>
        <td><input type="checkbox" name="formularium_nasional" ${entry.formularium_nasional ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="non_formularium" ${entry.non_formularium ? 'checked' : ''} /></td>
    `;
}

/**
 * Generates time options for the Visite form's "Jam" dropdown.
 * @returns {string} HTML string of option elements.
 */
function generateTimeOptions() {
    let options = '<option value="">Pilih Jam</option>';
    for (let h = 0; h < 24; h++) {
        const hour = String(h).padStart(2, '0');
        const nextHour = String((h + 1) % 24).padStart(2, '0');
        options += `<option value="${hour}:00 - ${nextHour}:00">${hour}:00 - ${nextHour}:00</option>`;
    }
    return options;
}

/**
 * Adds a new row to the Visite form table.
 * @param {HTMLElement} tbody - The tbody element of the table.
 * @param {number} index - The index for the new row.
 * @param {object} [entry={}] - Optional initial data for the row.
 */
function addVisiteRow(tbody, index, entry = {}) {
    const newRow = tbody.insertRow();
    newRow.innerHTML = `
        <td>${index}</td> <td><input type="date" name="tgl_registrasi" value="${entry.tgl_registrasi || ''}" required /></td>
        <td><input type="text" name="nama_pasien" value="${entry.nama_pasien || ''}" required /></td>
        <td><input type="text" name="no_rm" value="${entry.no_rm || ''}" required /></td>
        <td><input type="text" name="ruangan" value="${entry.ruangan || ''}" required /></td>
        <td><input type="number" name="jml_hari_efektif" value="${entry.jml_hari_efektif || 0}" required /></td>
        <td><input type="number" name="jml_hari_rawat" value="${entry.jml_hari_rawat || 0}" required /></td>
        <td><input type="text" name="dpjp_utama" value="${entry.dpjp_utama || ''}" required /></td>
        <td><input type="text" name="smf" value="${entry.smf || ''}" required /></td>
        <td><input type="date" name="tgl_visite" value="${entry.tgl_visite || ''}" required /></td>
        <td>
            <select name="jam" required>
                ${generateTimeOptions()}
            </select>
        </td>
        <td><input type="number" name="val_i" value="${entry.val_i || 0}" readonly /></td>
        <td><input type="number" name="val_ii" value="${entry.val_ii || 0}" readonly /></td>
        <td><input type="number" name="val_iii" value="${entry.val_iii || 0}" readonly /></td>
        <td><input type="number" name="val_iv" value="${entry.val_iv || 0}" readonly /></td>
        <td><input type="number" readonly name="total" value="${entry.total || 0}" /></td>
        <td><input type="time" name="jam_visite_akhir" value="${entry.jam_visite_akhir || ''}" required /></td>
    `;

    const jamSelect = newRow.querySelector(`select[name="jam"]`);
    const totalInput = newRow.querySelector(`input[name="total"]`);
    const valI = newRow.querySelector(`input[name="val_i"]`);
    const valII = newRow.querySelector(`input[name="val_ii"]`);
    const valIII = newRow.querySelector(`input[name="val_iii"]`);
    const valIV = newRow.querySelector(`input[name="val_iv"]`);

    // Set initial selected value for the dropdown
    if (entry.jam) {
        jamSelect.value = entry.jam;
    }

    const updateVisiteTotal = () => {
        const selectedTimeRange = jamSelect.value;
        let score = 0;
        // Extract the start time from the range (e.g., "09:00" from "09:00 - 10:00")
        const jam = selectedTimeRange ? selectedTimeRange.split(' - ')[0] : '';

        if (jam) {
            const time = moment(jam, 'HH:mm');
            if (time.isSameOrBefore(moment('10:00', 'HH:mm'))) {
                score = 100;
                valI.value = 1; valII.value = 0; valIII.value = 0; valIV.value = 0;
            } else if (time.isAfter(moment('10:00', 'HH:mm')) && time.isSameOrBefore(moment('12:00', 'HH:mm'))) {
                score = 50;
                valI.value = 0; valII.value = 1; valIII.value = 0; valIV.value = 0;
            } else if (time.isAfter(moment('12:00', 'HH:mm')) && time.isSameOrBefore(moment('14:00', 'HH:mm'))) {
                score = 25;
                valI.value = 0; valII.value = 0; valIII.value = 1; valIV.value = 0;
            } else {
                score = 0;
                valI.value = 0; valII.value = 0; valIII.value = 0; valIV.value = 1;
            }
        } else {
            valI.value = 0; valII.value = 0; valIII.value = 0; valIV.value = 0;
        }
        totalInput.value = score;
    };

    jamSelect.addEventListener('change', updateVisiteTotal); // Use 'change' for select elements
    updateVisiteTotal(); // Initial calculation
}

/**
 * Adds a new row to the Jatuh form table.
 * @param {HTMLElement} tbody - The tbody element of the table.
 * @param {number} index - The index for the new row.
 * @param {object} [entry={}] - Optional initial data for the row.
 */
function addJatuhRow(tbody, index, entry = {}) {
    const insertIndex = getInsertionIndex(tbody, ['total-row']); // Only exclude total-row for Fornas
    const newRow = tbody.insertRow(insertIndex);
    newRow.innerHTML = `
        <td>${index}</td> <td><input type="text" placeholder="Nama Pasien" name="nama_pasien" value="${entry.nama_pasien || ''}" required /></td>
        <td><input type="text" placeholder="No. RM" name="no_rm" value="${entry.no_rm || ''}" required /></td>
        <td><select name="assessment_awal" required>
            <option value="">Pilih</option>
            <option value="Ya" ${entry.assessment_awal === 'Ya' ? 'selected' : ''}>Ya</option>
            <option value="Tidak" ${entry.assessment_awal === 'Tidak' ? 'selected' : ''}>Tidak</option>
        </select></td>
        <td><select name="assessment_ulang" required>
            <option value="">Pilih</option>
            <option value="Ya" ${entry.assessment_ulang === 'Ya' ? 'selected' : ''}>Ya</option>
            <option value="Tidak" ${entry.assessment_ulang === 'Tidak' ? 'selected' : ''}>Tidak</option>
        </select></td>
        <td><select name="intervensi" required>
            <option value="">Pilih</option>
            <option value="Ya" ${entry.intervensi === 'Ya' ? 'selected' : ''}>Ya</option>
            <option value="Tidak" ${entry.intervensi === 'Tidak' ? 'selected' : ''}>Tidak</option>
        </select></td>
        <td><input type="checkbox" name="ketiga_upaya_ya" ${entry.ketiga_upaya_ya ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="ketiga_upaya_tidak" ${entry.ketiga_upaya_tidak ? 'checked' : ''} /></td>
    `;
    const selects = newRow.querySelectorAll('select');
    const yaCheckbox = newRow.querySelector(`input[name="ketiga_upaya_ya"]`);
    const tidakCheckbox = newRow.querySelector(`input[name="ketiga_upaya_tidak"]`);

    const updateJatuhCheckboxes = () => {
        // Ensure all selects have a 'Ya' value and are not empty
        const allYa = Array.from(selects).every(s => s.value === 'Ya');
        yaCheckbox.checked = allYa;
        tidakCheckbox.checked = !allYa;
    };
    selects.forEach(select => select.addEventListener('change', () => {
        updateJatuhCheckboxes();
        updateJatuhTotals(select.closest('.form-card'));
    }));
    updateJatuhCheckboxes(); // Initial check
}

/**
 * Updates total calculations for the Jatuh form.
 * @param {HTMLElement} formElement - The Jatuh form element.
 */
function updateJatuhTotals(formElement) {
    let totalAssessmentAwal = 0;
    let totalAssessmentUlang = 0;
    let totalIntervensi = 0;
    let totalKetigaUpayaYa = 0;
    let totalKetigaUpayaTidak = 0;

    formElement.querySelectorAll('.form-table tbody tr:not(.total-row)').forEach(row => {
        // Find the index of the current row relative to its siblings in the tbody (excluding total row)
        // No need for index for input names here due to new naming convention
        if (row.querySelector(`select[name="assessment_awal"]`)?.value === 'Ya') totalAssessmentAwal++;
        if (row.querySelector(`select[name="assessment_ulang"]`)?.value === 'Ya') totalAssessmentUlang++;
        if (row.querySelector(`select[name="intervensi"]`)?.value === 'Ya') totalIntervensi++;
        if (row.querySelector(`input[name="ketiga_upaya_ya"]`)?.checked) totalKetigaUpayaYa++;
        if (row.querySelector(`input[name="ketiga_upaya_tidak"]`)?.checked) totalKetigaUpayaTidak++;
    });

    formElement.querySelector('input[name="jatuh_total_assessment_awal"]').value = totalAssessmentAwal;
    formElement.querySelector('input[name="jatuh_total_assessment_ulang"]').value = totalAssessmentUlang;
    formElement.querySelector('input[name="jatuh_total_intervensi"]').value = totalIntervensi;
    formElement.querySelector('input[name="jatuh_total_ketiga_upaya_ya"]').value = totalKetigaUpayaYa;
    formElement.querySelector('input[name="jatuh_total_ketiga_upaya_tidak"]').value = totalKetigaUpayaTidak;
}

/**
 * Adds a new row to the CP form table.
 * @param {HTMLElement} tbody - The tbody element of the table.
 * @param {number} index - The index for the new row.
 * @param {object} [entry={}] - Optional initial data for the row.
 */
function addCpRow(tbody, index, entry = {}) {
    // Locate the total/rata-rata rows to insert before them
     // Determine where to insert: before total-row or rata-rata-row
    const insertIndex = getInsertionIndex(tbody, ['total-row', 'rata-rata-row']);
    const newRow = tbody.insertRow(insertIndex);
    newRow.innerHTML = `
        <td>${index}</td> <td><input type="text" placeholder="No. MR" name="no_mr" value="${entry.no_mr || ''}" required /></td>
        <td><input type="number" name="asesmen_p" value="${entry.asesmen_p || 0}" required /></td>
        <td><input type="number" name="asesmen_n" value="${entry.asesmen_n || 0}" required /></td>
        <td><input type="number" name="asesmen_c" value="${entry.asesmen_c || 0}" required /></td>
        <td><input type="number" name="fisik_p" value="${entry.fisik_p || 0}" required /></td>
        <td><input type="number" name="fisik_n" value="${entry.fisik_n || 0}" required /></td>
        <td><input type="number" name="fisik_c" value="${entry.fisik_c || 0}" required /></td>
        <td><input type="number" name="penunjang_p" value="${entry.penunjang_p || 0}" required /></td>
        <td><input type="number" name="penunjang_n" value="${entry.penunjang_n || 0}" required /></td>
        <td><input type="number" name="penunjang_c" value="${entry.penunjang_c || 0}" required /></td>
        <td><input type="number" name="obat_p" value="${entry.obat_p || 0}" required /></td>
        <td><input type="number" name="obat_n" value="${entry.obat_n || 0}" required /></td>
        <td><input type="number" name="obat_c" value="${entry.obat_c || 0}" required /></td>
        <td><input type="number" readonly name="total" value="${entry.total || 0}" /></td>
        <td><input type="text" name="varian" value="${entry.varian || ''}" required /></td>
        <td><input type="text" name="ket" value="${entry.ket || ''}" required /></td>
    `;
    const inputs = newRow.querySelectorAll('input[type="number"]');
    inputs.forEach(input => input.addEventListener('input', () => updateCpTotals(input.closest('.form-card'))));
}

/**
 * Updates total calculations for the CP form.
 * @param {HTMLElement} formElement - The CP form element.
 */
function updateCpTotals(formElement) {
    let totalAsesmenP = 0, totalAsesmenN = 0, totalAsesmenC = 0;
    let totalFisikP = 0, totalFisikN = 0, totalFisikC = 0;
    let totalPenunjangP = 0, totalPenunjangN = 0, totalPenunjangC = 0;
    let totalObatP = 0, totalObatN = 0, totalObatC = 0;
    let grandTotal = 0;

    formElement.querySelectorAll('.form-table tbody tr:not(.total-row):not(.rata-rata-row)').forEach(row => {
        // IMPORTANT CHANGE: Input names are now simple.
        const asesmen_p = parseInt(row.querySelector(`input[name="asesmen_p"]`)?.value) || 0;
        const asesmen_n = parseInt(row.querySelector(`input[name="asesmen_n"]`)?.value) || 0;
        const asesmen_c = parseInt(row.querySelector(`input[name="asesmen_c"]`)?.value) || 0;
        const fisik_p = parseInt(row.querySelector(`input[name="fisik_p"]`)?.value) || 0;
        const fisik_n = parseInt(row.querySelector(`input[name="fisik_n"]`)?.value) || 0;
        const fisik_c = parseInt(row.querySelector(`input[name="fisik_c"]`)?.value) || 0;
        const penunjang_p = parseInt(row.querySelector(`input[name="penunjang_p"]`)?.value) || 0;
        const penunjang_n = parseInt(row.querySelector(`input[name="penunjang_n"]`)?.value) || 0;
        const penunjang_c = parseInt(row.querySelector(`input[name="penunjang_c"]`)?.value) || 0;
        const obat_p = parseInt(row.querySelector(`input[name="obat_p"]`)?.value) || 0;
        const obat_n = parseInt(row.querySelector(`input[name="obat_n"]`)?.value) || 0;
        const obat_c = parseInt(row.querySelector(`input[name="obat_c"]`)?.value) || 0;

        const rowTotal = asesmen_p + asesmen_n + asesmen_c + fisik_p + fisik_n + fisik_c + penunjang_p + penunjang_n + penunjang_c + obat_p + obat_n + obat_c;
        row.querySelector(`input[name="total"]`).value = rowTotal; // Changed from `cp_total_${index}` to `total`

        totalAsesmenP += asesmen_p; totalAsesmenN += asesmen_n; totalAsesmenC += asesmen_c;
        totalFisikP += fisik_p; totalFisikN += fisik_n; totalFisikC += fisik_c;
        totalPenunjangP += penunjang_p; totalPenunjangN += penunjang_n; totalPenunjangC += penunjang_c;
        totalObatP += obat_p; totalObatN += obat_n; totalObatC += obat_c;
        grandTotal += rowTotal;
    });

    // These should already have fixed names in Blade, not dynamic indices.
    formElement.querySelector('input[name="cp_total_asesmen_p"]').value = totalAsesmenP;
    formElement.querySelector('input[name="cp_total_asesmen_n"]').value = totalAsesmenN;
    formElement.querySelector('input[name="cp_total_asesmen_c"]').value = totalAsesmenC;
    formElement.querySelector('input[name="cp_total_fisik_p"]').value = totalFisikP;
    formElement.querySelector('input[name="cp_total_fisik_n"]').value = totalFisikN;
    formElement.querySelector('input[name="cp_total_fisik_c"]').value = totalFisikC;
    formElement.querySelector('input[name="cp_total_penunjang_p"]').value = totalPenunjangP;
    formElement.querySelector('input[name="cp_total_penunjang_n"]').value = totalPenunjangN;
    formElement.querySelector('input[name="cp_total_penunjang_c"]').value = totalPenunjangC;
    formElement.querySelector('input[name="cp_total_obat_p"]').value = totalObatP;
    formElement.querySelector('input[name="cp_total_obat_n"]').value = totalObatN;
    formElement.querySelector('input[name="cp_total_obat_c"]').value = totalObatC;
    formElement.querySelector('input[name="cp_grand_total"]').value = grandTotal;

    const totalObservedItems = totalAsesmenP + totalAsesmenN + totalAsesmenC + totalFisikP + totalFisikN + totalFisikC + totalPenunjangP + totalPenunjangN + totalPenunjangC + totalObatP + totalObatN + totalObatC;
    const compliantItems = totalAsesmenP + totalFisikP + totalPenunjangP + totalObatP;
    const compliance = totalObservedItems > 0 ? (compliantItems / totalObservedItems) * 100 : 0; // Assuming 'P' means compliant
    formElement.querySelector('input[name="cp_rata_rata_kepatuhan"]').value = compliance.toFixed(2) + '%';
}


/**
 * Adds a new row to the Kepuasan form table.
 * @param {HTMLElement} tbody - The tbody element of the table.
 * @param {number} index - The index for the new row.
 * @param {object} [entry={}] - Optional initial data for the row.
 */
function addKepuasanRow(tbody, index, entry = {}) {
    const newRow = tbody.insertRow();
    newRow.innerHTML = `
        <td>${index}</td> <td><input type="date" name="tanggal" value="${entry.tanggal || ''}" required /></td>
        <td><input type="text" name="unit_kerja" value="${entry.unit_kerja || ''}" required /></td>
        <td><input type="text" name="nilai_ikm" value="${entry.nilai_ikm || ''}" required /></td>
        <td><select name="jenis_pelayanan" required>
            <option value="">Pilih</option>
            <option ${entry.jenis_pelayanan === 'Rawat Jalan' ? 'selected' : ''}>Rawat Jalan</option>
            <option ${entry.jenis_pelayanan === 'Rawat Inap' ? 'selected' : ''}>Rawat Inap</option>
            <option ${entry.jenis_pelayanan === 'IGD' ? 'selected' : ''}>IGD</option>
            <option ${entry.jenis_pelayanan === 'Farmasi' ? 'selected' : ''}>Farmasi</option>
            <option ${entry.jenis_pelayanan === 'Laboratorium' ? 'selected' : ''}>Laboratorium</option>
        </select></td>
        <td><select name="nilai_kepuasan" required>
            <option value="">Pilih</option>
            <option ${entry.nilai_kepuasan === '1 (Sangat Tidak Puas)' ? 'selected' : ''}>1 (Sangat Tidak Puas)</option>
            <option ${entry.nilai_kepuasan === '2 (Tidak Puas)' ? 'selected' : ''}>2 (Tidak Puas)</option>
            <option ${entry.nilai_kepuasan === '3 (Cukup Puas)' ? 'selected' : ''}>3 (Cukup Puas)</option>
            <option ${entry.nilai_kepuasan === '4 (Puas)' ? 'selected' : ''}>4 (Puas)</option>
            <option ${entry.nilai_kepuasan === '5 (Sangat Puas)' ? 'selected' : ''}>5 (Sangat Puas)</option>
        </select></td>
        <td><input type="text" name="komentar" value="${entry.komentar || ''}" required /></td>
    `;
}

/**
 * Adds a new row to the KRK form table.
 * @param {HTMLElement} tbody - The tbody element of the table.
 * @param {number} index - The index for the new row.
 * @param {object} [entry={}] - Optional initial data for the row.
 */
function addKrkRow(tbody, index, entry = {}) {
    const insertIndex = getInsertionIndex(tbody, ['total-row']);
    const newRow = tbody.insertRow(insertIndex);
    newRow.innerHTML = `
        <td>${index}</td> <td><input type="date" name="tgl" value="${entry.tgl || ''}" required /></td>
        <td><input type="text" name="isi_komplain" value="${entry.isi_komplain || ''}" required /></td>
        <td><input type="text" name="kategori_komplain" value="${entry.kategori_komplain || ''}" required /></td>
        <td><input type="checkbox" name="lisan" ${entry.lisan ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="tulisan" ${entry.tulisan ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="media_masa" ${entry.media_masa ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="grading_merah" ${entry.grading_merah ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="grading_kuning" ${entry.grading_kuning ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="grading_hijau" ${entry.grading_hijau ? 'checked' : ''} /></td>
        <td><input type="number" name="waktu_tanggap" value="${entry.waktu_tanggap || 0}" required /></td>
        <td><input type="checkbox" name="penyelesaian_ya" ${entry.penyelesaian_ya ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="penyelesaian_tidak" ${entry.penyelesaian_tidak ? 'checked' : ''} /></td>
        <td><input type="text" name="ket" value="${entry.ket || ''}" required /></td>
    `;
}

/**
 * Adds a new row to the POE form table.
 * @param {HTMLElement} tbody - The tbody element of the table.
 * @param {number} index - The index for the new row.
 * @param {object} [entry={}] - Optional initial data for the row.
 */
function addPoeRow(tbody, index, entry = {}) {
    const newRow = tbody.insertRow();
    newRow.innerHTML = `
        <td>${index}</td> <td><input type="date" name="tgl" value="${entry.tgl || ''}" required /></td>
        <td><input type="text" name="nama_pasien" value="${entry.nama_pasien || ''}" required /></td>
        <td><input type="text" name="no_rm" value="${entry.no_rm || ''}" required /></td>
        <td><input type="text" name="ruangan" value="${entry.ruangan || ''}" required /></td>
        <td><input type="text" name="diagnosa" value="${entry.diagnosa || ''}" required /></td>
        <td><input type="text" name="tindakan_bedah" value="${entry.tindakan_bedah || ''}" required /></td>
        <td><input type="text" name="dpjp_bedah" value="${entry.dpjp_bedah || ''}" required /></td>
        <td><input type="time" name="jam_rencana_operasi" value="${entry.jam_rencana_operasi || ''}" required /></td>
        <td><input type="time" name="jam_insisi" value="${entry.jam_insisi || ''}" required /></td>
        <td><input type="checkbox" name="penundaan_gt_1hr" ${entry.penundaan_gt_1hr ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="penundaan_lt_1hr" ${entry.penundaan_lt_1hr ? 'checked' : ''} /></td>
        <td><input type="text" name="keterangan" value="${entry.keterangan || ''}" required /></td>
    `;
    const jamRencanaInput = newRow.querySelector(`input[name="jam_rencana_operasi"]`);
    const jamInsisiInput = newRow.querySelector(`input[name="jam_insisi"]`);
    const penundaanGt1Hr = newRow.querySelector(`input[name="penundaan_gt_1hr"]`);
    const penundaanLt1Hr = newRow.querySelector(`input[name="penundaan_lt_1hr"]`);

    const updatePoePenundaan = () => {
        if (jamRencanaInput.value && jamInsisiInput.value) {
            try {
                const rencana = moment(`2000-01-01T${jamRencanaInput.value}:00`);
                const insisi = moment(`2000-01-01T${jamInsisiInput.value}:00`);
                if (rencana.isValid() && insisi.isValid()) {
                    const diff = insisi.diff(rencana, 'minutes');
                    penundaanGt1Hr.checked = diff > 60;
                    penundaanLt1Hr.checked = diff <= 60;
                } else {
                    penundaanGt1Hr.checked = false;
                    penundaanLt1Hr.checked = false;
                }
            } catch (error) {
                console.error("Error calculating POE penundaan:", error);
                penundaanGt1Hr.checked = false;
                penundaanLt1Hr.checked = false;
            }
        } else {
            penundaanGt1Hr.checked = false;
            penundaanLt1Hr.checked = false;
        }
    };
    jamRencanaInput.addEventListener('input', updatePoePenundaan);
    jamInsisiInput.addEventListener('input', updatePoePenundaan);
    updatePoePenundaan(); // Initial calculation
}


/**
 * Adds a new row to the SC form table.
 * @param {HTMLElement} tbody - The tbody element of the table.
 * @param {number} index - The index for the new row.
 * @param {object} [entry={}] - Optional initial data for the row.
 */
function addScRow(tbody, index, entry = {}) {
    const newRow = tbody.insertRow();
    newRow.innerHTML = `
        <td>${index}</td> <td><input type="text" name="nama_pasien" value="${entry.nama_pasien || ''}" required /></td>
        <td><input type="text" name="no_rm" value="${entry.no_rm || ''}" required /></td>
        <td><select name="diagnosa_kategori" required>
            <option value="">Pilih</option>
            <option ${entry.diagnosa_kategori === 'Kategori I' ? 'selected' : ''}>Kategori I</option>
            <option ${entry.diagnosa_kategori === 'Kategori II' ? 'selected' : ''}>Kategori II</option>
            <option ${entry.diagnosa_kategori === 'Kategori III' ? 'selected' : ''}>Kategori III</option>
        </select></td>
        <td><input type="time" name="jam_tiba_igd" value="${entry.jam_tiba_igd || ''}" required /></td>
        <td><input type="time" name="jam_diputuskan_operasi" value="${entry.jam_diputuskan_operasi || ''}" required /></td>
        <td><input type="time" name="jam_mulai_insisi" value="${entry.jam_mulai_insisi || ''}" required /></td>
        <td><input type="number" name="waktu_tanggap" value="${entry.waktu_tanggap || 0}" readonly /></td>
        <td><select name="gt_30_menit" required>
            <option value="">Pilih</option>
            <option ${entry.gt_30_menit === 'Ya' ? 'selected' : ''}>Ya</option>
            <option ${entry.gt_30_menit === 'Tidak' ? 'selected' : ''}>Tidak</option>
        </select></td>
        <td><input type="text" name="keterangan" value="${entry.keterangan || ''}" required /></td>
    `;

    const jamDiputuskanOperasiInput = newRow.querySelector(`input[name="jam_diputuskan_operasi"]`);
    const jamMulaiInsisiInput = newRow.querySelector(`input[name="jam_mulai_insisi"]`);
    const waktuTanggapInput = newRow.querySelector(`input[name="waktu_tanggap"]`);
    const gt30MenitSelect = newRow.querySelector(`select[name="gt_30_menit"]`);

    const updateScWaktuTanggap = () => {
        if (jamDiputuskanOperasiInput.value && jamMulaiInsisiInput.value) {
            try {
                const diputuskan = moment(`2000-01-01T${jamDiputuskanOperasiInput.value}:00`);
                const insisi = moment(`2000-01-01T${jamMulaiInsisiInput.value}:00`);
                if (diputuskan.isValid() && insisi.isValid()) {
                    const diff = insisi.diff(diputuskan, 'minutes');
                    waktuTanggapInput.value = diff;
                    gt30MenitSelect.value = diff > 30 ? 'Ya' : 'Tidak';
                } else {
                    waktuTanggapInput.value = '';
                    gt30MenitSelect.value = '';
                }
            } catch (error) {
                console.error("Error calculating SC waktu tanggap:", error);
                waktuTanggapInput.value = '';
                gt30MenitSelect.value = '';
            }
        } else {
            waktuTanggapInput.value = '';
            gt30MenitSelect.value = '';
        }
    };
    jamDiputuskanOperasiInput.addEventListener('input', updateScWaktuTanggap);
    jamMulaiInsisiInput.addEventListener('input', updateScWaktuTanggap);
    updateScWaktuTanggap(); // Initial calculation
}

/**
 * Updates total calculations for the Hand Hygiene form.
 * @param {HTMLElement} formElement - The Hand Hygiene form element (not just a row).
 */
function updateHandHygieneTotals(formElement) {
    formElement.querySelectorAll('.form-table tbody tr:not(.total-row):not(.rata-rata-row):not(.nb-row)').forEach(row => {
        // Now use the simplified names
        const getVal = (name) => {
            const input = row.querySelector(`input[name="${name}"]`);
            return parseInt(input?.value) || 0;
        };

        const dpjp_kesempatan = getVal('dpjp_kesempatan');
        const dpjp_handwash = getVal('dpjp_handwash');
        const dpjp_handrub = getVal('dpjp_handrub');

        const perawat_kesempatan = getVal('perawat_kesempatan');
        const perawat_handwash = getVal('perawat_handwash');
        const perawat_handrub = getVal('perawat_handrub');

        const pendidikan_kesempatan = getVal('pendidikan_kesempatan');
        const pendidikan_handwash = getVal('pendidikan_handwash');
        const pendidikan_handrub = getVal('pendidikan_handrub');

        const lain_kesempatan = getVal('lain_kesempatan');
        const lain_handwash = getVal('lain_handwash');
        const lain_handrub = getVal('lain_handrub');

        const total_kesempatan = dpjp_kesempatan + perawat_kesempatan + pendidikan_kesempatan + lain_kesempatan;
        const total_handwash = dpjp_handwash + perawat_handwash + pendidikan_handwash + lain_handwash; 
        const total_handrub = dpjp_handrub + perawat_handrub + pendidikan_handrub + lain_handrub;


        // Update total inputs for this specific row using simplified names
        row.querySelector(`input[name="total_kesempatan"]`).value = total_kesempatan;
        row.querySelector(`input[name="total_handwash"]`).value = total_handwash;
        row.querySelector(`input[name="total_handrub"]`).value = total_handrub;
    });
}


// --- Main Application Logic ---

document.addEventListener('DOMContentLoaded', async function() {
    showLoading();
    await initializeData();
    // checkAndAutoSubmitOldForms(); // This function logic might need adjustment based on your backend completion status
    updateStatisticsDisplay();
    showSection('list'); // Show the list after everything is loaded and updated
    setupFormEventListeners(); // Call this function to set up all event listeners
    hideLoading();
    showNotification('Selamat datang di Dashboard Indikator Mutu!', 'info');
});

/**
 * Initializes data by fetching current and historical data for all forms.
 */
async function initializeData() {
    for (const indicator of indicators) {
        try {
            // Fetch current data
            const currentResponse = await authenticatedFetch(`${API_BASE_URL}/${indicator.id}/current`);
            if (currentResponse.ok) {
                formCurrentData[indicator.id] = await currentResponse.json();
            } else {
                console.warn(`Failed to fetch current data for ${indicator.id}:`, currentResponse.statusText);
                formCurrentData[indicator.id] = { data: {} }; // Ensure it's an object even if empty
            }

            // Fetch history data
            const historyResponse = await authenticatedFetch(`${API_BASE_URL}/${indicator.id}/history`);
            if (historyResponse.ok) {
                formHistoryData[indicator.id] = await historyResponse.json();
            } else {
                console.warn(`Failed to fetch history data for ${indicator.id}:`, historyResponse.statusText);
                formHistoryData[indicator.id] = []; // Ensure it's an array even if empty
            }
        } catch (error) {
            console.error(`Error initializing data for ${indicator.id}:`, error);
            formCurrentData[indicator.id] = { data: {} };
            formHistoryData[indicator.id] = [];
        }
    }
}

/**
 * Checks for incomplete forms from previous weeks and attempts to auto-submit them.
 * NOTE: This function's effectiveness heavily relies on how "complete" is determined
 * by your backend. If the backend doesn't save a clear 'completed' status, this
 * might need further refinement or a different approach for status management.
 */
async function checkAndAutoSubmitOldForms() {
    const today = moment().startOf('day');
    const currentWeekStart = today.clone().startOf('isoWeek'); // Monday as start of week

    for (const indicator of indicators) {
        const currentData = formCurrentData[indicator.id];

        // Check if current data exists, has a week_start_date, and is from a previous week
        if (currentData && currentData.week_start_date) {
            const dataWeekStart = moment(currentData.week_start_date).startOf('isoWeek');

            // If it's an old week's data AND it's not marked complete (based on current client-side check)
            if (dataWeekStart.isBefore(currentWeekStart) && !isFormComplete(indicator.id, currentData.data)) {
                console.log(`Auto-submitting incomplete form for ${indicator.id} from week ${dataWeekStart.format('YYYY-MM-DD')}`);
                // Save the data for the *previous* week. This should ideally mark it as complete on the backend.
                await saveFormData(indicator.id, dataWeekStart.format('YYYY-MM-DD'), currentData.data);
            }
        }
    }
    // After potential auto-submissions, re-initialize to reflect any changes
    // await initializeData(); // This might cause a loop if saveFormData also triggers updates
    updateStatisticsDisplay(); // Update display based on current local state
}


/**
 * Filters the entries displayed in the table based on the selected month.
 * This is a client-side filtering function.
 * @param {HTMLElement} formElement - The current form HTML element.
 * @param {string} formType - The type of form (e.g., 'visite').
 * @param {object} fullData - The full data object for the form, containing all entries.
 */
function filterEntriesByMonth(formElement, formType, fullData) {
    const monthInput = formElement.querySelector('input[type="month"][name$="_bulan"]');
    const selectedMonth = monthInput ? monthInput.value : ''; // Format YYYY-MM

    const tbody = formElement.querySelector('.form-table tbody');
    if (!tbody || !fullData.entries) return;

    // Preserve static rows by filtering them out, clearing tbody, then re-appending them
    const rowsToPreserve = Array.from(tbody.children).filter(row =>
        row.classList.contains('total-row') ||
        row.classList.contains('rata-rata-row') ||
        row.classList.contains('nb-row')
    );
    tbody.innerHTML = ''; // Clear all content
    rowsToPreserve.forEach(row => tbody.appendChild(row)); // Re-append preserved rows

    let filteredEntries = fullData.entries;

    if (selectedMonth) {
        filteredEntries = fullData.entries.filter(entry => {
            // Adapt property name based on form type for date checking
            let entryDateString;
            if (formType === 'hand-hygiene') {
                entryDateString = entry.bulan; // Hand Hygiene uses 'bulan' field directly (YYYY-MM format)
            } else if (entry.tgl) {
                entryDateString = entry.tgl; // Many forms use 'tgl' (YYYY-MM-DD format)
            } else if (entry.tanggal) {
                entryDateString = entry.tanggal; // Kepuasan uses 'tanggal' (YYYY-MM-DD format)
            } else if (entry.tgl_registrasi) {
                entryDateString = entry.tgl_registrasi; // Visite uses 'tgl_registrasi' (YYYY-MM-DD format)
            }

            if (entryDateString) {
                // For date fields (YYYY-MM-DD), extract YYYY-MM
                if (entryDateString.length >= 7 && entryDateString.includes('-')) {
                    return entryDateString.substring(0, 7) === selectedMonth;
                }
                // For month fields (YYYY-MM)
                return entryDateString === selectedMonth;
            }
            return false; // No date/month field to compare
        });
    }

    // Sort filtered entries by their original 'no' for consistent numbering if not already
    filteredEntries.sort((a, b) => (a.no || 0) - (b.no || 0));

    // Repopulate tbody with filtered entries
    filteredEntries.forEach((entry, index) => {
        const newIndex = index + 1; // Ensure sequential numbering after filtering
        if (formType === 'hand-hygiene') {
            addHandHygieneRow(tbody, newIndex, entry);
        } else if (formType === 'apd') {
            addApdRow(tbody, newIndex, entry);
        } else if (formType === 'identifikasi') {
            addIdentifikasiRow(tbody, newIndex, entry);
        } else if (formType === 'wtri') {
            addWtriRow(tbody, newIndex, entry);
        } else if (formType === 'kritis-lab') {
            addKritisLabRow(tbody, newIndex, entry);
        } else if (formType === 'fornas') {
            addFornasRow(tbody, newIndex, entry);
        } else if (formType === 'visite') {
            addVisiteRow(tbody, newIndex, entry);
        } else if (formType === 'jatuh') {
            addJatuhRow(tbody, newIndex, entry);
        } else if (formType === 'cp') {
            addCpRow(tbody, newIndex, entry);
        } else if (formType === 'kepuasan') {
            addKepuasanRow(tbody, newIndex, entry);
        } else if (formType === 'krk') {
            addKrkRow(tbody, newIndex, entry);
        } else if (formType === 'poe') {
            addPoeRow(tbody, newIndex, entry);
        } else if (formType === 'sc') {
            addScRow(tbody, newIndex, entry);
        }
    });

    // IMPORTANT: Re-number rows after all filtered entries have been added.
    // The renumberTableRows function has been simplified to only update the displayed 'No'.
    if (formType === 'identifikasi') {
        renumberTableRows(tbody, ['nb-row']);
    } else if (['fornas', 'jatuh', 'krk'].includes(formType)) {
        renumberTableRows(tbody, ['total-row']);
    } else if (formType === 'cp') {
        renumberTableRows(tbody, ['total-row', 'rata-rata-row']);
    } else {
        renumberTableRows(tbody, []); // For forms with no specific static rows at the end
    }

    // Re-run totals if the form has them (e.g., CP, Jatuh, Hand Hygiene)
    if (formType === 'cp') {
        updateCpTotals(formElement);
    } else if (formType === 'jatuh') {
        updateJatuhTotals(formElement);
    } else if (formType === 'hand-hygiene') {
        updateHandHygieneTotals(formElement);
    }
}


/**
 * Saves form data to the backend API.
 * @param {string} formType - The type of the form to save.
 * @param {string} [weekStartDate=null] - Optional specific week start date for historical saves.
 * @param {object} [existingData=null] - Optional data to send if it's an auto-submission of existing (potentially incomplete) data.
 */
async function saveFormData(formType, weekStartDate = null, existingData = null) {
    showLoading();
    try {
        let dataToSave;
        if (existingData) {
            dataToSave = existingData; // Use provided data for auto-submission of old forms
        } else {
            dataToSave = getFormData(formType); // Get current form data from UI
        }

        const requestBody = { data: dataToSave };

        // If a specific weekStartDate is provided (e.g., for auto-submitting old data), use it.
        // Otherwise, the backend will default to the current week.
        if (weekStartDate) {
            requestBody.week_start_date = weekStartDate;
        }

        const response = await authenticatedFetch(`${API_BASE_URL}/${formType}`, {
            method: 'POST',
            body: JSON.stringify(requestBody)
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        formCurrentData[formType] = result.data; // Update local current data with response

        // Re-fetch history to ensure it's up-to-date
        const historyResponse = await authenticatedFetch(`${API_BASE_URL}/${formType}/history`);
        if (historyResponse.ok) {
            formHistoryData[formType] = await historyResponse.json();
        }

        updateStatisticsDisplay(); // Update dashboard stats after save
        showNotification(result.message, 'success');

    } catch (error) {
        console.error(`Error saving data for ${formType}:`, error);
        showNotification(`Failed to save data: ${error.message}`, 'error');
    } finally {
        hideLoading();
    }
}


/**
 * Updates the counts and statuses displayed in the dashboard statistics and indicator list.
 */
function updateStatisticsDisplay() {
    let completedCount = 0;
    let inProgressCount = 0;
    let pendingCount = 0;

    indicators.forEach(indicator => {
        const formData = formCurrentData[indicator.id]?.data;

        // The logic for isFormComplete now needs to rely on the *saved* data structure
        // not just what's visible in the DOM, especially after a refresh.
        // This is why backend completeness is crucial. For now, it uses the frontend's
        // understanding of completeness which might be what's causing your refresh issue.
        // If your backend also sets a 'status' field, use that here:
        // if (formCurrentData[indicator.id]?.status === 'completed') { ... }

        if (isFormComplete(indicator.id, formData)) {
            indicator.status = 'completed';
            completedCount++;
        } else if (formData && Object.keys(formData).length > 0 && (formData.entries && formData.entries.length > 0)) {
            // Form has some data entries, but not "complete"
            indicator.status = 'in-progress';
            inProgressCount++;
        } else {
            indicator.status = 'pending';
            pendingCount++;
        }

        const itemElement = document.getElementById(`indicator-${indicator.id}`);
        if (itemElement) {
            itemElement.classList.remove('completed', 'in-progress', 'pending');
            itemElement.classList.add(indicator.status);

            const statusBadge = itemElement.querySelector('.status-badge');
            if (statusBadge) {
                statusBadge.textContent = indicator.status.replace('-', ' ').toUpperCase();
                statusBadge.className = `status-badge status-${indicator.status}`;
            }

            const actionButton = itemElement.querySelector('.action-btn');
            if (actionButton) {
                actionButton.textContent = (indicator.status === 'pending') ? 'Mulai Input' : 'Lihat Detail';
            }
        }
    });

    // Update the numbers in the stat cards
    document.querySelector('.stat-card:nth-child(2) .stat-number').textContent = completedCount;
    document.querySelector('.stat-card:nth-child(3) .stat-number').textContent = inProgressCount;
    document.querySelector('.stat-card:nth-child(4) .stat-number').textContent = pendingCount;
}


// --- History Section Logic ---

/**
 * Renders the history section with tabs for each form type.
 */
function renderHistorySection() {
    const historyContainer = document.getElementById('history-section-content');
    if (!historyContainer) return;

    historyContainer.innerHTML = '<h2>Riwayat Pengisian Form</h2>'; // Clear and re-add title

    const historyTabs = document.createElement('div');
    historyTabs.className = 'history-tabs flex flex-wrap gap-2 mb-4'; // flex-wrap for small screens

    let defaultFormType = Object.keys(formIdMap)[0]; // Default to the first form type

    // Create tabs for each form type
    for (const formType of Object.keys(formIdMap)) {
        const button = document.createElement('button');
        const indicator = indicators.find(i => i.id === formType);
        button.textContent = indicator ? indicator.name : formType.replace(/-/g, ' ').toUpperCase();
        button.className = 'tab-button px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 transition-colors duration-200';

        // Add click event to display history for that form
        button.onclick = () => displayHistoryForForm(formType, historyContainer);
        historyTabs.appendChild(button);
    }
    historyContainer.appendChild(historyTabs);

    // Display history for the default form type initially
    displayHistoryForForm(defaultFormType, historyContainer);
}

/**
 * Displays the historical data for a specific form type.
 * @param {string} formType - The type of form whose history to display.
 * @param {HTMLElement} container - The container element for history data display.
 */
function displayHistoryForForm(formType, container) {
    // Remove previous history data display
    container.querySelectorAll('.history-data-display').forEach(el => el.remove());

    const historyContent = document.createElement('div');
    historyContent.className = 'history-data-display mt-4 overflow-x-auto'; // Added overflow-x-auto

    let history = formHistoryData[formType];

    if (!history || history.length === 0) {
        historyContent.innerHTML = `<p class="text-gray-600">Tidak ada riwayat tersedia untuk ${formType.replace(/-/g, ' ')}.</p>`;
    } else {
        // Sort history by week_start_date in descending order (most recent first)
        history = [...history].sort((a, b) => moment(b.week_start_date).diff(moment(a.week_start_date)));

        const indicator = indicators.find(i => i.id === formType);
        const formName = indicator ? indicator.name : formType.replace(/-/g, ' ').toUpperCase();
        historyContent.innerHTML = `<h3 class="text-xl font-semibold mb-3">Riwayat ${formName}</h3>`;

        const table = document.createElement('table');
        table.className = 'history-table w-full border-collapse min-w-max md:min-w-0'; // min-w-max for horizontal scroll on small screens
        let tableHtml = `
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-2 px-4 border text-left">Tanggal Mulai Minggu</th>
                    <th class="py-2 px-4 border text-left">Data Form</th>
                    <th class="py-2 px-4 border text-left">Tanggal Input</th>
                </tr>
            </thead>
            <tbody>`;

        history.forEach(entry => {
            const weekStartDate = moment(entry.week_start_date).format('YYYY-MM-DD');
            const createdAt = moment(entry.created_at).format('YYYY-MM-DD HH:mm');
            const dataDisplay = formatFormDataForDisplay(entry.data, formType);
            tableHtml += `
                <tr>
                    <td class="py-2 px-4 border">${weekStartDate}</td>
                    <td class="py-2 px-4 border text-sm">${dataDisplay}</td>
                    <td class="py-2 px-4 border">${createdAt}</td>
                </tr>`;
        });
        tableHtml += '</tbody>';
        table.innerHTML = tableHtml;
        historyContent.appendChild(table);
    }
    container.appendChild(historyContent);

    // Update active state of history tabs
    container.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active', 'bg-blue-500', 'text-white', 'hover:bg-blue-600');
        btn.classList.add('border-gray-300', 'text-gray-700', 'hover:bg-gray-100');
        const btnText = btn.textContent.toLowerCase();
        const indicatorName = indicators.find(i => i.id === formType)?.name.toLowerCase();
        if (btnText === indicatorName.toLowerCase() || btnText === formType.replace(/-/g, ' ')) { // Compare by lowercased names
            btn.classList.add('active', 'bg-blue-500', 'text-white', 'hover:bg-blue-600');
        }
    });
}

/**
 * Formats the raw form data into a human-readable string for history display.
 * This function needs to be expanded for each specific form type for better summaries.
 * @param {object} data - The raw form data.
 * @param {string} formType - The type of the form.
 * @returns {string} Formatted string.
 */
function formatFormDataForDisplay(data, formType) {
    if (!data || Object.keys(data).length === 0) {
        return "Tidak ada data";
    }

    switch (formType) {
        case 'hand-hygiene':
            if (data.entries && data.entries.length > 0) {
                const totalSesi = data.entries.reduce((sum, entry) => sum + (entry.sesi || 0), 0);
                const totalKesempatan = data.entries.reduce((sum, entry) => sum + (entry.total_kesempatan || 0), 0);
                return `Total Entries: ${data.entries.length}, Total Sesi: ${totalSesi}, Total Kesempatan: ${totalKesempatan}`;
            }
            return "Tidak ada data";
        case 'apd':
            return `Entries: ${data.entries?.length || 0} (e.g., Tgl: ${data.entries?.[0]?.tgl || '-'}, Profesi: ${data.entries?.[0]?.profesi || '-'})`;
        case 'identifikasi':
            return `Unit: ${data.unit_kerja || '-'}, Entries: ${data.entries?.length || 0}`;
        case 'wtri':
            return `Unit: ${data.unit || '-'}, Entries: ${data.entries?.length || 0}`;
        case 'kritis-lab':
            return `Entries: ${data.entries?.length || 0} (e.g., Critical Value: ${data.entries?.[0]?.critical_value || '-'})`;
        case 'fornas':
            return `Entries: ${data.entries?.length || 0} (e.g., Resep: ${data.entries?.[0]?.jumlah_resep || 0}, Fornas Nasional: ${data.entries?.[0]?.formularium_nasional ? 'Ya' : 'Tidak'})`;
        case 'visite':
            return `Bulan: ${data.bulan || '-'}, Entries: ${data.entries?.length || 0}`;
        case 'jatuh':
            return `Entries: ${data.entries?.length || 0}, Ya: ${data.totals?.ketiga_upaya_ya || 0}, Tidak: ${data.totals?.ketiga_upaya_tidak || 0}`;
        case 'cp':
            return `Bulan: ${data.bulan || '-'}, Ruangan: ${data.ruangan || '-'}, Kepatuhan: ${data.rata_rata_kepatuhan || '-'}`;
        case 'kepuasan':
            return `Entries: ${data.entries?.length || 0} (e.g., Nilai: ${data.entries?.[0]?.nilai_kepuasan || '-'})`;
        case 'krk':
            return `Entries: ${data.entries?.length || 0} (e.g., Komplain: ${data.entries?.[0]?.isi_komplain || '-'})`;
        case 'poe':
            return `Entries: ${data.entries?.length || 0} (e.g., Diagnosa: ${data.entries?.[0]?.diagnosa || '-'})`;
        case 'sc':
            return `Entries: ${data.entries?.length || 0} (e.g., Kat: ${data.entries?.[0]?.diagnosa_kategori || '-'})`;
        default:
            return "Data: " + JSON.stringify(data).substring(0, 100) + "..."; // Truncate for brevity
    }
}

/**
 * Renumbers the 'No' column in a table's tbody.
 * This is crucial for forms where rows can be added/removed and the 'No' column needs to reflect the current order.
 * This function has been simplified to only update the displayed 'No' column.
 * It NO LONGER modifies input 'name' attributes.
 * @param {HTMLElement} tbody - The tbody element of the table.
 * @param {string[]} excludeClasses - An array of class names for rows to exclude from renumbering (e.g., ['total-row', 'nb-row']).
 */
function renumberTableRows(tbody, excludeClasses = []) {
    let currentNumber = 1;
    Array.from(tbody.children).forEach(row => {
        let isExcluded = false;
        for (const cls of excludeClasses) {
            if (row.classList.contains(cls)) {
                isExcluded = true;
                break;
            }
        }

        if (!isExcluded) {
            const noCell = row.querySelector('td:first-child');
            if (noCell) {
                noCell.textContent = currentNumber;
            }
            // IMPORTANT: The logic to update input names has been removed from here.
            // Input names should be consistent across all dynamic rows (e.g., `name="tgl"` instead of `name="tgl_1"`).
            currentNumber++;
        }
    });
}


/**
 * Sets up event listeners for "Add Row" buttons and "Save" buttons.
 */
function setupFormEventListeners() {
    // Add Row buttons
    document.getElementById('add-hand-hygiene-row')?.addEventListener('click', function() {
        const tbody = document.querySelector('#kebersihan-form .form-table tbody');
        const currentDataRows = tbody.querySelectorAll('tr:not(.total-row):not(.rata-rata-row):not(.nb-row)').length;
        addHandHygieneRow(tbody, currentDataRows + 1);
        renumberTableRows(tbody, []);
        updateHandHygieneTotals(document.getElementById('kebersihan-form'));
    });

    document.getElementById('add-apd-row')?.addEventListener('click', function() {
        const tbody = document.querySelector('#apd-form .form-table tbody');
        const dataRows = Array.from(tbody.children).filter(row => !row.classList.contains('total-row') && !row.classList.contains('rata-rata-row') && !row.classList.contains('nb-row'));
        addApdRow(tbody, dataRows.length + 1);
        renumberTableRows(tbody, ['total-row', 'rata-rata-row', 'nb-row']);
    });

    document.getElementById('add-identifikasi-row')?.addEventListener('click', function() {
        const tbody = document.querySelector('#identifikasi-form .form-table tbody');
        const dataRows = Array.from(tbody.children).filter(row => !row.classList.contains('nb-row'));
        addIdentifikasiRow(tbody, dataRows.length + 1);
        renumberTableRows(tbody, ['nb-row']);
    });

    document.getElementById('add-wtri-row')?.addEventListener('click', function() {
        const tbody = document.querySelector('#wtri-form .form-table tbody');
        const dataRows = Array.from(tbody.children).filter(row => !row.classList.contains('total-row') && !row.classList.contains('rata-rata-row') && !row.classList.contains('nb-row'));
        addWtriRow(tbody, dataRows.length + 1);
        renumberTableRows(tbody, ['total-row', 'rata-rata-row', 'nb-row']);
    });

    document.getElementById('add-kritis-lab-row')?.addEventListener('click', function() {
        const tbody = document.querySelector('#kritis-form .form-table tbody');
        const dataRows = Array.from(tbody.children).filter(row => !row.classList.contains('total-row') && !row.classList.contains('rata-rata-row') && !row.classList.contains('nb-row'));
        addKritisLabRow(tbody, dataRows.length + 1);
        renumberTableRows(tbody, ['total-row', 'rata-rata-row', 'nb-row']);
    });

    document.getElementById('add-fornas-row')?.addEventListener('click', function() {
        const tbody = document.querySelector('#fornas-form .form-table tbody');
        const dataRows = Array.from(tbody.children).filter(row => !row.classList.contains('total-row'));
        addFornasRow(tbody, dataRows.length + 1);
        renumberTableRows(tbody, ['total-row']);
    });

    document.getElementById('add-visite-row')?.addEventListener('click', function() {
        const tbody = document.querySelector('#visite-form .form-table tbody');
        const dataRows = Array.from(tbody.children).filter(row => !row.classList.contains('total-row') && !row.classList.contains('rata-rata-row') && !row.classList.contains('nb-row'));
        addVisiteRow(tbody, dataRows.length + 1);
        renumberTableRows(tbody, ['total-row', 'rata-rata-row', 'nb-row']);
    });

    document.getElementById('add-jatuh-row')?.addEventListener('click', function() {
        const tbody = document.querySelector('#jatuh-form .form-table tbody');
        const dataRows = Array.from(tbody.children).filter(row => !row.classList.contains('total-row'));
        addJatuhRow(tbody, dataRows.length + 1);
        renumberTableRows(tbody, ['total-row']);
        updateJatuhTotals(document.getElementById('jatuh-form'));
    });

    document.getElementById('add-cp-row')?.addEventListener('click', function() {
        const tbody = document.querySelector('#cp-form .form-table tbody');
        // Problem area: How is `dataRows.length + 1` behaving?
        const dataRows = Array.from(tbody.children).filter(row => !row.classList.contains('total-row') && !row.classList.contains('rata-rata-row'));
        addCpRow(tbody, dataRows.length + 1); // Pass the new index here
        renumberTableRows(tbody, ['total-row', 'rata-rata-row']); // Renumber after adding
        updateCpTotals(document.getElementById('cp-form')); // Update totals
    });

    document.getElementById('add-kepuasan-row')?.addEventListener('click', function() {
        const tbody = document.querySelector('#kepuasan-form .form-table tbody');
        const dataRows = Array.from(tbody.children).filter(row => !row.classList.contains('total-row') && !row.classList.contains('rata-rata-row') && !row.classList.contains('nb-row'));
        addKepuasanRow(tbody, dataRows.length + 1);
        renumberTableRows(tbody, ['total-row', 'rata-rata-row', 'nb-row']);
    });

    document.getElementById('add-krk-row')?.addEventListener('click', function() {
        const tbody = document.querySelector('#krk-form .form-table tbody');
        const dataRows = Array.from(tbody.children).filter(row => !row.classList.contains('total-row'));
        addKrkRow(tbody, dataRows.length + 1);
        renumberTableRows(tbody, ['total-row']);
    });

    document.getElementById('add-poe-row')?.addEventListener('click', function() {
        const tbody = document.querySelector('#poe-form .form-table tbody');
        const dataRows = Array.from(tbody.children).filter(row => !row.classList.contains('total-row') && !row.classList.contains('rata-rata-row') && !row.classList.contains('nb-row'));
        addPoeRow(tbody, dataRows.length + 1);
        renumberTableRows(tbody, ['total-row', 'rata-rata-row', 'nb-row']);
    });

    document.getElementById('add-sc-row')?.addEventListener('click', function() {
        const tbody = document.querySelector('#sc-form .form-table tbody');
        const dataRows = Array.from(tbody.children).filter(row => !row.classList.contains('total-row') && !row.classList.contains('rata-rata-row') && !row.classList.contains('nb-row'));
        addScRow(tbody, dataRows.length + 1);
        renumberTableRows(tbody, ['total-row', 'rata-rata-row', 'nb-row']);
    });

    // Save buttons
    document.querySelectorAll('.save-btn').forEach(button => {
        button.addEventListener('click', function() {
            const formCard = this.closest('.form-card');
            if (formCard) {
                const formType = Object.keys(formIdMap).find(key => formIdMap[key] === formCard.id);
                if (formType) {
                    saveFormData(formType);
                } else {
                    console.error("Save button's parent form-card ID not found in formIdMap.");
                    showNotification("Error: Could not determine form type to save.", "error");
                }
            } else {
                console.error("Save button is not inside a .form-card element.");
                showNotification("Error: Could not determine form type to save.", "error");
            }
        });
    });

    // Attach event listeners to month inputs for filtering
    document.querySelectorAll('input[type="month"][name$="_bulan"]').forEach(monthInput => {
        monthInput.addEventListener('change', function() {
            const formElement = this.closest('.form-card');
            const formType = Object.keys(formIdMap).find(key => formIdMap[key] === formElement.id);
            if (formType) {
                filterEntriesByMonth(formElement, formType, formCurrentData[formType]?.data || {});
            }
        });
    });
}