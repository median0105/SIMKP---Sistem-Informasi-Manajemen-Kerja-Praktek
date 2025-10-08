<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Kerja Praktek - {{ $kerjaPraktek->mahasiswa->name }}
            </h2>
            <a href="{{ route('admin.kerja-praktek.index') }}" class="text-unib-blue-600 hover:text-unib-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Alerts --}}
            @if(!empty($isDuplicate))
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
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
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
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
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Kerja Praktek</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Mahasiswa</label>
                                <p class="text-gray-900 mt-1">{{ $kerjaPraktek->mahasiswa->name }} ({{ $kerjaPraktek->mahasiswa->npm }})</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Judul KP</label>
                                <p class="text-gray-900 mt-1">{{ $kerjaPraktek->judul_kp }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Tempat Magang</label>
                                <p class="text-gray-900 mt-1">
                                    @if($kerjaPraktek->pilihan_tempat == 3)
                                        {{ $kerjaPraktek->tempat_magang_sendiri }}
                                        <br><small class="text-gray-500">{{ $kerjaPraktek->alamat_tempat_sendiri }}</small>
                                    @else
                                        {{ $kerjaPraktek->tempatMagang->nama_perusahaan ?? '-' }}
                                        <br><small class="text-gray-500">{{ $kerjaPraktek->tempatMagang->alamat ?? '' }}</small>
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
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-2"></i>Menunggu Persetujuan
                                            </span>
                                            @break
                                        @case('disetujui')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-check-circle mr-2"></i>Disetujui
                                            </span>
                                            @break
                                        @case('sedang_kp')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-play-circle mr-2"></i>Sedang KP
                                            </span>
                                            @break
                                        @case('selesai')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-flag-checkered mr-2"></i>Selesai
                                            </span>
                                            @break
                                        @case('ditolak')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-2"></i>Ditolak
                                            </span>
                                            @break
                                    @endswitch
                                </div>
                            </div>
                            @if($kerjaPraktek->tanggal_mulai)
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Tanggal Mulai</label>
                                    <p class="text-gray-900 mt-1">{{ $kerjaPraktek->tanggal_mulai->locale('id')->translatedFormat('d F Y') }}</p>
                                </div>
                            @endif
                            @if($kerjaPraktek->tanggal_selesai)
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Tanggal Selesai</label>
                                    <p class="text-gray-900 mt-1">{{ $kerjaPraktek->tanggal_selesai->locale('id')->translatedFormat('d F Y') }}</p>
                                </div>
                            @endif

                            {{-- KRS --}}
                            @if($kerjaPraktek->file_krs)
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Kartu Rencana Studi (KRS)</label>
                                    <div class="mt-1">
                                        <a href="{{ Storage::url($kerjaPraktek->file_krs) }}" target="_blank" class="text-unib-blue-600 hover:text-unib-blue-800">
                                            <i class="fas fa-file-pdf text-red-500 mr-1"></i> Lihat KRS
                                        </a>
                                    </div>
                                </div>
                            @endif

                            {{-- PROPOSAL --}}
                            @if($kerjaPraktek->file_proposal)
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Proposal Kerja Praktek</label>
                                    <div class="mt-1">
                                        <a href="{{ Storage::url($kerjaPraktek->file_proposal) }}" target="_blank" class="text-purple-600 hover:text-purple-800">
                                            <i class="fas fa-file-pdf text-red-500 mr-1"></i> Lihat Proposal
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex flex-wrap gap-3">
                            @if($kerjaPraktek->status === 'pengajuan')
                                <button onclick="accProposal({{ $kerjaPraktek->id }})"
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg {{ !empty($isDuplicate) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ !empty($isDuplicate) ? 'disabled' : '' }}>
                                    <i class="fas fa-check mr-2"></i>ACC Proposal
                                </button>
                                <button onclick="openRejectProposal({{ $kerjaPraktek->id }})"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                    <i class="fas fa-times mr-2"></i>Tolak Proposal
                                </button>
                            @endif

                            @if($kerjaPraktek->status === 'disetujui')
                                <button onclick="startKP({{ $kerjaPraktek->id }})"
                                        class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-4 py-2 rounded-lg">
                                    <i class="fas fa-play mr-2"></i>Mulai KP
                                </button>
                            @endif

                            @if(in_array($kerjaPraktek->status, ['sedang_kp','selesai']) && $kerjaPraktek->file_laporan)
                                @if(!$kerjaPraktek->acc_pembimbing_laporan)
                                    <button onclick="accLaporan({{ $kerjaPraktek->id }})"
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                                        <i class="fas fa-check mr-2"></i>ACC Laporan
                                    </button>
                                @endif
                            @endif

                            {{-- ACC Seminar - muncul setelah ACC Laporan DAN ACC Pendaftaran Seminar --}}
                            @if($kerjaPraktek->acc_pembimbing_laporan && $kerjaPraktek->acc_pendaftaran_seminar && !$kerjaPraktek->acc_seminar)
                                <button onclick="accSeminar({{ $kerjaPraktek->id }})"
                                        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
                                    <i class="fas fa-microphone mr-2"></i>ACC Seminar
                                </button>
                            @elseif($kerjaPraktek->acc_seminar)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-microphone mr-2"></i>Seminar Sudah Di-ACC
                                </span>
                            @endif

                            @if($kerjaPraktek->canTakeExam() && $kerjaPraktek->acc_seminar && !$kerjaPraktek->lulus_ujian && !$kerjaPraktek->nilai_akhir)
                                <button onclick="openNilai({{ $kerjaPraktek->id }})"
                                        class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg">
                                    <i class="fas fa-edit mr-2"></i>Input Nilai
                                </button>
                            @endif

                            @if($kerjaPraktek->pendaftaran_seminar && !$kerjaPraktek->acc_pendaftaran_seminar)
                                <button onclick="openAccSeminar({{ $kerjaPraktek->id }})"
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                                    <i class="fas fa-calendar-check mr-2"></i>ACC Pendaftaran Seminar
                                </button>
                                <button onclick="openTolakSeminar({{ $kerjaPraktek->id }})"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                    <i class="fas fa-times mr-2"></i>Tolak Pendaftaran Seminar
                                </button>
                            @endif

                            @if($kerjaPraktek->status === 'sedang_kp')
                                <button onclick="sendReminder({{ $kerjaPraktek->id }})"
                                        class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg">
                                    <i class="fas fa-bell mr-2"></i>Kirim Reminder
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Progress --}}
            @if(in_array($kerjaPraktek->status, ['sedang_kp','selesai']))
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Progress Seminar & Ujian</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            {{-- Laporan --}}
                            <div class="text-center">
                                <div class="mx-auto w-12 h-12 rounded-full flex items-center justify-center mb-3
                                    {{ $kerjaPraktek->file_laporan ? 'bg-green-100' : 'bg-gray-100' }}">
                                    <i class="fas fa-file-alt {{ $kerjaPraktek->file_laporan ? 'text-green-600' : 'text-gray-600' }}"></i>
                                </div>
                                <h4 class="font-medium text-gray-900">Upload Laporan</h4>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $kerjaPraktek->file_laporan ? 'Sudah Upload' : 'Belum Upload' }}
                                </p>
                                @if($kerjaPraktek->file_laporan)
                                    <a href="{{ Storage::url($kerjaPraktek->file_laporan) }}" target="_blank"
                                       class="text-unib-blue-600 text-sm mt-2 inline-block">
                                        <i class="fas fa-download mr-1"></i>Download
                                    </a>
                                @endif
                            </div>

                            {{-- Pendaftaran Seminar --}}
                            <div class="text-center">
                                <div class="mx-auto w-12 h-12 rounded-full flex items-center justify-center mb-3
                                    {{ $kerjaPraktek->pendaftaran_seminar ? 'bg-green-100' : 'bg-gray-100' }}">
                                    <i class="fas fa-calendar-plus {{ $kerjaPraktek->pendaftaran_seminar ? 'text-green-600' : 'text-gray-600' }}"></i>
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
                            <div class="text-center">
                                <div class="mx-auto w-12 h-12 rounded-full flex items-center justify-center mb-3
                                    {{ $kerjaPraktek->acc_seminar ? 'bg-green-100' : 'bg-gray-100' }}">
                                    <i class="fas fa-microphone {{ $kerjaPraktek->acc_seminar ? 'text-green-600' : 'text-gray-600' }}"></i>
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
                            <div class="text-center">
                                <div class="mx-auto w-12 h-12 rounded-full flex items-center justify-center mb-3
                                    {{ $kerjaPraktek->lulus_ujian ? 'bg-green-100' : 'bg-gray-100' }}">
                                    <i class="fas fa-graduation-cap {{ $kerjaPraktek->lulus_ujian ? 'text-green-600' : 'text-gray-600' }}"></i>
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
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Akademik Semester</h3>
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

                    @isset($canUpdateIpk)
                        @if($canUpdateIpk && !($kerjaPraktek->nilai_akhir && !$kerjaPraktek->lulus_ujian))
                            <form method="POST" action="{{ route('admin.kerja-praktek.set-ipk', $kerjaPraktek) }}"
                                  class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Semester Ke</label>
                                    <input type="number" name="semester_ke" min="1" max="14"
                                           value="{{ old('semester_ke', $kerjaPraktek->semester_ke) }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">IPK Semester</label>
                                    <input type="text" name="ipk_semester" placeholder="mis. 3.75"
                                           value="{{ old('ipk_semester', $kerjaPraktek->ipk_semester ? number_format($kerjaPraktek->ipk_semester,2) : null) }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                                    <p class="text-xs text-gray-500 mt-1">Rentang 0.00 - 4.00</p>
                                    @error('ipk_semester') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="flex items-end">
                                    <button type="submit"
                                            class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-2 rounded-md font-medium">
                                        Simpan IPK
                                    </button>
                                </div>
                            </form>
                        @endif
                    @endisset
                </div>
            </div>

            {{-- Penilaian --}}
            @if($kerjaPraktek->nilai_akhir)
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Detail Penilaian</h3>
                    </div>
                    <div class="p-6">
                        @if($kerjaPraktek->penilaian_detail)
                            <div class="overflow-x-auto mb-6">
                                <table class="min-w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Indikator Penilaian</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($kerjaPraktek->penilaian_detail as $penilaian)
                                            <tr>
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
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Riwayat Bimbingan ({{ $kerjaPraktek->bimbingan->count() }})</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($kerjaPraktek->bimbingan->take(5) as $bimbingan)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-medium text-gray-900">{{ $bimbingan->topik_bimbingan }}</h4>
                                        @if($bimbingan->status_verifikasi)
                                            <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">Verified</span>
                                        @else
                                            <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">Pending</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">{{ $bimbingan->tanggal_bimbingan->locale('id')->translatedFormat('d F Y') }}</p>
                                    <p class="text-gray-900">{{ \Illuminate\Support\Str::limit($bimbingan->catatan_mahasiswa, 100) }}</p>
                                    @if($bimbingan->catatan_dosen)
                                        <div class="mt-3 p-3 bg-gray-50 rounded">
                                            <p class="text-sm font-medium text-gray-600">Feedback Dosen:</p>
                                            <p class="text-sm text-gray-900">{{ $bimbingan->catatan_dosen }}</p>
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

    {{-- Modal Input Nilai --}}
    <div id="nilaiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-2xl w-full max-h-screen overflow-y-auto">
                <form id="nilaiForm" method="POST" action="{{ route('admin.kerja-praktek.input-nilai', $kerjaPraktek) }}">
                    @csrf
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Input Penilaian Kerja Praktek</h3>

                        <div id="penilaianContainer" class="space-y-4 mb-6">
                            <div class="penilaian-item grid grid-cols-3 gap-4">
                                <div class="col-span-2">
                                    <input type="text" name="penilaian_detail[0][indikator]"
                                           placeholder="Indikator penilaian..." required
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                                </div>
                                <div>
                                    <input type="number" name="penilaian_detail[0][nilai]"
                                           placeholder="Nilai (0-100)" min="0" max="100" required
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 nilai-input">
                                </div>
                            </div>
                        </div>

                        <button type="button" onclick="addPenilaian()" class="text-unib-blue-600 hover:text-unib-blue-800 text-sm mb-4">
                            <i class="fas fa-plus mr-1"></i>Tambah Indikator
                        </button>

                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nilai Akhir (Otomatis)</label>
                                    <p class="text-xs text-gray-500">Rata-rata dari semua indikator penilaian</p>
                                </div>
                                <div class="text-right">
                                    <span id="nilaiAkhirDisplay" class="text-2xl font-bold text-gray-900">0.00</span>
                                    <p id="statusDisplay" class="text-sm text-gray-600">-</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan Penilaian</label>
                            <textarea name="keterangan_penilaian" rows="4" required
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                      placeholder="Berikan keterangan tentang penilaian..."></textarea>
                        </div>

                        <div class="flex space-x-3">
                            <button type="submit" class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-2 rounded-lg">
                                Simpan Penilaian
                            </button>
                            <button type="button" onclick="closeModal('nilaiModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg">
                                Batal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Approve --}}
    <div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Setujui Pengajuan KP</h3>
                    <p class="text-gray-600 mb-6">Yakin ingin menyetujui pengajuan KP ini?</p>
                    <div class="flex space-x-3">
                        <button onclick="confirmApprove()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                            Ya, Setujui
                        </button>
                        <button onclick="closeModal('approveModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Reject --}}
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full">
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Pengajuan KP</h3>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                            <textarea name="catatan_dosen" rows="4" required
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                      placeholder="Berikan alasan penolakan..."></textarea>
                        </div>
                        <div class="flex space-x-3">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                Tolak
                            </button>
                            <button type="button" onclick="closeModal('rejectModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                                Batal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Reject Proposal --}}
    <div id="rejectProposalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full">
                <form id="rejectProposalForm" method="POST">
                    @csrf
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Proposal KP</h3>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                            <textarea name="catatan_dosen" rows="4" required
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                      placeholder="Berikan alasan penolakan proposal..."></textarea>
                        </div>
                        <div class="flex space-x-3">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                Tolak Proposal
                            </button>
                            <button type="button" onclick="closeModal('rejectProposalModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                                Batal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal ACC Pendaftaran Seminar --}}
    <div id="accSeminarModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full">
                <form id="accSeminarForm" method="POST">
                    @csrf
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">ACC Pendaftaran Seminar</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jadwal Seminar *</label>
                                <input type="datetime-local" name="jadwal_seminar" required
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Pilih tanggal dan waktu seminar</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ruangan Seminar *</label>
                                <input type="text" name="ruangan_seminar" required
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                       placeholder="Contoh: Ruang Seminar 101">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Seminar</label>
                                <textarea name="catatan_seminar" rows="3"
                                          class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                          placeholder="Catatan tambahan untuk mahasiswa (opsional)"></textarea>
                            </div>
                        </div>

                        <div class="flex space-x-3 mt-6">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                                <i class="fas fa-check mr-2"></i>ACC & Tetapkan Jadwal
                            </button>
                            <button type="button" onclick="closeModal('accSeminarModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg">
                                Batal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Tolak Pendaftaran Seminar --}}
    <div id="tolakSeminarModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full">
                <form id="tolakSeminarForm" method="POST">
                    @csrf
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Pendaftaran Seminar</h3>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan *</label>
                            <textarea name="catatan_seminar" rows="4" required
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500"
                                      placeholder="Berikan alasan penolakan pendaftaran seminar..."></textarea>
                        </div>
                        <div class="flex space-x-3">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                Tolak Pendaftaran
                            </button>
                            <button type="button" onclick="closeModal('tolakSeminarModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                                Batal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        let currentKpId = null;
        let penilaianIndex = 1;

        function openApprove(kpId) {
            currentKpId = kpId;
            document.getElementById('approveModal').classList.remove('hidden');
        }
        function openReject(kpId) {
            currentKpId = kpId;
            const form = document.getElementById('rejectForm');
            form.action = `/admin/kerja-praktek/${kpId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function accProposal(kpId) {
            if (!confirm('ACC Proposal Kerja Praktek mahasiswa ini?')) return;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/kerja-praktek/${kpId}/acc-proposal`;
            form.innerHTML = '@csrf';
            document.body.appendChild(form);
            form.submit();
        }
        function openRejectProposal(kpId) {
            currentKpId = kpId;
            const form = document.getElementById('rejectProposalForm');
            form.action = `/admin/kerja-praktek/${kpId}/reject-proposal`;
            document.getElementById('rejectProposalModal').classList.remove('hidden');
        }

        // Tampilkan modal nilai (action form sudah benar dari Blade)
        function openNilai(kpId) {
            currentKpId = kpId;
            document.getElementById('nilaiModal').classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function confirmApprove() {
            if (!currentKpId) return;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/kerja-praktek/${currentKpId}/approve`;
            form.innerHTML = '@csrf';
            document.body.appendChild(form);
            form.submit();
        }
        function startKP(kpId) {
            if (!confirm('Yakin ingin memulai status KP untuk mahasiswa ini?')) return;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/kerja-praktek/${kpId}/start`;
            form.innerHTML = '@csrf';
            document.body.appendChild(form);
            form.submit();
        }
        function accSeminar(kpId) {
            if (!confirm('Yakin ingin memberikan ACC seminar untuk mahasiswa ini?')) return;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/kerja-praktek/${kpId}/acc-seminar`;
            form.innerHTML = '@csrf';
            document.body.appendChild(form);
            form.submit();
        }
        function accLaporan(kpId) {
            if (!confirm('Yakin ingin memberikan ACC laporan untuk mahasiswa ini?')) return;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/kerja-praktek/${kpId}/acc-laporan`;
            form.innerHTML = '@csrf';
            document.body.appendChild(form);
            form.submit();
        }
        function sendReminder(kpId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/kerja-praktek/${kpId}/send-reminder`;
            form.innerHTML = '@csrf';
            document.body.appendChild(form);
            form.submit();
        }

        function openAccSeminar(kpId) {
            currentKpId = kpId;
            const form = document.getElementById('accSeminarForm');
            form.action = `/admin/kerja-praktek/${kpId}/acc-pendaftaran-seminar`;
            document.getElementById('accSeminarModal').classList.remove('hidden');
        }

        function openTolakSeminar(kpId) {
            currentKpId = kpId;
            const form = document.getElementById('tolakSeminarForm');
            form.action = `/admin/kerja-praktek/${kpId}/tolak-pendaftaran-seminar`;
            document.getElementById('tolakSeminarModal').classList.remove('hidden');
        }



        // Fungsi untuk menghitung nilai akhir
        function calculateNilaiAkhir() {
            const nilaiInputs = document.querySelectorAll('input[name*="[nilai]"]');
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
            document.getElementById('nilaiAkhirDisplay').textContent = average;

            const statusElement = document.getElementById('statusDisplay');
            if (count === 0) {
                statusElement.textContent = '-';
                statusElement.className = 'text-sm text-gray-600';
            } else {
                const avgNum = parseFloat(average);
                if (avgNum >= 70) {
                    statusElement.textContent = 'LULUS';
                    statusElement.className = 'text-sm text-green-600';
                } else {
                    statusElement.textContent = 'TIDAK LULUS';
                    statusElement.className = 'text-sm text-red-600';
                }
            }
        }

        // Dinamika baris penilaian
        function addPenilaian() {
            const container = document.getElementById('penilaianContainer');
            const row = document.createElement('div');
            row.className = 'penilaian-item grid grid-cols-3 gap-4';
            row.innerHTML = `
                <div class="col-span-2">
                    <input type="text" name="penilaian_detail[${penilaianIndex}][indikator]"
                           placeholder="Indikator penilaian..." required
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500">
                </div>
                <div class="flex space-x-2">
                    <input type="number" name="penilaian_detail[${penilaianIndex}][nilai]"
                           placeholder="Nilai (0-100)" min="0" max="100" required
                           class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 nilai-input">
                    <button type="button" onclick="this.closest('.penilaian-item').remove(); calculateNilaiAkhir();" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
            penilaianIndex++;
            container.appendChild(row);

            // Attach event listener to new input
            const newInput = row.querySelector('.nilai-input');
            newInput.addEventListener('input', calculateNilaiAkhir);
        }

        // Attach event listeners to existing nilai inputs
        document.addEventListener('DOMContentLoaded', function() {
            const nilaiInputs = document.querySelectorAll('.nilai-input');
            nilaiInputs.forEach(input => {
                input.addEventListener('input', calculateNilaiAkhir);
            });
            calculateNilaiAkhir(); // Initial calculation
        });
    </script>
</x-app-layout>
