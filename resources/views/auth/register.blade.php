<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyManage - Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    </style>
</head>
<body>
<div class="gradient-bg flex items-center justify-center min-h-screen p-4">
    <!-- Floating Health Elements -->
    <div class="floating-health-elements fixed inset-0 pointer-events-none">
        <i class="health-element fas fa-heartbeat" style="left:10%; top:80%; font-size:28px; animation-delay:0s;"></i>
        <i class="health-element fas fa-stethoscope" style="left:25%; top:70%; font-size:24px; animation-delay:2s;"></i>
        <i class="health-element fas fa-pills" style="left:40%; top:60%; font-size:32px; animation-delay:4s;"></i>
        <i class="health-element fas fa-hospital" style="left:60%; top:75%; font-size:36px; animation-delay:1s;"></i>
        <i class="health-element fas fa-syringe" style="left:75%; top:65%; font-size:22px; animation-delay:3s;"></i>
        <i class="health-element fas fa-user-md" style="left:85%; top:55%; font-size:30px; animation-delay:5s;"></i>
        <i class="health-element fas fa-ambulance" style="left:15%; top:55%; font-size:26px; animation-delay:6s;"></i>
        <i class="health-element fas fa-microscope" style="left:50%; top:80%; font-size:34px; animation-delay:7s;"></i>
    </div>

    <!-- Register Card -->
    <div class="w-full max-w-md z-10">
        <div class="bg-white rounded-3xl card-shadow p-8">
            <!-- Logo Section -->
            <div class="text-center mb-8">
                <div class="logo-glow mb-4">
                    <img src="{{ asset('images/Logo Easy Manage.png') }}" alt="EasyManage Logo" class="w-24 h-24 mx-auto object-contain">
                </div>
                <h1 class="text-3xl font-bold text-[#0CC0DF] mb-2">EasyManage</h1>
                <p class="text-black/80 text-sm">Daftar Akun Baru</p>
            </div>

            <x-validation-errors class="mb-4 p-4 bg-red-500/20 border border-red-400/50 rounded-lg text-red-200" />

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap" required class="input-focus w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-800 placeholder-gray-400" />

                <select name="department_id" required class="input-focus w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-800">
                    <option value="">Pilih Ruangan</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>

                <select name="hospital_id" required class="input-focus w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-800">
                    <option value="">Pilih Rumah Sakit</option>
                    @foreach($hospitals as $hospital)
                        <option value="{{ $hospital->id }}" {{ old('hospital_id') == $hospital->id ? 'selected' : '' }}>{{ $hospital->name }}</option>
                    @endforeach
                </select>

                <input type="text" name="id_pegawai" value="{{ old('id_pegawai') }}" placeholder="ID Pegawai" required class="input-focus w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-800 placeholder-gray-400" />

                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required class="input-focus w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-800 placeholder-gray-400" />

                <input type="password" name="password" placeholder="Password" required class="input-focus w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-800 placeholder-gray-400" />

                <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required class="input-focus w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-800 placeholder-gray-400" />

                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="flex items-start space-x-3 text-sm text-gray-700">
                        <input type="checkbox" name="terms" required class="w-4 h-4 text-green-600 bg-gray-50 border-gray-300 rounded focus:ring-green-500 focus:ring-2">
                        <label for="terms" class="leading-snug">
                            {!! __('Saya menyetujui :terms_of_service dan :privacy_policy', [
                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="text-[#0CC0DF] underline">Syarat & Ketentuan</a>',
                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="text-[#0CC0DF] underline">Kebijakan Privasi</a>',
                            ]) !!}
                        </label>
                    </div>
                @endif

                <button type="submit" class="btn-hover w-full py-3 bg-[#0CC0DF] text-white font-semibold rounded-xl shadow-md hover:scale-[1.02] transform transition duration-300">
                    <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
                </button>

                <div class="text-center pt-3">
                    <p class="text-sm text-gray-600">Sudah punya akun?</p>
                    <a href="{{ route('login') }}" class="mt-2 inline-block w-full max-w-xs mx-auto py-2 px-4 bg-white border-2 border-[#0CC0DF] text-[#0CC0DF] font-medium rounded-xl hover:bg-[#0CC0DF]/5 transition duration-200 hover:shadow-md">
                        <span class="font-semibold">Masuk Disini</span>
                    </a>
                </div>
            </form>

            <div class="text-center mt-8">
                <p class="text-black/60 text-xs">© 2024 EasyManage. Semua hak dilindungi.</p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
