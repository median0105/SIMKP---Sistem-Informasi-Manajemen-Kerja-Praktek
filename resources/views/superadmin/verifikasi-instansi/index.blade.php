<x-sidebar-layout>
    {{-- Header section with UNIB blue background --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Verifikasi Instansi Mandiri
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
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

            {{-- Filter form --}}
            <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-6">
                    {{-- Search Input --}}
                    <div class="md:col-span-2">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama mahasiswa, NPM, nama perusahaan..." 
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                    </div>
                    
                    {{-- Status Filter --}}
                    <div>
                        <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                            <option value="">Semua Status</option>
                            <option value="pengajuan" {{ $status === 'pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                            <option value="disetujui" {{ $status === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="ditolak" {{ $status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    {{-- Verifikasi Filter --}}
                    <div>
                        <select name="instansi_verified" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                            <option value="">Semua Verifikasi</option>
                            <option value="1" {{ request('instansi_verified') === '1' ? 'selected' : '' }}>Sudah Diverifikasi</option>
                            <option value="0" {{ request('instansi_verified') === '0' ? 'selected' : '' }}>Belum Diverifikasi</option>
                        </select>
                    </div>

                    {{-- Action Buttons --}}
                    
                    <div class="flex items-end gap-3">
                        <button type="submit"
                                class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center flex-1">
                            Cari
                        </button>
                        <a href="{{ route('superadmin.verifikasi-instansi.index') }}" class="bg-teknik-orange-500 hover:bg-teknik-orange-600 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center flex-1">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
 
            {{-- Table container --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                {{-- Table header with UNIB blue gradient --}}
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Data Instansi Mandiri
                    </h3>
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                        <i class="fas fa-building mr-2"></i>
                        Total: {{ $kerjaPrakteks->total() }}
                    </div>
                </div>

                {{-- Scrollable table body --}}
               <div class="overflow-x-auto table-responsive">
                    <table class="w-full divide-y divide-gray-200">
                        {{-- Table header --}}
                        <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider">Mahasiswa</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider">Perusahaan</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider">Bidang Usaha</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider">Kontak</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider">Status KP</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider">Verifikasi</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-unib-blue-800 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        {{-- Table body --}}
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($kerjaPrakteks as $kp)
                            <tr class="hover:bg-unib-blue-50 transition duration-150">
                                {{-- Mahasiswa --}}
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-gray-900 text-sm">{{ $kp->mahasiswa->name ?? '-' }}</div>
                                    <div class="text-xs text-gray-500 mt-1 flex items-center">
                                        <i class="fas fa-id-card mr-1 text-xs text-unib-blue-400"></i>
                                        NPM: {{ $kp->mahasiswa->npm ?? '-' }}
                                    </div>
                                </td>
                                
                                {{-- Perusahaan --}}
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-gray-900 text-sm">{{ $kp->tempat_magang_sendiri ?? '-' }}</div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-map-marker-alt mr-1 text-xs text-unib-blue-400"></i>
                                        {{ Str::limit($kp->alamat_tempat_sendiri, 50) ?? '-' }}
                                    </div>
                                </td>
                                
                                {{-- Bidang Usaha --}}
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900">{{ $kp->bidang_usaha_sendiri ?? '-' }}</div>
                                </td>
                                
                                {{-- Kontak --}}
                                <td class="px-4 py-3">
                                    <div class="space-y-1">
                                        @if($kp->email_perusahaan_sendiri)
                                        <div class="text-xs text-gray-600 flex items-center">
                                            <i class="fas fa-envelope mr-1 text-xs text-unib-blue-400"></i>
                                            {{ $kp->email_perusahaan_sendiri }}
                                        </div>
                                        @endif
                                        @if($kp->telepon_perusahaan_sendiri)
                                        <div class="text-xs text-gray-600 flex items-center">
                                            <i class="fas fa-phone mr-1 text-xs text-unib-blue-400"></i>
                                            {{ $kp->telepon_perusahaan_sendiri }}
                                        </div>
                                        @endif
                                        @if($kp->kontak_tempat_sendiri)
                                        <div class="text-xs text-gray-600 flex items-center">
                                            <i class="fas fa-user mr-1 text-xs text-unib-blue-400"></i>
                                            {{ $kp->kontak_tempat_sendiri }}
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                
                                {{-- Status KP --}}
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                        @if($kp->status === 'pengajuan') bg-yellow-100 text-yellow-800 border border-yellow-300
                                        @elseif($kp->status === 'disetujui') bg-green-100 text-green-800 border border-green-300
                                        @elseif($kp->status === 'ditolak') bg-red-100 text-red-800 border border-red-300
                                        @else bg-gray-100 text-gray-800 border border-gray-300
                                        @endif shadow-sm">
                                        <i class="fas 
                                            @if($kp->status === 'pengajuan') fa-clock
                                            @elseif($kp->status === 'disetujui') fa-check
                                            @elseif($kp->status === 'ditolak') fa-times
                                            @else fa-question
                                            @endif mr-1 text-xs"></i>
                                        {{ ucfirst($kp->status) }}
                                    </span>
                                </td>
                                
                                {{-- Verifikasi Instansi --}}
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                        @if($kp->instansi_verified) bg-green-100 text-green-800 border border-green-300
                                        @else bg-yellow-100 text-yellow-800 border border-yellow-300
                                        @endif shadow-sm">
                                        <i class="fas 
                                            @if($kp->instansi_verified) fa-check-circle
                                            @else fa-clock
                                            @endif mr-1 text-xs"></i>
                                        {{ $kp->instansi_verified ? 'Diverifikasi' : 'Belum Diverifikasi' }}
                                    </span>
                                </td>
                                
                                {{-- Tanggal --}}
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900">{{ $kp->created_at->locale('id')->translatedFormat('d M Y') }}</div>
                                </td>
                                
                                {{-- Aksi --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-1">
                                        @if(!$kp->instansi_verified)
                                        <form method="POST" action="{{ route('superadmin.verifikasi-instansi.approve', $kp) }}" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menyetujui instansi ini?')" class="flex">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-800 text-xs font-medium flex items-center px-2 py-1 rounded hover:bg-green-50 transition duration-200 border border-green-300" title="Verifikasi">
                                                <i class="fas fa-check mr-1"></i>Verifikasi
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('superadmin.verifikasi-instansi.reject', $kp) }}" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menolak instansi ini?')" class="flex">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium flex items-center px-2 py-1 rounded hover:bg-red-50 transition duration-200 border border-red-300" title="Tolak">
                                                <i class="fas fa-times mr-1"></i>Tolak
                                            </button>
                                        </form>
                                        @else
                                        <span class="text-green-600 text-xs font-medium flex items-center px-2 py-1 rounded bg-green-50 border border-green-200">
                                            <i class="fas fa-check-circle mr-1"></i>Sudah Diverifikasi
                                        </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-12 text-gray-500">
                                    <div class="flex flex-col items-center justify-center animate-fade-in-up">
                                        <!-- Animasi Lottie yang diperbarui -->
                                        <dotlottie-wc 
                                            src="https://lottie.host/f4be40d9-971a-446d-9d51-ed1128f637ef/8YGejDufWD.lottie" 
                                            style="width: 300px;height: 300px" 
                                            autoplay 
                                            loop>
                                        </dotlottie-wc>
                                        <div class="text-base font-medium text-gray-900 mb-2 mt-4">Tidak Ada Data</div>
                                        <p class="text-sm text-gray-600 max-w-md text-center">
                                            Tidak ada data instansi mandiri ditemukan. 
                                            <br>Coba ubah filter pencarian atau reset filter untuk melihat semua data.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="p-6 border-t bg-unib-blue-50">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
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

    /* ---------------------------
       RESPONSIVE TABLE PERBAIKAN
       --------------------------- */
    @media (max-width: 1024px) {
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            min-width: 850px; /* Biar tidak terlalu mepet */
        }
    }

    @media (max-width: 768px) {
        .filter-form {
            grid-template-columns: 1fr;
        }

        table {
            min-width: 900px;
        }
    }

    @media (max-width: 640px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 12px;
        }

        .pagination-container {
            flex-direction: column;
            gap: 12px;
            text-align: center;
        }
    }
</style>

</x-sidebar-layout>