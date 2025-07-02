
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
            <label for="modal-toggle" class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-cog mr-2"></i>Pengaturan
            </label>
        </div>

        {{-- ---------- KARTU PROFIL ---------- --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <div class="md:flex">
                <div class="md:w-1/4 p-6 flex justify-center">
                    <img class="h-40 w-40 rounded-full object-cover border-4 border-green-500"
                         src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('images/foto-formal.png') }}"
                         alt="Foto Profil">
                </div>
                <div class="md:w-3/4 p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                            <p class="text-gray-600 mb-2">{{ $user->position }}</p>

                            <div class="flex items-center text-gray-600 mb-2">
                                <i class="fas fa-hospital mr-2"></i>
                                <span>{{ $user->hospital->name ?? '-' }}</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-door-open mr-2"></i>
                                <span>{{ $user->department->name }}
</span>
                            </div>
                        </div>
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                            Aktif
                        </span>
                    </div>

                    <div class="mt-6">
                        <div class="bg-gray-50 p-4 rounded-lg inline-block">
                            <p class="text-gray-500 text-sm">Total Staff</p>
                            <p class="text-2xl font-bold">{{  Auth::user()->staff->count() }}</p> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CONTENT LAIN … --}}
    </div>

    {{-- ---------- MODAL EDIT ---------- --}}
    {{-- Gunakan checkbox Trick untuk modal sederhana tanpa JS besar --}}
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

            {{-- FORM --}}
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="px-6 py-4 space-y-4">
                @csrf
                @method('PATCH')

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    @error('name') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
                </div>

                

                {{-- Rumah Sakit --}}
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

                {{-- Ruangan --}}
                {{-- Ruangan --}}
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


                {{-- Foto --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Foto Profil (opsional)</label>
                    <input type="file" name="photo"
                           class="mt-1 block w-full text-gray-700">
                    @error('photo') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
                </div>

                {{-- Tombol --}}
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
</body>
</html>
