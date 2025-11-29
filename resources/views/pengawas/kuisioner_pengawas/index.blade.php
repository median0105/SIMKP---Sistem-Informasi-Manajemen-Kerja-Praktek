{{-- resources/views/pengawas/kuisioner_pengawas/index.blade.php --}}
<x-sidebar-layout>
    {{-- Header section with UNIB blue background --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                    <!-- Ikon dihapus sesuai pattern -->
                </div>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Kuisioner Pengawas Lapangan
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-r-lg shadow-md flex items-center animate-fade-in-up">
                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Instructions --}}
            <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-unib-blue-500 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-unib-blue-800">
                            Instruksi Pengisian Kuisioner
                        </h3>
                        <div class="mt-2 text-sm text-unib-blue-700">
                            <p class="mb-2">Silakan isi kuisioner berikut dengan jujur dan objektif berdasarkan pengalaman Anda sebagai pembimbing lapangan.</p>
                            <p>Kuisioner ini akan membantu kami meningkatkan kualitas program kerja praktek mahasiswa.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                {{-- Form header with UNIB gradient --}}
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold flex items-center">
                        <i class="fas fa-edit mr-3"></i>
                        Form Kuisioner Pengawas Lapangan
                    </h3>
                </div>

                <form method="POST" action="{{ route('pengawas.kuisioner-pengawas.store') }}" class="p-6 space-y-8">
                    @csrf

                    @foreach($questions as $index => $question)
                        <div class="border-b border-unib-blue-100 pb-8 last:border-b-0 last:pb-0">
                            <div class="mb-4">
                                <label class="block text-lg font-semibold text-gray-900 mb-4 flex items-start">
                                    <span class="bg-unib-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-medium mr-3 mt-1 flex-shrink-0">
                                        {{ $index + 1 }}
                                    </span>
                                    <span class="flex-1">{{ $question->question_text }}</span>
                                </label>

                                @if($question->type === 'rating')
                                    <div class="flex items-center space-x-6 bg-unib-blue-50 p-4 rounded-lg border border-unib-blue-200">
                                        <span class="text-sm font-medium text-unib-blue-700">Rating:</span>
                                        <div class="flex items-center space-x-4">
                                            @for($i = 1; $i <= 5; $i++)
                                                <label class="flex items-center cursor-pointer group">
                                                    <input type="radio"
                                                           name="responses[{{ $index }}][rating]"
                                                           value="{{ $i }}"
                                                           {{ ($responses[$question->id]->rating ?? null) == $i ? 'checked' : '' }}
                                                           class="text-unib-blue-600 focus:ring-unib-blue-500 border-unib-blue-300">
                                                    <span class="ml-2 text-sm font-medium text-gray-700 group-hover:text-unib-blue-600 transition-colors">
                                                        {{ $i }}
                                                    </span>
                                                </label>
                                            @endfor
                                        </div>
                                        <input type="hidden" name="responses[{{ $index }}][question_id]" value="{{ $question->id }}">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2 flex items-center">
                                        <i class="fas fa-info-circle mr-1 text-unib-blue-500"></i>
                                        1 = Sangat Buruk, 5 = Sangat Baik
                                    </p>

                                @elseif($question->type === 'yes_no')
                                    <div class="flex items-center space-x-8 bg-unib-blue-50 p-4 rounded-lg border border-unib-blue-200">
                                        <span class="text-sm font-medium text-unib-blue-700">Jawaban:</span>
                                        <div class="flex items-center space-x-6">
                                            <label class="flex items-center cursor-pointer group">
                                                <input type="radio"
                                                       name="responses[{{ $index }}][yes_no]"
                                                       value="1"
                                                       {{ ($responses[$question->id]->yes_no ?? null) === true ? 'checked' : '' }}
                                                       class="text-unib-blue-600 focus:ring-unib-blue-500 border-unib-blue-300">
                                                <span class="ml-2 text-sm font-medium text-gray-700 group-hover:text-unib-blue-600 transition-colors">
                                                    Ya
                                                </span>
                                            </label>
                                            <label class="flex items-center cursor-pointer group">
                                                <input type="radio"
                                                       name="responses[{{ $index }}][yes_no]"
                                                       value="0"
                                                       {{ ($responses[$question->id]->yes_no ?? null) === false ? 'checked' : '' }}
                                                       class="text-unib-blue-600 focus:ring-unib-blue-500 border-unib-blue-300">
                                                <span class="ml-2 text-sm font-medium text-gray-700 group-hover:text-unib-blue-600 transition-colors">
                                                    Tidak
                                                </span>
                                            </label>
                                        </div>
                                        <input type="hidden" name="responses[{{ $index }}][question_id]" value="{{ $question->id }}">
                                    </div>

                                @else
                                    <div class="bg-unib-blue-50 p-4 rounded-lg border border-unib-blue-200">
                                        <textarea name="responses[{{ $index }}][answer]"
                                                  rows="4"
                                                  class="w-full border-unib-blue-200 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                                  placeholder="Tuliskan jawaban Anda di sini...">{{ $responses[$question->id]->answer ?? '' }}</textarea>
                                        <input type="hidden" name="responses[{{ $index }}][question_id]" value="{{ $question->id }}">
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    {{-- Submit Button --}}
                    <div class="flex justify-end pt-6 border-t border-unib-blue-200">
                        <button type="submit"
                                class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-8 py-3 rounded-lg font-semibold shadow-lg transform hover:scale-105 transition duration-200 flex items-center">
                            <i class="fas fa-save mr-3"></i>Simpan Kuisioner
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
        
        /* Smooth transitions for form elements */
        .transition-colors {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }
        
        /* Improve radio button styling */
        input[type="radio"] {
            border-color: #d1d5db;
        }
        
        input[type="radio"]:checked {
            border-color: #1e40af;
            background-color: #1e40af;
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .space-y-8 > * + * {
                margin-top: 1.5rem;
            }
            
            .flex.items-center.space-x-6 {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .flex.items-center.space-x-8 {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }
        
        @media (max-width: 640px) {
            .px-6 {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .p-6 {
                padding: 1rem;
            }
            
            .text-lg {
                font-size: 1rem;
                line-height: 1.5rem;
            }
            
            .text-xl {
                font-size: 1.125rem;
                line-height: 1.75rem;
            }
        }
    </style>
</x-sidebar-layout>