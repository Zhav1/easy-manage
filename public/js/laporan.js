// resources/js/laporan.js

// Wrap the entire script in an IIFE to prevent global variable conflicts.
(function() {

    // --- Global Variables (scoped to this IIFE) ---
    let headerStats = {};
    let dailyLogs = [];
    let staffSchedules = {};
    let shiftSummary = {};
    let logisticsData = {};
    let ppiData = {};
    let staffPerformanceData = [];
    let tnaRecords = [];
    let qualityIndicators = {};

    let currentAuthToken = null; // This variable will hold the token from Blade

    // --- Helper Functions (Defined early within the IIFE scope) ---

    // Date/Time Formatting Helpers
    function formatDate(dateString) {
        if (!dateString) return '-';
        const options = { year: 'numeric', month: 'short', day: 'numeric' };
        // Use 'en-CA' for YYYY-MM-DD for consistency when parsing dates
        // For display, 'id-ID' is good.
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

    // Performance/Rating Helpers
    function getPerformanceBadgeColor(status) {
        switch (status) {
            case 'Excellent Performance': return '#10b981'; // Green
            case 'Good Performance': return '#3b82f6';    // Blue
            case 'Need Mentoring': return '#f59e0b';    // Yellow/Orange
            case 'Need Improvement': return '#ef4444';  // Red
            default: return '#6b7280'; // Gray
        }
    }

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
    // --- End Helper Functions ---


    // --- Initialization on DOM Content Loaded ---
    document.addEventListener('DOMContentLoaded', async function() {
        currentAuthToken = window.authToken;

        if (!currentAuthToken) {
            console.error('Authentication token is missing. Please ensure you are logged in.');
            // window.location.href = '/login';
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
            if (!response.ok) throw new Error('Failed to fetch daily logs');
            dailyLogs = await response.json();
        } catch (error) {
            console.error('Error fetching daily logs:', error);
            dailyLogs = [];
        }
    }

    async function fetchStaffSchedules() {
        try {
            const response = await fetch('/api/v1/reports/staff-schedules', { headers: getAuthHeaders() });
            if (handleUnauthorized(response)) return;
            if (!response.ok) throw new Error('Failed to fetch staff schedules');
            const data = await response.json();
            staffSchedules = data; // Assign the entire data object
        } catch (error) {
            console.error('Error fetching staff schedules:', error);
            staffSchedules = { schedules: {}, shift_summary: {}, all_staff_names: [] };
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
                low_stock_items: 'N/A',
                out_of_stock_items: 'N/A',
                recent_transactions: [],
                all_logistics_items: []
            };
        }
    }

    async function fetchPpiData() {
        try {
            const response = await fetch('/api/v1/reports/ppi-data', { headers: getAuthHeaders() });
            if (handleUnauthorized(response)) return;
            if (!response.ok) throw new Error('Failed to fetch PPI data');
            ppiData = await response.json();
        } catch (error) {
            console.error('Error fetching PPI data:', error);
            ppiData = {
                cvc_insertions_month: 'N/A',
                cvc_maintenances_month: 'N/A',
                cvc_infections_month: 'N/A',
                recent_ppi_activities: []
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

        await fetchHeaderStats();
        renderHeaderStats();

        switch (tabId) {
            case 'catatan':
                await fetchDailyLogs();
                renderCatatanHarian();
                break;
            case 'jadwal':
                await fetchStaffSchedules();
                renderJadwalDinas();
                break;
            case 'logistik':
                await fetchLogisticsSummary();
                renderManajemenLogistik();
                break;
            case 'ppi':
                await fetchPpiData();
                renderPpiData();
                break;
            case 'kinerja':
                await fetchStaffPerformance();
                renderKinerjaStaff();
                break;
            case 'tna':
                await fetchTnaData();
                renderTna();
                break;
            case 'mutu':
                await fetchQualityIndicators();
                renderIndikatorMutu();
                break;
            default:
                console.warn(`Unknown tab ID: ${tabId}. Defaulting to Catatan Harian.`);
                await fetchDailyLogs();
                renderCatatanHarian();
                break;
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
        document.getElementById('complianceRate').textContent = `${headerStats.compliance_rate || 'N/A'}%`;
    }


    // --- Render Functions for Each Tab Content ---

    function renderCatatanHarian() {
        const catatanContent = document.getElementById('catatan');
        catatanContent.innerHTML = `
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Catatan Harian & Notifikasi</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal & Jam</th>
                            <th class="px-4 py-3 text-center">Briefing</th>
                            <th class="px-4 py-3 text-center">Rapat</th>
                            <th class="px-4 py-3 text-center">Supervisi</th>
                            <th class="px-4 py-3 text-center">Handover</th>
                            <th class="px-4 py-3 text-center">Tugas Luar</th>
                            <th class="px-4 py-3 text-left">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${dailyLogs.length > 0 ? dailyLogs.map(log => `
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3">${new Date(log.log_time).toLocaleString('id-ID', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' })}</td>
                                <td class="px-4 py-3 text-center"><span class="${log.briefing_conducted ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'} px-2 py-1 rounded">${log.briefing_conducted ? 'Ya' : 'Tidak'}</span></td>
                                <td class="px-4 py-3 text-center"><span class="${log.meeting_held ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'} px-2 py-1 rounded">${log.meeting_held ? 'Ya' : 'Tidak'}</span></td>
                                <td class="px-4 py-3 text-center"><span class="${log.supervision_conducted ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'} px-2 py-1 rounded">${log.supervision_conducted ? 'Ya' : 'Tidak'}</span></td>
                                <td class="px-4 py-3 text-center"><span class="${log.handover_done ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'} px-2 py-1 rounded">${log.handover_done ? 'Ya' : 'Tidak'}</span></td>
                                <td class="px-4 py-3 text-center"><span class="${log.external_task_performed ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'} px-2 py-1 rounded">${log.external_task_performed ? 'Ya' : 'Tidak'}</span></td>
                                <td class="px-4 py-3">${log.notes || '-'}</td>
                            </tr>
                        `).join('') : `
                            <tr>
                                <td colspan="8" class="text-center py-4">Tidak ada catatan harian.</td>
                            </tr>
                        `}
                    </tbody>
                </table>
            </div>
        `;
    }

    function renderJadwalDinas() {
        const jadwalContent = document.getElementById('jadwal');
        let tbody; // Declare tbody with 'let'

        // Render the main structure of the tab if it's not already there
        // This includes the summary cards and the table skeleton.
        jadwalContent.innerHTML = `
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Jadwal Dinas Staff</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                    <h3 class="text-lg font-semibold mb-2">Shift Pagi (07:00-14:00)</h3>
                    <p class="text-blue-100">${staffSchedules.shift_summary['Pagi'] || 0} Staff bertugas</p>
                </div>
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-6 text-white">
                    <h3 class="text-lg font-semibold mb-2">Shift Sore (14:00-21:00)</h3>
                    <p class="text-orange-100">${staffSchedules.shift_summary['Sore'] || 0} Staff bertugas</p>
                </div>
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                    <h3 class="text-lg font-semibold mb-2">Shift Malam (21:00-07:00)</h3>
                    <p class="text-purple-100">${staffSchedules.shift_summary['Malam'] || 0} Staff bertugas</p>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                    <h3 class="text-lg font-semibold mb-2">Staff Cuti/Libur</h3>
                    <p class="text-green-100">${(staffSchedules.shift_summary['Libur'] || 0) + (staffSchedules.shift_summary['Cuti'] || 0)} Staff tidak bertugas</p>
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

        // IMPORTANT: Select tbody *after* the innerHTML has updated the DOM
        tbody = document.getElementById('jadwalDinasTableBody');

        // Remaining logic for populating tbody
        const daysOfWeekFull = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const daysForTableHeader = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        const today = new Date();
        const currentDayIndex = today.getDay();
        const diffToMonday = (currentDayIndex === 0) ? 6 : (currentDayIndex - 1);
        const mondayOfCurrentWeek = new Date(today);
        mondayOfCurrentWeek.setDate(today.getDate() - diffToMonday);
        mondayOfCurrentWeek.setHours(0, 0, 0, 0);

        const weekDatesFormatted = {};
        for (let i = 0; i < 7; i++) {
            const date = new Date(mondayOfCurrentWeek);
            date.setDate(mondayOfCurrentWeek.getDate() + i);
            weekDatesFormatted[date.toLocaleDateString('en-CA')] = daysOfWeekFull[date.getDay()];
        }

        const allStaffNames = Array.isArray(staffSchedules.all_staff_names) ? staffSchedules.all_staff_names : [];

        const staffScheduleRows = allStaffNames.map(staffName => {
            const dailyShifts = {};
            daysOfWeekFull.forEach(day => { dailyShifts[day] = { display: '-', type: 'empty' }; });

            if (staffSchedules.schedules && staffSchedules.schedules[staffName]) {
                staffSchedules.schedules[staffName].forEach(schedule => {
                    const scheduleDate = new Date(schedule.date);
                    const formattedScheduleDate = scheduleDate.toLocaleDateString('en-CA');
                    const dayName = daysOfWeekFull[scheduleDate.getDay()];

                    if (weekDatesFormatted[formattedScheduleDate] === dayName) {
                        if (schedule.shift_name) { // Use shift_name (e.g., 'Pagi') as type, shift_code (e.g., 'P') as display
                            dailyShifts[dayName] = { display: schedule.shift_code, type: schedule.shift_name };
                        } else {
                            dailyShifts[dayName] = { display: '-', type: 'empty' };
                        }
                    }
                });
            }

            const dayCells = daysForTableHeader.map(dayName => {
                const dayData = dailyShifts[dayName];
                const displayChar = dayData.display;

                let badgeClass = 'bg-gray-100 text-gray-800';
                if (dayData.type === 'Pagi') badgeClass = 'bg-blue-100 text-blue-800';
                else if (dayData.type === 'Sore') badgeClass = 'bg-orange-100 text-orange-800';
                else if (dayData.type === 'Malam') badgeClass = 'bg-purple-100 text-purple-800';
                else if (dayData.type === 'Libur') badgeClass = 'bg-green-100 text-green-800'; // Keep these as they are for now, even if not explicitly provided
                else if (dayData.type === 'Cuti') badgeClass = 'bg-yellow-100 text-yellow-800';

                return `<td class="px-4 py-3 text-center"><span class="${badgeClass} px-2 py-1 rounded text-xs">${displayChar}</span></td>`;
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

    function renderManajemenLogistik() {
        const logistikContent = document.getElementById('logistik');
        logistikContent.innerHTML = `
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Manajemen Logistik</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Stok Tersedia</h3>
                            <p class="text-2xl font-bold">${logisticsData.total_stock_available || 0}</p>
                        </div>
                        <i class="fas fa-boxes text-3xl opacity-70"></i>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Stok Rendah</h3>
                            <p class="text-2xl font-bold">${logisticsData.low_stock_items || 0}</p>
                        </div>
                        <i class="fas fa-exclamation-triangle text-3xl opacity-70"></i>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Stok Habis</h3>
                            <p class="text-2xl font-bold">${logisticsData.out_of_stock_items || 0}</p>
                        </div>
                        <i class="fas fa-times-circle text-3xl opacity-70"></i>
                    </div>
                </div>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-3 mt-6">Daftar Item Logistik</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Nama Item</th>
                            <th class="px-4 py-3 text-center">Kuantitas</th>
                            <th class="px-4 py-3 text-center">Satuan</th>
                            <th class="px-4 py-3 text-center">Min Stok</th>
                            <th class="px-4 py-3 text-left">Terakhir Diperbarui</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${logisticsData.all_logistics_items && logisticsData.all_logistics_items.length > 0 ? logisticsData.all_logistics_items.map(item => `
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3">${item.item_name}</td>
                                <td class="px-4 py-3 text-center">${item.quantity}</td>
                                <td class="px-4 py-3 text-center">${item.unit || '-'}</td>
                                <td class="px-4 py-3">${item.min_stock_level || 0}</td>
                                <td class="px-4 py-3">${new Date(item.last_updated).toLocaleDateString('id-ID')}</td>
                            </tr>
                        `).join('') : `
                            <tr>
                                <td colspan="5" class="text-center py-4">Tidak ada data logistik.</td>
                            </tr>
                        `}
                    </tbody>
                </table>
            </div>
        `;
    }

    function renderPpiData() {
        const ppiContent = document.getElementById('ppi');
        let tbody = ppiContent.querySelector('tbody');

        // Ensure the table structure is present within the tab content
        if (!tbody) {
            ppiContent.innerHTML = `
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Pengendalian & Pencegahan Infeksi (PPI)</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold">Insersi CVC Bulan Ini</h3>
                                <p class="text-2xl font-bold">${ppiData.cvc_insertions_month || 0}</p>
                            </div>
                            <i class="fas fa-syringe text-3xl opacity-70"></i>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold">Maintenance CVC Bulan Ini</h3>
                                <p class="text-2xl font-bold">${ppiData.cvc_maintenances_month || 0}</p>
                            </div>
                            <i class="fas fa-tools text-3xl opacity-70"></i>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold">Laporan Infeksi Bulan Ini</h3>
                                <p class="text-2xl font-bold">${ppiData.cvc_infections_month || 0}</p>
                            </div>
                            <i class="fas fa-bug text-3xl opacity-70"></i>
                        </div>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-gray-800 mb-3 mt-6">Aktivitas PPI Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Aktivitas</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor RM</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Form</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Submit</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="ppiHistoryTableBody">
                            </tbody>
                    </table>
                </div>
            `;
            tbody = document.getElementById('ppiHistoryTableBody');
        } else {
            tbody.innerHTML = '';
        }

        const recentPpiActivities = ppiData.recent_ppi_activities || [];

        if (recentPpiActivities.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-4 text-center text-black">Tidak ada aktivitas PPI terbaru.</td></tr>`;
            return;
        }

        recentPpiActivities.forEach(activity => {
            const row = document.createElement('tr');
            row.classList.add('hover:bg-gray-50', 'transition-colors', 'duration-150');
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(activity.activity_date)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${activity.patient_name || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${activity.medical_record_number || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${activity.form_type || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDateTime(activity.submitted_at)}</td>
            `;
            tbody.appendChild(row);
        });
    }

    function renderKinerjaStaff() {
        const kinerjaContent = document.getElementById('kinerja');
        // IMPORTANT: Declare tbody with 'let' to allow reassignment
        let tbody = kinerjaContent.querySelector('tbody');

        // If tbody doesn't exist, create the full table structure.
        // This is important because the initial tab content div is empty.
        if (!tbody) {
            kinerjaContent.innerHTML = `
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Kinerja Staff</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left">Nama</th>
                                <th class="px-4 py-3 text-center">Kedisiplinan</th>
                                <th class="px-4 py-3 text-center">Komunikasi</th>
                                <th class="px-4 py-3 text-center">Komplain</th>
                                <th class="px-4 py-3 text-center">Kepatuhan</th>
                                <th class="px-4 py-3 text-center">Target</th>
                                <th class="px-4 py-3 text-center">Score</th>
                                <th class="px-4 py-3 text-left">Catatan</th>
                            </tr>
                        </thead>
                        <tbody id="kinerjaStaffTableBody"></tbody>
                    </table>
                </div>
            `;
            // Re-select tbody after it has been created in the DOM
            tbody = document.getElementById('kinerjaStaffTableBody');
        } else {
            // If tbody already exists (meaning the table structure is there), just clear its content.
            tbody.innerHTML = '';
        }

        const evaluationsToRender = staffPerformanceData;

        if (!evaluationsToRender || evaluationsToRender.length === 0) {
            const row = document.createElement('tr');
            row.innerHTML = `<td colspan="8" class="text-center py-4 text-gray-500">Tidak ada data penilaian kinerja staff.</td>`;
            tbody.appendChild(row);
            return;
        }

        evaluationsToRender.forEach(evaluation => {
            const staff = evaluation.staff;

            const row = document.createElement('tr');
            row.classList.add('border-t', 'hover:bg-gray-50');

            // Determine values for rendering
            const disciplineScore = evaluation.discipline_score;
            const communicationScore = evaluation.communication_score;
            const complaintScore = evaluation.complaint_count;
            const complianceScore = evaluation.compliance_score;
            const targetScore = evaluation.target_achievement; // This is the 1-5 score from backend

            const overallScore = evaluation.overall_score; // This is the 0-100 score for star rating

            // Construct star rating HTML using the 0-100 overall_score
            const scoreStarsHtml = getStarRating(overallScore);

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
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center">
                        <span class="status-indicator w-3 h-3 rounded-full mr-2" style="background:${getRatingColor(disciplineScore)}"></span>
                        <span class="${getRatingTextColor(disciplineScore)} font-medium">${getRatingDescription(disciplineScore)}</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center">
                        <span class="status-indicator w-3 h-3 rounded-full mr-2" style="background:${getRatingColor(communicationScore)}"></span>
                        <span class="${getRatingTextColor(communicationScore)} font-medium">${getRatingDescription(communicationScore)}</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center">
                        <span class="status-indicator w-3 h-3 rounded-full mr-2" style="background:${getRatingColor(complaintScore)}"></span>
                        <span class="${getRatingTextColor(complaintScore)} font-medium">${getRatingDescription(complaintScore)}</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center">
                        <span class="status-indicator w-3 h-3 rounded-full mr-2" style="background:${getRatingColor(complianceScore)}"></span>
                        <span class="${getRatingTextColor(complianceScore)} font-medium">${getRatingDescription(complianceScore)}</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center">
                        <span class="status-indicator w-3 h-3 rounded-full mr-2" style="background:${getRatingColor(targetScore)}"></span>
                        <span class="${getRatingTextColor(targetScore)} font-medium">${getRatingDescription(targetScore)}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="flex items-center justify-center gap-1">
                        ${scoreStarsHtml}
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-600">${evaluation.notes || 'Tidak ada catatan.'}</td>
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
                            <th class="px-4 py-3 text-left">Nama Staff</th>
                            <th class="px-4 py-3 text-left">Seminar / Workshop / Webinar</th>
                            <th class="px-4 py-3 text-left">Pelatihan</th>
                            <th class="px-4 py-3 text-left">Pendidikan Lanjutan</th>
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
        mutuContent.innerHTML = `
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Indikator Mutu</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Rata-rata Tingkat Kepatuhan</h3>
                            <p class="text-2xl font-bold">${qualityIndicators.overall_pass_rate || 0}%</p>
                        </div>
                        <i class="fas fa-chart-line text-3xl opacity-70"></i>
                    </div>
                </div>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-3 mt-6">Inspeksi Mutu Terbaru</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Jenis Form</th>
                            <th class="px-4 py-3 text-center">Skor/Kepatuhan</th>
                            <th class="px-4 py-3 text-left">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${qualityIndicators.recent_inspections && qualityIndicators.recent_inspections.length > 0 ? qualityIndicators.recent_inspections.map(inspection => `
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3">${new Date(inspection.date).toLocaleDateString('id-ID')}</td>
                                <td class="px-4 py-3">${inspection.form_type || 'N/A'}</td>
                                <td class="px-4 py-3 text-center">${inspection.score || 'N/A'}</td>
                                <td class="px-4 py-3">${inspection.notes || 'Tidak ada'}</td>
                            </tr>
                        `).join('') : `
                            <tr>
                                <td colspan="4" class="text-center py-4">Tidak ada data inspeksi mutu terbaru.</td>
                            </tr>
                        `}
                    </tbody>
                </table>
            </div>
        `;
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

})(); // --- End of IIFE