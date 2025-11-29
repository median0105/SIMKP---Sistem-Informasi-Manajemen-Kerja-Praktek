
{{-- resources/views/admin/seminar/index.blade.php --}}
<x-sidebar-layout>
    {{-- Header section with UNIB blue gradient --}}
   <x-slot name="header">
    <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
        <div class="flex items-center space-x-3">
            <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                <!-- Ikon dihapus sesuai pattern -->
            </div>
            <div>
                <h2 class="font-bold text-xl leading-tight">
                    Daftar Mahasiswa Seminar
                </h2>
               
            </div>
        </div>
    </div>
</x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Flash messages --}}
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

            {{-- Filter & Search --}}
            <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="md:col-span-2">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari berdasarkan nama, NPM, atau email..."
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                    </div>
                    
                    <div>
                        <select name="status_kp"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                            <option value="">Semua Status KP</option>
                            <option value="pengajuan"  {{ request('status_kp')==='pengajuan'  ? 'selected' : '' }}>Pengajuan</option>
                            <option value="disetujui"  {{ request('status_kp')==='disetujui'  ? 'selected' : '' }}>Disetujui</option>
                            <option value="sedang_kp"  {{ request('status_kp')==='sedang_kp'  ? 'selected' : '' }}>Sedang KP</option>
                            <option value="selesai"    {{ request('status_kp')==='selesai'    ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak"    {{ request('status_kp')==='ditolak'    ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end gap-3">
                        <button type="submit" 
                                class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center flex-1">
                            Cari
                        </button>
                        @if(request()->filled('search') || request()->filled('status_kp'))
                            <a href="{{ route('admin.seminar.index') }}"
                               class="bg-teknik-orange-500 hover:bg-teknik-orange-600 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center flex-1">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Tabel Mahasiswa Seminar --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                {{-- Table header with UNIB gradient --}}
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Tabel Mahasiswa Seminar
                    </h3>
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                        Total: {{ $mahasiswa->total() }}
                    </div>
                </div>

                @if($mahasiswa->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        NPM
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Nama
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Judul KP
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Status KP
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Tempat
                                    </th>
                                    <th class="px-6 py-4 text-center text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($mahasiswa as $mhs)
                                    @php
                                        $kp = $mhs->kpTerbaru;
                                        $status = $kp->status ?? null;
                                        
                                        // Status mapping dengan warna UNIB
                                        $statusMap = [
                                            'pengajuan' => ['bg-yellow-100 text-yellow-800 border-yellow-300', 'Pengajuan'],
                                            'disetujui' => ['bg-unib-blue-100 text-unib-blue-800 border-unib-blue-300', 'Disetujui'],
                                            'sedang_kp' => ['bg-teknik-orange-100 text-teknik-orange-800 border-teknik-orange-300', 'Sedang KP'],
                                            'selesai'   => ['bg-green-100 text-green-800 border-green-300', 'Selesai'],
                                            'ditolak'   => ['bg-red-100 text-red-800 border-red-300', 'Ditolak'],
                                        ];
                                        [$statusClass, $statusLabel] = $statusMap[$status] ?? ['bg-gray-100 text-gray-800 border-gray-300', 'Tidak Diketahui'];
                                    @endphp

                                    <tr class="hover:bg-unib-blue-50 transition">
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-900 text-base">
                                                {{ $mhs->npm ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-900 text-base">
                                                {{ $mhs->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 mt-1">
                                                {{ $mhs->email }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-900 text-base">
                                                {{ $kp->judul_kp ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($status)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }} border shadow-sm">
                                                    {{ $statusLabel }}
                                                </span>
                                            @else
                                                <span class="text-gray-500">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-900 text-base">
                                                {{ $kp?->tempatMagang?->nama_perusahaan ?? ($kp->tempat_magang_sendiri ?? '-') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center gap-2 justify-center">
                                                @if($kp)
                                                    <a href="{{ route('admin.seminar.show', $kp) }}"
                                                       class="text-unib-blue-600 hover:text-unib-blue-800 text-base font-medium flex items-center px-3 py-2 rounded-lg hover:bg-unib-blue-100 transition duration-200">
                                                        Detail
                                                    </a>
                                                @else
                                                    <span class="text-gray-400 text-base font-medium flex items-center px-3 py-2">
                                                        Detail
                                                    </span>
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
                    {{-- Empty state dengan animasi Lottie --}}
                    <div class="text-center py-16 text-gray-500">
                        <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>
                        <dotlottie-wc src="https://lottie.host/f4be40d9-971a-446d-9d51-ed1128f637ef/8YGejDufWD.lottie" style="width: 300px;height: 300px; margin: 0 auto;" autoplay loop></dotlottie-wc>
                        <div class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Mahasiswa</div>
                        <p class="text-base text-gray-600 max-w-md mx-auto">
                            Data mahasiswa tidak ditemukan untuk filter saat ini. 
                            Coba ubah kriteria pencarian atau reset filter.
                        </p>
                        @if(request()->filled('search') || request()->filled('status_kp'))
                            <button onclick="window.location.href='{{ route('admin.seminar.index') }}'" 
                                    class="mt-6 bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center">
                                Reset Filter
                            </button>
                        @endif
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
    </style>
</x-sidebar-layout>