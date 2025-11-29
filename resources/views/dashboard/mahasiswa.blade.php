<!-- Welcome Card -->
<div class="bg-gradient-to-br from-unib-blue-600 to-unib-blue-700 rounded-xl p-6 text-white mb-6 shadow-lg transform transition-all duration-300 hover:shadow-xl animate-fade-in-up">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <h3 class="text-2xl font-bold">Selamat Datang, {{ auth()->user()->name }}</h3>
            <p class="text-blue-100 mt-1 font-medium">NPM: {{ auth()->user()->npm }}</p>
            <p class="text-blue-100 text-sm mt-2">Sistem Informasi Manajemen Kerja Praktek</p>
        </div>
        <div class="hidden md:block ml-6">
            <dotlottie-wc 
                src="https://lottie.host/4eb19f07-adf0-4dd6-a362-d513cd34ab3a/Yw0Th2vfMH.lottie" 
                style="width: 120px; height: 120px" 
                autoplay 
                loop>
            </dotlottie-wc>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ ($data['kerjaPraktek'] && $data['kerjaPraktek']->status === 'selesai') ? (($data['kerjaPraktek']->dosenAkademik) ? '6' : '5') : (($data['kerjaPraktek'] && $data['kerjaPraktek']->dosenAkademik) ? '5' : '4') }} gap-6 mb-6">
    <!-- Status KP -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-xl animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-600">Status Kerja Praktek</p>
                <div class="mt-2">
                    @if($data['kerjaPraktek'])
                        @php
                            $status = $data['kerjaPraktek']->status;
                            $displayStatus = $status;
                            if ($status === 'sedang_kp' && $data['kerjaPraktek']->nilai_akhir && $data['kerjaPraktek']->file_laporan) {
                                $displayStatus = 'selesai';
                            }
                        @endphp
                        <span class="text-lg font-semibold
                            @switch($displayStatus)
                                @case('pengajuan') text-yellow-600 @break
                                @case('disetujui') text-unib-blue-600 @break
                                @case('sedang_kp') text-green-600 @break
                                @case('selesai') text-gray-600 @break
                                @case('ditolak') text-red-600 @break
                            @endswitch
                        ">
                            {{ ucfirst(str_replace('_', ' ', $displayStatus)) }}
                        </span>
                        @if($displayStatus === 'selesai' && $data['kerjaPraktek']->lulus_ujian === false)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-300 ml-2">
                                <i class="fas fa-times-circle mr-1"></i> TIDAK LULUS
                            </span>
                        @endif
                    @else
                        <span class="text-base font-medium text-gray-400">Belum Mengajukan</span>
                    @endif
                </div>
            </div>
            <div class="bg-unib-blue-100 rounded-full p-3 w-12 h-12 flex items-center justify-center ml-4">
                <i class="fas fa-briefcase text-unib-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Bimbingan -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-xl animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-600">Total Bimbingan</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ $data['totalBimbingan'] }}</p>
            </div>
            <div class="bg-green-100 rounded-full p-3 w-12 h-12 flex items-center justify-center ml-4">
                <i class="fas fa-comments text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Kegiatan -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-xl animate-fade-in-up" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-600">Total Kegiatan</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ $data['totalKegiatan'] }}</p>
            </div>
            <div class="bg-teknik-orange-100 rounded-full p-3 w-12 h-12 flex items-center justify-center ml-4">
                <i class="fas fa-tasks text-teknik-orange-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Dosen Pembimbing -->
    @if($data['kerjaPraktek'] && $data['kerjaPraktek']->dosenAkademik)
    <div class="bg-white rounded-xl shadow-lg p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-xl animate-fade-in-up" style="animation-delay: 0.3s;">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-600">Dosen Pembimbing</p>
                <p class="text-lg font-semibold text-gray-900 mt-2">
                    {{ $data['kerjaPraktek']->dosenAkademik?->dosen?->name ?? 'Dosen belum ditugaskan' }}
                </p>
                @if($data['kerjaPraktek']->dosenAkademik?->dosen?->nip)
                    <p class="text-sm text-gray-500 mt-1">
                        NIP: {{ $data['kerjaPraktek']->dosenAkademik->dosen->nip }}
                    </p>
                @endif
            </div>
            <div class="bg-yellow-100 rounded-full p-3 w-12 h-12 flex items-center justify-center ml-4">
                <i class="fas fa-chalkboard-teacher text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>
    @endif

    <!-- Nilai Akhir -->
    @if($data['kerjaPraktek'] && ($data['kerjaPraktek']->status === 'selesai' || ($data['kerjaPraktek']->status === 'sedang_kp' && $data['kerjaPraktek']->nilai_akhir)))
    <div class="bg-white rounded-xl shadow-lg p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-xl animate-fade-in-up" style="animation-delay: 0.4s;">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-600">Nilai Akhir KP</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">
                    @if($data['kerjaPraktek']->nilai_akhir)
                        {{ number_format($data['kerjaPraktek']->nilai_akhir, 2) }}
                    @else
                        -
                    @endif
                </p>
                @if($data['kerjaPraktek']->nilai_akhir)
                    <p class="text-sm font-medium {{ $data['kerjaPraktek']->lulus_ujian ? 'text-green-600' : 'text-red-600' }} mt-1">
                        {{ $data['kerjaPraktek']->lulus_ujian ? 'LULUS' : 'TIDAK LULUS' }}
                    </p>
                @endif
            </div>
            <div class="bg-purple-100 rounded-full p-3 w-12 h-12 flex items-center justify-center ml-4">
                <i class="fas fa-graduation-cap text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>
    @endif

    <!-- Dosen Penguji -->
    @if($data['kerjaPraktek'] && $data['kerjaPraktek']->dosenPenguji && $data['kerjaPraktek']->dosenPenguji->isNotEmpty())
    <div class="bg-white rounded-xl shadow-lg p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-xl animate-fade-in-up" style="animation-delay: 0.5s;">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-600">Dosen Penguji</p>
                <p class="text-lg font-semibold text-gray-900 mt-2">
                    {{ $data['kerjaPraktek']->dosenPenguji->first()->dosen->name }}
                </p>
                @if($data['kerjaPraktek']->dosenPenguji->first()->dosen->nip)
                    <p class="text-sm text-gray-500 mt-1">
                        NIP: {{ $data['kerjaPraktek']->dosenPenguji->first()->dosen->nip }}
                    </p>
                @endif
            </div>
            <div class="bg-blue-100 rounded-full p-3 w-12 h-12 flex items-center justify-center ml-4">
                <i class="fas fa-pen-alt text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Progress Details -->
@if($data['kerjaPraktek'] && in_array($data['kerjaPraktek']->status, ['sedang_kp', 'selesai']))
    <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 transform transition-all duration-300 hover:shadow-xl animate-fade-in-up mb-6">
        <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white rounded-t-xl">
            <h3 class="text-lg font-semibold">Detail Progress Seminar & Ujian</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Upload Laporan -->
                <div class="text-center p-4 bg-unib-blue-50 rounded-xl border border-unib-blue-100">
                    <div class="mx-auto w-12 h-12 rounded-full flex items-center justify-center mb-3
                        {{ $data['kerjaPraktek']->file_laporan ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                        <i class="fas fa-file-alt text-lg"></i>
                    </div>
                    <h4 class="font-medium text-gray-900">Laporan KP</h4>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $data['kerjaPraktek']->file_laporan ? 'Sudah Upload' : 'Belum Upload' }}
                    </p>
                </div>

                <!-- ACC Seminar -->
                <div class="text-center p-4 bg-unib-blue-50 rounded-xl border border-unib-blue-100">
                    <div class="mx-auto w-12 h-12 rounded-full flex items-center justify-center mb-3
                        {{ $data['kerjaPraktek']->acc_seminar ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                        <i class="fas fa-microphone text-lg"></i>
                    </div>
                    <h4 class="font-medium text-gray-900">Seminar KP</h4>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $data['kerjaPraktek']->acc_seminar ? 'Sudah ACC' : 'Belum ACC' }}
                    </p>
                </div>

                <!-- Ujian -->
                <div class="text-center p-4 bg-unib-blue-50 rounded-xl border border-unib-blue-100">
                    <div class="mx-auto w-12 h-12 rounded-full flex items-center justify-center mb-3
                        {{ $data['kerjaPraktek']->lulus_ujian ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                        <i class="fas fa-graduation-cap text-lg"></i>
                    </div>
                    <h4 class="font-medium text-gray-900">Hasil Akhir</h4>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $data['kerjaPraktek']->lulus_ujian ? 'Lulus' : ($data['kerjaPraktek']->nilai_akhir ? 'Tidak Lulus' : 'Belum Ada') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Bimbingan Terbaru -->
    <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 transform transition-all duration-300 hover:shadow-xl animate-fade-in-up">
        <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white rounded-t-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">Bimbingan Terbaru</h3>
                <a href="{{ route('mahasiswa.bimbingan.index') }}" class="text-white/80 hover:text-white text-sm font-medium flex items-center">
                    Lihat Semua <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
        <div class="p-6">
            @forelse($data['bimbinganTerbaru'] as $bimbingan)
                <div class="flex items-start space-x-4 p-4 rounded-lg border border-gray-200 hover:bg-unib-blue-50 transition-colors duration-200 mb-4 last:mb-0">
                    <div class="bg-green-100 rounded-full p-3 flex-shrink-0">
                        <i class="fas fa-calendar-check text-green-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900 truncate">{{ $bimbingan->topik_bimbingan }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $bimbingan->tanggal_bimbingan->locale('id')->translatedFormat('d F Y') }}</p>
                        <div class="mt-3">
                            @if($bimbingan->status_verifikasi)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-300">
                                    <i class="fas fa-check mr-1"></i>
                                    Verified
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-300">
                                    <i class="fas fa-clock mr-1"></i>
                                    Pending
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600">Belum ada bimbingan</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Kegiatan Terbaru -->
    <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 transform transition-all duration-300 hover:shadow-xl animate-fade-in-up">
        <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white rounded-t-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">Kegiatan Terbaru</h3>
                <a href="{{ route('mahasiswa.kegiatan.index') }}" class="text-white/80 hover:text-white text-sm font-medium flex items-center">
                    Lihat Semua <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
        <div class="p-6">
            @forelse($data['kegiatanTerbaru'] as $kegiatan)
                <div class="flex items-start space-x-4 p-4 rounded-lg border border-gray-200 hover:bg-unib-blue-50 transition-colors duration-200 mb-4 last:mb-0">
                    <div class="bg-teknik-orange-100 rounded-full p-3 flex-shrink-0">
                        <i class="fas fa-tasks text-teknik-orange-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900">{{ \Illuminate\Support\Str::limit($kegiatan->deskripsi_kegiatan, 60) }}</p>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $kegiatan->tanggal_kegiatan->locale('id')->translatedFormat('d F Y') }} • {{ $kegiatan->durasi_jam }} jam
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-tasks text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600">Belum ada kegiatan</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Lottie Web Component Script -->
<script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>

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
    
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }
    
    .transition-colors {
        transition-property: background-color, border-color, color, fill, stroke;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Pastikan dotlottie-wc terload dengan baik
        if (typeof customElements !== 'undefined') {
            customElements.whenDefined('dotlottie-wc').then(() => {
                console.log('DotLottie Web Component loaded successfully');
            });
        }
    });
</script>