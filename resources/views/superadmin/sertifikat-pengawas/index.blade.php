{{-- resources/views/superadmin/sertifikat-pengawas/index.blade.php --}}
<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Sertifikat Pengawas Lapangan
                </h2>
            </div>
        </div>
    </x-slot>
    
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('superadmin.sertifikat-pengawas.create') }}"
                   class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i>Tambah Sertifikat
                </a>

                {{-- Generate All Button --}}
                <form method="POST" action="{{ route('superadmin.sertifikat-pengawas.generate-all') }}"
                      onsubmit="return confirm('Generate semua sertifikat yang belum digenerate?');">
                    @csrf
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        <i class="fas fa-cogs mr-2"></i>Generate Semua
                    </button>
                </form>
            </div>

            {{-- Flash --}}
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Filter --}}
            <div class="bg-white shadow rounded-lg p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pengawas / nomor sertifikat…"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                    </div>
                    <div>
                        <input type="text" name="tahun_ajaran" value="{{ request('tahun_ajaran') }}" placeholder="Tahun Ajaran"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Cari</button>
                        <a href="{{ route('superadmin.sertifikat-pengawas.index') }}" class="ml-3 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white shadow rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Pengawas</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nomor Sertifikat</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tahun Ajaran</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($sertifikats as $sertifikat)
                            <tr>
                                <td class="px-4 py-2">
                                    <div class="font-medium text-gray-900">{{ $sertifikat->nama_pengawas }}</div>
                                </td>
                                <td class="px-4 py-2">
                                    <span class="text-gray-900">{{ $sertifikat->nomor_sertifikat }}</span>
                                </td>
                                <td class="px-4 py-2">
                                    <span class="text-gray-900">{{ $sertifikat->tahun_ajaran }}</span>
                                </td>
                                <td class="px-4 py-2">
                                    @if($sertifikat->is_generated)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            <i class="fas fa-check-circle mr-1"></i>Generated
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                            <i class="fas fa-clock mr-1"></i>Belum Generated
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        {{-- View --}}
                                        <a href="{{ route('superadmin.sertifikat-pengawas.show', $sertifikat) }}"
                                           class="text-unib-blue-600 hover:text-unib-blue-800 text-sm">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('superadmin.sertifikat-pengawas.edit', $sertifikat) }}"
                                           class="text-unib-blue-600 hover:text-unib-blue-800 text-sm">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a>

                                        {{-- Generate --}}
                                        @if(!$sertifikat->is_generated)
                                            <form method="POST" action="{{ route('superadmin.sertifikat-pengawas.generate', $sertifikat) }}">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-800 text-sm">
                                                    <i class="fas fa-cog mr-1"></i>Generate
                                                </button>
                                            </form>
                                        @else
                                            {{-- Download --}}
                                            <a href="{{ route('superadmin.sertifikat-pengawas.download', $sertifikat) }}"
                                               class="text-blue-600 hover:text-blue-800 text-sm">
                                                <i class="fas fa-download mr-1"></i>Download
                                            </a>
                                        @endif

                                        {{-- Delete --}}
                                        <form method="POST" action="{{ route('superadmin.sertifikat-pengawas.destroy', $sertifikat) }}"
                                              onsubmit="return confirm('Hapus sertifikat ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                <i class="fas fa-trash mr-1"></i>Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-500">
                                    Tidak ada data sertifikat.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t">
                    {{ $sertifikats->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-sidebar-layout>
