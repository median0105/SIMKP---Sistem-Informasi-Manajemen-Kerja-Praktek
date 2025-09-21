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

    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gradient-to-r from-blue-800 to-blue-900">

<div class="min-h-screen grid grid-cols-1 md:grid-cols-2">
    <!-- Left Side - Decorative Panel -->
    <div class="hidden lg:flex flex-col justify-center items-center p-16 bg-gradient-to-br from-blue-700 to-blue-600 text-white relative overflow-hidden">
        <!-- Background decorative elements -->
        <div class="absolute inset-0">
            <div class="absolute top-20 left-20 w-32 h-32 bg-blue-400 rounded-full opacity-20 blur-xl"></div>
            <div class="absolute top-60 right-32 w-24 h-24 bg-purple-400 rounded-full opacity-30 blur-lg"></div>
            <div class="absolute bottom-40 left-16 w-40 h-40 bg-cyan-400 rounded-full opacity-25 blur-2xl"></div>
            <div class="absolute bottom-20 right-20 w-16 h-16 bg-pink-400 rounded-full opacity-40 blur-lg"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col justify-center items-center text-center">
            <img src="{{ asset('storage/unib.png') }}" alt="UNIB Logo" class="w-40 h-40 mb-6">
            <h1 class="text-3xl sm:text-4xl font-bold">Universitas bengkulu</h1>
            <p class="mt-3 text-lg font-medium">Selamat Datang</p>
            <p class="text-sm text-blue-200 mt-1">Sistem Informasi Manajemen Praktek</p>
        </div>

        <!-- Website URL -->
        <div class="absolute bottom-8 text-center w-full">
            <span class="text-blue-200 text-sm">www.unib.ac.id</span>
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="w-full flex items-center justify-center px-8 py-12 bg-gray-50">
        <div class="max-w-sm w-full">
            <!-- Header -->
            <div class="text-left mb-6">
                <h2 class="text-4xl font-bold text-gray-900">Masuk</h2>
                <p class="mt-2 text-gray-500">Masuk ke akun Anda</p>
            </div>

            <!-- Session Status (sukses/notice dari Laravel, mis. reset password terkirim) -->
            @if (session('status'))
                <div class="mb-4 p-3 rounded-lg text-sm text-center bg-green-100 text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Global Auth Error (opsional): tampilkan pesan umum -->
            @if ($errors->has('email') || $errors->has('password'))
                {{-- Biarkan error per field tampil di bawah field masing-masing supaya UI konsisten --}}
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Alamat Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Masukkan email Anda"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors placeholder:text-gray-400 shadow-sm @error('email') border-red-400 focus:ring-red-200 focus:border-red-400 @enderror"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                {{-- <div>
                    <label for="npm" class="block text-sm font-medium text-gray-700 not-italic">NPM</label>
                    <input id="npm" type="text" name="npm" value="{{ old('npm') }}" required
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Masukkan NPM Anda">
                    <x-input-error :messages="$errors->get('npm')" class="mt-2 text-sm text-red-600" />
                </div> --}}
                <!-- Password Field with Toggle -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan kata sandi"
                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors placeholder:text-gray-400 shadow-sm @error('password') border-red-400 focus:ring-red-200 focus:border-red-400 @enderror"
                        >
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors" aria-label="Tampilkan/Sembunyikan Password">
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
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between text-sm">
                    <label for="remember" class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ old('remember') ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700">Ingat saya</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">Lupa kata sandi?</a>
                    @endif
                </div>

                <!-- Login Button -->
                <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-blue-800 text-white py-3 px-4 rounded-xl hover:from-blue-700 hover:to-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform transition-all duration-200 hover:scale-[1.01] font-medium text-lg shadow-lg">
                    Masuk
                </button>
            </form>
        </div>
    </div>
</div>

<script>
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
</script>
</body>
</html>
