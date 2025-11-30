<x-sidebar-layout>
    {{-- Header section with UNIB blue background --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <a href="{{ route('mahasiswa.bimbingan.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg inline-flex items-center transition duration-200 backdrop-blur-sm border border-white/30">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                </a>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Detail Bimbingan
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Ringkasan Bimbingan Card --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-unib-blue-800">
                            {{ $bimbingan->topik_bimbingan }}
                        </h3>
                        @if($bimbingan->status_verifikasi)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-300 shadow-sm">
                                <i class="fas fa-check mr-2 text-xs"></i>Terverifikasi
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-300 shadow-sm">
                                <i class="fas fa-clock mr-2 text-xs"></i>Tertunda
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-sm text-gray-500">Tanggal Bimbingan</p>
                                <p class="font-medium text-gray-900">
                                    {{ optional($bimbingan->tanggal_bimbingan)->locale('id')->translatedFormat('d F Y') ?? '-' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-sm text-gray-500">Dibuat</p>
                                <p class="font-medium text-gray-900">
                                    {{ $bimbingan->created_at->locale('id')->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-sticky-note mr-2 text-unib-blue-500"></i>
                                Catatan Mahasiswa
                            </p>
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="whitespace-pre-line text-gray-900">{{ $bimbingan->catatan_mahasiswa }}</div>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-comment-dots mr-2 text-unib-blue-500"></i>
                                Masukan dari Dosen
                            </p>
                            @if($bimbingan->catatan_dosen)
                                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                    <div class="whitespace-pre-line text-gray-900">{{ $bimbingan->catatan_dosen }}</div>
                                </div>
                            @else
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <p class="text-gray-500 text-sm italic">Belum ada Masukan dosen.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info KP Card --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                    <h3 class="text-base font-semibold text-unib-blue-800">
                        Informasi KP
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-gray-500">Judul</p>
                                <p class="font-medium text-gray-900">{{ $bimbingan->kerjaPraktek->judul_kp ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-gray-500">Tempat</p>
                                <p class="font-medium text-gray-900">
                                    @php $kp = $bimbingan->kerjaPraktek; @endphp
                                    @if($kp)
                                        @if($kp->pilihan_tempat == 3)
                                            {{ $kp->tempat_magang_sendiri }}
                                        @else
                                            {{ $kp->tempatMagang->nama_perusahaan ?? '-' }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center md:col-span-2">
                            <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-gray-500">Status KP</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    @if($kp->status === 'disetujui') bg-green-100 text-green-800 border border-green-300
                                    @elseif($kp->status === 'pengajuan') bg-yellow-100 text-yellow-800 border border-yellow-300
                                    @elseif($kp->status === 'ditolak') bg-red-100 text-red-800 border border-red-300
                                    @else bg-gray-100 text-gray-800 border border-gray-300
                                    @endif shadow-sm">
                                    <i class="fas 
                                        @if($kp->status === 'disetujui') fa-check
                                        @elseif($kp->status === 'pengajuan') fa-clock
                                        @elseif($kp->status === 'ditolak') fa-times
                                        @else fa-question
                                        @endif mr-2 text-xs"></i>
                                    {{ $kp->status ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
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
</x-sidebar-layout>