

{{-- resources/views/superadmin/kegiatan/index.blade.php --}}
<x-sidebar-layout>
<x-slot name="header">
    <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
        <div class="flex items-center space-x-3">
            <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                
            </div>
            <div>
                <h2 class="font-bold text-xl leading-tight">
                    Kegiatan Mahasiswa
                </h2>
               
            </div>
        </div>
    </div>
</x-slot>

    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-r-lg shadow-md flex items-center">
                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Filter --}}
            <div class="bg-white rounded-xl shadow-lg p-6 border border-unib-blue-100">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Cari
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari deskripsi/mahasiswa/perusahaan…"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Dari
                        </label>
                        <input type="text" name="start_date" value="{{ request('start_date') }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200 datepicker">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Sampai
                        </label>
                        <input type="text" name="end_date" value="{{ request('end_date') }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200 datepicker">
                    </div>
                    <div class="flex items-end gap-3">
                        <button class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center flex-1">
                            Cari
                        </button>
                        <a href="{{ route('superadmin.kegiatan.index') }}" class="bg-teknik-orange-500 hover:bg-teknik-orange-600 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center flex-1">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Tabel --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-unib-blue-100">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Daftar Kegiatan Mahasiswa
                    </h3>
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                        Total: {{ $kegiatan->total() }}
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                Mahasiswa
                            </th>
                            <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                Perusahaan
                            </th>
                            <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                Durasi
                            </th>
                            <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                Deskripsi
                            </th>
                            <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                Dokumentasi
                            </th>
                            <th class="px-6 py-4 text-center text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($kegiatan as $row)
                            <tr class="hover:bg-unib-blue-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $row->tanggal_kegiatan->locale('id')->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-gray-900 font-medium">{{ $row->mahasiswa->name ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $row->mahasiswa->npm ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $row->kerjaPraktek->tempatMagang->nama_perusahaan ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-unib-blue-100 text-unib-blue-800 border border-unib-blue-300 shadow-sm">
                                        {{ $row->durasi_jam }} jam
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ \Illuminate\Support\Str::limit($row->deskripsi_kegiatan, 90) }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($row->file_dokumentasi)
                                        <a href="{{ \Illuminate\Support\Facades\Storage::url($row->file_dokumentasi) }}" target="_blank">
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($row->file_dokumentasi) }}" alt="Bukti" class="w-16 h-16 object-cover rounded-lg cursor-pointer hover:opacity-75 border border-unib-blue-200">
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form method="POST" action="{{ route('superadmin.kegiatan.destroy', $row) }}"
                                          onsubmit="return confirm('Hapus kegiatan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center px-3 py-2 rounded-lg hover:bg-red-50 transition duration-200 justify-center">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
                                        <div class="text-base font-medium text-gray-900 mb-2">Tidak Ada Data</div>
                                        <p class="text-sm text-gray-600">Tidak ada data kegiatan yang ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t bg-unib-blue-50">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-unib-blue-700">
                            Menampilkan {{ $kegiatan->firstItem() }} - {{ $kegiatan->lastItem() }} dari {{ $kegiatan->total() }} hasil
                        </p>
                        <div class="flex space-x-1">
                            {{ $kegiatan->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

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