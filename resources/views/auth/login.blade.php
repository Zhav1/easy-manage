<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <img src="{{ asset('images/l2.jpg') }}" alt="EasyManage Logo" class="w-28 h-auto mx-auto  transparent-bg ">
            
        </x-slot>
<style>
    img.transparent-bg {
        background-color: transparent;
        mix-blend-mode: multiply;
    }
</style>

<x-slot name="logo">
    <img src="{{ asset('images/Logo Easy Manage.png') }}" alt="EasyManage Logo" class="w-28 h-auto mx-auto transparent-bg">
</x-slot>
        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mt-4">
            <x-label for="id_pegawai" value="{{ __('ID Pegawai') }}" />
            <x-input id="id_pegawai" class="block mt-1 w-full" type="text" name="id_pegawai" :value="old('id_pegawai')" required autocomplete="off" />
    </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-button class="ms-4">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
