<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                    <!-- Icon placeholder -->
                </div>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Kelola Pertanyaan Kuisioner Pengawas
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            {{-- Button to add new question --}}
            <div class="flex justify-end">
                <a href="{{ route('superadmin.kuisioner_pengawas_questions.create') }}"
                   class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-semibold shadow-lg transition duration-200 flex items-center">
                    <i class="fa-solid fa-plus mr-2"></i>Tambah Pertanyaan
                </a>
            </div>

            {{-- Flash messages for success --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-r-lg shadow-md flex items-center animate-fade-in-up">
                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif

        
            {{-- TABEL --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                
                {{-- Table header with UNIB blue gradient --}}
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Daftar Pertanyaan Kuisioner Pengawas
                    </h3>
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                        Total: {{ $questions->count() }}
                    </div>
                </div>

                {{-- Scrollable table body --}}
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    Urutan
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    Pertanyaan
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    Tipe
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    Status
                                </th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($questions as $question)
                                <tr class="hover:bg-unib-blue-50 transition-colors duration-150 ease-in-out">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="font-semibold text-gray-900 text-sm">
                                            {{ $question->order }}
                                        </div>
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="text-gray-900 text-sm">
                                            {{ $question->question_text }}
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-unib-blue-100 text-unib-blue-800 border border-unib-blue-300 shadow-sm">
                                            @switch($question->type)
                                                @case('rating')
                                                    Rating (1-5)
                                                    @break
                                                @case('yes_no')
                                                    Ya/Tidak
                                                    @break
                                                @case('text')
                                                    Teks Bebas
                                                    @break
                                                @default
                                                    {{ $question->type }}
                                            @endswitch
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @if($question->is_active)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-300 shadow-sm">
                                                <i class="fas fa-check mr-1 text-xs"></i>Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-300 shadow-sm">
                                                <i class="fas fa-times mr-1 text-xs"></i>Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('superadmin.kuisioner_pengawas_questions.edit', $question) }}"
                                               class="text-unib-blue-600 hover:text-unib-blue-800 text-sm font-medium flex items-center px-3 py-2 rounded-lg hover:bg-unib-blue-100 transition-colors duration-150"
                                               title="Edit Pertanyaan">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>

                                            <form action="{{ route('superadmin.kuisioner_pengawas_questions.destroy', $question) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus pertanyaan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center px-3 py-2 rounded-lg hover:bg-red-50 transition-colors duration-150"
                                                        title="Hapus Pertanyaan">
                                                    <i class="fas fa-trash mr-1"></i>Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-16 text-gray-500 animate-fade-in-up">
                                        <div class="flex flex-col items-center justify-center">
                                            <!-- Animasi Lottie -->
                                            <dotlottie-wc 
                                                src="https://lottie.host/f4be40d9-971a-446d-9d51-ed1128f637ef/8YGejDufWD.lottie" 
                                                style="width: 300px;height: 300px" 
                                                autoplay 
                                                loop>
                                            </dotlottie-wc>
                                            <div class="text-lg font-medium text-gray-900 mb-2 mt-4">Tidak Ada Data Pertanyaan</div>
                                            <p class="text-sm text-gray-600 max-w-md text-center">
                                                Belum ada pertanyaan kuisioner pengawas. 
                                                <br>Klik tombol "Tambah Pertanyaan" untuk menambahkan pertanyaan baru.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>

    {{-- Script untuk Lottie Player --}}
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>

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
        
        /* Perbaikan untuk mencegah gerakan yang tidak diinginkan */
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
        }
        
        /* Smooth transitions tanpa mengganggu layout */
        .hover\\:bg-unib-blue-50 {
            transition: background-color 0.2s ease-in-out;
        }
        
        .transition-colors {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
        
        /* Responsive design untuk mobile */
        @media (max-width: 768px) {
            .table-container {
                overflow-x: auto;
            }
            
            .flex.justify-end {
                justify-content: center;
            }
            
            .flex.justify-end a {
                width: 100%;
                justify-content: center;
            }
        }
        
        @media (max-width: 640px) {
            .px-4.py-3 {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
            }
            
            .text-sm {
                font-size: 0.875rem;
            }
            
            .text-xs {
                font-size: 0.75rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pastikan dotlottie-wc terload dengan baik
            if (typeof customElements !== 'undefined') {
                customElements.whenDefined('dotlottie-wc').then(() => {
                    console.log('DotLottie Web Component loaded successfully');
                });
            }
            
            // Menghilangkan efek transform yang menyebabkan pergeseran
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.classList.remove('transform', 'hover:scale-105');
            });
        });
    </script>
</x-sidebar-layout>