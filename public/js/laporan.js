// resources/js/laporan.js

// Wrap the entire script in an IIFE to prevent global variable conflicts.
(function() {

    // --- Global Variables (scoped to this IIFE) ---
    let headerStats = {};
    let dailyLogs = []; // This will now hold combined PrivateSchedule and SpecialCase data
    let staffSchedulesData = {}; 
    let logisticsData = {};
    let ppiData = {}; // NOW CONTAINS ALL PPI DATA
    let staffPerformanceData = [];
    let tnaRecords = [];
    let qualityIndicators = {};

    let currentAuthToken = null;
    let logisticItemNames = { 
        'Alat Kesehatan': [],
        'Barang Habis Pakai': []
    };

    // Chart instances for PPI tab - declare globally within this IIFE
    let ppiInsertionComplianceChartInstance = null;
    let ppiMaintenanceComplianceChartInstance = null;
    let ppiInfectionTrendChartInstance = null;
    let ppiNeedlestickTrendChartInstance = null;
    let ppiInfectionLocationChartInstance = null;
    let ppiMicroorganismChartInstance = null;
    let ppiNeedlestickByDepartmentChartInstance = null;
    let ppiNeedlestickByPositionChartInstance = null;


    // --- Helper Functions ---
    function formatDate(dateString) {
        if (!dateString) return '-';
        const options = { year: 'numeric', month: 'short', day: 'numeric' };
        try {
             return new Date(dateString).toLocaleDateString('id-ID', options);
        } catch (e) {
             console.warn('Invalid date string for formatDate:', dateString);
             return '-';
        }
    }

    function formatDateTime(dateTimeString) {
        if (!dateTimeString) return '-';
        const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
        try {
            return new Date(dateTimeString).toLocaleDateString('id-ID', options);
        } catch (e) {
            console.warn('Invalid datetime string for formatDateTime:', dateTimeString);
            return '-';
        }
    }

    function capitalizeFirstLetter(string) {
        if (!string) return '';
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function getRatingDescription(rating) {
        if (rating >= 86) return 'Sangat Baik';
        if (rating >= 76) return 'Baik';
        if (rating >= 61) return 'Cukup';
        if (rating >= 31) return 'Kurang';
        if (rating >= 0) return 'Sangat Kurang';
        return '-';
    }

    function getRatingColor(rating) {
        if (rating >= 86) return '#10b981';
        if (rating >= 76) return '#3b82f6';
        if (rating >= 61) return '#f59e0b';
        if (rating >= 31) return '#ef4444';
        return '#6b7280';
    }

    function getRatingTextColor(rating) {
        if (rating >= 86) return 'text-green-700';
        if (rating >= 76) return 'text-blue-600';
        if (rating >= 61) return 'text-yellow-600';
        if (rating >= 31) return 'text-red-600';
        return 'text-gray-600';
    }

    function getStarRating(score) {
        const maxStars = 5;
        const filledStars = Math.round((score / 100) * maxStars);
        let starsHtml = '';
        for (let i = 0; i < filledStars; i++) {
            starsHtml += '<i class="fas fa-star text-yellow-500"></i>';
        }
        for (let i = filledStars; i < maxStars; i++) {
            starsHtml += '<i class="far fa-star text-gray-300"></i>';
        }
        return starsHtml;
    }

     // Helper for performance status badge colors
    function getPerformanceBadgeColor(status) {
        switch (status) {
            case 'Sangat Baik': return '#10b981'; // Green
            case 'Baik': return '#3b82f6';    // Blue
            case 'Cukup': return '#f59e0b';    // Yellow/Orange
            case 'Kurang': return '#ef4444';  // Red
            case 'Sangat Kurang': return '#6b7280'; // Grey
            default: return '#6b7280'; // Grey fallback
        }
    }
    
    function toggleSection(sectionId) {
        const section = document.getElementById(sectionId);
        const arrow = document.getElementById('arrow-' + sectionId);

        if (section && arrow) {
            if (section.classList.contains('hidden')) {
                section.classList.remove('hidden');
                arrow.classList.add('rotate-180');
            } else {
                section.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }
        }
    }

    // --- End Helper Functions ---


    // --- Initialization on DOM Content Loaded ---
    document.addEventListener('DOMContentLoaded', async function() {
        currentAuthToken = window.authToken;

        if (!currentAuthToken) {
            console.error('Authentication token is missing. Please ensure you are logged in.');
            return;
        } else {
            console.log('Auth token successfully loaded (partial):', currentAuthToken.substring(0, 10) + '...');
        }

        const initialTabButton = document.querySelector('.tab-btn[data-tab="catatan"]');
        if (initialTabButton) {
            initialTabButton.classList.add('border-blue-600', 'text-blue-600');
            initialTabButton.classList.remove('text-gray-500', 'hover:text-gray-700');
            initialTabButton.style.borderBottomWidth = '2px';
        }
        document.getElementById('catatan').classList.add('active');

        await loadDataForTab('catatan');
        setupTabNavigation();
    });

    // NEW: Function to generate and append export buttons
    function renderExportButtons(targetElementId, reportNameSlug) {
        const targetElement = document.getElementById(targetElementId);
        if (!targetElement) {
            console.error(`Target element for export buttons not found: ${targetElementId}`);
            return;
        }

        const buttonsHtml = `
            <div class="flex flex-wrap gap-2 mt-4 mb-6 justify-end">
                <button onclick="downloadReport('excel', '${reportNameSlug}')"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-file-excel mr-2"></i> Export Excel
                </button>
                <button onclick="downloadReport('pdf', '${reportNameSlug}')"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                </button>
            </div>
        `;
        // Prepend buttons to ensure they appear at the top of the content area
        targetElement.insertAdjacentHTML('afterbegin', buttonsHtml);
    }

    function showLoading() {
        const overlay = document.createElement('div');
        overlay.id = 'global-loading-overlay';
        overlay.className = 'fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-[9999]';
        overlay.innerHTML = `
            <div class="flex flex-col items-center space-y-4">
                <div class="relative w-20 h-20">
                    <div class="absolute inset-0 border-4 border-blue-400 border-t-blue-600 rounded-full animate-spin"></div>
                    <div class="absolute inset-2 border-4 border-white border-t-white rounded-full animate-spin-reverse" style="animation-duration: 1.5s;"></div>
                    <div class="absolute inset-4 border-4 border-purple-400 border-t-purple-600 rounded-full animate-spin" style="animation-duration: 2s;"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-sync-alt text-white text-3xl animate-pulse"></i>
                    </div>
                </div>
                <p class="text-white text-lg font-semibold animate-pulse">Loading Data...</p>
            </div>
        `;
        document.body.appendChild(overlay);
    }

    function hideLoading() {
        const overlay = document.getElementById('global-loading-overlay');
        if (overlay) {
            overlay.remove();
        }
    }


    // --- Utility Functions (for API calls and error handling) ---

    function getAuthHeaders() {
        if (!currentAuthToken) {
            console.error('Attempted to get auth headers but currentAuthToken is null.');
            return {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            };
        }
        return {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${currentAuthToken}`
        };
    }

    function handleUnauthorized(response) {
        if (response.status === 401) {
            console.error('Authentication failed (401 Unauthorized). Redirecting to login.');
            window.location.href = '/login';
            return true;
        }
        return false;
    }


    // --- Data Fetching Functions ---

    async function fetchHeaderStats() {
        try {
            const response = await fetch('/api/v1/reports/header-stats', { headers: getAuthHeaders() });
            if (handleUnauthorized(response)) return;
            if (!response.ok) throw new Error('Failed to fetch header stats');
            headerStats = await response.json();
        } catch (error) {
            console.error('Error fetching header stats:', error);
            headerStats = { active_staff_count: 'N/A', compliance_rate: 'N/A', report_date: 'N/A' };
        }
    }

    async function fetchDailyLogs() {
        try {
            const response = await fetch('/api/v1/reports/daily-logs', { headers: getAuthHeaders() });
            if (handleUnauthorized(response)) return;
            if (!response.ok) {
                const errorBody = await response.json();
                throw new Error(`Failed to fetch daily logs: ${errorBody.message || response.statusText}`);
            }
            dailyLogs = await response.json();
            console.log('Combined Daily Logs & Special Cases for Laporan:', dailyLogs);
        } catch (error) {
            console.error('Error fetching daily logs for Laporan:', error);
            dailyLogs = [];
        }
    }

    async function fetchStaffSchedules() {
        try {
            const response = await fetch('/api/v1/reports/staff-schedules', { headers: getAuthHeaders() });
            if (handleUnauthorized(response)) return;
            if (!response.ok) {
                const errorBody = await response.json();
                throw new Error(`Failed to fetch staff schedules: ${errorBody.message || response.statusText}`);
            }
            staffSchedulesData = await response.json();
            console.log('Processed Staff Schedules for Laporan:', staffSchedulesData);
        } catch (error) {
            console.error('Error fetching staff schedules for Laporan:', error);
            staffSchedulesData = { schedules: [], shift_summary: {}, all_staff_names: [] };
        }
    }

    async function fetchLogisticsSummary() {
        try {
            const response = await fetch('/api/v1/reports/logistics-summary', { headers: getAuthHeaders() });
            if (handleUnauthorized(response)) return;
            if (!response.ok) throw new Error('Failed to fetch logistics summary');
            logisticsData = await response.json();
        } catch (error) {
            console.error('Error fetching logistics summary:', error);
            logisticsData = {
                total_stock_available: 'N/A',
                limited_stock: 'N/A',
                low_stock: 'N/A',
                categorized_items: [],
                categories_overview: [],
            };
        }
    }

    async function fetchPpiData() {
        try {
            // Updated to fetch comprehensive PPI data
            const response = await fetch('/api/v1/reports/ppi-data', { headers: getAuthHeaders() });
            if (handleUnauthorized(response)) return;
            if (!response.ok) {
                const errorBody = await response.json();
                throw new Error(`Failed to fetch PPI data: ${errorBody.message || response.statusText}`);
            }
            ppiData = await response.json();
            console.log('Fetched comprehensive PPI Data:', ppiData); // Debugging
        } catch (error) {
            console.error('Error fetching PPI data:', error);
            // Default empty/N/A values for all expected PPI data points
            ppiData = {
                total_insertions_today: 'N/A',
                total_maintenances_today: 'N/A',
                total_infections_today: 'N/A',
                total_needlestick_cases_today: 'N/A',
                insertion_compliance_rate: 'N/A',
                maintenance_compliance_rate: 'N/A',
                needlestick_rate_30_days: 'N/A',
                infection_trend: [],
                needlestick_trend: [],
                infection_by_location: [],
                infection_by_microorganism: [],
                needlestick_by_department: [],
                needlestick_by_position: [],
                recent_ppi_activities: [],
            };
        }
    }

    async function fetchStaffPerformance() {
        try {
            const response = await fetch('/api/v1/reports/staff-performance', { headers: getAuthHeaders() });
            if (handleUnauthorized(response)) return;
            if (!response.ok) throw new Error('Failed to fetch staff performance');
            staffPerformanceData = await response.json();
        } catch (error) {
            console.error('Error fetching staff performance:', error);
            staffPerformanceData = [];
        }
    }

    async function fetchTnaData() {
        try {
            const response = await fetch('/api/v1/reports/tna-data', { headers: getAuthHeaders() });
            if (handleUnauthorized(response)) return;
            if (!response.ok) throw new Error('Failed to fetch TNA data');
            tnaRecords = await response.json();
        } catch (error) {
            console.error('Error fetching TNA data:', error);
            tnaRecords = [];
        }
    }

    async function fetchQualityIndicators() {
        try {
            const response = await fetch('/api/v1/reports/quality-indicators', { headers: getAuthHeaders() });
            if (handleUnauthorized(response)) return;
            if (!response.ok) throw new Error('Failed to fetch quality indicators');
            qualityIndicators = await response.json();
        } catch (error) {
            console.error('Error fetching quality indicators:', error);
            qualityIndicators = { recent_inspections: [], overall_pass_rate: 'N/A' };
        }
    }

    /**
     * Loads data for a given tab and renders its content.
     * Also updates header stats.
     * @param {string} tabId The ID of the tab to load.
     */
     async function loadDataForTab(tabId) {
        const mainContentArea = document.querySelector(`#${tabId}`);
        if (mainContentArea) {
            mainContentArea.innerHTML = '<div class="text-center py-10 text-gray-500 text-lg"><i class="fas fa-spinner fa-spin mr-2"></i>Loading data...</div>';
        }

        showLoading(); // Show global loading overlay

        try {
            await fetchHeaderStats();
            renderHeaderStats();

            // Destroy existing Chart.js instances if the tab is PPI before rendering new content
            if (tabId === 'ppi') {
                if (ppiInsertionComplianceChartInstance) ppiInsertionComplianceChartInstance.destroy();
                if (ppiMaintenanceComplianceChartInstance) ppiMaintenanceComplianceChartInstance.destroy();
                if (ppiInfectionTrendChartInstance) ppiInfectionTrendChartInstance.destroy();
                if (ppiNeedlestickTrendChartInstance) ppiNeedlestickTrendChartInstance.destroy();
                if (ppiInfectionLocationChartInstance) ppiInfectionLocationChartInstance.destroy();
                if (ppiMicroorganismChartInstance) ppiMicroorganismChartInstance.destroy();
                if (ppiNeedlestickByDepartmentChartInstance) ppiNeedlestickByDepartmentChartInstance.destroy();
                if (ppiNeedlestickByPositionChartInstance) ppiNeedlestickByPositionChartInstance.destroy();
            }

            switch (tabId) {
                case 'catatan':
                    await fetchDailyLogs();
                    renderCatatanHarian();
                    renderExportButtons('catatan', 'daily-logs');
                    break;
                case 'jadwal':
                    await fetchStaffSchedules();
                    renderJadwalDinas();
                    renderExportButtons('jadwal', 'staff-schedules');
                    break;
                case 'logistik':
                    await fetchLogisticsSummary();
                    renderManajemenLogistik();
                    renderExportButtons('logistik', 'logistics');
                    break;
                case 'ppi':
                    await fetchPpiData(); // Fetch all new PPI data
                    renderPpiData();      // Render all new PPI data
                    renderExportButtons('ppi', 'ppi');
                    break;
                case 'kinerja':
                    await fetchStaffPerformance();
                    renderKinerjaStaff();
                    renderExportButtons('kinerja', 'staff-performance');
                    break;
                case 'tna':
                    await fetchTnaData();
                    renderTna();
                    renderExportButtons('tna', 'tna');
                    break;
                case 'mutu':
                    await fetchQualityIndicators();
                    renderIndikatorMutu();
                    renderExportButtons('mutu', 'quality-indicators');
                    break;
                default:
                    console.warn(`Unknown tab ID: ${tabId}. Defaulting to Catatan Harian.`);
                    await fetchDailyLogs();
                    renderCatatanHarian();
                    renderExportButtons('catatan', 'daily-logs');
                    break;
            }
        } catch (error) {
            console.error(`Error loading data for tab ${tabId}:`, error);
            if (mainContentArea) {
                mainContentArea.innerHTML = `<div class="text-center py-10 text-red-500 text-lg"><i class="fas fa-exclamation-circle mr-2"></i>Gagal memuat data untuk tab ini: ${error.message}</div>`;
            }
        } finally {
            hideLoading(); // Hide global loading overlay
        }
    }


    // --- UI/Event Handling Functions ---

    function setupTabNavigation() {
        const tabButtons = document.querySelectorAll('.tab-btn');
        tabButtons.forEach(button => {
            button.addEventListener('click', async function() {
                tabButtons.forEach(btn => {
                    btn.classList.remove('border-blue-600', 'text-blue-600');
                    btn.classList.add('text-gray-500', 'hover:text-gray-700');
                    btn.style.borderBottomWidth = '0px';
                });
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });

                this.classList.add('border-blue-600', 'text-blue-600');
                this.classList.remove('text-gray-500', 'hover:text-gray-700');
                this.style.borderBottomWidth = '2px';

                const tabId = this.dataset.tab;
                document.getElementById(tabId).classList.add('active');

                await loadDataForTab(tabId);
            });
        });
    }

    function renderHeaderStats() {
        document.getElementById('headerDate').textContent = headerStats.report_date || 'N/A';
        document.getElementById('activeStaffCount').textContent = headerStats.active_staff_count || 'N/A';
    }


    // --- Render Functions for Each Tab Content ---

    function renderCatatanHarian() {
        const catatanContent = document.getElementById('catatan');
        let privateSchedulesHtml = '';
        let specialCasesHtml = '';

        const privateSchedules = dailyLogs.filter(log => log.type === 'private_schedule');
        const specialCases = dailyLogs.filter(log => log.type === 'special_case');

        privateSchedulesHtml = `
            <h3 class="text-lg font-semibold text-gray-800 mb-3 mt-4">Catatan Harian Kegiatan</h3>
            <div class="overflow-x-auto mb-8">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal & Jam</th>
                            <th class="px-4 py-3 text-center">Briefing</th>
                            <th class="px-4 py-3 text-center">Rapat</th>
                            <th class="px-4 py-3 text-center">Supervisi</th>
                            <th class="px-4 py-3 text-center">Handover</th>
                            <th class="px-4 py-3 text-left">Tugas Luar</th>
                            <th class="px-4 py-3 text-left">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${privateSchedules.length > 0 ? privateSchedules.map(log => `
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3">${formatDateTime(log.date)}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="${log.briefing_conducted ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'} px-2 py-1 rounded">${log.briefing_conducted ? 'Ya' : 'Tidak'}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="${log.meeting_held ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'} px-2 py-1 rounded">${log.meeting_held ? 'Ya' : 'Tidak'}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="${log.supervision_conducted ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'} px-2 py-1 rounded">${log.supervision_conducted ? 'Ya' : 'Tidak'}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="${log.handover_done ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'} px-2 py-1 rounded">${log.handover_done ? 'Ya' : 'Tidak'}</span>
                                </td>
                                <td class="px-4 py-3 text-gray-700">${log.external_task || '-'}</td>
                                <td class="px-4 py-3 text-gray-700">${log.notes || '-'}</td>
                            </tr>
                        `).join('') : `
                            <tr>
                                <td colspan="7" class="text-center py-4 text-gray-500">Tidak ada catatan kegiatan harian.</td>
                            </tr>
                        `}
                    </tbody>
                </table>
            </div>
        `;

        specialCasesHtml = `
            <h3 class="text-lg font-semibold text-gray-800 mb-3 mt-4">Kasus Perhatian Khusus</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal Kasus</th>
                            <th class="px-4 py-3 text-left">Nama Pasien</th>
                            <th class="px-4 py-3 text-left">Jenis Kasus</th>
                            <th class="px-4 py-3 text-left">Detail</th>
                            <th class="px-4 py-3 text-left">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${specialCases.length > 0 ? specialCases.map(log => {
                            let caseTypeClass = '';
                            switch (log.case_type) {
                                case 'Resiko Tinggi':
                                    caseTypeClass = 'bg-red-100 text-red-800';
                                    break;
                                case 'Kompleks':
                                    caseTypeClass = 'bg-yellow-100 text-yellow-800';
                                    break;
                                case 'Kasus Langka':
                                    caseTypeClass = 'bg-purple-100 text-purple-800';
                                    break;
                                default:
                                    caseTypeClass = 'bg-gray-100 text-gray-800';
                                    break;
                            }
                            return `
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-4 py-3">${formatDateTime(log.date)}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900">${log.patient_name || '-'}</td>
                                    <td class="px-4 py-3">
                                        <span class="${caseTypeClass} px-2 py-1 rounded text-xs">${log.case_type || '-'}</span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">${log.details || '-'}</td>
                                    <td class="px-4 py-3 text-gray-700">${log.action_taken || '-'}</td>
                                </tr>
                            `;
                        }).join('') : `
                            <tr>
                                <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada kasus perhatian khusus.</td>
                            </tr>
                        `}
                    </tbody>
                </table>
            </div>
        `;

        catatanContent.innerHTML = privateSchedulesHtml + specialCasesHtml;
    }

    function renderJadwalDinas() {
        const jadwalContent = document.getElementById('jadwal');
        let tbody;

        jadwalContent.innerHTML = `
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Jadwal Dinas Staff</h2>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                    <h3 class="text-lg font-semibold mb-2">Shift Pagi (07:00-14:00)</h3>
                    <p class="text-blue-100">${staffSchedulesData.shift_summary['Pagi'] || 0} Jadwal</p>
                </div>
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-6 text-white">
                    <h3 class="text-lg font-semibold mb-2">Shift Siang (14:00-21:00)</h3>
                    <p class="text-orange-100">${staffSchedulesData.shift_summary['Siang'] || 0} Jadwal</p>
                </div>
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                    <h3 class="text-lg font-semibold mb-2">Shift Malam (21:00-07:00)</h3>
                    <p class="text-purple-100">${staffSchedulesData.shift_summary['Malam'] || 0} Jadwal</p>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white col-span-full">
                    <h3 class="text-lg font-semibold mb-2">Total Jadwal Minggu Ini</h3>
                    <p class="text-green-100">${(staffSchedulesData.shift_summary['Pagi'] || 0) + (staffSchedulesData.shift_summary['Siang'] || 0) + (staffSchedulesData.shift_summary['Malam'] || 0)} Jadwal</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left">Nama Staff</th>
                            <th class="px-4 py-3 text-center">Senin</th>
                            <th class="px-4 py-3 text-center">Selasa</th>
                            <th class="px-4 py-3 text-center">Rabu</th>
                            <th class="px-4 py-3 text-center">Kamis</th>
                            <th class="px-4 py-3 text-center">Jumat</th>
                            <th class="px-4 py-3 text-center">Sabtu</th>
                            <th class="px-4 py-3 text-center">Minggu</th>
                        </tr>
                    </thead>
                    <tbody id="jadwalDinasTableBody">
                    </tbody>
                </table>
            </div>
        `;

        tbody = document.getElementById('jadwalDinasTableBody');

        const daysForTableHeader = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        const schedulesToRender = staffSchedulesData.schedules || [];

        if (schedulesToRender.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-4">Tidak ada jadwal dinas untuk staff Anda minggu ini.</td>
                </tr>
            `;
            return;
        }

        const staffScheduleRows = schedulesToRender.map(staffRow => {
            const staffName = staffRow.staff_name;
            const dayCells = daysForTableHeader.map(dayHeader => {
                const dayKey = dayHeader.toLowerCase();
                const dayData = staffRow[dayKey] || { display: '-', types: ['empty'] };

                let badgeClass = 'bg-gray-100 text-gray-800';
                
                if (dayData.types && dayData.types.length > 0 && dayData.types[0] !== 'empty') {
                    const primaryShiftType = dayData.types[0];
                    if (primaryShiftType === 'Pagi') badgeClass = 'bg-blue-100 text-blue-800';
                    else if (primaryShiftType === 'Siang') badgeClass = 'bg-orange-100 text-orange-800';
                    else if (primaryShiftType === 'Malam') badgeClass = 'bg-purple-100 text-purple-800';
                }
                
                return `<td class="px-4 py-3 text-center"><span class="${badgeClass} px-2 py-1 rounded text-xs">${dayData.display}</span></td>`;
            }).join('');

            return `
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">${staffName}</td>
                    ${dayCells}
                </tr>
            `;
        }).join('');

        tbody.innerHTML = staffScheduleRows;
    }

    function renderKinerjaStaff() { // Correctly defined and available
        const kinerjaContent = document.getElementById('kinerja');
        let tbody;

        kinerjaContent.innerHTML = `
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Ringkasan Kinerja Staff</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
                    <h3 class="text-lg font-semibold mb-2">Sangat Baik</h3>
                    <p class="text-3xl font-bold">${staffPerformanceData.filter(e => e.status_kinerja === 'Sangat Baik').length}</p>
                    <p class="text-green-100 text-sm">Staff</p>
                </div>
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
                    <h3 class="text-lg font-semibold mb-2">Baik</h3>
                    <p class="text-3xl font-bold">${staffPerformanceData.filter(e => e.status_kinerja === 'Baik').length}</p>
                    <p class="text-blue-100 text-sm">Staff</p>
                </div>
                <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl p-6 text-white shadow-lg">
                    <h3 class="text-lg font-semibold mb-2">Cukup</h3>
                    <p class="text-3xl font-bold">${staffPerformanceData.filter(e => e.status_kinerja === 'Cukup').length}</p>
                    <p class="text-yellow-100 text-sm">Staff</p>
                </div>
                <div class="bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl p-6 text-white shadow-lg">
                    <h3 class="text-lg font-semibold mb-2">Kurang / Sangat Kurang</h3>
                    <p class="text-3xl font-bold">${staffPerformanceData.filter(e => e.status_kinerja === 'Kurang' || e.status_kinerja === 'Sangat Kurang').length}</p>
                    <p class="text-red-100 text-sm">Staff</p>
                </div>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-3 mt-6">Rekapitulasi Penilaian Staff</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-center">Kedisiplinan</th>
                            <th class="px-4 py-3 text-center">Komunikasi</th>
                            <th class="px-4 py-3 text-center">Komplain</th>
                            <th class="px-4 py-3 text-center">Kepatuhan</th>
                            <th class="px-4 py-3 text-center">Target</th>
                            <th class="px-4 py-3 text-center">Skor Akhir</th>
                            <th class="px-4 py-3 text-left">Catatan</th>
                        </tr>
                    </thead>
                    <tbody id="kinerjaStaffTableBody">
                    </tbody>
                </table>
            </div>
        `;

        tbody = document.getElementById('kinerjaStaffTableBody');

        if (!staffPerformanceData || staffPerformanceData.length === 0) {
            const row = document.createElement('tr');
            row.innerHTML = `<td colspan="8" class="text-center py-4 text-gray-500">Tidak ada data penilaian kinerja staff.</td>`;
            tbody.appendChild(row);
            return;
        }

        staffPerformanceData.forEach(evaluation => {
            const staff = evaluation.staff;

            // These scores are now coming directly from the backend as numbers
            const disciplineScore = evaluation.discipline_score;
            const communicationScore = evaluation.communication_score;
            const complaintScore = evaluation.complaint_count; // complaint_count is a raw count, getRatingDescription handles it
            const complianceScore = evaluation.compliance_score;
            const targetScore = evaluation.target_achievement;
            const overallScore = evaluation.overall_score; // This is the 0-100 score for star rating

            const row = document.createElement('tr');
            row.classList.add('border-t', 'hover:bg-gray-50');

            row.innerHTML = `
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">${staff ? staff.name.charAt(0).toUpperCase() : 'N/A'}</div>
                        <div>
                            <p class="font-semibold text-black">${staff ? staff.name : 'N/A'}</p>
                            <p class="text-xs text-gray-500">Jabatan: ${staff && staff.position ? staff.position.name : 'N/A'}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="${getRatingTextColor(disciplineScore)} font-medium">${getRatingDescription(disciplineScore)}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="${getRatingTextColor(communicationScore)} font-medium">${getRatingDescription(communicationScore)}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="${getRatingTextColor(complaintScore)} font-medium">${getRatingDescription(complaintScore)}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="${getRatingTextColor(complianceScore)} font-medium">${getRatingDescription(complianceScore)}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="${getRatingTextColor(targetScore)} font-medium">${getRatingDescription(targetScore)}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="performance-badge px-2 py-1 rounded text-xs font-medium" style="background-color: ${getPerformanceBadgeColor(evaluation.status_kinerja)}; color: white;">
                        ${evaluation.overall_score || '-'}% (${evaluation.status_kinerja || 'N/A'})
                    </span>
                    <div class="flex items-center justify-center mt-1">
                        ${getStarRating(evaluation.overall_score)}
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-600">${evaluation.notes || 'Tidak ada catatan.'}</td>
            </tr>
            `;
            tbody.appendChild(row);
        });
    }

    function renderTna() {
        const tnaContent = document.getElementById('tna');
        tnaContent.innerHTML = `
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Training Need Assessment (TNA)</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left">Nama Staff</th>
                            <th scope="col" class="px-4 py-3 text-left">Seminar / Workshop / Webinar</th>
                            <th scope="col" class="px-4 py-3 text-left">Pelatihan</th>
                            <th scope="col" class="px-4 py-3 text-left">Pendidikan Lanjutan</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${tnaRecords.length > 0 ? tnaRecords.map(tna => {
                            const staffName = tna.staff ? tna.staff.name : 'N/A';
                            return `
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium">${staffName}</td>
                                    <td class="px-4 py-3">${tna.seminar_workshop_webinar || 'Belum Ada'}</td>
                                    <td class="px-4 py-3">${tna.pelatihan || 'Belum Ada'}</td>
                                    <td class="px-4 py-3">${tna.pendidikan_lanjutan || 'Belum Ada'}</td>
                                </tr>
                            `;
                        }).join('') : `
                            <tr>
                                <td colspan="4" class="text-center py-4">Tidak ada data TNA.</td>
                            </tr>
                        `}
                    </tbody>
                </table>
            </div>
        `;
    }

    function renderIndikatorMutu() {
        const mutuContent = document.getElementById('mutu');
        const recentInspections = qualityIndicators.recent_inspections || [];
        const overallPassRate = qualityIndicators.overall_pass_rate || 0;

        mutuContent.innerHTML = `
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Indikator Mutu</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Rata-rata Tingkat Kepatuhan (Global)</h3>
                            <p class="text-2xl font-bold">${overallPassRate}%</p>
                        </div>
                        <i class="fas fa-chart-line text-3xl opacity-70"></i>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Total Form Terisi (Unik)</h3>
                            <p class="text-2xl font-bold">${qualityIndicators.recent_inspections.length > 0 ? new Set(qualityIndicators.recent_inspections.map(item => item.form_name)).size : 0}</p>
                        </div>
                        <i class="fas fa-file-alt text-3xl opacity-70"></i>
                    </div>
                </div>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-3 mt-6">Inspeksi Mutu Terbaru (Semua Formulir)</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left">Mulai Minggu Aktivitas</th>
                            <th scope="col" class="px-4 py-3 text-left">Jenis Formulir</th>
                            <th scope="col" class="px-4 py-3 text-left">Pasien/Entitas</th> <th scope="col" class="px-4 py-3 text-center">Skor/Kepatuhan</th>
                            <th scope="col" class="px-4 py-3 text-left">Catatan Ringkas</th>
                            <th scope="col" class="px-4 py-3 text-left">Waktu Input</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${recentInspections.length > 0 ? recentInspections.map(inspection => `
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3">${formatDate(inspection.activity_date)}</td>
                                <td class="px-4 py-3">${inspection.form_name || 'N/A'}</td>
                                <td class="px-4 py-3">${inspection.patient_name || inspection.medical_record_number || 'N/A'}</td> <td class="px-4 py-3 text-center">${inspection.score || 'N/A'}</td>
                                <td class="px-4 py-3">${inspection.notes || 'Tidak ada'}</td>
                                <td class="px-4 py-3">${formatDateTime(inspection.submitted_at)}</td>
                            </tr>
                        `).join('') : `
                            <tr>
                                <td colspan="6" class="text-center py-4">Tidak ada data inspeksi mutu terbaru.</td>
                            </tr>
                        `}
                    </tbody>
                </table>
            </div>
        `;
    }

    function renderManajemenLogistik() {
        const logistikContent = document.getElementById('logistik');
        const totalStock = logisticsData.total_stock_available || 0;
        const limitedStock = logisticsData.limited_stock || 0;
        const lowStock = logisticsData.low_stock || 0;
        const categorizedItems = logisticsData.categorized_items || {};
        const categoriesOverview = logisticsData.categories_overview || [];

        logistikContent.innerHTML = `
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Manajemen Logistik</h2>

            <div class="grid md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Total Stok Tersedia</h3>
                            <p class="text-3xl font-bold">${totalStock}</p>
                            <p class="text-green-100 text-sm mt-1">Items dalam kondisi baik</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Stok Terbatas</h3>
                            <p class="text-3xl font-bold">${limitedStock}</p>
                            <p class="text-yellow-100 text-sm mt-1">Perlu segera diisi ulang</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Stok Menipis</h3>
                            <p class="text-3xl font-bold">${lowStock}</p>
                            <p class="text-red-100 text-sm mt-1">Butuh perhatian urgent</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-times-circle text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6" id="logisticsCategoriesContainer">
                ${categoriesOverview.map(category => `
                    ${category.count > 0 ? `
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                            <div class="p-6 cursor-pointer" onclick="toggleSection('${category.slug}')">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                                            <i class="fas ${category.icon_class} text-blue-600 text-xl"></i>
                                        </div>
                                        <div>
                                            <span class="text-lg font-semibold text-gray-900">${category.name}</span>
                                            <div class="text-sm text-gray-500 mt-1">${category.description_text}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">${category.count} Items</div>
                                        <i class="fas fa-chevron-down text-gray-400 transform transition-transform duration-300" id="arrow-${category.slug}"></i>
                                    </div>
                                </div>
                            </div>
                            <div id="${category.slug}" class="hidden border-t border-gray-100">
                                <div class="p-4">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Merk</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Diperbarui</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                ${categorizedItems[category.slug].length > 0 ? categorizedItems[category.slug].map(item => `
                                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.item_name || '-'}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.brand || '-'}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.stock || '0'}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.unit_of_measure || '-'}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                                                item.status === 'Tersedia' ? 'bg-green-100 text-green-800' :
                                                                item.status === 'Terbatas' ? 'bg-yellow-100 text-yellow-800' :
                                                                item.status === 'Menipis' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'
                                                            }">
                                                                ${item.status || '-'}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDateTime(item.last_updated)}</td>
                                                    </tr>
                                                `).join('') : `
                                                    <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada item dalam kategori ini.</td></tr>
                                                `}
                                            </tbody>
                                        </table>
                                    </div>
                                    ${category.count > 5 ? `
                                        <div class="mt-4 text-center">
                                            <a href="/mltable?category=${category.slug}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Lihat semua ${category.count} item <i class="fas fa-arrow-right ml-1"></i>
                                            </a>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    ` : ''}
                `).join('')}
            </div>
        `;

        logistikContent.querySelectorAll('.p-6.cursor-pointer').forEach(header => {
            header.onclick = function() {
                const sectionId = this.getAttribute('onclick').match(/toggleSection\('(.+?)'\)/)[1];
                toggleSection(sectionId);
            };
        });

        // --- Initialize Logistic Form Dropdowns and Handlers ---
        const logisticCategorySelect = document.getElementById('logistic_category');
        const logisticItemNameSelect = document.getElementById('logistic_item_name');
        const otherItemNameContainer = document.getElementById('other_item_name_container');
        const otherItemNameInput = document.getElementById('other_item_name');
        const logisticForm = document.getElementById('logisticForm');

        if (logisticCategorySelect && logisticItemNameSelect && otherItemNameContainer && otherItemNameInput && logisticForm) {
            // Re-bind event listeners for dynamically added content
            logisticCategorySelect.removeEventListener('change', handleLogisticCategoryChange); // Prevent multiple bindings
            logisticCategorySelect.addEventListener('change', handleLogisticCategoryChange);

            logisticItemNameSelect.removeEventListener('change', handleLogisticItemNameChange); // Prevent multiple bindings
            logisticItemNameSelect.addEventListener('change', handleLogisticItemNameChange);

            logisticForm.removeEventListener('submit', handleLogisticFormSubmission); // Prevent multiple bindings
            logisticForm.addEventListener('submit', handleLogisticFormSubmission);

            // Populate initial item name dropdown based on current category selection, if any
            if (logisticCategorySelect.value) {
                populateLogisticItemNameDropdown(logisticCategorySelect.value);
            }
        }

        // Re-initialize Flowbite components for dynamically added content
        if (typeof initFlowbite === 'function') {
             initFlowbite();
        } else {
             console.warn('Flowbite initFlowbite function not found. Ensure Flowbite JS is loaded.');
        }
    }

    function renderPpiData() {
        const ppiContent = document.getElementById('ppi');
        
        // Destroy existing chart instances before rendering new content
        if (ppiInsertionComplianceChartInstance) ppiInsertionComplianceChartInstance.destroy();
        if (ppiMaintenanceComplianceChartInstance) ppiMaintenanceComplianceChartInstance.destroy();
        if (ppiInfectionTrendChartInstance) ppiInfectionTrendChartInstance.destroy();
        if (ppiNeedlestickTrendChartInstance) ppiNeedlestickTrendChartInstance.destroy();
        if (ppiInfectionLocationChartInstance) ppiInfectionLocationChartInstance.destroy();
        if (ppiMicroorganismChartInstance) ppiMicroorganismChartInstance.destroy();
        if (ppiNeedlestickByDepartmentChartInstance) ppiNeedlestickByDepartmentChartInstance.destroy();
        if (ppiNeedlestickByPositionChartInstance) ppiNeedlestickByPositionChartInstance.destroy();

        // Build the HTML structure for PPI tab
        ppiContent.innerHTML = `
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Pengendalian & Pencegahan Infeksi (PPI)</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-md border border-blue-100 flex items-center hover:shadow-lg transition-shadow duration-300">
                    <div class="bg-blue-100 p-3 rounded-full mr-4">
                        <i class="fas fa-check-circle text-blue-800 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Insersi Hari Ini</h3>
                        <p class="text-2xl font-bold text-gray-800">${ppiData.total_insertions_today || 0} Form</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border border-purple-100 flex items-center hover:shadow-lg transition-shadow duration-300">
                    <div class="bg-purple-100 p-3 rounded-full mr-4">
                        <i class="fas fa-hand-holding-medical text-purple-800 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Maintenance Hari Ini</h3>
                        <p class="text-2xl font-bold text-gray-800">${ppiData.total_maintenances_today || 0} Form</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border border-red-100 flex items-center hover:shadow-lg transition-shadow duration-300">
                    <div class="bg-red-100 p-3 rounded-full mr-4">
                        <i class="fas fa-bug text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Infeksi Hari Ini</h3>
                        <p class="text-2xl font-bold text-gray-800">${ppiData.total_infections_today || 0} Kasus</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md border border-emerald-100 flex items-center hover:shadow-lg transition-shadow duration-300">
                    <div class="bg-emerald-100 p-3 rounded-full mr-4">
                        <i class="fas fa-syringe text-emerald-800 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Tertusuk Jarum Hari Ini</h3>
                        <p class="text-2xl font-bold text-gray-800">${ppiData.total_needlestick_cases_today || 0} Kasus</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Indikator Kinerja Utama PPI (30 Hari Terakhir)</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                        <div class="text-blue-800 font-medium">Kepatuhan Insersi</div>
                        <div class="text-2xl font-bold text-blue-900">${ppiData.insertion_compliance_rate || 0}%</div>
                        <div class="text-sm text-blue-600">dari ${ppiData.total_insertions_today || 0} form</div>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg border border-purple-100">
                        <div class="text-purple-800 font-medium">Kepatuhan Maintenance</div>
                        <div class="text-2xl font-bold text-purple-900">${ppiData.maintenance_compliance_rate || 0}%</div>
                        <div class="text-sm text-purple-600">dari ${ppiData.total_maintenances_today || 0} form</div>
                    </div>
                    <div class="bg-emerald-50 p-4 rounded-lg border border-emerald-100">
                        <div class="text-emerald-800 font-medium">Laporan Tertusuk Jarum</div>
                        <div class="text-2xl font-bold text-emerald-900">${ppiData.needlestick_rate_30_days || 0} Kasus</div>
                        <div class="text-sm text-emerald-600">30 hari terakhir</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Tren Infeksi CVC (6 Bulan Terakhir)</h3>
                    <div class="h-64">
                        <canvas id="ppiInfectionTrendChart"></canvas>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Tren Insiden Tertusuk Jarum (6 Bulan Terakhir)</h3>
                    <div class="h-64">
                        <canvas id="ppiNeedlestickTrendChart"></canvas>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Infeksi CVC Berdasarkan Lokasi Insersi</h3>
                    <div class="h-64">
                        <canvas id="ppiInfectionLocationChart"></canvas>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Infeksi CVC Berdasarkan Mikroorganisme</h3>
                    <div class="h-64">
                        <canvas id="ppiMicroorganismChart"></canvas>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Insiden Tertusuk Jarum Berdasarkan Unit/Bagian</h3>
                    <div class="h-64">
                        <canvas id="ppiNeedlestickByDepartmentChart"></canvas>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Insiden Tertusuk Jarum Berdasarkan Jabatan</h3>
                    <div class="h-64">
                        <canvas id="ppiNeedlestickByPositionChart"></canvas>
                    </div>
                </div>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-3 mt-6">Aktivitas PPI Terbaru</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Aktivitas</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien/Nama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. RM</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Form</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Submit</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="ppiRecentActivitiesTableBody">
                    </tbody>
                </table>
            </div>
        `;

        // Populate recent activities table
        const ppiRecentActivitiesTableBody = document.getElementById('ppiRecentActivitiesTableBody');
        const recentPpiActivities = ppiData.recent_ppi_activities || [];
        if (ppiRecentActivitiesTableBody) {
            ppiRecentActivitiesTableBody.innerHTML = '';
            if (recentPpiActivities.length === 0) {
                ppiRecentActivitiesTableBody.innerHTML = `<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada aktivitas PPI terbaru.</td></tr>`;
            } else {
                recentPpiActivities.forEach(activity => {
                    const row = document.createElement('tr');
                    row.classList.add('hover:bg-gray-50', 'transition-colors', 'duration-150');
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(activity.activity_date)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${activity.patient_name || activity.injured_person_name || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${activity.medical_record_number || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${activity.form_type || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDateTime(activity.submitted_at)}</td>
                    `;
                    ppiRecentActivitiesTableBody.appendChild(row);
                });
            }
        }


        // --- Render Charts ---
        // 1. CVC Insertion Compliance Chart
        const insertionComplianceCtx = document.getElementById('ppiInsertionComplianceChart')?.getContext('2d');
        if (insertionComplianceCtx) {
            ppiInsertionComplianceChartInstance = new Chart(insertionComplianceCtx, {
                type: 'bar',
                data: {
                    labels: ['Kepatuhan Insersi'],
                    datasets: [{
                        label: 'Kepatuhan (%)',
                        data: [ppiData.insertion_compliance_rate || 0],
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
        const maintenanceComplianceCtx = document.getElementById('ppiMaintenanceComplianceChart')?.getContext('2d');
        if (maintenanceComplianceCtx) {
            ppiMaintenanceComplianceChartInstance = new Chart(maintenanceComplianceCtx, {
                type: 'bar',
                data: {
                    labels: ['Kepatuhan Maintenance'],
                    datasets: [{
                        label: 'Kepatuhan (%)',
                        data: [ppiData.maintenance_compliance_rate || 0],
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

        // 3. Infection Trend Chart
        const infectionTrendCtx = document.getElementById('ppiInfectionTrendChart')?.getContext('2d');
        if (infectionTrendCtx) {
            const labels = ppiData.infection_trend.map(item => {
                const [year, month] = item.month.split('-');
                return new Date(year, month - 1).toLocaleString('id-ID', { month: 'short', year: '2-digit' });
            });
            const data = ppiData.infection_trend.map(item => item.count);

            ppiInfectionTrendChartInstance = new Chart(infectionTrendCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Infeksi',
                        data: data,
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

        // 4. Needlestick Trend Chart
        const needlestickTrendCtx = document.getElementById('ppiNeedlestickTrendChart')?.getContext('2d');
        if (needlestickTrendCtx) {
            const labels = ppiData.needlestick_trend.map(item => {
                const [year, month] = item.month.split('-');
                return new Date(year, month - 1).toLocaleString('id-ID', { month: 'short', year: '2-digit' });
            });
            const data = ppiData.needlestick_trend.map(item => item.count);

            ppiNeedlestickTrendChartInstance = new Chart(needlestickTrendCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Kasus Tertusuk Jarum',
                        data: data,
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

        // 5. Infection by Location Chart (Pie)
        const infectionLocationCtx = document.getElementById('ppiInfectionLocationChart')?.getContext('2d');
        if (infectionLocationCtx) {
            const labels = ppiData.infection_by_location.map(item => item.insertion_location || 'Tidak Diketahui');
            const data = ppiData.infection_by_location.map(item => item.count);

            ppiInfectionLocationChartInstance = new Chart(infectionLocationCtx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
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

        // 6. Infection by Microorganism Chart (Bar)
        const microorganismCtx = document.getElementById('ppiMicroorganismChart')?.getContext('2d');
        if (microorganismCtx) {
            const labels = ppiData.infection_by_microorganism.map(item => item.microorganism);
            const data = ppiData.infection_by_microorganism.map(item => item.count);

            ppiMicroorganismChartInstance = new Chart(microorganismCtx, {
                type: 'bar',
                data: { labels: labels, datasets: [{ label: 'Jumlah Kasus', data: data, backgroundColor: 'rgba(153, 102, 255, 0.7)', borderColor: 'rgb(153, 102, 255)', borderWidth: 1 }] },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y', // Horizontal bar chart
                    scales: {
                        x: { beginAtZero: true, title: { display: true, text: 'Jumlah Kasus' } },
                        y: { title: { display: true, text: 'Mikroorganisme' } }
                    },
                    plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(context) { return `Kasus: ${context.raw}`; } } } }
                }
            });
        }

        // 7. Needlestick by Department Chart (Pie)
        const needlestickByDepartmentCtx = document.getElementById('ppiNeedlestickByDepartmentChart')?.getContext('2d');
        if (needlestickByDepartmentCtx) {
            const labels = ppiData.needlestick_by_department.map(item => item.department || 'Tidak Diketahui');
            const data = ppiData.needlestick_by_department.map(item => item.count);

            ppiNeedlestickByDepartmentChartInstance = new Chart(needlestickByDepartmentCtx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
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

        // 8. Needlestick by Position Chart (Bar)
        const needlestickByPositionCtx = document.getElementById('ppiNeedlestickByPositionChart')?.getContext('2d');
        if (needlestickByPositionCtx) {
            const labels = ppiData.needlestick_by_position.map(item => item.injured_person_position || 'Tidak Diketahui');
            const data = ppiData.needlestick_by_position.map(item => item.count);

            ppiNeedlestickByPositionChartInstance = new Chart(needlestickByPositionCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Kasus',
                        data: data,
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
    }


    // --- Expose global functions (for onclick attributes in HTML) ---

    window.showTab = async function(tabId) {
        const tabButtons = document.querySelectorAll('.tab-btn');
        tabButtons.forEach(btn => {
            btn.classList.remove('border-blue-600', 'text-blue-600');
            btn.classList.add('text-gray-500', 'hover:text-gray-700');
            btn.style.borderBottomWidth = '0px';
        });
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });

        const clickedButton = document.querySelector(`.tab-btn[data-tab="${tabId}"]`);
        if (clickedButton) {
            clickedButton.classList.add('border-blue-600', 'text-blue-600');
            clickedButton.classList.remove('text-gray-500', 'hover:text-gray-700');
            clickedButton.style.borderBottomWidth = '2px';
        }

        document.getElementById(tabId).classList.add('active');

        await loadDataForTab(tabId);
    };

    // NEW: Global function for report download
    window.downloadReport = function(type, reportNameSlug) {
        showLoading(); // Show global loading indicator
        let url = `/reports/export/${reportNameSlug}/${type}`;

        // Append the auth token as a query parameter for direct browser downloads
        // This is crucial for authentication on web routes that aren't handling AJAX tokens automatically.
        const fullUrl = `${url}?token=${currentAuthToken}`;

        // Create a temporary link element to trigger the download
        const link = document.createElement('a');
        link.href = fullUrl;
        link.target = '_blank'; // Open in a new tab, especially good for PDFs
        link.style.display = 'none'; // Hide the link element
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link); // Clean up the temporary link

        console.log(`Attempting to download ${type} report for ${reportNameSlug} from ${fullUrl}`);

        // Set a timeout to hide the loading indicator.
        // We can't know precisely when the browser finishes the file download,
        // so a short delay is a heuristic for better UX.
        setTimeout(() => {
            hideLoading();
            alert(`Laporan ${reportNameSlug.replace(/-/g, ' ').toUpperCase()} (${type.toUpperCase()}) sedang diunduh. Mohon cek unduhan browser Anda.`);
        }, 1500); // 1.5 seconds delay
    };

    // --- Expose global functions (for onclick attributes in HTML) ---
    window.showTab = async function(tabId) {
        const tabButtons = document.querySelectorAll('.tab-btn');
        tabButtons.forEach(btn => {
            btn.classList.remove('border-blue-600', 'text-blue-600');
            btn.classList.add('text-gray-500', 'hover:text-gray-700');
            btn.style.borderBottomWidth = '0px';
        });
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });

        const clickedButton = document.querySelector(`.tab-btn[data-tab="${tabId}"]`);
        if (clickedButton) {
            clickedButton.classList.add('border-blue-600', 'text-blue-600');
            clickedButton.classList.remove('text-gray-500', 'hover:text-gray-700');
            clickedButton.style.borderBottomWidth = '2px';
        }

        document.getElementById(tabId).classList.add('active');

        await loadDataForTab(tabId);
    };


})(); // --- End of IIFE