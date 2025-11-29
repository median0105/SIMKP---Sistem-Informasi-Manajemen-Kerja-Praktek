{{-- resources/views/pengawas/kuisioner/show.blade.php --}}
<x-sidebar-layout>
    {{-- Header section with UNIB blue background --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <a href="{{ route('pengawas.kuisioner.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg inline-flex items-center transition duration-200 backdrop-blur-sm border border-white/30">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                    <!-- Ikon dihapus -->
                </div>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Detail Kuisioner Mahasiswa
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Info Mahasiswa & KP Card --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                    <h3 class="text-base font-semibold text-unib-blue-800">
                        Informasi Mahasiswa & Kerja Praktek
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-gray-500">Mahasiswa</p>
                                <p class="font-medium text-gray-900">{{ $kerjaPraktek->mahasiswa->name }}</p>
                                <p class="text-xs text-gray-600 mt-1">NPM: {{ $kerjaPraktek->mahasiswa->npm }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-gray-500">Tanggal Isi Kuisioner</p>
                                <p class="font-medium text-gray-900">{{ $kerjaPraktek->kuisioner->created_at->locale('id')->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center md:col-span-2">
                            <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-gray-500">Judul KP</p>
                                <p class="font-medium text-gray-900">{{ $kerjaPraktek->judul_kp }}</p>
                            </div>
                        </div>
                        <div class="flex items-center md:col-span-2">
                            <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-gray-500">Tempat Magang</p>
                                <p class="font-medium text-gray-900">
                                    @if($kerjaPraktek->pilihan_tempat == 3)
                                        {{ $kerjaPraktek->tempat_magang_sendiri }}
                                    @else
                                        {{ $kerjaPraktek->tempatMagang->nama_perusahaan }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hasil Kuisioner Card --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                    <h3 class="text-base font-semibold text-unib-blue-800">
                        Hasil Kuisioner
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    {{-- Pertanyaan Dinamis --}}
                    @if($kerjaPraktek->kuisioner->dynamic_answers)
                        @php
                            $questions = \App\Models\KuisionerQuestion::where('is_active', true)->orderBy('order')->get();
                        @endphp
                        @foreach($questions as $question)
                            @if(isset($kerjaPraktek->kuisioner->dynamic_answers[$question->id]))
                                <div class="border-b border-gray-200 pb-4 last:border-b-0">
                                    <p class="text-sm font-medium text-gray-700 mb-2">{{ $question->question_text }}</p>
                                    @if($question->type === 'rating')
                                        <div class="flex items-center">
                                            <span class="text-2xl font-bold text-green-600 mr-3">{{ $kerjaPraktek->kuisioner->dynamic_answers[$question->id] }}/5</span>
                                            <div class="flex">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $kerjaPraktek->kuisioner->dynamic_answers[$question->id] ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                                @endfor
                                            </div>
                                        </div>
                                    @elseif($question->type === 'yes_no')
                                        @if($kerjaPraktek->kuisioner->dynamic_answers[$question->id])
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-300 shadow-sm">
                                                <i class="fas fa-check mr-2 text-xs"></i>Ya
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-300 shadow-sm">
                                                <i class="fas fa-times mr-2 text-xs"></i>Tidak
                                            </span>
                                        @endif
                                    @else
                                        <p class="text-gray-900 bg-gray-50 rounded-lg p-3 border border-gray-200">{{ $kerjaPraktek->kuisioner->dynamic_answers[$question->id] ?: '-' }}</p>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    @endif

                    {{-- Rating Statis --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4">
                        <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-sm text-gray-600 mb-2">Rating Tempat Magang</p>
                            <p class="text-2xl font-bold text-green-600 mb-1">{{ $kerjaPraktek->kuisioner->rating_tempat_magang }}/5</p>
                            <div class="flex justify-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $kerjaPraktek->kuisioner->rating_tempat_magang ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                @endfor
                            </div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-sm text-gray-600 mb-2">Rating Bimbingan Akademik</p>
                            <p class="text-2xl font-bold text-green-600 mb-1">{{ $kerjaPraktek->kuisioner->rating_bimbingan }}/5</p>
                            <div class="flex justify-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $kerjaPraktek->kuisioner->rating_bimbingan ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                @endfor
                            </div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-sm text-gray-600 mb-2">Rating Sistem</p>
                            <p class="text-2xl font-bold text-green-600 mb-1">{{ $kerjaPraktek->kuisioner->rating_sistem }}/5</p>
                            <div class="flex justify-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $kerjaPraktek->kuisioner->rating_sistem ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                @endfor
                            </div>
                        </div>
                    </div>

                    {{-- Rating Keseluruhan --}}
                    <div class="border-t border-gray-200 pt-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Rating Keseluruhan</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            @class([
                                'bg-green-100 text-green-800 border border-green-300' => $kerjaPraktek->kuisioner->rating_keseluruhan === 'Sangat Baik',
                                'bg-blue-100 text-blue-800 border border-blue-300'  => $kerjaPraktek->kuisioner->rating_keseluruhan === 'Baik',
                                'bg-yellow-100 text-yellow-800 border border-yellow-300' => $kerjaPraktek->kuisioner->rating_keseluruhan === 'Cukup Baik',
                                'bg-red-100 text-red-800 border border-red-300'    => $kerjaPraktek->kuisioner->rating_keseluruhan === 'Kurang Baik',
                            ]) shadow-sm">
                            <i class="fas fa-chart-bar mr-2 text-xs"></i>
                            {{ $kerjaPraktek->kuisioner->rating_keseluruhan }}
                        </span>
                    </div>

                    {{-- Rekomendasi --}}
                    <div class="border-t border-gray-200 pt-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Rekomendasi Tempat Magang</p>
                        @if($kerjaPraktek->kuisioner->rekomendasi_tempat)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-300 shadow-sm">
                                <i class="fas fa-check-circle mr-2 text-xs"></i>Direkomendasikan
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-300 shadow-sm">
                                <i class="fas fa-times-circle mr-2 text-xs"></i>Tidak Direkomendasikan
                            </span>
                        @endif
                    </div>

                    {{-- Saran & Kesan --}}
                    @if($kerjaPraktek->kuisioner->saran_perbaikan)
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Saran Perbaikan</p>
                            <p class="text-gray-900 bg-gray-50 rounded-lg p-3 border border-gray-200">{{ $kerjaPraktek->kuisioner->saran_perbaikan }}</p>
                        </div>
                    @endif

                    @if($kerjaPraktek->kuisioner->kesan_pesan)
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Kesan & Pesan</p>
                            <p class="text-gray-900 bg-gray-50 rounded-lg p-3 border border-gray-200">{{ $kerjaPraktek->kuisioner->kesan_pesan }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Feedback Pembimbing Card --}}
            @php
                $feedback = \App\Models\KuisionerPembimbingLapangan::where('kerja_praktek_id', $kerjaPraktek->id)->first();
            @endphp
            @if($feedback)
                <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                        <h3 class="text-base font-semibold text-unib-blue-800">
                            Feedback Pembimbing Lapangan
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-600 mb-2">Rating Kinerja Mahasiswa</p>
                                <p class="text-2xl font-bold text-blue-600 mb-1">{{ $feedback->rating_mahasiswa }}/5</p>
                                <div class="flex justify-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $feedback->rating_mahasiswa ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                    @endfor
                                </div>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-600 mb-2">Rekomendasi Mahasiswa</p>
                                @if($feedback->rekomendasi_mahasiswa)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-300 shadow-sm">
                                        <i class="fas fa-check-circle mr-2 text-xs"></i>Direkomendasikan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-300 shadow-sm">
                                        <i class="fas fa-times-circle mr-2 text-xs"></i>Tidak Direkomendasikan
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($feedback->komentar_kinerja)
                            <div class="border-t border-gray-200 pt-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Komentar Kinerja</p>
                                <p class="text-gray-900 bg-gray-50 rounded-lg p-3 border border-gray-200">{{ $feedback->komentar_kinerja }}</p>
                            </div>
                        @endif

                        @if($feedback->kelebihan_mahasiswa)
                            <div class="border-t border-gray-200 pt-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Kelebihan Mahasiswa</p>
                                <p class="text-gray-900 bg-gray-50 rounded-lg p-3 border border-gray-200">{{ $feedback->kelebihan_mahasiswa }}</p>
                            </div>
                        @endif

                        @if($feedback->kekurangan_mahasiswa)
                            <div class="border-t border-gray-200 pt-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Kekurangan Mahasiswa</p>
                                <p class="text-gray-900 bg-gray-50 rounded-lg p-3 border border-gray-200">{{ $feedback->kekurangan_mahasiswa }}</p>
                            </div>
                        @endif

                        @if($feedback->saran_mahasiswa)
                            <div class="border-t border-gray-200 pt-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Saran untuk Mahasiswa</p>
                                <p class="text-gray-900 bg-gray-50 rounded-lg p-3 border border-gray-200">{{ $feedback->saran_mahasiswa }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Action Button --}}
            <div class="flex justify-center animate-fade-in-up" style="animation-delay: 0.3s;">
                <a href="{{ route('pengawas.kuisioner.feedback', $kerjaPraktek) }}"
                   class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium transition duration-200 flex items-center">
                    <i class="fas fa-edit mr-2"></i>
                    @if($feedback)
                        Edit Feedback
                    @else
                        Berikan Feedback
                    @endif
                </a>
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