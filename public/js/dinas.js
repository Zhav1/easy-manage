// Global variables
let calendar;
let departments = [];
let positions = [];
let staffMembers = [];

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    initializeCalendar();
    loadInitialData();
    setupEventListeners();
});

// Initialize FullCalendar
function initializeCalendar() {
    const calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'id',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        height: 'auto',
        events: '/api/v1/schedules',
        eventClick: function(info) {
            openEditScheduleModal(info.event);
        },
        dateClick: function(info) {
            openAddScheduleModal(info.dateStr);
        },
        eventContent: renderEventContent
    });
    calendar.render();
}

// Load initial data from API
async function loadInitialData() {
    try {
        const [deptsResponse, posResponse, staffResponse, shiftResponse] = await Promise.all([
            fetch('/api/v1/departments'),
            fetch('/api/v1/positions'),
            fetch('/api/v1/staff'),
            fetch('/api/v1/shifts')
        ]);
        
        departments = await deptsResponse.json();
        positions = await posResponse.json();
        staffMembers = await staffResponse.json();
        console.log('Staff from API:', staffMembers);
        shifts = await shiftResponse.json();
        console.log('Shifts from API:', shifts);
        
        updateShiftDropdown();
        updateDepartmentDropdown();
        updatePositionDropdown();
        renderStaffTable();
        updateTotalStaffCount();
    } catch (error) {
        console.error('Error loading initial data:', error);
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
        option.textContent = `${shift.code} - ${formatShiftTime(shift.code)}`;
        select.appendChild(option);
    });
}

function formatShiftTime(shiftCode) {
    switch (shiftCode) {
        case 'Pagi': return '07:00 - 14:00';
        case 'Siang': return '14:00 - 21:00';
        case 'Malam': return '21:00 - 07:00';
        default: return '';
    }
}

// Setup form event listeners
function setupEventListeners() {
    // Staff Form
    document.getElementById('staffForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        await handleStaffFormSubmit();
    });
    
    // Department Form
    document.getElementById('departmentForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        await handleDepartmentFormSubmit();
    });
    
    // Position Form
    document.getElementById('positionForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        await handlePositionFormSubmit();
    });
    
    // Schedule Form
    document.getElementById('scheduleForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        await handleScheduleFormSubmit();
    });
    
    // Modal close on outside click
    document.getElementById('staffModal').addEventListener('click', function(e) {
        if (e.target === this) closeStaffModal();
    });
    
    document.getElementById('departmentModal').addEventListener('click', function(e) {
        if (e.target === this) closeDepartmentModal();
    });
    
    document.getElementById('positionModal').addEventListener('click', function(e) {
        if (e.target === this) closePositionModal();
    });
    
    document.getElementById('scheduleModal').addEventListener('click', function(e) {
        if (e.target === this) closeScheduleModal();
    });
}

// Department Modal Functions
window.openAddDepartmentModal = function() {
    document.getElementById('departmentModalTitle').textContent = 'Tambah Ruangan Baru';
    document.getElementById('departmentId').value = '';
    document.getElementById('departmentName').value = '';
    document.getElementById('departmentCode').value = '';
    document.getElementById('departmentModal').classList.remove('hidden');
    document.getElementById('departmentModal').classList.add('flex');
}

window.closeDepartmentModal = function() {
    document.getElementById('departmentModal').classList.add('hidden');
    document.getElementById('departmentModal').classList.remove('flex');
}

// Position Modal Functions
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

// Staff Modal Functions
window.openAddStaffModal = function() {
    document.getElementById('staffModalTitle').textContent = 'Tambah Staff Baru';
    document.getElementById('staffId').value = '';
    document.getElementById('staffFullName').value = '';
    document.getElementById('staffPosition').value = '';
    document.getElementById('staffDepartment').value = '';
    document.getElementById('staffStatus').value = 'Aktif';
    document.getElementById('deleteStaffBtn').classList.add('hidden');
    document.getElementById('staffModal').classList.remove('hidden');
    document.getElementById('staffModal').classList.add('flex');
}

window.openEditStaffModal = function(staffId) {
    const staff = staffMembers.find(s => s.id == staffId);
    if (!staff) return;
    
    document.getElementById('staffModalTitle').textContent = 'Edit Staff';
    document.getElementById('staffId').value = staff.id;
    document.getElementById('staffFullName').value = staff.name;
    document.getElementById('staffPosition').value = staff.position_id;
    document.getElementById('staffDepartment').value = staff.department_id;
    document.getElementById('staffStatus').value = staff.status;
    document.getElementById('deleteStaffBtn').classList.remove('hidden');
    document.getElementById('staffModal').classList.remove('hidden');
    document.getElementById('staffModal').classList.add('flex');
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
}

function openEditScheduleModal(event) {
    document.getElementById('modalTitle').textContent = 'Edit Jadwal Dinas';
    document.getElementById('eventId').value = event.id;
    document.getElementById('staffName').value = event.extendedProps.staff_id;
    document.getElementById('shiftType').value = event.extendedProps.shift;
    
    const startDate = event.start.toISOString().split('T')[0];
    const endDate = event.end ? event.end.toISOString().split('T')[0] : startDate;
    
    document.getElementById('startDate').value = startDate;
    document.getElementById('endDate').value = endDate;
    document.getElementById('deleteBtn').classList.remove('hidden');
    document.getElementById('scheduleModal').classList.remove('hidden');
    document.getElementById('scheduleModal').classList.add('flex');
}

window.closeScheduleModal = function() {
    document.getElementById('scheduleModal').classList.add('hidden');
    document.getElementById('scheduleModal').classList.remove('flex');
}

// Form Handlers
async function handleStaffFormSubmit() {
    const formData = {
        id: document.getElementById('staffId').value,
        name: document.getElementById('staffFullName').value,
        position_id: document.getElementById('staffPosition').value,
        department_id: document.getElementById('staffDepartment').value,
        status: document.getElementById('staffStatus').value
    };
    
    try {
        const url = formData.id ? `/api/v1/staff/${formData.id}` : '/api/v1/staff';
        const method = formData.id ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        });
        
        if (!response.ok) throw new Error('Network response was not ok');
        
        const result = await response.json();
        await loadInitialData(); // Refresh data
        closeStaffModal();
    } catch (error) {
        console.error('Error saving staff:', error);
        alert('Gagal menyimpan data staff');
    }
}

async function handleDepartmentFormSubmit() {
    const formData = {
        id: document.getElementById('departmentId').value,
        name: document.getElementById('departmentName').value,
        code: document.getElementById('departmentCode').value
    };
    
    try {
        const url = formData.id ? `/api/v1/departments/${formData.id}` : '/api/v1/departments';
        const method = formData.id ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        });
        
        if (!response.ok) throw new Error('Network response was not ok');
        
        const result = await response.json();
        await loadInitialData(); // Refresh data
        closeDepartmentModal();
    } catch (error) {
        console.error('Error saving department:', error);
        alert('Gagal menyimpan data ruangan');
    }
}

async function handlePositionFormSubmit() {
    const formData = {
        id: document.getElementById('positionId').value,
        name: document.getElementById('positionName').value,
        description: document.getElementById('positionDescription').value
    };
    
    try {
        const url = formData.id ? `/api/v1/positions/${formData.id}` : '/api/v1/positions';
        const method = formData.id ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        });
        
        if (!response.ok) throw new Error('Network response was not ok');
        
        const result = await response.json();
        await loadInitialData(); // Refresh data
        closePositionModal();
    } catch (error) {
        console.error('Error saving position:', error);
        alert('Gagal menyimpan data jabatan');
    }
}

async function handleScheduleFormSubmit() {
    const formData = {
        id: document.getElementById('eventId').value,
        staff_id: document.getElementById('staffName').value,
        shift_id: document.getElementById('shiftType').value,
        start: document.getElementById('startDate').value,
        end: document.getElementById('endDate').value
    };
    
    try {
        const url = formData.id ? `/api/v1/schedules/${formData.id}` : '/api/v1/schedules';
        const method = formData.id ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        });
        
        if (!response.ok) throw new Error('Network response was not ok');
        
        calendar.refetchEvents(); // Refresh calendar
        closeScheduleModal();
    } catch (error) {
        console.error('Error saving schedule:', error);
        alert('Gagal menyimpan jadwal dinas');
    }
}

// Delete Functions
window.deleteStaff = async function() {
    const staffId = document.getElementById('staffId').value;
    if (!staffId || !confirm('Apakah Anda yakin ingin menghapus staff ini?')) return;
    
    try {
        const response = await fetch(`/api/v1/staff/${staffId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (!response.ok) throw new Error('Network response was not ok');
        
        await loadInitialData(); // Refresh data
        closeStaffModal();
    } catch (error) {
        console.error('Error deleting staff:', error);
        alert('Gagal menghapus staff');
    }
}

window.deleteEvent = async function() {
    const eventId = document.getElementById('eventId').value;
    if (!eventId || !confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) return;
    
    try {
        const response = await fetch(`/api/v1/schedules/${eventId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (!response.ok) throw new Error('Network response was not ok');
        
        calendar.refetchEvents(); // Refresh calendar
        closeScheduleModal();
    } catch (error) {
        console.error('Error deleting schedule:', error);
        alert('Gagal menghapus jadwal');
    }
}

// Render Functions
function renderStaffTable() {
   console.log('Rendering staff table...', staffMembers);
    const tbody = document.getElementById('staffTableBody');
    tbody.innerHTML = '';
    
    staffMembers.forEach((staff, index) => {
        const department = departments.find(d => d.id == staff.department_id) || {};
        const position = positions.find(p => p.id == staff.position_id) || {};
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${staff.name}</td>
            <td>${position.name || '-'}</td>
            <td>${department.name || '-'}</td>
            <td>
                <span class="px-2 py-1 rounded-full text-xs ${
                    staff.status === 'Aktif' ? 'bg-green-100 text-green-800' : 
                    staff.status === 'Cuti' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'
                }">
                    ${staff.status}
                </span>
            </td>
            <td class="flex space-x-2">
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
    
    updateStaffDropdown();
}

function updateStaffDropdown() {
    const staffSelect = document.getElementById('staffName');
    staffSelect.innerHTML = '<option value="">Pilih Staff</option>';
    staffMembers.forEach(staff => {
        const option = document.createElement('option');
        option.value = staff.id;
        option.textContent = staff.name;
        staffSelect.appendChild(option);
    });
}

function updateDepartmentDropdown() {
    const deptSelect = document.getElementById('staffDepartment');
    deptSelect.innerHTML = '<option value="">Pilih Ruangan</option>';
    
    departments.forEach(dept => {
        const option = document.createElement('option');
        option.value = dept.id;
        option.textContent = dept.name;
        deptSelect.appendChild(option);
    });
}

function updatePositionDropdown() {
    const posSelect = document.getElementById('staffPosition');
    posSelect.innerHTML = '<option value="">Pilih Jabatan</option>';
    
    positions.forEach(pos => {
        const option = document.createElement('option');
        option.value = pos.id;
        option.textContent = pos.name;
        posSelect.appendChild(option);
    });
}

function updateTotalStaffCount() {
    document.getElementById('totalStaffCount').textContent = staffMembers.length;
}

function renderEventContent(arg) {
    const shiftBadge = document.createElement('div');
    shiftBadge.classList.add('shift-badge');

    
    if (arg.event.extendedProps.shift === 'Pagi') {
        shiftBadge.classList.add('shift-pagi');
        shiftBadge.innerHTML = `${arg.event.extendedProps.staff_name} (P)`;
    } else if (arg.event.extendedProps.shift === 'Siang') {
        shiftBadge.classList.add('shift-sore');
        shiftBadge.innerHTML = `${arg.event.extendedProps.staff_name} (S)`;
    } else {
        shiftBadge.classList.add('shift-malam');
        shiftBadge.innerHTML = `${arg.event.extendedProps.staff_name} (M)`;
    }
    
    return { domNodes: [shiftBadge] };
}

// Confirmation Dialog
window.confirmDeleteStaff = function(staffId) {
    if (confirm('Apakah Anda yakin ingin menghapus staff ini?')) {
        openEditStaffModal(staffId);
        document.getElementById('deleteStaffBtn').classList.remove('hidden');
    }
};