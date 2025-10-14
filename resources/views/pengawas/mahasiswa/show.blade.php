{{-- resources/views/pengawas/mahasiswa/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Detail Mahasiswa</h2>
            <a href="{{ route('pengawas.mahasiswa.index') }}" class="text-unib-blue-600 hover:text-unib-blue-800">
                <i class="fas fa-arrow-left mr-1"></i>Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $kp->mahasiswa->name }} ({{ $kp->mahasiswa->npm }})</h3>
                    <p class="text-sm text-gray-600">{{ $kp->judul_kp }}</p>
                    <div class="mt-2">
                        @php
                            $displayStatus = $kp->status ?? 'pengajuan';
                            if ($kp->status === 'sedang_kp' && $kp->nilai_akhir && $kp->file_laporan) {
                                $displayStatus = 'selesai';
                            }
                        @endphp
                        @include('components.kp-status-badge',['status'=>$displayStatus])
                    </div>
                </div>
                <div class="text-sm text-gray-500">
                    <div>Mulai: {{ optional($kp->tanggal_mulai)->locale('id')->translatedFormat('d F Y') }}</div>
                    {{-- <div>Selesai: {{ optional($kp->tanggal_selesai)->format('d M Y') ?: '-' }}</div> --}}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <div class="text-center border rounded-lg p-4">
                    <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center {{ $kp->file_laporan ? 'bg-green-100 text-green-700':'bg-gray-100 text-gray-500' }}">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="mt-2 font-medium">Laporan</div>
                    <div class="text-sm text-gray-600">{{ $kp->file_laporan ? 'Sudah upload' : 'Belum upload' }}</div>
                </div>

                <div class="text-center border rounded-lg p-4">
                    <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center {{ $kp->acc_seminar ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        <i class="fas fa-microphone"></i>
                    </div>
                    <div class="mt-2 font-medium">Seminar</div>
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
                        <p class="text-xs text-gray-500 mt-1">Tanggal daftar: {{ $kp->tanggal_daftar_seminar->locale('id')->translatedFormat('d F Y') }}</p>
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

                <div class="text-center border rounded-lg p-4">
                    <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center {{ $kp->nilai_akhir ? 'bg-blue-100 text-blue-700':'bg-gray-100 text-gray-500' }}">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="mt-2 font-medium">Hasil Akhir</div>
                    <div class="text-sm text-gray-600">
                        @if($kp->nilai_akhir)
                            Nilai: {{ $kp->nilai_akhir }} — {{ $kp->lulus_ujian ? 'Lulus' : 'Tidak lulus' }}
                        @else
                            Belum Ada
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <h4 class="font-semibold text-gray-900 mb-2">Feedback Pembimbing Lapangan</h4>
                <form method="POST" action="{{ route('pengawas.mahasiswa.feedback', $kp) }}" class="space-y-3">
                    @csrf
                    <textarea name="catatan_pengawas" rows="4" class="w-full border-gray-300 rounded-md" placeholder="Tulis catatan/masukan...">{{ old('catatan_pengawas', $kp->catatan_pengawas) }}</textarea>
                    <button class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-4 py-2 rounded-md">
                        Simpan Feedback
                    </button>
                </form>
            </div>

            {{-- Penilaian Kerja --}}
            @if($kp->acc_pendaftaran_seminar && ($kp->acc_seminar || $kp->status === 'sedang_kp'))
                <div class="mt-6">
                    <h4 class="font-semibold text-gray-900 mb-2">Penilaian Kerja Mahasiswa</h4>
                    @if($kp->penilaian_pengawas)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                            <h5 class="font-medium text-green-800">Penilaian Sudah Disimpan</h5>
                            <p class="text-sm text-green-700 mt-1">Rata-rata: {{ $kp->rata_rata_pengawas }}</p>
                            <div class="mt-3 space-y-2">
                                @foreach($kp->penilaian_pengawas as $penilaian)
                                    <div class="flex justify-between text-sm">
                                        <span>{{ $penilaian['aspek'] }}</span>
                                        <span class="font-medium">{{ $penilaian['nilai'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <form method="POST" action="{{ route('pengawas.mahasiswa.penilaian-pengawas', $kp) }}" class="space-y-4">
                            @csrf
                            <div id="penilaianContainer">
                                {{-- Aspek penilaian --}}
                                <div class="penilaian-item grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Aspek Penilaian</label>
                                        <input type="text" name="penilaian_pengawas[0][aspek]" value="Displin kehadiran" readonly class="w-full border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nilai (0-100)</label>
                                        <input type="number" name="penilaian_pengawas[0][nilai]" min="0" max="100" required class="w-full border-gray-300 rounded-md nilai-input">
                                    </div>
                                </div>
                                <div class="penilaian-item grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Aspek Penilaian</label>
                                        <input type="text" name="penilaian_pengawas[1][aspek]" value="Tanggung jawab terhadap pekerjaan" readonly class="w-full border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nilai (0-100)</label>
                                        <input type="number" name="penilaian_pengawas[1][nilai]" min="0" max="100" required class="w-full border-gray-300 rounded-md nilai-input">
                                    </div>
                                </div>
                                <div class="penilaian-item grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Aspek Penilaian</label>
                                        <input type="text" name="penilaian_pengawas[2][aspek]" value="Etika dan komunikasi" readonly class="w-full border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nilai (0-100)</label>
                                        <input type="number" name="penilaian_pengawas[2][nilai]" min="0" max="100" required class="w-full border-gray-300 rounded-md nilai-input">
                                    </div>
                                </div>
                                <div class="penilaian-item grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Aspek Penilaian</label>
                                        <input type="text" name="penilaian_pengawas[3][aspek]" value="Kemampuan kerja sama" readonly class="w-full border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nilai (0-100)</label>
                                        <input type="number" name="penilaian_pengawas[3][nilai]" min="0" max="100" required class="w-full border-gray-300 rounded-md nilai-input">
                                    </div>
                                </div>
                                <div class="penilaian-item grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Aspek Penilaian</label>
                                        <input type="text" name="penilaian_pengawas[4][aspek]" value="Inisiatif dan kreativitas" readonly class="w-full border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nilai (0-100)</label>
                                        <input type="number" name="penilaian_pengawas[4][nilai]" min="0" max="100" required class="w-full border-gray-300 rounded-md nilai-input">
                                    </div>
                                </div>
                                <div class="penilaian-item grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Aspek Penilaian</label>
                                        <input type="text" name="penilaian_pengawas[5][aspek]" value="Penguasaan materi dan tugas kerja" readonly class="w-full border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nilai (0-100)</label>
                                        <input type="number" name="penilaian_pengawas[5][nilai]" min="0" max="100" required class="w-full border-gray-300 rounded-md nilai-input">
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Rata-rata Nilai</label>
                                        <p class="text-xs text-gray-500">Otomatis dihitung dari semua aspek</p>
                                    </div>
                                    <div class="text-right">
                                        <span id="rataRataDisplay" class="text-2xl font-bold text-gray-900">0.00</span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-4 py-2 rounded-md">
                                Simpan Penilaian Kerja
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>

        <!-- Activity Kegiatan -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Kegiatan Terbaru</h3>
            </div>
            <div class="p-6">
                @if($kp->mahasiswa->kegiatan->count() > 0)
                    <div class="space-y-3">
                        @foreach($kp->mahasiswa->kegiatan as $kegiatan)
                            <div class="flex items-start space-x-3 py-3 border-b border-gray-100 last:border-0">
                                <div class="bg-purple-100 rounded-full p-2 mt-1">
                                    <i class="fas fa-tasks text-purple-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-gray-900">{{ Str::limit($kegiatan->deskripsi_kegiatan, 100) }}</p>
                                    <p class="text-sm text-gray-600">
                                        {{ $kegiatan->tanggal_kegiatan->locale('id')->translatedFormat('d F Y') }} • {{ $kegiatan->durasi_jam }} jam
                                    </p>
                                </div>
                                @if($kegiatan->file_dokumentasi)
                                    <a href="{{ Storage::url($kegiatan->file_dokumentasi) }}" target="_blank"
                                       class="text-unib-blue-600 hover:text-unib-blue-800 text-sm">
                                        <i class="fas fa-paperclip"></i>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-tasks text-4xl text-gray-300 mb-4"></i>
                        <p>Belum ada kegiatan tercatat</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        let currentKpId = null;
        let penilaianIndex = 1;

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
</x-app-layout>
