<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMKP - Register</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.1/dist/dotlottie-wc.js" type="module"></script>
    <style> 
        body { 
            font-family: 'Inter', sans-serif; 
        }
    </style>
</head>
<body class="bg-[#1D3C96]">

<div class="h-screen grid grid-cols-1 lg:grid-cols-2">
    <!-- Left Side - Decorative Panel -->
    <div class="hidden lg:flex flex-col bg-[#1D3C96] text-white relative overflow-hidden">
        <!-- Background decorations -->
        <div class="absolute inset-0">
            <div class="absolute top-10 left-10 w-20 h-20 bg-blue-400 rounded-full opacity-20 blur-xl"></div>
            <div class="absolute top-40 right-20 w-16 h-16 bg-purple-400 rounded-full opacity-30 blur-lg"></div>
            <div class="absolute bottom-20 left-8 w-24 h-24 bg-cyan-400 rounded-full opacity-25 blur-2xl"></div>
            <div class="absolute bottom-10 right-10 w-12 h-12 bg-pink-400 rounded-full opacity-40 blur-lg"></div>
        </div>

        <!-- Logo dan Teks di Top -->
       <div class="relative z-10 flex flex-col items-center pt-4 pb-1 px-8">
            <img src="{{ asset('images/Logo Unib.png') }}" alt="UNIB Logo"  class="w-24 xl:w-28 2xl:w-32 h-auto -mb-4">
            <div class="text-center">
                <div class="h-8"></div>
                <h1 class="text-2xl font-bold">Buat Akun Baru</h1>
                
                <p class="text-sm font-medium text-blue-200 mt-0.5">Sistem Informasi Manajemen Praktek</p>
                <p class="text-sm text-blue-300 mt-0.5">Daftar untuk mulai menggunakan layanan</p>
            </div>
        </div>

        <!-- Animasi lebih besar lagi -->
        <div class="relative z-10 flex-1 flex items-center justify-center p-1">
            <dotlottie-wc 
                src="https://lottie.host/822c4872-3897-4cb2-aaaf-957253b8a6a1/hBSthKLt34.lottie" 
                style="width: 100%; height: 100%; max-width: 650px; max-height: 550px;" 
                autoplay 
                loop>
            </dotlottie-wc>
        </div>

        <!-- Hapus bagian content di bottom karena sudah dipindah ke atas -->
        <div class="relative z-10 text-center pb-4 px-8">
            <span class="text-blue-200 text-xs">www.unib.ac.id</span>
        </div>
    </div>

    <!-- Right Side - Register Form -->
    <div class="w-full flex items-center justify-center px-4 sm:px-5 py-4 bg-gradient-to-br from-slate-50 to-blue-50">
        <div class="w-full max-w-md mx-auto my-auto">
            <!-- Modern Card Container TANPA scroll -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-2xl border border-white/20 p-4 sm:p-6">
                <!-- Header -->
                <div class="text-center mb-4">
                    <h2 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-1">
                        Daftar Akun
                    </h2>
                    <p class="text-gray-600 text-sm">Buat akun baru untuk mengakses SIMKP</p>
                    <div class="w-20 h-1 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full mx-auto mt-2"></div>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-3">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                        <div class="relative">
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                                placeholder="Masukkan nama lengkap Anda"
                                class="w-full px-3 py-2 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 placeholder:text-gray-400 shadow-sm transition-all duration-300 hover:border-gray-300 text-sm @error('name') border-red-400 bg-red-50 @enderror">
                        </div>
                        @error('name') 
                            <div class="flex items-center text-xs text-red-600 mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">Alamat Email</label>
                        <div class="relative">
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                                placeholder="Masukkan alamat email Anda"
                                class="w-full px-3 py-2 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 placeholder:text-gray-400 shadow-sm transition-all duration-300 hover:border-gray-300 text-sm @error('email') border-red-400 bg-red-50 @enderror">
                        </div>
                        @error('email') 
                            <div class="flex items-center text-xs text-red-600 mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- NPM -->
                    <div>
                        <label for="npm" class="block text-xs font-semibold text-gray-700 mb-1">NPM</label>
                        <div class="relative">
                            <input id="npm" type="text" name="npm" value="{{ old('npm') }}" required
                                placeholder="Masukkan NPM Anda"
                                class="w-full px-3 py-2 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 placeholder:text-gray-400 shadow-sm transition-all duration-300 hover:border-gray-300 text-sm @error('npm') border-red-400 bg-red-50 @enderror">
                        </div>
                        @error('npm') 
                            <div class="flex items-center text-xs text-red-600 mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-gray-700 mb-1">Kata Sandi</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required autocomplete="new-password"
                                placeholder="Masukkan kata sandi yang kuat"
                                class="w-full px-3 py-2 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 placeholder:text-gray-400 shadow-sm transition-all duration-300 hover:border-gray-300 text-sm @error('password') border-red-400 bg-red-50 @enderror">
                        </div>
                        @error('password') 
                            <div class="text-xs text-red-600 mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-gray-700 mb-1">Konfirmasi Kata Sandi</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                                placeholder="Ulangi kata sandi Anda"
                                class="w-full px-3 py-2 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 placeholder:text-gray-400 shadow-sm transition-all duration-300 hover:border-gray-300 text-sm @error('password_confirmation') border-red-400 bg-red-50 @enderror">
                        </div>
                        @error('password_confirmation') 
                            <div class="text-xs text-red-600 mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit"
                            class="group relative w-full overflow-hidden bg-[#1D3C96] text-white py-2.5 px-6 rounded-xl hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-blue-500/30 transition-all duration-300 font-bold shadow-lg text-sm">
                            <span class="relative z-10 flex items-center justify-center">Daftar Sekarang</span>
                        </button>
                    </div>

                    <!-- Login Link - DI LUAR FORM agar tetap visible -->
                </form>
                
                <!-- Pindah Login Link di luar form -->
                <div class="text-center pt-3 mt-3 border-t border-gray-200">
                    <div class="text-sm text-gray-500 font-medium mb-2">Sudah punya akun?</div>
                    <div>
                        <a href="{{ route('login') }}" 
                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition-all duration-300 shadow-sm text-sm">
                            Masuk di sini
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>