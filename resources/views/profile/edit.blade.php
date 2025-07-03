<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Kepala Ruangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="min-h-full bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100">

    @include('components.sidebar-navbar')

    {{-- ALERT sukses --}}
    @if (session('success'))
        <div class="fixed top-20 right-5 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    <div class="container mx-auto p-4 pt-20 pl-60 pr-5">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Profil Kepala Ruangan</h1>
            <div class="flex space-x-3">
                <label for="modal-toggle" class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md transition-all duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit Profil
                </label>
                <label for="settings-modal" class="cursor-pointer bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg shadow-md transition-all duration-200">
                    <i class="fas fa-cog mr-2"></i>Pengaturan
                </label>
            </div>
        </div>

        {{-- ---------- KARTU PROFIL ---------- --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8 border border-gray-100">
            <div class="bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 h-24"></div>
            <div class="relative px-6 pb-6">
                <div class="flex flex-col md:flex-row md:items-end -mt-12">
                    <div class="flex-shrink-0">
                        <img class="h-32 w-32 rounded-full object-cover border-4 border-white shadow-lg"
                             src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('images/p.png') }}"
                             alt="Foto Profil">
                    </div>
                    <div class="mt-4 md:mt-0 md:ml-6 flex-1">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                                <p class="text-gray-600 font-medium">{{ $user->position }}</p>
                            </div>
                            <span class="mt-2 md:mt-0 bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium inline-flex items-center">
                                <i class="fas fa-circle text-green-500 text-xs mr-1"></i>
                                Aktif
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Info Detail --}}
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-id-card text-blue-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500">ID Pegawai</p>
                                <p class="font-semibold text-gray-800">{{ $user->employee_id ?? 'EMP-' . str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-hospital text-green-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500">Rumah Sakit</p>
                                <p class="font-semibold text-gray-800">{{ $user->hospital->name ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-door-open text-purple-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500">Ruangan</p>
                                <p class="font-semibold text-gray-800">{{ $user->department->name ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-envelope text-orange-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="font-semibold text-gray-800">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar text-indigo-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500">Bergabung</p>
                                <p class="font-semibold text-gray-800">{{ $user->created_at->format('d M Y') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-200">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-blue-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-500">Total Staff</p>
                                <p class="text-2xl font-bold text-blue-600">{{ Auth::user()->staff->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ---------- MODAL EDIT PROFIL ---------- --}}
    <input type="checkbox" id="modal-toggle" class="hidden peer" />
    <div class="fixed inset-0 bg-gray-600/50 hidden peer-checked:block z-50"></div>
    <div class="fixed inset-0 flex items-start justify-center hidden peer-checked:flex z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mt-24">
            <div class="border-b px-6 py-4 flex justify-between items-center">
                <h3 class="font-semibold text-lg">Edit Profil</h3>
                <label for="modal-toggle" class="cursor-pointer text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </label>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="px-6 py-4 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    @error('name') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Rumah Sakit</label>
                    <select name="hospital_id"
                            class="mt-1 block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
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
                            class="mt-1 block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
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
                           class="mt-1 block w-full text-gray-700">
                    @error('photo') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
                </div>

                <div class="pt-3 flex justify-end space-x-3">
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                        Simpan
                    </button>
                    <label for="modal-toggle"
                           class="cursor-pointer px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md">
                        Batal
                    </label>
                </div>
            </form>
        </div>
    </div>

    {{-- ---------- MODAL PENGATURAN ---------- --}}
    <input type="checkbox" id="settings-modal" class="hidden peer2" />
    <div class="fixed inset-0 bg-gray-600/50 hidden peer2-checked:block z-50" style="display: none;"></div>
    <div class="fixed inset-0 flex items-center justify-center hidden peer2-checked:flex z-50" style="display: none;">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md">
            <div class="border-b px-6 py-4 flex justify-between items-center">
                <h3 class="font-semibold text-lg flex items-center">
                    <i class="fas fa-cog mr-2 text-gray-600"></i>
                    Pengaturan Akun
                </h3>
                <label for="settings-modal" class="cursor-pointer text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </label>
            </div>

            <div class="px-6 py-4 space-y-3">
                {{-- Ganti Password --}}
                <a href="{{ route('password.change') }}" class="w-full flex items-center p-3 text-left bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors duration-200">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-key text-blue-600"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="font-medium text-gray-800">Ganti Password</p>
                        <p class="text-sm text-gray-500">Ubah password untuk keamanan</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </a>

                {{-- Logout --}}
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center p-3 text-left bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors duration-200">
                        <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-sign-out-alt text-yellow-600"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="font-medium text-gray-800">Keluar</p>
                            <p class="text-sm text-gray-500">Logout dari akun</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </button>
                </form>

                {{-- Hapus Akun --}}
                <label for="delete-modal" class="w-full flex items-center p-3 text-left bg-red-50 hover:bg-red-100 rounded-lg transition-colors duration-200 cursor-pointer">
                    <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-trash text-red-600"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="font-medium text-gray-800">Hapus Akun</p>
                        <p class="text-sm text-gray-500">Hapus akun secara permanen</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </label>
            </div>
        </div>
    </div>

    {{-- ---------- MODAL KONFIRMASI HAPUS AKUN ---------- --}}
    <input type="checkbox" id="delete-modal" class="hidden peer3" />
    <div class="fixed inset-0 bg-gray-600/50 hidden peer3-checked:block z-50" style="display: none;"></div>
    <div class="fixed inset-0 flex items-center justify-center hidden peer3-checked:flex z-50" style="display: none;">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md">
            <div class="px-6 py-4 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Konfirmasi Hapus Akun</h3>
                <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus akun ini? Tindakan ini tidak dapat dibatalkan.</p>
                
                <div class="flex space-x-3">
                    <label for="delete-modal" class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md cursor-pointer text-center">
                        Batal
                    </label>
                    <form action="{{ route('profile.delete') }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Handle modal toggles
        document.addEventListener('DOMContentLoaded', function() {
            const settingsModal = document.getElementById('settings-modal');
            const deleteModal = document.getElementById('delete-modal');
            
            settingsModal.addEventListener('change', function() {
                const overlay = document.querySelector('.peer2-checked\\:block');
                const modal = document.querySelector('.peer2-checked\\:flex');
                if (this.checked) {
                    overlay.style.display = 'block';
                    modal.style.display = 'flex';
                } else {
                    overlay.style.display = 'none';
                    modal.style.display = 'none';
                }
            });

            deleteModal.addEventListener('change', function() {
                const overlay = document.querySelector('.peer3-checked\\:block');
                const modal = document.querySelector('.peer3-checked\\:flex');
                if (this.checked) {
                    overlay.style.display = 'block';
                    modal.style.display = 'flex';
                } else {
                    overlay.style.display = 'none';
                    modal.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>