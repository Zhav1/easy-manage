<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyManage - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg,#E1F6E9 0%,#E1F6E9);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        
        .health-element {
            color: #065f46;
            position: fixed;
            z-index: 1;
            opacity: 0.6;
            pointer-events: none;
            animation: float 30s linear infinite;
        }
        
        .login-card {
            position: relative;
            z-index: 10;
        }
        
        .card-shadow {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(0, 0, 0, 0.1);
        }
        
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(12, 192, 223, 0.2);
        }
        
        .logo-glow:hover {
            filter: drop-shadow(0 0 8px rgba(12, 192, 223, 0.4));
        }
        
        @keyframes float {
            0% {
                transform: translate(0, 0) rotate(0deg);
            }
            25% {
                transform: translate(10vw, -10vh) rotate(10deg);
            }
            50% {
                transform: translate(5vw, -20vh) rotate(20deg);
            }
            75% {
                transform: translate(-5vw, -30vh) rotate(10deg);
            }
            100% {
                transform: translate(0, -40vh) rotate(0deg);
            }
        }
    </style>
    
    <div class="gradient-bg flex items-center justify-center min-h-screen p-4">
        <!-- Floating Health Elements in Background -->
        <div class="floating-health-elements fixed inset-0 pointer-events-none">
            <!-- Static health elements that float continuously -->
            <i class="health-element fas fa-heartbeat" style="left:10%; top:80%; font-size:28px; animation-delay:0s;"></i>
            <i class="health-element fas fa-stethoscope" style="left:25%; top:70%; font-size:24px; animation-delay:2s;"></i>
            <i class="health-element fas fa-pills" style="left:40%; top:60%; font-size:32px; animation-delay:4s;"></i>
            <i class="health-element fas fa-hospital" style="left:60%; top:75%; font-size:36px; animation-delay:1s;"></i>
            <i class="health-element fas fa-syringe" style="left:75%; top:65%; font-size:22px; animation-delay:3s;"></i>
            <i class="health-element fas fa-user-md" style="left:85%; top:55%; font-size:30px; animation-delay:5s;"></i>
            <i class="health-element fas fa-ambulance" style="left:15%; top:55%; font-size:26px; animation-delay:6s;"></i>
            <i class="health-element fas fa-microscope" style="left:50%; top:80%; font-size:34px; animation-delay:7s;"></i>
        </div>
        
        <!-- Login Card -->
        <div class="w-full max-w-md login-card">
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
                    <p class="text-black/80 text-sm">Masuk ke akun Anda</p>
                </div>
                
                <!-- Validation Errors -->
                <x-validation-errors class="mb-4" />

                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif
                
                <!-- Login Form -->
                <form class="space-y-6" method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-800">Email</label>
                        <div class="relative">
                            <i class="fas fa-user absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"></i>
                            <input
                                id="email"
                                class="input-focus block w-full pl-10 pr-3 py-3
                                    bg-gray-50 border border-gray-300 rounded-xl
                                    text-gray-800 placeholder-black-400
                                    focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                placeholder="Masukkan Email"
                                type="email"
                                name="email"
                                :value="old('email')"
                                required
                                autofocus
                                autocomplete="username"
                            />
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
                                autocomplete="current-password"
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
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-black/80 hover:text-black transition-colors">
                                Lupa password?
                            </a>
                        @endif
                    </div>
                    
                    <!-- Login Button -->
                    <button
                        type="submit"
                        class="btn-hover w-full py-3 px-4 bg-[#0CC0DF] text-white font-semibold rounded-xl shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-white/50 focus:ring-offset-2 focus:ring-offset-transparent transition-all duration-300"
                    >
                        <span class="flex items-center justify-center space-x-2 font-bold">
                            <i class="fas fa-sign-in-alt font-bold"></i>
                            <span>Masuk</span>
                        </span>
                    </button>

                <!-- Additional Links -->
                <div class="mt-6 text-center">
                    <div class="flex items-center justify-center space-x-2">
                        <p class="text-black/70 text-sm">
                            Belum punya akun?
                        </p>
                        <a href="{{ route('register') }}" 
                           class="btn-hover inline-flex items-center px-3 py-1.5 bg-[#0CC0DF] text-white text-xs font-semibold rounded-full shadow-md hover:shadow-lg transition-all duration-300">
                            <i class="fas fa-user-plus mr-1"></i>
                            Daftar
                        </a>
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
    </script>
</body>
</html>