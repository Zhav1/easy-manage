document.addEventListener('DOMContentLoaded', () => {
    const token = window.authToken;
    if (!token) {
        console.error('Auth token not found');
        return;
    }

    const API_BASE = '/api/v1/private-schedules';
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
    };

    // Form elements
    const form = document.getElementById('privateScheduleForm');
    const submitBtn = document.getElementById('submitScheduleBtn');
    const tableBody = document.getElementById('scheduleTableBody');
    

    // Modal elements
    const modalForm = document.getElementById('modalPrivateScheduleForm');
    const scheduleModal = document.getElementById('scheduleModal');
    const modalTitle = document.getElementById('modalTitle');
    const deleteBtn = document.getElementById('deleteBtn');
    const modalSubmitBtn = document.getElementById('modalSubmitBtn');
    
    // Initial load
    loadPrivateSchedules();

    // Event listener for button click
    submitBtn.addEventListener('click', async (e) => {
        e.preventDefault();
        await handleFormSubmission();
    });

    // Modal close handlers
    scheduleModal.addEventListener('click', function(e) {
        if (e.target === this) closeScheduleModal();
    });

    async function handleFormSubmission() {
        const data = getFormData();
        
        if (!data.scheduled_at) {
            alert('Waktu tidak boleh kosong');
            return;
        }

        try {
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';

            const response = await fetch(API_BASE, {
                method: 'POST',
                headers,
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Gagal menyimpan');
            }

            const result = await response.json();
            renderScheduleRow(result);
            resetForm();
            
        } catch (error) {
            console.error('Error:', error);
            alert(error.message || 'Terjadi kesalahan');
        } finally {
            // Reset button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-3"></i>Simpan Catatan';
        }
    }

    function getFormData() {
        return {
            scheduled_at: form.querySelector('[name="scheduled_at"]').value,
            briefing: form.querySelector('[name="briefing"]').value === '1',
            meeting: form.querySelector('[name="meeting"]').value === '1',
            supervision: form.querySelector('[name="supervision"]').value === '1',
            handover: form.querySelector('[name="handover"]').value === '1',
            external_task: form.querySelector('[name="external_task"]').value,
            note: form.querySelector('[name="note"]').value
        };
    }

    async function loadPrivateSchedules() {
        try {
            const response = await fetch(API_BASE, { headers });
            if (!response.ok) throw new Error('Gagal mengambil data');
            
            const schedules = await response.json();
            renderSchedules(schedules);
        } catch (error) {
            console.error('Error loading schedules:', error);
            renderEmptyState();
        }
    }

    function renderSchedules(schedules) {
        tableBody.innerHTML = '';
        
        if (!schedules || schedules.length === 0) {
            renderEmptyState();
            return;
        }

        // Sort by date (newest first)
        schedules.sort((a, b) => new Date(b.scheduled_at) - new Date(a.scheduled_at));
        
        schedules.forEach(schedule => {
            renderScheduleRow(schedule);
        });
    }

    function renderScheduleRow(schedule) {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50 transition-colors duration-200';
        row.dataset.id = schedule.id;

        row.innerHTML = `
            <td class="px-4 py-4 whitespace-nowrap text-gray-500">${formatDateTime(schedule.scheduled_at)}</td>
            <td class="px-4 py-4 whitespace-nowrap text-gray-500">
                ${schedule.briefing ? '<span class="status-badge bg-amber-100 text-amber-800"><i class="fas fa-check mr-1"></i>Ya</span>' : '<span class="status-badge bg-gray-100 text-gray-600"><i class="fas fa-times mr-1"></i>Tidak</span>'}
            </td>
            <td class="px-4 py-4 whitespace-nowrap text-gray-500">
                ${schedule.meeting ? '<span class="status-badge bg-amber-100 text-amber-800"><i class="fas fa-check mr-1"></i>Ya</span>' : '<span class="status-badge bg-gray-100 text-gray-600"><i class="fas fa-times mr-1"></i>Tidak</span>'}
            </td>
            <td class="px-4 py-4 whitespace-nowrap text-gray-500">
                ${schedule.supervision ? '<span class="status-badge bg-amber-100 text-amber-800"><i class="fas fa-check mr-1"></i>Ya</span>' : '<span class="status-badge bg-gray-100 text-gray-600"><i class="fas fa-times mr-1"></i>Tidak</span>'}
            </td>
            <td class="px-4 py-4 whitespace-nowrap text-gray-500">
                ${schedule.handover ? '<span class="status-badge bg-amber-100 text-amber-800"><i class="fas fa-check mr-1"></i>Ya</span>' : '<span class="status-badge bg-gray-100 text-gray-600"><i class="fas fa-times mr-1"></i>Tidak</span>'}
            </td>
            <td class="px-4 py-4 text-gray-500">${schedule.external_task || '-'}</td>
            <td class="px-4 py-4 min-w-[300px] max-w-[500px] break-words text-gray-500">${schedule.note || '-'}</td>
            <td class="px-4 py-4 whitespace-nowrap ">
                <div class="flex flex-col gap-2 items-center justify-center">
                    <button onclick="openEditScheduleModal('${schedule.id}')" class="edit-btn w-full gap-2 inline-flex items-center px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-edit"></i>Edit
                    </button>
                    <button onclick="confirmDeleteSchedule('${schedule.id}')" class="delete-btn w-full gap-2 inline-flex items-center px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-trash-alt"></i>Hapus
                    </button>
                </div>
            </td>
        `;

        // Insert the row in the correct position based on date
        const allRows = Array.from(tableBody.querySelectorAll('tr'));
        const insertBefore = allRows.find(r => {
            const rowDate = new Date(r.querySelector('td:first-child').textContent);
            return new Date(schedule.scheduled_at) > rowDate;
        });
        
        if (insertBefore) {
            tableBody.insertBefore(row, insertBefore);
        } else {
            tableBody.appendChild(row);
        }
    }

    function formatDateTime(dateString) {
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    }

    function renderEmptyState() {
        tableBody.innerHTML = `
            <tr class="hover:bg-gray-50 transition-colors duration-200">
                <td class="px-6 py-4 whitespace-nowrap text-gray-500" colspan="8">
                    <span class="status-badge bg-gray-100 text-gray-600">
                        <i class="fas fa-minus mr-1"></i>Tidak ada data
                    </span>
                </td>
            </tr>
        `;
    }

    window.openEditScheduleModal = async function(id) {
        try {
            const response = await fetch(`${API_BASE}/${id}`, {
                method: 'GET',
                headers
            });
            
            if (!response.ok) throw new Error('Gagal mengambil data');
            
            const schedule = await response.json();
            
            // Populate modal form
            modalForm.querySelector('[name="scheduled_at"]').value = schedule.scheduled_at.slice(0, 16);
            modalForm.querySelector('[name="briefing"]').value = schedule.briefing ? '1' : '0';
            modalForm.querySelector('[name="meeting"]').value = schedule.meeting ? '1' : '0';
            modalForm.querySelector('[name="supervision"]').value = schedule.supervision ? '1' : '0';
            modalForm.querySelector('[name="handover"]').value = schedule.handover ? '1' : '0';
            modalForm.querySelector('[name="external_task"]').value = schedule.external_task || '';
            modalForm.querySelector('[name="note"]').value = schedule.note || '';
            
            // Set the ID to be used in update
            modalForm.dataset.editId = id;
            
            // Update UI
            modalTitle.textContent = 'Edit Jadwal Kegiatan';
            deleteBtn.classList.remove('hidden');
            deleteBtn.setAttribute('data-id', id);
            
            // Show modal
            scheduleModal.classList.remove('hidden');
            scheduleModal.classList.add('flex');
            
        } catch (error) {
            console.error('Error:', error);
            alert('Gagal memuat data untuk diedit');
        }
    };

    // Update modal form submission handler
    modalForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = modalForm.dataset.editId;
        
        if (id) {
            await handleUpdateSchedule(id);
        }
    });

    async function handleUpdateSchedule(id) {
        const modalForm = document.getElementById('modalPrivateScheduleForm');
        const data = {
            scheduled_at: modalForm.querySelector('[name="scheduled_at"]').value,
            briefing: modalForm.querySelector('[name="briefing"]').value === '1',
            meeting: modalForm.querySelector('[name="meeting"]').value === '1',
            supervision: modalForm.querySelector('[name="supervision"]').value === '1',
            handover: modalForm.querySelector('[name="handover"]').value === '1',
            external_task: modalForm.querySelector('[name="external_task"]').value,
            note: modalForm.querySelector('[name="note"]').value
        };
        
        try {
            modalSubmitBtn.disabled = true;
            modalSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memperbarui...';

            const response = await fetch(`${API_BASE}/${id}`, {
                method: 'PUT',
                headers,
                body: JSON.stringify(data)
            });

            const updatedSchedule = await response.json();
        
            // Remove the old row
            const oldRow = document.querySelector(`tr[data-id="${id}"]`);
            if (oldRow) {
                oldRow.remove();
            }
            
            // Add the updated row (will be added in sorted position)
            renderScheduleRow(updatedSchedule);
        } catch (error) {
            console.error('Error:', error);
            alert(error.message || 'Gagal memperbarui');
        } finally {
            modalSubmitBtn.disabled = false;
            modalSubmitBtn.innerHTML = '<i class="fas fa-save mr-3"></i>Simpan Catatan';
            window.closeScheduleModal();
        }
    }

    window.confirmDeleteSchedule = function(id) {
        if (confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
            deleteSchedule(id);
        }
    };

    async function deleteSchedule(id) {
        try {
            const response = await fetch(`${API_BASE}/${id}`, {
                method: 'DELETE',
                headers
            });
            
            if (!response.ok) throw new Error('Gagal menghapus data');
            
            // Remove the row from table
            document.querySelector(`tr[data-id="${id}"]`)?.remove();
            
            // Check if table is empty now
            if (tableBody.querySelectorAll('tr').length === 0) {
                renderEmptyState();
            }
            
            window.closeScheduleModal();
            
        } catch (error) {
            console.error('Error:', error);
            alert('Gagal menghapus jadwal');
        }
    }

    function resetForm() {
        form.reset();
        submitBtn.onclick = async function(e) {
            e.preventDefault();
            await handleFormSubmission();
        };
    }

    window.closeScheduleModal = function() {
        scheduleModal.classList.add('hidden');
        scheduleModal.classList.remove('flex');
        deleteBtn.classList.add('hidden');
        deleteBtn.removeAttribute('data-id');
        const modalForm = document.querySelector('#modalPrivateScheduleForm');
        modalForm.reset();
    };
});