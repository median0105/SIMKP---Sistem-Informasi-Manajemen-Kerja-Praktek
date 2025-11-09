{{-- resources/views/superadmin/kuisioner/show.blade.php --}}
<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Kuisioner
            </h2>
            <a href="{{ route('superadmin.kuisioner.index') }}" class="text-unib-blue-600 hover:text-unib-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Mahasiswa & KP</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600">Mahasiswa</p>
                        <p class="text-gray-900 font-medium">
                            {{ $kuisioner->kerjaPraktek?->mahasiswa?->name ?? '-' }}
                        </p>
                        @if($kuisioner->kerjaPraktek?->mahasiswa?->npm)
                        <p class="text-sm text-gray-600">NPM: {{ $kuisioner->kerjaPraktek->mahasiswa->npm }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Isi</p>
                        <p class="text-gray-900">
                            {{ $kuisioner->created_at->locale('id')->translatedFormat('d F Y') }}
                        </p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600">Judul KP</p>
                        <p class="text-gray-900">
                            {{ $kuisioner->kerjaPraktek?->judul_kp ?? '-' }}
                        </p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600">Tempat Magang</p>
                        <p class="text-gray-900">
                            @php
                                $kp = $kuisioner->kerjaPraktek;
                                $tm = $kp?->tempatMagang;
                            @endphp
                            {{ $kp?->pilihan_tempat == 3 ? ($kp->tempat_magang_sendiri ?? '-') : ($tm?->nama_perusahaan ?? '-') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Hasil Kuisioner</h3>
                </div>
                <div class="p-6 space-y-4">
                    {{-- Pertanyaan Dinamis --}}
                    @if($kuisioner->dynamic_answers)
                        @php
                            $questions = \App\Models\KuisionerQuestion::where('is_active', true)->orderBy('order')->get();
                        @endphp
                        @foreach($questions as $question)
                            @if(isset($kuisioner->dynamic_answers[$question->id]))
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">{{ $question->question_text }}</p>
                                    @if($question->type === 'rating')
                                        <p class="text-2xl font-bold text-green-600">{{ $kuisioner->dynamic_answers[$question->id] }}/5</p>
                                    @elseif($question->type === 'yes_no')
                                        @if($kuisioner->dynamic_answers[$question->id])
                                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Ya</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">Tidak</span>
                                        @endif
                                    @else
                                        <p class="text-gray-900">{{ $kuisioner->dynamic_answers[$question->id] ?: '-' }}</p>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Rating Tempat</p>
                            <p class="text-2xl font-bold text-green-600">{{ $kuisioner->rating_tempat_magang }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Rating Bimbingan</p>
                            <p class="text-2xl font-bold text-green-600">{{ $kuisioner->rating_bimbingan }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Rating Sistem</p>
                            <p class="text-2xl font-bold text-green-600">{{ $kuisioner->rating_sistem }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Rekomendasi Tempat</p>
                        @if($kuisioner->rekomendasi_tempat)
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Direkomendasikan</span>
                        @else
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">Tidak Direkomendasikan</span>
                        @endif
                    </div>

                    @if($kuisioner->saran_perbaikan)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Saran Perbaikan</p>
                            <p class="text-gray-900">{{ $kuisioner->saran_perbaikan }}</p>
                        </div>
                    @endif

                    @if($kuisioner->kesan_pesan)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Kesan & Pesan</p>
                            <p class="text-gray-900">{{ $kuisioner->kesan_pesan }}</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-sidebar-layout>
