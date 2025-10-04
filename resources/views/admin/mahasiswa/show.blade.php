<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Mahasiswa - {{ $mahasiswa->name }}
            </h2>
            <a href="{{ route('admin.mahasiswa.index') }}" class="text-unib-blue-600 hover:text-unib-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Profile Mahasiswa -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Profil Mahasiswa</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-start space-x-6">
                        <div class="h-24 w-24 bg-unib-blue-100 rounded-full flex items-center justify-center">
                            @if($mahasiswa->avatar)
                                <img src="{{ Storage::url($mahasiswa->avatar) }}" alt="Avatar" class="h-24 w-24 rounded-full object-cover">
                            @else
                                <span class="text-unib-blue-600 text-2xl font-bold">{{ substr($mahasiswa->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Nama Lengkap</label>
                                        <p class="text-gray-900 font-medium">{{ $mahasiswa->name }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">NPM</label>
                                        <p class="text-gray-900">{{ $mahasiswa->npm }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Email</label>
                                        <p class="text-gray-900">{{ $mahasiswa->email }}</p>
                                    </div>
                                    
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">No. Telepon</label>
                                        <p class="text-gray-900">{{ $mahasiswa->phone ?? 'Belum diisi' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Status</label>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Aktif
                                        </span>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Bergabung</label>
                                        <p class="text-gray-900">{{ $mahasiswa->created_at->locale('id')->translatedFormat('d F Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Activity -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Bimbingan</p>
                            <p class="text-2xl font-semibold text-blue-600 mt-2">{{ $stats['total_bimbingan'] }}</p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-3">
                            <i class="fas fa-comments text-blue-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Verified</p>
                            <p class="text-2xl font-semibold text-green-600 mt-2">{{ $stats['bimbingan_verified'] }}</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-3">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Kegiatan</p>
                            <p class="text-2xl font-semibold text-purple-600 mt-2">{{ $stats['total_kegiatan'] }}</p>
                        </div>
                        <div class="bg-purple-100 rounded-full p-3">
                            <i class="fas fa-tasks text-purple-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Jam</p>
                            <p class="text-2xl font-semibold text-orange-600 mt-2">{{ $stats['total_jam_kegiatan'] }}</p>
                        </div>
                        <div class="bg-orange-100 rounded-full p-3">
                            <i class="fas fa-clock text-orange-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Kerja Praktek -->
            @if($mahasiswa->kerjaPraktek->first())
                @php
                    $kp = $mahasiswa->kerjaPraktek->first();
                    $displayStatus = $kp->status;
                    if ($kp->status === 'sedang_kp' && $kp->nilai_akhir && $kp->file_laporan) {
                        $displayStatus = 'selesai';
                    }
                @endphp
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Status Kerja Praktek</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Judul KP</label>
                                    <p class="text-gray-900 font-medium">{{ $kp->judul_kp }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Tempat Magang</label>
                                    <p class="text-gray-900">
                                        @if($kp->pilihan_tempat == 3)
                                            {{ $kp->tempat_magang_sendiri }}
                                        @else
                                            {{ $kp->tempatMagang->nama_perusahaan ?? '-' }}
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Status</label>
                                    <div class="mt-1">
                                        @switch($displayStatus)
                                            @case('pengajuan')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                    Pengajuan
                                                </span>
                                                @break
                                            @case('disetujui')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                    Disetujui
                                                </span>
                                                @break
                                            @case('sedang_kp')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    Sedang KP
                                                </span>
                                                @break
                                            @case('selesai')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                    Selesai
                                                </span>
                                                @break
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                @if($kp->tanggal_mulai)
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Tanggal Mulai</label>
                                        <p class="text-gray-900">{{ $kp->tanggal_mulai->locale('id')->translatedFormat('d F Y') }}</p>
                                    </div>
                                @endif
                                @if($kp->tanggal_selesai)
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Tanggal Selesai</label>
                                        <p class="text-gray-900">{{ $kp->tanggal_selesai->locale('id')->translatedFormat('d F Y') }}</p>
                                    </div>
                                @endif
                                @if($kp->nilai_akhir)
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Nilai Akhir</label>
                                        <p class="text-2xl font-bold {{ $kp->lulus_ujian ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $kp->nilai_akhir }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Dosen Pembimbing -->
                        @if($kp->dosenPembimbing->count() > 0)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="font-medium text-gray-900 mb-3">Dosen Pembimbing</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($kp->dosenPembimbing as $pembimbing)
                                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                            <div class="h-10 w-10 bg-unib-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-unib-blue-600 font-medium">{{ substr($pembimbing->dosen->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $pembimbing->dosen->name }}</p>
                                                <p class="text-sm text-gray-600 capitalize">{{ str_replace('_', ' ', $pembimbing->jenis_pembimbingan) }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Quick Actions untuk KP -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex flex-wrap gap-3">
                                @if($kp->status === 'sedang_kp')
                                    <button onclick="sendReminder({{ $kp->id }})" 
                                            class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                        <i class="fas fa-bell mr-2"></i>Kirim Reminder
                                    </button>
                                @endif
                                <a href="{{ route('admin.kerja-praktek.show', $kp) }}" 
                                   class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                    <i class="fas fa-eye mr-2"></i>Detail KP
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Status Kerja Praktek</h3>
                    </div>
                    <div class="p-6 text-center">
                        <i class="fas fa-info-circle text-6xl text-gray-300 mb-4"></i>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Belum Mengajukan KP</h4>
                        <p class="text-gray-600">Mahasiswa ini belum mengajukan kerja praktek.</p>
                    </div>
                </div>
            @endif

            <!-- Riwayat Bimbingan -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Riwayat Bimbingan Terbaru</h3>
                        <a href="{{ route('admin.bimbingan.index', ['search' => $mahasiswa->name]) }}" 
                           class="text-unib-blue-600 hover:text-unib-blue-800 text-sm">
                            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if($mahasiswa->bimbingan->count() > 0)
                        <div class="space-y-4">
                            @foreach($mahasiswa->bimbingan as $bimbingan)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-medium text-gray-900">{{ $bimbingan->topik_bimbingan }}</h4>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm text-gray-500">{{ $bimbingan->tanggal_bimbingan->locale('id')->translatedFormat('d F Y') }}</span>
                                            @if($bimbingan->status_verifikasi)
                                                <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">Verified</span>
                                            @else
                                                <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">Pending</span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-gray-900 mb-3">{{ Str::limit($bimbingan->catatan_mahasiswa, 150) }}</p>
                                    @if($bimbingan->catatan_dosen)
                                        <div class="bg-green-50 border-l-4 border-green-400 p-3">
                                            <p class="text-sm font-medium text-green-800">Feedback Dosen:</p>
                                            <p class="text-sm text-green-900">{{ $bimbingan->catatan_dosen }}</p>
                                        </div>
                                    @endif
                                    <div class="mt-3 flex space-x-2">
                                        <a href="{{ route('admin.bimbingan.show', $bimbingan) }}" 
                                           class="text-unib-blue-600 hover:text-unib-blue-800 text-sm">
                                            Detail
                                        </a>
                                        @if(!$bimbingan->status_verifikasi)
                                            <button onclick="verifyBimbingan({{ $bimbingan->id }})" 
                                                    class="text-green-600 hover:text-green-800 text-sm">
                                                Verify
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-comments text-4xl text-gray-300 mb-4"></i>
                            <p>Belum ada riwayat bimbingan</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Activity Kegiatan -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Kegiatan Terbaru</h3>
                </div>
                <div class="p-6">
                    @if($mahasiswa->kegiatan->count() > 0)
                        <div class="space-y-3">
                            @foreach($mahasiswa->kegiatan as $kegiatan)
                                <div class="flex items-start space-x-3 py-3 border-b border-gray-100 last:border-0">
                                    <div class="bg-purple-100 rounded-full p-2 mt-1">
                                        <i class="fas fa-tasks text-purple-600 text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-gray-900">{{ Str::limit($kegiatan->deskripsi_kegiatan, 100) }}</p>
                                        <p class="text-sm text-gray-600">
                                            Tanggal : {{ $kegiatan->tanggal_kegiatan->locale('id')->translatedFormat('d F Y') }} • {{ $kegiatan->durasi_jam }} jam
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
    </div>

    <script>
        function sendReminder(kpId) {
            if (confirm('Yakin ingin mengirim reminder ke mahasiswa ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/kerja-praktek/${kpId}/send-reminder`;
                form.innerHTML = '@csrf';
                document.body.appendChild(form);
                form.submit();
            }
        }

        function verifyBimbingan(bimbinganId) {
            if (confirm('Yakin ingin memverifikasi bimbingan ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/bimbingan/${bimbinganId}/verify`;
                form.innerHTML = '@csrf';
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-app-layout>