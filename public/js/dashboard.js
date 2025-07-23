// resources/js/dashboard.js

(function() { // Wrap in IIFE

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

    // Fetch data from the API and store it in sessionStorage
    async function prefetchAllData() {
        console.log('🚀 Starting to prefetch all application data in the background...');
        
        // Define all endpoints to pre-fetch
        const endpoints = {
            // Dinas Page
            prefetched_dinas_userInfo: '/api/v1/user/info',
            prefetched_dinas_departments: '/api/v1/departments',
            prefetched_dinas_positions: '/api/v1/positions',
            prefetched_dinas_staff: '/api/v1/staff',
            prefetched_dinas_shifts: '/api/v1/shifts',
            prefetched_dinas_schedules: '/api/v1/schedules',
            // Kinerja Staff Page
            prefetched_kinerja_evaluations: '/api/v1/performance-evaluations',
            // PPI Page
            prefetched_ppi_analytics: '/api/v1/cvc-infections/analytics',
            // Schedule Page
            prefetched_schedule_private: '/api/v1/private-schedules',
            prefetched_schedule_special: '/api/v1/special-cases',
            // TNA Page
            prefetched_tna_records: '/api/v1/training-needs',
            // Notifikasi Page
            prefetched_notifications: '/api/v1/notifications',
            // Laporan Page
            prefetched_laporan_header: '/api/v1/reports/header-stats',
            prefetched_laporan_logs: '/api/v1/reports/daily-logs',
            // Indikator Mutu (We will pre-fetch the list of indicators)
            prefetched_indikator_mutu_all: '/api/v1/quality-inspection/all-indicators/all'
        };

        const headers = getAuthHeaders();
        const requests = Object.entries(endpoints).map(([key, url]) => 
            fetch(url, { headers })
                .then(res => {
                    if (!res.ok) throw new Error(`Failed to fetch ${url}`);
                    return res.json();
                })
                .then(data => ({ key, data })) // Pair the data with its key
        );
        
        // Use Promise.allSettled to ensure that if one request fails, the others are still cached.
        const results = await Promise.allSettled(requests);
        
        results.forEach(result => {
            if (result.status === 'fulfilled') {
                const { key, data } = result.value;
                try {
                    sessionStorage.setItem(key, JSON.stringify(data));
                    console.log(`✅ Cached data for: ${key}`);
                } catch (e) {
                    console.error(`Error storing data for ${key} in sessionStorage:`, e);
                }
            } else {
                console.warn(`⚠️ Failed to prefetch an endpoint:`, result.reason.message);
            }
        });
        
        console.log('✅ Background pre-fetching complete.');
    }


    // --- Initial Load on DOMContentLoaded ---
    document.addEventListener('DOMContentLoaded', async function() {
        // Ensure authToken is set when DOM is ready
        if (!currentAuthToken) {
            console.error("Auth token not found on DOMContentLoaded in dashboard.js. Redirecting to login.");
            return;
        }
        prefetchAllData();
    });

})(); // End of IIFE