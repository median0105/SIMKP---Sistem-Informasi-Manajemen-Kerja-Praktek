{{-- resources/views/pengawas/mahasiswa/show.blade.php --}}
<x-sidebar-layout>
    {{-- Header section with UNIB blue background --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <a href="{{ route('pengawas.mahasiswa.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg inline-flex items-center transition duration-200 backdrop-blur-sm border border-white/30">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                    <!-- Ikon dihapus -->
                </div>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Detail Mahasiswa
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Main Info Card --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $kp->mahasiswa->name }}</h3>
                            <p class="text-sm text-gray-600 mt-1">NPM: {{ $kp->mahasiswa->npm }}</p>
                            <p class="text-gray-700 mt-2">{{ $kp->judul_kp }}</p>
                            <div class="mt-3">
                                @php
                                    $displayStatus = $kp->status ?? 'pengajuan';
                                    if ($kp->status === 'sedang_kp' && $kp->nilai_akhir && $kp->file_laporan) {
                                        $displayStatus = 'selesai';
                                    }
                                @endphp
                                @include('components.kp-status-badge',['status'=>$displayStatus])
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 text-right">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-unib-blue-400"></i>
                                Mulai: {{ $kp->tanggal_mulai ? $kp->tanggal_mulai->locale('id')->translatedFormat('d F Y') : '-' }}
                            </div>
                        </div>
                    </div>

                    {{-- Status Indicators --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                        <div class="text-center border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-150">
                            <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center {{ $kp->file_laporan ? 'bg-green-100 text-green-700 border border-green-200':'bg-gray-100 text-gray-500 border border-gray-200' }}">
                                <!-- Ikon dihapus -->
                            </div>
                            <div class="mt-3 font-medium text-gray-900">Laporan</div>
                            <div class="text-sm text-gray-600">{{ $kp->file_laporan ? 'Sudah upload' : 'Belum upload' }}</div>
                        </div>

                        <div class="text-center border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-150">
                            <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center {{ $kp->acc_seminar ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-gray-100 text-gray-500 border border-gray-200' }}">
                                <!-- Ikon dihapus -->
                            </div>
                            <div class="mt-3 font-medium text-gray-900">Seminar</div>
                            <div class="text-sm text-gray-600">
                                @if(!$kp->pendaftaran_seminar)
                                    Belum mendaftar seminar
                                @elseif($kp->pendaftaran_seminar && !$kp->acc_pendaftaran_seminar)
                                    Menunggu ACC pendaftaran seminar
                                @elseif($kp->acc_pendaftaran_seminar && !$kp->acc_seminar)
                                    Menunggu ACC seminar
                                @elseif($kp->acc_seminar)
                                    Seminar disetujui
                                @endif
                            </div>
                            @if($kp->tanggal_daftar_seminar)
                                <p class="text-xs text-gray-500 mt-2">Tanggal daftar: {{ $kp->tanggal_daftar_seminar->locale('id')->translatedFormat('d F Y') }}</p>
                            @endif
                            @if($kp->jadwal_seminar)
                                <p class="text-xs text-gray-500 mt-1">
                                    Jadwal: {{ $kp->jadwal_seminar->locale('id')->translatedFormat('d F Y \p\u\k\u\l H:i') }} WIB
                                </p>
                            @endif
                            @if($kp->ruangan_seminar)
                                <p class="text-xs text-gray-500">{{ $kp->ruangan_seminar }}</p>
                            @endif
                        </div>

                        <div class="text-center border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-150">
                            <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center {{ $kp->nilai_akhir ? 'bg-blue-100 text-blue-700 border border-blue-200':'bg-gray-100 text-gray-500 border border-gray-200' }}">
                                <!-- Ikon dihapus -->
                            </div>
                            <div class="mt-3 font-medium text-gray-900">Hasil Akhir</div>
                            <div class="text-sm text-gray-600">
                                @if($kp->nilai_akhir)
                                    Nilai: {{ $kp->nilai_akhir }} — {{ $kp->lulus_ujian ? 'Lulus' : 'Tidak lulus' }}
                                @else
                                    Belum Ada
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Feedback Form --}}
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-base font-semibold text-gray-900 mb-3">Feedback Pembimbing Lapangan</h4>
                        <form method="POST" action="{{ route('pengawas.mahasiswa.feedback', $kp) }}" class="space-y-4">
                            @csrf
                            <textarea name="catatan_pengawas" rows="4" 
                                      class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                      placeholder="Tulis catatan/masukan...">{{ old('catatan_pengawas', $kp->catatan_pengawas) }}</textarea>
                            <button class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium transition duration-200 flex items-center">
                                <i class="fas fa-save mr-2"></i>Simpan Feedback
                            </button>
                        </form>
                    </div>

                    {{-- Penilaian Kerja --}}
                    @if($kp->acc_pendaftaran_seminar && ($kp->acc_seminar || $kp->status === 'sedang_kp'))
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-base font-semibold text-gray-900 mb-4">Penilaian Kerja Mahasiswa</h4>
                            @if($kp->penilaian_pengawas)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                        <h5 class="font-medium text-green-800">Penilaian Sudah Disimpan</h5>
                                    </div>
                                    <p class="text-sm text-green-700 mt-2">Rata-rata: <span class="font-bold">{{ $kp->rata_rata_pengawas }}</span></p>
                                    <div class="mt-3 space-y-2">
                                        @foreach($kp->penilaian_pengawas as $penilaian)
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-700">{{ $penilaian['aspek'] }}</span>
                                                <span class="font-medium text-gray-900">{{ $penilaian['nilai'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <form method="POST" action="{{ route('pengawas.mahasiswa.penilaian-pengawas', $kp) }}" class="space-y-4">
                                    @csrf
                                    <div id="penilaianContainer" class="space-y-4">
                                        {{-- Aspek penilaian --}}
                                        @foreach([
                                            'Displin kehadiran',
                                            'Tanggung jawab terhadap pekerjaan', 
                                            'Etika dan komunikasi',
                                            'Kemampuan kerja sama',
                                            'Inisiatif dan kreativitas',
                                            'Penguasaan materi dan tugas kerja'
                                        ] as $index => $aspek)
                                            <div class="penilaian-item grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Aspek Penilaian</label>
                                                    <input type="text" name="penilaian_pengawas[{{ $index }}][aspek]" value="{{ $aspek }}" readonly 
                                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200 bg-white">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nilai (0-100)</label>
                                                    <input type="number" name="penilaian_pengawas[{{ $index }}][nilai]" min="0" max="100" required 
                                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200 nilai-input">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="bg-unib-blue-50 border border-unib-blue-200 rounded-lg p-4">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <label class="block text-sm font-medium text-unib-blue-700">Rata-rata Nilai</label>
                                                <p class="text-xs text-unib-blue-600 mt-1">Otomatis dihitung dari semua aspek</p>
                                            </div>
                                            <div class="text-right">
                                                <span id="rataRataDisplay" class="text-2xl font-bold text-unib-blue-700">0.00</span>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium transition duration-200 flex items-center">
                                        <i class="fas fa-save mr-2"></i>Simpan Penilaian Kerja
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Activity Kegiatan Card --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                    <h3 class="text-base font-semibold text-unib-blue-800">
                        Kegiatan Terbaru
                    </h3>
                </div>
                <div class="p-6">
                    @if($kp->mahasiswa->kegiatan->count() > 0)
                        <div class="space-y-4">
                            @foreach($kp->mahasiswa->kegiatan as $kegiatan)
                                <div class="flex items-start space-x-4 py-3 border-b border-gray-100 last:border-0 hover:bg-gray-50 rounded-lg px-3 transition duration-150">
                                    <div class="bg-purple-100 rounded-full p-3 mt-1 border border-purple-200">
                                        <!-- Ikon dihapus -->
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-gray-900 font-medium">{{ Str::limit($kegiatan->deskripsi_kegiatan, 100) }}</p>
                                        <p class="text-sm text-gray-600 mt-1 flex items-center">
                                            <i class="fas fa-calendar mr-2 text-xs text-unib-blue-400"></i>
                                            {{ $kegiatan->tanggal_kegiatan->locale('id')->translatedFormat('d F Y') }} • {{ $kegiatan->durasi_jam }} jam
                                        </p>
                                    </div>
                                    @if($kegiatan->file_dokumentasi)
                                        <a href="{{ Storage::url($kegiatan->file_dokumentasi) }}" target="_blank"
                                           class="text-unib-blue-600 hover:text-unib-blue-800 text-sm flex items-center p-2 rounded-lg hover:bg-unib-blue-50 transition duration-200"
                                           title="Lihat Dokumentasi">
                                            <i class="fas fa-paperclip"></i>
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-tasks text-4xl mb-4 text-gray-300"></i>
                                <div class="text-base font-medium text-gray-900 mb-2">Belum Ada Kegiatan</div>
                                <p class="text-sm text-gray-600">Belum ada kegiatan tercatat</p>
                            </div>
                        </div>
                    @endif
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

    {{-- Scripts --}}
    <script>
        // Fungsi untuk menghitung rata-rata penilaian pengawas
        function calculateRataRata() {
            const nilaiInputs = document.querySelectorAll('.nilai-input');
            let total = 0;
            let count = 0;

            nilaiInputs.forEach(input => {
                const value = parseFloat(input.value);
                if (!isNaN(value)) {
                    total += value;
                    count++;
                }
            });

            const average = count > 0 ? (total / count).toFixed(2) : '0.00';
            document.getElementById('rataRataDisplay').textContent = average;
        }

        // Attach event listeners to nilai inputs
        document.addEventListener('DOMContentLoaded', function() {
            const nilaiInputs = document.querySelectorAll('.nilai-input');
            nilaiInputs.forEach(input => {
                input.addEventListener('input', calculateRataRata);
            });
            calculateRataRata(); // Initial calculation
        });
    </script>
</x-sidebar-layout>