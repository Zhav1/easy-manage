<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }

        img.transparent-bg {
    background-color: transparent;
    mix-blend-mode: multiply;
    border-radius: 0 !important;   /* reset sudut */
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
            left: 5%;
            font-size: 35px;
            animation-delay: 0s;
            animation-duration: 22s;
        }
        
        .health-element:nth-child(2) {
            left: 15%;
            font-size: 28px;
            animation-delay: 2s;
            animation-duration: 18s;
        }
        
        .health-element:nth-child(3) {
            left: 25%;
            font-size: 32px;
            animation-delay: 4s;
            animation-duration: 20s;
        }
        
        .health-element:nth-child(4) {
            left: 35%;
            font-size: 26px;
            animation-delay: 6s;
            animation-duration: 24s;
        }
        
        .health-element:nth-child(5) {
            left: 45%;
            font-size: 40px;
            animation-delay: 8s;
            animation-duration: 16s;
        }
        
        .health-element:nth-child(6) {
            left: 55%;
            font-size: 30px;
            animation-delay: 10s;
            animation-duration: 21s;
        }
        
        .health-element:nth-child(7) {
            left: 65%;
            font-size: 38px;
            animation-delay: 12s;
            animation-duration: 19s;
        }
        
        .health-element:nth-child(8) {
            left: 75%;
            font-size: 24px;
            animation-delay: 14s;
            animation-duration: 23s;
        }
        
        .health-element:nth-child(9) {
            left: 85%;
            font-size: 34px;
            animation-delay: 16s;
            animation-duration: 17s;
        }
        
        .health-element:nth-child(10) {
            left: 95%;
            font-size: 29px;
            animation-delay: 18s;
            animation-duration: 25s;
        }
        
        .health-element:nth-child(11) {
            left: 10%;
            font-size: 42px;
            animation-delay: 20s;
            animation-duration: 15s;
        }
        
        .health-element:nth-child(12) {
            left: 80%;
            font-size: 27px;
            animation-delay: 22s;
            animation-duration: 26s;
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
        
        .glassmorphism {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .transparent-bg {
            background-color: white ;
            mix-blend-mode: multiply;
        }
    </style>

    <div class="gradient-bg">
        <!-- Floating Health Elements -->
        <div class="floating-health-elements">
            <div class="health-element">🏥</div>
            <div class="health-element">💊</div>
            <div class="health-element">🩺</div>
            <div class="health-element">❤️</div>
            <div class="health-element">⚕️</div>
            <div class="health-element">🏥</div>
            <div class="health-element">💉</div>
            <div class="health-element">🧑‍⚕️</div>
            <div class="health-element">📋</div>
            <div class="health-element">🩹</div>
            <div class="health-element">🔬</div>
            <div class="health-element">💊</div>
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <!-- Logo Section -->
            
            <!-- Registration Card -->
            <div class="w-full sm:max-w-lg mt-6 px-6 py-8 bg-white card-shadow rounded-3xl">
                <div class="mb-8">
                    <div class="logo-glow">
                        <img src="{{ asset('images/Logo Easy Manage.png') }}" alt="EasyManage Logo" 
                             class="w-28 h-28 mx-auto transparent-bg rounded-full ">
                    </div>
                    <h1 class="text-4xl font-bold  text-[#0CC0DF] text-center mt-4 drop-shadow-lg">EasyManage</h1>
                    <p class="text-black/80 text-center mt-2">Daftar Akun Baru</p>
                </div>
               
                
                <!-- Validation Errors -->
                <x-validation-errors class="mb-4 p-4 bg-red-500/20 border border-red-400/50 rounded-lg text-red-200" />
                
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Name Field -->
                    <div>
                        <x-label for="name" value="{{ __('Nama Lengkap') }}" class="block text-sm font-medium text-black/90 mb-2" />
                        <x-input id="name" 
                                class="input-focus w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-black placeholder-gray/60 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent backdrop-blur-sm" 
                                type="text" 
                                name="name" 
                                :value="old('name')" 
                                required 
                                autofocus 
                                autocomplete="name" 
                                placeholder="Masukkan nama lengkap" />
                    </div>

                    <!-- Hospital Selection -->
                    <div>
                        <x-label for="hospital_id" value="{{ __('Rumah Sakit') }}" class="block text-sm font-medium text-black/90 mb-2" />
                        <select id="hospital_id" name="hospital_id" required
                            class="input-focus w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-black focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent backdrop-blur-sm">
                            <option value="" disabled selected style="color: #374151;">Pilih Rumah Sakit</option>
                            @foreach(\App\Models\Hospital::all() as $hospital)
                                <option value="{{ $hospital->id }}" style="color: #374151;" {{ old('hospital_id') == $hospital->id ? 'selected' : '' }}>
                                    {{ $hospital->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Employee ID -->
                    <div>
                        <x-label for="id_pegawai" value="{{ __('ID Pegawai') }}" class="block text-sm font-medium text-black/90 mb-2" />
                        <x-input id="id_pegawai" 
                                class="input-focus w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-black  placeholder-gray/60 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent backdrop-blur-sm" 
                                type="text" 
                                name="id_pegawai" 
                                :value="old('id_pegawai')" 
                                required 
                                autocomplete="off" 
                                placeholder="Masukkan ID pegawai" />
                    </div>

                    <!-- Email Field -->
                    <div>
                        <x-label for="email" value="{{ __('Email') }}" class="block text-sm font-medium text-black/90 mb-2" />
                        <x-input id="email" 
                                class="input-focus w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-black placeholder-gray/60 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent backdrop-blur-sm" 
                                type="email" 
                                name="email" 
                                :value="old('email')" 
                                required 
                                autocomplete="username" 
                                placeholder="Masukkan alamat email" />
                    </div>

                    <!-- Password Field -->
                    <div>
                        <x-label for="password" value="{{ __('Password') }}" class="block text-sm font-medium text-black/90 mb-2" />
                        <x-input id="password" 
                                class="input-focus w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-black placeholder-gray/60 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent backdrop-blur-sm" 
                                type="password" 
                                name="password" 
                                required 
                                autocomplete="new-password" 
                                placeholder="Masukkan password" />
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <x-label for="password_confirmation" value="{{ __('Konfirmasi Password') }}" class="block text-sm font-medium text-black/90 mb-2" />
                        <x-input id="password_confirmation" 
                                class="input-focus w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-black placeholder-gray/60 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent backdrop-blur-sm" 
                                type="password" 
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password" 
                                placeholder="Konfirmasi password" />
                    </div>

                    <!-- Terms and Conditions -->
                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="flex items-start space-x-3">
                            <x-checkbox name="terms" id="terms" required 
                                       class="mt-1 w-4 h-4 text-cyan-400 bg-white/10 border-white/20 rounded focus:ring-cyan-400 focus:ring-2" />
                            <x-label for="terms" class="text-sm text-black /90">
                                <div class="flex flex-wrap">
                                    {!! __('Saya menyetujui :terms_of_service dan :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="text-cyan-300 hover:text-cyan-200 underline">'.__('Syarat & Ketentuan').'</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="text-cyan-300 hover:text-cyan-200 underline">'.__('Kebijakan Privasi').'</a>',
                                    ]) !!}
                                </div>
                            </x-label>
                        </div>
                    @endif

                    <!-- Register Button and Login Link -->
                    <div class="flex items-center justify-between pt-4">
                        <a class="text-cyan-300 hover:text-cyan-200 text-sm underline focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2 focus:ring-offset-transparent rounded-md" 
                           href="{{ route('login') }}">
                            {{ __('Sudah punya akun?') }}
                        </a>
                        
                        <x-button class="btn-hover bg-gradient-to-r from-emerald-500 to-cyan-500 text-black font-semibold py-3 px-6 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2 focus:ring-offset-transparent ms-4">
                            {{ __('Daftar Sekarang') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>