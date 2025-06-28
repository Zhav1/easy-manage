<x-guest-layout>
    <link rel="stylesheet" href={{ asset('css/register.css') }}>
    <script src={{ asset('js/register.js') }}></script>

    <div class="gradient-bg flex items-center justify-center min-h-screen p-4">
        <!-- Floating Health Elements -->
        <div class="floating-health-elements">
            <i class="health-element fas fa-syringe"></i>
            <i class="health-element fas fa-stethoscope"></i>
            <i class="health-element fas fa-pills"></i>
            <i class="health-element fas fa-hospital"></i>
            <i class="health-element fas fa-heartbeat"></i>
            <i class="health-element fas fa-band-aid"></i>
            <i class="health-element fas fa-flask"></i>
            <i class="health-element fas fa-microscope"></i>
            <i class="health-element fas fa-user-md"></i>
            <i class="health-element fas fa-ambulance"></i>
            <i class="health-element fas fa-thermometer"></i>
            <i class="health-element fas fa-briefcase-medical"></i>
        </div>
        
        <!-- Registration Card -->
        <div class="w-full max-w-md">
            <div class="bg-white rounded-3xl card-shadow p-8">
                <!-- Logo Section -->
                <div class="text-center mb-8">
                    <div class="logo-glow mb-4">
                        <img
                            src="{{ asset('images/Logo Easy Manage.png') }}"   
                            alt="EasyManage Logo"
                            class="w-24 h-24 mx-auto transparent-bg object-contain"       
                        >
                    </div>
                    <h1 class="text-3xl font-bold text-[#0CC0DF] mb-2">EasyManage</h1>
                    <p class="text-black/80 text-sm">Daftar Akun Baru</p>
                </div>
                
                <!-- Validation Errors -->
                <x-validation-errors class="mb-4 p-4 bg-red-500/20 border border-red-400/50 rounded-lg text-red-200" />
                
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Name Field -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-medium text-gray-800">Nama Lengkap</label>
                        <div class="relative flex items-center">
                            <i class="fas fa-user text-gray-400 mr-2"></i>
                            <x-input id="name" 
                                    class="input-focus block w-full pl-3 pr-3 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                    type="text" 
                                    name="name" 
                                    :value="old('name')" 
                                    required 
                                    autofocus 
                                    autocomplete="name" 
                                    placeholder="Masukkan nama lengkap" />
                        </div>
                    </div>
                    
                    <!-- Room Field -->
                    <div class="space-y-2">
                        <label for="department_id" class="block text-sm font-medium text-gray-800">Ruangan</label>
                        <div class="relative flex items-center">
                            <i class="fas fa-door-open text-gray-400 mr-2"></i>
                            <select id="department_id" name="department_id" required
                                class="input-focus block w-full pl-3 pr-3 py-3 bg-gray-50 border border-gray-300 rounded-xl">
                                <option value="" disabled selected>Pilih Ruangan</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Hospital Selection -->
                    <div class="space-y-2">
                        <label for="hospital_id" class="block text-sm font-medium text-gray-800">Rumah Sakit</label>
                        <select id="hospital_id" name="hospital_id" required
                            class="input-focus block w-full pl-3 pr-3 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-800">
                            <option value="" disabled selected>Pilih Rumah Sakit</option>
                            @foreach($hospitals as $hospital)
                                <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Employee ID -->
                    <div class="space-y-2">
                        <label for="id_pegawai" class="block text-sm font-medium text-gray-800">ID Pegawai</label>
                        <div class="relative flex items-center">
                            <i class="fas fa-id-card text-gray-400 mr-2"></i>
                            <x-input id="id_pegawai" 
                                    class="input-focus block w-full pl-3 pr-3 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                    type="text" 
                                    name="id_pegawai" 
                                    :value="old('id_pegawai')" 
                                    required 
                                    autocomplete="off" 
                                    placeholder="Masukkan ID pegawai" />
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-800">Email</label>
                        <div class="relative flex items-center">
                            <i class="fas fa-envelope text-gray-400 mr-2"></i>
                            <x-input id="email" 
                                    class="input-focus block w-full pl-3 pr-3 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                    type="email" 
                                    name="email" 
                                    :value="old('email')" 
                                    required 
                                    autocomplete="username" 
                                    placeholder="Masukkan alamat email" />
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-800">Password</label>
                        <div class="relative flex items-center">
                            <i class="fas fa-lock text-gray-400 mr-2"></i>
                            <x-input id="password" 
                                    class="input-focus block w-full pl-3 pr-3 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                    type="password" 
                                    name="password" 
                                    required 
                                    autocomplete="new-password" 
                                    placeholder="Masukkan password" />
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword('password')">
                                <i id="passwordToggle" class="fas fa-eye-slash text-gray-400 hover:text-gray-600 transition-colors"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-800">Konfirmasi Password</label>
                        <div class="relative flex items-center">
                            <i class="fas fa-lock text-gray-400 mr-2"></i>
                            <x-input id="password_confirmation" 
                                    class="input-focus block w-full pl-3 pr-3 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                    type="password" 
                                    name="password_confirmation" 
                                    required 
                                    autocomplete="new-password" 
                                    placeholder="Konfirmasi password" />
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword('password_confirmation')">
                                <i id="passwordConfirmationToggle" class="fas fa-eye-slash text-gray-400 hover:text-gray-600 transition-colors"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="flex items-start space-x-3">
                            <x-checkbox name="terms" id="terms" required 
                                       class="w-4 h-4 text-green-600 bg-gray-50 border-gray-300 rounded focus:ring-green-500 focus:ring-2" />
                            <label for="terms" class="text-sm text-gray-800">
                                <div class="flex flex-wrap">
                                    {!! __('Saya menyetujui :terms_of_service dan :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="text-[#0CC0DF] hover:text-[#0CC0DF]/80 underline">'.__('Syarat & Ketentuan').'</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="text-[#0CC0DF] hover:text-[#0CC0DF]/80 underline">'.__('Kebijakan Privasi').'</a>',
                                    ]) !!}
                                </div>
                            </label>
                        </div>
                    @endif

                    <!-- Register Button -->
<div class="pt-4 space-y-3"> <!-- Reduced space between elements -->
    <!-- Register Button - Centered and compact -->
    <div class="text-center">
        <x-button class="btn-hover w-full flex items-center justify-center max-w-xs mx-auto py-3 px-4 bg-[#0CC0DF] text-white font-semibold rounded-xl shadow-lg hover:scale-[1.02] transform transition-all duration-300 text-center">
            <span class="flex items-center justify-center space-x-2">
                <i class="fas fa-user-plus"></i>
                <span class="font-bold">Daftar Sekarang</span>
            </span>
        </x-button>
    </div>

    <!-- Login Link - Compact and stylish -->
    <div class="text-center pt-1"> <!-- Reduced top padding -->
        <p class="text-gray-600 text-sm mb-1"> <!-- Smaller margin -->
            Sudah punya akun? 
        </p>
        <a href="{{ route('login') }}" 
           id = "login-link" class="inline-block w-full max-w-xs mx-auto py-2 px-4 bg-white border-2 border-[#0CC0DF] text-[#0CC0DF] hover:bg-[#0CC0DF]/5 font-medium rounded-xl transition-all duration-200 hover:shadow-md">
           <span class="font-semibold">Masuk Disini</span> 
        </a>
    </div>
</div>
                </form>
                
                <!-- Login Link -->
            </div>
            <script>
document.addEventListener('DOMContentLoaded', function() {
    const loginBtn = document.querySelector('a[href="{{ route('login') }}"]');
    
    if (loginBtn) {
        loginBtn.addEventListener('click', function(e) {
            console.log('Login link clicked - redirecting to:', this.href);
            // Jika console log muncul tapi tidak redirect, ada masalah lain
        });
    }
});
</script>
            <!-- Footer -->
            <div class="text-center mt-8">
                <p class="text-black/60 text-xs">
                    © 2024 EasyManage. Semua hak dilindungi.
                </p>
            </div>
        </div>
    </div>

    <script>
        
    </script>
</x-guest-layout>