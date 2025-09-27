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
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gradient-to-r from-blue-800 to-blue-900">

<div class="min-h-screen grid grid-cols-1 md:grid-cols-2">
    <!-- Left Side - Decorative Panel -->
    <div class="hidden lg:flex flex-col justify-center items-center p-16 bg-gradient-to-br from-blue-700 to-blue-600 text-white relative overflow-hidden">
        <!-- Background decorations -->
        <div class="absolute inset-0">
            <div class="absolute top-20 left-20 w-32 h-32 bg-blue-400 rounded-full opacity-20 blur-xl"></div>
            <div class="absolute top-60 right-32 w-24 h-24 bg-purple-400 rounded-full opacity-30 blur-lg"></div>
            <div class="absolute bottom-40 left-16 w-40 h-40 bg-cyan-400 rounded-full opacity-25 blur-2xl"></div>
            <div class="absolute bottom-20 right-20 w-16 h-16 bg-pink-400 rounded-full opacity-40 blur-lg"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col justify-center items-center text-center">
            <img src="{{ asset('storage/logo-unib.png') }}" alt="UNIB Logo" class="w-48 h-auto ">
            <h1 class="text-3xl sm:text-4xl font-bold">Buat Akun Baru</h1>
            <p class="mt-3 text-lg font-medium">Sistem Informasi Manajemen Praktek</p>
            <p class="text-sm text-blue-200 mt-1">Daftar untuk mulai menggunakan layanan</p>
        </div>

        <!-- Website URL -->
        <div class="absolute bottom-8 text-center w-full">
            <span class="text-blue-200 text-sm">www.unib.ac.id</span>
        </div>
    </div>

    <!-- Right Side - Register Form -->
    <div class="w-full flex items-center justify-center px-8 py-12 bg-gray-50">
        <div class="max-w-sm w-full">
            <!-- Header -->
            <div class="text-left mb-6">
                <h2 class="text-4xl font-bold text-gray-900">Daftar</h2>
                <p class="mt-2 text-gray-500">Buat akun baru Anda</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                        placeholder="Masukkan nama lengkap"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder:text-gray-400 shadow-sm @error('name') border-red-400 @enderror">
                    @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                        placeholder="Masukkan email Anda"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder:text-gray-400 shadow-sm @error('email') border-red-400 @enderror">
                    @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="npm" class="block text-sm font-medium text-gray-700 not-italic">NPM</label>
                    <input id="npm" type="text" name="npm" value="{{ old('npm') }}" required
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Masukkan NPM Anda">
                    <x-input-error :messages="$errors->get('npm')" class="mt-2 text-sm text-red-600" />
                </div>
                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi</label>
                    <input type="password" id="password" name="password" required autocomplete="new-password"
                        placeholder="Masukkan kata sandi"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder:text-gray-400 shadow-sm @error('password') border-red-400 @enderror">
                    @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Kata Sandi</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                        placeholder="Ulangi kata sandi"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder:text-gray-400 shadow-sm @error('password_confirmation') border-red-400 @enderror">
                    @error('password_confirmation') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-between text-sm">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500 transition-colors">
                        Sudah punya akun? Masuk
                    </a>
                    <button type="submit"
                        class="ml-4 bg-gradient-to-r from-blue-600 to-blue-800 text-white py-3 px-5 rounded-xl hover:from-blue-700 hover:to-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 font-medium shadow-md">
                        Daftar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
