<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('superadmin.kuisioner_questions.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Tambah Pertanyaan Kuisioner
                </h2>
            </div>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <form method="POST" action="{{ route('superadmin.kuisioner_questions.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="question_text" class="block text-sm font-medium text-gray-700 mb-2">
                            Pertanyaan *
                        </label>
                        <textarea name="question_text" id="question_text" rows="3"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                  placeholder="Masukkan pertanyaan kuisioner..." required>{{ old('question_text') }}</textarea>
                        @error('question_text') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipe Jawaban *
                        </label>
                        <select name="type" id="type"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500" required>
                            <option value="">Pilih tipe jawaban</option>
                            <option value="rating" {{ old('type') === 'rating' ? 'selected' : '' }}>Rating (1-5)</option>
                            <option value="text" {{ old('type') === 'text' ? 'selected' : '' }}>Teks</option>
                            <option value="yes_no" {{ old('type') === 'yes_no' ? 'selected' : '' }}>Ya/Tidak</option>
                        </select>
                        @error('type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                            Urutan
                        </label>
                        <input type="number" name="order" id="order" value="{{ old('order', $nextOrder ?? 1) }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500" readonly>
                        <p class="text-xs text-gray-500 mt-1">Urutan otomatis berdasarkan pertanyaan yang sudah ada</p>
                        @error('order') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="text-unib-blue-600 focus:ring-unib-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Aktif</span>
                        </label>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-2 rounded-md font-medium">
                            <i class="fas fa-save mr-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-sidebar-layout>
