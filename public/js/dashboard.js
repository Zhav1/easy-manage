// resources/js/dashboard.js

(function() { // Wrap in IIFE

    let dashboardData = {}; // Store fetched dashboard data

    // --- Helper Functions ---

    // Ensure window.authToken is available from the Blade file
    const currentAuthToken = window.authToken;

    function getAuthHeaders() {
        if (!currentAuthToken) {
            console.error('Authentication token is missing. Please ensure you are logged in.');
            return {};
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

    // --- Data Fetching ---
    async function fetchDashboardData() {
        try {
            const response = await fetch('/api/v1/dashboard-data', { headers: getAuthHeaders() });
            if (handleUnauthorized(response)) return;
            if (!response.ok) throw new Error('Failed to fetch dashboard data');
            dashboardData = await response.json();
            console.log('Fetched Dashboard Data:', dashboardData); // Debugging
        } catch (error) {
            console.error('Error fetching dashboard data:', error);
            // Set fallback values for UI stability if fetch fails
            dashboardData = {
                user_name: 'Pengguna',
                profile_photo_url: 'images/p.png', // Default image fallback
                greeting_time: 'Siang',
                current_date: 'N/A',
                today_schedules_count: 'N/A',
                low_stock_count: 'N/A',
                ppi_submitted_today_count: 'N/A', // Corrected name for display
                tasks_completed_count: 'N/A',
                jadwal_next_shift_time: 'Tidak ada jadwal',
                jadwal_active_nurses: 'N/A',
                logistik_total_stock: 'N/A',
                logistik_thinning_items: 'N/A',
                ppi_compliance_rate: 'N/A',
                ppi_last_audit_days_ago: 'N/A',
            };
        }
    }

    // --- Rendering ---
    function renderDashboard() {
        // Welcome Section
        const userNameElement = document.getElementById('user-name');
        if (userNameElement) userNameElement.textContent = dashboardData.user_name;
        
        const greetingTimeElement = document.getElementById('greeting-time');
        if (greetingTimeElement) greetingTimeElement.textContent = dashboardData.greeting_time;
        
        const profilePhotoImg = document.querySelector('.relative > img');
        if (profilePhotoImg && dashboardData.profile_photo_url) {
            profilePhotoImg.src = dashboardData.profile_photo_url;
        }
        
        const currentDateElement = document.getElementById('current-date-display');
        if (currentDateElement) {
             currentDateElement.textContent = dashboardData.current_date;
             currentDateElement.setAttribute('datetime', new Date().toISOString());
        }


        // Quick Stats
        document.getElementById('today-schedules-count').textContent = dashboardData.today_schedules_count;
        document.getElementById('low-stock-count').textContent = dashboardData.low_stock_count;
        // Corrected ID for PPI Quick Stat
        document.getElementById('ppi-submitted-today-count').textContent = dashboardData.ppi_submitted_today_count;
        document.getElementById('tasks-completed-count').textContent = dashboardData.tasks_completed_count;

        // Shortcut Cards
        // Jadwal Dinas
        document.getElementById('jadwal-active-nurses').textContent = `Tim: ${dashboardData.jadwal_active_nurses} perawat aktif`;
        
        // Manajemen Logistik
        document.getElementById('logistik-total-stock').textContent = `Stok tersedia: ${dashboardData.logistik_total_stock} item`;
        document.getElementById('logistik-thinning-items').textContent = `Menipis: ${dashboardData.logistik_thinning_items} item`;
        
        // PPI
        document.getElementById('ppi-compliance-rate').textContent = `Kepatuhan: ${dashboardData.ppi_compliance_rate}`;
        document.getElementById('ppi-last-audit').textContent = `Audit terakhir: ${dashboardData.ppi_last_audit_days_ago}`;
    }


    // --- Initial Load on DOMContentLoaded ---
    document.addEventListener('DOMContentLoaded', async function() {
        // Ensure authToken is set when DOM is ready
        if (!currentAuthToken) {
            console.error("Auth token not found on DOMContentLoaded in dashboard.js. Redirecting to login.");
            window.location.href = '/login'; // Redirect if no token
            return;
        }

        await fetchDashboardData();
        renderDashboard();
    });

})(); // End of IIFE