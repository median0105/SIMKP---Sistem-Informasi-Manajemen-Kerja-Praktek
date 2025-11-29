{{-- resources/views/superadmin/periodes/index.blade.php --}}
<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                
                </div>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Daftar Periode
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-r-lg shadow-md flex items-center">
                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-lg shadow-md flex items-center">
                    <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Action Button --}}
            <div class="bg-white rounded-xl shadow-lg p-6 border border-unib-blue-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Manajemen Periode KP</h3>
                        <p class="text-sm text-gray-500 mt-1">Kelola periode kerja praktik mahasiswa</p>
                    </div>
                    <a href="{{ route('superadmin.periodes.create') }}"
                       class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-semibold shadow-lg transform hover:scale-105 transition duration-200 flex items-center">
                        <i class="fas fa-plus mr-2"></i>Tambah Periode Baru
                    </a>
                </div>
            </div>

            {{-- Tabel Periode --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-unib-blue-100">
                {{-- Table Header --}}
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Data Periode Kerja Praktek
                    </h3>
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                        Total: {{ $periodes->total() }}
                    </div>
                </div>

                {{-- Scrollable Table Container --}}
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">Tahun Akademik</th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">Semester Ke</th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">Jenis Semester</th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">Tanggal Mulai KP</th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">Tanggal Selesai KP</th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">Status Aktif</th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($periodes as $periode)
                                <tr class="hover:bg-unib-blue-50 transition @if($periode->status) bg-green-50 @endif">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-semibold text-gray-900 text-base">{{ $periode->tahun_akademik }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-gray-900 text-base">{{ $periode->semester_ke }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-gray-900 text-base">{{ ucfirst($periode->semester_type) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-gray-900 text-base">{{ $periode->tanggal_mulai_kp->locale('id')->translatedFormat('d F Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-gray-900 text-base">{{ $periode->tanggal_selesai_kp->locale('id')->translatedFormat('d F Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($periode->status)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-300 shadow-sm">
                                                <i class="fas fa-check-circle mr-1"></i>Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 border border-gray-300 shadow-sm">
                                                <i class="fas fa-times-circle mr-1"></i>Tidak Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('superadmin.periodes.edit', $periode) }}" 
                                               class="text-unib-blue-600 hover:text-unib-blue-800 text-sm font-medium flex items-center px-3 py-2 rounded-lg hover:bg-unib-blue-100 transition duration-200"
                                               title="Edit Periode">
                                                Edit
                                            </a>
                                            <form action="{{ route('superadmin.periodes.destroy', $periode) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Yakin ingin menghapus periode {{ $periode->tahun_akademik }} - {{ ucfirst($periode->semester_type) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center px-3 py-2 rounded-lg hover:bg-red-50 transition duration-200 {{ $periode->status ? 'opacity-50 cursor-not-allowed' : '' }}" 
                                                        title="Hapus Periode"
                                                        @if($periode->status) disabled @endif>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-12">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
                                            <div class="text-base font-medium text-gray-900 mb-2">Tidak Ada Data</div>
                                            <p class="text-sm text-gray-600">Belum ada periode kerja praktek.</p>
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
                            Menampilkan {{ $periodes->firstItem() }} - {{ $periodes->lastItem() }} dari {{ $periodes->total() }} hasil
                        </p>
                        <div class="flex space-x-1">
                            {{ $periodes->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-sidebar-layout>