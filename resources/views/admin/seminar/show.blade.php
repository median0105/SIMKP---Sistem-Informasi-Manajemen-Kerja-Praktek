{{-- resources/views/admin/seminar/show.blade.php --}}
<x-sidebar-layout>
    {{-- Header section dengan UNIB blue gradient --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.seminar.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg inline-flex items-center backdrop-blur-sm transition duration-200 border border-white/30">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Detail Kerja Praktek - {{ $kerjaPraktek->mahasiswa->name }} (Penguji)
                    </h2>
                    <p class="text-blue-100 text-sm mt-1">
                        {{ $kerjaPraktek->mahasiswa->npm }} - Kelola seminar dan penilaian
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Alerts --}}
            @if(!empty($isDuplicate))
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 transform transition-all duration-300 animate-fade-in-up">
                    <div class="flex">
                        <i class="fas fa-exclamation-triangle text-red-400 mt-1"></i>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Peringatan: Judul Duplikat</h3>
                            <p class="mt-1 text-sm text-red-700">
                                Judul KP ini sudah pernah digunakan sebelumnya. Pertimbangkan untuk meminta mahasiswa mengubah judul.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if(!empty($needsResponsi))
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 transform transition-all duration-300 animate-fade-in-up">
                    <div class="flex">
                        <i class="fas fa-clock text-yellow-400 mt-1"></i>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Perlu Responsi</h3>
                            <p class="mt-1 text-sm text-yellow-700">
                                KP ini sudah berjalan lebih dari 1 tahun. Mahasiswa perlu responsi.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Info KP --}}
            <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white rounded-t-xl">
                    <h3 class="text-lg font-semibold">Informasi Kerja Praktek</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Mahasiswa</label>
                                <p class="text-gray-900 mt-1 font-medium">{{ $kerjaPraktek->mahasiswa->name }} ({{ $kerjaPraktek->mahasiswa->npm }})</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Judul KP</label>
                                <p class="text-gray-900 mt-1 font-medium">{{ $kerjaPraktek->judul_kp }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Tempat Magang</label>
                                <p class="text-gray-900 mt-1 font-medium">
                                    @if($kerjaPraktek->pilihan_tempat == 3)
                                        {{ $kerjaPraktek->tempat_magang_sendiri }}
                                        <br><small class="text-gray-500 text-sm">{{ $kerjaPraktek->alamat_tempat_sendiri }}</small>
                                    @else
                                        {{ $kerjaPraktek->tempatMagang->nama_perusahaan ?? '-' }}
                                        <br><small class="text-gray-500 text-sm">{{ $kerjaPraktek->tempatMagang->alamat ?? '' }}</small>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Status</label>
                                <div class="mt-1">
                                    @php
                                        $displayStatus = $kerjaPraktek->status;
                                        if ($kerjaPraktek->status === 'sedang_kp' && $kerjaPraktek->nilai_akhir && $kerjaPraktek->file_laporan) {
                                            $displayStatus = 'selesai';
                                        }
                                    @endphp
                                    @switch($displayStatus)
                                        @case('pengajuan')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-300">
                                                <i class="fas fa-clock mr-2"></i>Menunggu Persetujuan
                                            </span>
                                            @break
                                        @case('disetujui')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-unib-blue-100 text-unib-blue-800 border border-unib-blue-300">
                                                <i class="fas fa-check-circle mr-2"></i>Disetujui
                                            </span>
                                            @break
                                        @case('sedang_kp')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-teknik-orange-100 text-teknik-orange-800 border border-teknik-orange-300">
                                                <i class="fas fa-play-circle mr-2"></i>Sedang KP
                                            </span>
                                            @break
                                        @case('selesai')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-300">
                                                <i class="fas fa-flag-checkered mr-2"></i>Selesai
                                            </span>
                                            @break
                                        @case('ditolak')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-300">
                                                <i class="fas fa-times-circle mr-2"></i>Ditolak
                                            </span>
                                            @break
                                    @endswitch
                                </div>
                            </div>
                            @if($kerjaPraktek->tanggal_mulai)
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Tanggal Mulai</label>
                                    <p class="text-gray-900 mt-1 font-medium">{{ $kerjaPraktek->tanggal_mulai->locale('id')->translatedFormat('d F Y') }}</p>
                                </div>
                            @endif
                            @if($kerjaPraktek->tanggal_selesai)
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Tanggal Selesai</label>
                                    <p class="text-gray-900 mt-1 font-medium">{{ $kerjaPraktek->tanggal_selesai->locale('id')->translatedFormat('d F Y') }}</p>
                                </div>
                            @endif

                            {{-- File attachments --}}
                            <div class="space-y-3">
                                @if($kerjaPraktek->file_krs)
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Kartu Rencana Studi (KRS)</label>
                                        <div class="mt-1">
                                            <a href="{{ Storage::url($kerjaPraktek->file_krs) }}" target="_blank" 
                                               class="text-unib-blue-600 hover:text-unib-blue-800 text-sm font-medium flex items-center">
                                                <i class="fas fa-file-pdf text-red-500 mr-2"></i> Lihat KRS
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @if($kerjaPraktek->file_proposal)
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Proposal Kerja Praktek</label>
                                        <div class="mt-1">
                                            <a href="{{ Storage::url($kerjaPraktek->file_proposal) }}" target="_blank"
                                               class="text-purple-600 hover:text-purple-800 text-sm font-medium flex items-center">
                                                <i class="fas fa-file-pdf text-red-500 mr-2"></i> Lihat Proposal
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @if($kerjaPraktek->file_revisi)
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Revisi Laporan Kerja Praktek</label>
                                        <div class="mt-1">
                                            <a href="{{ Storage::url($kerjaPraktek->file_revisi) }}" target="_blank"
                                               class="text-green-600 hover:text-green-800 text-sm font-medium flex items-center">
                                                <i class="fas fa-file-pdf text-red-500 mr-2"></i> Lihat Revisi Laporan
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Actions for Penguji --}}
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex flex-wrap gap-3">
                            {{-- ACC Pendaftaran Seminar --}}
                            @if($kerjaPraktek->pendaftaran_seminar && !$kerjaPraktek->acc_pendaftaran_seminar)
                                <button onclick="openAccSeminar({{ $kerjaPraktek->id }})"
                                        class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center">
                                    <i class="fas fa-calendar-check mr-2"></i>ACC Pendaftaran Seminar
                                </button>
                                <button onclick="openTolakSeminar({{ $kerjaPraktek->id }})"
                                        class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center">
                                    <i class="fas fa-times mr-2"></i>Tolak Pendaftaran Seminar
                                </button>
                            @elseif($kerjaPraktek->acc_pendaftaran_seminar && !$kerjaPraktek->acc_seminar)
                                <button onclick="accSeminar({{ $kerjaPraktek->id }})"
                                        class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-lg font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center">
                                    <i class="fas fa-microphone mr-2"></i>ACC Seminar
                                </button>
                            @elseif($kerjaPraktek->acc_seminar)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 border border-purple-300 shadow-sm">
                                    <i class="fas fa-microphone mr-2"></i>Seminar Sudah Di-ACC
                                </span>
                            @endif

                            {{-- Input Nilai Seminar --}}
                            @if($kerjaPraktek->canTakeExam() && $kerjaPraktek->acc_seminar && !$kerjaPraktek->lulus_ujian && !$kerjaPraktek->nilai_akhir && !$kerjaPraktek->rata_rata_seminar)
                                <button onclick="openNilai({{ $kerjaPraktek->id }})"
                                        class="bg-teknik-orange-500 hover:bg-teknik-orange-600 text-white px-5 py-2.5 rounded-lg font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center">
                                    <i class="fas fa-edit mr-2"></i>Input Nilai Seminar
                                </button>
                            @elseif($kerjaPraktek->rata_rata_seminar)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800 border border-orange-300 shadow-sm">
                                    <i class="fas fa-edit mr-2"></i>Nilai Seminar Sudah Diinput
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Progress --}}
            @if(in_array($kerjaPraktek->status, ['sedang_kp','selesai']))
                <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                    <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white rounded-t-xl">
                        <h3 class="text-lg font-semibold">Progress Seminar & Ujian</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            {{-- Laporan --}}
                            <div class="text-center p-4 bg-unib-blue-50 rounded-xl border border-unib-blue-100">
                                <div class="mx-auto w-12 h-12 rounded-full flex items-center justify-center mb-3
                                    {{ $kerjaPraktek->file_laporan ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                                    <i class="fas fa-file-alt text-lg"></i>
                                </div>
                                <h4 class="font-medium text-gray-900">Upload Laporan</h4>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $kerjaPraktek->file_laporan ? 'Sudah Upload' : 'Belum Upload' }}
                                </p>
                                @if($kerjaPraktek->file_laporan)
                                    <a href="{{ Storage::url($kerjaPraktek->file_laporan) }}" target="_blank"
                                       class="text-unib-blue-600 hover:text-unib-blue-800 text-sm mt-2 inline-block font-medium">
                                        <i class="fas fa-download mr-1"></i>Download
                                    </a>
                                @endif
                            </div>

                            {{-- Pendaftaran Seminar --}}
                            <div class="text-center p-4 bg-unib-blue-50 rounded-xl border border-unib-blue-100">
                                <div class="mx-auto w-12 h-12 rounded-full flex items-center justify-center mb-3
                                    {{ $kerjaPraktek->pendaftaran_seminar ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                                    <i class="fas fa-calendar-plus text-lg"></i>
                                </div>
                                <h4 class="font-medium text-gray-900">Pendaftaran Seminar</h4>
                                <p class="text-sm text-gray-600 mt-1">
                                    @if($kerjaPraktek->pendaftaran_seminar)
                                        @if($kerjaPraktek->acc_pendaftaran_seminar)
                                            Sudah ACC
                                        @else
                                            Menunggu ACC
                                        @endif
                                    @else
                                        Belum Daftar
                                    @endif
                                </p>
                                @if($kerjaPraktek->tanggal_daftar_seminar)
                                    <p class="text-xs text-gray-500 mt-1">{{ $kerjaPraktek->tanggal_daftar_seminar->locale('id')->translatedFormat('d F Y \p\u\k\u\l H:i') }} WIB</p>
                                @endif
                            </div>

                            {{-- ACC Seminar --}}
                            <div class="text-center p-4 bg-unib-blue-50 rounded-xl border border-unib-blue-100">
                                <div class="mx-auto w-12 h-12 rounded-full flex items-center justify-center mb-3
                                    {{ $kerjaPraktek->acc_seminar ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                                    <i class="fas fa-microphone text-lg"></i>
                                </div>
                                <h4 class="font-medium text-gray-900">ACC Seminar</h4>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $kerjaPraktek->acc_seminar ? 'Sudah ACC' : 'Belum ACC' }}
                                </p>
                                @if($kerjaPraktek->jadwal_seminar)
                                    <p class="text-xs text-gray-500 mt-1">{{ $kerjaPraktek->jadwal_seminar->locale('id')->translatedFormat('d F Y \p\u\k\u\l H:i') }} WIB</p>
                                    @if($kerjaPraktek->ruangan_seminar)
                                        <p class="text-xs text-gray-500">{{ $kerjaPraktek->ruangan_seminar }}</p>
                                    @endif
                                @elseif($kerjaPraktek->tanggal_seminar)
                                    <p class="text-xs text-gray-500 mt-1">{{ $kerjaPraktek->tanggal_seminar->locale('id')->translatedFormat('d F Y') }}</p>
                                @endif
                            </div>

                            {{-- Ujian --}}
                            <div class="text-center p-4 bg-unib-blue-50 rounded-xl border border-unib-blue-100">
                                <div class="mx-auto w-12 h-12 rounded-full flex items-center justify-center mb-3
                                    {{ $kerjaPraktek->lulus_ujian ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                                    <i class="fas fa-graduation-cap text-lg"></i>
                                </div>
                                <h4 class="font-medium text-gray-900">Hasil Akhir</h4>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $kerjaPraktek->lulus_ujian ? 'Lulus' : ($kerjaPraktek->nilai_akhir ? 'Tidak Lulus' : 'Belum Ada') }}
                                </p>
                                @if($kerjaPraktek->nilai_akhir)
                                    <p class="text-xs text-gray-500 mt-1">Nilai: {{ $kerjaPraktek->nilai_akhir }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- IPK Semester --}}
            <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white rounded-t-xl">
                    <h3 class="text-lg font-semibold">Akademik Semester</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-600">Semester Ke</p>
                            <p class="font-medium text-gray-900">{{ $kerjaPraktek->semester_ke ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">IPK Semester Ini</p>
                            <p class="font-medium text-gray-900">
                                {{ $kerjaPraktek->ipk_semester ? number_format($kerjaPraktek->ipk_semester, 2) : '-' }}
                            </p>
                        </div>
                        <div>
                            @php
                                $ipk = (float) ($kerjaPraktek->ipk_semester ?? 0);
                                $badge = 'bg-gray-100 text-gray-800'; $label = '-';
                                if ($kerjaPraktek->ipk_semester !== null) {
                                    if ($ipk >= 3.50) { $badge = 'bg-green-100 text-green-800'; $label = 'Sangat Baik'; }
                                    elseif ($ipk >= 3.00) { $badge = 'bg-blue-100 text-blue-800'; $label = 'Baik'; }
                                    elseif ($ipk >= 2.50) { $badge = 'bg-yellow-100 text-yellow-800'; $label = 'Cukup'; }
                                    else { $badge = 'bg-red-100 text-red-800'; $label = 'Perlu Perhatian'; }
                                }
                            @endphp
                            <p class="text-sm text-gray-600">Status</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                                {{ $label }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Penilaian --}}
            @if($kerjaPraktek->nilai_akhir)
                <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                    <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white rounded-t-xl">
                        <h3 class="text-lg font-semibold">Detail Penilaian</h3>
                    </div>
                    <div class="p-6">
                        @if($kerjaPraktek->penilaian_detail)
                            <div class="overflow-x-auto mb-6">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Indikator Penilaian</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @foreach($kerjaPraktek->penilaian_detail as $penilaian)
                                            <tr class="hover:bg-unib-blue-50 transition-colors duration-200">
                                                <td class="px-4 py-2 text-gray-900">{{ $penilaian['indikator'] }}</td>
                                                <td class="px-4 py-2 text-gray-900 font-semibold">{{ $penilaian['nilai'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Nilai Akhir</label>
                                <p class="text-2xl font-bold {{ $kerjaPraktek->lulus_ujian ? 'text-green-600' : 'text-red-600' }} mt-1">
                                    {{ $kerjaPraktek->nilai_akhir }}
                                </p>
                                <p class="text-sm {{ $kerjaPraktek->lulus_ujian ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $kerjaPraktek->lulus_ujian ? 'LULUS' : 'TIDAK LULUS' }}
                                </p>
                            </div>
                            @if($kerjaPraktek->keterangan_penilaian)
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Keterangan</label>
                                    <p class="text-gray-900 mt-1">{{ $kerjaPraktek->keterangan_penilaian }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Riwayat Bimbingan --}}
            @if($kerjaPraktek->bimbingan && $kerjaPraktek->bimbingan->count() > 0)
                <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                    <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white rounded-t-xl">
                        <h3 class="text-lg font-semibold">Riwayat Bimbingan ({{ $kerjaPraktek->bimbingan->count() }})</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($kerjaPraktek->bimbingan->take(5) as $bimbingan)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-medium text-gray-900">{{ $bimbingan->topik_bimbingan }}</h4>
                                        @if($bimbingan->status_verifikasi)
                                            <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full border border-green-300">Terverifikasi</span>
                                        @else
                                            <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full border border-yellow-300">Tertunda</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">{{ $bimbingan->tanggal_bimbingan->locale('id')->translatedFormat('d F Y') }}</p>
                                    <p class="text-gray-900">{{ \Illuminate\Support\Str::limit($bimbingan->catatan_mahasiswa, 100) }}</p>
                                    @if($bimbingan->catatan_dosen)
                                        <div class="mt-3 p-3 bg-unib-blue-50 rounded-lg border border-unib-blue-200">
                                            <p class="text-sm font-medium text-unib-blue-700">Feedback Dosen:</p>
                                            <p class="text-sm text-gray-900 mt-1">{{ $bimbingan->catatan_dosen }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- Modal ACC Pendaftaran Seminar --}}
    <div id="accSeminarModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 border border-unib-blue-200">
            <form id="accSeminarForm" method="POST">
                @csrf
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 rounded-full p-3 mr-4">
                            <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">ACC Pendaftaran Seminar</h3>
                    </div>

                    <div class="space-y-4">
                        {{-- Jadwal Seminar --}}
                        <div>
                            <label class="block text-base font-medium text-gray-700 mb-2">Jadwal Seminar *</label>
                            <input
                                type="text"
                                id="jadwal_seminar"
                                name="jadwal_seminar"
                                required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                placeholder="Pilih tanggal dan waktu"
                            >
                            <p class="text-xs text-gray-500 mt-1">Pilih tanggal dan waktu seminar (format 24 jam)</p>
                        </div>

                        {{-- Ruangan Seminar --}}
                        <div>
                            <label class="block text-base font-medium text-gray-700 mb-2">Ruangan Seminar *</label>
                            <input type="text" name="ruangan_seminar" required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                placeholder="Contoh: Ruang Seminar 101">
                        </div>

                        {{-- Catatan Seminar --}}
                        <div>
                            <label class="block text-base font-medium text-gray-700 mb-2">Catatan Seminar</label>
                            <textarea name="catatan_seminar" rows="3"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                    placeholder="Catatan tambahan untuk mahasiswa (opsional)"></textarea>
                        </div>
                    </div>

                    <div class="flex space-x-4 mt-6">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex-1 flex items-center justify-center">
                            <i class="fas fa-check mr-2"></i>ACC & Tetapkan Jadwal
                        </button>
                        <button type="button" onclick="closeModal('accSeminarModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex-1 flex items-center justify-center">
                            Batal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Tolak Pendaftaran Seminar --}}
    <div id="tolakSeminarModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 border border-unib-blue-200">
            <form id="tolakSeminarForm" method="POST">
                @csrf
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-red-100 rounded-full p-3 mr-4">
                            <i class="fas fa-times text-red-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Tolak Pendaftaran Seminar</h3>
                    </div>
                    <div class="mb-6">
                        <label class="block text-base font-medium text-gray-700 mb-3">Alasan Penolakan *</label>
                        <textarea name="catatan_seminar" rows="4" required
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                  placeholder="Berikan alasan penolakan pendaftaran seminar..."></textarea>
                    </div>
                    <div class="flex space-x-4">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex-1 flex items-center justify-center">
                            Tolak Pendaftaran
                        </button>
                        <button type="button" onclick="closeModal('tolakSeminarModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex-1 flex items-center justify-center">
                            Batal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Input Nilai --}}
    <div id="nilaiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-screen overflow-y-auto transform transition-all duration-300 scale-95 border border-unib-blue-200">
            <form id="nilaiForm" method="POST" action="{{ route('admin.seminar.input-nilai', $kerjaPraktek) }}">
                @csrf
                <div class="p-6">
                    <div class="flex items-center justify-center mb-6">
                        <div class="bg-teknik-orange-100 rounded-full p-4 mr-4">
                            <i class="fas fa-edit text-teknik-orange-600 text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">Input Penilaian Seminar Kerja Praktek</h3>
                    </div>

                    <div id="penilaianContainer" class="space-y-6 mb-6">
                        {{-- Lembar Penilaian Seminar Kerja Praktek --}}
                        <div class="border-b border-gray-200 pb-4">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Penilaian Seminar Kerja Praktek</h4>
                            <div class="space-y-3">
                                <div class="penilaian-item grid grid-cols-3 gap-4">
                                    <div class="col-span-2">
                                        <input type="text" name="penilaian_detail[0][indikator]"
                                               value="Laporan Kerja Praktek" readonly
                                               class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-50 px-4 py-3 text-base">
                                    </div>
                                    <div>
                                        <input type="number" name="penilaian_detail[0][nilai]"
                                               placeholder="Nilai (0-100)" min="0" max="100" required
                                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base nilai-input">
                                    </div>
                                </div>
                                <div class="penilaian-item grid grid-cols-3 gap-4">
                                    <div class="col-span-2">
                                        <input type="text" name="penilaian_detail[1][indikator]"
                                               value="Presentasi dan Pemahaman Materi" readonly
                                               class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-50 px-4 py-3 text-base">
                                    </div>
                                    <div>
                                        <input type="number" name="penilaian_detail[1][nilai]"
                                               placeholder="Nilai (0-100)" min="0" max="100" required
                                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base nilai-input">
                                    </div>
                                </div>
                                <div class="penilaian-item grid grid-cols-3 gap-4">
                                    <div class="col-span-2">
                                        <input type="text" name="penilaian_detail[2][indikator]"
                                               value="Sikap dan Etika" readonly
                                               class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-50 px-4 py-3 text-base">
                                    </div>
                                    <div>
                                        <input type="number" name="penilaian_detail[2][nilai]"
                                               placeholder="Nilai (0-100)" min="0" max="100" required
                                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base nilai-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Rata-rata Seminar --}}
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <label class="block text-base font-medium text-purple-700">Rata-rata Penilaian Seminar</label>
                                <p class="text-xs text-purple-600">Rata-rata dari 3 aspek seminar (Laporan, Presentasi, Sikap)</p>
                            </div>
                            <div class="text-right">
                                <span id="rataSeminarDisplay" class="text-2xl font-bold text-purple-900">{{ $kerjaPraktek->rata_rata_seminar ?: '0.00' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-base font-medium text-gray-700 mb-2">Keterangan Penilaian</label>
                        <textarea name="keterangan_penilaian" rows="4" required
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200"
                                  placeholder="Berikan keterangan tentang penilaian..."></textarea>
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex-1 flex items-center justify-center">
                            Simpan Penilaian
                        </button>
                        <button type="button" onclick="closeModal('nilaiModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex-1 flex items-center justify-center">
                            Batal
                        </button>
                    </div>
                </div>
            </form>
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
        
        /* Animasi untuk modal */
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .modal-open {
            animation: modalSlideIn 0.3s ease-out;
        }
    </style>

    <script>
        let currentKpId = null;
        let penilaianIndex = 1;

        // Tampilkan modal nilai (action form sudah benar dari Blade)
        function openNilai(kpId) {
            currentKpId = kpId;
            const modal = document.getElementById('nilaiModal');
            modal.classList.remove('hidden');
            modal.classList.add('modal-open');
            setTimeout(() => {
                modal.querySelector('div').classList.add('scale-100');
            }, 10);
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            modal.querySelector('div').classList.remove('scale-100');
            modal.classList.remove('modal-open');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        function accSeminar(kpId) {
            if (!confirm('Yakin ingin memberikan ACC seminar untuk mahasiswa ini?')) return;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/seminar/${kpId}/acc-seminar`;
            form.innerHTML = '@csrf';
            document.body.appendChild(form);
            form.submit();
        }

        function openAccSeminar(kpId) {
            currentKpId = kpId;
            const form = document.getElementById('accSeminarForm');
            form.action = `/admin/seminar/${kpId}/acc-pendaftaran-seminar`;
            const modal = document.getElementById('accSeminarModal');
            modal.classList.remove('hidden');
            modal.classList.add('modal-open');
            setTimeout(() => {
                modal.querySelector('div').classList.add('scale-100');
            }, 10);
        }

        function openTolakSeminar(kpId) {
            currentKpId = kpId;
            const form = document.getElementById('tolakSeminarForm');
            form.action = `/admin/seminar/${kpId}/tolak-pendaftaran-seminar`;
            const modal = document.getElementById('tolakSeminarModal');
            modal.classList.remove('hidden');
            modal.classList.add('modal-open');
            setTimeout(() => {
                modal.querySelector('div').classList.add('scale-100');
            }, 10);
        }

        // Fungsi untuk menghitung rata-rata seminar
        function calculateNilaiAkhir() {
            const nilaiInputs = document.querySelectorAll('input[name*="[nilai]"]');
            let totalSeminar = 0;
            let countSeminar = 0;

            nilaiInputs.forEach((input, index) => {
                const value = parseFloat(input.value);
                if (!isNaN(value)) {
                    if (index < 3) { // Indeks 0-2: Seminar
                        totalSeminar += value;
                        countSeminar++;
                    }
                }
            });

            const rataSeminar = countSeminar > 0 ? (totalSeminar / countSeminar).toFixed(2) : '0.00';

            // Update display rata-rata seminar
            document.getElementById('rataSeminarDisplay').textContent = rataSeminar;
        }

        // Attach event listeners to existing nilai inputs
        document.addEventListener('DOMContentLoaded', function() {
            const nilaiInputs = document.querySelectorAll('.nilai-input');
            nilaiInputs.forEach(input => {
                input.addEventListener('input', calculateNilaiAkhir);
            });
            calculateNilaiAkhir(); // Initial calculation

            // Initialize Flatpickr for date picker
            flatpickr("#jadwal_seminar", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                minuteIncrement: 5,
                altInput: true,
                altFormat: "d M Y H:i",
                defaultDate: null
            });

            // Tutup modal dengan klik di luar
            const modals = ['accSeminarModal', 'tolakSeminarModal', 'nilaiModal'];
            
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.addEventListener('click', function(e) {
                        if (e.target === this) {
                            closeModal(modalId);
                        }
                    });
                }
            });
        });
    </script>
</x-sidebar-layout>