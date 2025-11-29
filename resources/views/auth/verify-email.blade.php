<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-unib-blue-50 to-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            {{-- Header Card --}}
            <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white text-center rounded-t-xl">
                    <div class="flex items-center justify-center mb-2">
                        <div class="bg-white/20 p-3 rounded-full backdrop-blur-sm">
                            <i class="fas fa-envelope-open-text text-xl text-white"></i>
                        </div>
                    </div>
                    <h2 class="text-xl font-bold">Verifikasi Email</h2>
                    <p class="text-blue-100 text-sm mt-1">Langkah terakhir untuk mengaktifkan akun</p>
                </div>

                <div class="p-6">
                    {{-- Informasi Utama --}}
                    <div class="mb-6 text-sm text-gray-600 text-center bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                            <span>
                                Terima kasih telah mendaftar! Sebelum memulai, verifikasi alamat email Anda dengan mengklik link yang kami kirimkan. Jika tidak menerima email, kami akan dengan senang hati mengirimkan yang baru.
                            </span>
                        </div>
                    </div>

                    {{-- Status Success --}}
                    @if (session('status') == 'verification-link-sent')
                        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <span class="text-sm text-green-700 font-medium">
                                    Link verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.
                                </span>
                            </div>
                        </div>
                    @endif

                    <div class="mt-6 space-y-4">
                        {{-- Form Resend Verification --}}
                        <form method="POST" action="{{ route('verification.send') }}" class="text-center">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Kirim Ulang Email Verifikasi
                            </button>
                        </form>

                        {{-- Form Logout --}}
                        <form method="POST" action="{{ route('logout') }}" class="text-center">
                            @csrf
                            <button type="submit" 
                                    class="text-unib-blue-600 hover:text-unib-blue-800 text-sm font-medium flex items-center justify-center mx-auto transition duration-200">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Keluar
                            </button>
                        </form>
                    </div>

                    {{-- Additional Info --}}
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="text-center text-xs text-gray-500">
                            <p class="mb-2">
                                <i class="fas fa-clock mr-1"></i>
                                Link verifikasi berlaku selama 60 menit
                            </p>
                            <p>
                                <i class="fas fa-envelope mr-1"></i>
                                Periksa folder spam jika email tidak ditemukan
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Support Info --}}
            <div class="text-center">
                <p class="text-xs text-gray-500">
                    Butuh bantuan? 
                    <a href="mailto:support@unib.ac.id" class="text-unib-blue-600 hover:text-unib-blue-800 font-medium">
                        Hubungi Support
                    </a>
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