<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyManage - Forgot Password</title>
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
            0% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(10vw, -10vh) rotate(10deg); }
            50% { transform: translate(5vw, -20vh) rotate(20deg); }
            75% { transform: translate(-5vw, -30vh) rotate(10deg); }
            100% { transform: translate(0, -40vh) rotate(0deg); }
        }
        img.transparent-bg {
            background-color: transparent;
            mix-blend-mode: multiply;
        }
    </style>

    <div class="gradient-bg flex items-center justify-center min-h-screen p-4">
        <div class="floating-health-elements fixed inset-0 pointer-events-none">
            <i class="health-element fas fa-heartbeat" style="left:10%; top:80%; font-size:28px;"></i>
            <i class="health-element fas fa-stethoscope" style="left:25%; top:70%; font-size:24px;"></i>
            <i class="health-element fas fa-pills" style="left:40%; top:60%; font-size:32px;"></i>
            <i class="health-element fas fa-hospital" style="left:60%; top:75%; font-size:36px;"></i>
            <i class="health-element fas fa-syringe" style="left:75%; top:65%; font-size:22px;"></i>
            <i class="health-element fas fa-user-md" style="left:85%; top:55%; font-size:30px;"></i>
            <i class="health-element fas fa-ambulance" style="left:15%; top:55%; font-size:26px;"></i>
            <i class="health-element fas fa-microscope" style="left:50%; top:80%; font-size:34px;"></i>
        </div>

        <div class="w-full max-w-md login-card">
            <div class="bg-white rounded-3xl card-shadow p-8">
                <div class="text-center mb-8">
                    <div class="logo-glow mb-4">
                        <img src="{{ asset('images/Logo Easy Manage.png') }}" alt="EasyManage Logo" class="w-24 h-24 mx-auto transparent-bg object-contain">
                    </div>
                    <h1 class="text-3xl font-bold text-[#0CC0DF] mb-2">EasyManage</h1>
                    <p class="text-black/80 text-sm">Reset Password Anda</p>
                </div>

                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <x-validation-errors class="mb-4" />

                <div class="mb-4 text-sm text-gray-600">
                    {{ __('Lupa kata sandi Anda? Tidak masalah. Beri tahu kami alamat email Anda, dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi yang memungkinkan Anda memilih yang baru.') }}
                </div>

                <form class="space-y-6" method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-800">Email</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"></i>
                            <input
                                id="email"
                                class="input-focus block w-full pl-10 pr-3 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-800"
                                placeholder="Masukkan Email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                            />
                        </div>
                    </div>

                    <button type="submit" class="btn-hover w-full py-3 px-4 bg-[#0CC0DF] text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <span class="flex items-center justify-center space-x-2 font-bold">
                            <i class="fas fa-paper-plane"></i>
                            <span>Kirim Link Reset Password</span>
                        </span>
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="btn-hover inline-flex items-center px-3 py-1.5 border border-[#0CC0DF] text-[#0CC0DF] text-xs font-semibold rounded-full shadow-md hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Kembali ke Login
                    </a>
                </div>

                <div class="text-center mt-8">
                    <p class="text-black/60 text-xs">© 2025 EasyManage. Semua hak dilindungi.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.style.transform = 'scale(1.02)';
            });
            input.addEventListener('blur', () => {
                input.parentElement.style.transform = 'scale(1)';
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const logo = document.querySelector('.logo-glow');
            if (logo) {
                logo.addEventListener('mouseenter', () => {
                    logo.style.transform = 'rotate(5deg) scale(1.1)';
                    logo.style.transition = 'transform 0.6s ease';
                });
                logo.addEventListener('mouseleave', () => {
                    logo.style.transform = 'rotate(0deg) scale(1)';
                });
            }
        });
    </script>
</body>
</html>
