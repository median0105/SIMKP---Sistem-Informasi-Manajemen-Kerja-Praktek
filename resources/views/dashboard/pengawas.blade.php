{{-- Welcome Card --}}
<div class="bg-gradient-to-br from-unib-blue-600 to-unib-blue-700 rounded-2xl p-6 text-white mb-8 shadow-lg animate-fade-in-up">
    <div class="flex items-center justify-between">
        <div class="mb-4 md:mb-0 text-center md:text-left">
            <h3 class="text-3xl font-bold">Selamat Datang, {{ auth()->user()->name }}</h3>
            <p class="text-blue-200 mt-2 font-bold text-lg">Pembimbing Lapangan</p>
            <p class="text-blue-200 mt-1">Monitoring Kerja Praktek</p>
        </div>
        <div class="hidden md:block">
            <div class="bg-white/20 p-3 rounded-full backdrop-blur-sm">
                <!-- Gunakan URL Lottie yang lebih reliable -->
                <dotlottie-player
                    src="https://assets1.lottiefiles.com/packages/lf20_vybwn7df.json"
                    background="transparent"
                    speed="1"
                    style="width: 65px; height: 65px;"
                    loop
                    autoplay>
                </dotlottie-player>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
{{-- <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8"> --}}
    <!-- Mahasiswa KP -->
    {{-- <div class="bg-white rounded-xl shadow-lg p-6 transform transition-all duration-300 hover:shadow-xl animate-fade-in-up border border-unib-blue-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 flex items-center">
                    <i class="fas fa-users text-green-500 mr-2"></i>
                    Mahasiswa KP
                </p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ $data['mahasiswaKP'] }}</p>
            </div>
            <div class="bg-green-100 rounded-full p-3 w-12 h-12 flex items-center justify-center border border-green-200">
                <i class="fas fa-users text-green-600 text-xl"></i>
            </div>
        </div>
    </div> --}}

    <!-- Laporan Pending -->
    {{-- <div class="bg-white rounded-xl shadow-lg p-6 transform transition-all duration-300 hover:shadow-xl animate-fade-in-up border border-unib-blue-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 flex items-center">
                    <i class="fas fa-file-alt text-yellow-500 mr-2"></i>
                    Laporan Pending
                </p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ $data['laporanPending'] }}</p>
            </div>
            <div class="bg-yellow-100 rounded-full p-3 w-12 h-12 flex items-center justify-center border border-yellow-200">
                <i class="fas fa-file-alt text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div> --}}

    <!-- Akses Cepat -->
    {{-- <div class="bg-white rounded-xl shadow-lg p-6 transform transition-all duration-300 hover:shadow-xl animate-fade-in-up border border-unib-blue-100">
        <div class="text-center">
            <p class="text-sm font-medium text-gray-600 mb-4 flex items-center justify-center">
                <i class="fas fa-bolt text-unib-blue-500 mr-2"></i>
                Akses Cepat
            </p>
            <a href="{{ route('pengawas.mahasiswa.index') }}" 
               class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-xl font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition duration-200 flex items-center justify-center">
                <i class="fas fa-eye mr-3"></i>
                Lihat Mahasiswa KP
            </a>
        </div>
    </div> --}}
{{-- </div> --}}

<!-- Recent Activities -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl animate-fade-in-up border border-unib-blue-100">
    <div class="px-6 py-5 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white">
        <h3 class="text-lg font-semibold flex items-center">
            <i class="fas fa-exclamation-circle mr-3"></i>
            Mahasiswa yang Membutuhkan Perhatian
        </h3>
    </div>
    <div class="p-6">
        @if($data['recentNotifications']->count() > 0)
            <div class="space-y-6">
                @foreach($data['recentNotifications'] as $notification)
                    <div class="flex items-start space-x-4 p-6 bg-gradient-to-r from-unib-blue-50 to-blue-50 border-2 border-unib-blue-200 rounded-xl hover:border-unib-blue-300 transition duration-300 transform hover:-translate-y-1">
                        <div class="bg-unib-blue-100 rounded-xl p-3 mt-1 border border-unib-blue-200">
                            <i class="fas fa-info-circle text-unib-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 text-lg">{{ $notification->title }}</h4>
                            <p class="text-gray-700 mt-2 leading-relaxed">{{ $notification->message }}</p>
                            @if($notification->kerjaPraktek)
                                <div class="mt-4 space-y-2">
                                    <p class="text-sm text-gray-600 flex items-center">
                                        <i class="fas fa-user-graduate text-unib-blue-500 mr-2"></i>
                                        <span class="font-medium">Mahasiswa:</span> 
                                        <span class="ml-1">{{ $notification->kerjaPraktek->mahasiswa->name }} ({{ $notification->kerjaPraktek->mahasiswa->npm }})</span>
                                    </p>
                                    <p class="text-sm text-gray-600 flex items-center">
                                        <i class="fas fa-graduation-cap text-unib-blue-500 mr-2"></i>
                                        <span class="font-medium">Judul KP:</span> 
                                        <span class="ml-1">{{ $notification->kerjaPraktek->judul_kp }}</span>
                                    </p>
                                </div>
                            @endif
                            <p class="text-xs text-gray-500 mt-4 flex items-center">
                                <i class="far fa-clock text-gray-400 mr-2"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                        @if($notification->action_url)
                            <a href="{{ $notification->action_url }}" 
                               class="text-unib-blue-600 hover:text-unib-blue-800 text-sm font-medium flex items-center px-4 py-2 rounded-lg hover:bg-unib-blue-100 transition duration-200 transform hover:scale-105 self-start border border-unib-blue-200">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                Lihat Detail
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="flex justify-center mb-6">
                    <dotlottie-player 
                        src="https://lottie.host/f4be40d9-971a-446d-9d51-ed1128f637ef/8YGejDufWD.lottie" 
                        background="transparent" 
                        speed="1" 
                        style="width: 200px; height: 200px; margin: 0 auto;" 
                        loop 
                        autoplay>
                    </dotlottie-player>
                </div>
                <h4 class="text-xl font-bold text-gray-900 mb-3">Semua Kondisi Baik</h4>
                <p class="text-gray-600 text-lg mb-2">Tidak ada mahasiswa yang memerlukan tindakan segera</p>
                <p class="text-sm text-gray-500">Semua progress berjalan sesuai rencana</p>
            </div>
        @endif
    </div>
</div>

{{-- Lottie Player Script --}}
<script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>

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