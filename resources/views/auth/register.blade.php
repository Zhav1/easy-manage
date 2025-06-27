<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg,rgb(1, 74, 24) 0%,rgb(31, 240, 251) 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        
        .gradient-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="%23ffffff" stop-opacity="0.1"/><stop offset="100%" stop-color="%23ffffff" stop-opacity="0"/></radialGradient></defs><rect width="1000" height="1000" fill="url(%23a)"/></svg>');
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
        }
        
        .card-shadow {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .input-focus {
            transition: all 0.3s ease;
        }
        
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .btn-hover {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
        
        .btn-hover::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-hover:hover::before {
            left: 100%;
        }
        
        .logo-glow {
            filter: drop-shadow(0 0 20px rgba(255, 255, 255, 0.3));
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes glow {
            from { filter: drop-shadow(0 0 20px rgba(255, 255, 255, 0.3)); }
            to { filter: drop-shadow(0 0 30px rgba(255, 255, 255, 0.5)); }
        }
        
        /* Floating Health Elements */
        .floating-health-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        
        .health-element {
            position: absolute;
            font-size: 20px;
            color: rgba(255, 255, 255, 0.4);
            animation: healthFloat 25s linear infinite;
            bottom: -150px;
        }
        
        .health-element:nth-child(1) {
            left: 10%;
            font-size: 40px;
            animation-delay: 0s;
            animation-duration: 20s;
        }
        
        .health-element:nth-child(2) {
            left: 20%;
            font-size: 25px;
            animation-delay: 3s;
            animation-duration: 18s;
        }
        
        .health-element:nth-child(3) {
            left: 30%;
            font-size: 35px;
            animation-delay: 6s;
            animation-duration: 22s;
        }
        
        .health-element:nth-child(4) {
            left: 40%;
            font-size: 30px;
            animation-delay: 9s;
            animation-duration: 16s;
        }
        
        .health-element:nth-child(5) {
            left: 50%;
            font-size: 28px;
            animation-delay: 12s;
            animation-duration: 24s;
        }
        
        .health-element:nth-child(6) {
            left: 60%;
            font-size: 45px;
            animation-delay: 15s;
            animation-duration: 19s;
        }
        
        .health-element:nth-child(7) {
            left: 70%;
            font-size: 32px;
            animation-delay: 18s;
            animation-duration: 21s;
        }
        
        .health-element:nth-child(8) {
            left: 80%;
            font-size: 26px;
            animation-delay: 21s;
            animation-duration: 17s;
        }
        
        .health-element:nth-child(9) {
            left: 90%;
            font-size: 38px;
            animation-delay: 24s;
            animation-duration: 23s;
        }
        /* Tambahkan di bagian <style> */
.floating-health-elements {
    pointer-events: none; /* Pastikan ini ada */
    z-index: 1; /* Nilai lebih rendah dari card */
}

.bg-white {
    position: relative;
    z-index: 2; /* Pastikan card di atas floating elements */
}

/* Untuk tombol login */
.login-container {
    position: relative;
    z-index: 3;
}
        /* Style untuk dropdown tanpa scroll dan selalu buka ke bawah */
        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        /* Hilangkan scrollbar pada dropdown */
        select::-webkit-scrollbar {
            display: none;
        }

        /* Pastikan dropdown selalu buka ke bawah */
        select option {
            direction: ltr;
            padding: 8px 12px;
        }

        /* Style tambahan untuk dropdown yang lebih baik */
        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }
        
        .health-element:nth-child(10) {
            left: 15%;
            font-size: 42px;
            animation-delay: 27s;
            animation-duration: 15s;
        }
        
        .health-element:nth-child(11) {
            left: 85%;
            font-size: 29px;
            animation-delay: 30s;
            animation-duration: 25s;
        }
        
        .health-element:nth-child(12) {
            left: 25%;
            font-size: 33px;
            animation-delay: 33s;
            animation-duration: 14s;
        }
        
        @keyframes healthFloat {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.6;
            }
            50% {
                opacity: 0.3;
            }
            100% {
                transform: translateY(-1200px) rotate(360deg);
                opacity: 0;
            }
        }
        
        .transparent-bg {
            background-color: transparent;
            mix-blend-mode: multiply;
        }
    </style>

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
                        <label for="room" class="block text-sm font-medium text-gray-800">Ruangan</label>
                        <div class="relative flex items-center">
                            <i class="fas fa-door-open text-gray-400 mr-2"></i>
                            <select id="room" name="room"
                                class="input-focus block w-full pl-3 pr-3 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="" selected>Pilih Ruangan</option>
                                <option value="igd">IGD (Instalasi Gawat Darurat)</option>
                                <option value="icu">ICU (Intensive Care Unit)</option>
                                <option value="nicu">NICU (Neonatal ICU)</option>
                                <option value="picu">PICU (Pediatric ICU)</option>
                                <option value="ok">OK (Operasi)</option>
                                <option value="vip">Ruang VIP</option>
                                <option value="kelas1">Ruang Kelas 1</option>
                                <option value="kelas2">Ruang Kelas 2</option>
                                <option value="kelas3">Ruang Kelas 3</option>
                                <option value="isolasi">Ruang Isolasi</option>
                                <option value="persalinan">Ruang Persalinan</option>
                                <option value="perawatan">Ruang Perawatan</option>
                                <option value="rawat_jalan">Rawat Jalan</option>
                                <option value="laboratorium">Laboratorium</option>
                                <option value="radiologi">Radiologi</option>
                                <option value="fisioterapi">Fisioterapi</option>
                                <option value="hemodialisa">Hemodialisa</option>
                                <option value="kamar_mayat">Kamar Mayat</option>
                            </select>
                        </div>
                    </div>

                    <!-- Hospital Selection -->
                    <div class="space-y-2">
                        <label for="hospital_id" class="block text-sm font-medium text-gray-800">Rumah Sakit</label>
                        <div class="relative flex items-center">
                            <i class="fas fa-hospital text-gray-400 mr-2"></i>
                            <select id="hospital_id" name="hospital_id" required
                                class="input-focus block w-full pl-3 pr-3 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="" disabled selected>Pilih Rumah Sakit</option>
                                @foreach(\App\Models\Hospital::all() as $hospital)
                                    <option value="{{ $hospital->id }}" {{ old('hospital_id') == $hospital->id ? 'selected' : '' }}>
                                        {{ $hospital->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
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
        <x-button class="btn-hover w-full max-w-xs mx-auto py-3 px-4 bg-[#0CC0DF] text-white font-semibold rounded-xl shadow-lg hover:scale-[1.02] transform transition-all duration-300 text-center">
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
           class="inline-block w-full max-w-xs mx-auto py-2 px-4 bg-white border-2 border-[#0CC0DF] text-[#0CC0DF] hover:bg-[#0CC0DF]/5 font-medium rounded-xl transition-all duration-200 hover:shadow-md">
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
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(fieldId + 'Toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            }
        }
        
        // Add random health elements periodically
        function addRandomHealthElement() {
            const healthIcons = [
                'fas fa-syringe',
                'fas fa-stethoscope', 
                'fas fa-pills',
                'fas fa-hospital',
                'fas fa-heartbeat',
                'fas fa-band-aid',
                'fas fa-flask',
                'fas fa-microscope',
                'fas fa-user-md',
                'fas fa-ambulance',
                'fas fa-thermometer',
                'fas fa-briefcase-medical',
                'fas fa-tooth',
                'fas fa-eye',
                'fas fa-lungs'
            ];
            const container = document.querySelector('.floating-health-elements');
            
            if (container) {
                const element = document.createElement('i');
                element.className = 'health-element ' + healthIcons[Math.floor(Math.random() * healthIcons.length)];
                element.style.left = Math.random() * 100 + '%';
                element.style.fontSize = (20 + Math.random() * 25) + 'px';
                element.style.animationDuration = (15 + Math.random() * 10) + 's';
                element.style.animationDelay = '0s';
                
                container.appendChild(element);
                
                // Remove element after animation
                setTimeout(() => {
                    if (element.parentNode) {
                        element.parentNode.removeChild(element);
                    }
                }, 25000);
            }
        }

        // Add new health elements every 3 seconds
        setInterval(addRandomHealthElement, 3000);
        
        // Initial health elements
        setTimeout(() => {
            for (let i = 0; i < 3; i++) {
                setTimeout(addRandomHealthElement, i * 1000);
            }
        }, 1000);
    </script>
</x-guest-layout>