{{-- resources/views/superadmin/kuisioner/show.blade.php --}}
<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <a href="{{ route('superadmin.kuisioner.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-3 py-1.5 rounded-lg inline-flex items-center transition duration-200 transform hover:scale-105 backdrop-blur-sm border border-white/30 text-sm">
                    <i class="fas fa-arrow-left mr-1.5"></i>Kembali
                </a>
                <div class="flex items-center space-x-2">
                    <div class="bg-white/20 p-1.5 rounded-full backdrop-blur-sm">
                   
                    </div>
                    <div>
                        <h2 class="font-bold text-lg leading-tight">
                            Detail Kuisioner
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- ========================= FLASH ========================= --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-r-lg shadow-md flex items-center animate-fade-in-up">
                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-lg shadow-md flex items-center animate-fade-in-up">
                    <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                    {{ session('error') }}
                </div>
            @endif

            {{-- ========================= INFORMASI MAHASISWA & KP ========================= --}}
            <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-4 py-3 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white rounded-t-lg -mx-6 -mt-6 mb-6">
                    <h3 class="text-lg font-bold">Informasi Mahasiswa & Kerja Praktek</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-unib-blue-800 mb-2">Mahasiswa</label>
                            <p class="text-base text-gray-900 bg-unib-blue-50 px-4 py-3 rounded-lg border border-unib-blue-200">
                                {{ $kuisioner->kerjaPraktek?->mahasiswa?->name ?? '-' }}
                            </p>
                            @if($kuisioner->kerjaPraktek?->mahasiswa?->npm)
                            <p class="text-sm text-gray-600 mt-1">NPM: {{ $kuisioner->kerjaPraktek->mahasiswa->npm }}</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-unib-blue-800 mb-2">Tanggal Pengisian</label>
                            <p class="text-base text-gray-900 bg-unib-blue-50 px-4 py-3 rounded-lg border border-unib-blue-200">
                                {{ $kuisioner->created_at->locale('id')->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-unib-blue-800 mb-2">Judul Kerja Praktek</label>
                            <p class="text-base text-gray-900 bg-unib-blue-50 px-4 py-3 rounded-lg border border-unib-blue-200">
                                {{ $kuisioner->kerjaPraktek?->judul_kp ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-unib-blue-800 mb-2">Tempat Magang</label>
                            <p class="text-base text-gray-900 bg-unib-blue-50 px-4 py-3 rounded-lg border border-unib-blue-200">
                                @php
                                    $kp = $kuisioner->kerjaPraktek;
                                    $tm = $kp?->tempatMagang;
                                @endphp
                                {{ $kp?->pilihan_tempat == 3 ? ($kp->tempat_magang_sendiri ?? '-') : ($tm?->nama_perusahaan ?? '-') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========================= HASIL KUISIONER ========================= --}}
            <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-4 py-3 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white rounded-t-lg -mx-6 -mt-6 mb-6">
                    <h3 class="text-lg font-bold">Hasil Kuisioner</h3>
                </div>
                
                <div class="space-y-6">
                    {{-- Pertanyaan Dinamis --}}
                    @if($kuisioner->dynamic_answers)
                        @php
                            $questions = \App\Models\KuisionerQuestion::where('is_active', true)->orderBy('order')->get();
                        @endphp
                        @foreach($questions as $question)
                            @if(isset($kuisioner->dynamic_answers[$question->id]))
                                <div class="p-4 bg-unib-blue-50 rounded-lg border border-unib-blue-200">
                                    <label class="block text-sm font-semibold text-unib-blue-800 mb-2">{{ $question->question_text }}</label>
                                    @if($question->type === 'rating')
                                        <div class="flex items-center gap-3">
                                            <span class="text-2xl font-bold text-unib-blue-600">{{ $kuisioner->dynamic_answers[$question->id] }}/5</span>
                                            <div class="flex">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $kuisioner->dynamic_answers[$question->id] ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                    @elseif($question->type === 'yes_no')
                                        @if($kuisioner->dynamic_answers[$question->id])
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-300 shadow-sm">
                                                <i class="fas fa-check mr-1"></i>Ya
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-300 shadow-sm">
                                                <i class="fas fa-times mr-1"></i>Tidak
                                            </span>
                                        @endif
                                    @else
                                        <p class="text-base text-gray-900 mt-1">{{ $kuisioner->dynamic_answers[$question->id] ?: '-' }}</p>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    @endif

                    {{-- Rating Utama --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 bg-unib-blue-50 rounded-lg border border-unib-blue-200 text-center">
                            <label class="block text-sm font-semibold text-unib-blue-800 mb-2">Rating Tempat Magang</label>
                            <div class="flex items-center justify-center gap-2">
                                <span class="text-2xl font-bold text-unib-blue-600">{{ $kuisioner->rating_tempat_magang }}</span>
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $kuisioner->rating_tempat_magang ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="p-4 bg-unib-blue-50 rounded-lg border border-unib-blue-200 text-center">
                            <label class="block text-sm font-semibold text-unib-blue-800 mb-2">Rating Bimbingan</label>
                            <div class="flex items-center justify-center gap-2">
                                <span class="text-2xl font-bold text-unib-blue-600">{{ $kuisioner->rating_bimbingan }}</span>
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $kuisioner->rating_bimbingan ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="p-4 bg-unib-blue-50 rounded-lg border border-unib-blue-200 text-center">
                            <label class="block text-sm font-semibold text-unib-blue-800 mb-2">Rating Sistem</label>
                            <div class="flex items-center justify-center gap-2">
                                <span class="text-2xl font-bold text-unib-blue-600">{{ $kuisioner->rating_sistem }}</span>
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $kuisioner->rating_sistem ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Rekomendasi --}}
                    <div class="p-4 bg-unib-blue-50 rounded-lg border border-unib-blue-200">
                        <label class="block text-sm font-semibold text-unib-blue-800 mb-2">Rekomendasi Tempat</label>
                        @if($kuisioner->rekomendasi_tempat)
                            <span class="inline-flex items-center px-3 py-2 rounded-full text-base font-medium bg-green-100 text-green-800 border border-green-300 shadow-sm">
                                <i class="fas fa-thumbs-up mr-2"></i>Direkomendasikan
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-2 rounded-full text-base font-medium bg-red-100 text-red-800 border border-red-300 shadow-sm">
                                <i class="fas fa-thumbs-down mr-2"></i>Tidak Direkomendasikan
                            </span>
                        @endif
                    </div>

                    {{-- Saran Perbaikan --}}
                    @if($kuisioner->saran_perbaikan)
                        <div class="p-4 bg-unib-blue-50 rounded-lg border border-unib-blue-200">
                            <label class="block text-sm font-semibold text-unib-blue-800 mb-2">Saran Perbaikan</label>
                            <p class="text-base text-gray-900 mt-1">{{ $kuisioner->saran_perbaikan }}</p>
                        </div>
                    @endif

                    {{-- Kesan & Pesan --}}
                    @if($kuisioner->kesan_pesan)
                        <div class="p-4 bg-unib-blue-50 rounded-lg border border-unib-blue-200">
                            <label class="block text-sm font-semibold text-unib-blue-800 mb-2">Kesan & Pesan</label>
                            <p class="text-base text-gray-900 mt-1">{{ $kuisioner->kesan_pesan }}</p>
                        </div>
                    @endif
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