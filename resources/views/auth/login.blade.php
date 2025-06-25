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
        
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        
        .floating-elements span {
            position: absolute;
            display: block;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.1);
            animation: animate 25s linear infinite;
            bottom: -150px;
            border-radius: 50%;
        }
        
        .floating-elements span:nth-child(1) {
            left: 25%;
            width: 80px;
            height: 80px;
            animation-delay: 0s;
        }
        
        .floating-elements span:nth-child(2) {
            left: 10%;
            width: 20px;
            height: 20px;
            animation-delay: 2s;
            animation-duration: 12s;
        }
        
        .floating-elements span:nth-child(3) {
            left: 70%;
            width: 20px;
            height: 20px;
            animation-delay: 4s;
        }
        
        .floating-elements span:nth-child(4) {
            left: 40%;
            width: 60px;
            height: 60px;
            animation-delay: 0s;
            animation-duration: 18s;
        }
        
        .floating-elements span:nth-child(5) {
            left: 65%;
            width: 20px;
            height: 20px;
            animation-delay: 0s;
        }
        
        .floating-elements span:nth-child(6) {
            left: 75%;
            width: 110px;
            height: 110px;
            animation-delay: 3s;
        }
        
        .floating-elements span:nth-child(7) {
            left: 35%;
            width: 150px;
            height: 150px;
            animation-delay: 7s;
        }
        
        .floating-elements span:nth-child(8) {
            left: 50%;
            width: 25px;
            height: 25px;
            animation-delay: 15s;
            animation-duration: 45s;
        }
        
        .floating-elements span:nth-child(9) {
            left: 20%;
            width: 15px;
            height: 15px;
            animation-delay: 2s;
            animation-duration: 35s;
        }
        
        .floating-elements span:nth-child(10) {
            left: 85%;
            width: 150px;
            height: 150px;
            animation-delay: 0s;
            animation-duration: 11s;
        }
        
        @keyframes animate {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
                border-radius: 0;
            }
            100% {
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
                border-radius: 50%;
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
        <!-- Floating Background Elements -->
        <div class="floating-elements">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
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
                        <label for="password" class="block text-sm -medium text-black/90">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-white/60"></i>
                            </div>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
class="input-focus block w-full pl-10 pr-3 py-3
               bg-gray-50 border border-gray-300 rounded-xl
               text-gray-800 placeholder-black-400
               focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                placeholder="Masukkan password"
                            />
                            <button
                                type="button"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                onclick="togglePassword()"
                            >
                                <i id="passwordToggle" class="fas fa-eye-slash text-white/60 hover:text-white/90 transition-colors"></i>
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
                                class="w-4 h-4 text-purple-600 bg-white/10 border-white/30 rounded focus:ring-purple-500 focus:ring-2"
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
                        class="btn-hover w-full py-3 px-4 bg-white text-purple-700 -semibold rounded-xl shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-white/50 focus:ring-offset-2 focus:ring-offset-transparent transition-all duration-300"
                    >
                        <span class="flex items-center justify-center space-x-2 font-bold text-[#0CC0DF]">
                            <i class="fas fa-sign-in-alt font-bold text-[#0CC0DF] "></i>
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
        
        // Add typing animation to logo
        document.addEventListener('DOMContentLoaded', function() {
            const logo = document.querySelector('.logo-glow > div');
            logo.addEventListener('mouseenter', function() {
                this.style.transform = 'rotate(360deg) scale(1.1)';
                this.style.transition = 'transform 0.6s ease';
            });
            
            logo.addEventListener('mouseleave', function() {
                this.style.transform = 'rotate(0deg) scale(1)';
            });
        });
    </script>
</body>
</html>