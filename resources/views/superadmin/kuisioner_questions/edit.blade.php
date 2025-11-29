<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Pertanyaan Kuisioner
            </h2>
            <a href="{{ route('superadmin.kuisioner_questions.index') }}" class="text-unib-blue-600 hover:text-unib-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <form method="POST" action="{{ route('superadmin.kuisioner_questions.update', $kuisionerQuestion) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="question_text" class="block text-sm font-medium text-gray-700 mb-2">
                            Pertanyaan *
                        </label>
                        <textarea name="question_text" id="question_text" rows="3"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                  placeholder="Masukkan pertanyaan kuisioner..." required>{{ old('question_text', $kuisionerQuestion->question_text) }}</textarea>
                        @error('question_text') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipe Jawaban *
                        </label>
                        <select name="type" id="type"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500" required>
                            <option value="">Pilih tipe jawaban</option>
                            <option value="rating" {{ old('type', $kuisionerQuestion->type) === 'rating' ? 'selected' : '' }}>Rating (1-5)</option>
                            <option value="text" {{ old('type', $kuisionerQuestion->type) === 'text' ? 'selected' : '' }}>Teks</option>
                            <option value="yes_no" {{ old('type', $kuisionerQuestion->type) === 'yes_no' ? 'selected' : '' }}>Ya/Tidak</option>
                        </select>
                        @error('type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                            Urutan
                        </label>
                        <input type="number" name="order" id="order" value="{{ old('order', $kuisionerQuestion->order) }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                        @error('order') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $kuisionerQuestion->is_active) ? 'checked' : '' }}
                                   class="text-unib-blue-600 focus:ring-unib-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Aktif</span>
                        </label>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-2 rounded-md font-medium">
                            <i class="fas fa-save mr-2"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-sidebar-layout>
