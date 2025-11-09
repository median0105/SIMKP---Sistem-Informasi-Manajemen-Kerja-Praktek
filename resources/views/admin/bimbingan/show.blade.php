{{-- resources/views/admin/bimbingan/show.blade.php --}}
<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.bimbingan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Riwayat Bimbingan - {{ $mahasiswa->name }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif
                <div class="flex space-x-2">
                <form method="POST" action="{{ route('admin.bimbingan.verify-all', $mahasiswa) }}">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2 rounded-md text-white bg-green-600 hover:bg-green-700 inline-flex items-center">
                        <i class="fas fa-check-double mr-2"></i>Verifikasi Semua
                    </button>
                </form>
            </div>
            {{-- Info Mahasiswa --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Mahasiswa</h3>
                <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">Nama</dt>
                        <dd class="text-gray-900 font-medium">{{ $mahasiswa->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">NPM</dt>
                        <dd class="text-gray-900 font-medium">{{ $mahasiswa->npm }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Tempat KP</dt>
                        <dd class="text-gray-900 font-medium">
                            @if($kerjaPraktek->pilihan_tempat == 3)
                                {{ $kerjaPraktek->tempat_magang_sendiri }}
                            @elseif($kerjaPraktek->tempatMagang)
                                {{ $kerjaPraktek->tempatMagang->nama_perusahaan }}
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Daftar Bimbingan --}}
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Riwayat Bimbingan</h3>
                </div>

                @if($mahasiswa->bimbingan->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topik</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($mahasiswa->bimbingan as $bimbingan)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $bimbingan->tanggal_bimbingan->locale('id')->translatedFormat('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <div class="font-medium">{{ $bimbingan->topik_bimbingan }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <div class="max-w-xs truncate">
                                                {{ $bimbingan->catatan_dosen ?: $bimbingan->catatan_mahasiswa ?: '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($bimbingan->status_verifikasi)
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                                    Terverifikasi
                                                </span>
                                            @else
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                                    Menunggu
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if(!$bimbingan->status_verifikasi)
                                                <form method="POST" action="{{ route('admin.bimbingan.verify', $bimbingan) }}" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                            class="text-green-600 hover:text-green-800 inline-flex items-center">
                                                        <i class="fas fa-check mr-1"></i>Verifikasi
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400">Sudah diverifikasi</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-10 text-center">
                        <i class="fas fa-comments text-5xl text-gray-300 mb-3"></i>
                        <p class="text-gray-600">Belum ada riwayat bimbingan.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-sidebar-layout>
