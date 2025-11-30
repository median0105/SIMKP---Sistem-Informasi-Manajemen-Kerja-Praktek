{{-- resources/views/mahasiswa/kerja-praktek/kuisioner.blade.php --}}
<x-sidebar-layout>
    {{-- Header section with UNIB blue background --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <a href="{{ route('mahasiswa.kerja-praktek.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg inline-flex items-center transition duration-200 backdrop-blur-sm border border-white/30">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Kuisioner Evaluasi KP
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash messages --}}
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

            {{-- Error messages --}}
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-lg shadow-md animate-fade-in-up">
                    <div class="font-semibold mb-1 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Periksa lagi input Anda:
                    </div>
                    <ul class="list-disc list-inside text-sm mt-2">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Main Form Card --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                    <h3 class="text-base font-semibold text-unib-blue-800">
                        Form Evaluasi Kerja Praktek
                    </h3>
                </div>
                <div class="p-6">

                    {{-- Info KP --}}
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                                <div>
                                    <p class="text-gray-500">Judul KP</p>
                                    <p class="font-medium text-gray-900">{{ $kerjaPraktek->judul_kp }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                                <div>
                                    <p class="text-gray-500">Tempat Magang</p>
                                    <p class="font-medium text-gray-900">
                                        @if($kerjaPraktek->pilihan_tempat == 3)
                                            {{ $kerjaPraktek->tempat_magang_sendiri }}
                                        @else
                                            {{ $kerjaPraktek->tempatMagang->nama_perusahaan ?? '-' }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('mahasiswa.kerja-praktek.store-kuisioner', $kerjaPraktek) }}" class="space-y-6">
                        @csrf

                        {{-- Pertanyaan Dinamis --}}
                        @foreach($questions as $question)
                            <div class="border-b border-gray-200 pb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    {{ $question->question_text }}
                                    @if($question->type === 'rating' || $question->type === 'yes_no')
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>

                                @if($question->type === 'rating')
                                    <div class="flex items-center gap-6">
                                        @for($i=1;$i<=5;$i++)
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="dynamic_answers[{{ $question->id }}]" value="{{ $i }}"
                                                       class="text-unib-blue-600 focus:ring-unib-blue-500"
                                                       {{ (old('dynamic_answers.' . $question->id, $kuisioner->dynamic_answers[$question->id] ?? null) == $i) ? 'checked' : '' }} required>
                                                <span class="ml-2 text-sm font-medium">{{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                @elseif($question->type === 'text')
                                    <textarea name="dynamic_answers[{{ $question->id }}]" rows="3"
                                              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                              placeholder="Tuliskan jawaban Anda...">{{ old('dynamic_answers.' . $question->id, $kuisioner->dynamic_answers[$question->id] ?? '') }}</textarea>
                                @elseif($question->type === 'yes_no')
                                    <div class="flex items-center gap-6">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="dynamic_answers[{{ $question->id }}]" value="1"
                                                   class="text-unib-blue-600 focus:ring-unib-blue-500"
                                                   {{ (string)old('dynamic_answers.' . $question->id, (string)($kuisioner->dynamic_answers[$question->id] ?? '')) === '1' ? 'checked' : '' }} required>
                                            <span class="ml-2 text-sm font-medium">Ya</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="dynamic_answers[{{ $question->id }}]" value="0"
                                                   class="text-unib-blue-600 focus:ring-unib-blue-500"
                                                   {{ (string)old('dynamic_answers.' . $question->id, (string)($kuisioner->dynamic_answers[$question->id] ?? '')) === '0' ? 'checked' : '' }} required>
                                            <span class="ml-2 text-sm font-medium">Tidak</span>
                                        </label>
                                    </div>
                                @endif

                                @error('dynamic_answers.' . $question->id) 
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p> 
                                @enderror
                            </div>
                        @endforeach

                        {{-- Rating Sections --}}
                        <div class="space-y-6">
                            {{-- Rating Tempat Magang --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Nilai Tempat Magang (1–5) <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-6">
                                    @for($i=1;$i<=5;$i++)
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="rating_tempat_magang" value="{{ $i }}"
                                                   class="text-unib-blue-600 focus:ring-unib-blue-500"
                                                   {{ old('rating_tempat_magang', $kuisioner->rating_tempat_magang ?? null) == $i ? 'checked' : '' }} required>
                                            <span class="ml-2 text-sm font-medium">{{ $i }}</span>
                                        </label>
                                    @endfor
                                </div>
                                @error('rating_tempat_magang') 
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p> 
                                @enderror
                            </div>

                            {{-- Rating Bimbingan --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Nilai Bimbingan Dosen/Pembimbing (1–5) <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-6">
                                    @for($i=1;$i<=5;$i++)
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="rating_bimbingan" value="{{ $i }}"
                                                   class="text-unib-blue-600 focus:ring-unib-blue-500"
                                                   {{ old('rating_bimbingan', $kuisioner->rating_bimbingan ?? null) == $i ? 'checked' : '' }} required>
                                            <span class="ml-2 text-sm font-medium">{{ $i }}</span>
                                        </label>
                                    @endfor
                                </div>
                                @error('rating_bimbingan') 
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p> 
                                @enderror
                            </div>

                            {{-- Rating Sistem --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Nilai Sistem SIMKP (1–5) <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-6">
                                    @for($i=1;$i<=5;$i++)
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="rating_sistem" value="{{ $i }}"
                                                   class="text-unib-blue-600 focus:ring-unib-blue-500"
                                                   {{ old('rating_sistem', $kuisioner->rating_sistem ?? null) == $i ? 'checked' : '' }} required>
                                            <span class="ml-2 text-sm font-medium">{{ $i }}</span>
                                        </label>
                                    @endfor
                                </div>
                                @error('rating_sistem') 
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p> 
                                @enderror
                            </div>
                        </div>

                        {{-- Text Areas --}}
                        <div class="space-y-6">
                            {{-- Saran --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Saran Perbaikan</label>
                                <textarea name="saran_perbaikan" rows="3"
                                          class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                          placeholder="Tuliskan saran Anda...">{{ old('saran_perbaikan', $kuisioner->saran_perbaikan ?? '') }}</textarea>
                                @error('saran_perbaikan') 
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p> 
                                @enderror
                            </div>

                            {{-- Kesan Pesan --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kesan & Pesan</label>
                                <textarea name="kesan_pesan" rows="3"
                                          class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                          placeholder="Tuliskan kesan dan pesan...">{{ old('kesan_pesan', $kuisioner->kesan_pesan ?? '') }}</textarea>
                                @error('kesan_pesan') 
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p> 
                                @enderror
                            </div>
                        </div>

                        {{-- Rekomendasi --}}
                        <div class="border-t border-gray-200 pt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Apakah Anda merekomendasikan tempat ini ke teman lain? <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center gap-6">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="rekomendasi_tempat" value="1"
                                           class="text-unib-blue-600 focus:ring-unib-blue-500"
                                           {{ (string)old('rekomendasi_tempat', (string)($kuisioner->rekomendasi_tempat ?? '')) === '1' ? 'checked' : '' }} required>
                                    <span class="ml-2 text-sm font-medium">Ya</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="rekomendasi_tempat" value="0"
                                           class="text-unib-blue-600 focus:ring-unib-blue-500"
                                           {{ (string)old('rekomendasi_tempat', (string)($kuisioner->rekomendasi_tempat ?? '')) === '0' ? 'checked' : '' }} required>
                                    <span class="ml-2 text-sm font-medium">Tidak</span>
                                </label>
                            </div>
                            @error('rekomendasi_tempat') 
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p> 
                            @enderror
                        </div>

                        {{-- Action Button --}}
                        <div class="flex justify-end pt-6">
                            <button type="submit"
                                    class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium transition duration-200 flex items-center">
                                <i class="fas fa-save mr-2"></i>Simpan Kuisioner
                            </button>
                        </div>
                    </form>
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