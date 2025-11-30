{{-- resources/views/superadmin/tempat-magang/show.blade.php --}}
<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <a href="{{ route('superadmin.tempat-magang.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg inline-flex items-center transition duration-200 transform hover:scale-105 backdrop-blur-sm border border-white/30">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <div class="flex items-center space-x-3">
                    <div>
                        <h2 class="font-bold text-xl leading-tight">
                            Detail Tempat — {{ $tempatMagang->nama_perusahaan }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            {{-- Info Perusahaan --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-unib-blue-100">
                {{-- Card Header --}}
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Informasi Perusahaan
                    </h3>
                </div>

                {{-- Card Body --}}
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2 space-y-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $tempatMagang->nama_perusahaan }}</h3>
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-unib-blue-100 text-unib-blue-800 border border-unib-blue-300 shadow-sm">
                                        <i class="fas fa-industry mr-2 text-xs"></i>
                                        {{ $tempatMagang->bidang_usaha }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="text-base text-gray-700">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-map-marker-alt mr-3 text-unib-blue-500 w-5"></i>
                                    <span>{{ $tempatMagang->alamat }}</span>
                                </div>
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-user mr-3 text-unib-blue-500 w-5"></i>
                                    <span>{{ $tempatMagang->kontak_person }}</span>
                                </div>
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-envelope mr-3 text-unib-blue-500 w-5"></i>
                                    <span>{{ $tempatMagang->email_perusahaan }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-phone mr-3 text-unib-blue-500 w-5"></i>
                                    <span>{{ $tempatMagang->telepon_perusahaan }}</span>
                                </div>
                            </div>

                            @if($tempatMagang->deskripsi)
                                <div class="pt-4 border-t border-gray-200">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Deskripsi</h4>
                                    <p class="text-base text-gray-700 leading-relaxed">{{ $tempatMagang->deskripsi }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-6">
                            {{-- Kuota --}}
                            <div class="bg-unib-blue-50 rounded-lg p-4 border border-unib-blue-200">
                                <div class="text-sm font-medium text-unib-blue-700 mb-1">Kuota Tersedia</div>
                                @php
                                    $terpakai = $tempatMagang->kerjaPraktek->filter(function ($kp) {
                                        return in_array($kp->status, ['disetujui', 'sedang_kp']) && !($kp->nilai_akhir && $kp->file_laporan);
                                    })->count();
                                    $sisa = max(0, $tempatMagang->kuota_mahasiswa - $terpakai);
                                @endphp
                                <div class="text-2xl font-bold text-unib-blue-600">{{ $sisa }} <span class="text-sm font-normal text-gray-600">/ {{ $tempatMagang->kuota_mahasiswa }}</span></div>
                            </div>

                            {{-- Status --}}
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="text-sm font-medium text-gray-700 mb-2">Status</div>
                                @if($tempatMagang->is_active)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-300 shadow-sm">
                                        <i class="fas fa-check-circle mr-2 text-xs"></i>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-300 shadow-sm">
                                        <i class="fas fa-times-circle mr-2 text-xs"></i>Tidak Aktif
                                    </span>
                                @endif
                            </div>

                            {{-- Statistik KP --}}
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="text-sm font-medium text-gray-700 mb-3">Statistik KP</div>
                                @php
                                    $displayCounts = $tempatMagang->kerjaPraktek->map(function ($kp) {
                                        $displayStatus = $kp->status ?? 'pengajuan';
                                        if ($kp->status === 'sedang_kp' && $kp->nilai_akhir && $kp->file_laporan) {
                                            $displayStatus = 'selesai';
                                        }
                                        return $displayStatus;
                                    })->countBy();
                                    $sedangCount = $displayCounts->get('sedang_kp', 0);
                                    $selesaiCount = $displayCounts->get('selesai', 0);
                                @endphp
                                <div class="space-y-2 text-sm text-gray-600">
                                    <div class="flex justify-between">
                                        <span>Total KP:</span>
                                        <span class="font-semibold">{{ $tempatMagang->kerjaPraktek->count() }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Sedang KP:</span>
                                        <span class="font-semibold text-blue-600">{{ $sedangCount }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Selesai:</span>
                                        <span class="font-semibold text-green-600">{{ $selesaiCount }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Daftar Mahasiswa --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-unib-blue-100">
                {{-- Card Header --}}
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Daftar Mahasiswa
                    </h3>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                        Total: {{ $tempatMagang->kerjaPraktek->count() }}
                    </span>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">Judul KP</th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($tempatMagang->kerjaPraktek as $kp)
                                <tr class="hover:bg-unib-blue-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900 text-base">{{ $kp->mahasiswa->name ?? '-' }}</div>
                                        @if($kp->mahasiswa->npm ?? false)
                                            <div class="text-sm text-gray-500">{{ $kp->mahasiswa->npm }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-900 text-base">{{ $kp->judul_kp }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $displayStatus = $kp->status ?? 'pengajuan';
                                            if ($kp->status === 'sedang_kp' && $kp->nilai_akhir && $kp->file_laporan) {
                                                $displayStatus = 'selesai';
                                            }
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                            @if($displayStatus === 'selesai') bg-green-100 text-green-800 border border-green-300
                                            @elseif($displayStatus === 'sedang_kp') bg-blue-100 text-blue-800 border border-blue-300
                                            @elseif($displayStatus === 'disetujui') bg-yellow-100 text-yellow-800 border border-yellow-300
                                            @else bg-gray-100 text-gray-800 border border-gray-300
                                            @endif shadow-sm">
                                            {{ ucfirst(str_replace('_',' ',$displayStatus)) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-12">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
                                            <div class="text-base font-medium text-gray-900 mb-2">Tidak Ada Data</div>
                                            <p class="text-sm text-gray-600">Belum ada mahasiswa yang magang di tempat ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-sidebar-layout>