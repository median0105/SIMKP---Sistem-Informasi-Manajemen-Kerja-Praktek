{{-- resources/views/pengawas/kuisioner/feedback.blade.php --}}
<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                @if($existingFeedback)
                    Edit Feedback Mahasiswa
                @else
                    Berikan Feedback Mahasiswa
                @endif
            </h2>
            <a href="{{ route('pengawas.kuisioner.show', $kerjaPraktek) }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Info Mahasiswa --}}
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Mahasiswa</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Nama Mahasiswa</p>
                            <p class="text-gray-900 font-medium">{{ $kerjaPraktek->mahasiswa->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">NPM</p>
                            <p class="text-gray-900">{{ $kerjaPraktek->mahasiswa->npm }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-600">Judul Kerja Praktek</p>
                            <p class="text-gray-900">{{ $kerjaPraktek->judul_kp }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Feedback --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Form Feedback</h3>
                </div>
                <form action="{{ route('pengawas.kuisioner.store-feedback', $kerjaPraktek) }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    {{-- Rating Mahasiswa --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Rating Kinerja Mahasiswa <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-4">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="flex items-center">
                                    <input type="radio" name="rating_mahasiswa" value="{{ $i }}"
                                           {{ old('rating_mahasiswa', $existingFeedback->rating_mahasiswa ?? null) == $i ? 'checked' : '' }}
                                           class="text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm">{{ $i }}</span>
                                </label>
                            @endfor
                        </div>
                        <p class="text-xs text-gray-500 mt-1">1 = Sangat Buruk, 5 = Sangat Baik</p>
                        @error('rating_mahasiswa')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Komentar Kinerja --}}
                    <div>
                        <label for="komentar_kinerja" class="block text-sm font-medium text-gray-700 mb-2">
                            Komentar Kinerja Mahasiswa <span class="text-red-500">*</span>
                        </label>
                        <textarea id="komentar_kinerja" name="komentar_kinerja" rows="4"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Berikan komentar tentang kinerja mahasiswa selama kerja praktek...">{{ old('komentar_kinerja', $existingFeedback->komentar_kinerja ?? '') }}</textarea>
                        @error('komentar_kinerja')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kelebihan --}}
                    <div>
                        <label for="kelebihan_mahasiswa" class="block text-sm font-medium text-gray-700 mb-2">
                            Kelebihan Mahasiswa
                        </label>
                        <textarea id="kelebihan_mahasiswa" name="kelebihan_mahasiswa" rows="3"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Sebutkan kelebihan yang dimiliki mahasiswa...">{{ old('kelebihan_mahasiswa', $existingFeedback->kelebihan_mahasiswa ?? '') }}</textarea>
                        @error('kelebihan_mahasiswa')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kekurangan --}}
                    <div>
                        <label for="kekurangan_mahasiswa" class="block text-sm font-medium text-gray-700 mb-2">
                            Kekurangan Mahasiswa
                        </label>
                        <textarea id="kekurangan_mahasiswa" name="kekurangan_mahasiswa" rows="3"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Sebutkan kekurangan yang perlu diperbaiki...">{{ old('kekurangan_mahasiswa', $existingFeedback->kekurangan_mahasiswa ?? '') }}</textarea>
                        @error('kekurangan_mahasiswa')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Saran --}}
                    <div>
                        <label for="saran_mahasiswa" class="block text-sm font-medium text-gray-700 mb-2">
                            Saran untuk Mahasiswa
                        </label>
                        <textarea id="saran_mahasiswa" name="saran_mahasiswa" rows="3"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Berikan saran untuk pengembangan mahasiswa...">{{ old('saran_mahasiswa', $existingFeedback->saran_mahasiswa ?? '') }}</textarea>
                        @error('saran_mahasiswa')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Rekomendasi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Rekomendasi Mahasiswa <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="rekomendasi_mahasiswa" value="1"
                                       {{ old('rekomendasi_mahasiswa', $existingFeedback->rekomendasi_mahasiswa ?? null) ? 'checked' : '' }}
                                       class="text-green-600 focus:ring-green-500">
                                <span class="ml-2 text-sm text-green-700">Ya, saya merekomendasikan mahasiswa ini</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="rekomendasi_mahasiswa" value="0"
                                       {{ old('rekomendasi_mahasiswa', $existingFeedback->rekomendasi_mahasiswa ?? null) === false ? 'checked' : '' }}
                                       class="text-red-600 focus:ring-red-500">
                                <span class="ml-2 text-sm text-red-700">Tidak, saya tidak merekomendasikan mahasiswa ini</span>
                            </label>
                        </div>
                        @error('rekomendasi_mahasiswa')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('pengawas.kuisioner.show', $kerjaPraktek) }}"
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md">
                            Batal
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
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
</x-sidebar-layout>
