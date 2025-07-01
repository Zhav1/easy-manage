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
 * Determines if a form is considered "complete".
 * For simplicity, a form is complete if it has any data recorded in its 'data' field,
 * or for forms with 'entries', if the entries array is not empty.
 * This can be expanded with more specific validation per form type.
 * @param {string} formType - The ID of the form.
 * @param {object} formData - The data object for the form.
 * @returns {boolean}
 */
function isFormComplete(formType, formData) {
    if (!formData || Object.keys(formData).length === 0) {
        return false;
    }

    // For forms that have an 'entries' array, check if it's populated
    const formTypesWithEntries = ['apd', 'identifikasi', 'wtri', 'kritis-lab', 'fornas', 'visite', 'jatuh', 'cp', 'kepuasan', 'krk', 'poe', 'sc'];
    if (formTypesWithEntries.includes(formType)) {
        return formData.entries && formData.entries.length > 0;
    }

    // For other forms, check if any non-empty data exists
    return Object.values(formData).some(value => {
        if (Array.isArray(value)) return value.length > 0;
        if (typeof value === 'object' && value !== null) return Object.keys(value).length > 0;
        return value !== null && value !== '' && value !== false; // Consider 0 as valid input for numbers
    });
}


// --- DOM Manipulation and Section Switching ---

/**
 * Switches the displayed section of the page.
 * @param {string} section - The ID of the section to show ('list', 'history', or a formType like 'hand-hygiene').
 */
function showSection(section) {
    // Update active tab in the indicator table
    document.querySelectorAll('.indicator-table td').forEach(tab => {
        tab.classList.remove('active', 'bg-blue-500', 'text-white');
        const onclickAttr = tab.getAttribute('onclick');
        if (onclickAttr && onclickAttr.includes(`'${section}'`)) {
            tab.classList.add('active', 'bg-blue-500', 'text-white');
        }
    });

    // Hide all main content sections
    document.querySelector('.main-grid').style.display = 'none';
    document.querySelector('.stats-grid').style.display = 'none';
    document.querySelector('.data-forms').style.display = 'none';
    document.getElementById('history-section').style.display = 'none';

    document.querySelectorAll('.form-card').forEach(form => {
        form.style.display = 'none';
    });

    // Show the requested section
    if (section === 'list') {
        document.querySelector('.main-grid').style.display = 'grid';
        document.querySelector('.stats-grid').style.display = 'grid';
    } else if (section === 'history') {
        document.getElementById('history-section').style.display = 'block';
        renderHistorySection();
    } else {
        const formId = formIdMap[section];
        if (formId) {
            const form = document.getElementById(formId);
            if (form) {
                // Populate the form with current data before showing
                populateForm(form, formCurrentData[section]?.data || {}, section);
                document.querySelector('.data-forms').style.display = 'block';
                form.style.display = 'block';
                form.scrollIntoView({ behavior: 'smooth' });
            }
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
    if (!formElement || !data) return;

    // Reset form elements before populating
    formElement.querySelectorAll('input, select, textarea').forEach(input => {
        if (input.type === 'checkbox' || input.type === 'radio') {
            input.checked = false;
        } else {
            input.value = '';
        }
    });

    const tbody = formElement.querySelector('.form-table tbody');

    switch (formType) {
        case 'hand-hygiene':
            const hhRow = formElement.querySelector('.form-table tbody tr');
            if (hhRow) {
                hhRow.querySelector('.bulan-select').value = data.bulan || moment().month() + 1; // Default to current month
                hhRow.querySelector('.sesi-input').value = data.sesi || 1;
                hhRow.querySelector('input[name="dpjp_kesempatan"]').value = data.dpjp_kesempatan || 0;
                hhRow.querySelector('input[name="dpjp_handwash"]').value = data.dpjp_handwash || 0;
                hhRow.querySelector('input[name="dpjp_handrub"]').value = data.dpjp_handrub || 0;
                hhRow.querySelector('input[name="perawat_kesempatan"]').value = data.perawat_kesempatan || 0;
                hhRow.querySelector('input[name="perawat_handwash"]').value = data.perawat_handwash || 0;
                hhRow.querySelector('input[name="perawat_handrub"]').value = data.perawat_handrub || 0;
                hhRow.querySelector('input[name="pendidikan_kesempatan"]').value = data.pendidikan_kesempatan || 0;
                hhRow.querySelector('input[name="pendidikan_handwash"]').value = data.pendidikan_handwash || 0;
                hhRow.querySelector('input[name="pendidikan_handrub"]').value = data.pendidikan_handrub || 0;
                hhRow.querySelector('input[name="lain_kesempatan"]').value = data.lain_kesempatan || 0;
                hhRow.querySelector('input[name="lain_handwash"]').value = data.lain_handwash || 0;
                hhRow.querySelector('input[name="lain_handrub"]').value = data.lain_handrub || 0;
                updateHandHygieneTotals(hhRow); // Update totals immediately
            }
            break;

        case 'apd':
            tbody.innerHTML = ''; // Clear existing rows
            if (data.entries && data.entries.length > 0) {
                data.entries.forEach((entry, index) => addApdRow(tbody, index + 1, entry));
            } else {
                addApdRow(tbody, 1); // Add one empty row if no data
            }
            break;

        case 'identifikasi':
            formElement.querySelector('select[name="identifikasi_unit_kerja"]').value = data.unit_kerja || 'PJT';
            const identifikasiRows = tbody.querySelectorAll('tr:not(:last-child)');
            identifikasiRows.forEach(row => row.remove()); // Remove all but the NB row

            if (data.entries && data.entries.length > 0) {
                data.entries.forEach((entry, index) => addIdentifikasiRow(tbody, index + 1, entry));
            } else {
                // Add default 2 rows as per template if no entries
                addIdentifikasiRow(tbody, 1);
                addIdentifikasiRow(tbody, 2);
            }

            if (data.nb) {
                formElement.querySelector('input[name="identifikasi_nb_verbal_visual"]').checked = data.nb.verbal_visual || false;
                formElement.querySelector('input[name="identifikasi_nb_2_parameter"]').checked = data.nb['2_parameter'] || false;
                formElement.querySelector('input[name="identifikasi_nb_1_parameter"]').checked = data.nb['1_parameter'] || false;
                formElement.querySelector('input[name="identifikasi_nb_tidak_dilakukan"]').checked = data.nb.tidak_dilakukan || false;
            }
            break;

        case 'wtri':
            formElement.querySelectorAll('input[name="wtri_unit"]').forEach(radio => {
                radio.checked = (radio.value === data.unit);
            });
            tbody.innerHTML = '';
            if (data.entries && data.entries.length > 0) {
                data.entries.forEach((entry, index) => addWtriRow(tbody, index + 1, entry));
            } else {
                addWtriRow(tbody, 1);
            }
            break;

        case 'kritis-lab':
            tbody.innerHTML = '';
            if (data.entries && data.entries.length > 0) {
                data.entries.forEach((entry, index) => addKritisLabRow(tbody, index + 1, entry));
            } else {
                addKritisLabRow(tbody, 1);
            }
            break;

        case 'fornas':
            tbody.innerHTML = '';
            if (data.entries && data.entries.length > 0) {
                data.entries.forEach((entry, index) => addFornasRow(tbody, index + 1, entry));
            } else {
                addFornasRow(tbody, 1);
                addFornasRow(tbody, 2); // Template has 2 rows
            }
            break;

        case 'visite':
            formElement.querySelector('input[name="visite_bulan"]').value = data.bulan || moment().format('YYYY-MM');
            tbody.innerHTML = '';
            if (data.entries && data.entries.length > 0) {
                data.entries.forEach((entry, index) => addVisiteRow(tbody, index + 1, entry));
            } else {
                addVisiteRow(tbody, 1);
            }
            break;

        case 'jatuh':
            tbody.innerHTML = '';
            if (data.entries && data.entries.length > 0) {
                data.entries.forEach((entry, index) => addJatuhRow(tbody, index + 1, entry));
            } else {
                addJatuhRow(tbody, 1);
                addJatuhRow(tbody, 2); // Template has 2 rows
            }
            updateJatuhTotals(formElement);
            break;

        case 'cp':
            formElement.querySelector('input[name="cp_bulan"]').value = data.bulan || moment().format('YYYY-MM');
            formElement.querySelector('input[name="cp_ruangan"]').value = data.ruangan || '';
            formElement.querySelector('input[name="cp_judul_cp"]').value = data.judul_cp || '';

            const cpRows = tbody.querySelectorAll('tr:not(:last-child):not(:last-child)'); // Exclude total/rata-rata rows
            cpRows.forEach(row => row.remove());

            if (data.entries && data.entries.length > 0) {
                data.entries.forEach((entry, index) => addCpRow(tbody, index + 1, entry));
            } else {
                addCpRow(tbody, 1);
                addCpRow(tbody, 2); // Template has 2 rows
            }
            updateCpTotals(formElement);
            break;

        case 'kepuasan':
            tbody.innerHTML = '';
            if (data.entries && data.entries.length > 0) {
                data.entries.forEach((entry, index) => addKepuasanRow(tbody, index + 1, entry));
            } else {
                addKepuasanRow(tbody, 1);
            }
            break;

        case 'krk':
            tbody.innerHTML = '';
            if (data.entries && data.entries.length > 0) {
                data.entries.forEach((entry, index) => addKrkRow(tbody, index + 1, entry));
            } else {
                addKrkRow(tbody, 1);
            }
            break;

        case 'poe':
            tbody.innerHTML = '';
            if (data.entries && data.entries.length > 0) {
                data.entries.forEach((entry, index) => addPoeRow(tbody, index + 1, entry));
            } else {
                addPoeRow(tbody, 1);
            }
            break;

        case 'sc':
            tbody.innerHTML = '';
            if (data.entries && data.entries.length > 0) {
                data.entries.forEach((entry, index) => addScRow(tbody, index + 1, entry));
            } else {
                addScRow(tbody, 1);
            }
            break;

        default:
            // Generic population for simple forms (if any)
            formElement.querySelectorAll('input, select, textarea').forEach(input => {
                if (input.name && data[input.name] !== undefined) {
                    if (input.type === 'checkbox') {
                        input.checked = data[input.name];
                    } else if (input.type === 'radio') {
                        input.checked = (input.value === data[input.name]);
                    } else {
                        input.value = data[input.name];
                    }
                }
            });
            break;
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

    const getInputValue = (row, name) => row.querySelector(`[name="${name}"]`)?.value;
    const getCheckedValue = (row, name) => row.querySelector(`[name="${name}"]`)?.checked;
    const getParsedInt = (row, name) => parseInt(getInputValue(row, name)) || 0;

    switch (formType) {
        case 'hand-hygiene':
            const hhRow = formElement.querySelector('.form-table tbody tr');
            if (hhRow) {
                formData.bulan = getInputValue(hhRow, 'bulan');
                formData.sesi = getParsedInt(hhRow, 'sesi');

                formData.dpjp_kesempatan = getParsedInt(hhRow, 'dpjp_kesempatan');
                formData.dpjp_handwash = getParsedInt(hhRow, 'dpjp_handwash');
                formData.dpjp_handrub = getParsedInt(hhRow, 'dpjp_handrub');

                formData.perawat_kesempatan = getParsedInt(hhRow, 'perawat_kesempatan');
                formData.perawat_handwash = getParsedInt(hhRow, 'perawat_handwash');
                formData.perawat_handrub = getParsedInt(hhRow, 'perawat_handrub');

                formData.pendidikan_kesempatan = getParsedInt(hhRow, 'pendidikan_kesempatan');
                formData.pendidikan_handwash = getParsedInt(hhRow, 'pendidikan_handwash');
                formData.pendidikan_handrub = getParsedInt(hhRow, 'pendidikan_handrub');

                formData.lain_kesempatan = getParsedInt(hhRow, 'lain_kesempatan');
                formData.lain_handwash = getParsedInt(hhRow, 'lain_handwash');
                formData.lain_handrub = getParsedInt(hhRow, 'lain_handrub');

                formData.total_kesempatan = formData.dpjp_kesempatan + formData.perawat_kesempatan + formData.pendidikan_kesempatan + formData.lain_kesempatan;
                formData.total_handwash = formData.dpjp_handwash + formData.perawat_handwash + formData.pendidikan_handwash + formData.lain_handwash;
                formData.total_handrub = formData.dpjp_handrub + formData.perawat_handrub + formData.pendidikan_handrub + formData.lain_handrub;
            }
            break;

        case 'apd':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr').forEach((row, index) => {
                const entry = {
                    tgl: getInputValue(row, `apd_tgl_${index + 1}`),
                    profesi: getInputValue(row, `apd_profesi_${index + 1}`),
                    ruang: getInputValue(row, `apd_ruang_${index + 1}`),
                    pelayanan: getInputValue(row, `apd_pelayanan_${index + 1}`),
                    sarung_tangan_y: getCheckedValue(row, `apd_st_y_${index + 1}`),
                    sarung_tangan_t: getCheckedValue(row, `apd_st_t_${index + 1}`),
                    masker_y: getCheckedValue(row, `apd_masker_y_${index + 1}`),
                    masker_t: getCheckedValue(row, `apd_masker_t_${index + 1}`),
                    topi_y: getCheckedValue(row, `apd_topi_y_${index + 1}`),
                    topi_t: getCheckedValue(row, `apd_topi_t_${index + 1}`),
                    google_y: getCheckedValue(row, `apd_google_y_${index + 1}`),
                    google_t: getCheckedValue(row, `apd_google_t_${index + 1}`),
                    pakaian_y: getCheckedValue(row, `apd_pakaian_y_${index + 1}`),
                    pakaian_t: getCheckedValue(row, `apd_pakaian_t_${index + 1}`),
                    sepatu_y: getCheckedValue(row, `apd_sepatu_y_${index + 1}`),
                    sepatu_t: getCheckedValue(row, `apd_sepatu_t_${index + 1}`),
                    kepatuhan: getInputValue(row, `apd_kepatuhan_${index + 1}`),
                    ket: getInputValue(row, `apd_ket_${index + 1}`)
                };
                formData.entries.push(entry);
            });
            break;

        case 'identifikasi':
            formData.unit_kerja = formElement.querySelector('select[name="identifikasi_unit_kerja"]')?.value;
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(:last-child)').forEach((row, index) => { // Exclude NB row
                const entry = {
                    no: getParsedInt(row, `identifikasi_no_${index + 1}`),
                    tgl: getInputValue(row, `identifikasi_tgl_${index + 1}`),
                    staf: getInputValue(row, `identifikasi_staf_${index + 1}`),
                    obat: getCheckedValue(row, `identifikasi_obat_${index + 1}`),
                    darah: getCheckedValue(row, `identifikasi_darah_${index + 1}`),
                    diet: getCheckedValue(row, `identifikasi_diet_${index + 1}`),
                    spesimen: getCheckedValue(row, `identifikasi_spesimen_${index + 1}`),
                    diagnostik: getCheckedValue(row, `identifikasi_diagnostik_${index + 1}`),
                    verbal_nama: getCheckedValue(row, `identifikasi_verbal_nama_${index + 1}`),
                    verbal_tgl_lahir: getCheckedValue(row, `identifikasi_verbal_tgl_lahir_${index + 1}`),
                    visual_nama: getCheckedValue(row, `identifikasi_visual_nama_${index + 1}`),
                    visual_rm: getCheckedValue(row, `identifikasi_visual_rm_${index + 1}`),
                    dilakukan: getCheckedValue(row, `identifikasi_dilakukan_${index + 1}`),
                    tidak_dilakukan: getCheckedValue(row, `identifikasi_tidak_dilakukan_${index + 1}`),
                };
                formData.entries.push(entry);
            });
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
            formElement.querySelectorAll('.form-table tbody tr').forEach((row, index) => {
                const entry = {
                    no: getParsedInt(row, `wtri_no_${index + 1}`),
                    tgl: getInputValue(row, `wtri_tgl_${index + 1}`),
                    no_rm: getInputValue(row, `wtri_no_rm_${index + 1}`),
                    nama_pasien: getInputValue(row, `wtri_nama_pasien_${index + 1}`),
                    jam_reg_pendaftaran: getInputValue(row, `wtri_jam_reg_pendaftaran_${index + 1}`),
                    jam_reg_poli: getInputValue(row, `wtri_jam_reg_poli_${index + 1}`),
                    jam_dilayani_dokter: getInputValue(row, `wtri_jam_dilayani_dokter_${index + 1}`),
                    respon_time_ca: getParsedInt(row, `wtri_respon_time_ca_${index + 1}`),
                    pelayanan_percent_ca: getParsedInt(row, `wtri_pelayanan_percent_ca_${index + 1}`),
                    respon_time_cb: getParsedInt(row, `wtri_respon_time_cb_${index + 1}`),
                    pelayanan_percent_cb: getParsedInt(row, `wtri_pelayanan_percent_cb_${index + 1}`),
                };
                formData.entries.push(entry);
            });
            break;

        case 'kritis-lab':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr').forEach((row, index) => {
                const entry = {
                    no: getParsedInt(row, `kritis_no_${index + 1}`),
                    tgl: getInputValue(row, `kritis_tgl_${index + 1}`),
                    no_rm: getInputValue(row, `kritis_no_rm_${index + 1}`),
                    nama_pasien: getInputValue(row, `kritis_nama_pasien_${index + 1}`),
                    critical_value: getInputValue(row, `kritis_critical_value_${index + 1}`),
                    waktu_hasil_keluar: getInputValue(row, `kritis_waktu_hasil_keluar_${index + 1}`),
                    waktu_dilaporkan: getInputValue(row, `kritis_waktu_dilaporkan_${index + 1}`),
                    nama_penerima: getInputValue(row, `kritis_nama_penerima_${index + 1}`),
                    respon_time: getParsedInt(row, `kritis_respon_time_${index + 1}`),
                    pelaporan_status: getInputValue(row, `kritis_pelaporan_status_${index + 1}`),
                };
                formData.entries.push(entry);
            });
            break;

        case 'fornas':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(:last-child)').forEach((row, index) => { // Exclude total row
                const entry = {
                    no: parseInt(row.querySelector(`td:first-child`)?.textContent) || (index + 1),
                    unit_kerja: getInputValue(row, `fornas_unit_kerja_${index + 1}`),
                    nama_pasien: getInputValue(row, `fornas_nama_pasien_${index + 1}`),
                    no_rm: getInputValue(row, `fornas_no_rm_${index + 1}`),
                    jumlah_resep: getParsedInt(row, `fornas_jumlah_resep_${index + 1}`),
                    formularium_n: getParsedInt(row, `fornas_formularium_n_${index + 1}`),
                    non_formularium: getParsedInt(row, `fornas_non_formularium_${index + 1}`),
                };
                formData.entries.push(entry);
            });
            break;

        case 'visite':
            formData.bulan = getInputValue(formElement, 'visite_bulan');
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr').forEach((row, index) => {
                const entry = {
                    no: parseInt(row.querySelector(`td:first-child`)?.textContent) || (index + 1),
                    tgl_registrasi: getInputValue(row, `visite_tgl_registrasi_${index + 1}`),
                    nama_pasien: getInputValue(row, `visite_nama_pasien_${index + 1}`),
                    no_rm: getInputValue(row, `visite_no_rm_${index + 1}`),
                    ruangan: getInputValue(row, `visite_ruangan_${index + 1}`),
                    jml_hari_efektif: getParsedInt(row, `visite_jml_hari_efektif_${index + 1}`),
                    jml_hari_rawat: getParsedInt(row, `visite_jml_hari_rawat_${index + 1}`),
                    dpjp_utama: getInputValue(row, `visite_dpjp_utama_${index + 1}`),
                    smf: getInputValue(row, `visite_smf_${index + 1}`),
                    tgl_visite: getInputValue(row, `visite_tgl_visite_${index + 1}`),
                    jam: getInputValue(row, `visite_jam_${index + 1}`),
                    val_i: getParsedInt(row, `visite_val_i_${index + 1}`),
                    val_ii: getParsedInt(row, `visite_val_ii_${index + 1}`),
                    val_iii: getParsedInt(row, `visite_val_iii_${index + 1}`),
                    val_iv: getParsedInt(row, `visite_val_iv_${index + 1}`),
                    total: getParsedInt(row, `visite_total_${index + 1}`),
                    jam_visite_akhir: getInputValue(row, `visite_jam_visite_akhir_${index + 1}`),
                };
                formData.entries.push(entry);
            });
            break;

        case 'jatuh':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(:last-child)').forEach((row, index) => { // Exclude total row
                const entry = {
                    no: parseInt(row.querySelector(`td:first-child`)?.textContent) || (index + 1),
                    nama_pasien: getInputValue(row, `jatuh_nama_pasien_${index + 1}`),
                    no_rm: getInputValue(row, `jatuh_no_rm_${index + 1}`),
                    assessment_awal: getInputValue(row, `jatuh_assessment_awal_${index + 1}`),
                    assessment_ulang: getInputValue(row, `jatuh_assessment_ulang_${index + 1}`),
                    intervensi: getInputValue(row, `jatuh_intervensi_${index + 1}`),
                    ketiga_upaya_ya: getCheckedValue(row, `jatuh_ketiga_upaya_ya_${index + 1}`),
                    ketiga_upaya_tidak: getCheckedValue(row, `jatuh_ketiga_upaya_tidak_${index + 1}`),
                };
                formData.entries.push(entry);
            });
            formData.totals = {
                assessment_awal: getParsedInt(formElement, 'jatuh_total_assessment_awal'),
                assessment_ulang: getParsedInt(formElement, 'jatuh_total_assessment_ulang'),
                intervensi: getParsedInt(formElement, 'jatuh_total_intervensi'),
                ketiga_upaya_ya: getParsedInt(formElement, 'jatuh_total_ketiga_upaya_ya'),
                ketiga_upaya_tidak: getParsedInt(formElement, 'jatuh_total_ketiga_upaya_tidak'),
            };
            break;

        case 'cp':
            formData.bulan = getInputValue(formElement, 'cp_bulan');
            formData.ruangan = getInputValue(formElement, 'cp_ruangan');
            formData.judul_cp = getInputValue(formElement, 'cp_judul_cp');
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(:last-child):not(:last-child)').forEach((row, index) => { // Exclude total/rata-rata rows
                const entry = {
                    no: parseInt(row.querySelector(`td:first-child`)?.textContent) || (index + 1),
                    no_mr: getInputValue(row, `cp_no_mr_${index + 1}`),
                    asesmen_p: getParsedInt(row, `cp_asesmen_p_${index + 1}`),
                    asesmen_n: getParsedInt(row, `cp_asesmen_n_${index + 1}`),
                    asesmen_c: getParsedInt(row, `cp_asesmen_c_${index + 1}`),
                    fisik_p: getParsedInt(row, `cp_fisik_p_${index + 1}`),
                    fisik_n: getParsedInt(row, `cp_fisik_n_${index + 1}`),
                    fisik_c: getParsedInt(row, `cp_fisik_c_${index + 1}`),
                    penunjang_p: getParsedInt(row, `cp_penunjang_p_${index + 1}`),
                    penunjang_n: getParsedInt(row, `cp_penunjang_n_${index + 1}`),
                    penunjang_c: getParsedInt(row, `cp_penunjang_c_${index + 1}`),
                    obat_p: getParsedInt(row, `cp_obat_p_${index + 1}`),
                    obat_n: getParsedInt(row, `cp_obat_n_${index + 1}`),
                    obat_c: getParsedInt(row, `cp_obat_c_${index + 1}`),
                    total: getParsedInt(row, `cp_total_${index + 1}`),
                    varian: getInputValue(row, `cp_varian_${index + 1}`),
                    ket: getInputValue(row, `cp_ket_${index + 1}`),
                };
                formData.entries.push(entry);
            });
            formData.totals = {
                asesmen_p: getParsedInt(formElement, 'cp_total_asesmen_p'),
                asesmen_n: getParsedInt(formElement, 'cp_total_asesmen_n'),
                asesmen_c: getParsedInt(formElement, 'cp_total_asesmen_c'),
                fisik_p: getParsedInt(formElement, 'cp_total_fisik_p'),
                fisik_n: getParsedInt(formElement, 'cp_total_fisik_n'),
                fisik_c: getParsedInt(formElement, 'cp_total_fisik_c'),
                penunjang_p: getParsedInt(formElement, 'cp_total_penunjang_p'),
                penunjang_n: getParsedInt(formElement, 'cp_total_penunjang_n'),
                penunjang_c: getParsedInt(formElement, 'cp_total_penunjang_c'),
                obat_p: getParsedInt(formElement, 'cp_total_obat_p'),
                obat_n: getParsedInt(formElement, 'cp_total_obat_n'),
                obat_c: getParsedInt(formElement, 'cp_total_obat_c'),
                grand_total: getParsedInt(formElement, 'cp_grand_total'),
            };
            formData.rata_rata_kepatuhan = getInputValue(formElement, 'cp_rata_rata_kepatuhan');
            break;

        case 'kepuasan':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr').forEach((row, index) => {
                const entry = {
                    no: parseInt(row.querySelector(`td:first-child`)?.textContent) || (index + 1),
                    tanggal: getInputValue(row, `kepuasan_tanggal_${index + 1}`),
                    unit_kerja: getInputValue(row, `kepuasan_unit_kerja_${index + 1}`),
                    nilai_ikm: getInputValue(row, `kepuasan_nilai_ikm_${index + 1}`),
                    jenis_pelayanan: getInputValue(row, `kepuasan_jenis_pelayanan_${index + 1}`),
                    nilai_kepuasan: getInputValue(row, `kepuasan_nilai_kepuasan_${index + 1}`),
                    komentar: getInputValue(row, `kepuasan_komentar_${index + 1}`),
                };
                formData.entries.push(entry);
            });
            break;

        case 'krk':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(:last-child)').forEach((row, index) => { // Exclude total row
                const entry = {
                    no: getParsedInt(row, `krk_no_${index + 1}`),
                    tgl: getInputValue(row, `krk_tgl_${index + 1}`),
                    isi_komplain: getInputValue(row, `krk_isi_komplain_${index + 1}`),
                    kategori_komplain: getInputValue(row, `krk_kategori_komplain_${index + 1}`),
                    lisan: getCheckedValue(row, `krk_lisan_${index + 1}`),
                    tulisan: getCheckedValue(row, `krk_tulisan_${index + 1}`),
                    media_masa: getCheckedValue(row, `krk_media_masa_${index + 1}`),
                    grading_merah: getCheckedValue(row, `krk_grading_merah_${index + 1}`),
                    grading_kuning: getCheckedValue(row, `krk_grading_kuning_${index + 1}`),
                    grading_hijau: getCheckedValue(row, `krk_grading_hijau_${index + 1}`),
                    waktu_tanggap: getParsedInt(row, `krk_waktu_tanggap_${index + 1}`),
                    penyelesaian_ya: getCheckedValue(row, `krk_penyelesaian_ya_${index + 1}`),
                    penyelesaian_tidak: getCheckedValue(row, `krk_penyelesaian_tidak_${index + 1}`),
                    ket: getInputValue(row, `krk_ket_${index + 1}`),
                };
                formData.entries.push(entry);
            });
            break;

        case 'poe':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr').forEach((row, index) => {
                const entry = {
                    no: parseInt(row.querySelector(`td:first-child`)?.textContent) || (index + 1),
                    tgl: getInputValue(row, `poe_tgl_${index + 1}`),
                    nama_pasien: getInputValue(row, `poe_nama_pasien_${index + 1}`),
                    no_rm: getInputValue(row, `poe_no_rm_${index + 1}`),
                    ruangan: getInputValue(row, `poe_ruangan_${index + 1}`),
                    diagnosa: getInputValue(row, `poe_diagnosa_${index + 1}`),
                    tindakan_bedah: getInputValue(row, `poe_tindakan_bedah_${index + 1}`),
                    dpjp_bedah: getInputValue(row, `poe_dpjp_bedah_${index + 1}`),
                    jam_rencana_operasi: getInputValue(row, `poe_jam_rencana_${index + 1}`),
                    jam_insisi: getInputValue(row, `poe_jam_insisi_${index + 1}`),
                    penundaan_gt_1hr: getCheckedValue(row, `poe_penundaan_gt_1hr_${index + 1}`),
                    penundaan_lt_1hr: getCheckedValue(row, `poe_penundaan_lt_1hr_${index + 1}`),
                    keterangan: getInputValue(row, `poe_keterangan_${index + 1}`),
                };
                formData.entries.push(entry);
            });
            break;

        case 'sc':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr').forEach((row, index) => {
                const entry = {
                    no: parseInt(row.querySelector(`td:first-child`)?.textContent) || (index + 1),
                    nama_pasien: getInputValue(row, `sc_nama_pasien_${index + 1}`),
                    no_rm: getInputValue(row, `sc_no_rm_${index + 1}`),
                    diagnosa_kategori: getInputValue(row, `sc_diagnosa_kategori_${index + 1}`),
                    jam_tiba_igd: getInputValue(row, `sc_jam_tiba_igd_${index + 1}`),
                    jam_diputuskan_operasi: getInputValue(row, `sc_jam_diputuskan_operasi_${index + 1}`),
                    jam_mulai_insisi: getInputValue(row, `sc_jam_mulai_insisi_${index + 1}`),
                    waktu_tanggap: getParsedInt(row, `sc_waktu_tanggap_${index + 1}`),
                    gt_30_menit: getInputValue(row, `sc_gt_30_menit_${index + 1}`),
                    keterangan: getInputValue(row, `sc_keterangan_${index + 1}`),
                };
                formData.entries.push(entry);
            });
            break;

        default:
            // Generic data extraction
            formElement.querySelectorAll('input, select, textarea').forEach(input => {
                if (input.name) {
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
 * Adds a new row to the APD form table.
 * @param {HTMLElement} tbody - The tbody element of the table.
 * @param {number} index - The index for the new row.
 * @param {object} [entry={}] - Optional initial data for the row.
 */
function addApdRow(tbody, index, entry = {}) {
    const newRow = tbody.insertRow();
    newRow.innerHTML = `
        <td><input type="date" name="apd_tgl_${index}" value="${entry.tgl || ''}" /></td>
        <td><input type="text" placeholder="Profesi" name="apd_profesi_${index}" value="${entry.profesi || ''}" /></td>
        <td><input type="text" placeholder="Ruang" name="apd_ruang_${index}" value="${entry.ruang || ''}" /></td>
        <td><input type="text" placeholder="Pelayanan" name="apd_pelayanan_${index}" value="${entry.pelayanan || ''}" /></td>
        <td><input type="checkbox" name="apd_st_y_${index}" ${entry.sarung_tangan_y ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="apd_st_t_${index}" ${entry.sarung_tangan_t ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="apd_masker_y_${index}" ${entry.masker_y ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="apd_masker_t_${index}" ${entry.masker_t ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="apd_topi_y_${index}" ${entry.topi_y ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="apd_topi_t_${index}" ${entry.topi_t ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="apd_google_y_${index}" ${entry.google_y ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="apd_google_t_${index}" ${entry.google_t ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="apd_pakaian_y_${index}" ${entry.pakaian_y ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="apd_pakaian_t_${index}" ${entry.pakaian_t ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="apd_sepatu_y_${index}" ${entry.sepatu_y ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="apd_sepatu_t_${index}" ${entry.sepatu_t ? 'checked' : ''} /></td>
        <td>
            <select name="apd_kepatuhan_${index}">
                <option ${entry.kepatuhan === 'Patuh' ? 'selected' : ''}>Patuh</option>
                <option ${entry.kepatuhan === 'Tidak' ? 'selected' : ''}>Tidak</option>
            </select>
        </td>
        <td><input type="text" placeholder="Keterangan" name="apd_ket_${index}" value="${entry.ket || ''}" /></td>
    `;
    // Add event listeners for dynamic calculation if needed, e.g., on checkbox change
}

/**
 * Adds a new row to the Identifikasi form table (excluding the NB row).
 * @param {HTMLElement} tbody - The tbody element of the table.
 * @param {number} index - The index for the new row.
 * @param {object} [entry={}] - Optional initial data for the row.
 */
function addIdentifikasiRow(tbody, index, entry = {}) {
    const nbRow = tbody.querySelector('tr:last-child'); // Get the NB row
    const newRow = tbody.insertRow(nbRow ? tbody.rows.length - 1 : -1); // Insert before NB row if it exists
    newRow.innerHTML = `
        <td><input type="number" value="${entry.no || index}" name="identifikasi_no_${index}" /></td>
        <td><input type="date" name="identifikasi_tgl_${index}" value="${entry.tgl || ''}" /></td>
        <td><input type="text" placeholder="Nama Staf" name="identifikasi_staf_${index}" value="${entry.staf || ''}" /></td>
        <td><input type="checkbox" name="identifikasi_obat_${index}" ${entry.obat ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="identifikasi_darah_${index}" ${entry.darah ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="identifikasi_diet_${index}" ${entry.diet ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="identifikasi_spesimen_${index}" ${entry.spesimen ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="identifikasi_diagnostik_${index}" ${entry.diagnostik ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="identifikasi_verbal_nama_${index}" ${entry.verbal_nama ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="identifikasi_verbal_tgl_lahir_${index}" ${entry.verbal_tgl_lahir ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="identifikasi_visual_nama_${index}" ${entry.visual_nama ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="identifikasi_visual_rm_${index}" ${entry.visual_rm ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="identifikasi_dilakukan_${index}" ${entry.dilakukan ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="identifikasi_tidak_dilakukan_${index}" ${entry.tidak_dilakukan ? 'checked' : ''} /></td>
    `;
    // Attach event listeners if needed
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
        <td><input type="number" name="wtri_no_${index}" value="${entry.no || index}" /></td>
        <td><input type="date" name="wtri_tgl_${index}" value="${entry.tgl || ''}" /></td>
        <td><input type="text" name="wtri_no_rm_${index}" value="${entry.no_rm || ''}" /></td>
        <td><input type="text" name="wtri_nama_pasien_${index}" value="${entry.nama_pasien || ''}" /></td>
        <td><input type="time" name="wtri_jam_reg_pendaftaran_${index}" value="${entry.jam_reg_pendaftaran || ''}" /></td>
        <td><input type="time" name="wtri_jam_reg_poli_${index}" value="${entry.jam_reg_poli || ''}" /></td>
        <td><input type="time" name="wtri_jam_dilayani_dokter_${index}" value="${entry.jam_dilayani_dokter || ''}" /></td>
        <td><input type="number" value="${entry.respon_time_ca || 0}" name="wtri_respon_time_ca_${index}" readonly/></td>
        <td><input type="number" value="${entry.pelayanan_percent_ca || 0}" name="wtri_pelayanan_percent_ca_${index}" /></td>
        <td><input type="number" value="${entry.respon_time_cb || 0}" name="wtri_respon_time_cb_${index}" /></td>
        <td><input type="number" value="${entry.pelayanan_percent_cb || 0}" name="wtri_pelayanan_percent_cb_${index}" /></td>
    `;
    const jamRegPendaftaranInput = newRow.querySelector(`input[name="wtri_jam_reg_pendaftaran_${index}"]`);
    const jamDilayaniDokterInput = newRow.querySelector(`input[name="wtri_jam_dilayani_dokter_${index}"]`);
    const responTimeCaInput = newRow.querySelector(`input[name="wtri_respon_time_ca_${index}"]`);

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
        <td><input type="number" name="kritis_no_${index}" value="${entry.no || index}" /></td>
        <td><input type="date" name="kritis_tgl_${index}" value="${entry.tgl || ''}" /></td>
        <td><input type="text" name="kritis_no_rm_${index}" value="${entry.no_rm || ''}" /></td>
        <td><input type="text" name="kritis_nama_pasien_${index}" value="${entry.nama_pasien || ''}" /></td>
        <td><input type="text" name="kritis_critical_value_${index}" value="${entry.critical_value || ''}" /></td>
        <td><input type="time" name="kritis_waktu_hasil_keluar_${index}" value="${entry.waktu_hasil_keluar || ''}" /></td>
        <td><input type="time" name="kritis_waktu_dilaporkan_${index}" value="${entry.waktu_dilaporkan || ''}" /></td>
        <td><input type="text" name="kritis_nama_penerima_${index}" value="${entry.nama_penerima || ''}" /></td>
        <td><input type="number" name="kritis_respon_time_${index}" value="${entry.respon_time || 0}" /></td>
        <td><select name="kritis_pelaporan_status_${index}">
            <option ${entry.pelaporan_status === '≤ 30 Menit' ? 'selected' : ''}>≤ 30 Menit</option>
            <option ${entry.pelaporan_status === '> 30 Menit' ? 'selected' : ''}>> 30 Menit</option>
        </select></td>
    `;

    const waktuHasilKeluarInput = newRow.querySelector(`input[name="kritis_waktu_hasil_keluar_${index}"]`);
    const waktuDilaporkanInput = newRow.querySelector(`input[name="kritis_waktu_dilaporkan_${index}"]`);
    const responTimeInput = newRow.querySelector(`input[name="kritis_respon_time_${index}"]`);
    const pelaporanStatusSelect = newRow.querySelector(`select[name="kritis_pelaporan_status_${index}"]`);

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
}

/**
 * Adds a new row to the FORNAS form table.
 * @param {HTMLElement} tbody - The tbody element of the table.
 * @param {number} index - The index for the new row.
 * @param {object} [entry={}] - Optional initial data for the row.
 */
function addFornasRow(tbody, index, entry = {}) {
    const totalRow = tbody.querySelector('tr:last-child'); // Get the total row
    const newRow = tbody.insertRow(totalRow ? tbody.rows.length - 1 : -1); // Insert before total row if it exists
    newRow.innerHTML = `
        <td>${index}</td>
        <td><input type="text" placeholder="Unit Kerja" name="fornas_unit_kerja_${index}" value="${entry.unit_kerja || ''}" /></td>
        <td><input type="text" placeholder="Nama Pasien" name="fornas_nama_pasien_${index}" value="${entry.nama_pasien || ''}" /></td>
        <td><input type="text" placeholder="No. RM" name="fornas_no_rm_${index}" value="${entry.no_rm || ''}" /></td>
        <td><input type="number" name="fornas_jumlah_resep_${index}" value="${entry.jumlah_resep || 0}" /></td>
        <td><input type="number" name="fornas_formularium_n_${index}" value="${entry.formularium_n || 0}" /></td>
        <td><input type="number" name="fornas_non_formularium_${index}" value="${entry.non_formularium || 0}" /></td>
    `;
    // Attach event listeners if needed
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
        <td>${index}</td>
        <td><input type="date" name="visite_tgl_registrasi_${index}" value="${entry.tgl_registrasi || ''}" /></td>
        <td><input type="text" name="visite_nama_pasien_${index}" value="${entry.nama_pasien || ''}" /></td>
        <td><input type="text" name="visite_no_rm_${index}" value="${entry.no_rm || ''}" /></td>
        <td><input type="text" name="visite_ruangan_${index}" value="${entry.ruangan || ''}" /></td>
        <td><input type="number" name="visite_jml_hari_efektif_${index}" value="${entry.jml_hari_efektif || 0}" /></td>
        <td><input type="number" name="visite_jml_hari_rawat_${index}" value="${entry.jml_hari_rawat || 0}" /></td>
        <td><input type="text" name="visite_dpjp_utama_${index}" value="${entry.dpjp_utama || ''}" /></td>
        <td><input type="text" name="visite_smf_${index}" value="${entry.smf || ''}" /></td>
        <td><input type="date" name="visite_tgl_visite_${index}" value="${entry.tgl_visite || ''}" /></td>
        <td><input type="time" name="visite_jam_${index}" value="${entry.jam || ''}" /></td>
        <td><input type="number" name="visite_val_i_${index}" value="${entry.val_i || 0}" /></td>
        <td><input type="number" name="visite_val_ii_${index}" value="${entry.val_ii || 0}" /></td>
        <td><input type="number" name="visite_val_iii_${index}" value="${entry.val_iii || 0}" /></td>
        <td><input type="number" name="visite_val_iv_${index}" value="${entry.val_iv || 0}" /></td>
        <td><input type="number" readonly name="visite_total_${index}" value="${entry.total || 0}" /></td>
        <td><input type="time" name="visite_jam_visite_akhir_${index}" value="${entry.jam_visite_akhir || ''}" /></td>
    `;
    const jamInput = newRow.querySelector(`input[name="visite_jam_${index}"]`);
    const totalInput = newRow.querySelector(`input[name="visite_total_${index}"]`);
    const valI = newRow.querySelector(`input[name="visite_val_i_${index}"]`);
    const valII = newRow.querySelector(`input[name="visite_val_ii_${index}"]`);
    const valIII = newRow.querySelector(`input[name="visite_val_iii_${index}"]`);
    const valIV = newRow.querySelector(`input[name="visite_val_iv_${index}"]`);

    const updateVisiteTotal = () => {
        const jam = jamInput.value;
        let score = 0;
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
    jamInput.addEventListener('input', updateVisiteTotal);
    updateVisiteTotal(); // Initial calculation
}

/**
 * Adds a new row to the Jatuh form table.
 * @param {HTMLElement} tbody - The tbody element of the table.
 * @param {number} index - The index for the new row.
 * @param {object} [entry={}] - Optional initial data for the row.
 */
function addJatuhRow(tbody, index, entry = {}) {
    const totalRow = tbody.querySelector('tr:last-child');
    const newRow = tbody.insertRow(totalRow ? tbody.rows.length - 1 : -1);
    newRow.innerHTML = `
        <td>${index}</td>
        <td><input type="text" placeholder="Nama Pasien" name="jatuh_nama_pasien_${index}" value="${entry.nama_pasien || ''}" /></td>
        <td><input type="text" placeholder="No. RM" name="jatuh_no_rm_${index}" value="${entry.no_rm || ''}" /></td>
        <td><select name="jatuh_assessment_awal_${index}">
            <option ${entry.assessment_awal === 'Ya' ? 'selected' : ''}>Ya</option>
            <option ${entry.assessment_awal === 'Tidak' ? 'selected' : ''}>Tidak</option>
        </select></td>
        <td><select name="jatuh_assessment_ulang_${index}">
            <option ${entry.assessment_ulang === 'Ya' ? 'selected' : ''}>Ya</option>
            <option ${entry.assessment_ulang === 'Tidak' ? 'selected' : ''}>Tidak</option>
        </select></td>
        <td><select name="jatuh_intervensi_${index}">
            <option ${entry.intervensi === 'Ya' ? 'selected' : ''}>Ya</option>
            <option ${entry.intervensi === 'Tidak' ? 'selected' : ''}>Tidak</option>
        </select></td>
        <td><input type="checkbox" name="jatuh_ketiga_upaya_ya_${index}" ${entry.ketiga_upaya_ya ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="jatuh_ketiga_upaya_tidak_${index}" ${entry.ketiga_upaya_tidak ? 'checked' : ''} /></td>
    `;
    const selects = newRow.querySelectorAll('select');
    const yaCheckbox = newRow.querySelector(`input[name="jatuh_ketiga_upaya_ya_${index}"]`);
    const tidakCheckbox = newRow.querySelector(`input[name="jatuh_ketiga_upaya_tidak_${index}"]`);

    const updateJatuhCheckboxes = () => {
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

    formElement.querySelectorAll('.form-table tbody tr:not(:last-child)').forEach(row => {
        if (row.querySelector(`select[name^="jatuh_assessment_awal_"]`)?.value === 'Ya') totalAssessmentAwal++;
        if (row.querySelector(`select[name^="jatuh_assessment_ulang_"]`)?.value === 'Ya') totalAssessmentUlang++;
        if (row.querySelector(`select[name^="jatuh_intervensi_"]`)?.value === 'Ya') totalIntervensi++;
        if (row.querySelector(`input[name^="jatuh_ketiga_upaya_ya_"]`)?.checked) totalKetigaUpayaYa++;
        if (row.querySelector(`input[name^="jatuh_ketiga_upaya_tidak_"]`)?.checked) totalKetigaUpayaTidak++;
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
    const totalRow = tbody.querySelector('tr:nth-last-child(2)'); // The "TOTAL" row
    const rataRataRow = tbody.querySelector('tr:last-child'); // The "Rata-Rata" row
    const insertBeforeElement = totalRow || rataRataRow || null; // Insert before total, then rata-rata, then end

    const newRow = tbody.insertRow(insertBeforeElement ? insertBeforeElement.rowIndex - 1 : -1);
    newRow.innerHTML = `
        <td>${index}</td>
        <td><input type="text" placeholder="No. MR" name="cp_no_mr_${index}" value="${entry.no_mr || ''}" /></td>
        <td><input type="number" name="cp_asesmen_p_${index}" value="${entry.asesmen_p || 0}" /></td>
        <td><input type="number" name="cp_asesmen_n_${index}" value="${entry.asesmen_n || 0}" /></td>
        <td><input type="number" name="cp_asesmen_c_${index}" value="${entry.asesmen_c || 0}" /></td>
        <td><input type="number" name="cp_fisik_p_${index}" value="${entry.fisik_p || 0}" /></td>
        <td><input type="number" name="cp_fisik_n_${index}" value="${entry.fisik_n || 0}" /></td>
        <td><input type="number" name="cp_fisik_c_${index}" value="${entry.fisik_c || 0}" /></td>
        <td><input type="number" name="cp_penunjang_p_${index}" value="${entry.penunjang_p || 0}" /></td>
        <td><input type="number" name="cp_penunjang_n_${index}" value="${entry.penunjang_n || 0}" /></td>
        <td><input type="number" name="cp_penunjang_c_${index}" value="${entry.penunjang_c || 0}" /></td>
        <td><input type="number" name="cp_obat_p_${index}" value="${entry.obat_p || 0}" /></td>
        <td><input type="number" name="cp_obat_n_${index}" value="${entry.obat_n || 0}" /></td>
        <td><input type="number" name="cp_obat_c_${index}" value="${entry.obat_c || 0}" /></td>
        <td><input type="number" readonly name="cp_total_${index}" value="${entry.total || 0}" /></td>
        <td><input type="text" name="cp_varian_${index}" value="${entry.varian || ''}" /></td>
        <td><input type="text" name="cp_ket_${index}" value="${entry.ket || ''}" /></td>
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

    formElement.querySelectorAll('.form-table tbody tr:not(:last-child):not(:last-child)').forEach(row => {
        const index = parseInt(row.querySelector('td:first-child')?.textContent);
        if (!isNaN(index)) {
            const asesmen_p = parseInt(row.querySelector(`input[name="cp_asesmen_p_${index}"]`)?.value) || 0;
            const asesmen_n = parseInt(row.querySelector(`input[name="cp_asesmen_n_${index}"]`)?.value) || 0;
            const asesmen_c = parseInt(row.querySelector(`input[name="cp_asesmen_c_${index}"]`)?.value) || 0;
            const fisik_p = parseInt(row.querySelector(`input[name="cp_fisik_p_${index}"]`)?.value) || 0;
            const fisik_n = parseInt(row.querySelector(`input[name="cp_fisik_n_${index}"]`)?.value) || 0;
            const fisik_c = parseInt(row.querySelector(`input[name="cp_fisik_c_${index}"]`)?.value) || 0;
            const penunjang_p = parseInt(row.querySelector(`input[name="cp_penunjang_p_${index}"]`)?.value) || 0;
            const penunjang_n = parseInt(row.querySelector(`input[name="cp_penunjang_n_${index}"]`)?.value) || 0;
            const penunjang_c = parseInt(row.querySelector(`input[name="cp_penunjang_c_${index}"]`)?.value) || 0;
            const obat_p = parseInt(row.querySelector(`input[name="cp_obat_p_${index}"]`)?.value) || 0;
            const obat_n = parseInt(row.querySelector(`input[name="cp_obat_n_${index}"]`)?.value) || 0;
            const obat_c = parseInt(row.querySelector(`input[name="cp_obat_c_${index}"]`)?.value) || 0;

            const rowTotal = asesmen_p + asesmen_n + asesmen_c + fisik_p + fisik_n + fisik_c + penunjang_p + penunjang_n + penunjang_c + obat_p + obat_n + obat_c;
            row.querySelector(`input[name="cp_total_${index}"]`).value = rowTotal;

            totalAsesmenP += asesmen_p; totalAsesmenN += asesmen_n; totalAsesmenC += asesmen_c;
            totalFisikP += fisik_p; totalFisikN += fisik_n; totalFisikC += fisik_c;
            totalPenunjangP += penunjang_p; totalPenunjangN += penunjang_n; totalPenunjangC += penunjang_c;
            totalObatP += obat_p; totalObatN += obat_n; totalObatC += obat_c;
            grandTotal += rowTotal;
        }
    });

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
    const compliance = totalObservedItems > 0 ? ((totalAsesmenP + totalFisikP + totalPenunjangP + totalObatP) / totalObservedItems) * 100 : 0; // Assuming 'P' means compliant
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
        <td>${index}</td>
        <td><input type="date" name="kepuasan_tanggal_${index}" value="${entry.tanggal || ''}" /></td>
        <td><input type="text" name="kepuasan_unit_kerja_${index}" value="${entry.unit_kerja || ''}" /></td>
        <td><input type="text" name="kepuasan_nilai_ikm_${index}" value="${entry.nilai_ikm || ''}" /></td>
        <td><select name="kepuasan_jenis_pelayanan_${index}">
            <option ${entry.jenis_pelayanan === 'Rawat Jalan' ? 'selected' : ''}>Rawat Jalan</option>
            <option ${entry.jenis_pelayanan === 'Rawat Inap' ? 'selected' : ''}>Rawat Inap</option>
            <option ${entry.jenis_pelayanan === 'IGD' ? 'selected' : ''}>IGD</option>
            <option ${entry.jenis_pelayanan === 'Farmasi' ? 'selected' : ''}>Farmasi</option>
            <option ${entry.jenis_pelayanan === 'Laboratorium' ? 'selected' : ''}>Laboratorium</option>
        </select></td>
        <td><select name="kepuasan_nilai_kepuasan_${index}">
            <option ${entry.nilai_kepuasan === '1 (Sangat Tidak Puas)' ? 'selected' : ''}>1 (Sangat Tidak Puas)</option>
            <option ${entry.nilai_kepuasan === '2 (Tidak Puas)' ? 'selected' : ''}>2 (Tidak Puas)</option>
            <option ${entry.nilai_kepuasan === '3 (Cukup Puas)' ? 'selected' : ''}>3 (Cukup Puas)</option>
            <option ${entry.nilai_kepuasan === '4 (Puas)' ? 'selected' : ''}>4 (Puas)</option>
            <option ${entry.nilai_kepuasan === '5 (Sangat Puas)' ? 'selected' : ''}>5 (Sangat Puas)</option>
        </select></td>
        <td><input type="text" name="kepuasan_komentar_${index}" value="${entry.komentar || ''}" /></td>
    `;
    // Add event listeners if needed
}

/**
 * Adds a new row to the KRK form table.
 * @param {HTMLElement} tbody - The tbody element of the table.
 * @param {number} index - The index for the new row.
 * @param {object} [entry={}] - Optional initial data for the row.
 */
function addKrkRow(tbody, index, entry = {}) {
    const totalRow = tbody.querySelector('tr:last-child');
    const newRow = tbody.insertRow(totalRow ? tbody.rows.length - 1 : -1);
    newRow.innerHTML = `
        <td><input type="number" name="krk_no_${index}" value="${entry.no || index}" /></td>
        <td><input type="date" name="krk_tgl_${index}" value="${entry.tgl || ''}" /></td>
        <td><input type="text" name="krk_isi_komplain_${index}" value="${entry.isi_komplain || ''}" /></td>
        <td><input type="text" name="krk_kategori_komplain_${index}" value="${entry.kategori_komplain || ''}" /></td>
        <td><input type="checkbox" name="krk_lisan_${index}" ${entry.lisan ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="krk_tulisan_${index}" ${entry.tulisan ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="krk_media_masa_${index}" ${entry.media_masa ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="krk_grading_merah_${index}" ${entry.grading_merah ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="krk_grading_kuning_${index}" ${entry.grading_kuning ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="krk_grading_hijau_${index}" ${entry.grading_hijau ? 'checked' : ''} /></td>
        <td><input type="number" name="krk_waktu_tanggap_${index}" value="${entry.waktu_tanggap || 0}" /></td>
        <td><input type="checkbox" name="krk_penyelesaian_ya_${index}" ${entry.penyelesaian_ya ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="krk_penyelesaian_tidak_${index}" ${entry.penyelesaian_tidak ? 'checked' : ''} /></td>
        <td><input type="text" name="krk_ket_${index}" value="${entry.ket || ''}" /></td>
    `;
    // Add event listeners if needed
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
        <td>${index}</td>
        <td><input type="date" name="poe_tgl_${index}" value="${entry.tgl || ''}" /></td>
        <td><input type="text" name="poe_nama_pasien_${index}" value="${entry.nama_pasien || ''}" /></td>
        <td><input type="text" name="poe_no_rm_${index}" value="${entry.no_rm || ''}" /></td>
        <td><input type="text" name="poe_ruangan_${index}" value="${entry.ruangan || ''}" /></td>
        <td><input type="text" name="poe_diagnosa_${index}" value="${entry.diagnosa || ''}" /></td>
        <td><input type="text" name="poe_tindakan_bedah_${index}" value="${entry.tindakan_bedah || ''}" /></td>
        <td><input type="text" name="poe_dpjp_bedah_${index}" value="${entry.dpjp_bedah || ''}" /></td>
        <td><input type="time" name="poe_jam_rencana_${index}" value="${entry.jam_rencana_operasi || ''}" /></td>
        <td><input type="time" name="poe_jam_insisi_${index}" value="${entry.jam_insisi || ''}" /></td>
        <td><input type="checkbox" name="poe_penundaan_gt_1hr_${index}" ${entry.penundaan_gt_1hr ? 'checked' : ''} /></td>
        <td><input type="checkbox" name="poe_penundaan_lt_1hr_${index}" ${entry.penundaan_lt_1hr ? 'checked' : ''} /></td>
        <td><input type="text" name="poe_keterangan_${index}" value="${entry.keterangan || ''}" /></td>
    `;
    const jamRencanaInput = newRow.querySelector(`input[name="poe_jam_rencana_${index}"]`);
    const jamInsisiInput = newRow.querySelector(`input[name="poe_jam_insisi_${index}"]`);
    const penundaanGt1Hr = newRow.querySelector(`input[name="poe_penundaan_gt_1hr_${index}"]`);
    const penundaanLt1Hr = newRow.querySelector(`input[name="poe_penundaan_lt_1hr_${index}"]`);

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
        <td>${index}</td>
        <td><input type="text" name="sc_nama_pasien_${index}" value="${entry.nama_pasien || ''}" /></td>
        <td><input type="text" name="sc_no_rm_${index}" value="${entry.no_rm || ''}" /></td>
        <td><select name="sc_diagnosa_kategori_${index}">
            <option ${entry.diagnosa_kategori === 'Kategori I' ? 'selected' : ''}>Kategori I</option>
            <option ${entry.diagnosa_kategori === 'Kategori II' ? 'selected' : ''}>Kategori II</option>
            <option ${entry.diagnosa_kategori === 'Kategori III' ? 'selected' : ''}>Kategori III</option>
        </select></td>
        <td><input type="time" name="sc_jam_tiba_igd_${index}" value="${entry.jam_tiba_igd || ''}" /></td>
        <td><input type="time" name="sc_jam_diputuskan_operasi_${index}" value="${entry.jam_diputuskan_operasi || ''}" /></td>
        <td><input type="time" name="sc_jam_mulai_insisi_${index}" value="${entry.jam_mulai_insisi || ''}" /></td>
        <td><input type="number" name="sc_waktu_tanggap_${index}" value="${entry.waktu_tanggap || 0}" readonly /></td>
        <td><select name="sc_gt_30_menit_${index}">
            <option ${entry.gt_30_menit === 'Ya' ? 'selected' : ''}>Ya</option>
            <option ${entry.gt_30_menit === 'Tidak' ? 'selected' : ''}>Tidak</option>
        </select></td>
        <td><input type="text" name="sc_keterangan_${index}" value="${entry.keterangan || ''}" /></td>
    `;

    const jamDiputuskanOperasiInput = newRow.querySelector(`input[name="sc_jam_diputuskan_operasi_${index}"]`);
    const jamMulaiInsisiInput = newRow.querySelector(`input[name="sc_jam_mulai_insisi_${index}"]`);
    const waktuTanggapInput = newRow.querySelector(`input[name="sc_waktu_tanggap_${index}"]`);
    const gt30MenitSelect = newRow.querySelector(`select[name="sc_gt_30_menit_${index}"]`);

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
 * @param {HTMLElement} row - The table row for Hand Hygiene.
 */
function updateHandHygieneTotals(row) {
    const getVal = (name) => parseInt(row.querySelector(`input[name="${name}"]`)?.value) || 0;

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

    row.querySelector('input[name="total_kesempatan"]').value = total_kesempatan;
    row.querySelector('input[name="total_handwash"]').value = total_handwash;
    row.querySelector('input[name="total_handrub"]').value = total_handrub;
}


// --- Main Application Logic ---

document.addEventListener('DOMContentLoaded', async function() {
    showLoading();
    await initializeData();
    await checkAndAutoSubmitOldForms(); // Run after initial data load
    updateStatisticsDisplay();
    showSection('list'); // Show the list after everything is loaded and updated
    setupFormEventListeners();
    hideLoading();
    showNotification('Selamat datang di Dashboard Indikator Mutu!', 'info');
});

/**
 * Initializes data for all forms by fetching current week's data and history from the API.
 */
async function initializeData() {
    for (const indicator of indicators) {
        const formType = indicator.id;
        try {
            const currentResponse = await authenticatedFetch(`${API_BASE_URL}/${formType}/current`);
            if (!currentResponse.ok) throw new Error(`HTTP error! status: ${currentResponse.status}`);
            formCurrentData[formType] = await currentResponse.json();

            const historyResponse = await authenticatedFetch(`${API_BASE_URL}/${formType}/history`);
            if (!historyResponse.ok) throw new Error(`HTTP error! status: ${historyResponse.status}`);
            formHistoryData[formType] = await historyResponse.json();

        } catch (error) {
            console.error(`Error initializing data for ${formType}:`, error);
            showNotification(`Failed to load data for ${formType}`, 'error');
            // Ensure formCurrentData and formHistoryData are initialized even on error
            formCurrentData[formType] = { data: {}, week_start_date: moment().startOf('isoWeek').format('YYYY-MM-DD') };
            formHistoryData[formType] = [];
        }
    }
}

/**
 * Checks for old week's data and auto-submits it if a new week has started.
 * Then, it ensures the current week's form is displayed.
 */
async function checkAndAutoSubmitOldForms() {
    const currentMomentWeekStart = moment().startOf('isoWeek'); // Monday
    const currentWeekStartDate = currentMomentWeekStart.format('YYYY-MM-DD');

    for (const indicator of indicators) {
        const formType = indicator.id;
        const currentFormDataEntry = formCurrentData[formType];

        if (currentFormDataEntry && currentFormDataEntry.week_start_date) {
            const dataWeekStart = moment(currentFormDataEntry.week_start_date);

            // If the data is from a previous week
            if (dataWeekStart.isBefore(currentMomentWeekStart, 'day')) {
                console.log(`Detected old week data for ${formType}: ${currentFormDataEntry.week_start_date}. Auto-submitting...`);
                showNotification(`Form ${formType} from week ${currentFormDataEntry.week_start_date} is being finalized.`, 'info');

                // Submit the old data to finalize it as a historical entry
                await saveFormData(formType, currentFormDataEntry.week_start_date);

                // Now, fetch the *new* current week's data (which the backend command creates as empty)
                try {
                    const newWeekResponse = await authenticatedFetch(`${API_BASE_URL}/${formType}/current`);
                    if (!newWeekResponse.ok) throw new Error(`HTTP error! status: ${newWeekResponse.status}`);
                    formCurrentData[formType] = await newWeekResponse.json();
                    showNotification(`Form ${formType} updated for current week.`, 'success');
                } catch (error) {
                    console.error(`Error fetching new week data for ${formType} after auto-submit:`, error);
                    showNotification(`Failed to load new week data for ${formType}.`, 'error');
                }
            }
        }
    }
}


/**
 * Sets up event listeners for form interactions (save button, add row buttons, dynamic calculations).
 */
function setupFormEventListeners() {
    // Save buttons for all forms
    document.querySelectorAll('.form-card .save-btn').forEach(button => {
        button.addEventListener('click', async function() {
            const formCard = this.closest('.form-card');
            const formType = Object.keys(formIdMap).find(key => formIdMap[key] === formCard.id);
            if (formType) {
                await saveFormData(formType);
            }
        });
    });

    // Add Row button for APD form
    const addApdRowButton = document.getElementById('add-apd-row');
    if (addApdRowButton) {
        addApdRowButton.addEventListener('click', () => {
            const tbody = document.querySelector('#apd-form .form-table tbody');
            const newIndex = tbody.querySelectorAll('tr').length + 1;
            addApdRow(tbody, newIndex);
            // Re-attach event listeners for newly added rows if needed (e.g., for checkboxes)
        });
    }

    // Dynamic calculations for Hand Hygiene
    const handHygieneForm = document.getElementById('kebersihan-form');
    if (handHygieneForm) {
        handHygieneForm.querySelectorAll('.form-table input[type="number"]').forEach(input => {
            input.addEventListener('input', function() {
                updateHandHygieneTotals(this.closest('tr'));
            });
        });
    }

    // Auto-save logic on input change (with debounce)
    document.addEventListener('input', function(e) {
        // Exclude specific calculations handled by dedicated listeners
        if (e.target.closest('#wtri-form') && (e.target.name.startsWith('wtri_jam_reg_pendaftaran_') || e.target.name.startsWith('wtri_jam_dilayani_dokter_'))) {
            // WTPR calculation is handled by its specific listener
            return;
        }
        if (e.target.closest('#kritis-form') && (e.target.name.startsWith('kritis_waktu_hasil_keluar_') || e.target.name.startsWith('kritis_waktu_dilaporkan_'))) {
            // Kritis Lab calculation is handled by its specific listener
            return;
        }
        if (e.target.closest('#poe-form') && (e.target.name.startsWith('poe_jam_rencana_') || e.target.name.startsWith('poe_jam_insisi_'))) {
            // POE calculation is handled by its specific listener
            return;
        }
        if (e.target.closest('#sc-form') && (e.target.name.startsWith('sc_jam_diputuskan_operasi_') || e.target.name.startsWith('sc_jam_mulai_insisi_'))) {
            // SC calculation is handled by its specific listener
            return;
        }
        if (e.target.closest('#visite-form') && e.target.name.startsWith('visite_jam_')) {
            // Visite calculation is handled by its specific listener
            return;
        }
        if (e.target.closest('#jatuh-form') && e.target.name.startsWith('jatuh_assessment')) {
            // Jatuh calculation is handled by its specific listener
            return;
        }
        if (e.target.closest('#cp-form') && e.target.name.startsWith('cp_')) {
            // CP calculation is handled by its specific listener
            return;
        }


        // Generic auto-save after 5 seconds of inactivity
        clearTimeout(window.autoSaveTimer);
        window.autoSaveTimer = setTimeout(() => {
            const formCard = e.target.closest('.form-card');
            // Ensure not to auto-save the history section or if no form card is found
            if (formCard && formCard.id !== 'history-section') {
                const formType = Object.keys(formIdMap).find(key => formIdMap[key] === formCard.id);
                if (formType) {
                    console.log('Auto-saving data for:', formType);
                    saveFormData(formType); // Save data for the active form
                }
            }
        }, 5000); // 5 seconds debounce
    });
}


/**
 * Saves form data to the backend API.
 * @param {string} formType - The type of the form to save.
 * @param {string} [weekStartDate=null] - Optional specific week start date for historical saves.
 */
async function saveFormData(formType, weekStartDate = null) {
    showLoading();
    try {
        const dataToSave = getFormData(formType);
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
        if (isFormComplete(indicator.id, formData)) {
            indicator.status = 'completed';
            completedCount++;
        } else if (formData && Object.keys(formData).length > 0) {
            // Form has some data, but not "complete" by isFormComplete definition
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
    historyContent.className = 'history-data-display mt-4 overflow-x-auto';

    const history = formHistoryData[formType];

    if (!history || history.length === 0) {
        historyContent.innerHTML = `<p class="text-gray-600">Tidak ada riwayat tersedia untuk ${formType.replace(/-/g, ' ')}.</p>`;
    } else {
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
        if (btnText === indicatorName || btnText === formType.replace(/-/g, ' ')) {
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
            return `Sesi: ${data.sesi || '-'}, Total Kesempatan: ${data.total_kesempatan || 0}, Handwash: ${data.total_handwash || 0}, Handrub: ${data.total_handrub || 0}`;
        case 'apd':
            return `Entries: ${data.entries?.length || 0} (e.g., Tgl: ${data.entries?.[0]?.tgl || '-'}, Profesi: ${data.entries?.[0]?.profesi || '-'})`;
        case 'identifikasi':
            return `Unit: ${data.unit_kerja || '-'}, Entries: ${data.entries?.length || 0}`;
        case 'wtri':
            return `Unit: ${data.unit || '-'}, Entries: ${data.entries?.length || 0}`;
        case 'kritis-lab':
            return `Entries: ${data.entries?.length || 0} (e.g., Critical Value: ${data.entries?.[0]?.critical_value || '-'})`;
        case 'fornas':
            return `Entries: ${data.entries?.length || 0} (e.g., Resep: ${data.entries?.[0]?.jumlah_resep || 0}, Fornas: ${data.entries?.[0]?.formularium_n || 0})`;
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

