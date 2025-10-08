<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100">
        <div class="bg-white shadow-lg rounded-2xl p-6 w-full sm:w-[400px]">
            <div class="text-center mb-5">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Login" class="mx-auto w-20 mb-3">
                <h2 class="text-2xl font-bold text-gray-800">Login Pegawai</h2>
                <p class="text-gray-500 text-sm">Masuk untuk melanjutkan</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                        :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                        required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center mb-4">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        name="remember">
                    <label for="remember_me" class="ms-2 text-sm text-gray-600">Ingat saya</label>
                </div>

                <x-primary-button class="w-full justify-center">
                    {{ __('Masuk') }}
                </x-primary-button>

                <div class="text-center mt-4">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-indigo-600 hover:underline"
                            href="{{ route('password.request') }}">
                            {{ __('Lupa password?') }}
                        </a>
                    @endif
                    <div class="mt-2">
                        <a href="{{ url('/') }}" class="text-gray-500 hover:text-gray-700 text-sm">← Kembali ke Halaman Utama</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
