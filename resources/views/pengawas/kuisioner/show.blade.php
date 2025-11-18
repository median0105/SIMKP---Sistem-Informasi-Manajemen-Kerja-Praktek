{{-- resources/views/pengawas/kuisioner/show.blade.php --}}
<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Kuisioner Mahasiswa
            </h2>
            <a href="{{ route('pengawas.kuisioner.index') }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Info Mahasiswa & KP --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Mahasiswa & Kerja Praktek</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600">Mahasiswa</p>
                        <p class="text-gray-900 font-medium">{{ $kerjaPraktek->mahasiswa->name }}</p>
                        <p class="text-sm text-gray-600">NPM: {{ $kerjaPraktek->mahasiswa->npm }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Isi Kuisioner</p>
                        <p class="text-gray-900">{{ $kerjaPraktek->kuisioner->created_at->locale('id')->translatedFormat('d F Y') }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600">Judul KP</p>
                        <p class="text-gray-900">{{ $kerjaPraktek->judul_kp }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600">Tempat Magang</p>
                        <p class="text-gray-900">
                            @if($kerjaPraktek->pilihan_tempat == 3)
                                {{ $kerjaPraktek->tempat_magang_sendiri }}
                            @else
                                {{ $kerjaPraktek->tempatMagang->nama_perusahaan }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Hasil Kuisioner --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Hasil Kuisioner</h3>
                </div>
                <div class="p-6 space-y-4">
                    {{-- Pertanyaan Dinamis --}}
                    @if($kerjaPraktek->kuisioner->dynamic_answers)
                        @php
                            $questions = \App\Models\KuisionerQuestion::where('is_active', true)->orderBy('order')->get();
                        @endphp
                        @foreach($questions as $question)
                            @if(isset($kerjaPraktek->kuisioner->dynamic_answers[$question->id]))
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">{{ $question->question_text }}</p>
                                    @if($question->type === 'rating')
                                        <p class="text-2xl font-bold text-green-600">{{ $kerjaPraktek->kuisioner->dynamic_answers[$question->id] }}/5</p>
                                    @elseif($question->type === 'yes_no')
                                        @if($kerjaPraktek->kuisioner->dynamic_answers[$question->id])
                                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Ya</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">Tidak</span>
                                        @endif
                                    @else
                                        <p class="text-gray-900">{{ $kerjaPraktek->kuisioner->dynamic_answers[$question->id] ?: '-' }}</p>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    @endif

                    {{-- Rating Statis --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Rating Tempat Magang</p>
                            <p class="text-2xl font-bold text-green-600">{{ $kerjaPraktek->kuisioner->rating_tempat_magang }}/5</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Rating Bimbingan Akademik</p>
                            <p class="text-2xl font-bold text-green-600">{{ $kerjaPraktek->kuisioner->rating_bimbingan }}/5</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Rating Sistem</p>
                            <p class="text-2xl font-bold text-green-600">{{ $kerjaPraktek->kuisioner->rating_sistem }}/5</p>
                        </div>
                    </div>

                    {{-- Rating Keseluruhan --}}
                    <div>
                        <p class="text-sm text-gray-600">Rating Keseluruhan</p>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @class([
                                'bg-green-100 text-green-800' => $kerjaPraktek->kuisioner->rating_keseluruhan === 'Sangat Baik',
                                'bg-blue-100 text-blue-800'  => $kerjaPraktek->kuisioner->rating_keseluruhan === 'Baik',
                                'bg-yellow-100 text-yellow-800' => $kerjaPraktek->kuisioner->rating_keseluruhan === 'Cukup Baik',
                                'bg-red-100 text-red-800'    => $kerjaPraktek->kuisioner->rating_keseluruhan === 'Kurang Baik',
                            ])">
                            {{ $kerjaPraktek->kuisioner->rating_keseluruhan }}
                        </span>
                    </div>

                    {{-- Rekomendasi --}}
                    <div>
                        <p class="text-sm text-gray-600">Rekomendasi Tempat Magang</p>
                        @if($kerjaPraktek->kuisioner->rekomendasi_tempat)
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Direkomendasikan</span>
                        @else
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">Tidak Direkomendasikan</span>
                        @endif
                    </div>

                    {{-- Saran & Kesan --}}
                    @if($kerjaPraktek->kuisioner->saran_perbaikan)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Saran Perbaikan</p>
                            <p class="text-gray-900">{{ $kerjaPraktek->kuisioner->saran_perbaikan }}</p>
                        </div>
                    @endif

                    @if($kerjaPraktek->kuisioner->kesan_pesan)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Kesan & Pesan</p>
                            <p class="text-gray-900">{{ $kerjaPraktek->kuisioner->kesan_pesan }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Feedback Pembimbing --}}
            @php
                $feedback = \App\Models\KuisionerPembimbingLapangan::where('kerja_praktek_id', $kerjaPraktek->id)->first();
            @endphp
            @if($feedback)
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Feedback Pembimbing Lapangan</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Rating Kinerja Mahasiswa</p>
                                <p class="text-2xl font-bold text-blue-600">{{ $feedback->rating_mahasiswa }}/5</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Rekomendasi Mahasiswa</p>
                                @if($feedback->rekomendasi_mahasiswa)
                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Direkomendasikan</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">Tidak Direkomendasikan</span>
                                @endif
                            </div>
                        </div>

                        @if($feedback->komentar_kinerja)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Komentar Kinerja</p>
                                <p class="text-gray-900">{{ $feedback->komentar_kinerja }}</p>
                            </div>
                        @endif

                        @if($feedback->kelebihan_mahasiswa)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Kelebihan Mahasiswa</p>
                                <p class="text-gray-900">{{ $feedback->kelebihan_mahasiswa }}</p>
                            </div>
                        @endif

                        @if($feedback->kekurangan_mahasiswa)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Kekurangan Mahasiswa</p>
                                <p class="text-gray-900">{{ $feedback->kekurangan_mahasiswa }}</p>
                            </div>
                        @endif

                        @if($feedback->saran_mahasiswa)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Saran untuk Mahasiswa</p>
                                <p class="text-gray-900">{{ $feedback->saran_mahasiswa }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Action Buttons --}}
            <div class="flex justify-center space-x-4">
                <a href="{{ route('pengawas.kuisioner.feedback', $kerjaPraktek) }}"
                   class="bg-purple-500 hover:bg-purple-700 text-white px-6 py-3 rounded-md">
                    @if($feedback)
                        Edit Feedback
                    @else
                        Berikan Feedback
                    @endif
                </a>
            </div>
        </div>
    </div>
</x-sidebar-layout>
