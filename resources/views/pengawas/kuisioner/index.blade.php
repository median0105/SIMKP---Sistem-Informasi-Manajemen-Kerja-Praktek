
{{-- resources/views/pengawas/kuisioner/index.blade.php --}}
<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kuisioner Mahasiswa
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Statistics --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-600">Total KP Selesai</p>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['total_selesai_kp'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-600">Sudah Isi Kuisioner</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['sudah_isi_kuisioner'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-600">Belum Isi Kuisioner</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['belum_isi_kuisioner'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-600">Rata-rata Rating</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ number_format($stats['rata_rating'], 1) }}</p>
                </div>
            </div>

            {{-- Search --}}
            <div class="bg-white shadow rounded-lg p-6">
                <form method="GET" class="flex gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama mahasiswa atau perusahaan..."
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('pengawas.kuisioner.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white shadow rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Mahasiswa</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Judul KP</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tempat</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status Kuisioner</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Feedback</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($kerjaPraktek as $kp)
                            <tr>
                                <td class="px-4 py-2">
                                    <div class="font-medium text-gray-900">{{ $kp->mahasiswa->name }}</div>
                                    <div class="text-xs text-gray-500">NPM: {{ $kp->mahasiswa->npm }}</div>
                                </td>
                                <td class="px-4 py-2">{{ $kp->judul_kp }}</td>
                                <td class="px-4 py-2">
                                    {{ $kp->pilihan_tempat == 3 ? $kp->tempat_magang_sendiri : $kp->tempatMagang->nama_perusahaan }}
                                </td>
                                <td class="px-4 py-2">
                                    @if($kp->kuisioner)
                                        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Sudah Isi</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">Belum Isi</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    @php
                                        $feedback = \App\Models\KuisionerPembimbingLapangan::where('kerja_praktek_id', $kp->id)->first();
                                    @endphp
                                    @if($feedback)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">Sudah Feedback</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">Belum Feedback</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-sm font-medium space-x-2">
                                    @if($kp->kuisioner)
                                        <a href="{{ route('pengawas.kuisioner.show', $kp) }}"
                                           class="text-blue-600 hover:text-blue-900">Lihat Kuisioner</a>
                                        <a href="{{ route('pengawas.kuisioner.feedback', $kp) }}"
                                           class="text-purple-600 hover:text-purple-900">Feedback</a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-500">
                                    Belum ada data kerja praktek yang selesai.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t">
                    {{ $kerjaPraktek->links() }}
                </div>
            </div>

            {{-- Analytics Link --}}
            <div class="text-center">
                <a href="{{ route('pengawas.kuisioner.analytics') }}"
                   class="bg-purple-500 hover:bg-purple-700 text-white px-6 py-3 rounded-md inline-flex items-center">
                    <i class="fas fa-chart-bar mr-2"></i>Lihat Analytics
                </a>
            </div>
        </div>
    </div>
</x-sidebar-layout>
