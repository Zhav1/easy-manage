<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Kepala Ruangan</title>

    <!-- Tailwind & Font‑Awesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="min-h-full bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100">

    {{-- Sidebar + Navbar --}}
    @include('components.sidebar-navbar')

    {{-- ALERT sukses --}}
    @if (session('success'))
        <div class="fixed top-20 right-5 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    {{-- ===================== HEADER ===================== --}}
    <div class="container mx-auto pt-20 px-4 sm:pl-64 sm:pr-5 animate-fadeIn">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 md:mb-8 gap-4">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Profil Kepala Ruangan</h1>

            <div class="flex flex-wrap gap-2 w-full md:w-auto">
                {{-- Tombol Edit Profil --}}
                <label for="modal-toggle"
                       class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 md:px-4 md:py-2 rounded-lg shadow-md transition-all duration-200 text-sm md:text-base w-full md:w-auto text-center">
                    <i class="fas fa-edit mr-1 md:mr-2"></i>Edit Profil
                </label>

                {{-- Tombol Pengaturan --}}
                <label for="settings-modal"
                       class="cursor-pointer bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 md:px-4 md:py-2 rounded-lg shadow-md transition-all duration-200 text-sm md:text-base w-full md:w-auto text-center">
                    <i class="fas fa-cog mr-1 md:mr-2"></i>Pengaturan
                </label>
            </div>
        </div>

        {{-- ===================== KARTU PROFIL ===================== --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8 border border-gray-100">
            <div class="bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 h-16 md:h-24"></div>

            <div class="relative px-4 md:px-6 pb-6">
                <div class="flex flex-col items-center md:flex-row md:items-end -mt-12">
                    <div class="flex-shrink-0">
                        <img class="h-24 w-24 md:h-32 md:w-32 rounded-full object-cover border-4 border-white shadow-lg"
                             src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('images/p.png') }}"
                             alt="Foto Profil">
                    </div>

                    <div class="mt-4 md:mt-0 md:ml-6 w-full text-center md:text-left">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                            <div class="mb-2 md:mb-0">
                                <h2 class="text-xl md:text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                                <p class="text-gray-600 font-medium text-sm md:text-base">{{ $user->position }}</p>
                            </div>

                            <span class="mt-2 md:mt-0 bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs md:text-sm font-medium inline-flex items-center justify-center">
                                <i class="fas fa-circle text-green-500 text-xs mr-1"></i>
                                Aktif
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Detail Info --}}
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                        {{-- ID --}}
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-id-card text-blue-600 text-sm md:text-base"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs md:text-sm text-gray-500">ID Pegawai</p>
                                <p class="font-semibold text-gray-800 text-sm md:text-base">{{ Auth::user()->id }}</p>
                            </div>
                        </div>

                        {{-- Rumah Sakit --}}
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-hospital text-green-600 text-sm md:text-base"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs md:text-sm text-gray-500">Rumah Sakit</p>
                                <p class="font-semibold text-gray-800 text-sm md:text-base">{{ $user->hospital->name ?? '-' }}</p>
                            </div>
                        </div>

                        {{-- Ruangan --}}
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-door-open text-purple-600 text-sm md:text-base"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs md:text-sm text-gray-500">Ruangan</p>
                                <p class="font-semibold text-gray-800 text-sm md:text-base">{{ $user->department->name ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        {{-- Email --}}
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-envelope text-orange-600 text-sm md:text-base"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs md:text-sm text-gray-500">Email</p>
                                <p class="font-semibold text-gray-800 text-sm md:text-base break-all">{{ $user->email }}</p>
                            </div>
                        </div>

                        {{-- Bergabung --}}
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar text-indigo-600 text-sm md:text-base"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs md:text-sm text-gray-500">Bergabung</p>
                                <p class="font-semibold text-gray-800 text-sm md:text-base">{{ $user->created_at->format('d M Y') }}</p>
                            </div>
                        </div>

                        {{-- Total Staff --}}
                        <div class="flex items-center p-3 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-200">
                            <div class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-blue-600 text-sm md:text-base"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs md:text-sm text-gray-500">Total Staff</p>
                                <p class="text-xl md:text-2xl font-bold text-blue-600">{{ Auth::user()->staff->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================
       =============== MODAL EDIT PROFIL =======================
       ========================================================= --}}
    <div class="relative">
        <input type="checkbox" id="modal-toggle" class="hidden peer" />

        {{-- overlay --}}
        <div class="fixed inset-0 bg-gray-600/50 hidden peer-checked:block z-50"></div>

        {{-- konten modal --}}
        <div class="fixed inset-0 flex items-start justify-center hidden peer-checked:flex z-50 p-4">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mt-10 md:mt-24 max-h-[90vh] overflow-y-auto">
                <div class="border-b px-4 py-3 md:px-6 md:py-4 flex justify-between items-center sticky top-0 bg-white z-10">
                    <h3 class="font-semibold text-lg">Edit Profil</h3>
                    <label for="modal-toggle" class="cursor-pointer text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </label>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                      class="px-4 py-4 md:px-6 md:py-4 space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm md:text-base p-2">
                        @error('name') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Rumah Sakit</label>
                        <select name="hospital_id"
                                class="mt-1 block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm md:text-base p-2">
                            @foreach ($hospitals as $hospital)
                                <option value="{{ $hospital->id }}"
                                    {{ $hospital->id == old('hospital_id', $user->hospital_id) ? 'selected' : '' }}>
                                    {{ $hospital->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('hospital_id') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ruangan</label>
                        <select name="department_id"
                                class="mt-1 block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm md:text-base p-2">
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ $department->id == old('department_id', $user->department_id) ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Foto Profil (opsional)</label>
                        <input type="file" name="photo"
                               class="mt-1 block w-full text-gray-700 text-sm md:text-base p-2 border rounded-md">
                        @error('photo') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-3 flex flex-col-reverse md:flex-row justify-end gap-2 md:space-x-3 sticky bottom-0 bg-white py-3 border-t">
                        <label for="modal-toggle"
                               class="cursor-pointer px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md text-center">
                            Batal
                        </label>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- =========================================================
       =============== MODAL PENGATURAN ========================
       ========================================================= --}}
    <div class="relative">
        <input type="checkbox" id="settings-modal" class="hidden peer" />

        <div class="fixed inset-0 bg-gray-600/50 hidden peer-checked:block z-50"></div>

        <div class="fixed inset-0 flex items-center justify-center hidden peer-checked:flex z-50 p-4">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md max-h-[90vh] overflow-y-auto">
                <div class="border-b px-4 py-3 md:px-6 md:py-4 flex justify-between items-center sticky top-0 bg-white z-10">
                    <h3 class="font-semibold text-lg flex items-center">
                        <i class="fas fa-cog mr-2 text-gray-600"></i>
                        Pengaturan Akun
                    </h3>
                    <label for="settings-modal" class="cursor-pointer text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </label>
                </div>

                <div class="px-4 py-4 md:px-6 md:py-4 space-y-3">
                    {{-- Ganti Password --}}
                    <a href="{{ route('password.change') }}"
                       class="w-full flex items-center p-3 text-left bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors duration-200">
                        <div class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-key text-blue-600 text-sm md:text-base"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="font-medium text-gray-800 text-sm md:text-base">Ganti Password</p>
                            <p class="text-xs md:text-sm text-gray-500">Ubah password untuk keamanan</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
                    </a>

                    {{-- Logout --}}
                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center p-3 text-left bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors duration-200">
                            <div class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-sign-out-alt text-yellow-600 text-sm md:text-base"></i>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="font-medium text-gray-800 text-sm md:text-base">Keluar</p>
                                <p class="text-xs md:text-sm text-gray-500">Logout dari akun</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
                        </button>
                    </form>

                    {{-- Hapus Akun --}}
                    <label for="delete-modal"
                           class="w-full flex items-center p-3 text-left bg-red-50 hover:bg-red-100 rounded-lg transition-colors duration-200 cursor-pointer">
                        <div class="flex-shrink-0 w-8 h-8 md:w-10 md:h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-trash text-red-600 text-sm md:text-base"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="font-medium text-gray-800 text-sm md:text-base">Hapus Akun</p>
                            <p class="text-xs md:text-sm text-gray-500">Hapus akun secara permanen</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
                    </label>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================
       ======= MODAL KONFIRMASI HAPUS AKUN =====================
       ========================================================= --}}
    <div class="relative">
        <input type="checkbox" id="delete-modal" class="hidden peer" />

        <div class="fixed inset-0 bg-gray-600/50 hidden peer-checked:block z-50"></div>

        <div class="fixed inset-0 flex items-center justify-center hidden peer-checked:flex z-50 p-4">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md">
                <div class="px-4 py-6 md:px-6 md:py-6 text-center">
                    <div class="w-12 h-12 md:w-16 md:h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3 md:mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl md:text-2xl"></i>
                    </div>
                    <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-2">Konfirmasi Hapus Akun</h3>
                    <p class="text-xs md:text-sm text-gray-600 mb-4 md:mb-6">
                        Apakah Anda yakin ingin menghapus akun ini? Tindakan ini tidak dapat dibatalkan.
                    </p>

                    <div class="flex flex-col md:flex-row gap-2 md:space-x-3">
                        <label for="delete-modal"
                               class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md cursor-pointer text-center text-sm md:text-base">
                            Batal
                        </label>

                        <form action="{{ route('profile.delete') }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm md:text-base">
                                Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================= SCRIPTS ========================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Auto-hide success message
            setTimeout(() => {
                const successMessage = document.querySelector('.fixed.top-20.right-5');
                if (successMessage) successMessage.style.display = 'none';
            }, 5000);

            // Tutup modal jika klik overlay
            document.querySelectorAll('.fixed.inset-0.bg-gray-600\\/50').forEach(overlay => {
                overlay.addEventListener('click', function (e) {
                    if (e.target === this) {
                        const modalId = this.previousElementSibling.id;
                        document.getElementById(modalId).checked = false;
                    }
                });
            });
        });
    </script>
</body>
</html>
