<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mahasiswa Kerja Praktek') }} — {{ $place->nama_perusahaan ?? '-' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Statistics Cards --}}
            {{-- Statistics Cards --}}
                @php
                    $cards = [
                        ['label'=>'Total Mahasiswa','value'=>$stats['total'] ?? 0,'icon'=>'fa-users','bg'=>'bg-blue-100','iconColor'=>'text-blue-600','numColor'=>'text-blue-600'],
                        ['label'=>'Pengajuan','value'=>$stats['pengajuan'] ?? 0,'icon'=>'fa-hourglass','bg'=>'bg-yellow-100','iconColor'=>'text-yellow-600','numColor'=>'text-yellow-600'],
                        ['label'=>'Disetujui','value'=>$stats['disetujui'] ?? 0,'icon'=>'fa-check','bg'=>'bg-purple-100','iconColor'=>'text-purple-600','numColor'=>'text-purple-600'],
                        ['label'=>'Sedang KP','value'=>$stats['sedang'] ?? 0,'icon'=>'fa-play','bg'=>'bg-emerald-100','iconColor'=>'text-emerald-600','numColor'=>'text-emerald-600'],
                        ['label'=>'Selesai','value'=>$stats['selesai'] ?? 0,'icon'=>'fa-flag-checkered','bg'=>'bg-gray-100','iconColor'=>'text-gray-600','numColor'=>'text-gray-600'],
                    ];
                @endphp

                <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6 items-stretch">
                    @foreach($cards as $c)
                        <div class="bg-white rounded-lg shadow p-6 h-full">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">{{ $c['label'] }}</p>
                                    <p class="text-2xl font-semibold mt-2 {{ $c['numColor'] }}">
                                        {{ $c['value'] }}
                                    </p>
                                </div>
                                <div class="{{ $c['bg'] }} rounded-full p-3 w-10 h-10 flex items-center justify-center">
                                    <i class="fas {{ $c['icon'] }} {{ $c['iconColor'] }}"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            {{-- Filter --}}
            <div class="bg-white shadow rounded-lg p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama / NPM / judul KP…"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                        >
                    </div>
                    <div class="flex gap-3">
                        <select
                            name="status"
                            class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                        >
                            <option value="">Semua Status</option>
                            @foreach(['pengajuan','disetujui','sedang_kp','selesai','ditolak'] as $st)
                                <option value="{{ $st }}" @selected(request('status')===$st)>
                                    {{ ucfirst(str_replace('_',' ',$st)) }}
                                </option>
                            @endforeach
                        </select>
                        <button
                            class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-5 rounded-md font-medium"
                        >
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>

            {{-- Daftar Mahasiswa --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Daftar Mahasiswa</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Nama</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">NPM</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Judul KP</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Status</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Tanggal Mulai</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($kp as $item)
                                <tr>
                                    <td class="px-4 py-2">
                                        {{ optional($item->mahasiswa)->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ optional($item->mahasiswa)->npm ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ $item->judul_kp ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2">
                                    @php
                                        $displayStatus = $item->status ?? 'pengajuan';
                                        if ($item->status === 'sedang_kp' && $item->nilai_akhir && $item->file_laporan) {
                                            $displayStatus = 'selesai';
                                        }
                                    @endphp
                                    @if($displayStatus === \App\Models\KerjaPraktek::STATUS_DISETUJUI || $displayStatus === 'disetujui')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-check-circle mr-1"></i> Disetujui
                                        </span>
                                    @elseif($displayStatus === \App\Models\KerjaPraktek::STATUS_DITOLAK || $displayStatus === 'ditolak')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i> Ditolak
                                        </span>
                                    @elseif($displayStatus === \App\Models\KerjaPraktek::STATUS_SEDANG_KP || $displayStatus === 'sedang_kp')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 whitespace-nowrap">
                                            <i class="fas fa-play-circle mr-1"></i> Sedang KP
                                        </span>
                                    @elseif($displayStatus === \App\Models\KerjaPraktek::STATUS_SELESAI || $displayStatus === 'selesai')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-flag-checkered mr-1"></i> Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i> Pengajuan
                                        </span>
                                    @endif
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700 whitespace-nowrap">
                                        {{ optional($item->tanggal_mulai)->locale('id')->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('pengawas.mahasiswa.show', $item) }}"
                                               class="text-unib-blue-600 hover:text-unib-blue-800 whitespace-nowrap">
                                                <i class="fas fa-eye mr-1"></i> Detail
                                            </a>

                                            @if($item->file_kartu_implementasi && !$item->acc_pembimbing_lapangan)
                                                <form method="POST" action="{{ route('pengawas.mahasiswa.acc-kartu', $item) }}">
                                                    @csrf
                                                    <button
                                                        onclick="return confirm('ACC kartu implementasi?')"
                                                        class="text-green-600 hover:text-green-800"
                                                    >
                                                        <i class="fas fa-check mr-1"></i> ACC Kartu
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-6 text-gray-500">
                                        Tidak ada mahasiswa untuk tempat ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $kp->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
