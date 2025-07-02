document.addEventListener('DOMContentLoaded', function() {
    loadStaffData();
    loadTnaData();
    loadStats();

    // Staff form submission
    document.getElementById('staff-form').addEventListener('submit', function(e) {
        e.preventDefault();
        saveStaff();
    });

    // TNA form submission
    document.getElementById('tna-form').addEventListener('submit', function(e) {
        e.preventDefault();
        saveTna();
    });
});

function loadStats() {
    axios.get('/api/tna/data')
        .then(response => {
            const data = response.data;
            const statsContainer = document.getElementById('stats-cards');
            
            // Count different types of TNA records
            const seminarCount = data.reduce((acc, staff) => {
                return acc + staff.tna_records.filter(record => record.type === 'seminar').length;
            }, 0);
            
            const trainingCount = data.reduce((acc, staff) => {
                return acc + staff.tna_records.filter(record => record.type === 'training').length;
            }, 0);
            
            const educationCount = data.reduce((acc, staff) => {
                return acc + staff.tna_records.filter(record => record.type === 'education').length;
            }, 0);
            
            const activeStaffCount = data.length;

            statsContainer.innerHTML = `
                <!-- Total Staff Card -->
                <div class="bg-white text-gray-700 p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Staff</p>
                            <p class="text-3xl font-bold mt-2">${activeStaffCount}</p>
                            <p class="text-xs text-gray-400 mt-1">Personil aktif</p>
                        </div>
                        <div class="bg-blue-50 p-3 rounded-full text-blue-500">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                    </div>
                </div>
                <!-- Seminar/Workshop Card -->
                <div class="bg-white text-gray-700 p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Seminar/Workshop</p>
                            <p class="text-3xl font-bold mt-2">${seminarCount}</p>
                            <p class="text-xs text-gray-400 mt-1">Kegiatan tahun ini</p>
                        </div>
                        <div class="bg-green-50 p-3 rounded-full text-green-500">
                            <i class="fas fa-chalkboard-teacher text-xl"></i>
                        </div>
                    </div>
                </div>
                <!-- Pelatihan Card -->
                <div class="bg-white text-gray-700 p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Pelatihan</p>
                            <p class="text-3xl font-bold mt-2">${trainingCount}</p>
                            <p class="text-xs text-gray-400 mt-1">Program terselesaikan</p>
                        </div>
                        <div class="bg-amber-50 p-3 rounded-full text-amber-500">
                            <i class="fas fa-medal text-xl"></i>
                        </div>
                    </div>
                </div>
                <!-- Pendidikan Lanjutan Card -->
                <div class="bg-white text-gray-700 p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Pendidikan Lanjutan</p>
                            <p class="text-3xl font-bold mt-2">${educationCount}</p>
                            <p class="text-xs text-gray-400 mt-1">Staf berkembang</p>
                        </div>
                        <div class="bg-purple-50 p-3 rounded-full text-purple-500">
                            <i class="fas fa-user-graduate text-xl"></i>
                        </div>
                    </div>
                </div>
            `;
        })
        .catch(error => {
            console.error('Error loading stats:', error);
        });
}

function loadStaffData() {
    axios.get('/api/staff')
        .then(response => {
            const staffData = response.data;
            const tableBody = document.getElementById('staff-table-body');
            tableBody.innerHTML = '';

            staffData.forEach((staff, index) => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 transition-all duration-300';
                row.innerHTML = `
                    <td class="px-6 py-4">${index + 1}</td>
                    <td class="px-6 py-4">${staff.name}</td>
                    <td class="px-6 py-4">${staff.position.name}</td>
                    <td class="px-6 py-4">${staff.department.name}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full ${staff.status === 'Aktif' ? 'bg-green-100 text-green-800' : staff.status === 'Cuti' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'}">
                            ${staff.status}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <button onclick="editStaff(${staff.id})" class="bg-white hover:bg-gray-100 text-black px-3 py-1 rounded-lg text-xs font-medium transition-all duration-300 flex items-center border border-blue-500 mr-2">
                            <i class="fas fa-pen mr-1 text-blue-500"></i>Edit
                        </button>
                        <button onclick="deleteStaff(${staff.id})" class="bg-white hover:bg-gray-100 text-black px-3 py-1 rounded-lg text-xs font-medium transition-all duration-300 flex items-center border border-red-500">
                            <i class="fas fa-trash mr-1 text-red-500"></i>Hapus
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error loading staff data:', error);
        });
}

function loadTnaData() {
    axios.get('/api/tna/data')
        .then(response => {
            const tnaData = response.data;
            const tableBody = document.getElementById('tna-table-body');
            tableBody.innerHTML = '';

            tnaData.forEach(staff => {
                // Group TNA records by type
                const seminars = staff.tna_records.filter(record => record.type === 'seminar');
                const trainings = staff.tna_records.filter(record => record.type === 'training');
                const educations = staff.tna_records.filter(record => record.type === 'education');

                // Get the first letter of staff name for avatar
                const avatarLetter = staff.name.charAt(0).toUpperCase();

                const row = document.createElement('tr');
                row.className = 'hover:bg-white transition-all duration-300';
                row.innerHTML = `
                    <td class="px-6 py-4 flex items-center">
                        <div class="w-10 h-10 bg-[#0CC0DF] rounded-full flex items-center justify-center text-white font-bold mr-3">${avatarLetter}</div>
                        ${staff.name}
                    </td>
                    <td class="px-6 py-4">
                        ${seminars.map(seminar => `
                            <div class="mb-2">
                                <span class="text-sm font-medium text-gray-900">${seminar.title}</span>
                                <div class="flex gap-2 mt-1">
                                    <button onclick="viewTnaDetail(${seminar.id})" class="text-xs text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-eye mr-1"></i>Detail
                                    </button>
                                    <button onclick="editTna(${seminar.id})" class="text-xs text-yellow-500 hover:text-yellow-700">
                                        <i class="fas fa-pen mr-1"></i>Edit
                                    </button>
                                    <button onclick="deleteTna(${seminar.id})" class="text-xs text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </div>
                            </div>
                        `).join('')}
                        ${seminars.length === 0 ? '<span class="text-gray-400">Belum Ada</span>' : ''}
                    </td>
                    <td class="px-6 py-4">
                        ${trainings.map(training => `
                            <div class="mb-2">
                                <span class="text-sm font-medium text-gray-900">${training.title}</span>
                                <div class="flex gap-2 mt-1">
                                    <button onclick="viewTnaDetail(${training.id})" class="text-xs text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-eye mr-1"></i>Detail
                                    </button>
                                    <button onclick="editTna(${training.id})" class="text-xs text-yellow-500 hover:text-yellow-700">
                                        <i class="fas fa-pen mr-1"></i>Edit
                                    </button>
                                    <button onclick="deleteTna(${training.id})" class="text-xs text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </div>
                            </div>
                        `).join('')}
                        ${trainings.length === 0 ? '<span class="text-gray-400">Belum Ada</span>' : ''}
                    </td>
                    <td class="px-6 py-4">
                        ${educations.map(education => `
                            <div class="mb-2">
                                <span class="text-sm font-medium text-gray-900">${education.title}</span>
                                <div class="flex gap-2 mt-1">
                                    <button onclick="viewTnaDetail(${education.id})" class="text-xs text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-eye mr-1"></i>Detail
                                    </button>
                                    <button onclick="editTna(${education.id})" class="text-xs text-yellow-500 hover:text-yellow-700">
                                        <i class="fas fa-pen mr-1"></i>Edit
                                    </button>
                                    <button onclick="deleteTna(${education.id})" class="text-xs text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </div>
                            </div>
                        `).join('')}
                        ${educations.length === 0 ? '<span class="text-gray-400">Belum Ada</span>' : ''}
                    </td>
                    <td class="px-6 py-4">
                        <button onclick="openTnaModal(${staff.id})" class="bg-white hover:bg-gray-100 text-black px-4 py-2 rounded-lg text-xs font-medium transition-all duration-300 flex items-center border border-[#0CC0DF]">
                            <i class="fas fa-plus mr-1 text-[#0CC0DF]"></i>Tambah
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error loading TNA data:', error);
        });
}

// Staff Modal Functions
function openStaffModal(staffId = null) {
    const modal = document.getElementById('staff-modal');
    const modalTitle = document.getElementById('staff-modal-title');
    const form = document.getElementById('staff-form');
    
    // Load dropdown options first
    Promise.all([
        axios.get('/api/positions'),
        axios.get('/api/departments')
    ]).then(([positionsRes, departmentsRes]) => {
        const positionSelect = document.getElementById('position_id');
        const departmentSelect = document.getElementById('department_id');
        
        // Clear and populate position dropdown
        positionSelect.innerHTML = '';
        positionsRes.data.forEach(position => {
            const option = document.createElement('option');
            option.value = position.id;
            option.textContent = position.name;
            positionSelect.appendChild(option);
        });
        
        // Clear and populate department dropdown
        departmentSelect.innerHTML = '';
        departmentsRes.data.forEach(department => {
            const option = document.createElement('option');
            option.value = department.id;
            option.textContent = department.name;
            departmentSelect.appendChild(option);
        });
        
        if (staffId) {
            // Edit mode
            modalTitle.textContent = 'Edit Data Staf';
            axios.get(`/api/staff/${staffId}`)
                .then(response => {
                    const staff = response.data;
                    document.getElementById('staff-id').value = staff.id;
                    document.getElementById('name').value = staff.name;
                    document.getElementById('position_id').value = staff.position_id;
                    document.getElementById('department_id').value = staff.department_id;
                    document.getElementById('status').value = staff.status;
                })
                .catch(error => {
                    console.error('Error fetching staff data:', error);
                });
        } else {
            // Add mode
            modalTitle.textContent = 'Tambah Staf Baru';
            form.reset();
            document.getElementById('staff-id').value = '';
        }
        
        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');
    }).catch(error => {
        console.error('Error loading dropdown data:', error);
    });
}

function saveStaff() {
    const form = document.getElementById('staff-form');
    const formData = new FormData(form);
    const staffId = document.getElementById('staff-id').value;
    
    const data = {
        name: formData.get('name'),
        position_id: formData.get('position_id'),
        department_id: formData.get('department_id'),
        status: formData.get('status'),
        // hospital_id dan user_id harus disesuaikan dengan kebutuhan
        hospital_id: 1, // atau ambil dari session/input
        user_id: 1 // atau ambil dari auth user
    };
    
    const request = staffId 
        ? axios.put(`/api/staff/${staffId}`, data)
        : axios.post('/api/staff', data);
    
    request.then(response => {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: staffId ? 'Data staf berhasil diperbarui' : 'Staf baru berhasil ditambahkan',
            showConfirmButton: false,
            timer: 1500
        });
        closeStaffModal();
        loadStaffData();
        loadTnaData();
        loadStats();
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat menyimpan data staf',
        });
        console.error('Error saving staff:', error);
    });
}

function closeStaffModal() {
    const modal = document.getElementById('staff-modal');
    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');
}

function saveStaff() {
    const form = document.getElementById('staff-form');
    const formData = new FormData(form);
    const staffId = document.getElementById('staff-id').value;
    
    const data = {
        name: formData.get('name'),
        position: formData.get('position'),
        department: formData.get('department'),
        status: formData.get('status')
    };
    
    const request = staffId 
        ? axios.put(`/api/staff/${staffId}`, data)
        : axios.post('/api/staff', data);
    
    request.then(response => {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: staffId ? 'Data staf berhasil diperbarui' : 'Staf baru berhasil ditambahkan',
            showConfirmButton: false,
            timer: 1500
        });
        closeStaffModal();
        loadStaffData();
        loadTnaData();
        loadStats();
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat menyimpan data staf',
        });
        console.error('Error saving staff:', error);
    });
}

function editStaff(staffId) {
    openStaffModal(staffId);
}

function deleteStaff(staffId) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Anda tidak akan dapat mengembalikan data ini!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/api/staff/${staffId}`)
                .then(response => {
                    Swal.fire(
                        'Dihapus!',
                        'Data staf telah dihapus.',
                        'success'
                    );
                    loadStaffData();
                    loadTnaData();
                    loadStats();
                })
                .catch(error => {
                    Swal.fire(
                        'Gagal!',
                        'Terjadi kesalahan saat menghapus data staf.',
                        'error'
                    );
                    console.error('Error deleting staff:', error);
                });
        }
    });
}

// TNA Modal Functions
function openTnaModal(staffId = null, tnaId = null) {
    const modal = document.getElementById('tna-modal');
    const modalTitle = document.getElementById('tna-modal-title');
    const form = document.getElementById('tna-form');
    const staffSelect = document.getElementById('staff_id');
    
    // Load staff for dropdown
    axios.get('/api/tna/staff')
        .then(response => {
            staffSelect.innerHTML = '<option value="">Pilih Staf</option>';
            response.data.forEach(staff => {
                const option = document.createElement('option');
                option.value = staff.id;
                option.textContent = staff.name;
                if (staffId && staff.id == staffId) {
                    option.selected = true;
                }
                staffSelect.appendChild(option);
            });
            
            if (tnaId) {
                // Edit mode
                modalTitle.textContent = 'Edit Data TNA';
                axios.get(`/api/tna/${tnaId}`)
                    .then(response => {
                        const tna = response.data;
                        document.getElementById('tna-id').value = tna.id;
                        document.getElementById('staff_id').value = tna.staff_id;
                        document.getElementById('type').value = tna.type;
                        document.getElementById('title').value = tna.title;
                        document.getElementById('description').value = tna.description || '';
                        document.getElementById('start_date').value = tna.start_date || '';
                        document.getElementById('end_date').value = tna.end_date || '';
                        document.getElementById('organizer').value = tna.organizer || '';
                    })
                    .catch(error => {
                        console.error('Error fetching TNA data:', error);
                    });
            } else {
                // Add mode
                modalTitle.textContent = 'Tambah Data TNA';
                form.reset();
                document.getElementById('tna-id').value = '';
                if (staffId) {
                    document.getElementById('staff_id').value = staffId;
                }
            }
            
            modal.classList.remove('hidden');
            modal.setAttribute('aria-hidden', 'false');
        })
        .catch(error => {
            console.error('Error loading staff for dropdown:', error);
        });
}

function closeTnaModal() {
    const modal = document.getElementById('tna-modal');
    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');
}

function saveTna() {
    const form = document.getElementById('tna-form');
    const formData = new FormData(form);
    const tnaId = document.getElementById('tna-id').value;
    
    const data = {
        staff_id: formData.get('staff_id'),
        type: formData.get('type'),
        title: formData.get('title'),
        description: formData.get('description'),
        start_date: formData.get('start_date'),
        end_date: formData.get('end_date'),
        organizer: formData.get('organizer')
    };
    
    const request = tnaId 
        ? axios.put(`/api/tna/${tnaId}`, data)
        : axios.post('/api/tna', data);
    
    request.then(response => {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: tnaId ? 'Data TNA berhasil diperbarui' : 'Data TNA berhasil ditambahkan',
            showConfirmButton: false,
            timer: 1500
        });
        closeTnaModal();
        loadTnaData();
        loadStats();
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat menyimpan data TNA',
        });
        console.error('Error saving TNA:', error);
    });
}

function editTna(tnaId) {
    openTnaModal(null, tnaId);
}

function deleteTna(tnaId) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Anda tidak akan dapat mengembalikan data ini!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/api/tna/${tnaId}`)
                .then(response => {
                    Swal.fire(
                        'Dihapus!',
                        'Data TNA telah dihapus.',
                        'success'
                    );
                    loadTnaData();
                    loadStats();
                })
                .catch(error => {
                    Swal.fire(
                        'Gagal!',
                        'Terjadi kesalahan saat menghapus data TNA.',
                        'error'
                    );
                    console.error('Error deleting TNA:', error);
                });
        }
    });
}

function viewTnaDetail(tnaId) {
    axios.get(`/api/tna/${tnaId}`)
        .then(response => {
            const tna = response.data;
            const modal = document.getElementById('view-tna-modal');
            
            // Set modal content
            document.getElementById('view-tna-title').textContent = tna.title;
            document.getElementById('view-tna-staff').textContent = `Oleh: ${tna.staff.name}`;
            document.getElementById('view-tna-description').textContent = tna.description || 'Tidak ada deskripsi';
            document.getElementById('view-tna-start-date').textContent = tna.start_date ? new Date(tna.start_date).toLocaleDateString('id-ID') : 'Tidak ditentukan';
            document.getElementById('view-tna-end-date').textContent = tna.end_date ? new Date(tna.end_date).toLocaleDateString('id-ID') : 'Tidak ditentukan';
            document.getElementById('view-tna-organizer').textContent = tna.organizer || 'Tidak diketahui';
            
            // Show modal
            modal.classList.remove('hidden');
            modal.setAttribute('aria-hidden', 'false');
        })
        .catch(error => {
            console.error('Error fetching TNA detail:', error);
        });
}

function closeViewTnaModal() {
    const modal = document.getElementById('view-tna-modal');
    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');
}