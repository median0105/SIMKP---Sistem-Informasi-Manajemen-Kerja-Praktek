{{-- resources/views/mahasiswa/kerja-praktek/kuisioner.blade.php --}}
<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('mahasiswa.kerja-praktek.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Kuisioner Evaluasi KP
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Flash --}}
            @if (session('success'))
                <div class="mb-4 p-3 rounded bg-green-50 text-green-700">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-3 rounded bg-red-50 text-red-700">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 p-3 rounded bg-red-50 text-red-700">
                    <div class="font-semibold mb-1">Periksa lagi input Anda:</div>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- Info ringkas KP --}}
                    <div class="mb-6">
                        <p class="text-sm text-gray-600">Judul KP</p>
                        <p class="text-gray-900 font-medium">{{ $kerjaPraktek->judul_kp }}</p>
                        <p class="text-sm text-gray-600 mt-2">Tempat Magang</p>
                        <p class="text-gray-900">
                            @if($kerjaPraktek->pilihan_tempat == 3)
                                {{ $kerjaPraktek->tempat_magang_sendiri }}
                            @else
                                {{ $kerjaPraktek->tempatMagang->nama_perusahaan ?? '-' }}
                            @endif
                        </p>
                    </div>

                    <form method="POST" action="{{ route('mahasiswa.kerja-praktek.store-kuisioner', $kerjaPraktek) }}" class="space-y-6">
                        @csrf

                        {{-- Pertanyaan Dinamis --}}
                        @foreach($questions as $question)
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $question->question_text }}
                                    @if($question->type === 'rating' || $question->type === 'yes_no')
                                        *
                                    @endif
                                </label>

                                @if($question->type === 'rating')
                                    <div class="flex items-center gap-4">
                                        @for($i=1;$i<=5;$i++)
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="dynamic_answers[{{ $question->id }}]" value="{{ $i }}"
                                                       class="text-unib-blue-600 focus:ring-unib-blue-500"
                                                       {{ (old('dynamic_answers.' . $question->id, $kuisioner->dynamic_answers[$question->id] ?? null) == $i) ? 'checked' : '' }} required>
                                                <span class="ml-2">{{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                @elseif($question->type === 'text')
                                    <textarea name="dynamic_answers[{{ $question->id }}]" rows="3"
                                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                              placeholder="Tuliskan jawaban Anda...">{{ old('dynamic_answers.' . $question->id, $kuisioner->dynamic_answers[$question->id] ?? '') }}</textarea>
                                @elseif($question->type === 'yes_no')
                                    <div class="flex items-center gap-6">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="dynamic_answers[{{ $question->id }}]" value="1"
                                                   class="text-unib-blue-600 focus:ring-unib-blue-500"
                                                   {{ (string)old('dynamic_answers.' . $question->id, (string)($kuisioner->dynamic_answers[$question->id] ?? '')) === '1' ? 'checked' : '' }} required>
                                            <span class="ml-2">Ya</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="dynamic_answers[{{ $question->id }}]" value="0"
                                                   class="text-unib-blue-600 focus:ring-unib-blue-500"
                                                   {{ (string)old('dynamic_answers.' . $question->id, (string)($kuisioner->dynamic_answers[$question->id] ?? '')) === '0' ? 'checked' : '' }} required>
                                            <span class="ml-2">Tidak</span>
                                        </label>
                                    </div>
                                @endif

                                @error('dynamic_answers.' . $question->id) <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                        @endforeach

                        {{-- Rating Tempat Magang --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nilai Tempat Magang (1–5) *
                            </label>
                            <div class="flex items-center gap-4">
                                @for($i=1;$i<=5;$i++)
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="rating_tempat_magang" value="{{ $i }}"
                                               class="text-unib-blue-600 focus:ring-unib-blue-500"
                                               {{ old('rating_tempat_magang', $kuisioner->rating_tempat_magang ?? null) == $i ? 'checked' : '' }} required>
                                        <span class="ml-2">{{ $i }}</span>
                                    </label>
                                @endfor
                            </div>
                            @error('rating_tempat_magang') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Rating Bimbingan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nilai Bimbingan Dosen/Pembimbing (1–5) *
                            </label>
                            <div class="flex items-center gap-4">
                                @for($i=1;$i<=5;$i++)
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="rating_bimbingan" value="{{ $i }}"
                                               class="text-unib-blue-600 focus:ring-unib-blue-500"
                                               {{ old('rating_bimbingan', $kuisioner->rating_bimbingan ?? null) == $i ? 'checked' : '' }} required>
                                        <span class="ml-2">{{ $i }}</span>
                                    </label>
                                @endfor
                            </div>
                            @error('rating_bimbingan') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Rating Sistem --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nilai Sistem SIMKP (1–5) *
                            </label>
                            <div class="flex items-center gap-4">
                                @for($i=1;$i<=5;$i++)
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="rating_sistem" value="{{ $i }}"
                                               class="text-unib-blue-600 focus:ring-unib-blue-500"
                                               {{ old('rating_sistem', $kuisioner->rating_sistem ?? null) == $i ? 'checked' : '' }} required>
                                        <span class="ml-2">{{ $i }}</span>
                                    </label>
                                @endfor
                            </div>
                            @error('rating_sistem') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Saran --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Saran Perbaikan</label>
                            <textarea name="saran_perbaikan" rows="3"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                      placeholder="Tuliskan saran Anda...">{{ old('saran_perbaikan', $kuisioner->saran_perbaikan ?? '') }}</textarea>
                            @error('saran_perbaikan') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Kesan Pesan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kesan & Pesan</label>
                            <textarea name="kesan_pesan" rows="3"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                      placeholder="Tuliskan kesan dan pesan...">{{ old('kesan_pesan', $kuisioner->kesan_pesan ?? '') }}</textarea>
                            @error('kesan_pesan') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Rekomendasi --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Apakah Anda merekomendasikan tempat ini ke teman lain? *
                            </label>
                            <div class="flex items-center gap-6">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="rekomendasi_tempat" value="1"
                                           class="text-unib-blue-600 focus:ring-unib-blue-500"
                                           {{ (string)old('rekomendasi_tempat', (string)($kuisioner->rekomendasi_tempat ?? '')) === '1' ? 'checked' : '' }} required>
                                    <span class="ml-2">Ya</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="rekomendasi_tempat" value="0"
                                           class="text-unib-blue-600 focus:ring-unib-blue-500"
                                           {{ (string)old('rekomendasi_tempat', (string)($kuisioner->rekomendasi_tempat ?? '')) === '0' ? 'checked' : '' }} required>
                                    <span class="ml-2">Tidak</span>
                                </label>
                            </div>
                            @error('rekomendasi_tempat') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                                <i class="fas fa-save mr-2"></i>Simpan Kuisioner
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</x-sidebar-layout>
