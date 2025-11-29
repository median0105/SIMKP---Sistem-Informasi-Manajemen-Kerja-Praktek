<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                   
                </div>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Edit Pertanyaan Kuisioner Pengawas
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Flash messages for success --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-r-lg shadow-md flex items-center mb-6">
                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Card Form --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-unib-blue-100">
                {{-- Card Header --}}
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Form Edit Pertanyaan
                    </h3>
                </div>

                {{-- Card Body --}}
                <div class="p-6">
                    <form method="POST" action="{{ route('superadmin.kuisioner_pengawas_questions.update', $question->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- Pertanyaan --}}
                        <div class="mb-6">
                            <label for="question_text" class="block text-base font-semibold text-gray-700 mb-3">
                                Pertanyaan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="question_text" id="question_text" rows="4"
                                      class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                      placeholder="Masukkan pertanyaan kuisioner pengawas..." required>{{ old('question_text', $question->question_text) }}</textarea>
                            @error('question_text') 
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p> 
                            @enderror
                        </div>

                        {{-- Tipe Jawaban --}}
                        <div class="mb-6">
                            <label for="type" class="block text-base font-semibold text-gray-700 mb-3">
                                Tipe Jawaban <span class="text-red-500">*</span>
                            </label>
                            <select name="type" id="type"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200" required>
                                <option value="">Pilih tipe jawaban</option>
                                <option value="rating" {{ old('type', $question->type) === 'rating' ? 'selected' : '' }}>Rating (1-5)</option>
                                <option value="text" {{ old('type', $question->type) === 'text' ? 'selected' : '' }}>Teks</option>
                                <option value="yes_no" {{ old('type', $question->type) === 'yes_no' ? 'selected' : '' }}>Ya/Tidak</option>
                                <option value="qualitative_rating" {{ old('type', $question->type) === 'qualitative_rating' ? 'selected' : '' }}>Tidak Baik, Kurang Baik, Cukup Baik, Sangat Baik</option>
                            </select>
                            @error('type') 
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p> 
                            @enderror
                        </div>

                        {{-- Urutan --}}
                        <div class="mb-6">
                            <label for="order" class="block text-base font-semibold text-gray-700 mb-3">
                                Urutan
                            </label>
                            <input type="number" name="order" id="order" value="{{ old('order', $question->order) }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200" min="1">
                            @error('order') 
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p> 
                            @enderror
                        </div>

                        {{-- Status Aktif --}}
                        <div class="mb-8">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_active" value="1" 
                                       {{ old('is_active', $question->is_active) ? 'checked' : '' }}
                                       class="text-unib-blue-600 focus:ring-unib-blue-500">
                                <span class="ml-2 text-base text-gray-700">Aktif</span>
                            </label>
                            @error('is_active') 
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p> 
                            @enderror
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('superadmin.kuisioner_pengawas_questions.index') }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-base font-semibold shadow-md transform hover:scale-105 transition duration-200 flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                            <button type="submit"
                                    class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-8 py-3 rounded-lg text-base font-semibold shadow-lg transform hover:scale-105 transition duration-200 flex items-center">
                                <i class="fas fa-save mr-2"></i>Update Pertanyaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-sidebar-layout>