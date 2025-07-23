    // Global variables
    let currentSection = 'list';
    let formCurrentData = {}; // Stores current week's data for each form type
    let formHistoryData = {}; // Stores history data for each form type
    let handHygieneChartInstance = null;
    let apdChartInstance = null;
    let identifikasiChartInstance = null;
    let wtriChartInstance = null;
    let kritisLabChartInstance = null;
    let fornasChartInstance = null;
    let visiteChartInstance = null;
    let jatuhChartInstance = null;
    let cpChartInstance = null;
    let kepuasanChartInstance = null;
    let krkChartInstance = null;
    let poeChartInstance = null;
    let scChartInstance = null;

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

    function calculateCompliance(formType, formData) {
        if (!formData || !formData.entries || formData.entries.length === 0) {
            return 0;
        }

        const entries = formData.entries;
        let numerator = 0;
        let denominator = 0;

        switch (formType) {
            case 'hand-hygiene':
                // FIX: This now correctly sums up the compliant actions and divides by the total opportunities.
                numerator = entries.reduce((sum, entry) => sum + (parseInt(entry.total_handwash) || 0) + (parseInt(entry.total_handrub) || 0), 0);
                denominator = entries.reduce((sum, entry) => sum + (parseInt(entry.total_kesempatan) || 0), 0);
                break;
            case 'apd':
            case 'jatuh':
                numerator = entries.filter(entry => entry.kepatuhan === 'Patuh' || entry.ketiga_upaya_ya).length;
                denominator = entries.length;
                break;
            case 'identifikasi':
                numerator = entries.filter(entry => entry.dilakukan).length;
                denominator = entries.length;
                break;
            case 'wtri':
                numerator = entries.filter(entry => (parseInt(entry.respon_time_ca) || 0) <= 60).length;
                denominator = entries.length;
                break;
            case 'kritis-lab':
                numerator = entries.filter(entry => entry.pelaporan_status === '≤ 30 Menit').length;
                denominator = entries.length;
                break;
            case 'fornas':
                numerator = entries.filter(entry => entry.formularium_nasional).length;
                denominator = entries.length;
                break;
            case 'visite':
                numerator = entries.filter(entry => {
                    const jam = entry.jam ? entry.jam.split(':')[0] : '99';
                    return parseInt(jam) < 14;
                }).length;
                denominator = entries.length;
                break;
            case 'cp':
                const totals = formData.totals || {};
                numerator = (totals.asesmen_p || 0) + (totals.fisik_p || 0) + (totals.penunjang_p || 0) + (totals.obat_p || 0);
                denominator = numerator + (totals.asesmen_n || 0) + (totals.asesmen_c || 0) + (totals.fisik_n || 0) + (totals.fisik_c || 0) + (totals.penunjang_n || 0) + (totals.penunjang_c || 0) + (totals.obat_n || 0) + (totals.obat_c || 0);
                break;
            case 'kepuasan':
                numerator = entries.filter(entry => {
                    const score = parseInt(entry.nilai_kepuasan);
                    return score >= 4;
                }).length;
                denominator = entries.length;
                break;
            case 'krk':
                numerator = entries.filter(entry => entry.penyelesaian_ya).length;
                denominator = entries.length;
                break;
            case 'poe': // Target is <5%, so we calculate NON-DELAY compliance
                numerator = entries.filter(entry => entry.penundaan_lt_1hr).length;
                denominator = entries.length;
                break;
            case 'sc':
                numerator = entries.filter(entry => (parseInt(entry.waktu_tanggap) || 99) <= 30).length;
                denominator = entries.length;
                break;
            default:
                return 0;
        }

        return denominator > 0 ? Math.round((numerator / denominator) * 100) : 0;
    }

    function updateComplianceBars() {
        indicators.forEach(indicator => {
            const formType = indicator.form_type;
            const currentData = formCurrentData[formType]?.data;
            const percentage = calculateCompliance(formType, currentData);

            const indicatorElement = document.getElementById(`indicator-${formType}`);
            if (indicatorElement) {
                const innerBar = indicatorElement.querySelector('.progress-bar-inner');
                const label = indicatorElement.querySelector('.progress-bar-label');

                if (innerBar && label) {
                    innerBar.style.width = `${percentage}%`;
                    label.textContent = `${percentage}%`;
                    
                    // Change bar color based on compliance
                    if (percentage < 50) {
                        innerBar.style.backgroundColor = '#dc3545'; // Red
                    } else if (percentage < 80) {
                        innerBar.style.backgroundColor = '#ffc107'; // Yellow
                    } else {
                        innerBar.style.backgroundColor = '#28a745'; // Green
                    }
                }
            }
        });
    }

    function updateFormCardComplianceBar(formElement, formType, data) {
        if (!formElement) return;

        const percentage = calculateCompliance(formType, data);

        const innerBar = formElement.querySelector('.progress-bar-inner');
        const label = formElement.querySelector('.progress-bar-label');

        if (innerBar && label) {
            innerBar.style.width = `${percentage}%`;
            label.textContent = `${percentage}%`;

            if (percentage < 50) {
                innerBar.style.backgroundColor = '#dc3545'; // Red
            } else if (percentage < 80) {
                innerBar.style.backgroundColor = '#ffc107'; // Yellow
            } else {
                innerBar.style.backgroundColor = '#28a745'; // Green
            }
        }
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


    
    // --- Chart ---

    /**
 * Renders the Hand Hygiene compliance chart.
 * Shows compliance over time, or by professional type if enough data exists.
 * @param {CanvasRenderingContext2D} ctx - The canvas rendering context.
 * @param {Array} entries - The data entries for Hand Hygiene.
 */
    function renderHandHygieneChart(ctx, entries) {
        if (ctx && handHygieneChartInstance) { // Only destroy if rendering to a live canvas
            handHygieneChartInstance.destroy();
        }

        const professionalCompliance = {
            'DPJP': { opportunities: 0, compliant: 0 },
            'Perawat': { opportunities: 0, compliant: 0 },
            'Pendidikan': { opportunities: 0, compliant: 0 },
            'Lain-lain': { opportunities: 0, compliant: 0 }
        };

        if (entries && entries.length > 0) {
            entries.forEach(entry => {
                professionalCompliance['DPJP'].opportunities += parseInt(entry.dpjp_kesempatan) || 0;
                professionalCompliance['DPJP'].compliant += (parseInt(entry.dpjp_handwash) || 0) + (parseInt(entry.dpjp_handrub) || 0);

                professionalCompliance['Perawat'].opportunities += parseInt(entry.perawat_kesempatan) || 0;
                professionalCompliance['Perawat'].compliant += (parseInt(entry.perawat_handwash) || 0) + (parseInt(entry.perawat_handrub) || 0);

                professionalCompliance['Pendidikan'].opportunities += parseInt(entry.pendidikan_kesempatan) || 0;
                professionalCompliance['Pendidikan'].compliant += (parseInt(entry.pendidikan_handwash) || 0) + (parseInt(entry.pendidikan_handrub) || 0);
                
                professionalCompliance['Lain-lain'].opportunities += parseInt(entry.lain_kesempatan) || 0;
                professionalCompliance['Lain-lain'].compliant += (parseInt(entry.lain_handwash) || 0) + (parseInt(entry.lain_handrub) || 0);
            });
        }

        const labels = Object.keys(professionalCompliance);
        const data = labels.map(prof => {
            const group = professionalCompliance[prof];
            const compliantActions = group.compliant;
            return group.opportunities > 0 ? Math.round((compliantActions / group.opportunities) * 100) : 0;
        });

        // Step 1: Define the configuration object
        const config = {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Kepatuhan (%)',
                    data: data,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(153, 102, 255, 0.7)'
                    ],
                    borderColor: [
                        'rgb(54, 162, 235)',
                        'rgb(75, 192, 192)',
                        'rgb(255, 206, 86)',
                        'rgb(153, 102, 255)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: 'Kepatuhan Kebersihan Tangan per Profesi' },
                    legend: { display: false },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        // The 'max' was removed to allow values >100% to be visible, indicating data entry errors.
                        // If you want to cap the visual chart at 100%, you can add back: max: 100,
                        title: { display: true, text: 'Persentase Kepatuhan (%)' }
                    }
                }
            }
        };

        // Step 2: Only create a new Chart instance if a canvas context is provided (i.e., not for PDF export)
        if (ctx) {
            handHygieneChartInstance = new Chart(ctx, config);
        }

        // Step 3: Always return the config object for the PDF exporter to use
        return { config };
    }

    function renderApdChart(ctx, entries) {
        let chartInstance = apdChartInstance;
        if (ctx && chartInstance) chartInstance.destroy();
        
        // Your original detailed logic
        const apdTypes = [{ key: 'sarung_tangan', label: 'Sarung Tangan' }, { key: 'masker', label: 'Masker' }, { key: 'topi', label: 'Topi' }, { key: 'google', label: 'Google' }, { key: 'pakaian', label: 'Pakaian' }, { key: 'sepatu', label: 'Sepatu' }];
        const overallComplianceCounts = { 'Patuh': 0, 'Tidak': 0 };
        const apdTypeCompliance = {};
        entries.forEach(entry => {
            if (entry.kepatuhan === 'Patuh') overallComplianceCounts.Patuh++; else if (entry.kepatuhan === 'Tidak') overallComplianceCounts.Tidak++;
            apdTypes.forEach(apd => {
                if (!apdTypeCompliance[apd.label]) apdTypeCompliance[apd.label] = { 'Ya': 0, 'Tidak': 0 };
                if (entry[`${apd.key}_y`]) apdTypeCompliance[apd.label].Ya++; if (entry[`${apd.key}_t`]) apdTypeCompliance[apd.label].Tidak++;
            });
        });
        let config;
        const hasEnoughApdTypeData = Object.values(apdTypeCompliance).some(vals => vals.Ya > 0 || vals.Tidak > 0);

        if (hasEnoughApdTypeData) {
            let labels = [], compliantData = [], nonCompliantData = [];
            apdTypes.forEach(apd => {
                const total = (apdTypeCompliance[apd.label]?.Ya || 0) + (apdTypeCompliance[apd.label]?.Tidak || 0);
                if (total > 0) {
                    labels.push(apd.label);
                    compliantData.push((apdTypeCompliance[apd.label].Ya / total * 100).toFixed(2));
                    nonCompliantData.push((apdTypeCompliance[apd.label].Tidak / total * 100).toFixed(2));
                }
            });
            if (labels.length === 0) { labels.push('Tidak Ada Data'); compliantData.push(0); nonCompliantData.push(0); }
            config = {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        { label: 'Patuh (%)', data: compliantData, backgroundColor: 'rgba(75, 192, 192, 0.7)', borderColor: 'rgba(75, 192, 192, 1)', borderWidth: 1 },
                        { label: 'Tidak Patuh (%)', data: nonCompliantData, backgroundColor: 'rgba(255, 99, 132, 0.7)', borderColor: 'rgba(255, 99, 132, 1)', borderWidth: 1 }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { title: { display: true, text: 'Kepatuhan APD per Jenis' }, legend: { position: 'top' }, tooltip: { callbacks: { label: c => `${c.dataset.label}: ${c.raw}%` } } },
                    scales: { x: { stacked: false, title: { display: true, text: 'Jenis APD' } }, y: { stacked: false, beginAtZero: true, max: 100, title: { display: true, text: 'Persentase (%)' } } }
                }
            };
        } else {
            const total = overallComplianceCounts.Patuh + overallComplianceCounts.Tidak;
            config = {
                type: 'pie',
                data: {
                    labels: total > 0 ? ['Patuh', 'Tidak Patuh'] : ['Tidak Ada Data'],
                    datasets: [{ data: total > 0 ? [overallComplianceCounts.Patuh, overallComplianceCounts.Tidak] : [1], backgroundColor: total > 0 ? ['#4bc0c0', '#ff6384'] : ['#ccc'], borderColor: '#fff' }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: {
                        title: { display: true, text: 'Kepatuhan APD (Keseluruhan)' },
                        legend: { position: 'right' },
                        tooltip: { callbacks: { label: c => { if (total === 0) return 'Tidak Ada Data'; return `${c.label}: ${c.raw} (${(c.raw / total * 100).toFixed(2)}%)`; } } }
                    }
                }
            };
        }

        if (ctx) { apdChartInstance = new Chart(ctx, config); }
        return { config };
    }

    function renderIdentifikasiChart(ctx, entries) {
        let chartInstance = identifikasiChartInstance;
        if (ctx && chartInstance) chartInstance.destroy();
        
        // Your original detailed logic
        const totalObservations = entries.length;
        const complianceCounts = { 'Dilakukan': 0, 'Tidak Dilakukan': 0 };
        entries.forEach(entry => {
            if (entry.dilakukan) complianceCounts.Dilakukan++;
            if (entry.tidak_dilakukan) complianceCounts['Tidak Dilakukan']++;
        });
        
        const config = {
            type: 'pie',
            data: {
                labels: totalObservations > 0 ? Object.keys(complianceCounts) : ['Tidak Ada Data'],
                datasets: [{ data: totalObservations > 0 ? Object.values(complianceCounts) : [1], backgroundColor: totalObservations > 0 ? ['#4bc0c0', '#ff6384'] : ['#ccc'] }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: `Kepatuhan Identifikasi Pasien (Total: ${totalObservations})` },
                    legend: { position: 'right' },
                    tooltip: { callbacks: { label: c => { if (totalObservations === 0) return 'Tidak Ada Data'; return `${c.label}: ${c.raw} (${(c.raw / totalObservations * 100).toFixed(2)}%)`; }}}
                }
            }
        };
        if (ctx) { identifikasiChartInstance = new Chart(ctx, config); }
        return { config };
    }

    function renderWtriChart(ctx, entries) {
        let chartInstance = wtriChartInstance;
        if (ctx && chartInstance) chartInstance.destroy();
        
        // Your original detailed logic
        const dailyAverages = entries.reduce((acc, current) => {
            const date = moment(current.tgl).format('YYYY-MM-DD');
            if (!acc[date]) acc[date] = { totalCA: 0, countCA: 0, totalCB: 0, countCB: 0 };
            acc[date].totalCA += parseInt(current.respon_time_ca) || 0;
            acc[date].countCA++;
            acc[date].totalCB += parseInt(current.respon_time_cb) || 0;
            acc[date].countCB++;
            return acc;
        }, {});
        const labels = Object.keys(dailyAverages).sort();
        let config;
        if (labels.length === 0) {
            config = { type: 'bar', data: { labels: ['Tidak Ada Data'], datasets: [{ data: [0] }] }, options: { plugins: { title: { display: true, text: 'Waktu Tunggu Rawat Jalan' } } } };
        } else {
            const avgDataCA = labels.map(date => (dailyAverages[date].totalCA / dailyAverages[date].countCA).toFixed(0));
            const avgDataCB = labels.map(date => (dailyAverages[date].totalCB / dailyAverages[date].countCB).toFixed(0));
            config = {
                type: 'line',
                data: {
                    labels: labels.map(d => moment(d).format('DD MMM')),
                    datasets: [
                        { label: 'Respon Time (C-A) menit', data: avgDataCA, borderColor: '#36a2eb', fill: false },
                        { label: 'Respon Time (C-B) menit', data: avgDataCB, borderColor: '#ff6384', fill: false }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { title: { display: true, text: 'Rata-rata Waktu Tunggu Rawat Jalan' }, legend: { position: 'top' }, tooltip: { callbacks: { label: c => `${c.dataset.label}: ${c.raw} menit` } } },
                    scales: { y: { beginAtZero: true, title: { display: true, text: 'Waktu (Menit)' } }, x: { title: { display: true, text: 'Tanggal' } } }
                }
            };
        }

        if (ctx) { wtriChartInstance = new Chart(ctx, config); }
        return { config };
    }

    function renderKritisLabChart(ctx, entries) {
        let chartInstance = kritisLabChartInstance;
        if (ctx && chartInstance) chartInstance.destroy();
        
        // Your original detailed logic
        const total = entries.length;
        const statusCounts = { '≤ 30 Menit': 0, '> 30 Menit': 0 };
        entries.forEach(e => { if (statusCounts.hasOwnProperty(e.pelaporan_status)) statusCounts[e.pelaporan_status]++; });
        
        const config = {
            type: 'pie',
            data: {
                labels: total > 0 ? Object.keys(statusCounts) : ['Tidak Ada Data'],
                datasets: [{ data: total > 0 ? Object.values(statusCounts) : [1], backgroundColor: total > 0 ? ['#4bc0c0', '#ff6384'] : ['#ccc'] }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: `Waktu Lapor Hasil Kritis (Total: ${total})` },
                    legend: { position: 'right' },
                    tooltip: { callbacks: { label: c => { if (total === 0) return 'Tidak Ada Data'; return `${c.label}: ${c.raw} (${(c.raw / total * 100).toFixed(2)}%)`; } } }
                }
            }
        };
        if (ctx) { kritisLabChartInstance = new Chart(ctx, config); }
        return { config };
    }

    function renderFornasChart(ctx, entries) {
        let chartInstance = fornasChartInstance;
        if (ctx && chartInstance) chartInstance.destroy();
        
        // Your original detailed logic
        const total = entries.length;
        const compliant = entries.filter(e => e.formularium_nasional).length;
        
        const config = {
            type: 'pie',
            data: {
                labels: total > 0 ? ['Sesuai Fornas', 'Tidak Sesuai'] : ['Tidak Ada Data'],
                datasets: [{ data: total > 0 ? [compliant, total - compliant] : [1], backgroundColor: total > 0 ? ['#4bc0c0', '#ff6384'] : ['#ccc'] }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: `Kepatuhan Fornas (Total: ${total})` },
                    legend: { position: 'right' },
                    tooltip: { callbacks: { label: c => { if (total === 0) return 'Tidak Ada Data'; return `${c.label}: ${c.raw} (${(c.raw / total * 100).toFixed(2)}%)`; } } }
                }
            }
        };
        if (ctx) { fornasChartInstance = new Chart(ctx, config); }
        return { config };
    }

    function renderVisiteChart(ctx, entries) {
        let chartInstance = visiteChartInstance;
        if (ctx && chartInstance) chartInstance.destroy();

        // Your original detailed logic
        const timeCategories = { '≤10:00': 0, '>10-12:00': 0, '>12-14:00': 0, '>14:00': 0 };
        const total = entries.length;
        entries.forEach(e => {
            if (e.val_i) timeCategories['≤10:00']++; if (e.val_ii) timeCategories['>10-12:00']++;
            if (e.val_iii) timeCategories['>12-14:00']++; if (e.val_iv) timeCategories['>14:00']++;
        });

        const config = {
            type: 'doughnut',
            data: {
                labels: total > 0 ? Object.keys(timeCategories) : ['Tidak Ada Data'],
                datasets: [{ data: total > 0 ? Object.values(timeCategories) : [1], backgroundColor: total > 0 ? ['#4bc0c0', '#36a2eb', '#ffce56', '#ff6384'] : ['#ccc'] }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: `Distribusi Waktu Visite (Total: ${total})` },
                    legend: { position: 'right' },
                    tooltip: { callbacks: { label: c => { if (total === 0) return 'Tidak Ada Data'; return `${c.label}: ${c.raw} (${(c.raw / total * 100).toFixed(2)}%)`; } } }
                }
            }
        };
        if (ctx) { visiteChartInstance = new Chart(ctx, config); }
        return { config };
    }

    function renderJatuhChart(ctx, entries) {
        let chartInstance = jatuhChartInstance;
        if (ctx && chartInstance) chartInstance.destroy();

        // Your original detailed logic
        const total = entries.length;
        const compliant = entries.filter(e => e.ketiga_upaya_ya).length;

        const config = {
            type: 'pie',
            data: {
                labels: total > 0 ? ['Patuh (3 Upaya)', 'Tidak Patuh'] : ['Tidak Ada Data'],
                datasets: [{ data: total > 0 ? [compliant, total - compliant] : [1], backgroundColor: total > 0 ? ['#4bc0c0', '#ff6384'] : ['#ccc'] }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: `Kepatuhan Pencegahan Jatuh (Total: ${total})` },
                    legend: { position: 'right' },
                    tooltip: { callbacks: { label: c => { if (total === 0) return 'Tidak Ada Data'; return `${c.label}: ${c.raw} (${(c.raw / total * 100).toFixed(2)}%)`; } } }
                }
            }
        };
        if (ctx) { jatuhChartInstance = new Chart(ctx, config); }
        return { config };
    }

    function renderCpChart(ctx, entries, formData) {
        let chartInstance = cpChartInstance;
        if (ctx && chartInstance) chartInstance.destroy();
        
        // Your original detailed logic
        const compliancePercentage = parseFloat(formData.rata_rata_kepatuhan) || 0;
        const total = entries.length;

        const config = {
            type: 'doughnut',
            data: {
                labels: total > 0 ? ['Kepatuhan', 'Non-Kepatuhan'] : ['Tidak Ada Data'],
                datasets: [{ data: total > 0 ? [compliancePercentage, 100 - compliancePercentage] : [100], backgroundColor: total > 0 ? ['#4bc0c0', '#e0e0e0'] : ['#ccc'] }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: `Rata-rata Kepatuhan Clinical Pathway: ${compliancePercentage.toFixed(2)}%` },
                    legend: { position: 'right' },
                    tooltip: { callbacks: { label: c => `${c.label}: ${c.raw}%` } }
                }
            }
        };
        if (ctx) { cpChartInstance = new Chart(ctx, config); }
        return { config };
    }

    function renderKepuasanChart(ctx, entries) {
        let chartInstance = kepuasanChartInstance;
        if (ctx && chartInstance) chartInstance.destroy();
        
        // Your original detailed logic
        const satisfactionLevels = { '1 (Sangat Tidak Puas)': 0, '2 (Tidak Puas)': 0, '3 (Cukup Puas)': 0, '4 (Puas)': 0, '5 (Sangat Puas)': 0 };
        const total = entries.length;
        entries.forEach(e => { if (satisfactionLevels.hasOwnProperty(e.nilai_kepuasan)) satisfactionLevels[e.nilai_kepuasan]++; });

        const config = {
            type: 'bar',
            data: {
                labels: total > 0 ? Object.keys(satisfactionLevels) : ['Tidak Ada Data'],
                datasets: [{ label: 'Jumlah Responden', data: total > 0 ? Object.values(satisfactionLevels) : [0], backgroundColor: ['#ff6384', '#ff9f40', '#ffce56', '#4bc0c0', '#36a2eb'] }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: `Distribusi Kepuasan Pasien (Total: ${total})` },
                    legend: { display: false },
                    tooltip: { callbacks: { label: c => { if (total === 0) return 'Tidak Ada Data'; return `Responden: ${c.parsed.y} (${(c.parsed.y / total * 100).toFixed(2)}%)`; } } }
                },
                scales: { y: { beginAtZero: true, title: { display: true, text: 'Jumlah Responden' } } }
            }
        };
        if (ctx) { kepuasanChartInstance = new Chart(ctx, config); }
        return { config };
    }

    function renderKrkChart(ctx, entries) {
        let chartInstance = krkChartInstance;
        if (ctx && chartInstance) chartInstance.destroy();

        // Your original detailed logic
        const total = entries.length;
        const compliant = entries.filter(e => e.penyelesaian_ya).length;
        
        const config = {
            type: 'pie',
            data: {
                labels: total > 0 ? ['Sesuai Grading', 'Tidak Sesuai'] : ['Tidak Ada Data'],
                datasets: [{ data: total > 0 ? [compliant, total - compliant] : [1], backgroundColor: total > 0 ? ['#4bc0c0', '#ff6384'] : ['#ccc'] }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: `Kecepatan Tanggap Komplain (Total: ${total})` },
                    legend: { position: 'right' },
                    tooltip: { callbacks: { label: c => { if (total === 0) return 'Tidak Ada Data'; return `${c.label}: ${c.raw} (${(c.raw / total * 100).toFixed(2)}%)`; } } }
                }
            }
        };
        if (ctx) { krkChartInstance = new Chart(ctx, config); }
        return { config };
    }

    function renderPoeChart(ctx, entries) {
        let chartInstance = poeChartInstance;
        if (ctx && chartInstance) chartInstance.destroy();
        
        // Your original detailed logic
        const total = entries.length;
        const nonCompliant = entries.filter(e => e.penundaan_gt_1hr).length;
        
        const config = {
            type: 'pie',
            data: {
                labels: total > 0 ? ['Tepat Waktu (≤ 1 jam)', 'Tertunda (> 1 jam)'] : ['Tidak Ada Data'],
                datasets: [{ data: total > 0 ? [total - nonCompliant, nonCompliant] : [1], backgroundColor: total > 0 ? ['#4bc0c0', '#ff6384'] : ['#ccc'] }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: `Penundaan Operasi Elektif (Total: ${total})` },
                    legend: { position: 'right' },
                    tooltip: { callbacks: { label: c => { if (total === 0) return 'Tidak Ada Data'; return `${c.label}: ${c.raw} (${(c.raw / total * 100).toFixed(2)}%)`; } } }
                }
            }
        };
        if (ctx) { poeChartInstance = new Chart(ctx, config); }
        return { config };
    }

    function renderScChart(ctx, entries) {
        let chartInstance = scChartInstance;
        if (ctx && chartInstance) chartInstance.destroy();
        
        // Your original detailed logic
        const total = entries.length;
        const compliant = entries.filter(e => (parseInt(e.waktu_tanggap) || 999) <= 30).length;
        
        const config = {
            type: 'pie',
            data: {
                labels: total > 0 ? ['Dalam Target (≤ 30 menit)', 'Luar Target (> 30 menit)'] : ['Tidak Ada Data'],
                datasets: [{ data: total > 0 ? [compliant, total - compliant] : [1], backgroundColor: total > 0 ? ['#4bc0c0', '#ff6384'] : ['#ccc'] }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: `Waktu Tanggap SC Emergensi (Total: ${total})` },
                    legend: { position: 'right' },
                    tooltip: { callbacks: { label: c => { if (total === 0) return 'Tidak Ada Data'; return `${c.label}: ${c.raw} (${(c.raw / total * 100).toFixed(2)}%)`; } } }
                }
            }
        };
        if (ctx) { scChartInstance = new Chart(ctx, config); }
        return { config };
    }


    function renderChartForForm(formType, formData) {
        const canvasIdMap = {
            'hand-hygiene': 'handHygieneChart',
            'apd': 'apdChart',
            'identifikasi': 'identifikasiChart',
            'wtri': 'wtriChart',
            'kritis-lab': 'kritisLabChart',
            'fornas': 'fornasChart',
            'visite': 'visiteChart',
            'jatuh': 'jatuhChart',
            'cp': 'cpChart',
            'kepuasan': 'kepuasanChart',
            'krk': 'krkChart',
            'poe': 'poeChart',
            'sc': 'scChart',
        };

        const canvas = document.getElementById(canvasIdMap[formType]);
        if (!canvas) {
            console.warn(`Canvas for ${formType} not found.`);
            return;
        }
        const ctx = canvas.getContext('2d');
        if (!ctx) {
            console.error(`Failed to get 2D context for ${formType} chart.`);
            return;
        }

        // Prepare data and call specific chart renderer
        switch (formType) {
            case 'hand-hygiene':
                renderHandHygieneChart(ctx, formData.entries || []);
                break;
            case 'apd':
                renderApdChart(ctx, formData.entries || []);
                break;
            case 'identifikasi':
                renderIdentifikasiChart(ctx, formData.entries || []);
                break;
            case 'wtri':
                renderWtriChart(ctx, formData.entries || []);
                break;
            case 'kritis-lab':
                renderKritisLabChart(ctx, formData.entries || []);
                break;
            case 'fornas':
                renderFornasChart(ctx, formData.entries || []);
                break;
            case 'visite':
                renderVisiteChart(ctx, formData.entries || []);
                break;
            case 'jatuh':
                renderJatuhChart(ctx, formData.entries || []);
                break;
            case 'cp':
                renderCpChart(ctx, formData.entries || []);
                break;
            case 'kepuasan':
                renderKepuasanChart(ctx, formData.entries || []);
                break;
            case 'krk':
                renderKrkChart(ctx, formData.entries || []);
                break;
            case 'poe':
                renderPoeChart(ctx, formData.entries || []);
                break;
            case 'sc':
                renderScChart(ctx, formData.entries || []);
                break;
            default:
                console.warn(`No chart rendering logic for form type: ${formType}`);
        }
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

        console.log(`Populating form ${formType} with data:`, data);

        const tbody = formElement.querySelector('.form-table tbody');
        if (!tbody) {
            console.error(`populateForm: tbody not found for form ${formType}`);
            return;
        }

        // Clear previous rows, but preserve static rows (e.g., totals, NB)
        const rowsToPreserve = Array.from(tbody.children).filter(row =>
            row.classList.contains('total-row') ||
            row.classList.contains('rata-rata-row') ||
            row.classList.contains('nb-row')
        );
        tbody.innerHTML = ''; // Clear all existing rows
        rowsToPreserve.forEach(row => tbody.appendChild(row)); // Re-append preserved rows

        // --- IMPORTANT: Destroy previous chart instance before rendering new one ---
        const chartCanvas = formElement.querySelector('canvas');
        if (chartCanvas) {
            let chartInstance;
            switch (formType) {
                case 'hand-hygiene': chartInstance = handHygieneChartInstance; break;
                case 'apd': chartInstance = apdChartInstance; break;
                case 'identifikasi': chartInstance = identifikasiChartInstance; break;
                case 'wtri': chartInstance = wtriChartInstance; break;
                case 'kritis-lab': chartInstance = kritisLabChartInstance; break;
                case 'fornas': chartInstance = fornasChartInstance; break;
                case 'visite': chartInstance = visiteChartInstance; break;
                case 'jatuh': chartInstance = jatuhChartInstance; break;
                case 'cp': chartInstance = cpChartInstance; break;
                case 'kepuasan': chartInstance = kepuasanChartInstance; break;
                case 'krk': chartInstance = krkChartInstance; break;
                case 'poe': chartInstance = poeChartInstance; break;
                case 'sc': chartInstance = scChartInstance; break;
            }
            if (chartInstance) {
                chartInstance.destroy();
                console.log(`Destroyed previous chart for ${formType}`);
            }
        }
        // --- End chart destruction logic ---

        // --- Populate common/top-level fields (NOT part of the 'entries' array) ---
        // ... (existing logic for populating top-level fields like unit_kerja, judul_cp, nb) ...
        if (formType === 'identifikasi') {
            const unitKerjaInput = formElement.querySelector('input[name="identifikasi_unit_kerja"]');
            if (unitKerjaInput) {
                unitKerjaInput.value = data.unit_kerja || '';
            }
            if (data.nb) {
                formElement.querySelector('input[name="identifikasi_nb_verbal_visual"]').checked = data.nb.verbal_visual === true;
                formElement.querySelector('input[name="identifikasi_nb_2_parameter"]').checked = data.nb['2_parameter'] === true;
                formElement.querySelector('input[name="identifikasi_nb_1_parameter"]').checked = data.nb['1_parameter'] === true;
                formElement.querySelector('input[name="identifikasi_nb_tidak_dilakukan"]').checked = data.nb.tidak_dilakukan === true;
            }
        } else if (formType === 'wtri') {
            const unitKerjaInput = formElement.querySelector('input[name="wtrj_unit_kerja"]');
            if (unitKerjaInput) {
                unitKerjaInput.value = data.unit || '';
            }
        } else if (formType === 'cp') {
            formElement.querySelector('input[name="cp_ruangan"]').value = data.ruangan || '';
            formElement.querySelector('input[name="cp_judul_cp"]').value = data.judul_cp || '';
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
            formElement.querySelector('input[name="cp_rata_rata_kepatuhan"]').value = data.rata_rata_kepatuhan || '0%';
        }

        // --- Populate dynamic entries (always display all entries from the 'entries' array) ---
        const entriesToPopulate = data.entries || [];

        if (entriesToPopulate.length > 0) {
            entriesToPopulate.sort((a, b) => {
                let dateA, dateB;
                if (formType === 'hand-hygiene' && a.tgl) {
                    dateA = moment(a.tgl);
                    dateB = moment(b.tgl);
                } else if (a.tgl) {
                    dateA = moment(a.tgl);
                    dateB = moment(b.tgl);
                } else if (a.tanggal) {
                    dateA = moment(a.tanggal);
                    dateB = moment(b.tanggal);
                } else if (a.tgl_registrasi) {
                    dateA = moment(a.tgl_registrasi);
                    dateB = moment(b.tgl_registrasi);
                }

                if (dateA && dateB && dateA.isValid() && dateB.isValid()) {
                    return dateA.diff(dateB);
                }
                return 0;
            });

            entriesToPopulate.forEach((entry, index) => {
                const newIndex = index + 1;
                switch (formType) {
                    case 'hand-hygiene': addHandHygieneRow(tbody, newIndex, entry); break;
                    case 'apd': addApdRow(tbody, newIndex, entry); break;
                    case 'identifikasi': addIdentifikasiRow(tbody, newIndex, entry); break;
                    case 'wtri': addWtriRow(tbody, newIndex, entry); break;
                    case 'kritis-lab': addKritisLabRow(tbody, newIndex, entry); break;
                    case 'fornas': addFornasRow(tbody, newIndex, entry); break;
                    case 'visite': addVisiteRow(tbody, newIndex, entry); break;
                    case 'jatuh': addJatuhRow(tbody, newIndex, entry); break;
                    case 'cp': addCpRow(tbody, newIndex, entry); break;
                    case 'kepuasan': addKepuasanRow(tbody, newIndex, entry); break;
                    case 'krk': addKrkRow(tbody, newIndex, entry); break;
                    case 'poe': addPoeRow(tbody, newIndex, entry); break;
                    case 'sc': addScRow(tbody, newIndex, entry); break;
                }
            });
        } else {
            if (['identifikasi', 'fornas', 'jatuh', 'cp'].includes(formType)) {
                for (let i = 1; i <= 2; i++) {
                    switch (formType) {
                        case 'identifikasi': addIdentifikasiRow(tbody, i); break;
                        case 'fornas': addFornasRow(tbody, i); break;
                        case 'jatuh': addJatuhRow(tbody, i); break;
                        case 'cp': addCpRow(tbody, i); break;
                    }
                }
            } else {
                switch (formType) {
                    case 'hand-hygiene': addHandHygieneRow(tbody, 1); break;
                    case 'apd': addApdRow(tbody, 1); break;
                    case 'wtri': addWtriRow(tbody, 1); break;
                    case 'kritis-lab': addKritisLabRow(tbody, 1); break;
                    case 'visite': addVisiteRow(tbody, 1); break;
                    case 'kepuasan': addKepuasanRow(tbody, 1); break;
                    case 'krk': addKrkRow(tbody, 1); break;
                    case 'poe': addPoeRow(tbody, 1); break;
                    case 'sc': addScRow(tbody, 1); break;
                }
            }
        }

        if (formType === 'identifikasi') {
            renumberTableRows(tbody, ['nb-row']);
        } else if (['fornas', 'jatuh', 'krk'].includes(formType)) {
            renumberTableRows(tbody, ['total-row']);
        } else if (formType === 'cp') {
            renumberTableRows(tbody, ['total-row', 'rata-rata-row']);
        } else {
            renumberTableRows(tbody, []);
        }

        if (formType === 'cp') {
            updateCpTotals(formElement);
        } else if (formType === 'jatuh') {
            updateJatuhTotals(formElement);
        } else if (formType === 'hand-hygiene') {
            updateHandHygieneTotals(formElement);
        }

        // --- NEW: Call chart rendering function after data population ---
        renderChartForForm(formType, data);

        updateFormCardComplianceBar(formElement, formType, data);
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

    // Helper to get input value within a given row (if row element provided)
    // or directly from the formElement (if row is null for top-level inputs).
    // Always returns a trimmed string, never null or undefined.
    const getInputValue = (row, name) => {
        const input = row ? row.querySelector(`[name="${name}"]`) : formElement.querySelector(`[name="${name}"]`);
        return input ? input.value.trim() : '';
    };

    // Helper to get checked status within a given row or directly from formElement.
    // Always returns a boolean, false if input is null/undefined or unchecked.
    const getCheckedValue = (row, name) => {
        const input = row ? row.querySelector(`[name="${name}"]`) : formElement.querySelector(`[name="${name}"]`);
        return input ? input.checked : false;
    };

    // Helper to get parsed integer, defaulting to 0 if not a valid number.
    const getParsedInt = (row, name) => parseInt(getInputValue(row, name)) || 0;

    // Remove direct 'bulan' field for forms now using 'tgl' or similar
    // The top-level 'bulan' from the API response is now typically only for history formatting.
    // If a form still uses a top-level month input (e.g., if you re-add it for specific forms),
    // this block would be relevant, but for a simplified "no month filtering" model, it's less critical here.
    // The `populateForm` now handles `data.bulan` for historical context, not input.

    switch (formType) {
        case 'hand-hygiene':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row):not(.rata-rata-row):not(.nb-row)').forEach(row => {
                const entry = {
                    // CHANGED: Use 'tgl' field and ensure it's a valid date string or today's date.
                    tgl: getInputValue(row, `tgl`) || moment().format('YYYY-MM-DD'),
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
                // Only push entry if it's not entirely empty (has a date or a session count)
                if (entry.tgl || entry.sesi > 0 || entry.total_kesempatan > 0) {
                    formData.entries.push(entry);
                }
            });
            break;

        case 'apd':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row):not(.rata-rata-row):not(.nb-row)').forEach(row => {
                const entry = {
                    tgl: getInputValue(row, `tgl`) || moment().format('YYYY-MM-DD'), // Default to today if empty
                    profesi: getInputValue(row, `profesi`) || 'N/A', // Default to N/A
                    ruang: getInputValue(row, `ruang`) || 'N/A',
                    pelayanan: getInputValue(row, `pelayanan`) || 'N/A',
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
                    kepatuhan: getInputValue(row, `kepatuhan`) || 'Tidak', // Default to 'Tidak'
                    ket: getInputValue(row, `ket`) || '',
                };
                if (entry.tgl || entry.profesi) { // Only push if key fields are present
                    formData.entries.push(entry);
                }
            });
            break;

        case 'identifikasi':
            formData.unit_kerja = getInputValue(null, 'identifikasi_unit_kerja') || 'Unknown Unit'; // Stronger default for top-level
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.nb-row)').forEach((row) => {
                const entry = {
                    tgl: getInputValue(row, `tgl`) || moment().format('YYYY-MM-DD'), // Default to today
                    staf: getInputValue(row, `staf`) || 'Unknown Staff', // Default value
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
                if (entry.tgl || entry.staf) { // Only push if key fields are present
                    formData.entries.push(entry);
                }
            });
            formData.nb = { // Ensure these are always booleans
                verbal_visual: getCheckedValue(null, 'identifikasi_nb_verbal_visual'),
                '2_parameter': getCheckedValue(null, 'identifikasi_nb_2_parameter'),
                '1_parameter': getCheckedValue(null, 'identifikasi_nb_1_parameter'),
                tidak_dilakukan: getCheckedValue(null, 'identifikasi_nb_tidak_dilakukan'),
            };
            break;

        case 'wtri':
            formData.unit = getInputValue(null, 'wtrj_unit_kerja') || 'Unknown Unit'; // Default for top-level
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row):not(.rata-rata-row):not(.nb-row)').forEach(row => {
                const entry = {
                    tgl: getInputValue(row, `tgl`) || moment().format('YYYY-MM-DD'), // Default to today
                    no_rm: getInputValue(row, `no_rm`) || 'N/A', // Default
                    nama_pasien: getInputValue(row, `nama_pasien`) || 'Unknown Patient', // Default
                    jam_reg_pendaftaran: getInputValue(row, `jam_reg_pendaftaran`) || '00:00', // Default
                    jam_reg_poli: getInputValue(row, `jam_reg_poli`) || '00:00', // Default
                    jam_dilayani_dokter: getInputValue(row, `jam_dilayani_dokter`) || '00:00', // Default
                    respon_time_ca: getParsedInt(row, `respon_time_ca`),
                    pelayanan_percent_ca: getParsedInt(row, `pelayanan_percent_ca`),
                    respon_time_cb: getParsedInt(row, `respon_time_cb`),
                    pelayanan_percent_cb: getParsedInt(row, `pelayanan_percent_cb`),
                };
                if (entry.tgl || entry.no_rm) { // Only push if key fields are present
                    formData.entries.push(entry);
                }
            });
            break;

        case 'kritis-lab':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row):not(.rata-rata-row):not(.nb-row)').forEach(row => {
                const entry = {
                    tgl: getInputValue(row, `tgl`) || moment().format('YYYY-MM-DD'), // Default to today
                    no_rm: getInputValue(row, `no_rm`) || 'N/A',
                    nama_pasien: getInputValue(row, `nama_pasien`) || 'Unknown Patient',
                    critical_value: getInputValue(row, `critical_value`) || 'N/A',
                    waktu_hasil_keluar: getInputValue(row, `waktu_hasil_keluar`) || '00:00', // Default
                    waktu_dilaporkan: getInputValue(row, `waktu_dilaporkan`) || '00:00', // Default
                    nama_penerima: getInputValue(row, `nama_penerima`) || 'N/A',
                    respon_time: getParsedInt(row, `respon_time`),
                    pelaporan_status: getInputValue(row, `pelaporan_status`) || 'N/A',
                };
                if (entry.tgl || entry.no_rm) {
                    formData.entries.push(entry);
                }
            });
            break;

        case 'fornas':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row)').forEach((row) => {
                const entry = {
                    unit_kerja: getInputValue(row, `unit_kerja`) || 'N/A',
                    nama_pasien: getInputValue(row, `nama_pasien`) || 'N/A',
                    no_rm: getInputValue(row, `no_rm`) || 'N/A',
                    jumlah_resep: getParsedInt(row, `jumlah_resep`),
                    formularium_nasional: getCheckedValue(row, `formularium_nasional`),
                    non_formularium: getCheckedValue(row, `non_formularium`),
                };
                if (entry.unit_kerja || entry.nama_pasien) {
                    formData.entries.push(entry);
                }
            });
            break;

        case 'visite':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row):not(.rata-rata-row):not(.nb-row)').forEach(row => {
                const entry = {
                    tgl_registrasi: getInputValue(row, `tgl_registrasi`) || moment().format('YYYY-MM-DD'), // Default to today
                    nama_pasien: getInputValue(row, `nama_pasien`) || 'Unknown Patient', // Default
                    no_rm: getInputValue(row, `no_rm`) || 'N/A', // Default
                    ruangan: getInputValue(row, `ruangan`) || 'Unknown Room', // Default
                    jml_hari_efektif: getParsedInt(row, `jml_hari_efektif`),
                    jml_hari_rawat: getParsedInt(row, `jml_hari_rawat`),
                    dpjp_utama: getInputValue(row, `dpjp_utama`) || 'Unknown DPJP', // Default
                    smf: getInputValue(row, `smf`) || 'N/A', // Default
                    tgl_visite: getInputValue(row, `tgl_visite`) || moment().format('YYYY-MM-DD'), // Default
                    jam: getInputValue(row, `jam`) || '00:00 - 01:00', // Default
                    val_i: getParsedInt(row, `val_i`),
                    val_ii: getParsedInt(row, `val_ii`),
                    val_iii: getParsedInt(row, `val_iii`),
                    val_iv: getParsedInt(row, `val_iv`),
                    total: getParsedInt(row, `total`),
                    jam_visite_akhir: getInputValue(row, `jam_visite_akhir`) || '00:00', // Default
                };
                if (entry.tgl_registrasi || entry.nama_pasien) { // Only push if key fields are present
                    formData.entries.push(entry);
                }
            });
            break;

        case 'jatuh':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row)').forEach((row) => {
                const entry = {
                    nama_pasien: getInputValue(row, `nama_pasien`) || 'Unknown Patient', // Default
                    no_rm: getInputValue(row, `no_rm`) || 'N/A', // Default
                    assessment_awal: getInputValue(row, `assessment_awal`) || 'Tidak', // Default
                    assessment_ulang: getInputValue(row, `assessment_ulang`) || 'Tidak', // Default
                    intervensi: getInputValue(row, `intervensi`) || 'Tidak', // Default
                    ketiga_upaya_ya: getCheckedValue(row, `ketiga_upaya_ya`),
                    ketiga_upaya_tidak: getCheckedValue(row, `ketiga_upaya_tidak`),
                };
                if (entry.nama_pasien || entry.no_rm) { // Only push if key fields are present
                    formData.entries.push(entry);
                }
            });
            formData.totals = { // Ensure totals are always numbers
                assessment_awal: parseInt(formElement.querySelector('input[name="jatuh_total_assessment_awal"]')?.value) || 0,
                assessment_ulang: parseInt(formElement.querySelector('input[name="jatuh_total_assessment_ulang"]')?.value) || 0,
                intervensi: parseInt(formElement.querySelector('input[name="jatuh_total_intervensi"]')?.value) || 0,
                ketiga_upaya_ya: parseInt(formElement.querySelector('input[name="jatuh_total_ketiga_upaya_ya"]')?.value) || 0,
                ketiga_upaya_tidak: parseInt(formElement.querySelector('input[name="jatuh_total_ketiga_upaya_tidak"]')?.value) || 0,
            };
            break;

        case 'cp':
            // Provide stronger defaults for top-level fields
            formData.ruangan = getInputValue(null, 'cp_ruangan') || 'Unknown Room';
            formData.judul_cp = getInputValue(null, 'cp_judul_cp') || 'Default Clinical Pathway';
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row):not(.rata-rata-row)').forEach((row) => {
                const entry = {
                    no_mr: getInputValue(row, `no_mr`) || 'N/A', // Default
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
                    varian: getInputValue(row, `varian`) || '', // Default
                    ket: getInputValue(row, `ket`) || '', // Default
                };
                if (entry.no_mr) { // Only push if key field is present
                    formData.entries.push(entry);
                }
            });
            formData.totals = { // Ensure totals are always numbers
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
                // Corrected name for 'obat_c' based on typical naming. Ensure this matches your Blade's `name` too.
                obat_c: parseInt(formElement.querySelector('input[name="cp_total_obat_c"]')?.value) || 0,
                grand_total: parseInt(formElement.querySelector('input[name="cp_grand_total"]')?.value) || 0,
            };
            formData.rata_rata_kepatuhan = getInputValue(null, 'cp_rata_rata_kepatuhan');
            break;

        case 'kepuasan':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row):not(.rata-rata-row):not(.nb-row)').forEach(row => {
                const entry = {
                    tanggal: getInputValue(row, `tanggal`) || moment().format('YYYY-MM-DD'), // Default to today
                    unit_kerja: getInputValue(row, `unit_kerja`) || 'N/A',
                    nilai_ikm: getInputValue(row, `nilai_ikm`) || 'N/A',
                    jenis_pelayanan: getInputValue(row, `jenis_pelayanan`) || 'N/A',
                    nilai_kepuasan: getInputValue(row, `nilai_kepuasan`) || 'N/A',
                    komentar: getInputValue(row, `komentar`) || '',
                };
                if (entry.tanggal || entry.unit_kerja) {
                    formData.entries.push(entry);
                }
            });
            break;

        case 'krk':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row)').forEach((row) => {
                const entry = {
                    tgl: getInputValue(row, `tgl`) || moment().format('YYYY-MM-DD'), // Default to today
                    isi_komplain: getInputValue(row, `isi_komplain`) || 'N/A',
                    kategori_komplain: getInputValue(row, `kategori_komplain`) || 'N/A',
                    lisan: getCheckedValue(row, `lisan`),
                    tulisan: getCheckedValue(row, `tulisan`),
                    media_masa: getCheckedValue(row, `media_masa`),
                    grading_merah: getCheckedValue(row, `grading_merah`),
                    grading_kuning: getCheckedValue(row, `grading_kuning`),
                    grading_hijau: getCheckedValue(row, `grading_hijau`),
                    waktu_tanggap: getParsedInt(row, `waktu_tanggap`),
                    penyelesaian_ya: getCheckedValue(row, `penyelesaian_ya`),
                    penyelesaian_tidak: getCheckedValue(row, `penyelesaian_tidak`),
                    ket: getInputValue(row, `ket`) || '',
                };
                if (entry.tgl || entry.isi_komplain) {
                    formData.entries.push(entry);
                }
            });
            break;

        case 'poe':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row):not(.rata-rata-row):not(.nb-row)').forEach(row => {
                const entry = {
                    tgl: getInputValue(row, `tgl`) || moment().format('YYYY-MM-DD'), // Default to today
                    nama_pasien: getInputValue(row, `nama_pasien`) || 'N/A',
                    no_rm: getInputValue(row, `no_rm`) || 'N/A',
                    ruangan: getInputValue(row, `ruangan`) || 'N/A',
                    diagnosa: getInputValue(row, `diagnosa`) || 'N/A',
                    tindakan_bedah: getInputValue(row, `tindakan_bedah`) || 'N/A',
                    dpjp_bedah: getInputValue(row, `dpjp_bedah`) || 'N/A',
                    jam_rencana_operasi: getInputValue(row, `jam_rencana_operasi`) || '00:00', // Default
                    jam_insisi: getInputValue(row, `jam_insisi`) || '00:00', // Default
                    penundaan_gt_1hr: getCheckedValue(row, `penundaan_gt_1hr`),
                    penundaan_lt_1hr: getCheckedValue(row, `penundaan_lt_1hr`),
                    keterangan: getInputValue(row, `keterangan`) || '',
                };
                if (entry.tgl || entry.nama_pasien) {
                    formData.entries.push(entry);
                }
            });
            break;

        case 'sc':
            formData.entries = [];
            formElement.querySelectorAll('.form-table tbody tr:not(.total-row):not(.rata-rata-row):not(.nb-row)').forEach(row => {
                const entry = {
                    nama_pasien: getInputValue(row, `nama_pasien`) || 'N/A',
                    no_rm: getInputValue(row, `no_rm`) || 'N/A',
                    diagnosa_kategori: getInputValue(row, `diagnosa_kategori`) || 'N/A',
                    jam_tiba_igd: getInputValue(row, `jam_tiba_igd`) || '00:00',
                    jam_diputuskan_operasi: getInputValue(row, `jam_diputuskan_operasi`) || '00:00',
                    jam_mulai_insisi: getInputValue(row, `jam_mulai_insisi`) || '00:00',
                    waktu_tanggap: getParsedInt(row, `waktu_tanggap`),
                    gt_30_menit: getInputValue(row, `gt_30_menit`) || 'Tidak',
                    keterangan: getInputValue(row, `keterangan`) || '',
                };
                if (entry.nama_pasien || entry.no_rm) {
                    formData.entries.push(entry);
                }
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
                        formData[input.name] = input.value.trim(); // Ensure no leading/trailing spaces
                    }
                }
            });
            break;
    }
    return formData;
}
    function _downloadMutuReport(type) {
        if (type === 'pdf') {
            handleMutuPdfExport();
        } else {
            handleMutuExcelExport();
        }
    }

    async function handleMutuExcelExport() {
        showLoading();
        try {
            const response = await fetch('/api/v1/reports/export/quality-indicators/excel', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${window.authToken}`,
                    'Accept': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                }
            });

            if (!response.ok) throw new Error(`Server error: ${response.status}`);

            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `Laporan_Indikator_Mutu_${new Date().toISOString().slice(0, 10)}.xlsx`;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);

        } catch (error) {
            console.error('Error exporting Indikator Mutu Excel:', error);
            alert('Gagal membuat laporan Excel.');
        } finally {
            hideLoading();
        }
    }

    async function handleMutuPdfExport() {
        showLoading();
        const chartImages = {};

        // 1. Create a temporary, invisible container and add it to the page
        const tempContainer = document.createElement('div');
        tempContainer.style.position = 'absolute';
        tempContainer.style.left = '-9999px'; // Move it completely off-screen
        tempContainer.style.top = '-9999px';
        tempContainer.style.width = '600px'; // Give it a defined size
        document.body.appendChild(tempContainer);

        try {
            for (const indicator of indicators) {
                const formType = indicator.form_type;
                const formData = formCurrentData[formType]?.data || { entries: [] };

                // 2. Create canvas and APPEND IT to our invisible container
                const tempCanvas = document.createElement('canvas');
                tempCanvas.width = 400; 
                tempCanvas.height = 200;
                tempContainer.appendChild(tempCanvas);
                const ctx = tempCanvas.getContext('2d');

                const getChartRenderer = (type) => {
                    const renderers = {
                        'hand-hygiene': renderHandHygieneChart, 'apd': renderApdChart,
                        'identifikasi': renderIdentifikasiChart, 'wtri': renderWtriChart,
                        'kritis-lab': renderKritisLabChart, 'fornas': renderFornasChart,
                        'visite': renderVisiteChart, 'jatuh': renderJatuhChart,
                        'cp': renderCpChart, 'kepuasan': renderKepuasanChart,
                        'krk': renderKrkChart, 'poe': renderPoeChart, 'sc': renderScChart
                    };
                    return renderers[type];
                };

                const renderFunction = getChartRenderer(formType);
                if (renderFunction) {
                    const { config } = renderFunction(null, formData.entries || [], formData);
                    
                    const exportConfig = {
                        ...config,
                        options: { ...config.options, animation: { duration: 0 } }
                    };
                    
                    const tempChart = new Chart(ctx, exportConfig);
                    
                    // 3. Capture the image (it will now be valid)
                    chartImages[formType] = tempChart.toBase64Image();
                    tempChart.destroy();
                }
                // 4. Clear the container for the next chart
                tempContainer.innerHTML = '';
            }

            // Send the generated images to the backend
            const response = await fetch('/api/v1/reports/export/quality-indicators/pdf', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/pdf',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Authorization': `Bearer ${window.authToken}`
                },
                body: JSON.stringify({ chart_images: chartImages })
            });

            if (!response.ok) throw new Error(`Server error: ${response.status}`);

            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `Laporan_Indikator_Mutu.pdf`;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);

        } catch (error) {
            console.error('Error exporting Indikator Mutu PDF:', error);
            alert('Gagal membuat laporan PDF Indikator Mutu.');
        } finally {
            document.body.removeChild(tempContainer);
            hideLoading();
        }
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
                <input type="date" name="tgl" value="${entry.tgl || moment().format('YYYY-MM-DD')}" required />
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

        // Attach event listeners for dynamic calculation (unchanged)
        newRow.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('input', function() {
                updateHandHygieneTotals(input.closest('.form-card'));
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
    const insertIndex = getInsertionIndex(tbody, ['total-row']);
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
        <td><input type="checkbox" name="ketiga_upaya_ya" /></td>
        <td><input type="checkbox" name="ketiga_upaya_tidak" /></td>
    `;
    const selects = newRow.querySelectorAll('select');
    const yaCheckbox = newRow.querySelector(`input[name="ketiga_upaya_ya"]`);
    const tidakCheckbox = newRow.querySelector(`input[name="ketiga_upaya_tidak"]`);

    // Helper to ensure proper checkbox state (radio-like behavior)
    const setJatuhCheckboxes = (isYaChecked, isTidakChecked) => {
        yaCheckbox.checked = isYaChecked;
        tidakCheckbox.checked = isTidakChecked;
    };

    // Function to derive checkbox state from the three selects
    const deriveCheckboxStateFromSelects = () => {
        const allAreYaFromSelects = Array.from(selects).every(s => s.value === 'Ya');
        setJatuhCheckboxes(allAreYaFromSelects, !allAreYaFromSelects);
    };

    // --- Initial State Population ---
    // First, try to set the state based directly on the 'entry' data from the backend.
    if (entry.ketiga_upaya_ya === true) {
        setJatuhCheckboxes(true, false);
    } else if (entry.ketiga_upaya_tidak === true) {
        setJatuhCheckboxes(false, true);
    } else {
        // If entry data doesn't explicitly define, then derive from selects (e.g., for new rows or incomplete old data)
        deriveCheckboxStateFromSelects();
    }

    // --- Event Listeners for Dynamic Updates ---

    // Listen to changes in the three main select fields
    selects.forEach(select => select.addEventListener('change', () => {
        deriveCheckboxStateFromSelects(); // Always re-evaluate based on selects
        updateJatuhTotals(select.closest('.form-card'));
    }));

    // Listen to direct changes on the 'Ya' checkbox (manual override)
    yaCheckbox.addEventListener('change', function() {
        if (this.checked) {
            tidakCheckbox.checked = false; // If 'Ya' is checked, uncheck 'Tidak'
        } else {
            // If 'Ya' is unchecked, and 'Tidak' is also unchecked,
            // it implies that the status is "Tidak" based on the three selects.
            if (!tidakCheckbox.checked) {
                tidakCheckbox.checked = true;
            }
        }
        updateJatuhTotals(newRow.closest('.form-card'));
    });

    // Listen to direct changes on the 'Tidak' checkbox (manual override)
    tidakCheckbox.addEventListener('change', function() {
        if (this.checked) {
            yaCheckbox.checked = false; // If 'Tidak' is checked, uncheck 'Ya'
        } else {
            // If 'Tidak' is unchecked, and 'Ya' is also unchecked,
            // re-evaluate based on the three selects.
            if (!yaCheckbox.checked) {
                deriveCheckboxStateFromSelects(); // Try to set based on selects
            }
        }
        updateJatuhTotals(newRow.closest('.form-card'));
    });

    // Initial totals update (after all initial state has been set)
    updateJatuhTotals(newRow.closest('.form-card'));
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
    async function initializeData(forceRefresh = false) {
        const cacheKey = 'prefetched_indikator_mutu_all';
        const cachedAllIndicatorsData = sessionStorage.getItem(cacheKey);

        if (cachedAllIndicatorsData && !forceRefresh) {
            console.log('⚡️ Memuat semua data Indikator Mutu dari cache.');
            try {
                const allData = JSON.parse(cachedAllIndicatorsData);
                // Populate local variables from the single cached object
                for (const indicator of indicators) {
                    const indicatorData = allData[indicator.id];
                    if (indicatorData) {
                        formCurrentData[indicator.id] = {
                            data: indicatorData.data || { entries: [] },
                            history: indicatorData.history || []
                        };
                        formHistoryData[indicator.id] = indicatorData.history || [];
                    } else {
                        formCurrentData[indicator.id] = { data: { entries: [] }, history: [] };
                        formHistoryData[indicator.id] = [];
                    }
                }
                return; // Exit function, we are done
            } catch (e) {
                console.error("Gagal mem-parsing data Indikator Mutu dari cache, mengambil dari API.", e);
            }
        }
        
        // Fallback: If no cache or forceRefresh is true, fetch all data from a single new endpoint.
        if (forceRefresh) console.log('🔄 Memaksa pembaruan data Indikator Mutu...');
        else console.log('Cache tidak ditemukan. Mengambil semua data Indikator Mutu dari API...');
        
        try {
            const response = await authenticatedFetch(`${API_BASE_URL}/all-indicators/all`);
            if (!response.ok) {
                throw new Error(`Failed to fetch all indicators: ${response.statusText}`);
            }
            const allData = await response.json();
            
            // **NEW**: Cache the entire object of all indicators
            sessionStorage.setItem(cacheKey, JSON.stringify(allData));
            console.log('✅ Semua data Indikator Mutu berhasil disimpan di cache.');
            
            // Now, populate the local variables from the fetched data
            for (const indicator of indicators) {
                const indicatorData = allData[indicator.id];
                if (indicatorData) {
                    formCurrentData[indicator.id] = {
                        data: indicatorData.data || { entries: [] },
                        history: indicatorData.history || []
                    };
                    formHistoryData[indicator.id] = indicatorData.history || [];
                }
            }
        } catch (error) {
            console.error('Error initializing all indicator data:', error);
            // On failure, initialize all forms as empty
            for (const indicator of indicators) {
                formCurrentData[indicator.id] = { data: { entries: [] }, history: [] };
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
     * Saves form data to the backend API.
     * @param {string} formType - The type of the form to save.
     * @param {string} [weekStartDate=null] - Optional specific week start date for historical saves.
     * @param {object} [existingData=null] - Optional data to send if it's an auto-submission of existing (potentially incomplete) data.
     */
    // REPLACE your old saveFormData() function with this new one
    async function saveFormData(formType, weekStartDate = null, existingData = null) {
        showLoading();
        try {
            let dataToSave = existingData || getFormData(formType);
            const requestBody = { data: dataToSave };
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
            
            // **MODIFIED**: Instead of updating just one form's data,
            // we will now call initializeData(true) to refresh EVERYTHING.
            // This ensures all compliance scores, stats, and histories are up to date.
            await initializeData(true);

            updateStatisticsDisplay(); // Update dashboard stats after the fresh data is loaded
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
    }
