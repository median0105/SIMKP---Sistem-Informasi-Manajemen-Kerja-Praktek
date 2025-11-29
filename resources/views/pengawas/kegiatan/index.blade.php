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
                        Kegiatan Mahasiswa
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Filter --}}
            <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <form method="GET" action="{{ route('pengawas.kegiatan.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                    {{-- Search Input --}}
                    <div class="md:col-span-2">
                        <label class="block text-base font-medium text-gray-700 mb-2">
                            Cari Mahasiswa
                        </label>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Nama mahasiswa atau NPM..." 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                        >
                    </div>
                    
                    {{-- Placeholder untuk filter tambahan --}}
                    <div class="hidden md:block">
                        {{-- Reserved untuk filter tambahan --}}
                    </div>
                    
                    {{-- Action Buttons --}}
                    <div class="flex gap-3">
                        <button type="submit"
                                class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center flex-1">
                            <i class="fas fa-search mr-2"></i>
                            Cari
                        </button>
                        @if(request()->filled('search'))
                            <a href="{{ route('pengawas.kegiatan.index') }}"
                               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center flex-1">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Tabel kegiatan --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                {{-- Table header with UNIB gradient --}}
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Daftar Kegiatan Mahasiswa
                    </h3>
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                        Total: {{ $kegiatan->total() }}
                    </div>
                </div>

                @if($kegiatan->count())
                    {{-- Scrollable table body --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 table-auto">
                            <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                        Mahasiswa
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                        Tanggal
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                        Durasi
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Deskripsi Kegiatan
                                    </th>
                                    <th class="px-6 py-4 text-center text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                        Dokumentasi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($kegiatan as $k)
                                    <tr class="hover:bg-unib-blue-50 transition-colors duration-200">
                                        {{-- Mahasiswa --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-semibold text-gray-900 text-base">
                                                {{ $k->mahasiswa->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 mt-1">
                                                {{ $k->mahasiswa->npm }}
                                            </div>
                                        </td>
                                        
                                        {{-- Tanggal --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-gray-900 text-base">
                                                {{ \Illuminate\Support\Carbon::parse($k->tanggal_kegiatan)->locale('id')->translatedFormat('d F Y') }}
                                            </span>
                                        </td>
                                        
                                        {{-- Durasi --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-unib-blue-100 text-unib-blue-800 border border-unib-blue-300 shadow-sm">
                                                {{ $k->durasi_jam }} jam
                                            </span>
                                        </td>
                                        
                                        {{-- Deskripsi --}}
                                        <td class="px-6 py-4">
                                            <span class="text-gray-900 text-base break-words max-w-[300px] inline-block">
                                                {{ $k->deskripsi_kegiatan }}
                                                @if(strlen($k->deskripsi_kegiatan) > 100)
                                                    <button onclick="showFullDescription('{{ $k->deskripsi_kegiatan }}')" 
                                                            class="text-unib-blue-600 hover:text-unib-blue-800 text-sm ml-1">
                                                        selengkapnya
                                                    </button>
                                                @endif
                                            </span>
                                        </td>
                                        
                                        {{-- Dokumentasi --}}
                                        <td class="px-6 py-4 text-center whitespace-nowrap">
                                            @if($k->file_dokumentasi)
                                                <a href="{{ \Illuminate\Support\Facades\Storage::url($k->file_dokumentasi) }}" 
                                                   target="_blank" 
                                                   class="inline-block transform hover:scale-105 transition duration-200">
                                                    <img 
                                                        src="{{ \Illuminate\Support\Facades\Storage::url($k->file_dokumentasi) }}" 
                                                        alt="Dokumentasi Kegiatan" 
                                                        class="w-16 h-16 object-cover rounded-lg border border-unib-blue-200 cursor-pointer hover:opacity-75 transition duration-200 hover:shadow-md"
                                                        loading="lazy"
                                                    >
                                                </a>
                                            @else
                                                <span class="text-gray-500 text-base">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="p-6 border-t bg-unib-blue-50">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <p class="text-sm text-unib-blue-700">
                                Menampilkan {{ $kegiatan->firstItem() }} - {{ $kegiatan->lastItem() }} dari {{ $kegiatan->total() }} hasil
                            </p>
                            <div class="flex space-x-1">
                                {{ $kegiatan->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Empty state dengan animasi DotLottie --}}
                    <div class="text-center py-16 text-gray-500 animate-fade-in-up">
                        <div class="flex flex-col items-center justify-center">
                            <!-- DotLottie Animation -->
                            <div class="mb-6 lottie-container">
                                <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>
                                <dotlottie-wc 
                                    src="https://lottie.host/f4be40d9-971a-446d-9d51-ed1128f637ef/8YGejDufWD.lottie" 
                                    style="width: 300px; height: 300px;" 
                                    autoplay 
                                    loop>
                                </dotlottie-wc>
                            </div>
                            <div class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Kegiatan</div>
                            <p class="text-base text-gray-600 max-w-md mx-auto">
                                Belum ada kegiatan yang tercatat.
                            </p>
                            @if(request()->filled('search'))
                                <button onclick="window.location.href='{{ route('pengawas.kegiatan.index') }}'" 
                                        class="mt-6 bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center">
                                    Reset Filter
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Modal untuk deskripsi lengkap --}}
    <div id="descriptionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 border border-unib-blue-200 max-h-[80vh] overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Deskripsi Lengkap Kegiatan</h3>
                    <button onclick="closeDescriptionModal()" class="text-gray-400 hover:text-gray-600 text-xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mb-6">
                    <p id="fullDescription" class="text-gray-700 text-base leading-relaxed max-h-60 overflow-y-auto"></p>
                </div>
                <div class="flex justify-end">
                    <button onclick="closeDescriptionModal()" 
                            class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md transition duration-200">
                        Tutup
                    </button>
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
        
        /* Smooth transitions for modals */
        .modal-transition {
            transition: all 0.3s ease-out;
        }
        
        /* Table styling improvements */
        .table-auto {
            width: 100%;
            table-layout: auto;
        }
        
        /* Prevent text wrapping in table headers and specific cells */
        .whitespace-nowrap {
            white-space: nowrap;
        }
        
        /* Allow text wrapping in specific cells with max width */
        .break-words {
            word-wrap: break-word;
            word-break: break-word;
        }
        
        /* Smooth hover effects for table rows */
        .transition-colors {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }
        
        /* Style untuk dotlottie container */
        .lottie-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
        }
        
        /* Ensure table cells have proper spacing */
        .min-w-full {
            min-width: 100%;
        }
        
        /* Improve table responsiveness */
        @media (max-width: 768px) {
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>

    <!-- DotLottie Web Component Script -->
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>

    <script>
        // Initialize responsive behavior
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-adjust table columns
            const table = document.querySelector('table');
            if (table) {
                table.style.tableLayout = 'auto';
                
                // Add responsive behavior for smaller screens
                const headers = table.querySelectorAll('thead th');
                const rows = table.querySelectorAll('tbody tr');
                
                headers.forEach((header, index) => {
                    // Set minimum width for headers to prevent too narrow columns
                    header.style.minWidth = '120px';
                });
            }
            
            // Ensure dotlottie-wc loads properly
            if (typeof customElements !== 'undefined') {
                customElements.whenDefined('dotlottie-wc').then(() => {
                    console.log('DotLottie Web Component loaded successfully');
                });
            }
        });

        // Modal functions
        function showFullDescription(description) {
            document.getElementById('fullDescription').textContent = description;
            document.getElementById('descriptionModal').classList.remove('hidden');
            setTimeout(() => {
                document.querySelector('#descriptionModal > div').classList.add('scale-100');
            }, 10);
        }

        function closeDescriptionModal() {
            document.querySelector('#descriptionModal > div').classList.remove('scale-100');
            setTimeout(() => {
                document.getElementById('descriptionModal').classList.add('hidden');
            }, 200);
        }
    </script>
</x-sidebar-layout>