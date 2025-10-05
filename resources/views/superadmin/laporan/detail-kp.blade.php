{{-- resources/views/superadmin/laporan/detail-kp.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Daftar KP {{ $status ? '— '.strtoupper(str_replace('_',' ',$status)) : '' }}
            </h2>
            <a href="{{ route('superadmin.laporan.index') }}" class="text-unib-blue-600 hover:text-unib-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-lg shadow p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <div class="md:col-span-2">
                        <input name="search" value="{{ request('search') }}" placeholder="Cari judul / nama / NPM…"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                    </div>
                    <div>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div class="flex items-center">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            Cari
                        </button>
                        <a href="{{ route('superadmin.laporan.detail-kp', ['status' => 'selesai']) }}" class="ml-3 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Mahasiswa</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Judul KP</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tempat</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nilai</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($kp as $row)
                                <tr>
                                    <td class="px-4 py-2">
                                        <div class="font-medium text-gray-900">{{ $row->mahasiswa->name ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $row->mahasiswa->npm ?? '' }}</div>
                                    </td>
                                    <td class="px-4 py-2">{{ $row->judul_kp }}</td>
                                    <td class="px-4 py-2">{{ $row->tempatMagang->nama_perusahaan ?? $row->tempat_magang_sendiri ?? '-' }}</td>
                                    <td class="px-4 py-2 capitalize">{{ $row->display_status === 'tidak_lulus' ? 'Tidak Lulus' : str_replace('_',' ',$row->display_status) }}</td>
                                    <td class="px-4 py-2">{{ $row->nilai_akhir ?? '-' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $row->created_at->locale('id')->translatedFormat('d F Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-gray-500">Tidak ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t">
                    {{ $kp->withQueryString()->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
