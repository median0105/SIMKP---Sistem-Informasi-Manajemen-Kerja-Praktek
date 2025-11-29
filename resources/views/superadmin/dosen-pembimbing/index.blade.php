{{-- resources/views/superadmin/dosen-pembimbing/index.blade.php --}}
<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                    
                </div>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Data Dosen Pembimbing
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            {{-- ========================= FLASH ========================= --}}
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

            {{-- ========================= FILTER ========================= --}}
            <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                    <div class="md:col-span-3">
                        <label class="block text-base font-medium text-gray-700 mb-2">
                           
                        </label>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Cari nama atau email dosen..." 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                        >
                    </div>

                    <div class="flex gap-3">
                        <button class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center flex-1">
                            Cari
                        </button>
                        <a href="{{ route('superadmin.dosen-pembimbing.index') }}"
                           class="bg-teknik-orange-500 hover:bg-teknik-orange-600 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center flex-1">
                            Reset
                        </a>
                    </div>
                </form>
            </div>


            {{-- ========================= TABLE ========================= --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <!-- Table header with UNIB blue gradient -->
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Data Dosen Pembimbing
                    </h3>
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                        Total: {{ $dosen->total() }}
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    Nama
                                </th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    Email
                                </th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    NIP
                                </th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    No HP
                                </th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    Status
                                </th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    Dibuat
                                </th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($dosen as $d)
                            <tr class="hover:bg-unib-blue-50 transition-all duration-150 ease-in-out group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-gray-900 text-base group-hover:text-unib-blue-700 transition-colors duration-150">
                                        {{ $d->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-900 text-base group-hover:text-unib-blue-700 transition-colors duration-150">
                                        {{ $d->email }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-900 text-base group-hover:text-unib-blue-700 transition-colors duration-150">
                                        {{ $d->nip ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-900 text-base group-hover:text-unib-blue-700 transition-colors duration-150">
                                        {{ $d->phone ?? '-' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($d->is_active)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-300 shadow-sm transition-all duration-150">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-300 shadow-sm transition-all duration-150">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-900 text-base group-hover:text-unib-blue-700 transition-colors duration-150">
                                        {{ $d->created_at->locale('id')->translatedFormat('d F Y') }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('superadmin.dosen-pembimbing.show', $d) }}"
                                       class="text-unib-blue-600 hover:text-unib-blue-800 text-base font-medium flex items-center px-3 py-2 rounded-lg hover:bg-unib-blue-100 transition-all duration-150"
                                       title="Lihat Detail">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-16 text-gray-500 animate-fade-in-up">
                                    <div class="flex flex-col items-center justify-center">
                                        <!-- DotLottie Animation -->
                                        <div class="mb-6 lottie-container">
                                            <dotlottie-wc 
                                                src="https://lottie.host/f4be40d9-971a-446d-9d51-ed1128f637ef/8YGejDufWD.lottie" 
                                                style="width: 300px; height: 300px;" 
                                                autoplay 
                                                loop>
                                            </dotlottie-wc>
                                        </div>
                                        <div class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Data Dosen Pembimbing</div>
                                        <p class="text-base text-gray-600 max-w-md mx-auto">
                                            Data dosen pembimbing tidak ditemukan untuk filter saat ini.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination dengan style UNIB -->
                <div class="p-6 border-t bg-unib-blue-50">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-unib-blue-700">
                            Menampilkan {{ $dosen->firstItem() }} - {{ $dosen->lastItem() }} dari {{ $dosen->total() }} hasil
                        </p>
                        <div class="flex space-x-1">
                            {{ $dosen->withQueryString()->links() }}
                        </div>
                    </div>
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
        
        /* Style untuk dotlottie container */
        .lottie-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
        }
        
        /* Perbaikan untuk mencegah gerakan yang tidak diinginkan */
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
        }
        
        /* Menghilangkan transform yang menyebabkan pergeseran layout */
        tbody tr {
            transform: none !important;
            will-change: auto;
        }
        
        /* Smooth transitions tanpa mengganggu layout */
        .hover\\:bg-unib-blue-50 {
            transition: background-color 0.2s ease-in-out;
        }
        
        .group-hover\\:text-unib-blue-700 {
            transition: color 0.2s ease-in-out;
        }
        
        /* Memastikan tombol tidak menyebabkan pergeseran */
        .transform {
            transition: transform 0.2s ease-in-out;
        }
        
        .hover\\:scale-105:hover {
            transform: scale(1.05);
        }
    </style>

    <!-- DotLottie Web Component Script -->
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>

    <script>
        // Menghilangkan efek transform pada row yang menyebabkan pergeseran
        document.addEventListener('DOMContentLoaded', function() {
            // Pastikan dotlottie-wc terload dengan baik
            if (typeof customElements !== 'undefined') {
                customElements.whenDefined('dotlottie-wc').then(() => {
                    console.log('DotLottie Web Component loaded successfully');
                });
            }
            
            // Menghapus class transform yang tidak diperlukan dari table rows
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                // Hapus class transform yang menyebabkan pergeseran
                row.classList.remove('transform', 'hover:scale-105');
            });
        });
    </script>
</x-sidebar-layout>