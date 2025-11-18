{{-- resources/views/pengawas/kuisioner_pengawas/index.blade.php --}}
<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kuisioner Pengawas Lapangan
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Instructions --}}
            <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">
                            Instruksi Pengisian Kuisioner
                        </h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>Silakan isi kuisioner berikut dengan jujur dan objektif. Kuisioner ini akan membantu kami meningkatkan kualitas program kerja praktek.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Form Kuisioner Pengawas Lapangan</h3>
                </div>

                <form method="POST" action="{{ route('pengawas.kuisioner-pengawas.store') }}" class="p-6 space-y-6">
                    @csrf

                    @foreach($questions as $index => $question)
                        <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $index + 1 }}. {{ $question->question_text }}
                                </label>

                                @if($question->type === 'rating')
                                    <div class="flex items-center space-x-4">
                                        <span class="text-sm text-gray-600">Rating:</span>
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="flex items-center">
                                                <input type="radio"
                                                       name="responses[{{ $index }}][rating]"
                                                       value="{{ $i }}"
                                                       {{ ($responses[$question->id]->rating ?? null) == $i ? 'checked' : '' }}
                                                       class="text-blue-600 focus:ring-blue-500">
                                                <span class="ml-1 text-sm">{{ $i }}</span>
                                            </label>
                                        @endfor
                                        <input type="hidden" name="responses[{{ $index }}][question_id]" value="{{ $question->id }}">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">1 = Sangat Buruk, 5 = Sangat Baik</p>

                                @elseif($question->type === 'yes_no')
                                    <div class="flex items-center space-x-6">
                                        <label class="flex items-center">
                                            <input type="radio"
                                                   name="responses[{{ $index }}][yes_no]"
                                                   value="1"
                                                   {{ ($responses[$question->id]->yes_no ?? null) === true ? 'checked' : '' }}
                                                   class="text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2 text-sm">Ya</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio"
                                                   name="responses[{{ $index }}][yes_no]"
                                                   value="0"
                                                   {{ ($responses[$question->id]->yes_no ?? null) === false ? 'checked' : '' }}
                                                   class="text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2 text-sm">Tidak</span>
                                        </label>
                                        <input type="hidden" name="responses[{{ $index }}][question_id]" value="{{ $question->id }}">
                                    </div>

                                @else
                                    <textarea name="responses[{{ $index }}][answer]"
                                              rows="3"
                                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                              placeholder="Jawaban Anda...">{{ $responses[$question->id]->answer ?? '' }}</textarea>
                                    <input type="hidden" name="responses[{{ $index }}][question_id]" value="{{ $question->id }}">
                                @endif
                            </div>
                        </div>
                    @endforeach

                    {{-- Submit Button --}}
                    <div class="flex justify-end pt-6 border-t border-gray-200">
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium">
                            <i class="fas fa-save mr-2"></i>Simpan Kuisioner
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-sidebar-layout>
