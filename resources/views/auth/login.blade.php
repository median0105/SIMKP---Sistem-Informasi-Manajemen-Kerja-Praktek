<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMKP - Login</title>

    {{-- Bila proyekmu memakai Vite, kamu boleh ganti CDN ini ke @vite([...]) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.1/dist/dotlottie-wc.js" type="module"></script>

    <style> 
        body { 
            font-family: 'Inter', sans-serif; 
            overflow: hidden; /* Mencegah scroll global */
        }
        
        /* Animasi untuk form login */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-form {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        /* Efek glassmorphism untuk form */
        .glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        /* Efek hover untuk input */
        .input-field {
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(29, 60, 150, 0.12);
        }
        
        /* Efek untuk tombol login */
        .login-btn {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .login-btn:hover::before {
            left: 100%;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(29, 60, 150, 0.25);
        }
        
        /* Animasi untuk ikon */
        .icon-animate {
            transition: all 0.3s ease;
        }
        
        .input-field:focus ~ .input-icon {
            transform: scale(1.1);
            color: #1D3C96;
        }
        
        /* Custom checkbox styling */
        .custom-checkbox {
            width: 18px;
            height: 18px;
            border: 2px solid #d1d5db;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .custom-checkbox.checked {
            background-color: #1D3C96;
            border-color: #1D3C96;
        }
        
        .checkbox-input:checked + .custom-checkbox .check-icon {
            opacity: 1;
        }
        
        .checkbox-input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        /* Container utama yang responsif */
        .login-container {
            min-height: 100vh;
            max-height: 100vh;
            overflow: hidden;
        }

        /* Konten form yang bisa discroll jika diperlukan */
        .form-content {
            max-height: calc(100vh - 2rem);
            overflow-y: auto;
            padding: 1rem 0;
        }

        /* Scrollbar custom untuk form */
        .form-content::-webkit-scrollbar {
            width: 4px;
        }

        .form-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .form-content::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        .form-content::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Responsive adjustments untuk layar sangat kecil */
        @media (max-height: 600px) {
            .form-content {
                padding: 0.5rem 0;
            }
            
            .login-form {
                padding: 1rem !important;
            }
            
            .header-section {
                margin-bottom: 1rem !important;
            }
            
            .form-fields {
                gap: 0.75rem !important;
            }
        }
    </style>
</head>
<body class="bg-[#1D3C96]">

<div class="login-container grid grid-cols-1 md:grid-cols-2">
    <!-- Left Side - Decorative Panel -->
    <div class="hidden lg:flex flex-col justify-center items-center p-8 xl:p-12 2xl:p-16 bg-[#1D3C96] text-white relative overflow-hidden">
        <!-- Background decorative elements -->
        <div class="absolute inset-0">
            <div class="absolute top-20 left-20 w-32 h-32 bg-blue-400 rounded-full opacity-20 blur-xl"></div>
            <div class="absolute top-60 right-32 w-24 h-24 bg-purple-400 rounded-full opacity-30 blur-lg"></div>
            <div class="absolute bottom-40 left-16 w-40 h-40 bg-cyan-400 rounded-full opacity-25 blur-2xl"></div>
            <div class="absolute bottom-20 right-20 w-16 h-16 bg-pink-400 rounded-full opacity-40 blur-lg"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col justify-center items-center text-center w-full h-full">
            <img src="{{ asset('images/Logo Unib.png') }}" alt="UNIB Logo" class="w-24 xl:w-28 2xl:w-32 h-auto mb-2">
            <h1 class="text-2xl xl:text-3xl 2xl:text-4xl font-bold">Universitas Bengkulu</h1>
            <p class="mt-2 xl:mt-3 text-base xl:text-lg font-medium">Selamat Datang</p>
            <p class="text-xs xl:text-sm text-blue-200 mt-1">Sistem Informasi Manajemen Praktek</p>
            <div class="mt-2 w-full flex-1 flex items-center justify-center max-h-[50vh]">
                <dotlottie-wc 
                    src="https://lottie.host/2a79e069-cdea-4221-b4b7-55364a525f84/NZhjQEd9TC.lottie" 
                    class="w-full h-full max-h-[40vh] xl:max-h-[45vh] 2xl:max-h-[50vh]" 
                    autoplay 
                    loop>
                </dotlottie-wc>
            </div>
        </div>

        <!-- Website URL -->
        <div class="absolute bottom-4 xl:bottom-6 2xl:bottom-8 text-center w-full">
            <span class="text-blue-200 text-xs xl:text-sm">www.unib.ac.id</span>
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="w-full flex items-center justify-center p-4 sm:p-6 bg-gradient-to-b from-gray-50/90 to-gray-100/80 backdrop-blur-sm">
        <div class="form-content w-full max-w-md">
            <div class="glass-effect rounded-2xl shadow-xl p-4 sm:p-6 md:p-8 login-form">
                <!-- Header dengan ikon dan dekorasi -->
                <div class="text-center header-section mb-6 md:mb-8">
                    <!-- Ikon user dengan background -->
                    <div class="flex justify-center mb-4 md:mb-5">
                        <div class="relative">
                            <div class="absolute -inset-2 sm:-inset-3 bg-gradient-to-r from-blue-100 to-purple-100 rounded-full opacity-70 blur-sm"></div>
                            <div class="relative w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-[#1D3C96] to-blue-600 rounded-full flex items-center justify-center shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Masuk ke Akun</h2>
                    <p class="mt-1 sm:mt-2 text-gray-600 text-xs sm:text-sm">Silakan masuk untuk mengakses sistem</p>
                    
                    <!-- Garis dekoratif -->
                    <div class="mt-3 sm:mt-4 flex justify-center">
                        <div class="w-10 sm:w-12 h-1 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full"></div>
                    </div>
                </div>

                <!-- Session Status (sukses/notice dari Laravel, mis. reset password terkirim) -->
                @if (session('status'))
                    <div class="mb-4 md:mb-5 p-3 rounded-lg text-sm text-center bg-green-50 text-green-700 border border-green-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Error Message -->
                @if (session('error'))
                    <div class="mb-4 md:mb-5 p-3 rounded-lg text-sm text-center bg-red-50 text-red-700 border border-red-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Global Auth Error (opsional): tampilkan pesan umum -->
                @if ($errors->has('email') || $errors->has('password'))
                    {{-- Biarkan error per field tampil di bawah field masing-masing supaya UI konsisten --}}
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="form-fields space-y-4 md:space-y-5">
                    @csrf

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 icon-animate input-icon" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Masukkan email Anda"
                                class="input-field w-full pl-10 pr-4 py-2 sm:py-3 border border-gray-300 rounded-lg bg-white focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition @error('email') border-red-400 focus:ring-red-500/20 focus:border-red-400 @enderror"
                            >
                        </div>
                        @error('email')
                            <p class="text-sm text-red-600 flex items-center mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field with Toggle -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 icon-animate input-icon" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan kata sandi"
                                class="input-field w-full pl-10 pr-12 py-2 sm:py-3 border border-gray-300 rounded-lg bg-white focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition @error('password') border-red-400 focus:ring-red-500/20 focus:border-red-400 @enderror"
                            >
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition" aria-label="Tampilkan/Sembunyikan Password">
                                <svg id="eye-open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-closed" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-sm text-red-600 flex items-center mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Remember Me (dipindahkan ke kanan) -->
                    <div class="flex justify-end pt-1">
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox" id="remember" name="remember" class="checkbox-input" {{ old('remember') ? 'checked' : '' }}>
                            <div class="custom-checkbox group-hover:border-blue-400 transition-colors">
                                <svg class="w-3 h-3 text-white check-icon opacity-0 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span class="ml-2 text-sm text-gray-700 select-none">Ingat saya</span>
                        </label>
                    </div>

                    <!-- Login Button -->
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-800 text-white py-3 px-4 rounded-xl hover:from-blue-700 hover:to-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform transition-all duration-200 hover:scale-[1.01] font-medium text-lg shadow-lg">
                        Masuk
                    </button>
                    
<!-- Catatan di bawah tombol masuk -->
<div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200 flex items-start">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mt-0.5 mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
    </svg>
    <span class="text-sm text-blue-700 text-justify">
        Untuk keamanan akun, proses reset dan pemulihan kata sandi dilakukan secara terpusat melalui Program Studi. Silakan hubungi administrator Program Studi Anda untuk mendapatkan bantuan
    </span>
</div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
                   
<script>
    // Fungsi untuk toggle password visibility
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeOpen = document.getElementById('eye-open');
        const eyeClosed = document.getElementById('eye-closed');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }
    
    // Fungsi untuk toggle checkbox "Ingat Saya"
    document.addEventListener('DOMContentLoaded', function() {
        const rememberCheckbox = document.getElementById('remember');
        const customCheckbox = document.querySelector('.custom-checkbox');
        
        // Set initial state
        if (rememberCheckbox.checked) {
            customCheckbox.classList.add('checked');
        }
        
        rememberCheckbox.addEventListener('change', function() {
            if (this.checked) {
                customCheckbox.classList.add('checked');
            } else {
                customCheckbox.classList.remove('checked');
            }
        });

        // Prevent form from causing page overflow
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            // Prevent any layout shifts that might cause scrolling
            e.preventDefault();
            // Your actual form submission logic would go here
            // For now, we'll just submit normally
            this.submit();
        });
    });

    // Adjust layout on window resize
    window.addEventListener('resize', function() {
        const formContent = document.querySelector('.form-content');
        const container = document.querySelector('.login-container');
        
        // Ensure container doesn't exceed viewport height
        container.style.maxHeight = '100vh';
        container.style.overflow = 'hidden';
    });
</script>
</body>
</html>