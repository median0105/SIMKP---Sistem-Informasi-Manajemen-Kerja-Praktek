<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-unib-blue-50 to-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            {{-- Header Card --}}
            <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white text-center rounded-t-xl">
                    <div class="flex items-center justify-center mb-2">
                        <div class="bg-white/20 p-3 rounded-full backdrop-blur-sm">
                            <i class="fas fa-key text-xl text-white"></i>
                        </div>
                    </div>
                    <h2 class="text-xl font-bold">Reset Password</h2>
                    <p class="text-blue-100 text-sm mt-1">Masukkan email untuk reset password</p>
                </div>

                <div class="p-6">
                    {{-- Session Status --}}
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <div class="mb-6 text-sm text-gray-600 text-center">
                        Lupa password? Tidak masalah. Berikan alamat email Anda dan kami akan mengirimkan link reset password untuk memilih password baru.
                    </div>

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Alamat Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input 
                                    id="email" 
                                    name="email" 
                                    type="email" 
                                    required 
                                    autofocus
                                    value="{{ old('email') }}"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 transition duration-200"
                                    placeholder="masukkan email Anda"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('login') }}" 
                               class="text-unib-blue-600 hover:text-unib-blue-800 text-sm font-medium flex items-center transition duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali ke Login
                            </a>
                            <button type="submit" 
                                    class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Kirim Link Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Additional Info --}}
            <div class="text-center">
                <p class="text-xs text-gray-500">
                    Link reset password akan dikirim ke email Anda dan berlaku dalam waktu 60 menit.
                </p>
            </div>
        </div>
    </div>

    {{-- CSS untuk animasi kustom --}}
    <style>
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
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>

    <script>
        // Add animation on load
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.bg-white');
            card.classList.add('animate-fade-in-up');
        });
    </script>
</x-guest-layout>