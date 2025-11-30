{{-- resources/views/superadmin/laporan/detail-kp.blade.php --}}
<x-sidebar-layout>
 <x-slot name="header">
    <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
        <div class="flex items-center space-x-3">
            <a href="{{ route('superadmin.laporan.index') }}" 
               class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-base font-medium transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
           
            <div>
                <h2 class="font-bold text-xl leading-tight">
                    Daftar Kerja Praktek {{ $status ? '— '.strtoupper(str_replace('_',' ',$status)) : '' }}
                </h2>
            </div>
        </div>
    </div>
</x-slot>

    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Filter Section --}}
            <div class="bg-white rounded-xl shadow-lg p-6 border border-unib-blue-100">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                        <input name="search" value="{{ request('search') }}" placeholder="Cari judul / nama / NPM…"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dari</label>
                        <input type="text" name="start_date" value="{{ request('start_date') }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200 datepicker">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sampai</label>
                        <input type="text" name="end_date" value="{{ request('end_date') }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200 datepicker">
                    </div>
                    <div class="flex items-end gap-3">
                        <button class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center flex-1">
                            Cari
                        </button>
                        <a href="{{ route('superadmin.laporan.detail-kp', ['status' => 'selesai']) }}" class="bg-teknik-orange-500 hover:bg-teknik-orange-600 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center flex-1">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Table Section --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-unib-blue-100">
                {{-- Table Header --}}
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Data Kerja Praktek
                    </h3>
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                        Total: {{ $kp->total() }}
                    </div>
                </div>

                {{-- Table Content --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">Mahasiswa</th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">Judul KP</th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">Tempat</th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">Nilai</th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">Grade</th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($kp as $row)
                                <tr class="hover:bg-unib-blue-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900 text-base">{{ $row->mahasiswa->name ?? '-' }}</div>
                                        <div class="text-sm text-gray-500">{{ $row->mahasiswa->npm ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-900 text-base">{{ $row->judul_kp }}</td>
                                    <td class="px-6 py-4 text-gray-900 text-base">{{ $row->tempatMagang->nama_perusahaan ?? $row->tempat_magang_sendiri ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-unib-blue-100 text-unib-blue-800 border border-unib-blue-300 shadow-sm capitalize">
                                            {{ $row->display_status === 'tidak_lulus' ? 'Tidak Lulus' : str_replace('_',' ',$row->display_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-900 text-base">{{ $row->nilai_akhir ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            @if($row->grade === 'A') bg-green-100 text-green-800 border border-green-300
                                            @elseif($row->grade === 'B') bg-blue-100 text-blue-800 border border-blue-300
                                            @elseif($row->grade === 'C') bg-yellow-100 text-yellow-800 border border-yellow-300
                                            @elseif($row->grade === 'D') bg-orange-100 text-orange-800 border border-orange-300
                                            @elseif($row->grade === 'E') bg-red-100 text-red-800 border border-red-300
                                            @else bg-gray-100 text-gray-800 border border-gray-300
                                            @endif shadow-sm">
                                            {{ $row->grade }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-900 text-base">{{ $row->created_at->locale('id')->translatedFormat('d F Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-12">
                                        <div class="flex flex-col items-center justify-center">
                                            <dotlottie-wc 
                                                src="https://lottie.host/f4be40d9-971a-446d-9d51-ed1128f637ef/8YGejDufWD.lottie" 
                                                style="width: 300px; height: 300px;" 
                                                autoplay 
                                                loop>
                                            </dotlottie-wc>
                                            <div class="text-base font-medium text-gray-900 mb-2 mt-4">Tidak Ada Data</div>
                                            <p class="text-sm text-gray-600">Tidak ada data kerja praktek yang ditemukan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="p-6 border-t bg-unib-blue-50">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-unib-blue-700">
                            Menampilkan {{ $kp->firstItem() }} - {{ $kp->lastItem() }} dari {{ $kp->total() }} hasil
                        </p>
                        <div class="flex space-x-1">
                            {{ $kp->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

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
            altFormat: 'd F Y'
        });
    });
    </script>
</x-sidebar-layout>