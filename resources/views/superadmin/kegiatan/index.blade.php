{{-- resources/views/superadmin/kegiatan/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kegiatan Mahasiswa
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Filter --}}
            <div class="bg-white rounded-lg shadow p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs text-gray-500 mb-1">Cari</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari deskripsi/mahasiswa/perusahaan…"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Dari</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Sampai</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                    </div>
                    <div class="flex items-end gap-2">
                        <button class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-4 py-2 rounded-md">Filter</button>
                        <a href="{{ route('superadmin.kegiatan.index') }}" class="ml-3 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md">Reset</a>
                    </div>
                </form>
            </div>

            {{-- Tabel --}}
            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Mahasiswa</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Perusahaan</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Durasi</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Deskripsi</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Bukti</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($kegiatan as $row)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900">
                                {{ \Illuminate\Support\Carbon::parse($row->tanggal_kegiatan)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-2">
                                <div class="text-gray-900 font-medium">{{ $row->mahasiswa->name ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $row->mahasiswa->npm ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-900">
                                {{ $row->kerjaPraktek->tempatMagang->nama_perusahaan ?? '-' }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-900">
                                {{ $row->durasi_jam }} jam
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-900">
                                {{ \Illuminate\Support\Str::limit($row->deskripsi_kegiatan, 90) }}
                            </td>
                            <td class="px-4 py-2 text-sm">
                                @if($row->file_dokumentasi)
                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($row->file_dokumentasi) }}" target="_blank">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($row->file_dokumentasi) }}" alt="Bukti" class="w-16 h-16 object-cover rounded cursor-pointer hover:opacity-75">
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <form method="POST" action="{{ route('superadmin.kegiatan.destroy', $row) }}"
                                      onsubmit="return confirm('Hapus kegiatan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:text-red-800 text-sm">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-8 text-gray-500">Tidak ada data.</td></tr>
                    @endforelse
                    </tbody>
                </table>

                <div class="p-4 border-t">
                    {{ $kegiatan->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
