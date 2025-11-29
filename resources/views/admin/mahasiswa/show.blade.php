<x-sidebar-layout>
    {{-- Header section with UNIB blue background --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.mahasiswa.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg inline-flex items-center transition duration-200 backdrop-blur-sm border border-white/30">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                    <!-- Ikon dihapus -->
                </div>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Detail Mahasiswa - {{ $mahasiswa->name }}
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Profile Mahasiswa Card -->
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                    <h3 class="text-base font-semibold text-unib-blue-800">
                        Profil Mahasiswa
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-start space-x-6">
                        <div class="h-24 w-24 bg-unib-blue-100 rounded-full flex items-center justify-center border border-unib-blue-200">
                            @if($mahasiswa->avatar)
                                <img src="{{ Storage::url($mahasiswa->avatar) }}" alt="Avatar" class="h-24 w-24 rounded-full object-cover">
                            @else
                                <span class="text-unib-blue-600 text-2xl font-bold">{{ substr($mahasiswa->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                                        <div>
                                            <p class="text-sm text-gray-500">Nama Lengkap</p>
                                            <p class="font-medium text-gray-900">{{ $mahasiswa->name }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                                        <div>
                                            <p class="text-sm text-gray-500">NPM</p>
                                            <p class="font-medium text-gray-900">{{ $mahasiswa->npm }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                                        <div>
                                            <p class="text-sm text-gray-500">Email</p>
                                            <p class="font-medium text-gray-900">{{ $mahasiswa->email }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                                        <div>
                                            <p class="text-sm text-gray-500">No. Telepon</p>
                                            <p class="font-medium text-gray-900">{{ $mahasiswa->phone ?? 'Belum diisi' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                                        <div>
                                            <p class="text-sm text-gray-500">Status</p>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-300 shadow-sm">
                                                <i class="fas fa-check-circle mr-2 text-xs"></i>Aktif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                                        <div>
                                            <p class="text-sm text-gray-500">Bergabung</p>
                                            <p class="font-medium text-gray-900">{{ $mahasiswa->created_at->locale('id')->translatedFormat('d F Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Activity -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Bimbingan</p>
                            <p class="text-2xl font-bold text-unib-blue-600 mt-2">{{ $stats['total_bimbingan'] }}</p>
                        </div>
                        <div class="bg-unib-blue-100 rounded-full p-3 w-12 h-12 flex items-center justify-center">
                            <!-- Ikon dihapus -->
                        </div>
                    </div>
                </div>
                
                <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Verified</p>
                            <p class="text-2xl font-bold text-green-600 mt-2">{{ $stats['bimbingan_verified'] }}</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-3 w-12 h-12 flex items-center justify-center">
                            <!-- Ikon dihapus -->
                        </div>
                    </div>
                </div>
                
                <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Kegiatan</p>
                            <p class="text-2xl font-bold text-purple-600 mt-2">{{ $stats['total_kegiatan'] }}</p>
                        </div>
                        <div class="bg-purple-100 rounded-full p-3 w-12 h-12 flex items-center justify-center">
                            <!-- Ikon dihapus -->
                        </div>
                    </div>
                </div>
                
                <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up" style="animation-delay: 0.3s;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Jam</p>
                            <p class="text-2xl font-bold text-orange-600 mt-2">{{ $stats['total_jam_kegiatan'] }}</p>
                        </div>
                        <div class="bg-orange-100 rounded-full p-3 w-12 h-12 flex items-center justify-center">
                            <!-- Ikon dihapus -->
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
                <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                    <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                        <h3 class="text-base font-semibold text-unib-blue-800">
                            Status Kerja Praktek
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                                    <div>
                                        <p class="text-sm text-gray-500">Judul KP</p>
                                        <p class="font-medium text-gray-900">{{ $kp->judul_kp }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                                    <div>
                                        <p class="text-sm text-gray-500">Tempat Magang</p>
                                        <p class="font-medium text-gray-900">
                                            @if($kp->pilihan_tempat == 3)
                                                {{ $kp->tempat_magang_sendiri }}
                                            @else
                                                {{ $kp->tempatMagang->nama_perusahaan ?? '-' }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                                    <div>
                                        <p class="text-sm text-gray-500">Status</p>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                            @switch($displayStatus)
                                                @case('pengajuan') bg-yellow-100 text-yellow-800 border border-yellow-300 @break
                                                @case('disetujui') bg-blue-100 text-blue-800 border border-blue-300 @break
                                                @case('sedang_kp') bg-green-100 text-green-800 border border-green-300 @break
                                                @case('selesai') bg-gray-100 text-gray-800 border border-gray-300 @break
                                            @endswitch shadow-sm">
                                            <i class="fas 
                                                @switch($displayStatus)
                                                    @case('pengajuan') fa-clock @break
                                                    @case('disetujui') fa-check @break
                                                    @case('sedang_kp') fa-play @break
                                                    @case('selesai') fa-flag-checkered @break
                                                @endswitch mr-2 text-xs"></i>
                                            {{ ucfirst($displayStatus) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                @if($kp->tanggal_mulai)
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                                        <div>
                                            <p class="text-sm text-gray-500">Tanggal Mulai</p>
                                            <p class="font-medium text-gray-900">{{ $kp->tanggal_mulai->locale('id')->translatedFormat('d F Y') }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($kp->tanggal_selesai)
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                                        <div>
                                            <p class="text-sm text-gray-500">Tanggal Selesai</p>
                                            <p class="font-medium text-gray-900">{{ $kp->tanggal_selesai->locale('id')->translatedFormat('d F Y') }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($kp->nilai_akhir)
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-unib-blue-500 rounded-full mr-3"></div>
                                        <div>
                                            <p class="text-sm text-gray-500">Nilai Akhir</p>
                                            <p class="text-2xl font-bold {{ $kp->lulus_ujian ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $kp->nilai_akhir }}
                                            </p>
                                        </div>
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
                                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                            <div class="h-10 w-10 bg-unib-blue-100 rounded-full flex items-center justify-center border border-unib-blue-200">
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
                                            class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200 flex items-center">
                                        <i class="fas fa-bell mr-2"></i>Kirim Reminder
                                    </button>
                                @endif
                                <a href="{{ route('admin.kerja-praktek.show', $kp) }}" 
                                   class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200 flex items-center">
                                    <i class="fas fa-eye mr-2"></i>Detail KP
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                    <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                        <h3 class="text-base font-semibold text-unib-blue-800">
                            Status Kerja Praktek
                        </h3>
                    </div>
                    <div class="p-6 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-info-circle text-4xl mb-4 text-gray-300"></i>
                            <div class="text-base font-medium text-gray-900 mb-2">Belum Mengajukan KP</div>
                            <p class="text-sm text-gray-600">Mahasiswa ini belum mengajukan kerja praktek.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Riwayat Bimbingan Card -->
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-unib-blue-800">
                            Riwayat Bimbingan Terbaru
                        </h3>
                        <a href="{{ route('admin.bimbingan.index', ['search' => $mahasiswa->name]) }}" 
                           class="text-unib-blue-600 hover:text-unib-blue-800 text-sm font-medium flex items-center">
                            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if($mahasiswa->bimbingan->count() > 0)
                        <div class="space-y-4">
                            @foreach($mahasiswa->bimbingan as $bimbingan)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-150">
                                    <div class="flex justify-between items-start mb-3">
                                        <h4 class="font-medium text-gray-900 text-base">{{ $bimbingan->topik_bimbingan }}</h4>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm text-gray-500">{{ $bimbingan->tanggal_bimbingan->locale('id')->translatedFormat('d F Y') }}</span>
                                            @if($bimbingan->status_verifikasi)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-300">
                                                    Verified
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-300">
                                                    Pending
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-gray-900 mb-3">{{ Str::limit($bimbingan->catatan_mahasiswa, 150) }}</p>
                                    @if($bimbingan->catatan_dosen)
                                        <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                            <p class="text-sm font-medium text-green-800 mb-1">Feedback Dosen:</p>
                                            <p class="text-sm text-green-900">{{ $bimbingan->catatan_dosen }}</p>
                                        </div>
                                    @endif
                                    <div class="mt-3 flex space-x-3">
                                        <a href="{{ route('admin.bimbingan.show', ['mahasiswa' => $mahasiswa->id]) }}"
                                           class="text-unib-blue-600 hover:text-unib-blue-800 text-sm font-medium flex items-center">
                                            <i class="fas fa-eye mr-1"></i>Detail
                                        </a>
                                        @if(!$bimbingan->status_verifikasi)
                                            <button onclick="verifyBimbingan({{ $bimbingan->id }})" 
                                                    class="text-green-600 hover:text-green-800 text-sm font-medium flex items-center">
                                                <i class="fas fa-check mr-1"></i>Verify
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-comments text-4xl mb-4 text-gray-300"></i>
                                <div class="text-base font-medium text-gray-900 mb-2">Belum Ada Bimbingan</div>
                                <p class="text-sm text-gray-600">Belum ada riwayat bimbingan</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Activity Kegiatan Card -->
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                    <h3 class="text-base font-semibold text-unib-blue-800">
                        Kegiatan Terbaru
                    </h3>
                </div>
                <div class="p-6">
                    @if($mahasiswa->kegiatan->count() > 0)
                        <div class="space-y-4">
                            @foreach($mahasiswa->kegiatan as $kegiatan)
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
</x-sidebar-layout>