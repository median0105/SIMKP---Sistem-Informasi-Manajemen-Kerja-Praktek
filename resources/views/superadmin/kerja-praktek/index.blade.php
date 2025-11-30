<x-sidebar-layout>
    {{-- HEADER dengan UNIB blue background --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Data Kerja Praktek
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- ALERT DUPLIKAT --}}
            @if(isset($duplicateTitles) && $duplicateTitles->count() > 0)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg shadow-md p-6 mb-6 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mt-1 mr-4"></i>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-yellow-800 mb-2">Peringatan: Judul Duplikat Ditemukan</h3>
                            <div class="text-base text-yellow-700">
                                <p class="mb-3">{{ $duplicateTitles->count() }} judul KP terdeteksi duplikat dari total {{ $kerjaPrakteks->total() }} data KP ({{ $duplicatePercentage }}% duplikat).</p>
                                <ul class="list-disc list-inside space-y-1 bg-yellow-100 p-4 rounded-lg">
                                    @foreach($duplicateTitles as $title)
                                        <li class="font-medium">{{ $title }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Flash messages for success and error --}}
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

            {{-- FILTER --}}
            <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-6">
                    <div class="md:col-span-2">
                       
                        <input type="text" name="search"
                               value="{{ $search }}"
                               placeholder="Cari nama, NPM, judul KP, atau tempat magang..."
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                    </div>
                    
                    <div>
                    
                        <select name="tempat_magang_id"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                            <option value="">Semua Instansi</option>
                            @foreach($tempatMagang as $tm)
                                <option value="{{ $tm->id }}" {{ $tempat_magang_id == $tm->id ? 'selected' : '' }}>
                                    {{ $tm->nama_perusahaan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                   
                        <select name="status"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                            <option value="">Semua Status</option>
                            <option value="pengajuan" {{ $status === 'pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                            <option value="disetujui" {{ $status === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="ditolak" {{ $status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            <option value="berjalan" {{ $status === 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                            <option value="selesai" {{ $status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-3">
                        <button type="submit"
                                class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center flex-1">
                            Cari
                        </button>
                        <a href="{{ route('superadmin.kerja-praktek.index') }}" class="bg-teknik-orange-500 hover:bg-teknik-orange-600 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center flex-1">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- TABLE --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 animate-fade-in-up">
                {{-- Table header dengan UNIB gradient --}}
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Daftar Kerja Praktek
                    </h3>
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                        Total: {{ $kerjaPrakteks->total() }}
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200 min-w-[1200px]">
                        <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap min-w-[150px]">
                                    Mahasiswa
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap min-w-[200px]">
                                    Judul KP
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap min-w-[180px]">
                                    Duplikat
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap min-w-[150px]">
                                    Tempat Magang
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap min-w-[180px]">
                                    Dosen Pembimbing
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap min-w-[180px]">
                                    Dosen Penguji
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap min-w-[120px]">
                                    Status
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap min-w-[140px]">
                                    Tanggal Dibuat
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap min-w-[120px]">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($kerjaPrakteks as $kp)
                                <tr class="hover:bg-unib-blue-50 transition duration-150 group">
                                    <td data-label="Mahasiswa" class="px-4 py-3 whitespace-nowrap min-w-[150px]">
                                        <div class="font-semibold text-gray-900 text-sm group-hover:text-unib-blue-700 transition-colors">
                                            {{ $kp->mahasiswa->name }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $kp->mahasiswa->npm }}
                                        </div>
                                    </td>
                                    <td data-label="Judul KP" class="px-4 py-3">
                                        <span class="text-gray-900 text-sm group-hover:text-unib-blue-700 transition-colors">
                                            {{ \Illuminate\Support\Str::limit($kp->judul_kp, 60) }}
                                        </span>
                                    </td>
                                    <td data-label="Duplikat" class="px-4 py-3">
                                        @if($kp->duplicate_info && count($kp->duplicate_info) > 0)
                                            <div class="space-y-1">
                                                @foreach($kp->duplicate_info as $duplicate)
                                                    <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-300 shadow-sm whitespace-nowrap">
                                                        {{ $duplicate['similarity'] }}% - {{ \Illuminate\Support\Str::limit($duplicate['mahasiswa'], 20) }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-300 shadow-sm whitespace-nowrap">
                                                Tidak ada duplikasi
                                            </div>
                                        @endif
                                    </td>
                                    <td data-label="Tempat Magang" class="px-4 py-3">
                                        <span class="text-gray-900 text-sm group-hover:text-unib-blue-700 transition-colors">
                                            {{ $kp->pilihan_tempat == 3 ? ($kp->tempat_magang_sendiri ?? '-') : ($kp->tempatMagang->nama_perusahaan ?? '-') }}
                                        </span>
                                    </td>
                                    <td data-label="Dosen Pembimbing" class="px-4 py-3">
                                        @php
                                            $pembimbing = $kp->dosenPembimbing->where('jenis_pembimbingan','akademik')->where('is_active', true)->first();
                                        @endphp
                                        @if($pembimbing && $pembimbing->dosen)
                                            <span class="text-gray-900 text-sm group-hover:text-unib-blue-700 transition-colors">
                                                {{ $pembimbing->dosen->name }}
                                            </span>
                                        @else
                                            <form method="POST" action="{{ route('superadmin.kerja-praktek.assign-dosen-pembimbing', $kp) }}" class="flex items-center gap-1">
                                                @csrf
                                                <select name="dosen_id" class="text-xs border-gray-300 rounded px-2 py-1 focus:border-unib-blue-500 focus:ring-unib-blue-500 w-full max-w-[140px]">
                                                    <option value="">Pilih Dosen</option>
                                                    @foreach($dosen as $d)
                                                        <option value="{{ $d->id }}">{{ \Illuminate\Support\Str::limit($d->name, 20) }}</option>
                                                    @endforeach
                                                </select>
                                                <button class="text-unib-blue-600 hover:text-unib-blue-800 text-xs transition duration-200 whitespace-nowrap ml-1 px-2 py-1 bg-unib-blue-50 rounded">
                                                    Tambah
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                    <td data-label="Dosen Penguji" class="px-4 py-3">
                                        @php
                                            $penguji = $kp->dosenPenguji->where('is_active', true)->first();
                                        @endphp
                                        @if($penguji && $penguji->dosen)
                                            <span class="text-gray-900 text-sm group-hover:text-unib-blue-700 transition-colors">
                                                {{ $penguji->dosen->name }}
                                            </span>
                                        @else
                                            <form method="POST" action="{{ route('superadmin.kerja-praktek.assign-dosen-penguji', $kp) }}" class="flex items-center gap-1">
                                                @csrf
                                                <select name="dosen_id" class="text-xs border-gray-300 rounded px-2 py-1 focus:border-unib-blue-500 focus:ring-unib-blue-500 w-full max-w-[140px]">
                                                    <option value="">Pilih Dosen</option>
                                                    @foreach($dosen as $d)
                                                        <option value="{{ $d->id }}">{{ \Illuminate\Support\Str::limit($d->name, 20) }}</option>
                                                    @endforeach
                                                </select>
                                                <button class="text-unib-blue-600 hover:text-unib-blue-800 text-xs transition duration-200 whitespace-nowrap ml-1 px-2 py-1 bg-unib-blue-50 rounded">
                                                    Tambah
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                    <td data-label="Status" class="px-4 py-3">
                                        @php
                                            $statusMap = [
                                                'pengajuan' => ['bg-yellow-100 text-yellow-800 border-yellow-300'],
                                                'disetujui' => ['bg-unib-blue-100 text-unib-blue-800 border-unib-blue-300'],
                                                'ditolak' => ['bg-red-100 text-red-800 border-red-300'],
                                                'berjalan' => ['bg-teknik-orange-100 text-teknik-orange-800 border-teknik-orange-300'],
                                                'selesai' => ['bg-green-100 text-green-800 border-green-300'],
                                                'tidak_lulus' => ['bg-red-100 text-red-800 border-red-300'],
                                            ];
                                            $statusKey = $kp->display_status;
                                            [$statusClass] = $statusMap[$statusKey] ?? ['bg-gray-100 text-gray-800 border-gray-300'];
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusClass }} border shadow-sm whitespace-nowrap">
                                            {{ $statusKey === 'tidak_lulus' ? 'Tidak Lulus' : ucfirst($statusKey) }}
                                        </span>
                                    </td>
                                    <td data-label="Tanggal Dibuat" class="px-4 py-3">
                                        <span class="text-gray-900 text-sm group-hover:text-unib-blue-700 transition-colors">
                                            {{ $kp->created_at->locale('id')->translatedFormat('d F Y') }}
                                        </span>
                                    </td>
                                    <td data-label="Aksi" class="px-4 py-3">
                                        <div class="flex flex-col gap-1">
                                            @if($kp->status === 'ditolak')
                                                <form method="POST" action="{{ route('superadmin.users.destroy-kp', [$kp->mahasiswa, $kp]) }}" onsubmit="return confirm('Hapus data KP ditolak ini?')" class="w-full">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center justify-center px-2 py-1 rounded hover:bg-red-100 transition duration-200 whitespace-nowrap w-full"
                                                            title="Hapus KP Ditolak">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @elseif($kp->status === 'pengajuan')
                                                <a href="{{ route('superadmin.kerja-praktek.edit', $kp) }}" 
                                                   class="text-unib-blue-600 hover:text-unib-blue-800 text-sm font-medium flex items-center justify-center px-2 py-1 rounded hover:bg-unib-blue-100 transition duration-200 whitespace-nowrap"
                                                   title="Edit KP">
                                                    Edit
                                                </a>
                                                <a href="{{ route('superadmin.users.show', $kp->mahasiswa) }}" 
                                                   class="text-green-600 hover:text-green-800 text-sm font-medium flex items-center justify-center px-2 py-1 rounded hover:bg-green-100 transition duration-200 whitespace-nowrap"
                                                   title="Detail Mahasiswa">
                                                    Detail
                                                </a>
                                            @else
                                                <a href="{{ route('superadmin.users.show', $kp->mahasiswa) }}" 
                                                   class="text-unib-blue-600 hover:text-unib-blue-800 text-sm font-medium flex items-center justify-center px-2 py-1 rounded hover:bg-unib-blue-100 transition duration-200 whitespace-nowrap"
                                                   title="Detail Mahasiswa">
                                                    Detail
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-16 text-gray-500 animate-fade-in-up">
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
                                            <div class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Data Kerja Praktek</div>
                                            <p class="text-base text-gray-600 max-w-md mx-auto">
                                                Data kerja praktek tidak ditemukan untuk filter saat ini.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination dengan style UNIB --}}
                <div class="p-6 border-t bg-unib-blue-50">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-unib-blue-700">
                            Menampilkan {{ $kerjaPrakteks->firstItem() }} - {{ $kerjaPrakteks->lastItem() }} dari {{ $kerjaPrakteks->total() }} hasil
                        </p>
                        <div class="flex space-x-1">
                            {{ $kerjaPrakteks->appends(request()->query())->links() }}
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
        
        /* Hover effects for table rows - tanpa transform */
        .table-row-hover {
            transition: all 0.2s ease-in-out;
        }
        
        /* Style untuk dotlottie container */
        .lottie-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
        }
        
        /* Perbaikan untuk tabel agar responsive dengan scroll */
        .overflow-x-auto {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        /* Menghilangkan fixed layout dan menggunakan auto dengan min-width */
        table {
            table-layout: auto;
            width: auto;
            min-width: 100%;
        }
        
        th, td {
            vertical-align: middle;
            white-space: nowrap;
        }
        
        /* Memberikan ruang yang cukup untuk konten */
        th, td {
            min-width: 120px;
        }
        
        /* Scrollbar styling */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        /* ====== RESPONSIVE FIX TANPA MERUBAH TAMPILAN ASLI ====== */

/* Hilangkan scroll horizontal */

/* Untuk layar kecil, table dibuat fleksibel */
@media (max-width: 1024px) {

    /* Bungkus tabel agar bisa shrink tanpa scroll */
    table {
        width: 100% !important;
        min-width: auto !important;
        display: block;
    }

    thead {
        display: none; /* hide header di mobile */
    }

    tbody tr {
        display: block;
        margin-bottom: 16px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px;
        background: white;
    }

    tbody td {
        display: flex;
        justify-content: space-between;
        padding: 8px 4px;
        white-space: normal !important;
        min-width: auto !important;
        text-align: left;
        border-bottom: 1px dashed #e5e7eb;
    }

    tbody td:last-child {
        border-bottom: none;
    }

    /* Label otomatis dari data-label */
    tbody td:before {
        content: attr(data-label);
        font-weight: 700;
        color: #1e3a8a;
        margin-right: 12px;
        text-transform: uppercase;
        font-size: 12px;
    }
}

/* RESPONSIVE FILTER FORM */
@media (max-width: 768px) {
    form.grid {
        grid-template-columns: 1fr !important;
    }
}

    </style>

    <!-- DotLottie Web Component Script -->
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>

    <script>
        // Add hover effects to table rows
        document.addEventListener('DOMContentLoaded', function() {
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.classList.add('table-row-hover');
            });
            
            // Pastikan dotlottie-wc terload dengan baik
            if (typeof customElements !== 'undefined') {
                customElements.whenDefined('dotlottie-wc').then(() => {
                    console.log('DotLottie Web Component loaded successfully');
                });
            }
        });
    </script>
</x-sidebar-layout>