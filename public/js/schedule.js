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

// --- New Notification Function ---
function showNotification(message, type = 'success') {
    const container = document.getElementById('notification-container');
    if (!container) {
        console.error('Notification container not found!');
        return;
    }

    const notification = document.createElement('div');
    notification.className = `relative p-4 rounded-lg shadow-md text-white flex items-center space-x-3 transition-all duration-300 ease-out transform translate-x-full opacity-0`;

    let bgColor = '';
    let iconClass = '';

    switch (type) {
        case 'success':
            bgColor = 'bg-green-500';
            iconClass = 'fas fa-check-circle';
            break;
        case 'error':
            bgColor = 'bg-red-600';
            iconClass = 'fas fa-exclamation-circle';
            break;
        case 'info':
            bgColor = 'bg-blue-500';
            iconClass = 'fas fa-info-circle';
            break;
        case 'warning':
            bgColor = 'bg-yellow-500';
            iconClass = 'fas fa-exclamation-triangle';
            break;
        default:
            bgColor = 'bg-gray-700';
            iconClass = 'fas fa-bell';
    }

    notification.classList.add(bgColor);
    notification.innerHTML = `
        <i class="${iconClass} text-xl"></i>
        <span class="font-semibold">${message}</span>
        <button class="absolute top-2 right-2 text-white hover:text-gray-200" onclick="this.closest('div').remove()">
            <i class="fas fa-times"></i>
        </button>
    `;

    container.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full', 'opacity-0');
    }, 100);

    // Animate out and remove after a few seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full', 'opacity-0');
        notification.addEventListener('transitionend', () => notification.remove());
    }, 5000); // Notification disappears after 5 seconds
}


document.addEventListener('DOMContentLoaded', () => {
    const token = window.authToken;
    if (!token) {
        console.error('Auth token not found');
        return;
    }

    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
    };

    // --- Private Schedule (Existing) ---
    const PRIVATE_SCHEDULE_API_BASE = '/api/v1/private-schedules';
    const privateScheduleForm = document.getElementById('privateScheduleForm');
    const submitScheduleBtn = document.getElementById('submitScheduleBtn');
    const scheduleTableBody = document.getElementById('scheduleTableBody');

    const scheduleModal = document.getElementById('scheduleModal');
    const modalPrivateScheduleForm = document.getElementById('modalPrivateScheduleForm');
    const modalTitle = document.getElementById('modalTitle');
    const deleteScheduleBtn = document.getElementById('deleteBtn');
    const modalSubmitScheduleBtn = document.getElementById('modalSubmitBtn');

    // Initial load for Private Schedules
    loadPrivateSchedules();

    // Event listener for private schedule form submission
    submitScheduleBtn.addEventListener('click', async (e) => {
        e.preventDefault();
        await handlePrivateScheduleFormSubmission();
    });

    // Modal close handlers for Private Schedule
    scheduleModal.addEventListener('click', function(e) {
        if (e.target === this) closePrivateScheduleModal();
    });

    async function handlePrivateScheduleFormSubmission() {
        const data = getPrivateScheduleFormData(privateScheduleForm);

        if (!data.scheduled_at) {
            showNotification('Waktu tidak boleh kosong', 'warning');
            return;
        }

        try {
            showLoading();
            submitScheduleBtn.disabled = true;
            submitScheduleBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';

            const response = await fetch(PRIVATE_SCHEDULE_API_BASE, {
                method: 'POST',
                headers,
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Gagal menyimpan catatan pribadi');
            }

            await loadPrivateSchedules(true); // This will refetch, re-cache, and re-render the entire table
            resetPrivateScheduleForm();
            showNotification('Catatan pribadi berhasil disimpan!', 'success'); 

        } catch (error) {
            console.error('Error saving private schedule:', error);
            showNotification(error.message || 'Terjadi kesalahan saat menyimpan catatan pribadi', 'error'); // Error notification
        } finally {
            hideLoading();
            submitScheduleBtn.disabled = false;
            submitScheduleBtn.innerHTML = '<i class="fas fa-save mr-3"></i>Simpan Catatan';
        }
    }

    function getPrivateScheduleFormData(formElement) {
        let externalTaskValue = formElement.querySelector('[name="external_task"]').value;
        const externalTaskOtherInput = formElement.querySelector('[name="external_task_other"]');
        if (formElement.querySelector('[name="external_task"]').value === 'other' && externalTaskOtherInput) {
            externalTaskValue = externalTaskOtherInput.value;
        }

        return {
            scheduled_at: formElement.querySelector('[name="scheduled_at"]').value,
            briefing: formElement.querySelector('[name="briefing"]').value === '1',
            meeting: formElement.querySelector('[name="meeting"]').value === '1',
            supervision: formElement.querySelector('[name="supervision"]').value === '1',
            handover: formElement.querySelector('[name="handover"]').value === '1',
            external_task: externalTaskValue,
            note: formElement.querySelector('[name="note"]').value
        };
    }

    async function loadPrivateSchedules(forceRefresh = false) {
        const cacheKey = 'prefetched_schedule_private';
        const cachedData = sessionStorage.getItem(cacheKey);

        if (cachedData && !forceRefresh) {
            console.log('⚡️ Loading private schedules from cache.');
            try {
                const schedules = JSON.parse(cachedData);
                renderPrivateSchedules(schedules);
                return;
            } catch (e) {
                console.error("Failed to parse cached private schedules, fetching from API.", e);
            }
        }

        if (forceRefresh) console.log('🔄 Forcing refresh of private schedules...');
        else console.log('No cache found. Fetching private schedules from API...');
        
        try {
            showLoading();
            const response = await fetch(PRIVATE_SCHEDULE_API_BASE, { headers });
            if (!response.ok) throw new Error('Gagal mengambil data catatan pribadi');
            const schedules = await response.json();

            // **NEW**: Cache the freshly fetched data
            sessionStorage.setItem(cacheKey, JSON.stringify(schedules));
            console.log('✅ Private schedules data has been cached.');

            renderPrivateSchedules(schedules);
        } catch (error) {
            console.error('Error loading private schedules:', error);
            renderEmptyPrivateScheduleState();
        } finally {
            hideLoading();
        }
    }

    function renderPrivateSchedules(schedules) {
        scheduleTableBody.innerHTML = '';
        if (!schedules || schedules.length === 0) {
            renderEmptyPrivateScheduleState();
            return;
        }
        // Remove empty state if present before adding new rows
        const emptyRow = scheduleTableBody.querySelector('.empty-state-row-private-schedule');
        if (emptyRow) {
            emptyRow.remove();
        }

        schedules.sort((a, b) => new Date(b.scheduled_at) - new Date(a.scheduled_at));
        schedules.forEach(schedule => {
            renderPrivateScheduleRow(schedule);
        });
    }

    function renderPrivateScheduleRow(schedule) {
        // Check if an empty state row exists and remove it before appending
        const emptyRow = scheduleTableBody.querySelector('.empty-state-row-private-schedule');
        if (emptyRow) {
            emptyRow.remove();
        }

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
            <td class="px-4 py-4 break-words text-gray-500">${schedule.note || '-'}</td>
            <td class="px-4 py-4 whitespace-nowrap ">
                <div class="flex flex-col gap-2 items-center justify-center">
                    <button onclick="openEditPrivateScheduleModal('${schedule.id}')" class="edit-btn w-full gap-2 inline-flex items-center px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-edit"></i>Edit
                    </button>
                    <button onclick="confirmDeletePrivateSchedule('${schedule.id}')" class="delete-btn w-full gap-2 inline-flex items-center px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-trash-alt"></i>Hapus
                    </button>
                </div>
            </td>
        `;

        const allRows = Array.from(scheduleTableBody.querySelectorAll('tr'));
        const insertBefore = allRows.find(r => {
            const rowDate = new Date(r.querySelector('td:first-child').textContent);
            return new Date(schedule.scheduled_at) > rowDate;
        });

        if (insertBefore) {
            scheduleTableBody.insertBefore(row, insertBefore);
        } else {
            scheduleTableBody.appendChild(row);
        }
    }

    function renderEmptyPrivateScheduleState() {
        scheduleTableBody.innerHTML = `
            <tr class="hover:bg-gray-50 transition-colors duration-200 empty-state-row-private-schedule">
                <td class="px-6 py-4 whitespace-nowrap text-gray-500" colspan="8">
                    <span class="status-badge bg-gray-100 text-gray-600">
                        <i class="fas fa-minus mr-1"></i>Tidak ada data
                    </span>
                </td>
            </tr>
        `;
    }

    window.openEditPrivateScheduleModal = async function(id) {
        try {
            showLoading();
            const response = await fetch(`${PRIVATE_SCHEDULE_API_BASE}/${id}`, {
                method: 'GET',
                headers
            });

            if (!response.ok) throw new Error('Gagal mengambil data jadwal');

            const schedule = await response.json();

            // Populate modal form
            modalPrivateScheduleForm.querySelector('[name="scheduled_at"]').value = schedule.scheduled_at.slice(0, 16);
            modalPrivateScheduleForm.querySelector('[name="briefing"]').value = schedule.briefing ? '1' : '0';
            modalPrivateScheduleForm.querySelector('[name="meeting"]').value = schedule.meeting ? '1' : '0';
            modalPrivateScheduleForm.querySelector('[name="supervision"]').value = schedule.supervision ? '1' : '0';
            modalPrivateScheduleForm.querySelector('[name="handover"]').value = schedule.handover ? '1' : '0';
            
            // Set external_task and handle 'other' option
            const externalTaskSelect = modalPrivateScheduleForm.querySelector('[name="external_task"]');
            const externalTaskOtherInput = modalPrivateScheduleForm.querySelector('#modal_external_task_other');
            const otherInputContainer = modalPrivateScheduleForm.querySelector('#modal_other_input_container');

            const existingOptions = Array.from(externalTaskSelect.options).map(option => option.value);
            if (existingOptions.includes(schedule.external_task)) {
                externalTaskSelect.value = schedule.external_task;
                otherInputContainer.classList.add('hidden');
            } else if (schedule.external_task) {
                externalTaskSelect.value = 'other';
                otherInputContainer.classList.remove('hidden');
                externalTaskOtherInput.value = schedule.external_task;
            } else {
                externalTaskSelect.value = ''; // Default or empty
                otherInputContainer.classList.add('hidden');
                externalTaskOtherInput.value = '';
            }

            modalPrivateScheduleForm.querySelector('[name="note"]').value = schedule.note || '';

            // Set the ID to be used in update
            modalPrivateScheduleForm.dataset.editId = id;

            // Update UI
            modalTitle.textContent = 'Edit Jadwal Kegiatan';
            deleteScheduleBtn.classList.remove('hidden');
            deleteScheduleBtn.setAttribute('data-id', id);
            
            // Ensure modal form uses correct handler for submit
            modalPrivateScheduleForm.removeEventListener('submit', handleSpecialCaseModalSubmit); // Remove previous listener if any
            modalPrivateScheduleForm.addEventListener('submit', handlePrivateScheduleModalSubmit);


            // Show modal
            scheduleModal.classList.remove('hidden');
            scheduleModal.classList.add('flex');

        } catch (error) {
            console.error('Error:', error);
            showNotification('Gagal memuat data untuk diedit', 'error');
        } finally {
            hideLoading();
        }
    };

    async function handlePrivateScheduleModalSubmit(e) {
        e.preventDefault();
        const id = modalPrivateScheduleForm.dataset.editId;

        if (id) {
            const data = getPrivateScheduleFormData(modalPrivateScheduleForm);
            try {
                showLoading();
                modalSubmitScheduleBtn.disabled = true;
                modalSubmitScheduleBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memperbarui...';

                const response = await fetch(`${PRIVATE_SCHEDULE_API_BASE}/${id}`, {
                    method: 'PUT',
                    headers,
                    body: JSON.stringify(data)
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Gagal memperbarui catatan pribadi');
                }

                // Remove the old row
                document.querySelector(`tr[data-id="${id}"]`)?.remove();
                // AFTER
                await loadPrivateSchedules(true); // This line fetches fresh data, updates the cache, and re-renders the table.
                showNotification('Catatan pribadi berhasil diperbarui!', 'success')

            } catch (error) {
                console.error('Error updating private schedule:', error);
                showNotification(error.message || 'Gagal memperbarui catatan pribadi', 'error'); // Error notification
            } finally {
                hideLoading();
                modalSubmitScheduleBtn.disabled = false;
                modalSubmitScheduleBtn.innerHTML = '<i class="fas fa-save mr-3"></i>Simpan Perubahan';
                closePrivateScheduleModal();
            }
        }
    }

    window.confirmDeletePrivateSchedule = function(id) {
        if (confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
            deletePrivateSchedule(id);
        }
    };

    async function deletePrivateSchedule(id) {
        try {
            showLoading();
            const response = await fetch(`${PRIVATE_SCHEDULE_API_BASE}/${id}`, {
                method: 'DELETE',
                headers
            });

            if (!response.ok) throw new Error('Gagal menghapus data jadwal');

            document.querySelector(`tr[data-id="${id}"]`)?.remove();

            if (scheduleTableBody.querySelectorAll('tr').length === 0) {
                renderEmptyPrivateScheduleState();
            }
            // REPLACE them with this single line:
            await loadPrivateSchedules(true); // This will refetch, re-cache, and re-render everything
            closePrivateScheduleModal();
            showNotification('Catatan pribadi berhasil dihapus!', 'success');

        } catch (error) {
            console.error('Error deleting private schedule:', error);
            showNotification('Gagal menghapus jadwal', 'error'); // Error notification
        } finally {
            hideLoading();
        }
    }

    function resetPrivateScheduleForm() {
        privateScheduleForm.reset();
        // Reset external_task_other visibility
        const otherInputContainer = privateScheduleForm.querySelector('#other_input_container');
        const externalTaskOtherInput = privateScheduleForm.querySelector('#external_task_other');
        if (otherInputContainer) otherInputContainer.classList.add('hidden');
        if (externalTaskOtherInput) externalTaskOtherInput.value = '';
    }

    window.closePrivateScheduleModal = function() {
        scheduleModal.classList.add('hidden');
        scheduleModal.classList.remove('flex');
        deleteScheduleBtn.classList.add('hidden');
        deleteScheduleBtn.removeAttribute('data-id');
        modalPrivateScheduleForm.reset();
        // Reset external_task_other visibility in modal
        const modalExternalTaskSelect = modalPrivateScheduleForm.querySelector('[name="external_task"]');
        const modalExternalTaskOtherInput = modalPrivateScheduleForm.querySelector('#modal_external_task_other');
        const modalOtherInputContainer = modalPrivateScheduleForm.querySelector('#modal_other_input_container');
        if (modalOtherInputContainer) modalOtherInputContainer.classList.add('hidden');
        if (modalExternalTaskOtherInput) modalExternalTaskOtherInput.value = '';
    };

    // Global function for external task select change, used by both forms
    window.checkOther = function(select) {
        let otherInputContainer;
        let otherInput;

        if (select.id === 'external_task') { // Main form
            otherInputContainer = document.getElementById('other_input_container');
            otherInput = document.getElementById('external_task_other');
        } else if (select.id === 'modal_external_task') { // Modal form
            otherInputContainer = document.getElementById('modal_other_input_container');
            otherInput = document.getElementById('modal_external_task_other');
        }

        if (otherInputContainer && otherInput) {
            if (select.value === 'other') {
                otherInputContainer.classList.remove('hidden');
            } else {
                otherInputContainer.classList.add('hidden');
                otherInput.value = ''; // Clear input if not 'other'
            }
        }
    };


    // --- Pasien/Kasus Butuh Perhatian Khusus (New Feature) ---
    const SPECIAL_CASE_API_BASE = '/api/v1/special-cases';
    const specialCaseForm = document.getElementById('specialCaseForm');
    const submitSpecialCaseBtn = document.getElementById('submitSpecialCaseBtn');
    const specialCasesTableBody = document.getElementById('specialCasesTableBody');

    const specialCaseModal = document.getElementById('specialCaseModal'); // New modal for special cases
    const modalSpecialCaseForm = document.getElementById('modalSpecialCaseForm');
    const modalSpecialCaseTitle = document.getElementById('modalSpecialCaseTitle');
    const deleteSpecialCaseBtn = document.getElementById('deleteSpecialCaseBtn');
    const modalSubmitSpecialCaseBtn = document.getElementById('modalSubmitSpecialCaseBtn');

    // Initial load for Special Cases
    loadSpecialCases();

    // Event listener for special case form submission
    if (submitSpecialCaseBtn) {
        submitSpecialCaseBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            await handleSpecialCaseFormSubmission();
        });
    }

    // Modal close handler for Special Case
    if (specialCaseModal) {
        specialCaseModal.addEventListener('click', function(e) {
            if (e.target === this) closeSpecialCaseModal();
        });
    }


    async function handleSpecialCaseFormSubmission() {
        const data = getSpecialCaseFormData(specialCaseForm);

        if (!data.case_date || !data.patient_name || !data.case_type) {
            showNotification('Tanggal Kasus, Nama Pasien, dan Jenis Kasus tidak boleh kosong', 'warning');
            return;
        }

        try {
            showLoading();
            submitSpecialCaseBtn.disabled = true;
            submitSpecialCaseBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';

            const response = await fetch(SPECIAL_CASE_API_BASE, {
                method: 'POST',
                headers,
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Gagal menyimpan kasus khusus');
            }

            await loadSpecialCases(true); // This will refetch, re-cache, and re-render the table
            resetSpecialCaseForm();
            showNotification('Kasus khusus berhasil disimpan!', 'success');

        } catch (error) {
            console.error('Error saving special case:', error);
            showNotification(error.message || 'Terjadi kesalahan saat menyimpan kasus khusus', 'error'); // Error notification
        } finally {
            hideLoading();
            submitSpecialCaseBtn.disabled = false;
            submitSpecialCaseBtn.innerHTML = '<i class="fas fa-plus-circle mr-3"></i>Tambah Kasus Khusus'; // Reset to initial button text
        }
    }

    function getSpecialCaseFormData(formElement) {
        return {
            case_date: formElement.querySelector('[name="case_date"]').value,
            patient_name: formElement.querySelector('[name="patient_name"]').value,
            case_type: formElement.querySelector('[name="case_type"]').value,
            details: formElement.querySelector('[name="details"]').value,
            action_taken: formElement.querySelector('[name="action_taken"]').value
        };
    }

    async function loadSpecialCases(forceRefresh = false) {
        const cacheKey = 'prefetched_schedule_special';
        const cachedData = sessionStorage.getItem(cacheKey);

        if (cachedData && !forceRefresh) {
            console.log('⚡️ Loading special cases from cache.');
            try {
                const specialCases = JSON.parse(cachedData);
                renderSpecialCases(specialCases);
                return;
            } catch (e) {
                console.error("Failed to parse cached special cases, fetching from API.", e);
            }
        }
        
        if (forceRefresh) console.log('🔄 Forcing refresh of special cases...');
        else console.log('No cache found. Fetching special cases from API...');
        
        try {
            showLoading();
            const response = await fetch(SPECIAL_CASE_API_BASE, { headers });
            if (!response.ok) throw new Error('Gagal mengambil data kasus khusus');
            const specialCases = await response.json();

            // **NEW**: Cache the freshly fetched data
            sessionStorage.setItem(cacheKey, JSON.stringify(specialCases));
            console.log('✅ Special cases data has been cached.');

            renderSpecialCases(specialCases);
        } catch (error) {
            console.error('Error loading special cases:', error);
            renderEmptySpecialCaseState();
        } finally {
            hideLoading();
        }
    }

    function renderSpecialCases(specialCases) {
        specialCasesTableBody.innerHTML = '';
        if (!specialCases || specialCases.length === 0) {
            renderEmptySpecialCaseState();
            return;
        }
        // Remove empty state if present before adding new rows
        const emptyRow = specialCasesTableBody.querySelector('.empty-state-row-special-case');
        if (emptyRow) {
            emptyRow.remove();
        }

        specialCases.sort((a, b) => new Date(b.case_date) - new Date(a.case_date));
        specialCases.forEach(specialCase => {
            renderSpecialCaseRow(specialCase);
        });
    }

    function renderSpecialCaseRow(specialCase) {
        // Check if an empty state row exists and remove it before appending
        const emptyRow = specialCasesTableBody.querySelector('.empty-state-row-special-case');
        if (emptyRow) {
            emptyRow.remove();
        }

        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        row.dataset.id = specialCase.id;

        let caseTypeClass = '';
        switch (specialCase.case_type) {
            case 'Resiko Tinggi':
                caseTypeClass = 'special-case-high-risk';
                break;
            case 'Kompleks':
                caseTypeClass = 'special-case-complex';
                break;
            case 'Kasus Langka':
                caseTypeClass = 'special-case-rare';
                break;
            default:
                caseTypeClass = 'special-case-info'; // A general class for other types
                break;
        }

        row.innerHTML = `
            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                ${formatDateTime(specialCase.case_date)}
            </td>
            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                ${specialCase.patient_name}
            </td>
            <td class="px-4 py-4 whitespace-nowrap text-sm">
                <span class="special-case-badge ${caseTypeClass}">${specialCase.case_type}</span>
            </td>
            <td class="px-4 py-4 text-sm text-gray-700">
                ${specialCase.details || '-'}
            </td>
            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                ${specialCase.action_taken || '-'}
            </td>
            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                <button onclick="openEditSpecialCaseModal('${specialCase.id}')" class="text-blue-500 hover:text-blue-700 mr-3">
                    <i class="fas fa-edit"></i>
                </button>
                <button onclick="confirmDeleteSpecialCase('${specialCase.id}')" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;

        const allRows = Array.from(specialCasesTableBody.querySelectorAll('tr'));
        const insertBefore = allRows.find(r => {
            const rowDate = new Date(r.querySelector('td:first-child').textContent);
            return new Date(specialCase.case_date) > rowDate;
        });

        if (insertBefore) {
            specialCasesTableBody.insertBefore(row, insertBefore);
        } else {
            specialCasesTableBody.appendChild(row);
        }
    }

    function renderEmptySpecialCaseState() {
        specialCasesTableBody.innerHTML = `
            <tr class="hover:bg-gray-50 empty-state-row-special-case">
                <td class="px-6 py-4 whitespace-nowrap text-gray-500" colspan="6">
                    <span class="status-badge bg-gray-100 text-gray-600">
                        <i class="fas fa-minus mr-1"></i>Tidak ada data
                    </span>
                </td>
            </tr>
        `;
    }

    window.openEditSpecialCaseModal = async function(id) {
        try {
            showLoading();
            const response = await fetch(`${SPECIAL_CASE_API_BASE}/${id}`, {
                method: 'GET',
                headers
            });

            if (!response.ok) throw new Error('Gagal mengambil data kasus khusus');

            const specialCase = await response.json();

            // Populate modal form
            modalSpecialCaseForm.querySelector('[name="case_date"]').value = specialCase.case_date.slice(0, 16);
            modalSpecialCaseForm.querySelector('[name="patient_name"]').value = specialCase.patient_name || '';
            modalSpecialCaseForm.querySelector('[name="case_type"]').value = specialCase.case_type || '';
            modalSpecialCaseForm.querySelector('[name="details"]').value = specialCase.details || '';
            modalSpecialCaseForm.querySelector('[name="action_taken"]').value = specialCase.action_taken || '';

            // Set the ID to be used in update
            modalSpecialCaseForm.dataset.editId = id;

            // Update UI
            modalSpecialCaseTitle.textContent = 'Edit Kasus Perhatian Khusus';
            deleteSpecialCaseBtn.classList.remove('hidden');
            deleteSpecialCaseBtn.setAttribute('data-id', id);

            // Ensure modal form uses correct handler for submit
            modalSpecialCaseForm.removeEventListener('submit', handlePrivateScheduleModalSubmit); // Clear previous listener if any
            modalSpecialCaseForm.addEventListener('submit', handleSpecialCaseModalSubmit); // Add the specific listener

            // Show modal
            specialCaseModal.classList.remove('hidden');
            specialCaseModal.classList.add('flex');

        } catch (error) {
            console.error('Error:', error);
            showNotification('Gagal memuat data kasus khusus untuk diedit', 'error');
        } finally {
            hideLoading();
        }
    };

    async function handleSpecialCaseModalSubmit(e) {
        e.preventDefault();
        const id = modalSpecialCaseForm.dataset.editId;

        if (id) {
            const data = getSpecialCaseFormData(modalSpecialCaseForm);
            try {
                showLoading();
                modalSubmitSpecialCaseBtn.disabled = true;
                modalSubmitSpecialCaseBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memperbarui...';

                const response = await fetch(`${SPECIAL_CASE_API_BASE}/${id}`, {
                    method: 'PUT',
                    headers,
                    body: JSON.stringify(data)
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Gagal memperbarui kasus khusus');
                }

                const updatedSpecialCase = await response.json();

                // Remove the old row
                document.querySelector(`#specialCasesTableBody tr[data-id="${id}"]`)?.remove();
                // Add the updated row (will be added in sorted position)
                await loadSpecialCases(true);
                showNotification('Kasus khusus berhasil diperbarui!', 'success'); // Success notification

            } catch (error) {
                console.error('Error updating special case:', error);
                showNotification(error.message || 'Gagal memperbarui kasus khusus', 'error'); // Error notification
            } finally {
                hideLoading();
                modalSubmitSpecialCaseBtn.disabled = false;
                modalSubmitSpecialCaseBtn.innerHTML = '<i class="fas fa-save mr-3"></i>Simpan Perubahan';
                closeSpecialCaseModal();
            }
        }
    }

    window.confirmDeleteSpecialCase = function(id) {
        if (confirm('Apakah Anda yakin ingin menghapus kasus khusus ini?')) {
            deleteSpecialCase(id);
        }
    };

    async function deleteSpecialCase(id) {
        try {
            showLoading();
            const response = await fetch(`${SPECIAL_CASE_API_BASE}/${id}`, {
                method: 'DELETE',
                headers
            });

            if (!response.ok) throw new Error('Gagal menghapus data kasus khusus');

            await loadSpecialCases(true);
            closeSpecialCaseModal();
            showNotification('Kasus khusus berhasil dihapus!', 'success'); // Success notification

        } catch (error) {
            console.error('Error deleting special case:', error);
            showNotification('Gagal menghapus kasus khusus', 'error'); // Error notification
        } finally {
            hideLoading();
        }
    }

    function resetSpecialCaseForm() {
        if (specialCaseForm) {
            specialCaseForm.reset();
        }
    }

    window.closeSpecialCaseModal = function() {
        if (specialCaseModal) {
            specialCaseModal.classList.add('hidden');
            specialCaseModal.classList.remove('flex');
            deleteSpecialCaseBtn.classList.add('hidden');
            deleteSpecialCaseBtn.removeAttribute('data-id');
            modalSpecialCaseForm.reset();
        }
    };

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
});