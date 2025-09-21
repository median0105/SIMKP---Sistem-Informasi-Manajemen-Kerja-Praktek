{{-- resources/views/superadmin/laporan/index.blade.php --}}
<x-app-layout>
    @php
        // Fallback agar view tetap aman walau controller belum mengirim variabelnya
        $startDate = ($startDate ?? now()->startOfMonth()->subMonths(5));
        $endDate   = ($endDate   ?? now());

        $stats = array_merge([
            'total_kp'              => 0,
            'kp_selesai'            => 0,
            'total_mahasiswa_aktif' => 0,
            'total_tempat_magang'   => 0,
        ], ($stats ?? []));

        $avgDuration  = $avgDuration  ?? 0;
        $statusStats  = $statusStats  ?? ['pengajuan'=>0,'disetujui'=>0,'sedang_kp'=>0,'selesai'=>0,'ditolak'=>0];
        $popularTempat= $popularTempat?? collect();
        $trendKP      = $trendKP      ?? collect();
        $topPerformers= $topPerformers?? collect();
    @endphp

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Laporan & Analytics
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('superadmin.laporan.export-kp') }}"
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-download mr-2"></i>Export KP
                </a>
                <a href="{{ route('superadmin.laporan.export-mahasiswa') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-download mr-2"></i>Export Mahasiswa
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Filter Periode --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Filter Laporan</h3>
                </div>
                <div class="p-6">
                    <form method="GET" class="flex flex-wrap gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                            <input type="date" name="start_date"
                                   value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
                                   class="border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                            <input type="date" name="end_date"
                                   value="{{ request('end_date', $endDate->format('Y-m-d')) }}"
                                   class="border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                        </div>
                        <div class="flex items-end">
                            <button type="submit"
                                    class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-2 rounded-md font-medium">
                                <i class="fas fa-search mr-2"></i>Update Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Overview Statistics --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total KP</p>
                            <p class="text-3xl font-bold text-blue-600 mt-2">{{ number_format($stats['total_kp']) }}</p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-3">
                            <i class="fas fa-briefcase text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 text-sm">
                        <span class="text-green-600 font-medium">
                            {{ $stats['total_kp'] > 0 ? number_format(($stats['kp_selesai'] / $stats['total_kp']) * 100, 1) : 0 }}%
                        </span>
                        <span class="text-gray-600 ml-1">completion rate</span>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">KP Selesai</p>
                            <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($stats['kp_selesai']) }}</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-3">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-gray-600">
                        Rata-rata durasi: {{ number_format($avgDuration, 0) }} hari
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Mahasiswa Aktif</p>
                            <p class="text-3xl font-bold text-purple-600 mt-2">{{ number_format($stats['total_mahasiswa_aktif']) }}</p>
                        </div>
                        <div class="bg-purple-100 rounded-full p-3">
                            <i class="fas fa-users text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Tempat Magang</p>
                            <p class="text-3xl font-bold text-orange-600 mt-2">{{ number_format($stats['total_tempat_magang']) }}</p>
                        </div>
                        <div class="bg-orange-100 rounded-full p-3">
                            <i class="fas fa-building text-orange-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Status Distribution --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Distribusi Status KP</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach($statusStats as $status => $count)
                            @php
                                $percentage = $stats['total_kp'] > 0 ? ($count / $stats['total_kp']) * 100 : 0;
                                $colorClass = match($status) {
                                    'pengajuan' => 'yellow',
                                    'disetujui' => 'blue',
                                    'sedang_kp' => 'green',
                                    'selesai'   => 'gray',
                                    'ditolak'   => 'red',
                                    default     => 'gray',
                                };
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 bg-{{ $colorClass }}-500 rounded-full mr-3"></span>
                                    <span class="text-sm text-gray-600 capitalize">{{ str_replace('_', ' ', $status) }}</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                        <div class="bg-{{ $colorClass }}-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">{{ $count }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Tempat Magang Terpopuler --}}
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Tempat Magang Terpopuler</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse($popularTempat as $tempat)
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-900">
                                            {{ $tempat->tempatMagang->nama_perusahaan ?? 'Unknown' }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            {{ $tempat->tempatMagang->bidang_usaha ?? '' }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-unib-blue-600">{{ $tempat->total }}</p>
                                        <p class="text-xs text-gray-500">mahasiswa</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-gray-500">
                                    <i class="fas fa-chart-bar text-3xl text-gray-300 mb-2"></i>
                                    Belum ada data
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Trend KP per Bulan --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Trend KP per Bulan</h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <div class="flex items-end space-x-2 h-64">
                            @php $maxValue = max($trendKP->max('total') ?? 0, 1); @endphp
                            @foreach($trendKP as $trend)
                                @php
                                    $height = ($trend->total / $maxValue) * 200; // px
                                @endphp
                                <div class="flex flex-col items-center">
                                    <div class="bg-unib-blue-500 rounded-t" style="height: {{ $height }}px; width: 40px;"></div>
                                    <div class="text-xs text-gray-600 mt-2 text-center">
                                        <div class="font-semibold">{{ $trend->total }}</div>
                                        <div>{{ date('M Y', strtotime(($trend->month ?? '1970-01') . '-01')) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top Performers --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Mahasiswa Berprestasi</h3>
                    <a href="{{ route('superadmin.laporan.detail-kp', ['status' => 'selesai']) }}"
                       class="text-unib-blue-600 hover:text-unib-blue-800 text-sm">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Mahasiswa</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Judul KP</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nilai</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($topPerformers as $index => $kp)
                                    <tr>
                                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-900">
                                                {{ $kp->mahasiswa->name ?? '-' }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $kp->mahasiswa->npm ?? '' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">{{ $kp->judul_kp ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $kp->nilai_akhir ?? '-' }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @class([
                                                    'bg-yellow-100 text-yellow-800' => ($kp->status ?? '')==='pengajuan',
                                                    'bg-blue-100 text-blue-800'     => ($kp->status ?? '')==='disetujui',
                                                    'bg-green-100 text-green-800'   => ($kp->status ?? '')==='sedang_kp',
                                                    'bg-gray-100 text-gray-800'     => ($kp->status ?? '')==='selesai',
                                                    'bg-red-100 text-red-800'       => ($kp->status ?? '')==='ditolak',
                                                ])">
                                                {{ ucfirst(str_replace('_',' ', $kp->status ?? '-')) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-6 text-gray-500">Belum ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
