<x-sidebar-layout>
    {{-- Header section with UNIB blue background --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Manajemen Bimbingan
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Action Button --}}
            <div class="flex justify-end animate-fade-in-up">
                <a href="{{ route('admin.bimbingan.create') }}"
                   class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-semibold shadow-lg transform hover:scale-105 transition duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>Tambah Bimbingan
                </a>
            </div>

            {{-- Filter & Search --}}
            <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-base font-medium text-gray-700 mb-2">
                        
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari topik atau nama mahasiswa…"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                    </div>
                    
                    <div>
                        <label class="block text-base font-medium text-gray-700 mb-2">
                       
                        </label>
                        <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                            <option value="all" @selected(request('status')==='all' || !request('status'))>Semua Status</option>
                            <option value="pending"  @selected(request('status')==='pending')>Tertunda</option>
                            <option value="verified" @selected(request('status')==='verified')>Terverifikasi</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end gap-3">
                        <button type="submit"
                                class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center flex-1">
                            Cari
                        </button>
                        @if(request()->filled('search') || request()->filled('status'))
                            <a href="{{ route('admin.bimbingan.index') }}"
                               class="bg-teknik-orange-500 hover:bg-teknik-orange-600 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center flex-1">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Daftar Mahasiswa --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                {{-- Table header with UNIB gradient --}}
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Daftar Mahasiswa Bimbingan
                    </h3>
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                        Total: {{ $mahasiswa->total() }}
                    </div>
                </div>

                @if($mahasiswa->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 table-auto">
                            <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                        Mahasiswa
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                        Tempat KP
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                        Total Bimbingan
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                        Bimbingan Terakhir
                                    </th>
                                    <th class="px-6 py-4 text-center text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($mahasiswa as $mhs)
                                    <tr class="hover:bg-unib-blue-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-semibold text-gray-900 text-base">{{ $mhs->name }}</div>
                                            <div class="text-sm text-gray-500 mt-1">
                                                {{ $mhs->npm }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-900 text-base break-words max-w-[200px] inline-block">
                                                @if($mhs->kerjaPraktek && $mhs->kerjaPraktek->first() && $mhs->kerjaPraktek->first()->pilihan_tempat == 3)
                                                    {{ \Illuminate\Support\Str::limit($mhs->kerjaPraktek->first()->tempat_magang_sendiri, 40) }}
                                                @elseif($mhs->kerjaPraktek && $mhs->kerjaPraktek->first() && $mhs->kerjaPraktek->first()->tempatMagang)
                                                    {{ \Illuminate\Support\Str::limit($mhs->kerjaPraktek->first()->tempatMagang->nama_perusahaan, 40) }}
                                                @else
                                                    -
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-unib-blue-100 text-unib-blue-800 border border-unib-blue-300 shadow-sm">
                                                {{ $mhs->bimbingan->count() }} bimbingan
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-gray-900 text-base">
                                                @if($mhs->bimbingan->first())
                                                    {{ \Illuminate\Support\Carbon::parse($mhs->bimbingan->first()->tanggal_bimbingan)->locale('id')->translatedFormat('d F Y') }}
                                                @else
                                                    <span class="text-gray-500">Belum ada</span>
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center whitespace-nowrap">
                                            <div class="flex items-center gap-3 justify-center">
                                                <a href="{{ route('admin.bimbingan.show', ['mahasiswa' => $mhs->id]) }}"
                                                   class="text-unib-blue-600 hover:text-unib-blue-800 text-base font-medium flex items-center px-3 py-2 rounded-lg hover:bg-unib-blue-100 transition-colors duration-200"
                                                   title="Detail Bimbingan">
                                                    Detail
                                                </a>
                                                
                                                @if($mhs->bimbingan->count() > 0)
                                                    <button onclick="openFeedback({{ $mhs->bimbingan->first()->id }})"
                                                            class="text-green-600 hover:text-green-800 text-base font-medium flex items-center px-3 py-2 rounded-lg hover:bg-green-100 transition-colors duration-200"
                                                            title="Beri Feedback">
                                                        Balasan
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination dengan style UNIB --}}
                    <div class="p-6 border-t bg-unib-blue-50">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-unib-blue-700">
                                Menampilkan {{ $mahasiswa->firstItem() }} - {{ $mahasiswa->lastItem() }} dari {{ $mahasiswa->total() }} hasil
                            </p>
                            <div class="flex space-x-1">
                                {{ $mahasiswa->withQueryString()->links() }}
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
                            <div class="text-lg font-medium text-gray-900 mb-2">Belum Ada Mahasiswa Bimbingan</div>
                            <p class="text-base text-gray-600 max-w-md mx-auto">
                                Data mahasiswa bimbingan tidak ditemukan untuk filter saat ini.
                            </p>
                            @if(request()->filled('search') || request()->filled('status'))
                                <button onclick="window.location.href='{{ route('admin.bimbingan.index') }}'" 
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

    {{-- Modal Feedback --}}
    <div id="feedbackModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 border border-unib-blue-200">
            <form id="feedbackForm" method="POST">
                @csrf
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 rounded-full p-3 mr-4">
                            <i class="fas fa-comment text-green-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Beri Feedback</h3>
                    </div>
                    <div class="mb-6">
                        <label class="block text-base font-medium text-gray-700 mb-3">Feedback untuk Mahasiswa</label>
                        <textarea name="catatan_dosen" rows="4" required
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                  placeholder="Tulis feedback yang konstruktif untuk mahasiswa…"></textarea>
                    </div>
                    <div class="flex space-x-4">
                        <button type="submit" 
                                class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex-1 flex items-center justify-center">
                            Simpan
                        </button>
                        <button type="button" 
                                onclick="closeFeedback()" 
                                class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex-1 flex items-center justify-center">
                            Batal
                        </button>
                    </div>
                </div>
            </form>
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
        function openFeedback(id) {
            const form = document.getElementById('feedbackForm');
            form.action = `/admin/bimbingan/${id}/feedback`;
            document.getElementById('feedbackModal').classList.remove('hidden');
            setTimeout(() => {
                document.querySelector('#feedbackModal > div').classList.add('scale-100');
            }, 10);
        }

        function closeFeedback() {
            document.querySelector('#feedbackModal > div').classList.remove('scale-100');
            setTimeout(() => {
                document.getElementById('feedbackModal').classList.add('hidden');
            }, 200);
        }
        
        // Initialize table functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Pastikan dotlottie-wc terload dengan baik
            if (typeof customElements !== 'undefined') {
                customElements.whenDefined('dotlottie-wc').then(() => {
                    console.log('DotLottie Web Component loaded successfully');
                });
            }
            
            // Auto-adjust table columns based on content
            const table = document.querySelector('table');
            if (table) {
                // Force table to auto-adjust column widths
                table.style.tableLayout = 'auto';
                
                // Add responsive behavior for smaller screens
                const headers = table.querySelectorAll('thead th');
                const rows = table.querySelectorAll('tbody tr');
                
                headers.forEach((header, index) => {
                    // Set minimum width for headers to prevent too narrow columns
                    header.style.minWidth = '120px';
                });
            }
        });
    </script>
</x-sidebar-layout>