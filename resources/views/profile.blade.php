<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Kepala Ruangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</head>
<body class="min-h-full bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100">
    @include('components.sidebar-navbar')
    <div class="container mx-auto p-4 pt-20 pl-60 pr-5">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8 animate-fadeIn">
            <h1 class="text-3xl font-bold text-gray-800">Profil Kepala Ruangan</h1>
            <div class="flex items-center space-x-4">
                <button onclick="openSettingsModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-cog mr-2"></i>Pengaturan
                </button>
            </div>
        </div>

        <!-- Profile Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8 animate-fadeIn">
            <div class="md:flex">
                <div class="md:w-1/4 p-6 flex justify-center">
                    <div class="relative">
                       <img id="profile-photo"
     class="h-40 w-40 rounded-full object-cover border-4 border-green-500"
     src="{{ $user->profile_photo_url }}"
     alt="Foto Profil">

                    </div>
                </div>
                <div class="md:w-3/4 p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 id="profile-name" class="text-2xl font-bold text-gray-800">Dr. Ahmad Budiman, S.Kep, Ns</h2>
                            <p id="profile-position" class="text-gray-600 mb-2">Kepala Ruangan</p>
                            <div class="flex items-center text-gray-600 mb-2">
                                <i class="fas fa-hospital mr-2"></i>
                                <span id="profile-hospital">RSUP H. Adam Malik</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-door-open mr-2"></i>
                                <span id="profile-department">IGD (Instalasi Gawat Darurat)</span>
                            </div>
                        </div>
                        <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                            Aktif
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <div class="bg-gray-50 p-4 rounded-lg inline-block">
                            <p class="text-gray-500 text-sm">Total Staff</p>
                            <p class="text-2xl font-bold">{{ auth::user()->staff->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ruangan Sections -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-md overflow-hidden animate-fadeIn">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800">
                        <i class="fas fa-procedures mr-2 text-blue-600"></i>
                        Ruangan <span id="display-department">IGD (Instalasi Gawat Darurat)</span>
                    </h3>
                </div>
                
                <div class="px-6 pb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Staff Kasur -->
                       
                        
                        <!-- Staff Kebidanan -->
                       
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Modal -->
    <div id="settingsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Profil</h3>
                <div class="mt-2 px-7 py-3">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="edit-name">
                            Nama
                        </label>
                        <input id="edit-name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" value="Dr. Ahmad Budiman, S.Kep, Ns">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="edit-position">
                            Jabatan
                        </label>
                        <input id="edit-position" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" value="Kepala Ruangan">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="edit-hospital">
                            Rumah Sakit
                        </label>
                        <select id="edit-hospital" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="RSUP H. Adam Malik">RSUP H. Adam Malik</option>
                            <option value="RSU dr. Pirngadi Medan">RSU dr. Pirngadi Medan</option>
                            <option value="RSUD Deli Serdang">RSUD Deli Serdang</option>
                            <option value="RS Bhayangkara TK II Medan">RS Bhayangkara TK II Medan</option>
                            <option value="RS Haji Medan">RS Haji Medan</option>
                            <option value="RS Royal Prima Medan">RS Royal Prima Medan</option>
                            <option value="RS Siloam Medan">RS Siloam Medan</option>
                            <option value="RS Universitas Sumatera Utara">RS Universitas Sumatera Utara</option>
                            <option value="RS Mitra Sejati">RS Mitra Sejati</option>
                            <option value="RS Bunda Thamrin">RS Bunda Thamrin</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="edit-department">
                            Ruangan
                        </label>
                        <select id="edit-department" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="IGD (Instalasi Gawat Darurat)">IGD (Instalasi Gawat Darurat)</option>
                            <option value="ICU (Intensive Care Unit)">ICU (Intensive Care Unit)</option>
                            <option value="NICU (Neonatal ICU)">NICU (Neonatal ICU)</option>
                            <option value="PICU (Pediatric ICU)">PICU (Pediatric ICU)</option>
                            <option value="OK (Operasi)">OK (Operasi)</option>
                            <option value="Ruang VIP">Ruang VIP</option>
                            <option value="Ruang Kelas 1">Ruang Kelas 1</option>
                            <option value="Ruang Kelas 2">Ruang Kelas 2</option>
                            <option value="Ruang Kelas 3">Ruang Kelas 3</option>
                            <option value="Ruang Isolasi">Ruang Isolasi</option>
                            <option value="Ruang Persalinan">Ruang Persalinan</option>
                            <option value="Ruang Perawatan">Ruang Perawatan</option>
                            <option value="Rawat Jalan">Rawat Jalan</option>
                            <option value="Laboratorium">Laboratorium</option>
                            <option value="Radiologi">Radiologi</option>
                            <option value="Fisioterapi">Fisioterapi</option>
                            <option value="Hemodialisa">Hemodialisa</option>
                            <option value="Kamar Mayat">Kamar Mayat</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="edit-photo">
                            Foto Profil
                        </label>
                        <input id="edit-photo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="file" accept="image/*">
                    </div>
                </div>
                <div class="items-center px-4 py-3">
                    <button onclick="saveProfileChanges()" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Simpan Perubahan
                    </button>
                    <button onclick="closeSettingsModal()" class="ml-3 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to open settings modal
        function openSettingsModal() {
            // Set current values in the form
            document.getElementById('edit-name').value = document.getElementById('profile-name').textContent;
            document.getElementById('edit-position').value = document.getElementById('profile-position').textContent;
            document.getElementById('edit-hospital').value = document.getElementById('profile-hospital').textContent;
            document.getElementById('edit-department').value = document.getElementById('profile-department').textContent;
            
            document.getElementById('settingsModal').classList.remove('hidden');
        }

        // Function to close settings modal
        function closeSettingsModal() {
            document.getElementById('settingsModal').classList.add('hidden');
        }

        // Function to save profile changes
        function saveProfileChanges() {
            // Get values from form
            const newName = document.getElementById('edit-name').value;
            const newPosition = document.getElementById('edit-position').value;
            const newHospital = document.getElementById('edit-hospital').value;
            const newDepartment = document.getElementById('edit-department').value;
            const photoFile = document.getElementById('edit-photo').files[0];
            
            // Update profile information
            document.getElementById('profile-name').textContent = newName;
            document.getElementById('profile-position').textContent = newPosition;
            document.getElementById('profile-hospital').textContent = newHospital;
            document.getElementById('profile-department').textContent = newDepartment;
            document.getElementById('display-department').textContent = newDepartment;
            
            // Update photo if a new one was selected
            if (photoFile) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-photo').src = e.target.result;
                    
                    // In a real application, you would upload the photo to your server here
                    // and update the database with the new photo path
                };
                reader.readAsDataURL(photoFile);
            }
            
            // In a real application, you would send this data to your server
            // to update the database. Example:
            /*
            const formData = new FormData();
            formData.append('name', newName);
            formData.append('position', newPosition);
            formData.append('hospital', newHospital);
            formData.append('department', newDepartment);
            if (photoFile) {
                formData.append('photo', photoFile);
            }
            
            fetch('/api/update-profile', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Profile updated successfully');
                    closeSettingsModal();
                } else {
                    alert('Error updating profile');
                }
            });
            */
            
            closeSettingsModal();
        }

        // Initialize with current values when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.animate-fadeIn');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>

    <style>
        .animate-fadeIn {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
@media (max-width: 768px) {
    .pl-60 {
        padding-left: 1rem;
    }
    .pr-5 {
        padding-right: 1rem;
    }
}



        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.6s ease-out forwards;
        }
    </style>
</body>
</html>