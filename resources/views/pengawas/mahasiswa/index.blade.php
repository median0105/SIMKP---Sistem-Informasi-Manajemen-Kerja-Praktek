<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        {{ __('Mahasiswa Kerja Praktek') }} — {{ $place->nama_perusahaan ?? '-' }}
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Statistics Cards --}}
            @php
                $cards = [
                    ['label'=>'Total Mahasiswa','value'=>$stats['total'] ?? 0,'icon'=>'fa-users','bg'=>'bg-unib-blue-100','iconColor'=>'text-unib-blue-600','numColor'=>'text-unib-blue-600'],
                    ['label'=>'Pengajuan','value'=>$stats['pengajuan'] ?? 0,'icon'=>'fa-hourglass','bg'=>'bg-yellow-100','iconColor'=>'text-yellow-600','numColor'=>'text-yellow-600'],
                    ['label'=>'Disetujui','value'=>$stats['disetujui'] ?? 0,'icon'=>'fa-check','bg'=>'bg-purple-100','iconColor'=>'text-purple-600','numColor'=>'text-purple-600'],
                    ['label'=>'Sedang KP','value'=>$stats['sedang'] ?? 0,'icon'=>'fa-play','bg'=>'bg-emerald-100','iconColor'=>'text-emerald-600','numColor'=>'text-emerald-600'],
                    ['label'=>'Selesai','value'=>$stats['selesai'] ?? 0,'icon'=>'fa-flag-checkered','bg'=>'bg-gray-100','iconColor'=>'text-gray-600','numColor'=>'text-gray-600'],
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 md:gap-6 items-stretch">
                @foreach($cards as $c)
                    <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 h-full border border-unib-blue-100 transition-all duration-300 animate-fade-in-up">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs md:text-sm font-medium text-gray-600">{{ $c['label'] }}</p>
                                <p class="text-xl md:text-2xl font-semibold mt-2 {{ $c['numColor'] }}">
                                    {{ $c['value'] }}
                                </p>
                            </div>
                            <div class="{{ $c['bg'] }} rounded-full p-2 md:p-3 w-10 h-10 md:w-12 md:h-12 flex items-center justify-center border border-unib-blue-200">
                                <i class="fas {{ $c['icon'] }} {{ $c['iconColor'] }} text-base md:text-lg"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Filter --}}
            <div class="bg-white shadow-xl rounded-xl p-4 md:p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm md:text-base font-medium text-gray-700 mb-2">
                            Cari Mahasiswa
                        </label>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama / NPM / judul KP…"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-3 md:px-4 py-2 md:py-3 text-sm md:text-base transition duration-200"
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm md:text-base font-medium text-gray-700 mb-2">
                            Status
                        </label>
                        <select
                            name="status"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-3 md:px-4 py-2 md:py-3 text-sm md:text-base transition duration-200"
                        >
                            <option value="">Semua Status</option>
                            @foreach(['pengajuan','disetujui','sedang_kp','selesai','ditolak'] as $st)
                                <option value="{{ $st }}" @selected(request('status')===$st)>
                                    {{ ucfirst(str_replace('_',' ',$st)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex items-end gap-2 md:gap-3">
                        <button
                            class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-4 md:px-6 py-2 md:py-3 rounded-lg text-sm md:text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center flex-1"
                        >
                            <i class="fas fa-search mr-1 md:mr-2"></i>Filter
                        </button>
                        @if(request()->filled('search') || request()->filled('status'))
                            <a href="{{ request()->url() }}"
                               class="bg-gray-500 hover:bg-gray-600 text-white px-4 md:px-6 py-2 md:py-3 rounded-lg text-sm md:text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center flex-1">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Daftar Mahasiswa Table --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                {{-- Table header with UNIB gradient --}}
                <div class="px-4 md:px-6 py-3 md:py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex flex-col sm:flex-row items-start sm:items-center justify-between min-h-[70px] gap-2">
                    <h3 class="text-lg md:text-xl font-bold">
                        Daftar Mahasiswa
                    </h3>
                    <div class="inline-flex items-center px-3 md:px-4 py-1 md:py-2 rounded-full text-sm md:text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                        Total: {{ $kp->total() }}
                    </div>
                </div>

                {{-- Scrollable table body --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 table-auto">
                        <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                            <tr>
                                <th class="px-3 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    Mahasiswa
                                </th>
                                <th class="px-3 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    NPM
                                </th>
                                <th class="px-3 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-unib-blue-800 uppercase tracking-wider">
                                    Judul KP
                                </th>
                                <th class="px-3 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    Status
                                </th>
                                <th class="px-3 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    Tanggal Mulai
                                </th>
                                <th class="px-3 md:px-6 py-3 text-center text-xs md:text-sm font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($kp as $item)
                                <tr class="hover:bg-unib-blue-50 transition-colors duration-200">
                                    <td class="px-3 md:px-6 py-3 md:py-4 whitespace-nowrap">
                                        <div class="font-semibold text-gray-900 text-sm md:text-base">
                                            {{ optional($item->mahasiswa)->name ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-3 md:px-6 py-3 md:py-4 whitespace-nowrap">
                                        <div class="text-gray-900 text-sm md:text-base">
                                            {{ optional($item->mahasiswa)->npm ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-3 md:px-6 py-3 md:py-4">
                                        <span class="text-gray-900 text-sm md:text-base break-words max-w-[200px] md:max-w-[300px] inline-block">
                                            {{ $item->judul_kp ?? '-' }}
                                            @if($item->judul_kp && strlen($item->judul_kp) > 100)
                                                <button onclick="showFullDescription('{{ $item->judul_kp }}')" 
                                                        class="text-unib-blue-600 hover:text-unib-blue-800 text-xs md:text-sm ml-1">
                                                    selengkapnya
                                                </button>
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-3 md:px-6 py-3 md:py-4 whitespace-nowrap">
                                        @php
                                            $displayStatus = $item->status ?? 'pengajuan';
                                            if ($item->status === 'sedang_kp' && $item->nilai_akhir && $item->file_laporan) {
                                                $displayStatus = 'selesai';
                                            }
                                        @endphp
                                        @if($displayStatus === \App\Models\KerjaPraktek::STATUS_DISETUJUI || $displayStatus === 'disetujui')
                                            <span class="inline-flex items-center px-2 md:px-3 py-1 rounded-full text-xs md:text-sm font-medium bg-blue-100 text-blue-800 border border-blue-300">
                                                <i class="fas fa-check-circle mr-1"></i> Disetujui
                                            </span>
                                        @elseif($displayStatus === \App\Models\KerjaPraktek::STATUS_DITOLAK || $displayStatus === 'ditolak')
                                            <span class="inline-flex items-center px-2 md:px-3 py-1 rounded-full text-xs md:text-sm font-medium bg-red-100 text-red-800 border border-red-300">
                                                <i class="fas fa-times-circle mr-1"></i> Ditolak
                                            </span>
                                        @elseif($displayStatus === \App\Models\KerjaPraktek::STATUS_SEDANG_KP || $displayStatus === 'sedang_kp')
                                            <span class="inline-flex items-center px-2 md:px-3 py-1 rounded-full text-xs md:text-sm font-medium bg-emerald-100 text-emerald-800 border border-emerald-300 whitespace-nowrap">
                                                <i class="fas fa-play-circle mr-1"></i> Sedang KP
                                            </span>
                                        @elseif($displayStatus === \App\Models\KerjaPraktek::STATUS_SELESAI || $displayStatus === 'selesai')
                                            <span class="inline-flex items-center px-2 md:px-3 py-1 rounded-full text-xs md:text-sm font-medium bg-gray-100 text-gray-800 border border-gray-300">
                                                <i class="fas fa-flag-checkered mr-1"></i> Selesai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 md:px-3 py-1 rounded-full text-xs md:text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-300">
                                                <i class="fas fa-clock mr-1"></i> Pengajuan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 md:px-6 py-3 md:py-4 whitespace-nowrap">
                                        <span class="text-gray-900 text-sm md:text-base">
                                            {{ $item->created_at ? $item->created_at->locale('id')->translatedFormat('d F Y') : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-3 md:px-6 py-3 md:py-4 text-center whitespace-nowrap">
                                        <div class="flex flex-col sm:flex-row items-center justify-center gap-1 md:gap-3">
                                            <a href="{{ route('pengawas.mahasiswa.show', $item) }}"
                                               class="text-unib-blue-600 hover:text-unib-blue-800 whitespace-nowrap transition-colors duration-200 text-xs md:text-sm">
                                                <i class="fas fa-eye mr-1"></i> Detail
                                            </a>

                                            @if($item->file_kartu_implementasi && !$item->acc_pembimbing_lapangan)
                                                <form method="POST" action="{{ route('pengawas.mahasiswa.acc-kartu', $item) }}">
                                                    @csrf
                                                    <button
                                                        onclick="return confirm('ACC kartu implementasi?')"
                                                        class="text-green-600 hover:text-green-800 transition-colors duration-200 text-xs md:text-sm"
                                                    >
                                                        <i class="fas fa-check mr-1"></i> ACC Kartu
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8 md:py-16 text-gray-500 animate-fade-in-up">
                                        <div class="flex flex-col items-center justify-center">
                                            <!-- DotLottie Animation -->
                                            <div class="mb-4 md:mb-6 lottie-container">
                                                <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>
                                                <dotlottie-wc 
                                                    src="https://lottie.host/f4be40d9-971a-446d-9d51-ed1128f637ef/8YGejDufWD.lottie" 
                                                    style="width: 200px; height: 200px;" 
                                                    autoplay 
                                                    loop>
                                                </dotlottie-wc>
                                            </div>
                                            <div class="text-base md:text-lg font-medium text-gray-900 mb-2">Tidak Ada Mahasiswa</div>
                                            <p class="text-sm md:text-base text-gray-600 max-w-md mx-auto px-4">
                                                Belum ada mahasiswa yang tercatat untuk tempat ini.
                                            </p>
                                            @if(request()->filled('search') || request()->filled('status'))
                                                <button onclick="window.location.href='{{ request()->url() }}'" 
                                                        class="mt-4 md:mt-6 bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-4 md:px-6 py-2 md:py-3 rounded-lg text-sm md:text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center">
                                                    Reset Filter
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="p-4 md:p-6 border-t bg-unib-blue-50">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-xs md:text-sm text-unib-blue-700">
                            Menampilkan {{ $kp->firstItem() }} - {{ $kp->lastItem() }} dari {{ $kp->total() }} hasil
                        </p>
                        <div class="flex space-x-1 overflow-x-auto">
                            {{ $kp->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal untuk deskripsi lengkap --}}
    <div id="descriptionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 border border-unib-blue-200 max-h-[80vh] overflow-hidden">
            <div class="p-4 md:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base md:text-lg font-bold text-gray-900">Judul KP Lengkap</h3>
                    <button onclick="closeDescriptionModal()" class="text-gray-400 hover:text-gray-600 text-xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mb-4 md:mb-6">
                    <p id="fullDescription" class="text-gray-700 text-sm md:text-base leading-relaxed max-h-60 overflow-y-auto"></p>
                </div>
                <div class="flex justify-end">
                    <button onclick="closeDescriptionModal()" 
                            class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-3 md:px-4 py-2 rounded-lg text-xs md:text-sm font-medium shadow-md transition duration-200">
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
        
        /* Table auto layout */
        .table-auto {
            width: 100%;
            table-layout: auto;
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
        
        /* Responsive design */
        @media (max-width: 640px) {
            .grid-cols-1 {
                grid-template-columns: 1fr;
            }
            
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
                overflow-x: auto;
            }
            
            .lottie-container dotlottie-wc {
                width: 150px !important;
                height: 150px !important;
            }
            
            /* Responsive table adjustments */
            table {
                font-size: 0.75rem;
            }
            
            .flex.items-end.gap-3 {
                flex-direction: column;
            }
            
            .flex.items-end.gap-3 > * {
                width: 100%;
            }
        }
        
        @media (max-width: 480px) {
            .px-3 {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            
            .py-3 {
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
            }
            
            .lottie-container dotlottie-wc {
                width: 120px !important;
                height: 120px !important;
            }
        }
        
        /* Custom breakpoint for very small screens */
        @media (max-width: 360px) {
            .text-xs {
                font-size: 0.7rem;
            }
            
            .px-2 {
                padding-left: 0.4rem;
                padding-right: 0.4rem;
            }
            
            .py-1 {
                padding-top: 0.25rem;
                padding-bottom: 0.25rem;
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