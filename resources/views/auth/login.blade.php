<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pegawai - Tubes MSBD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #00b646 0%, #009933 100%);
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(0, 182, 70, 0.2);
        }
        .shake {
            animation: shake 0.5s;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        .leaf-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%2300b646' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.1;
            z-index: -1;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-center items-center p-4 relative overflow-hidden">
    <!-- Background Leaf Pattern -->
    <div class="leaf-bg"></div>
    
    <!-- Background Gradient -->
    <div class="gradient-bg absolute inset-0 z-0"></div>
    
    <!-- Main Card -->
    <div class="bg-white/95 backdrop-blur-sm shadow-2xl rounded-2xl p-8 w-full max-w-md transform transition-all duration-300 hover:shadow-3xl z-10 border border-green-100">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-block">
                <div class="w-24 h-24 rounded-full bg-gradient-to-r from-green-500 to-emerald-600 flex items-center justify-center mx-auto mb-4 shadow-lg overflow-hidden border-4 border-white">
                    <!-- Logo Image -->
                    <img src="{{ asset('images/Logo 1.jpg') }}" 
                         alt="Logo Tubes MSBD" 
                         class="w-full h-full object-cover"
                         onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iOTYiIGhlaWdodD0iOTYiIHZpZXdCb3g9IjAgMCA5NiA5NiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNNjQgMzJDNjQgMTQuNzcgNTAuMjMgNCAzNiA0UzggMTQuNzcgOCAzMmg4YzAtNi42MyA0LjQzLTEyIDEwLTEyczEwIDUuMzcgMTAgMTJIMzZDNDkuMjMgMzIgNjQgNDUuNzcgNjQgNjRzLTE0Ljc3IDMyLTMyIDMyYy0xNy4yMyAwLTMyLTE0LjIzLTMyLTMyaC04YzAgMTcuMjMgMTQuMjMgMzIgMzIgMzJzMzItMTQuNzcgMzItMzJjMC0xNy4yMy0xNC4yMy0zMi0zMi0zMloiIGZpbGw9IiMwMGI2NDYiLz48L3N2Zz4='">
                </div>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <span class="bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                    Login Pegawai
                </span>
            </h1>
            <p class="text-gray-600 text-sm">
                Sistem Manajemen Kebun Sawit
            </p>
            <div class="mt-2 flex justify-center items-center gap-2">
                <i class="fas fa-seedling text-green-500"></i>
                <span class="text-xs text-gray-500">Berkembang Bersama Alam</span>
                <i class="fas fa-seedling text-green-500"></i>
            </div>
        </div>

        <!-- Session Status -->
        @if(session('status'))
            <div class="mb-6 p-4 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-800 text-sm flex items-center gap-3 animate-pulse">
                <i class="fas fa-check-circle text-green-600 text-lg"></i>
                <div>
                    <p class="font-medium">{{ session('status') }}</p>
                </div>
            </div>
        @endif

        <!-- Error Message -->
        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-gradient-to-r from-red-50 to-red-50 border border-red-200 text-red-800 text-sm animate-shake" id="error-message">
                <div class="flex items-center gap-3 mb-2">
                    <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                    <p class="font-medium">Terjadi Kesalahan</p>
                </div>
                <ul class="space-y-1 pl-7">
                    @foreach($errors->all() as $error)
                        <li class="flex items-center gap-2">
                            <i class="fas fa-circle text-xs text-red-500"></i>
                            <span>{{ $error }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" id="loginForm" class="space-y-6">
            @csrf

            <!-- No HP Field -->
            <div class="space-y-2">
                <label for="no_hp" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-user-circle mr-2 text-green-600"></i>
                    Nomor HP / Username
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400 group-focus-within:text-green-600 transition-colors"></i>
                    </div>
                    <input 
                        id="no_hp" 
                        type="text" 
                        name="no_hp" 
                        value="{{ old('no_hp') }}" 
                        required 
                        autofocus 
                        autocomplete="tel"
                        class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-300 outline-none input-focus @error('no_hp') border-red-500 @enderror"
                        placeholder="Contoh: 081234567890 atau Hasbi Zahy Rabani"
                        oninput="validateNoHP(this)"
                    >
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span id="no_hp_status" class="hidden">
                            <i class="fas fa-check text-green-500"></i>
                        </span>
                    </div>
                </div>
                @error('no_hp')
                    <p class="text-red-600 text-sm flex items-center gap-2 mt-1">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </p>
                @enderror
                <div id="no_hp_hint" class="text-xs text-gray-500 mt-1 hidden">
                    <i class="fas fa-info-circle mr-1 text-green-500"></i>
                    Gunakan nomor HP atau username yang terdaftar
                </div>
            </div>

            <!-- Password Field -->
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <label for="password" class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-lock mr-2 text-green-600"></i>
                        Password
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" 
                           class="text-xs text-green-600 hover:text-green-800 hover:underline transition-colors">
                            <i class="fas fa-key mr-1"></i>
                            Lupa password?
                        </a>
                    @endif
                </div>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400 group-focus-within:text-green-600 transition-colors"></i>
                    </div>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        class="w-full pl-10 pr-10 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-300 outline-none input-focus @error('password') border-red-500 @enderror"
                        placeholder="Masukkan password Anda"
                    >
                    <button type="button" 
                            onclick="togglePassword()" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-green-600 transition-colors">
                        <i id="togglePasswordIcon" class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-600 text-sm flex items-center gap-2 mt-1">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </p>
                @enderror

            <!-- Remember Me & Submit -->
            <div class="space-y-4">
                <div class="flex items-center">
                    <input 
                        id="remember_me" 
                        type="checkbox"
                        class="h-5 w-5 rounded border-gray-300 text-green-600 focus:ring-green-500 cursor-pointer"
                        name="remember"
                    >
                    <label for="remember_me" class="ms-3 text-sm text-gray-700 cursor-pointer select-none">
                        <i class="fas fa-bookmark mr-2 text-green-500"></i>
                        Ingat saya di perangkat ini
                    </label>
                </div>

                <button 
                    type="submit" 
                    id="submitBtn"
                    class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold py-3.5 px-4 rounded-xl hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-4 focus:ring-green-300 transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl flex items-center justify-center gap-3 group"
                >
                    <i class="fas fa-sign-in-alt group-hover:rotate-12 transition-transform"></i>
                    <span id="submitText">{{ __('Masuk') }}</span>
                    <div id="loadingSpinner" class="hidden">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </button>
            </div>
        </form>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-green-100 space-y-4">
            <!-- Back to Home -->
            <div class="text-center">
                <a href="{{ url('/') }}" 
                   class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-green-700 hover:underline transition-colors group">
                    <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    Kembali ke Halaman Utama
                </a>
            </div>
        </div>
    </div> <!-- End Main Card -->

    <!-- Floating Leaves -->
    <div class="absolute top-10 left-10 w-8 h-8 text-green-400 opacity-20 animate-bounce">
        <i class="fas fa-leaf text-3xl"></i>
    </div>
    <div class="absolute bottom-10 right-10 w-6 h-6 text-green-400 opacity-20 animate-bounce" style="animation-delay: 0.5s;">
        <i class="fas fa-leaf text-2xl"></i>
    </div>
    <div class="absolute top-20 right-20 w-4 h-4 text-green-400 opacity-20 animate-bounce" style="animation-delay: 1s;">
        <i class="fas fa-leaf text-xl"></i>
    </div>

    <!-- JavaScript -->
    <script>
        // Toggle Password Visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Validate No HP Format
        function validateNoHP(input) {
            const value = input.value.trim();
            const statusIcon = document.getElementById('no_hp_status');
            const hint = document.getElementById('no_hp_hint');
            
            // Show hint when user starts typing
            if (value.length > 0) {
                hint.classList.remove('hidden');
            } else {
                hint.classList.add('hidden');
            }
            
            // Basic validation
            if (value.length >= 3) {
                statusIcon.classList.remove('hidden');
                input.classList.remove('border-red-500');
                input.classList.add('border-green-500');
            } else {
                statusIcon.classList.add('hidden');
                input.classList.remove('border-green-500');
                if (value.length > 0) {
                    input.classList.add('border-red-500');
                } else {
                    input.classList.remove('border-red-500');
                }
            }
        }

        // Form submission validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const noHP = document.getElementById('no_hp').value.trim();
            const password = document.getElementById('password').value.trim();
            
            // Cek jika ada input kosong
            if (!noHP || !password) {
                e.preventDefault(); // Hentikan submit jika validasi gagal
                
                if (!noHP) {
                    document.getElementById('no_hp').focus();
                    document.getElementById('no_hp').classList.add('shake');
                    setTimeout(() => {
                        document.getElementById('no_hp').classList.remove('shake');
                    }, 500);
                }
                
                if (!password) {
                    document.getElementById('password').focus();
                    document.getElementById('password').classList.add('shake');
                    setTimeout(() => {
                        document.getElementById('password').classList.remove('shake');
                    }, 500);
                }
                
                return false;
            }
            
            // Jika validasi lolos, tampilkan loading
            const submitText = document.getElementById('submitText');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const submitBtn = document.getElementById('submitBtn');
            
            submitText.classList.add('hidden');
            loadingSpinner.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.classList.remove('hover:-translate-y-0.5');
            
            // Form akan disubmit secara normal
            return true;
        });

        // Auto-hide error message
        document.addEventListener('DOMContentLoaded', function() {
            const errorMessage = document.getElementById('error-message');
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.opacity = '0';
                    errorMessage.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => {
                        errorMessage.style.display = 'none';
                    }, 500);
                }, 5000);
            }
            
            // Focus on no_hp field if empty
            const noHPInput = document.getElementById('no_hp');
            if (!noHPInput.value) {
                noHPInput.focus();
            }
            
            // Prevent form resubmission
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
            
            // Add subtle background animation
            document.body.style.animation = 'gradientShift 10s ease infinite';
            
            // Preload logo untuk menghindari flash
            const logo = new Image();
            logo.src = "{{ asset('images/Logo 1.jpg') }}";
        });

        // Add CSS for gradient animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes gradientShift {
                0% { background: linear-gradient(135deg, #00b646 0%, #009933 100%); }
                50% { background: linear-gradient(135deg, #009933 0%, #00b646 100%); }
                100% { background: linear-gradient(135deg, #00b646 0%, #009933 100%); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>