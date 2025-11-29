{{-- resources/views/superadmin/sertifikat-pengawas/index.blade.php --}}
<x-sidebar-layout>

    {{-- ====================== CSS RESPONSIF ====================== --}}
    <style>
        .overflow-x-auto {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        table th, table td {
            white-space: nowrap;
        }

        /* --- Desktop Large --- */
        @media (min-width: 1280px) {
            table th { font-size: 1rem; }
            table td { font-size: .95rem; }
        }

        /* --- Tablet / iPad Landscape (≤1024px) --- */
        @media (max-width: 1024px) {
            .px-6.py-4 { padding-left: 1rem !important; padding-right: 1rem !important; }

            table th { font-size: .85rem !important; }
            table td { font-size: .85rem !important; }

            h2, .text-xl { font-size: 1.15rem !important; }
        }

        /* --- Tablet Potrait / Mobile Besar (≤768px) --- */
        @media (max-width: 768px) {
            .flex.justify-end { justify-content: center !important; }
            .flex.justify-end a { width: 100%; text-align: center; }

            table th { padding: .6rem .75rem; }
            table td { padding: .6rem .75rem; }

            /* Kolom Aksi jadi vertikal */
            td .flex.items-center.gap-3 { flex-direction: column !important; }

            /* Tabel shrink */
            table { min-width: 900px !important; }
        }

        /* --- Mobile (≤640px) --- */
        @media (max-width: 640px) {
            h2, .text-xl { font-size: 1rem !important; }
            table th, table td { font-size: .75rem !important; }

            .inline-flex.items-center {
                font-size: .65rem !important;
                padding: .25rem .5rem !important;
            }
        }

        /* --- Mobile kecil (≤480px) --- */
        @media (max-width: 480px) {
            .px-6.py-4, .px-4.py-3 { padding: .25rem !important; }
            table th, table td { font-size: .7rem !important; }
            td a, td button {
                width: 100%;
                text-align: center;
                padding: .35rem .5rem !important;
                font-size: .7rem !important;
            }
        }
    </style>
    {{-- ====================== END CSS ====================== --}}

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <h2 class="font-bold text-xl leading-tight">Sertifikat Pengawas Lapangan</h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- BUTTON --}}
            <div class="flex flex-wrap items-center gap-4 justify-end">

                <a href="{{ route('superadmin.sertifikat-pengawas.create') }}"
                   class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-semibold shadow-lg transform hover:scale-105 transition flex items-center">
                    <i class="fa-solid fa-plus mr-2"></i>
                    Tambah Sertifikat
                </a>

                <form method="POST" action="{{ route('superadmin.sertifikat-pengawas.generate-all') }}"
                      onsubmit="return confirm('Cetak semua sertifikat yang belum dicetak?');">
                    @csrf
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-base font-semibold shadow-lg transform hover:scale-105 transition flex items-center">
                        <i class="fa-solid fa-print mr-2"></i>
                        Cetak Semua
                    </button>
                </form>
            </div>

            {{-- ALERT --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-r-lg shadow-md flex items-center">
                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-lg shadow-md flex items-center">
                    <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                    {{ session('error') }}
                </div>
            @endif

            {{-- FILTER --}}
            <div class="bg-white rounded-xl shadow-lg p-6 border border-unib-blue-100">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama pengawas / nomor sertifikat…"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" value="{{ request('tahun_ajaran') }}"
                               placeholder="Tahun Ajaran"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base">
                    </div>

                    <div class="flex items-end gap-3">
                        <button class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md flex-1">
                            Cari
                        </button>
                        <a href="{{ route('superadmin.sertifikat-pengawas.index') }}"
                           class="bg-teknik-orange-500 hover:bg-teknik-orange-600 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md flex-1 text-center">
                            Reset
                        </a>
                    </div>

                </form>
            </div>

            {{-- TABLE --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-unib-blue-100">

                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between">
                    <h3 class="text-xl font-bold">Daftar Sertifikat Pengawas</h3>
                    <span class="inline-flex items-center px-4 py-2 rounded-full bg-white/20 border border-white/30">
                        Total: {{ $sertifikats->total() }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold text-unib-blue-800">Nama Pengawas</th>
                                <th class="px-6 py-4 text-left font-semibold text-unib-blue-800">Nomor Sertifikat</th>
                                <th class="px-6 py-4 text-left font-semibold text-unib-blue-800">Tahun Ajaran</th>
                                <th class="px-6 py-4 text-left font-semibold text-unib-blue-800">Status</th>
                                <th class="px-6 py-4 text-center font-semibold text-unib-blue-800">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">

                            @forelse($sertifikats as $sertifikat)
                                <tr class="hover:bg-unib-blue-50 transition">

                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $sertifikat->nama_pengawas }}</td>

                                    <td class="px-6 py-4">{{ $sertifikat->nomor_sertifikat }}</td>

                                    <td class="px-6 py-4">{{ $sertifikat->tahun_ajaran }}</td>

                                    <td class="px-6 py-4">
                                        @if($sertifikat->is_generated)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800 border border-green-300">
                                                <i class="fas fa-check mr-1"></i> Sudah Dicetak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-yellow-100 text-yellow-800 border border-yellow-300">
                                                <i class="fas fa-clock mr-1"></i> Belum Dicetak
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center gap-3 justify-center">

                                            <a href="{{ route('superadmin.sertifikat-pengawas.show', $sertifikat) }}"
                                               class="text-unib-blue-600 hover:text-unib-blue-800 text-sm font-semibold px-3 py-2 rounded-lg hover:bg-unib-blue-100 transition">Lihat</a>

                                            <a href="{{ route('superadmin.sertifikat-pengawas.edit', $sertifikat) }}"
                                               class="text-purple-600 hover:text-purple-800 text-sm font-semibold px-3 py-2 rounded-lg hover:bg-purple-100 transition">Edit</a>

                                            @if(!$sertifikat->is_generated)
                                                <form method="POST" action="{{ route('superadmin.sertifikat-pengawas.generate', $sertifikat) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            class="text-green-600 hover:text-green-800 text-sm font-semibold px-3 py-2 rounded-lg hover:bg-green-100 transition">
                                                        Cetak
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('superadmin.sertifikat-pengawas.download', $sertifikat) }}"
                                                   class="text-blue-700 hover:text-blue-900 text-sm font-semibold px-3 py-2 rounded-lg hover:bg-blue-100 transition">
                                                    Download
                                                </a>
                                            @endif

                                            <form method="POST" action="{{ route('superadmin.sertifikat-pengawas.destroy', $sertifikat) }}"
                                                  onsubmit="return confirm('Hapus sertifikat ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-800 text-sm font-semibold px-3 py-2 rounded-lg hover:bg-red-50 transition">
                                                    Hapus
                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-12">
                                        <i class="fas fa-inbox text-4xl text-gray-300 mb-4 block"></i>
                                        <p class="font-medium text-gray-900">Tidak Ada Data</p>
                                        <p class="text-sm text-gray-600">Tidak ada data sertifikat.</p>
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t bg-unib-blue-50">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-unib-blue-700">
                            Menampilkan {{ $sertifikats->firstItem() }} - {{ $sertifikats->lastItem() }} dari {{ $sertifikats->total() }}
                        </p>
                        {{ $sertifikats->withQueryString()->links() }}
                    </div>
                </div>

            </div>

        </div>
    </div>

</x-sidebar-layout>
