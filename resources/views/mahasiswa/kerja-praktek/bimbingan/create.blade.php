<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tambah Bimbingan
            </h2>
            <a href="{{ route('mahasiswa.bimbingan.index') }}"
               class="text-unib-blue-600 hover:text-unib-blue-800 text-sm">
                <i class="fas fa-arrow-left mr-1"></i>Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash / errors --}}
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
                    <div class="font-semibold mb-1">Periksa input Anda:</div>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Info KP singkat --}}
            @if($kerjaPraktek)
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi KP</h3>
                </div>
                <div class="p-6 text-sm text-gray-700 space-y-1">
                    <p><span class="text-gray-500">Judul:</span> <span class="font-medium">{{ $kerjaPraktek->judul_kp }}</span></p>
                    <p>
                        <span class="text-gray-500">Tempat:</span>
                        <span class="font-medium">
                            @if($kerjaPraktek->pilihan_tempat == 3)
                                {{ $kerjaPraktek->tempat_magang_sendiri }}
                            @else
                                {{ $kerjaPraktek->tempatMagang->nama_perusahaan ?? '-' }}
                            @endif
                        </span>
                    </p>
                    <p><span class="text-gray-500">Status:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Sedang KP
                        </span>
                    </p>
                </div>
            </div>
            @endif

            {{-- Form tambah bimbingan --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Ajukan Bimbingan</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('mahasiswa.bimbingan.store') }}" class="grid grid-cols-1 gap-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal & Waktu *</label>
                            <input type="date" name="tanggal_bimbingan" required
                                   value="{{ old('tanggal_bimbingan') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Ajukan waktu bimbingan. Dosen akan memverifikasi/jadwalkan ulang bila perlu.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Topik Bimbingan *</label>
                            <input type="text" name="topik_bimbingan" required maxlength="255"
                                   value="{{ old('topik_bimbingan') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan / Ringkasan *</label>
                            <textarea name="catatan_mahasiswa" rows="5" required
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                      placeholder="Jelaskan materi, hambatan, atau pertanyaan yang ingin dibahas...">{{ old('catatan_mahasiswa') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('mahasiswa.bimbingan.index') }}"
                               class="px-5 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                                Batal
                            </a>
                            <button type="submit"
                                    class="px-5 py-2 rounded-lg bg-unib-blue-600 hover:bg-unib-blue-700 text-white">
                                <i class="fas fa-paper-plane mr-2"></i>Kirim Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
