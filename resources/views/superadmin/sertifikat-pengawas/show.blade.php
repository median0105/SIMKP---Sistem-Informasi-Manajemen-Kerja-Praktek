    {{-- resources/views/superadmin/sertifikat-pengawas/show.blade.php --}}
<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Sertifikat Pengawas Lapangan
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 px-4 sm:px-6 lg:px-8 space-y-6">
            {{-- Flash --}}
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Detail Card --}}
            <div class="bg-white shadow rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Sertifikat</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama Pengawas</dt>
                                <dd class="text-sm text-gray-900">{{ $sertifikatPengawa->nama_pengawas }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nomor Sertifikat</dt>
                                <dd class="text-sm text-gray-900">{{ $sertifikatPengawa->nomor_sertifikat }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tahun Ajaran</dt>
                                <dd class="text-sm text-gray-900">{{ $sertifikatPengawa->tahun_ajaran }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama Kaprodi</dt>
                                <dd class="text-sm text-gray-900">{{ $sertifikatPengawa->nama_kaprodi }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">NIP Kaprodi</dt>
                                <dd class="text-sm text-gray-900">{{ $sertifikatPengawa->nip_kaprodi }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="text-sm">
                                    @if($sertifikatPengawa->is_generated)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            <i class="fas fa-check-circle mr-1"></i>Generated
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                            <i class="fas fa-clock mr-1"></i>Belum Generated
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dibuat Pada</dt>
                                <dd class="text-sm text-gray-900">{{ $sertifikatPengawa->created_at->format('d M Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi</h3>
                        <div class="space-y-3">
                            {{-- Edit --}}
                            <a href="{{ route('superadmin.sertifikat-pengawas.edit', $sertifikatPengawa) }}"
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <i class="fas fa-edit mr-2"></i>Edit Sertifikat
                            </a>

                            {{-- Generate --}}
                            @if(!$sertifikatPengawa->is_generated)
                                <form method="POST" action="{{ route('superadmin.sertifikat-pengawas.generate', $sertifikatPengawa) }}">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                        <i class="fas fa-cog mr-2"></i>Generate PDF
                                    </button>
                                </form>
                            @else
                                {{-- Download --}}
                                <a href="{{ route('superadmin.sertifikat-pengawas.download', $sertifikatPengawa) }}"
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <i class="fas fa-download mr-2"></i>Download PDF
                                </a>
                            @endif

                            {{-- Back --}}
                            <a href="{{ route('superadmin.sertifikat-pengawas.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-sidebar-layout>
