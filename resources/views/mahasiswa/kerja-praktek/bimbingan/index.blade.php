<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Bimbingan Kerja Praktek') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
               @if($kerjaPraktek && $kerjaPraktek->status === 'sedang_kp')
                <div class="mb-6">
                    <a href="{{ route('mahasiswa.bimbingan.create') }}"
                       class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Bimbingan
                    </a>
                </div>
            @endif
            <!-- Bimbingan List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Riwayat Bimbingan</h3>
                    @if($bimbingan->count() > 0)
                        <div class="space-y-6">
                            @foreach($bimbingan as $item)
                                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition duration-200">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-3">
                                                <h4 class="text-lg font-medium text-gray-900">{{ $item->topik_bimbingan }}</h4>
                                                @if($item->status_verifikasi)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-check mr-1"></i>
                                                        Verified
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        Pending
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="flex items-center text-sm text-gray-600 mb-3">
                                                <i class="fas fa-calendar mr-2"></i>
                                                {{ $item->tanggal_bimbingan->format('d F Y') }}
                                            </div>

                                            <div class="mb-4">
                                                <p class="text-sm font-medium text-gray-600 mb-1">Catatan Mahasiswa:</p>
                                                <p class="text-gray-900">{{ Str::limit($item->catatan_mahasiswa, 150) }}</p>
                                            </div>

                                            @if($item->catatan_dosen)
                                                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                                    <p class="text-sm font-medium text-gray-600 mb-1">Feedback Dosen:</p>
                                                    <p class="text-gray-900">{{ $item->catatan_dosen }}</p>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="ml-4">
                                            <a href="{{ route('mahasiswa.bimbingan.show', $item) }}"
                                               class="text-unib-blue-600 hover:text-unib-blue-800 font-medium">
                                                <i class="fas fa-eye mr-1"></i>
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $bimbingan->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Bimbingan</h4>
                            <p class="text-gray-600 mb-6">Mulai dokumentasikan proses bimbingan Anda dengan dosen pembimbing.</p>
                            <a href="{{ route('mahasiswa.bimbingan.create') }}"
                               class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Bimbingan Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-sidebar-layout>
