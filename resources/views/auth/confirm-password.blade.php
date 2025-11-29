<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-unib-blue-50 to-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            {{-- Header Card --}}
            <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white text-center rounded-t-xl">
                    <div class="flex items-center justify-center mb-2">
                        <div class="bg-white/20 p-3 rounded-full backdrop-blur-sm">
                            <i class="fas fa-shield-alt text-xl text-white"></i>
                        </div>
                    </div>
                    <h2 class="text-xl font-bold">Konfirmasi Password</h2>
                    <p class="text-blue-100 text-sm mt-1">Area aman - konfirmasi identitas Anda</p>
                </div>

                <div class="p-6">
                    <div class="mb-6 text-sm text-gray-600 text-center bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                            <span>Ini adalah area aman aplikasi. Harap konfirmasi password Anda sebelum melanjutkan.</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                        @csrf

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input 
                                    id="password" 
                                    name="password" 
                                    type="password" 
                                    required 
                                    autocomplete="current-password"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 transition duration-200"
                                    placeholder="masukkan password Anda"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-between pt-4">
                            <a href="{{ url()->previous() }}" 
                               class="text-unib-blue-600 hover:text-unib-blue-800 text-sm font-medium flex items-center transition duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali
                            </a>
                            <button type="submit" 
                                    class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                Konfirmasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Security Info --}}
            <div class="text-center">
                <p class="text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Konfirmasi keamanan diperlukan untuk mengakses fitur tertentu.
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