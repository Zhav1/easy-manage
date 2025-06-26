<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyManage - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
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
        
        .glassmorphism {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
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
        
        <!-- Login Card -->
        <div class="w-full max-w-md">
            <div class="bg-white rounded-3xl card-shadow p-8">

                <!-- Logo Section -->
                <div class="text-center mb-8">
                   <div class="logo-glow mb-4">
    <img
        src="{{ asset('images/Logo Easy Manage.png') }}"   
        alt="Foto Profil"
        class="w-24 h-24 mx-auto    
               transparent-bg                
               object-contain"       
    >
</div>

                    <h1 class="text-3xl font-bold text-[#0CC0DF] mb-2">EasyManage</h1>
                    <p class="text-black/80 text-sm">Masuk ke akun Anda</p>
                </div>
                
                <!-- Login Form -->
                <form class="space-y-6" onsubmit="handleLogin(event)">
                    <!-- ID Pegawai -->
                    <div class="space-y-2">
                      <!-- ID Pegawai -->
<label class="block text-sm font-medium text-gray-800">ID Pegawai</label>
<div class="relative">
    <i class="fas fa-user absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"></i>
    <input
        class="input-focus block w-full pl-10 pr-3 py-3
               bg-gray-50 border border-gray-300 rounded-xl
               text-gray-800 placeholder-black-400
               focus:ring-2 focus:ring-green-500 focus:border-green-500" 
        placeholder="Masukkan ID Pegawai" />
</div>

                    </div>
                    <!-- Password -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-black/90">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                class="input-focus block w-full pl-10 pr-3 py-3
                                       bg-gray-50 border border-gray-300 rounded-xl
                                       text-gray-800 placeholder-gray-400
                                       focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                placeholder="Masukkan password"
                            />
                            <button
                                type="button"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                onclick="togglePassword()"
                            >
                                <i id="passwordToggle" class="fas fa-eye-slash text-gray-400 hover:text-gray-600 transition-colors"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <input
                                id="remember_me"
                                name="remember"
                                type="checkbox"
                                class="w-4 h-4 text-green-600 bg-gray-50 border-gray-300 rounded focus:ring-green-500 focus:ring-2"
                            />
                            <label for="remember_me" class="text-sm text-black/90">
                                Ingat saya
                            </label>
                        </div>
                        <a href="#" class="text-sm text-black/80 hover:text-black transition-colors">
                            Lupa password?
                        </a>
                    </div>
                    
                    <!-- Login Button -->
                    <button
                        type="submit"
                        class="btn-hover w-full py-3 px-4 bg-white text-purple-700 font-semibold rounded-xl shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-white/50 focus:ring-offset-2 focus:ring-offset-transparent transition-all duration-300"
                    >
                        <span class="flex items-center justify-center space-x-2 font-bold text-[#0CC0DF]">
                            <i class="fas fa-sign-in-alt font-bold text-[#0CC0DF]"></i>
                            <span>Masuk</span>
                        </span>
                    </button>
                </form>
                
                <!-- Additional Links -->
                <div class="mt-6 text-center">
                    <p class="text-black/70 text-sm">
                        Belum punya akun? 
                        <a href="#" class="text-black hover:text-black/80 font-medium transition-colors">
                            Hubungi Administrator
                        </a>
                    </p>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="text-center mt-8">
                <p class="text-black/60 text-xs">
                    © 2024 EasyManage. Semua hak dilindungi.
                </p>
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('passwordToggle');
            
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
        
        function handleLogin(event) {
            event.preventDefault();
            
            const button = event.target.querySelector('button[type="submit"]');
            const originalContent = button.innerHTML;
            
            // Loading state
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            button.disabled = true;
            
            // Simulate login process
            setTimeout(() => {
                button.innerHTML = originalContent;
                button.disabled = false;
                alert('Login berhasil! (Demo)');
            }, 2000);
        }
        
        // Add subtle animations to input fields
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
        
        // Add hover animation to logo
        document.addEventListener('DOMContentLoaded', function() {
            const logoContainer = document.querySelector('.logo-glow');
            if (logoContainer) {
                logoContainer.addEventListener('mouseenter', function() {
                    this.style.transform = 'rotate(5deg) scale(1.1)';
                    this.style.transition = 'transform 0.6s ease';
                });
                
                logoContainer.addEventListener('mouseleave', function() {
                    this.style.transform = 'rotate(0deg) scale(1)';
                });
            }
        });

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
</body>
</html>