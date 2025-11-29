{{-- resources/views/superadmin/sertifikat-pengawas/show.blade.php --}}
<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                  
                </div>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Detail Sertifikat Pengawas Lapangan
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            {{-- Flash Messages --}}
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

            {{-- Detail Card --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-unib-blue-100">
                {{-- Card Header --}}
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Informasi Sertifikat
                    </h3>
                </div>

                {{-- Card Body --}}
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Informasi Sertifikat --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Detail Sertifikat</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Nama Pengawas</dt>
                                    <dd class="text-base text-gray-900 font-medium">{{ $sertifikatPengawa->nama_pengawas }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Nomor Sertifikat</dt>
                                    <dd class="text-base text-gray-900 font-medium">{{ $sertifikatPengawa->nomor_sertifikat }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Tahun Ajaran</dt>
                                    <dd class="text-base text-gray-900 font-medium">{{ $sertifikatPengawa->tahun_ajaran }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Nama Kaprodi</dt>
                                    <dd class="text-base text-gray-900 font-medium">{{ $sertifikatPengawa->nama_kaprodi }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 mb-1">NIP Kaprodi</dt>
                                    <dd class="text-base text-gray-900 font-medium">{{ $sertifikatPengawa->nip_kaprodi }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Status</dt>
                                    <dd class="text-base">
                                        @if($sertifikatPengawa->is_generated)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-300 shadow-sm">
                                                <i class="fas fa-check-circle mr-1"></i>Sudah Dicetak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-300 shadow-sm">
                                                <i class="fas fa-clock mr-1"></i>Belum Dicetak
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Dibuat Pada</dt>
                                    <dd class="text-base text-gray-900 font-medium">{{ $sertifikatPengawa->created_at->format('d M Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>

                        {{-- Aksi --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Aksi</h3>
                            <div class="space-y-4">
                                {{-- Edit --}}
                                <a href="{{ route('superadmin.sertifikat-pengawas.edit', $sertifikatPengawa) }}"
                                   class="w-full bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-semibold shadow-lg transform hover:scale-105 transition duration-200 flex items-center justify-center">
                                    <i class="fas fa-edit mr-2"></i>Edit Sertifikat
                                </a>

                                {{-- Generate / Download --}}
                                @if(!$sertifikatPengawa->is_generated)
                                    <form method="POST" action="{{ route('superadmin.sertifikat-pengawas.generate', $sertifikatPengawa) }}" class="w-full">
                                        @csrf
                                        <button type="submit"
                                                class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-base font-semibold shadow-lg transform hover:scale-105 transition duration-200 flex items-center justify-center">
                                            <i class="fas fa-file-pdf mr-2"></i>Cetak PDF
                                        </button>
                                    </form>
                                @else
                                    {{-- Download --}}
                                    <a href="{{ route('superadmin.sertifikat-pengawas.download', $sertifikatPengawa) }}"
                                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-base font-semibold shadow-lg transform hover:scale-105 transition duration-200 flex items-center justify-center">
                                        <i class="fas fa-download mr-2"></i>Download PDF
                                    </a>
                                @endif

                                {{-- Back --}}
                                <a href="{{ route('superadmin.sertifikat-pengawas.index') }}"
                                   class="w-full bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-base font-semibold shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-sidebar-layout>