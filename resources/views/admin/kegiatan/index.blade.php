{{-- resources/views/admin/kegiatan/index.blade.php --}}
<x-sidebar-layout>
    {{-- Header section with UNIB blue background --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Kegiatan Mahasiswa Bimbingan
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Filter Section --}}
            <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-6">
                    <div class="md:col-span-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari deskripsi, nama mahasiswa, atau perusahaan…"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                    </div>
                    <div>
                        <input type="text" name="start_date" value="{{ request('start_date') }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200 datepicker"
                               placeholder="Pilih tanggal">
                    </div>
                    <div>
                        <input type="text" name="end_date" value="{{ request('end_date') }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200 datepicker"
                               placeholder="Pilih tanggal">
                    </div>
                    <div class="flex items-end gap-3">
                        <button type="submit" 
                                class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 w-full flex items-center justify-center">
                            Filter
                        </button>
                        @if(request()->filled('search') || request()->filled('start_date') || request()->filled('end_date'))
                            <a href="{{ route('admin.kegiatan.index') }}" 
                               class="bg-teknik-orange-500 hover:bg-teknik-orange-600 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                {{-- Total Kegiatan --}}
                <div class="bg-white rounded-xl shadow-lg p-6 border border-unib-blue-100 transition-all duration-200 animate-fade-in-up hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Kegiatan</p>
                            <p class="text-2xl font-bold text-unib-blue-600 mt-2">{{ $kegiatan->total() }}</p>
                        </div>
                        <div class="bg-gradient-to-r from-unib-blue-100 to-unib-blue-200 rounded-full p-3 w-10 h-10 flex items-center justify-center">
                            <i class="fas fa-tasks text-unib-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                {{-- Total Durasi --}}
                <div class="bg-white rounded-xl shadow-lg p-6 border border-unib-blue-100 transition-all duration-200 animate-fade-in-up hover:shadow-xl" style="animation-delay: 0.1s;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Durasi</p>
                            <p class="text-2xl font-bold text-green-600 mt-2">{{ $kegiatan->sum('durasi_jam') }} jam</p>
                        </div>
                        <div class="bg-gradient-to-r from-green-100 to-green-200 rounded-full p-3 w-10 h-10 flex items-center justify-center">
                            <i class="fas fa-clock text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                {{-- Mahasiswa Aktif --}}
                <div class="bg-white rounded-xl shadow-lg p-6 border border-unib-blue-100 transition-all duration-200 animate-fade-in-up hover:shadow-xl" style="animation-delay: 0.2s;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Mahasiswa Aktif</p>
                            <p class="text-2xl font-bold text-purple-600 mt-2">{{ $kegiatan->unique('mahasiswa_id')->count() }}</p>
                        </div>
                        <div class="bg-gradient-to-r from-purple-100 to-purple-200 rounded-full p-3 w-10 h-10 flex items-center justify-center">
                            <i class="fas fa-user-graduate text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                {{-- Perusahaan --}}
                <div class="bg-white rounded-xl shadow-lg p-6 border border-unib-blue-100 transition-all duration-200 animate-fade-in-up hover:shadow-xl" style="animation-delay: 0.3s;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Perusahaan</p>
                            <p class="text-2xl font-bold text-teknik-orange-600 mt-2">{{ $kegiatan->unique('kerja_praktek_id')->count() }}</p>
                        </div>
                        <div class="bg-gradient-to-r from-teknik-orange-100 to-teknik-orange-200 rounded-full p-3 w-10 h-10 flex items-center justify-center">
                            <i class="fas fa-building text-teknik-orange-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Activities Table --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                {{-- Table header with UNIB gradient --}}
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Daftar Kegiatan Mahasiswa
                    </h3>
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                        Menampilkan: {{ $kegiatan->count() }} dari {{ $kegiatan->total() }}
                    </div>
                </div>

                @if($kegiatan->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 table-auto">
                            <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                        Tanggal
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                        Mahasiswa
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                        Perusahaan
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                        Durasi
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Deskripsi Kegiatan
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                        Dokumentasi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($kegiatan as $row)
                                    <tr class="hover:bg-unib-blue-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-gray-900 text-base">
                                                    {{ \Illuminate\Support\Carbon::parse($row->tanggal_kegiatan)->locale('id')->translatedFormat('d F Y') }}
                                                </span>
                                                <span class="text-sm text-gray-500 mt-1">
                                                    {{ \Illuminate\Support\Carbon::parse($row->tanggal_kegiatan)->locale('id')->translatedFormat('l') }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-semibold text-gray-900 text-base">{{ $row->mahasiswa->name ?? '-' }}</div>
                                            <div class="text-sm text-gray-500 mt-1">
                                                {{ $row->mahasiswa->npm ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-900 text-base break-words max-w-[200px] inline-block">
                                                {{ $row->kerjaPraktek->tempatMagang->nama_perusahaan ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-unib-blue-100 text-unib-blue-800 border border-unib-blue-300 shadow-sm">
                                                {{ $row->durasi_jam }} jam
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-gray-900 text-base">
                                                {{ \Illuminate\Support\Str::limit($row->deskripsi_kegiatan, 100) }}
                                                @if(strlen($row->deskripsi_kegiatan) > 100)
                                                    <button onclick="showFullDescription({{ $row->id }})" 
                                                            class="text-unib-blue-600 hover:text-unib-blue-800 text-sm font-medium ml-2 transition duration-200">
                                                        Lihat selengkapnya
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($row->file_dokumentasi)
                                                <a href="{{ \Illuminate\Support\Facades\Storage::url($row->file_dokumentasi) }}" 
                                                   target="_blank" 
                                                   class="group relative inline-block">
                                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($row->file_dokumentasi) }}" 
                                                         alt="Dokumentasi Kegiatan" 
                                                         class="w-20 h-20 object-cover rounded-lg shadow-md cursor-pointer transition duration-200 border border-gray-200">
                                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 rounded-lg transition duration-200 flex items-center justify-center">
                                                        <i class="fas fa-expand text-white opacity-0 group-hover:opacity-100 transition duration-200"></i>
                                                    </div>
                                                </a>
                                            @else
                                                <span class="text-gray-400 text-base">-</span>
                                            @endif
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
                            <div class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Data Kegiatan</div>
                            <p class="text-base text-gray-600 max-w-md mx-auto">
                                Data kegiatan mahasiswa tidak ditemukan untuk filter saat ini.
                            </p>
                            @if(request()->filled('search') || request()->filled('start_date') || request()->filled('end_date'))
                                <button onclick="window.location.href='{{ route('admin.kegiatan.index') }}'" 
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

    {{-- Modal for Full Description --}}
    <div id="descriptionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full transform transition-all duration-300 scale-95 border border-unib-blue-200">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Deskripsi Kegiatan Lengkap</h3>
                    <button onclick="closeDescriptionModal()" class="text-gray-400 hover:text-gray-600 transition duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Mahasiswa:</p>
                    <p id="modalStudentName" class="text-base font-semibold text-unib-blue-700"></p>
                </div>
                <div class="bg-unib-blue-50 rounded-lg p-4 border border-unib-blue-200">
                    <p id="modalDescription" class="text-gray-900 text-base leading-relaxed"></p>
                </div>
                <div class="mt-6 flex justify-end">
                    <button onclick="closeDescriptionModal()" 
                            class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center">
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
        
        /* Improved table styling */
        .table-auto {
            width: 100%;
            table-layout: auto;
        }
        
        /* Prevent text wrapping in specific cells */
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
        
        /* Card hover effects without movement */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
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
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Flatpickr for date inputs
            flatpickr('.datepicker', {
                dateFormat: 'Y-m-d',
                locale: 'id',
                allowInput: true,
                altInput: true,
                altFormat: 'd F Y',
                theme: 'light'
            });
            
            // Pastikan dotlottie-wc terload dengan baik
            if (typeof customElements !== 'undefined') {
                customElements.whenDefined('dotlottie-wc').then(() => {
                    console.log('DotLottie Web Component loaded successfully');
                });
            }
        });

        function showFullDescription(activityId) {
            // In a real implementation, you would fetch the full description via AJAX
            // For now, we'll get it from the table row
            const row = document.querySelector(`tr:has(button[onclick="showFullDescription(${activityId})"])`);
            if (row) {
                const studentName = row.querySelector('td:nth-child(2) .font-semibold').textContent;
                const fullDescription = row.querySelector('td:nth-child(5) .text-base').textContent;
                
                document.getElementById('modalStudentName').textContent = studentName;
                document.getElementById('modalDescription').textContent = fullDescription;
                
                document.getElementById('descriptionModal').classList.remove('hidden');
                setTimeout(() => {
                    document.querySelector('#descriptionModal > div').classList.add('scale-100');
                }, 10);
            }
        }

        function closeDescriptionModal() {
            document.querySelector('#descriptionModal > div').classList.remove('scale-100');
            setTimeout(() => {
                document.getElementById('descriptionModal').classList.add('hidden');
            }, 200);
        }
    </script>
</x-sidebar-layout>