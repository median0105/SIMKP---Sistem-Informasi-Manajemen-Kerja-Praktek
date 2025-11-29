<x-sidebar-layout>
    {{-- Header section with UNIB blue background --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <a href="{{ route('mahasiswa.bimbingan.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg inline-flex items-center transition duration-200 backdrop-blur-sm border border-white/30">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                    <!-- Ikon dihapus -->
                </div>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Tambah Bimbingan
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash messages for success and error --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-r-lg shadow-md flex items-center animate-fade-in-up">
                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-lg shadow-md flex items-center animate-fade-in-up">
                    <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Error messages --}}
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-lg shadow-md animate-fade-in-up">
                    <div class="font-semibold mb-1 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Periksa input Anda:
                    </div>
                    <ul class="list-disc list-inside text-sm mt-2">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Info KP Card --}}
            @if($kerjaPraktek)
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                    <h3 class="text-base font-semibold text-unib-blue-800">
                        Informasi KP
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-gray-500">Judul</p>
                                <p class="font-medium text-gray-900">{{ $kerjaPraktek->judul_kp }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-gray-500">Tempat</p>
                                <p class="font-medium text-gray-900">
                                    @if($kerjaPraktek->pilihan_tempat == 3)
                                        {{ $kerjaPraktek->tempat_magang_sendiri }}
                                    @else
                                        {{ $kerjaPraktek->tempatMagang->nama_perusahaan ?? '-' }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center md:col-span-2">
                            <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-gray-500">Status</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-300 shadow-sm">
                                    <i class="fas fa-check-circle mr-2 text-xs"></i>Sedang KP
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Form tambah bimbingan --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                    <h3 class="text-base font-semibold text-unib-blue-800">
                        Ajukan Bimbingan
                    </h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('mahasiswa.bimbingan.store') }}" class="space-y-6">
                        @csrf

                        {{-- Tanggal & Waktu --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal & Waktu *</label>
                            <input type="date" name="tanggal_bimbingan" required
                                   value="{{ old('tanggal_bimbingan') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                            <p class="text-xs text-gray-500 mt-2 flex items-center">
                                <i class="fas fa-info-circle mr-1 text-unib-blue-400"></i>
                                Ajukan waktu bimbingan. Dosen akan memverifikasi/jadwalkan ulang bila perlu.
                            </p>
                        </div>

                        {{-- Topik Bimbingan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Topik Bimbingan *</label>
                            <input type="text" name="topik_bimbingan" required maxlength="255"
                                   value="{{ old('topik_bimbingan') }}"
                                   placeholder="Masukkan topik bimbingan"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200">
                        </div>

                        {{-- Catatan / Ringkasan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan / Ringkasan *</label>
                            <textarea name="catatan_mahasiswa" rows="5" required
                                      class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                      placeholder="Jelaskan materi, hambatan, atau pertanyaan yang ingin dibahas...">{{ old('catatan_mahasiswa') }}</textarea>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end gap-3 pt-4">
                            <a href="{{ route('mahasiswa.bimbingan.index') }}"
                               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-base font-medium transition duration-200 flex items-center">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit"
                                    class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium transition duration-200 flex items-center">
                                <i class="fas fa-paper-plane mr-2"></i>Kirim Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- CSS untuk animasi kustom --}}
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</x-sidebar-layout>