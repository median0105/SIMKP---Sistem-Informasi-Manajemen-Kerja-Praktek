<x-sidebar-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
        <div class="flex items-center space-x-3">
            <div>
                <h2 class="font-bold text-xl leading-tight">
                    {{ __('Bimbingan Kerja Praktek') }}
                </h2>
            </div>
        </div>
    </div>
</x-slot>

    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($kerjaPraktek && $kerjaPraktek->status === 'sedang_kp')
                <!-- Tombol Tambah -->
                <div class="mb-8 flex justify-end animate-fade-in-up">
                    <a href="{{ route('mahasiswa.bimbingan.create') }}"
                       class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition duration-200 flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Bimbingan
                    </a>
                </div>
            @endif

            <!-- Card Utama -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-unib-blue-100 transform transition-all duration-300 hover:shadow-2xl animate-fade-in-up">
                <div class="p-8">
                    <div class="flex items-center mb-8">
                        <div class="bg-gradient-to-r from-unib-blue-500 to-unib-blue-600 p-3 rounded-xl mr-4">
                            <i class="fas fa-comments text-white text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Riwayat Bimbingan</h3>
                            <p class="text-gray-600 mt-1">Dokumentasi semua sesi bimbingan dengan dosen pembimbing</p>
                        </div>
                    </div>

                    @if($bimbingan->count() > 0)

                        <div class="space-y-6">

                            @foreach($bimbingan as $item)
                                <div class="rounded-2xl p-6 border-2 border-unib-blue-200 shadow-lg transition-all duration-300 hover:shadow-xl hover:border-unib-blue-300 transform hover:-translate-y-1"
                                     style="background: linear-gradient(135deg, rgba(29,60,150,0.03) 0%, rgba(29,60,150,0.08) 100%);">

                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">

                                            <!-- Judul & Status -->
                                            <div class="flex items-center space-x-3 mb-4">
                                                <h4 class="text-lg font-semibold text-gray-900">
                                                    {{ $item->topik_bimbingan }}
                                                </h4>

                                                @if($item->status_verifikasi)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800 border border-green-300 shadow-sm">
                                                        Terverifikasi
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-yellow-100 text-yellow-800 border border-yellow-300 shadow-sm">
                                                        Tertunda
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Tanggal -->
                                            <div class="flex items-center text-sm text-gray-600 mb-4">
                                                <div class="bg-unib-blue-100 p-3 mr-2 rounded-xl w-8 h-8 flex items-center justify-center "> 
                                                    <i class="fas fa-calendar text-unib-blue-600 "></i>
                                                </div>
                                                {{ $item->tanggal_bimbingan->locale('id')->translatedFormat('d F Y') }}
                                            </div>

                                            <!-- Catatan Mahasiswa -->
                                            <div class="mb-4">
                                                <p class="text-sm font-medium text-gray-600 mb-2">
                                                    Catatan Mahasiswa:
                                                </p>
                                                <p class="text-gray-900 leading-relaxed bg-gray-50 rounded-lg p-4 border border-gray-200">
                                                    {{ Str::limit($item->catatan_mahasiswa, 150) }}
                                                </p>
                                            </div>

                                            <!-- Feedback Dosen -->
                                            @if($item->catatan_dosen)
                                                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-4 shadow-sm transform transition-all duration-300 hover:shadow-md">
                                                    <p class="text-sm font-medium text-gray-600 mb-2">
                                                        Balasan dari Dosen:
                                                    </p>
                                                    <p class="text-gray-900 leading-relaxed">
                                                        {{ $item->catatan_dosen }}
                                                    </p>
                                                </div>
                                            @endif

                                        </div>

                                        <div class="ml-6">
                                            <a href="{{ route('mahasiswa.bimbingan.show', $item) }}"
                                               class="text-unib-blue-600 hover:text-unib-blue-800 font-semibold transition duration-200 flex items-center px-4 py-2 rounded-lg hover:bg-unib-blue-50 transform hover:scale-105"
                                               title="Lihat Detail Bimbingan">
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                        <div class="mt-10 pt-8 border-t border-gray-200">
                            {{ $bimbingan->links() }}
                        </div>

                    @else

                        <div class="text-center py-12">

                            <!-- Animasi di tengah -->
                            <div class="flex justify-center mb-6">
                                <dotlottie-player 
                                    src="https://lottie.host/130a991a-b34b-4c70-8276-70422531868d/n274VikQ2K.lottie"
                                    background="transparent" 
                                    speed="1" 
                                    style="width: 280px; height: 280px;" 
                                    loop 
                                    autoplay>
                                </dotlottie-player>
                            </div>

                            <h4 class="text-2xl font-bold text-gray-900 mt-4 mb-3">Belum Ada Bimbingan</h4>

                            <p class="text-gray-600 max-w-lg mx-auto mb-8 text-lg">
                                Dokumentasikan proses bimbingan Anda agar progress Kerja Praktek dapat dipantau dosen pembimbing.
                            </p>

                            @if($kerjaPraktek && $kerjaPraktek->status === 'sedang_kp')
                                <a href="{{ route('mahasiswa.bimbingan.create') }}"
                                   class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-8 py-4 rounded-xl font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition duration-200 flex items-center justify-center mx-auto w-fit">
                                    <i class="fas fa-plus mr-3"></i>
                                    Tambah Bimbingan Pertama
                                </a>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 max-w-md mx-auto">
                                    <div class="flex items-center justify-center mb-3">
                                        <i class="fas fa-info-circle text-yellow-600 text-2xl mr-3"></i>
                                        <p class="text-yellow-800 font-semibold">Informasi</p>
                                    </div>
                                    <p class="text-yellow-700 text-sm text-center">
                                        Fitur bimbingan akan tersedia ketika status Kerja Praktek Anda "Sedang KP"
                                    </p>
                                </div>
                            @endif

                        </div>

                    @endif

                </div>
            </div>

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
        
        /* Hover effects for cards */
        .card-hover {
            transition: all 0.2s ease-in-out;
        }
        
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to cards
            const cards = document.querySelectorAll('.bg-white');
            cards.forEach(card => {
                card.classList.add('transform', 'transition-all', 'duration-300', 'hover:shadow-lg');
            });
        });
    </script>
</x-sidebar-layout>