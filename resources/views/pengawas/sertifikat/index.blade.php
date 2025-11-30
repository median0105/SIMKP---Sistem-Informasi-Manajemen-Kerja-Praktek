{{-- resources/views/pengawas/sertifikat/index.blade.php --}}
<x-sidebar-layout>
    {{-- Header section with UNIB blue background --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Sertifikat Pengawas Lapangan
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-r-lg shadow-md flex items-center animate-fade-in-up">
                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-lg shadow-md flex items-center animate-fade-in-up">
                    <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Sertifikat List --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                {{-- Table header with UNIB gradient --}}
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Daftar Sertifikat
                    </h3>
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                        Total: {{ $sertifikats->total() }}
                    </div>
                </div>

                @if($sertifikats->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 table-auto">
                            <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                        Nomor Sertifikat
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                        Tahun Ajaran
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                        Tanggal Dibuat
                                    </th>
                                    <th class="px-6 py-4 text-center text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($sertifikats as $sertifikat)
                                    <tr class="hover:bg-unib-blue-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-semibold text-gray-900 text-base">
                                                {{ $sertifikat->nomor_sertifikat }}/SIMKP/SJ/FT/UNIB/{{ $sertifikat->tahun_ajaran }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-unib-blue-100 text-unib-blue-800 border border-unib-blue-300 shadow-sm">
                                                {{ $sertifikat->tahun_ajaran }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-gray-900 text-base">
                                                {{ $sertifikat->created_at->format('d F Y') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center whitespace-nowrap">
                                            <a href="{{ route('pengawas.sertifikat.download', $sertifikat) }}"
                                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-unib-blue-600 hover:bg-unib-blue-700 shadow-md transform hover:scale-105 transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-unib-blue-500">
                                                <i class="fas fa-download mr-2"></i>Download
                                            </a>
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
                                Menampilkan {{ $sertifikats->firstItem() }} - {{ $sertifikats->lastItem() }} dari {{ $sertifikats->total() }} hasil
                            </p>
                            <div class="flex space-x-1">
                                {{ $sertifikats->links() }}
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
                            <div class="text-lg font-medium text-gray-900 mb-2">Belum Ada Sertifikat</div>
                            <p class="text-base text-gray-600 max-w-md mx-auto">
                                Sertifikat Anda belum dibuat oleh Super Admin.
                            </p>
                            <p class="text-sm text-gray-500 mt-2">
                                Silakan hubungi Super Admin untuk membuat sertifikat pembimbing lapangan.
                            </p>
                        </div>
                    </div>
                @endif
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
        
        /* Smooth transitions */
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
        
        /* Table styling improvements */
        .table-auto {
            width: 100%;
            table-layout: auto;
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
    </script>
</x-sidebar-layout>