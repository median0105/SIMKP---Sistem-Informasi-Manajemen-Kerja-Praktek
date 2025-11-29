{{-- resources/views/pengawas/kuisioner/feedback.blade.php --}}
<x-sidebar-layout>
    {{-- Header section with UNIB blue background --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <a href="{{ route('pengawas.kuisioner.show', $kerjaPraktek) }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg inline-flex items-center transition duration-200 backdrop-blur-sm border border-white/30">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                    <!-- Ikon dihapus -->
                </div>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        @if($existingFeedback)
                            Edit Feedback Mahasiswa
                        @else
                            Berikan Feedback Mahasiswa
                        @endif
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Info Mahasiswa Card --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                    <h3 class="text-base font-semibold text-unib-blue-800">
                        Informasi Mahasiswa
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-gray-500">Nama Mahasiswa</p>
                                <p class="font-medium text-gray-900">{{ $kerjaPraktek->mahasiswa->name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-gray-500">NPM</p>
                                <p class="font-medium text-gray-900">{{ $kerjaPraktek->mahasiswa->npm }}</p>
                            </div>
                        </div>
                        <div class="flex items-center md:col-span-2">
                            <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-gray-500">Judul Kerja Praktek</p>
                                <p class="font-medium text-gray-900">{{ $kerjaPraktek->judul_kp }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Feedback Card --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                    <h3 class="text-base font-semibold text-unib-blue-800">
                        Form Feedback
                    </h3>
                </div>
                <form action="{{ route('pengawas.kuisioner.store-feedback', $kerjaPraktek) }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    {{-- Rating Mahasiswa --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Rating Kinerja Mahasiswa <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-6">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="inline-flex items-center">
                                    <input type="radio" name="rating_mahasiswa" value="{{ $i }}"
                                           {{ old('rating_mahasiswa', $existingFeedback->rating_mahasiswa ?? null) == $i ? 'checked' : '' }}
                                           class="text-unib-blue-600 focus:ring-unib-blue-500"
                                           required>
                                    <span class="ml-2 text-sm font-medium">{{ $i }}</span>
                                </label>
                            @endfor
                        </div>
                        <p class="text-xs text-gray-500 mt-2 flex items-center">
                            <i class="fas fa-info-circle mr-1 text-unib-blue-400"></i>
                            1 = Sangat Buruk, 5 = Sangat Baik
                        </p>
                        @error('rating_mahasiswa')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Komentar Kinerja --}}
                    <div>
                        <label for="komentar_kinerja" class="block text-sm font-medium text-gray-700 mb-2">
                            Komentar Kinerja Mahasiswa <span class="text-red-500">*</span>
                        </label>
                        <textarea id="komentar_kinerja" name="komentar_kinerja" rows="4"
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                  placeholder="Berikan komentar tentang kinerja mahasiswa selama kerja praktek..."
                                  required>{{ old('komentar_kinerja', $existingFeedback->komentar_kinerja ?? '') }}</textarea>
                        @error('komentar_kinerja')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Kelebihan --}}
                    <div>
                        <label for="kelebihan_mahasiswa" class="block text-sm font-medium text-gray-700 mb-2">
                            Kelebihan Mahasiswa
                        </label>
                        <textarea id="kelebihan_mahasiswa" name="kelebihan_mahasiswa" rows="3"
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                  placeholder="Sebutkan kelebihan yang dimiliki mahasiswa...">{{ old('kelebihan_mahasiswa', $existingFeedback->kelebihan_mahasiswa ?? '') }}</textarea>
                        @error('kelebihan_mahasiswa')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Kekurangan --}}
                    <div>
                        <label for="kekurangan_mahasiswa" class="block text-sm font-medium text-gray-700 mb-2">
                            Kekurangan Mahasiswa
                        </label>
                        <textarea id="kekurangan_mahasiswa" name="kekurangan_mahasiswa" rows="3"
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                  placeholder="Sebutkan kekurangan yang perlu diperbaiki...">{{ old('kekurangan_mahasiswa', $existingFeedback->kekurangan_mahasiswa ?? '') }}</textarea>
                        @error('kekurangan_mahasiswa')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Saran --}}
                    <div>
                        <label for="saran_mahasiswa" class="block text-sm font-medium text-gray-700 mb-2">
                            Saran untuk Mahasiswa
                        </label>
                        <textarea id="saran_mahasiswa" name="saran_mahasiswa" rows="3"
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                  placeholder="Berikan saran untuk pengembangan mahasiswa...">{{ old('saran_mahasiswa', $existingFeedback->saran_mahasiswa ?? '') }}</textarea>
                        @error('saran_mahasiswa')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Rekomendasi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Rekomendasi Mahasiswa <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150 cursor-pointer">
                                <input type="radio" name="rekomendasi_mahasiswa" value="1"
                                       {{ old('rekomendasi_mahasiswa', $existingFeedback->rekomendasi_mahasiswa ?? null) ? 'checked' : '' }}
                                       class="text-green-600 focus:ring-green-500"
                                       required>
                                <div class="ml-3">
                                    <span class="text-sm font-medium text-green-700">Ya, saya merekomendasikan mahasiswa ini</span>
                                </div>
                            </label>
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150 cursor-pointer">
                                <input type="radio" name="rekomendasi_mahasiswa" value="0"
                                       {{ old('rekomendasi_mahasiswa', $existingFeedback->rekomendasi_mahasiswa ?? null) === false ? 'checked' : '' }}
                                       class="text-red-600 focus:ring-red-500"
                                       required>
                                <div class="ml-3">
                                    <span class="text-sm font-medium text-red-700">Tidak, saya tidak merekomendasikan mahasiswa ini</span>
                                </div>
                            </label>
                        </div>
                        @error('rekomendasi_mahasiswa')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center justify-end gap-3 pt-6">
                        <a href="{{ route('pengawas.kuisioner.show', $kerjaPraktek) }}"
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-base font-medium transition duration-200 flex items-center">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                        <button type="submit" 
                                class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium transition duration-200 flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            @if($existingFeedback)
                                Update Feedback
                            @else
                                Simpan Feedback
                            @endif
                        </button>
                    </div>
                </form>
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