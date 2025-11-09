<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('mahasiswa.bimbingan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center text-sm">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Bimbingan
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Ringkasan --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $bimbingan->topik_bimbingan }}</h3>
                        @if($bimbingan->status_verifikasi)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i>Verified
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>Pending
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Bimbingan</p>
                            <p class="font-medium text-gray-900">
                                {{ optional($bimbingan->tanggal_bimbingan)->format('d M Y H:i') ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Dibuat</p>
                            <p class="font-medium text-gray-900">
                                {{ $bimbingan->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-600 mb-1">Catatan Mahasiswa</p>
                        <div class="whitespace-pre-line text-gray-900">{{ $bimbingan->catatan_mahasiswa }}</div>
                    </div>

                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-600 mb-1">Feedback Dosen</p>
                        @if($bimbingan->catatan_dosen)
                            <div class="whitespace-pre-line text-gray-900">{{ $bimbingan->catatan_dosen }}</div>
                        @else
                            <p class="text-gray-500 text-sm italic">Belum ada feedback dosen.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Info KP singkat --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi KP</h3>
                </div>
                <div class="p-6 text-sm text-gray-700 space-y-1">
                    <p><span class="text-gray-500">Judul:</span> <span class="font-medium">{{ $bimbingan->kerjaPraktek->judul_kp ?? '-' }}</span></p>
                    <p>
                        <span class="text-gray-500">Tempat:</span>
                        <span class="font-medium">
                            @php $kp = $bimbingan->kerjaPraktek; @endphp
                            @if($kp)
                                @if($kp->pilihan_tempat == 3)
                                    {{ $kp->tempat_magang_sendiri }}
                                @else
                                    {{ $kp->tempatMagang->nama_perusahaan ?? '-' }}
                                @endif
                            @else
                                -
                            @endif
                        </span>
                    </p>
                    <p>
                        <span class="text-gray-500">Status KP:</span>
                        <span class="font-medium">{{ $kp->status ?? '-' }}</span>
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-sidebar-layout>
