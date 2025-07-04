<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - EasyManage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</head>
<body class="min-h-full  bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100">
    <!-- Navbar -->
  @include('components.sidebar-navbar')
  <style>
    @media (max-width: 768px) {
    .pl-60 {
        padding-left: 1rem;
    }
    .pr-5 {
        padding-right: 1rem;
    }
}



  </style>
    <!-- Main Content -->
    <div class="p-4 pt-20 pl-60 pr-5">
        <div>
            <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-500 to-cyan-400 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold">Pengaturan Akun</h1>
                            <p class="text-blue-100">Kelola informasi profil dan preferensi akun Anda</p>
                        </div>
                        <div>
                            <span class="bg-blue-600 text-xs font-semibold px-2.5 py-0.5 rounded-full">PRO</span>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="p-6">
                    <!-- Profile Picture Section -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Foto Profil</h2>
                        <div class="flex items-center space-x-6">
                            <div class="shrink-0">
                                <img id="previewAvatar" class="h-24 w-24 object-cover rounded-full shadow-sm transparent-bg" 
                                     src="images/p.png" 
                                     alt="Current profile photo">
                            </div>
                            <div class="flex flex-col space-y-2">
                                <div>
                                    <input type="file" id="avatarInput" class="hidden" accept="image/*">
                                    <button onclick="document.getElementById('avatarInput').click()" 
                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-300">
                                        Unggah Foto Baru
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500">Format JPG, GIF atau PNG. Ukuran maksimal 2MB</p>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information Section -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pribadi</h2>
                        <form>
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                    <input type="text" id="name" name="name" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border" 
                                           value="Neil Sims">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                                    <input type="email" id="email" name="email" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border" 
                                           value="neil.sims@example.com">
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                                    <input type="tel" id="phone" name="phone" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border" 
                                           value="+62 812-3456-7890">
                                </div>
                                <div>
                                    <label for="position" class="block text-sm font-medium text-gray-700">Posisi/Jabatan</label>
                                    <input type="text" id="position" name="position" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border" 
                                           value="Staff Administrasi">
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button type="button" 
                                        class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Change Password Section -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Ubah Password</h2>
                        <form>
                            <div class="space-y-4">
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700">Password Saat Ini</label>
                                    <input type="password" id="current_password" name="current_password" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border">
                                </div>
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                                    <input type="password" id="password" name="password" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border">
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border">
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button type="button" 
                                        class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Ubah Password
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Notification Preferences -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Preferensi Notifikasi</h2>
                        <form>
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="email_notifications" name="email_notifications" type="checkbox" 
                                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" checked>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="email_notifications" class="font-medium text-gray-700">Email Notifikasi</label>
                                        <p class="text-gray-500">Dapatkan pembaruan penting melalui email</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="app_notifications" name="app_notifications" type="checkbox" 
                                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" checked>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="app_notifications" class="font-medium text-gray-700">Notifikasi Aplikasi</label>
                                        <p class="text-gray-500">Dapatkan notifikasi langsung di aplikasi</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="sms_notifications" name="sms_notifications" type="checkbox" 
                                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="sms_notifications" class="font-medium text-gray-700">SMS Notifikasi</label>
                                        <p class="text-gray-500">Dapatkan pembaruan penting melalui SMS</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button type="button" 
                                        class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Simpan Preferensi
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Danger Zone -->
                    <div class="border-t border-red-200 pt-6">
                        <h2 class="text-lg font-semibold text-red-600 mb-4">Zona Berbahaya</h2>
                        <div class="space-y-4">
                            <div>
                                <button type="button" onclick="showDeactivateModal()"
                                        class="inline-flex items-center px-4 py-2 border border-red-600 text-sm font-medium rounded-md text-red-600 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Nonaktifkan Akun Sementara
                                </button>
                                <p class="mt-1 text-sm text-gray-500">Akun Anda akan dinonaktifkan tetapi data tetap tersimpan.</p>
                            </div>
                            <div>
                                <button type="button" onclick="showDeleteModal()"
                                        class="inline-flex items-center px-4 py-2 border border-red-600 text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Hapus Akun Permanen
                                </button>
                                <p class="mt-1 text-sm text-gray-500">Semua data Anda akan dihapus secara permanen.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deactivate Account Modal -->
    <div id="deactivateModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Nonaktifkan Akun</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Apakah Anda yakin ingin menonaktifkan akun Anda?</p>
                </div>
                <div class="items-center px-4 py-3">
                    <button onclick="hideDeactivateModal()"
                            class="px-4 py-2 bg-yellow-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-300">
                        Ya, Nonaktifkan
                    </button>
                    <button onclick="hideDeactivateModal()"
                            class="ml-3 px-4 py-2 bg-gray-100 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Hapus Akun Permanen</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Semua data Anda akan dihapus dan tidak dapat dikembalikan.</p>
                </div>
                <div class="items-center px-4 py-3">
                    <button onclick="hideDeleteModal()"
                            class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                        Ya, Hapus
                    </button>
                    <button onclick="hideDeleteModal()"
                            class="ml-3 px-4 py-2 bg-gray-100 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Preview uploaded avatar
        document.getElementById('avatarInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB');
                    return;
                }
                
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Format file harus JPG, PNG, atau GIF');
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('previewAvatar').src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Modal functions
        function showDeactivateModal() {
            document.getElementById('deactivateModal').classList.remove('hidden');
        }

        function hideDeactivateModal() {
            document.getElementById('deactivateModal').classList.add('hidden');
        }

        function showDeleteModal() {
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function hideDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Simulate save actions
        document.querySelectorAll('button[type="button"]').forEach(button => {
            if (button.textContent.includes('Simpan')) {
                button.addEventListener('click', () => {
                    alert('Perubahan berhasil disimpan! (simulasi)');
                });
            }
        });
    </script>
</body>
</html>