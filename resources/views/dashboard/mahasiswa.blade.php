<!-- Welcome Card -->
<div class="bg-gradient-to-br from-blue-700 to-blue-600 rounded-lg p-6 text-white mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold">Selamat Datang, {{ auth()->user()->name }}</h3>
            <p class="text-unib-blue-200 mt-1 font-bold">NPM: {{ auth()->user()->npm }}</p>
            {{-- @if($data['kerjaPraktek'] && $data['kerjaPraktek']->dosenAkademik)
                <p class="text-unib-blue-200">Dosen Pembimbing: {{ $data['kerjaPraktek']->dosenAkademik->dosen->name }}</p>
            @endif --}}
            <p class="text-unib-blue-200">Sistem Informasi Manajemen Kerja Praktek</p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-user-graduate text-6xl text-unib-blue-300"></i>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ ($data['kerjaPraktek'] && $data['kerjaPraktek']->status === 'selesai') ? (($data['kerjaPraktek']->dosenAkademik) ? '6' : '5') : (($data['kerjaPraktek'] && $data['kerjaPraktek']->dosenAkademik) ? '5' : '4') }} gap-6 mb-6">
    <!-- Status KP -->
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-600">Status Kerja Praktek</p>
                <p class="text-xl font-semibold text-gray-900 mt-1">
                    @if($data['kerjaPraktek'])
                        <span class="
                            @switch($data['kerjaPraktek']->status)
                                @case('pengajuan') text-yellow-600 @break
                                @case('disetujui') text-blue-600 @break
                                @case('sedang_kp') text-green-600 @break
                                @case('selesai') text-gray-600 @break
                                @case('ditolak') text-red-600 @break
                            @endswitch
                        ">
                            {{ ucfirst(str_replace('_', ' ', $data['kerjaPraktek']->status)) }}
                        </span>
                    @else
                        <span class="text-xs font-medium text-gray-400">Belum Mengajukan</span>
                    @endif
                </p>
            </div>
            <div class="bg-unib-blue-100 rounded-full p-2">
                <i class="fas fa-briefcase text-unib-blue-600 text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Total Bimbingan -->
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-600">Total Bimbingan</p>
                <p class="text-xl font-semibold text-gray-900 mt-1">{{ $data['totalBimbingan'] }}</p>
            </div>
            <div class="bg-green-100 rounded-full p-2">
                <i class="fas fa-chalkboard-teacher text-green-600 text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Total Kegiatan -->
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-600">Total Kegiatan</p>
                <p class="text-xl font-semibold text-gray-900 mt-1">{{ $data['totalKegiatan'] }}</p>
            </div>
            <div class="bg-teknik-orange-100 rounded-full p-2">
                <i class="fas fa-tasks text-teknik-orange-600 text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Quick Action -->
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-center">
            <p class="text-xs font-medium text-gray-600 mb-3">Quick Action</p>
            @if(!$data['kerjaPraktek'])
                <a href="{{ route('mahasiswa.kerja-praktek.index') }}" class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-3 py-1 rounded-lg text-xs font-medium transition duration-200">
                    <i class="fas fa-plus mr-1"></i>
                    Ajukan KP
                </a>
            @elseif($data['kerjaPraktek']->status === 'sedang_kp')
                <a href="{{ route('mahasiswa.kegiatan.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg text-xs font-medium transition duration-200">
                    <i class="fas fa-plus mr-1"></i>
                    Tambah Kegiatan
                </a>
            @elseif($data['kerjaPraktek']->status === 'selesai' && !$data['kerjaPraktek']->kuisioner)
                <a href="{{ route('mahasiswa.kerja-praktek.kuisioner', $data['kerjaPraktek']) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded-lg text-xs font-medium transition duration-200">
                    <i class="fas fa-poll mr-1"></i>
                    Isi Kuisioner
                </a>
            @else
                <span class="text-gray-500 text-xs">-</span>
            @endif
        </div>
    </div>

    @if($data['kerjaPraktek'] && $data['kerjaPraktek']->status === 'selesai')
    <!-- Nilai Akhir -->
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-600">Nilai Akhir KP</p>
                <p class="text-xl font-semibold text-gray-900 mt-1">
                    @if($data['kerjaPraktek']->nilai_akhir)
                        {{ number_format($data['kerjaPraktek']->nilai_akhir, 2) }}
                    @else
                        -
                    @endif
                </p>
                @if($data['kerjaPraktek']->nilai_akhir)
                    <p class="text-xs {{ $data['kerjaPraktek']->lulus_ujian ? 'text-green-600' : 'text-red-600' }} mt-1">
                        {{ $data['kerjaPraktek']->lulus_ujian ? 'LULUS' : 'TIDAK LULUS' }}
                    </p>
                @endif
            </div>
            <div class="bg-purple-100 rounded-full p-2">
                <i class="fas fa-graduation-cap text-purple-600 text-lg"></i>
            </div>
        </div>
    </div>
    @endif

    <!-- Dosen Pembimbing -->
    @if($data['kerjaPraktek'] && $data['kerjaPraktek']->dosenAkademik)
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-600">Dosen Pembimbing</p>
                <p class="text-xl font-semibold text-gray-900 mt-1">
                    {{ $data['kerjaPraktek']->dosenAkademik->dosen->name }}
                </p>
            </div>
            <div class="bg-blue-100 rounded-full p-2">
                <i class="fas fa-chalkboard-teacher text-blue-600 text-lg"></i>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Additional Progress Details (if needed for more space) -->
@if($data['kerjaPraktek'] && in_array($data['kerjaPraktek']->status, ['sedang_kp', 'selesai']))
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Detail Progress Seminar & Ujian</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <!-- Upload Laporan -->
                <div class="text-center">
                    <div class="mx-auto w-12 h-12 bg-{{ $data['kerjaPraktek']->file_laporan ? 'green' : 'gray' }}-100 rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-file-alt text-{{ $data['kerjaPraktek']->file_laporan ? 'green' : 'gray' }}-600"></i>
                    </div>
                    <h4 class="font-medium text-gray-900">Laporan KP</h4>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $data['kerjaPraktek']->file_laporan ? 'Sudah Upload' : 'Belum Upload' }}
                    </p>
                </div>

                <!-- ACC Seminar -->
                <div class="text-center">
                    <div class="mx-auto w-12 h-12 bg-{{ $data['kerjaPraktek']->acc_seminar ? 'green' : 'gray' }}-100 rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-microphone text-{{ $data['kerjaPraktek']->acc_seminar ? 'green' : 'gray' }}-600"></i>
                    </div>
                    <h4 class="font-medium text-gray-900">Seminar KP</h4>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $data['kerjaPraktek']->acc_seminar ? 'Sudah ACC' : 'Belum ACC' }}
                    </p>
                </div>

                <!-- Kartu Implementasi -->
                <div class="text-center">
                    <div class="mx-auto w-12 h-12 bg-{{ $data['kerjaPraktek']->acc_pembimbing_lapangan ? 'green' : ($data['kerjaPraktek']->file_kartu_implementasi ? 'yellow' : 'gray') }}-100 rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-id-card text-{{ $data['kerjaPraktek']->acc_pembimbing_lapangan ? 'green' : ($data['kerjaPraktek']->file_kartu_implementasi ? 'yellow' : 'gray') }}-600"></i>
                    </div>
                    <h4 class="font-medium text-gray-900">Lembar Penilaian KP</h4>
                    <p class="text-sm text-gray-600 mt-1">
                        @if($data['kerjaPraktek']->acc_pembimbing_lapangan)
                            Sudah ACC
                        @elseif($data['kerjaPraktek']->file_kartu_implementasi)
                            Menunggu ACC
                        @else
                            Belum Upload
                        @endif
                    </p>
                </div>

                <!-- Ujian -->
                <div class="text-center">
                    <div class="mx-auto w-12 h-12 bg-{{ $data['kerjaPraktek']->lulus_ujian ? 'green' : 'gray' }}-100 rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-graduation-cap text-{{ $data['kerjaPraktek']->lulus_ujian ? 'green' : 'gray' }}-600"></i>
                    </div>
                    <h4 class="font-medium text-gray-900">Ujian</h4>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $data['kerjaPraktek']->lulus_ujian ? 'Lulus' : ($data['kerjaPraktek']->nilai_akhir ? 'Tidak Lulus' : 'Belum Ujian') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Bimbingan Terbaru -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Bimbingan Terbaru</h3>
                <a href="{{ route('mahasiswa.bimbingan.index') }}" class="text-unib-blue-600 hover:text-unib-blue-800 text-sm font-medium">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="p-6">
            @forelse($data['bimbinganTerbaru'] as $bimbingan)
                <div class="flex items-start space-x-3 mb-4 last:mb-0">
                    <div class="bg-green-100 rounded-full p-2 mt-1">
                        <i class="fas fa-calendar-check text-green-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">{{ $bimbingan->topik_bimbingan }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $bimbingan->tanggal_bimbingan->format('d M Y') }}</p>
                        <div class="mt-2">
                            @if($bimbingan->status_verifikasi)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>
                                    Verified
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Pending
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-4"></i>
                    <p>Belum ada bimbingan</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Kegiatan Terbaru -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Kegiatan Terbaru</h3>
                <a href="{{ route('mahasiswa.kegiatan.index') }}" class="text-unib-blue-600 hover:text-unib-blue-800 text-sm font-medium">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="p-6">
            @forelse($data['kegiatanTerbaru'] as $kegiatan)
                <div class="flex items-start space-x-3 mb-4 last:mb-0">
                    <div class="bg-teknik-orange-100 rounded-full p-2 mt-1">
                        <i class="fas fa-tasks text-teknik-orange-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">{{ Str::limit($kegiatan->deskripsi_kegiatan, 50) }}</p>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $kegiatan->tanggal_kegiatan->format('d M Y') }} • {{ $kegiatan->durasi_jam }} jam
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-tasks text-4xl text-gray-300 mb-4"></i>
                    <p>Belum ada kegiatan</p>
                </div>
            @endforelse
        </div>
    </div>
</div>