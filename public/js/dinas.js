// Global variables
let calendar;
let departments = [];
let positions = [];
let staffMembers = [];
let shifts = []; 
let userInfo = {}; 

// --- Global Loading Functions (for HCI principle) ---
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


// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    loadInitialData();
    initializeCalendar();
    setupEventListeners();
});

//initialize calendar
function initializeCalendar() {
    const calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek', // <--- IMPORTANT: Default to weekly time grid view
        locale: 'id',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay' // Allows users to switch views
        },
        height: 'auto', // Adjusts height to fit content, or use a fixed height
        
        // --- FullCalendar Time-Grid Specific Options ---
        slotMinTime: '00:00:00', // Start time displayed on the calendar grid (e.g., midnight)
        slotMaxTime: '24:00:00', // End time displayed (24:00:00 covers up to midnight of the next day)
        slotDuration: '01:00:00', // Interval of time slots (e.g., every hour)
        allDaySlot: false, // Hide the "all-day" section at the top of time-grid views
        nowIndicator: true, // Show a red line for the current time
        
        // --- Interactivity (ALL SET TO FALSE TO SIMPLIFY) ---
        editable: false, 
        eventStartEditable: false,
        eventDurationEditable: false,
        selectable: true, // Keep selectable for dateClick to add events
        selectMirror: false, // No mirror image on selection
        
        // --- Event Source and Callbacks ---
        events: async function(fetchInfo, successCallback, failureCallback) {
            showLoading();
            const token = window.authToken || document.getElementById('auth_token')?.value;
            if (!token) {
                console.error('Bearer token missing');
                hideLoading();
                return failureCallback('Token is missing');
            }

            try {
                const response = await fetch('/api/v1/schedules', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    console.error('Failed to fetch events response:', errorData);
                    throw new Error(errorData.message || 'Failed to fetch events');
                }

                const data = await response.json();
                console.log('Events received from API:', data); // IMPORTANT: Check this log!
                successCallback(data);
            } catch (error) {
                console.error('Error fetching events:', error);
                failureCallback(error);
            } finally {
                hideLoading();
            }
        },
        eventClick: function(info) {
            openEditScheduleModal(info.event);
        },
        dateClick: function(info) {
            // For timeGrid views, info.dateStr includes time (e.g., "2025-07-11T14:00:00")
            // We only want the date part for schedule creation in your current modal
            const clickedDate = info.dateStr.split('T')[0];
            openAddScheduleModal(clickedDate);
        },
        eventContent: renderEventContent // Custom rendering for events
    });

    calendar.render();
}


// Load initial data from API
async function loadInitialData() {
    showLoading();
    try {
        const token = window.authToken || document.getElementById('auth_token')?.value;
        console.log('Current Auth Token:', token ? 'Token exists' : 'Token is missing!'); 
        if (!token) {
            hideLoading();
            throw new Error('No authentication token found');
        }
        
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        };
        
        const userInfoResponse = await fetch('/api/v1/user/info', {headers});
        if (!userInfoResponse.ok) {
            const errorData = await userInfoResponse.json();
            throw new Error(errorData.message || 'Failed to fetch user info');
        }
        userInfo = await userInfoResponse.json();
        console.log('User Info from API:', userInfo);

        const [deptsResponse, posResponse, staffResponse, shiftResponse] = await Promise.all([
            fetch('/api/v1/departments', {headers}),
            fetch('/api/v1/positions', {headers}),
            fetch('/api/v1/staff', {headers}), 
            fetch('/api/v1/shifts', {headers})
        ]);
        
        departments = await deptsResponse.json();
        positions = await posResponse.json();
        console.log('Positions from API:', positions);
        
        staffMembers = await staffResponse.json(); 
        console.log('Staff from API (should be filtered by backend StaffController):', staffMembers);
        
        shifts = await shiftResponse.json();
        console.log('Shifts from API:', shifts);

        updateStaffDropdown();
        updatePositionDropdown();
        updateShiftDropdown();
        renderStaffTable(); 
        updateTotalStaffCount(); 
    } catch (error) {
        console.error('Error loading initial data:', error);
        alert('Gagal memuat data awal: ' + error.message);
    } finally {
        hideLoading();
    }
}

function updateShiftDropdown() {
    const select = document.getElementById('shiftType');
    if (!select) {
        console.warn('Element #shiftType not found!');
        return;
    }

    select.innerHTML = '<option value="">Pilih Shift</option>';

    shifts.forEach(shift => {
        const option = document.createElement('option');
        option.value = shift.id;
        // Display format includes times from your shifts table
        option.textContent = `${shift.code} (${shift.start.substring(0, 5)} - ${shift.end.substring(0, 5)})`;
        select.appendChild(option);
    });
}

function formatShiftTime(shiftCode) {
    // This helper is used by your previous updateShiftDropdown and eventContent, keep it here.
    const shift = shifts.find(s => s.code === shiftCode);
    if (shift) {
        return `${shift.start.substring(0, 5)} - ${shift.end.substring(0, 5)}`;
    }
    return '';
}

// Setup form event listeners
function setupEventListeners() {
    document.getElementById('staffForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        await handleStaffFormSubmit();
    });
    
    document.getElementById('positionForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        await handlePositionFormSubmit();
    });
    
    document.getElementById('scheduleForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        await handleScheduleFormSubmit();
    });
    
    document.getElementById('staffModal').addEventListener('click', function(e) {
        if (e.target === this) closeStaffModal();
    });
    
    document.getElementById('positionModal').addEventListener('click', function(e) {
        if (e.target === this) closePositionModal();
    });
    
    document.getElementById('scheduleModal').addEventListener('click', function(e) {
        if (e.target === this) closeScheduleModal();
    });
}

// Position Modal Functions (no changes)
window.openAddPositionModal = function() {
    document.getElementById('positionModalTitle').textContent = 'Tambah Jabatan Baru';
    document.getElementById('positionId').value = '';
    document.getElementById('positionName').value = '';
    document.getElementById('positionDescription').value = '';
    document.getElementById('positionModal').classList.remove('hidden');
    document.getElementById('positionModal').classList.add('flex');
}

window.closePositionModal = function() {
    document.getElementById('positionModal').classList.add('hidden');
    document.getElementById('positionModal').classList.remove('flex');
}

// Staff Modal Functions (no changes)
window.openAddStaffModal = function() {
    document.getElementById('staffModalTitle').textContent = 'Tambah Staff Baru';
    document.getElementById('staffId').value = '';
    document.getElementById('staffFullName').value = '';
    document.getElementById('staffPosition').value = '';
    document.getElementById('staffStatus').value = 'Aktif';
    document.getElementById('deleteStaffBtn').classList.add('hidden');
    document.getElementById('staffModal').classList.remove('hidden');
    document.getElementById('staffModal').classList.add('flex');
    updatePositionDropdown();
}

window.openEditStaffModal = function(staffId) {
    const staff = staffMembers.find(s => s.id == staffId);
    if (!staff) return;
    
    document.getElementById('staffModalTitle').textContent = 'Edit Staff';
    document.getElementById('staffId').value = staff.id;
    document.getElementById('staffFullName').value = staff.name;
    document.getElementById('staffPosition').value = staff.position_id;
    document.getElementById('staffStatus').value = staff.status;
    document.getElementById('deleteStaffBtn').classList.remove('hidden');
    document.getElementById('staffModal').classList.remove('hidden');
    document.getElementById('staffModal').classList.add('flex');
    updatePositionDropdown();
}

window.closeStaffModal = function() {
    document.getElementById('staffModal').classList.add('hidden');
    document.getElementById('staffModal').classList.remove('flex');
}

// Schedule Modal Functions
function openAddScheduleModal(dateStr) {
    document.getElementById('modalTitle').textContent = 'Tambah Jadwal Dinas';
    document.getElementById('eventId').value = '';
    document.getElementById('staffName').value = '';
    document.getElementById('shiftType').value = '';
    document.getElementById('startDate').value = dateStr;
    document.getElementById('endDate').value = dateStr;
    document.getElementById('deleteBtn').classList.add('hidden');
    document.getElementById('scheduleModal').classList.remove('hidden');
    document.getElementById('scheduleModal').classList.add('flex');
    updateStaffDropdown();
    updateShiftDropdown();
}

function openEditScheduleModal(event) {
    document.getElementById('modalTitle').textContent = 'Edit Jadwal Dinas';
    document.getElementById('eventId').value = event.id;
    document.getElementById('staffName').value = event.extendedProps.staff_id;
    // Set shiftType using the shift_id now stored in extendedProps
    document.getElementById('shiftType').value = event.extendedProps.shift_id; 
    
    // Using moment.js to correctly format dates for the input fields
    const startDate = moment(event.start).format('YYYY-MM-DD');
    let endDate = moment(event.end).format('YYYY-MM-DD');

    // Adjust endDate for 'Malam' shifts for the form input
    // If the shift is 'Malam' and the FullCalendar event's end date is actually the next day,
    // revert the 'endDate' for the form input to be the same as 'startDate'
    if (event.extendedProps.shift_code === 'Malam' && moment(event.start).date() !== moment(event.end).date()) {
        endDate = startDate;
    }
    
    document.getElementById('startDate').value = startDate;
    document.getElementById('endDate').value = endDate;
    document.getElementById('deleteBtn').classList.remove('hidden');
    document.getElementById('scheduleModal').classList.remove('hidden');
    document.getElementById('scheduleModal').classList.add('flex');
    updateStaffDropdown();
    updateShiftDropdown();
}

window.closeScheduleModal = function() {
    document.getElementById('scheduleModal').classList.add('hidden');
    document.getElementById('scheduleModal').classList.remove('flex');
}

// Form Handlers (no changes to core logic, improved error alerts)
async function handleStaffFormSubmit() {
    showLoading();
    const formData = {
        id: document.getElementById('staffId').value,
        name: document.getElementById('staffFullName').value,
        position_id: document.getElementById('staffPosition').value,
        user_id: userInfo.id,
        department_id: document.getElementById('staffDepartment').value,
        hospital_id: document.getElementById('staffHospital').value,
        status: document.getElementById('staffStatus').value
    };
    
    try {
        const token = window.authToken || document.getElementById('auth_token')?.value;
        if (!token) throw new Error('No authentication token found');
        
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        };

        const url = formData.id ? `/api/v1/staff/${formData.id}` : '/api/v1/staff';
        const method = formData.id ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers,
            body: JSON.stringify(formData)
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || response.statusText);
        }
        
        await loadInitialData();
        closeStaffModal();
    } catch (error) {
        console.error('Error saving staff:', error);
        alert('Gagal menyimpan data staff: ' + error.message);
    } finally {
        hideLoading();
    }
}

async function handlePositionFormSubmit() {
    showLoading();
    const formData = {
        id: document.getElementById('positionId').value,
        name: document.getElementById('positionName').value,
        description: document.getElementById('positionDescription').value
    };
    
    try {
        const token = window.authToken || document.getElementById('auth_token')?.value;
        if (!token) throw new Error('No authentication token found');
        
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        };
        const url = formData.id ? `/api/v1/positions/${formData.id}` : '/api/v1/positions';
        const method = formData.id ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers,
            body: JSON.stringify(formData)
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || response.statusText);
        }
        
        await loadInitialData();
        closePositionModal();
    } catch (error) {
        console.error('Error saving position:', error);
        alert('Gagal menyimpan data jabatan: ' + error.message);
    } finally {
        hideLoading();
    }
}

async function handleScheduleFormSubmit() {
    showLoading();
    const formData = {
        id: document.getElementById('eventId').value,
        staff_id: document.getElementById('staffName').value,
        shift_id: document.getElementById('shiftType').value,
        start: document.getElementById('startDate').value, // YYYY-MM-DD
        end: document.getElementById('endDate').value      // YYYY-MM-DD
    };
    
    try {
        const token = window.authToken || document.getElementById('auth_token')?.value;
        if (!token) throw new Error('No authentication token found');
        
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        };
        const url = formData.id ? `/api/v1/schedules/${formData.id}` : '/api/v1/schedules';
        const method = formData.id ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers,
            body: JSON.stringify(formData)
        });
        
        if (!response.ok) {
             const errorData = await response.json();
             throw new Error(errorData.message || response.statusText);
        }
        
        calendar.refetchEvents();
        closeScheduleModal();
    } catch (error) {
        console.error('Error saving schedule:', error);
        alert('Gagal menyimpan jadwal dinas: ' + error.message);
    } finally {
        hideLoading();
    }
}

// Delete Functions
window.deleteStaff = async function() {
    showLoading();
    const token = window.authToken || document.getElementById('auth_token')?.value;
    if (!token) {
        hideLoading();
        throw new Error('No authentication token found');
    }
        
    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
    };
    const staffId = document.getElementById('staffId').value;
    if (!staffId || !confirm('Apakah Anda yakin ingin menghapus staff ini?')) {
        hideLoading();
        return;
    }
    
    try {
        const response = await fetch(`/api/v1/staff/${staffId}`, {
            method: 'DELETE',
            headers
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || response.statusText);
        }
        
        await loadInitialData();
        closeStaffModal();
    } catch (error) {
        console.error('Error deleting staff:', error);
        alert('Gagal menghapus staff: ' + error.message);
    } finally {
        hideLoading();
    }
}

window.deleteEvent = async function() {
    showLoading();
    const token = window.authToken || document.getElementById('auth_token')?.value;
    if (!token) {
        hideLoading();
        throw new Error('No authentication token found');
    }
        
    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
    };
    const eventId = document.getElementById('eventId').value;
    if (!eventId || !confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
        hideLoading();
        return;
    }
    
    try {
        const response = await fetch(`/api/v1/schedules/${eventId}`, {
            method: 'DELETE',
            headers
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || response.statusText);
        }
        
        calendar.refetchEvents();
        closeScheduleModal();
    } catch (error) {
        console.error('Error deleting schedule:', error);
        alert('Gagal menghapus jadwal: ' + error.message);
    } finally {
        hideLoading();
    }
}

// Render Functions (no changes)
function renderStaffTable() {
    console.log('Rendering staff table with:', staffMembers);
    const tbody = document.getElementById('staffTableBody');
    
    if (!tbody) {
        console.error('Staff table body element not found!');
        return;
    }

    tbody.innerHTML = '';
    
    if (!staffMembers || staffMembers.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = `<td colspan="6" class="text-center py-4">Tidak ada data staff untuk user ini.</td>`;
        tbody.appendChild(row);
        return;
    }
    
    staffMembers.forEach((staff, index) => {
        const department = departments.find(d => d.id == staff.department_id) || {};
        const position = positions.find(p => p.id == staff.position_id) || {};
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-4 py-2">${index + 1}</td>
            <td class="px-4 py-2">${staff.name || '-'}</td>
            <td class="px-4 py-2">${position.name || '-'}</td>
            <td class="px-4 py-2">${department.name || '-'}</td>
            <td class="px-4 py-2">
                <span class="px-2 py-1 rounded-full text-xs ${
                    staff.status === 'Aktif' ? 'bg-green-100 text-green-800' : 
                    staff.status === 'Cuti' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'
                }">
                    ${staff.status || '-'}
                </span>
            </td>
            <td class="px-4 py-2 flex space-x-2">
                <button onclick="openEditStaffModal(${staff.id})" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-edit"></i>
                </button>
                <button onclick="confirmDeleteStaff(${staff.id})" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function updateStaffDropdown() {
    const staffSelect = document.getElementById('staffName');
    if (!staffSelect) {
        console.warn('Element #staffName not found!');
        return;
    }
    staffSelect.innerHTML = '<option value="">Pilih Staff</option>';
    staffMembers.forEach(staff => {
        const option = document.createElement('option');
        option.value = staff.id;
        option.textContent = staff.name;
        staffSelect.appendChild(option);
    });
}

function updatePositionDropdown() {
    const posSelect = document.getElementById('staffPosition');
    if (!posSelect) {
        console.error('Position dropdown element not found!');
        return;
    }

    posSelect.innerHTML = '<option value="">Pilih Jabatan</option>';
    if (!positions || !Array.isArray(positions)) {
        console.error('Positions data is invalid:', positions);
        return;
    }

    positions.forEach(pos => {
        if (!pos.id || !pos.name) {
            console.warn('Invalid position data:', pos);
            return;
        }
        const option = document.createElement('option');
        option.value = pos.id;
        option.textContent = pos.name;
        posSelect.appendChild(option);
    });
}

function updateTotalStaffCount() {
    const totalStaffCountElem = document.getElementById('totalStaffCount');
    if (totalStaffCountElem) {
        totalStaffCountElem.textContent = staffMembers.length;
    }
}

function renderEventContent(arg) {
    const shiftBadge = document.createElement('div');
    shiftBadge.classList.add('shift-badge');
    const shiftCode = arg.event.extendedProps.shift_code || '';
    const staffName = arg.event.extendedProps.staff_name || '';

    // Apply the specific shift class to the badge
    // Your CSS for .shift-pagi, .shift-sore, .shift-malam will style this.
    if (shiftCode === 'Pagi') {
        shiftBadge.classList.add('shift-pagi');
    } else if (shiftCode === 'Siang') {
        shiftBadge.classList.add('shift-sore');
    } else if (shiftCode === 'Malam') {
        shiftBadge.classList.add('shift-malam');
    } else {
        shiftBadge.classList.add('shift-other'); // Fallback if code doesn't match
    }

    shiftBadge.innerHTML = `${staffName} (${shiftCode.charAt(0).toUpperCase()})`;
    
    // Remove any inline style.color we added previously if not needed
    shiftBadge.style.color = ''; // Reset, let CSS handle it
    shiftBadge.style.backgroundColor = ''; // Reset, let CSS handle it
    shiftBadge.style.borderColor = ''; // Reset, let CSS handle it
    
    return { domNodes: [shiftBadge] };
}

// Confirmation Dialog
window.confirmDeleteStaff = function(staffId) {
    if (confirm('Apakah Anda yakin ingin menghapus staff ini?')) {
        document.getElementById('staffId').value = staffId;
        deleteStaff(); 
    }
};

// IMPORTANT: Ensure moment.js is included in your Blade file BEFORE dinas.js
// <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>