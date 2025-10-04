{{-- resources/views/superadmin/kuisioner/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Rekap Kuisioner KP
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Filter --}}
            <div class="bg-white shadow rounded-lg p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Dari</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-unib-blue-500 focus:border-unib-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Sampai</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-unib-blue-500 focus:border-unib-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-600 mb-1">Cari</label>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Nama/NPM, judul KP, atau perusahaan…"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-unib-blue-500 focus:border-unib-blue-500">
                    </div>
                    <div class="flex items-end">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            Cari
                        </button >
                        <a href="{{ route('superadmin.kuisioner.index') }}" class="ml-3 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Ringkasan --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-600">Total Responden</p>
                    <p class="text-3xl font-bold text-unib-blue-600 mt-2">{{ number_format($stats['total']) }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-600">Rata-rata Tempat</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['avg_tempat'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-600">Rata-rata Bimbingan</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['avg_bimbingan'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-600">Rekomendasi Tempat</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $stats['rekom_rate'] }}%</p>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="bg-white shadow rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Mahasiswa</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Judul KP</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tempat</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rekomendasi</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Isi</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($items as $it)
                            @php
                                $kp = $it->kerjaPraktek;
                                $mhs = $kp?->mahasiswa;
                                $tm  = $kp?->tempatMagang;
                            @endphp
                            <tr>
                                <td class="px-4 py-2">
                                    <div class="font-medium text-gray-900">{{ $mhs?->name ?? '-' }}</div>
                                    @if($mhs?->npm)
                                        <div class="text-xs text-gray-500">NPM: {{ $mhs->npm }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-2">{{ $kp?->judul_kp ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    {{ $kp?->pilihan_tempat == 3 ? ($kp->tempat_magang_sendiri ?? '-') : ($tm?->nama_perusahaan ?? '-') }}
                                </td>
                                <td class="px-4 py-2">
                                    <span class="px-6 py-4 whitespace-nowrap text-sm font-medium
                                        @class([
                                            'bg-green-100 text-green-800' => $it->rating_keseluruhan === 'Sangat Baik',
                                            'bg-blue-100 text-blue-800'  => $it->rating_keseluruhan === 'Baik',
                                            'bg-yellow-100 text-yellow-800' => $it->rating_keseluruhan === 'Cukup Baik',
                                            'bg-red-100 text-red-800'    => $it->rating_keseluruhan === 'Kurang Baik',
                                            'bg-gray-100 text-gray-800'  => !$it->rating_keseluruhan,
                                        ])">
                                        {{ $it->rating_keseluruhan ?? 'N/A' }}
                                <td class="px-4 py-2">
                                    @if($it->rekomendasi_tempat)
                                        <span class="px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700">Ya</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700">Tidak</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-700">
                                    {{ $it->created_at->locale('id')->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('superadmin.kuisioner.show', $it) }}"
                                        class="text-unib-blue-600 hover:text-unib-blue-900" title="Detail">
                                        <i class="fas fa-eye"></i> Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-500">
                                    Belum ada kuisioner yang masuk.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
