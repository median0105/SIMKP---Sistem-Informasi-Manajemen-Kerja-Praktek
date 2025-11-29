{{-- resources/views/admin/bimbingan/show.blade.php --}}
<x-sidebar-layout>
    {{-- Header section dengan UNIB blue gradient --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.bimbingan.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg inline-flex items-center backdrop-blur-sm transition duration-200 border border-white/30">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                    <!-- Ikon dihapus sesuai pattern -->
                </div>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Riwayat Bimbingan - {{ $mahasiswa->name }}
                    </h2>
                    <p class="text-blue-100 text-sm mt-1">Kelola riwayat bimbingan mahasiswa</p>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Main content area dengan gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Flash messages dengan style yang konsisten --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-green-800 transform transition-all duration-300 animate-fade-in-up">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-red-800 transform transition-all duration-300 animate-fade-in-up">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            {{-- Tombol Verifikasi Semua --}}
            <div class="flex space-x-2">
                <form method="POST" action="{{ route('admin.bimbingan.verify-all', $mahasiswa) }}">
                    @csrf
                    <button type="submit"
                            class="px-5 py-2.5 rounded-lg text-white bg-green-600 hover:bg-green-700 font-medium shadow-md transform hover:scale-105 transition duration-200 inline-flex items-center">
                        <i class="fas fa-check-double mr-2"></i>Verifikasi Semua
                    </button>
                </form>
            </div>

            {{-- Info Mahasiswa --}}
            <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white">
                    <h3 class="text-lg font-semibold">Informasi Mahasiswa</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-600 mb-1">Nama</dt>
                            <dd class="text-gray-900 font-semibold text-base">{{ $mahasiswa->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-600 mb-1">NPM</dt>
                            <dd class="text-gray-900 font-semibold text-base">{{ $mahasiswa->npm }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-600 mb-1">Tempat KP</dt>
                            <dd class="text-gray-900 font-semibold text-base">
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
            </div>

            {{-- Daftar Bimbingan --}}
            <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Riwayat Bimbingan</h3>
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm">
                        Total: {{ $mahasiswa->bimbingan->count() }}
                    </div>
                </div>

                @if($mahasiswa->bimbingan->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider">Topik</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider">Catatan</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-unib-blue-800 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold text-unib-blue-800 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($mahasiswa->bimbingan as $bimbingan)
                                    <tr class="hover:bg-unib-blue-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $bimbingan->tanggal_bimbingan->locale('id')->translatedFormat('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $bimbingan->tanggal_bimbingan->format('H:i') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $bimbingan->topik_bimbingan }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 max-w-xs">
                                                {{ \Illuminate\Support\Str::limit($bimbingan->catatan_dosen ?: $bimbingan->catatan_mahasiswa ?: '-', 80) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($bimbingan->status_verifikasi)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-300 shadow-sm">
                                                    <i class="fas fa-check mr-1"></i>Terverifikasi
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-300 shadow-sm">
                                                    <i class="fas fa-clock mr-1"></i>Menunggu
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if(!$bimbingan->status_verifikasi)
                                                <form method="POST" action="{{ route('admin.bimbingan.verify', $bimbingan) }}" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                            class="text-green-600 hover:text-green-800 font-medium text-sm flex items-center justify-center px-3 py-2 rounded-lg hover:bg-green-100 transition duration-200">
                                                        <i class="fas fa-check mr-2"></i>Verifikasi
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 text-sm">Sudah diverifikasi</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    {{-- Empty state dengan animasi Lottie --}}
                    <div class="text-center py-16 text-gray-500">
                        <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>
                        <dotlottie-wc src="https://lottie.host/f4be40d9-971a-446d-9d51-ed1128f637ef/8YGejDufWD.lottie" style="width: 300px;height: 300px; margin: 0 auto;" autoplay loop></dotlottie-wc>
                        <div class="text-lg font-medium text-gray-900 mb-2">Belum Ada Bimbingan</div>
                        <p class="text-base text-gray-600 max-w-md mx-auto">
                            Mahasiswa ini belum memiliki riwayat bimbingan.
                        </p>
                    </div>
                @endif
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