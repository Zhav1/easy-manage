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

function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const template = document.getElementById('toast-template');
    if (!container || !template) return;

    const newToast = template.cloneNode(true);
    newToast.id = '';
    newToast.classList.remove('hidden');
    newToast.classList.add('flex');

    const iconDiv = newToast.querySelector('#toast-icon');
    const messageDiv = newToast.querySelector('#toast-message');
    messageDiv.textContent = message;

    if (type === 'success') {
        iconDiv.innerHTML = '<i class="fas fa-check"></i>';
        iconDiv.className = 'inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg';
    } else if (type === 'error') {
        iconDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
        iconDiv.className = 'inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg';
    } else { // Info
        iconDiv.innerHTML = '<i class="fas fa-info-circle"></i>';
        iconDiv.className = 'inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg';
    }

    container.appendChild(newToast);

    setTimeout(() => {
        newToast.style.transition = 'opacity 0.5s ease';
        newToast.style.opacity = '0';
        setTimeout(() => newToast.remove(), 500);
    }, 5000);
}

function showConfirmationModal({ title, message, confirmText = 'Ya, Hapus', cancelText = 'Batal' }) {
    const modal = document.getElementById('confirmationModal');
    const modalBox = document.getElementById('confirmationModalBox');
    const titleEl = document.getElementById('confirmationTitle');
    const messageEl = document.getElementById('confirmationMessage');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    const cancelBtn = document.getElementById('confirmCancelBtn');

    titleEl.textContent = title;
    messageEl.innerHTML = message;
    confirmBtn.textContent = confirmText;
    cancelBtn.textContent = cancelText;

    modal.classList.remove('hidden');
    setTimeout(() => {
       modalBox.classList.remove('scale-95', 'opacity-0');
       modalBox.classList.add('scale-100', 'opacity-100');
    }, 10);

    return new Promise((resolve) => {
        confirmBtn.onclick = () => {
            modal.classList.add('hidden');
            modalBox.classList.add('scale-95', 'opacity-0');
            resolve(true);
        };
        cancelBtn.onclick = () => {
            modal.classList.add('hidden');
            modalBox.classList.add('scale-95', 'opacity-0');
            resolve(false);
        };
        modal.onclick = (e) => {
             if(e.target === modal) {
                modal.classList.add('hidden');
                modalBox.classList.add('scale-95', 'opacity-0');
                resolve(false);
             }
        }
    });
}


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

function cacheDinasData(data) {
    sessionStorage.setItem('prefetched_dinas_userInfo', JSON.stringify(data.userInfo));
    sessionStorage.setItem('prefetched_dinas_departments', JSON.stringify(data.departments));
    sessionStorage.setItem('prefetched_dinas_positions', JSON.stringify(data.positions));
    sessionStorage.setItem('prefetched_dinas_staff', JSON.stringify(data.staffMembers));
    sessionStorage.setItem('prefetched_dinas_shifts', JSON.stringify(data.shifts));
    console.log('✅ Dinas page data has been cached.');
}


async function loadInitialData() {
    showLoading();
    
    // **MODIFIED**: Check for cached data first
    const cachedUserInfo = sessionStorage.getItem('prefetched_dinas_userInfo');
    const cachedDepts = sessionStorage.getItem('prefetched_dinas_departments');
    const cachedPos = sessionStorage.getItem('prefetched_dinas_positions');
    const cachedStaff = sessionStorage.getItem('prefetched_dinas_staff');
    const cachedShifts = sessionStorage.getItem('prefetched_dinas_shifts');
    
    if (cachedUserInfo && cachedDepts && cachedPos && cachedStaff && cachedShifts) {
        console.log('⚡️ Loading Dinas data from cache.');
        userInfo = JSON.parse(cachedUserInfo);
        departments = JSON.parse(cachedDepts);
        positions = JSON.parse(cachedPos);
        staffMembers = JSON.parse(cachedStaff);
        shifts = JSON.parse(cachedShifts);
        
        // Render UI with cached data
        updateStaffDropdown();
        updatePositionDropdown();
        updateShiftDropdown();
        renderStaffTable();
        updateTotalStaffCount();
        hideLoading();
        return; // Exit function, no need to fetch from API
    }
    
    // Fallback: If no cache, fetch from API
    console.log('No cache found. Fetching Dinas data from API...');
    try {
        const token = window.authToken;
        if (!token) throw new Error('No authentication token found');
        
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        };
        
        const [userInfoResponse, deptsResponse, posResponse, staffResponse, shiftResponse] = await Promise.all([
            fetch('/api/v1/user/info', {headers}),
            fetch('/api/v1/departments', {headers}),
            fetch('/api/v1/positions', {headers}),
            fetch('/api/v1/staff', {headers}), 
            fetch('/api/v1/shifts', {headers})
        ]);
        
        userInfo = await userInfoResponse.json();
        departments = await deptsResponse.json();
        positions = await posResponse.json();
        staffMembers = await staffResponse.json(); 
        shifts = await shiftResponse.json();

        // **NEW**: Cache the freshly fetched data
        cacheDinasData({ userInfo, departments, positions, staffMembers, shifts });

        // Render UI
        updateStaffDropdown();
        updatePositionDropdown();
        updateShiftDropdown();
        renderStaffTable(); 
        updateTotalStaffCount(); 
    } catch (error) {
        console.error('Error loading initial data:', error);
        showToast('Gagal memuat data awal: ' + error.message, 'error');
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
        const token = window.authToken;
        const headers = { 'Accept': 'application/json', 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` };
        const url = formData.id ? `/api/v1/staff/${formData.id}` : '/api/v1/staff';
        const method = formData.id ? 'PUT' : 'POST';
        
        const response = await fetch(url, { method, headers, body: JSON.stringify(formData) });
        if (!response.ok) throw new Error((await response.json()).message || 'Gagal menyimpan data');
        
        const updatedStaffResponse = await fetch('/api/v1/staff', { headers });
        staffMembers = await updatedStaffResponse.json();
        sessionStorage.setItem('prefetched_dinas_staff', JSON.stringify(staffMembers));
        
        renderStaffTable();
        updateStaffDropdown();
        updateTotalStaffCount();
        closeStaffModal();
        showToast('Data staff berhasil disimpan!', 'success');
    } catch (error) {
        showToast(`Gagal menyimpan data staff: ${error.message}`, 'error');
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
        const token = window.authToken;
        const headers = { 'Accept': 'application/json', 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` };
        const url = formData.id ? `/api/v1/positions/${formData.id}` : '/api/v1/positions';
        const method = formData.id ? 'PUT' : 'POST';
        
        const response = await fetch(url, { method, headers, body: JSON.stringify(formData) });
        if (!response.ok) throw new Error((await response.json()).message || 'Gagal menyimpan data');
        
        // Reload all data since positions can affect staff rendering
        await loadInitialData(true); // Force refresh from API
        closePositionModal();
        showToast('Data jabatan berhasil disimpan!', 'success');
    } catch (error) {
        showToast(`Gagal menyimpan data jabatan: ${error.message}`, 'error');
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
        start: document.getElementById('startDate').value,
        end: document.getElementById('endDate').value
    };

    if (!formData.staff_id || !formData.shift_id) {
        showToast('Harap pilih staff dan jenis shift.', 'error');
        hideLoading();
        return;
    }
    
    try {
        const token = window.authToken;
        const headers = { 'Accept': 'application/json', 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` };
        const url = formData.id ? `/api/v1/schedules/${formData.id}` : '/api/v1/schedules';
        const method = formData.id ? 'PUT' : 'POST';
        
        const response = await fetch(url, { method, headers, body: JSON.stringify(formData) });
        if (!response.ok) throw new Error((await response.json()).message || 'Gagal menyimpan jadwal');
        
        calendar.refetchEvents();
        closeScheduleModal();
        showToast('Jadwal dinas berhasil disimpan!', 'success');
    } catch (error) {
        showToast(`Gagal menyimpan jadwal: ${error.message}`, 'error');
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
                <button onclick="deleteStaff(${staff.id})" class="text-red-600 hover:text-red-800">
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
// --- REFACTORED Delete Functions ---
async function deleteStaff(staffId) {
    const staff = staffMembers.find(s => s.id === staffId);
    if (!staff) {
        showToast('Staff tidak ditemukan.', 'error');
        return;
    }

    const isConfirmed = await showConfirmationModal({
        title: 'Konfirmasi Hapus Staff',
        message: `Anda akan menghapus staff: <strong>${staff.name}</strong>. Semua jadwal dinas terkait juga akan dihapus. <br><br> Tindakan ini tidak dapat dibatalkan.`
    });

    if (!isConfirmed) return;

    showLoading();
    try {
        const token = window.authToken;
        const headers = { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` };
        const response = await fetch(`/api/v1/staff/${staffId}`, { method: 'DELETE', headers });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Gagal menghapus data');
        }

        // Refetch staff data and update cache
        const updatedStaffResponse = await fetch('/api/v1/staff', { headers });
        staffMembers = await updatedStaffResponse.json();
        sessionStorage.setItem('prefetched_dinas_staff', JSON.stringify(staffMembers));

        renderStaffTable();
        updateStaffDropdown();
        updateTotalStaffCount();
        calendar.refetchEvents(); // Also refetch events as schedules might be deleted
        closeStaffModal();
        showToast('Data staff berhasil dihapus!', 'success');
    } catch (error) {
        showToast(`Gagal menghapus staff: ${error.message}`, 'error');
    } finally {
        hideLoading();
    }
}

async function deleteEvent() {
    const eventId = document.getElementById('eventId').value;
    if (!eventId) return;
    
    const event = calendar.getEventById(eventId);
    const staffName = event ? event.extendedProps.staff_name : "jadwal ini";

    const isConfirmed = await showConfirmationModal({
        title: 'Konfirmasi Hapus Jadwal',
        message: `Anda yakin ingin menghapus jadwal dinas untuk <strong>${staffName}</strong>?`
    });

    if (!isConfirmed) return;

    showLoading();
    try {
        const token = window.authToken;
        const headers = { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` };
        const response = await fetch(`/api/v1/schedules/${eventId}`, { method: 'DELETE', headers });
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Gagal menghapus jadwal');
        }
        
        calendar.refetchEvents();
        closeScheduleModal();
        showToast('Jadwal dinas berhasil dihapus.', 'success');
    } catch (error) {
        showToast(`Gagal menghapus jadwal: ${error.message}`, 'error');
    } finally {
        hideLoading();
    }
}