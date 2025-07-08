// resources/js/notifikasi.js

// Wrap in IIFE to prevent global variable conflicts
(function() {

    let notificationsData = []; // Store fetched notifications
    const API_BASE_URL_NOTIFICATIONS = '/api/v1/notifications'; // Base URL for notifications API

    // --- Helper Functions ---

    // Ensure window.authToken is available from the Blade file
    const currentAuthToken = window.authToken;

    function getAuthHeaders() {
        if (!currentAuthToken) {
            console.error('Authentication token is missing. Please ensure you are logged in.');
            // In a real app, you might want to redirect to login here
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

    // Function to get appropriate tag color classes (from your original notifikasi.blade.php styling)
    function getTagColorClass(tagColor) {
        switch (tagColor) {
            case 'blue': return 'text-blue-600 bg-blue-100';
            case 'yellow': return 'text-yellow-600 bg-yellow-100';
            case 'teal': return 'text-teal-600 bg-teal-100';
            case 'green': return 'text-green-600 bg-green-100';
            case 'purple': return 'text-purple-600 bg-purple-100';
            case 'indigo': return 'text-indigo-600 bg-indigo-100';
            case 'pink': return 'text-pink-600 bg-pink-100';
            case 'red': return 'text-red-600 bg-red-100';
            default: return 'text-gray-600 bg-gray-100'; // Fallback
        }
    }

    // Function to get card background colors (from your original notifikasi.blade.php styling)
    function getCardBackgroundClass(tagColor) {
        switch (tagColor) {
            case 'blue': return 'bg-gradient-to-r from-blue-50 to-blue-100 border-blue-200';
            case 'yellow': return 'bg-gradient-to-r from-yellow-50 to-amber-50 border-yellow-200';
            case 'teal': return 'bg-gradient-to-r from-teal-50 to-cyan-50 border-teal-200';
            case 'green': return 'bg-gradient-to-r from-green-50 to-emerald-50 border-green-200';
            case 'purple': return 'bg-gradient-to-r from-purple-50 to-violet-50 border-purple-200';
            case 'indigo': return 'bg-gradient-to-r from-indigo-50 to-blue-50 border-indigo-200';
            case 'pink': return 'bg-gradient-to-r from-pink-50 to-rose-50 border-pink-200';
            case 'red': return 'bg-gradient-to-r from-red-50 to-orange-50 border-red-200';
            default: return 'bg-gray-50 border-gray-200'; // Fallback
        }
    }
    // Function to get icon background colors (from your original notifikasi.blade.php styling)
    function getIconBackgroundClass(tagColor) {
        switch (tagColor) {
            case 'blue': return 'bg-blue-500';
            case 'yellow': return 'bg-yellow-500';
            case 'teal': return 'bg-teal-500';
            case 'green': return 'bg-green-500';
            case 'purple': return 'bg-purple-500';
            case 'indigo': return 'bg-indigo-500';
            case 'pink': return 'bg-pink-500';
            case 'red': return 'bg-red-500';
            default: return 'bg-gray-500'; // Fallback
        }
    }


    // --- Data Fetching ---
    async function fetchNotifications() {
        try {
            const response = await fetch(API_BASE_URL_NOTIFICATIONS, { headers: getAuthHeaders() });
            if (handleUnauthorized(response)) return;
            if (!response.ok) throw new Error('Failed to fetch notifications');
            const data = await response.json();
            notificationsData = data.data; // Assuming Laravel pagination returns data in 'data' key
            console.log('Fetched Notifications:', notificationsData);
        } catch (error) {
            console.error('Error fetching notifications:', error);
            notificationsData = []; // Ensure empty array on error
        }
    }

    // --- Rendering ---
    function renderNotifications() {
        const notificationsListContainer = document.getElementById('notifications-list-container');
        if (!notificationsListContainer) {
            console.error("Notifications list container div not found (id='notifications-list-container').");
            return;
        }
        
        notificationsListContainer.innerHTML = ''; // Clear previous content

        if (notificationsData.length === 0) {
            notificationsListContainer.innerHTML = `
                <div class="notification-card bg-gray-50 border border-gray-200 p-4 md:p-6 rounded-xl shadow-lg text-center text-gray-600">
                    <i class="fas fa-info-circle mr-2"></i>Tidak ada notifikasi untuk saat ini.
                </div>
            `;
            // Update total count on tab to 0
            const tabReminderCount = document.querySelector('#tab-reminder span');
            if (tabReminderCount) tabReminderCount.textContent = '0';
            return;
        }

        notificationsData.forEach(notification => {
            const card = document.createElement('div');
            // Add 'is-read' class for styling if notification is read
            const readClass = notification.is_read ? 'opacity-70 grayscale' : ''; // Example styling for read notifications
            card.className = `notification-card ${getCardBackgroundClass(notification.tag_color)} p-4 md:p-6 rounded-xl shadow-lg ${readClass}`;
            
            // Generate action buttons dynamically
            let actionButtonsHtml = '';
            // View/Link button
            if (notification.link) {
                let actionText = 'Lihat Detail';
                let actionIcon = 'fas fa-eye';
                // Customize action text/icon based on notification type if needed
                // These directly map to the static examples in your original blade for behavior consistency
                if (notification.type === 'schedule_reminder') { actionText = 'Lihat Jadwal'; actionIcon = 'fas fa-eye'; }
                else if (notification.type === 'low_stock_alert') { actionText = 'Pesan Sekarang'; actionIcon = 'fas fa-plus'; }
                else if (notification.type === 'ppi_audit_reminder') { actionText = 'Mulai Audit'; actionIcon = 'fas fa-clipboard-check'; }
                else if (notification.type === 'performance_evaluation_deadline') { actionText = 'Mulai Evaluasi'; actionIcon = 'fas fa-star'; }
                else if (notification.type === 'tna_reminder') { actionText = 'Lengkapi TNA'; actionIcon = 'fas fa-edit'; }
                else if (notification.type === 'quality_indicator_update') { actionText = 'Input Data'; actionIcon = 'fas fa-chart-bar'; }
                else if (notification.type === 'meeting_reminder') { actionText = 'Join Meeting'; actionIcon = 'fas fa-video'; }
                else if (notification.type === 'weekly_report_deadline') { actionText = 'Buat Laporan'; actionIcon = 'fas fa-pen'; }


                actionButtonsHtml += `
                    <a href="${notification.link}" class="text-${notification.tag_color}-600 hover:text-${notification.tag_color}-800 text-xs md:text-sm font-medium">
                        <i class="${actionIcon} mr-1"></i>${actionText}
                    </a>
                `;
            }
            // Mark as Read button (only if not already read)
            if (!notification.is_read) {
                actionButtonsHtml += `
                    <button class="text-gray-500 hover:text-gray-700 text-xs md:text-sm mark-read-button" data-id="${notification.id}">
                        <i class="fas fa-check-circle mr-1"></i>Tandai Dibaca
                    </button>
                `;
            }
            // Dismiss button (only if not already dismissed)
            // Note: If you dismiss, it won't show up on next fetch, but remains in DB as is_dismissed=true
            actionButtonsHtml += `
                <button class="text-gray-500 hover:text-gray-700 text-xs md:text-sm dismiss-button" data-id="${notification.id}">
                    <i class="fas fa-bell-slash mr-1"></i>Matikan
                </button>
            `;
            // Delete button (for hard delete, use sparingly)
            actionButtonsHtml += `
                <button class="text-red-500 hover:text-red-700 text-xs md:text-sm delete-button" data-id="${notification.id}">
                    <i class="fas fa-trash-alt mr-1"></i>Hapus
                </button>
            `;


            card.innerHTML = `
                <div class="flex items-start space-x-3 md:space-x-4">
                    <div class="${getIconBackgroundClass(notification.tag_color)} p-2 md:p-3 rounded-full flex-shrink-0">
                        <i class="${notification.icon} text-white"></i>
                    </div>
                    <div class="flex-grow">
                        <div class="flex items-center justify-between mb-1 md:mb-2">
                            <h3 class="font-semibold text-${notification.tag_color}-800 text-sm md:text-base">${notification.title}</h3>
                            <span class="text-xs ${getTagColorClass(notification.tag_color)} px-2 py-1 rounded-full">${notification.tag}</span>
                        </div>
                        <p class="text-${notification.tag_color}-700 mb-2 md:mb-3 text-sm md:text-base">${notification.message}</p>
                        <div class="flex space-x-2 mobile-button-group">
                            ${actionButtonsHtml}
                        </div>
                    </div>
                </div>
            `;
            notificationsListContainer.appendChild(card);
        });

        // Update total count on tab
        const tabReminderCount = document.querySelector('#tab-reminder span');
        if (tabReminderCount) tabReminderCount.textContent = notificationsData.length;

        // Note: Event listeners are attached via delegation in attachNotificationEventListeners
    }

    // --- Notification Interaction Handlers ---
    async function markNotificationAsRead(id) {
        try {
            const response = await fetch(`${API_BASE_URL_NOTIFICATIONS}/${id}/read`, { method: 'PATCH', headers: getAuthHeaders() });
            if (handleUnauthorized(response)) return;
            if (!response.ok) throw new Error('Failed to mark as read');
            // Update local data and re-render
            const updatedNotification = await response.json();
            const index = notificationsData.findIndex(n => n.id === id);
            if (index !== -1) { notificationsData[index].is_read = true; }
            renderNotifications(); // Re-render to update UI
            console.log(`Notification ${id} marked as read.`);
        }
        catch (error) {
            console.error('Error marking notification as read:', error);
            alert('Gagal menandai notifikasi sebagai dibaca.');
        }
    }

    async function dismissNotification(id) {
        try {
            const response = await fetch(`${API_BASE_URL_NOTIFICATIONS}/${id}/dismiss`, { method: 'PATCH', headers: getAuthHeaders() });
            if (handleUnauthorized(response)) return;
            if (!response.ok) throw new Error('Failed to dismiss notification');
            // Filter out the dismissed notification from the local array
            notificationsData = notificationsData.filter(n => n.id !== id); // Remove from list
            renderNotifications(); // Re-render to update UI
            console.log(`Notification ${id} dismissed.`);
        } catch (error) {
            console.error('Error dismissing notification:', error);
            alert('Gagal menyembunyikan notifikasi.');
        }
    }

    async function deleteNotification(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus notifikasi ini secara permanen?')) return; // Confirm permanent delete
        try {
            const response = await fetch(`${API_BASE_URL_NOTIFICATIONS}/${id}`, { method: 'DELETE', headers: getAuthHeaders() });
            if (handleUnauthorized(response)) return;
            if (!response.ok) throw new Error('Failed to delete notification');
            // Filter out the deleted notification from the local array
            notificationsData = notificationsData.filter(n => n.id !== id); // Remove from list
            renderNotifications(); // Re-render to update UI
            console.log(`Notification ${id} deleted.`);
        } catch (error) {
            console.error('Error deleting notification:', error);
            alert('Gagal menghapus notifikasi.');
        }
    }

    // --- Event Delegation for Notification Cards ---
    // Attaches a single click listener to the parent container
    function attachNotificationEventListeners() {
        const notificationsListContainer = document.getElementById('notifications-list-container');
        if (notificationsListContainer) {
            // Remove existing listener to prevent duplicates if function is called multiple times
            notificationsListContainer.removeEventListener('click', handleNotificationClick);
            notificationsListContainer.addEventListener('click', handleNotificationClick);
        }
    }

    // Handler for delegated events
    function handleNotificationClick(event) {
        const target = event.target;
        const button = target.closest('button'); // Find the closest button ancestor

        if (button) {
            const notificationId = button.dataset.id;
            if (!notificationId) return; // Not a button with a data-id

            if (button.classList.contains('mark-read-button')) {
                markNotificationAsRead(notificationId);
            } else if (button.classList.contains('dismiss-button')) {
                dismissNotification(notificationId);
            } else if (button.classList.contains('delete-button')) {
                deleteNotification(notificationId);
            }
        }
    }

    // --- Tab Switching Logic (from your existing notifikasi.js) ---
    // Make showTab global
    window.showTab = function(tabName) {
        // Hide all tab contents
        document.querySelectorAll('[id^="content-"]').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Remove active class from all tab buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('bg-white', 'border-b-4', 'border-blue-500');
            button.classList.add('bg-gray-50'); // Default inactive background
        });
        
        // Show selected tab content
        document.getElementById(`content-${tabName}`).classList.remove('hidden');
        
        // Add active class to selected tab button
        const selectedTabButton = document.getElementById(`tab-${tabName}`);
        if (selectedTabButton) {
            selectedTabButton.classList.remove('bg-gray-50');
            selectedTabButton.classList.add('bg-white', 'border-b-4', 'border-blue-500');
        }

        // Fetch and render data when tab is shown
        if (tabName === 'reminder') { // Assuming 'reminder' is the only tab for now
            if (!currentAuthToken) {
                console.error("Cannot fetch notifications: Auth token not available.");
                // Optionally redirect here or display a message
                return;
            }
            fetchNotifications().then(() => {
                renderNotifications();
                // Ensure event listeners are attached *after* rendering
                attachNotificationEventListeners();
            });
        }
    }
    
    // --- Initial Load on DOMContentLoaded (from your existing notifikasi.js) ---
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure authToken is set when DOM is ready
        if (!currentAuthToken) {
            console.error("Auth token not found on DOMContentLoaded in notifikasi.js.");
            // Handle this gracefully, e.g., redirect to login or show an error
        }

        // Call the showTab function to initialize the display
        window.showTab('reminder');
    });

})(); // End of IIFE