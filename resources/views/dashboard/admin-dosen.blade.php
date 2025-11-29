<!-- Welcome Card -->
<div class="bg-gradient-to-br from-unib-blue-600 to-unib-blue-700 rounded-xl p-6 text-white shadow-lg">

    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold">Selamat Datang, {{ auth()->user()->name }}</h3>
            <p class="text-unib-blue-200 mt-1 font-bold">Dosen Pembimbing</p>
            <p class="text-unib-blue-200">Sistem Informasi Manajemen Kerja Praktek</p>
        </div>

        <!-- Lottie Icon -->
        <div class="hidden md:block">
            <dotlottie-wc 
                src="https://lottie.host/8bb28c20-1675-4228-95a7-2eb3aaa2135f/eeQDyUQpRP.lottie" 
                style="width: 180px;height: 180px" autoplay loop>
            </dotlottie-wc>
        </div>
    </div>
</div>
<div class="h-4"></div>
<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Mahasiswa Bimbingan -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Mahasiswa Bimbingan</p>
                <p class="text-2xl font-semibold text-unib-blue-600 mt-2">{{ $data['totalMahasiswaBimbingan'] }}</p>
            </div>
            <div class="bg-unib-blue-100 rounded-full p-3 w-10 h-10 flex items-center justify-center">
                <i class="fas fa-users text-unib-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Pengajuan Baru -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Pengajuan Baru</p>
                <p class="text-2xl font-semibold text-yellow-600 mt-2">{{ $data['pengajuanBaru'] }}</p>
            </div>
            <div class="bg-yellow-100 rounded-full p-3 w-10 h-10 flex items-center justify-center">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Sedang KP -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Sedang KP</p>
                <p class="text-2xl font-semibold text-green-600 mt-2">{{ $data['sedangKP'] }}</p>
            </div>
            <div class="bg-green-100 rounded-full p-3 w-10 h-10 flex items-center justify-center">
                <i class="fas fa-play-circle text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Selesai KP -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Selesai KP</p>
                <p class="text-2xl font-semibold text-gray-600 mt-2">{{ $data['selesaiKP'] }}</p>
            </div>
            <div class="bg-gray-100 rounded-full p-3 w-10 h-10 flex items-center justify-center">
                <i class="fas fa-flag-checkered text-gray-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>


<!-- Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    <!-- Akses Cepat -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 bg-unib-blue-600 text-white rounded-t-lg">
            <h3 class="text-lg font-semibold">Akses Cepat</h3>
        </div>
        <div class="p-6 space-y-3">
            <a href="{{ route('admin.kerja-praktek.index', ['status' => 'pengajuan']) }}" 
               class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <i class="fas fa-eye mr-3"></i>
                Review Pengajuan ({{ $data['pengajuanBaru'] }})
            </a>

            <a href="{{ route('admin.bimbingan.index', ['status' => 'pending']) }}" 
               class="w-full bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-4 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <i class="fas fa-comments mr-3"></i>
                Verifikasi Bimbingan
            </a>

            <a href="{{ route('admin.mahasiswa.index') }}"
               class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <i class="fas fa-users mr-3"></i>
                Lihat Mahasiswa
            </a>
        </div>
    </div>

    <!-- Mahasiswa Bimbingan -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow">
        <div class="px-6 py-4 bg-unib-blue-600 text-white rounded-t-lg">
            <h3 class="text-lg font-semibold">Mahasiswa Bimbingan</h3>
        </div>
        <div class="p-6">
            @forelse($data['mahasiswaBimbingan'] as $kp)
                <div class="flex items-start space-x-3 mb-4 last:mb-0">
                    <div class="bg-unib-blue-100 rounded-full p-3 w-10 h-10 flex items-center justify-center">
                        <i class="fas fa-user-graduate text-unib-blue-600 text-sm"></i>
                    </div>

                    <div class="flex-1">
                        <p class="font-medium text-gray-900">{{ $kp->mahasiswa->name }}</p>
                        <p class="text-sm text-gray-600">{{ Str::limit($kp->judul_kp, 40) }}</p>

                        <div class="flex items-center justify-between mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($kp->status == 'pengajuan') bg-yellow-100 text-yellow-800
                                @elseif($kp->status == 'disetujui') bg-green-100 text-green-800
                                @elseif($kp->status == 'sedang_kp') bg-unib-blue-100 text-unib-blue-800
                                @elseif($kp->status == 'selesai') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $kp->status)) }}
                            </span>

                            <a href="{{ route('admin.kerja-praktek.show', $kp) }}"
                               class="text-unib-blue-600 hover:text-unib-blue-800 text-sm">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-4">
                    <i class="fas fa-users text-3xl text-gray-300 mb-2"></i>
                    <p>Belum ada mahasiswa yang ditugaskan untuk dibimbing</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Distribusi Status KP -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 bg-unib-blue-600 text-white rounded-t-lg">
            <h3 class="text-lg font-semibold">Distribusi Status KP</h3>
        </div>

        <div class="p-6 space-y-4">
            <!-- Pengajuan -->
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Pengajuan</span>
                <div class="flex items-center">
                    <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                        <div class="bg-yellow-600 h-2 rounded-full"
                            style="width: {{ ($data['pengajuanBaru'] / max($data['totalMahasiswaBimbingan'],1))*100 }}%">
                        </div>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ $data['pengajuanBaru'] }}</span>
                </div>
            </div>

            <!-- Sedang KP -->
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Sedang KP</span>
                <div class="flex items-center">
                    <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                        <div class="bg-unib-blue-600 h-2 rounded-full"
                            style="width: {{ ($data['sedangKP'] / max($data['totalMahasiswaBimbingan'],1))*100 }}%">
                        </div>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ $data['sedangKP'] }}</span>
                </div>
            </div>

            <!-- Selesai -->
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Selesai</span>
                <div class="flex items-center">
                    <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                        <div class="bg-gray-600 h-2 rounded-full"
                            style="width: {{ ($data['selesaiKP'] / max($data['totalMahasiswaBimbingan'],1))*100 }}%">
                        </div>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ $data['selesaiKP'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Aktivitas Hari Ini -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 bg-unib-blue-600 text-white rounded-t-lg">
            <h3 class="text-lg font-semibold">Aktivitas Hari Ini</h3>
        </div>

        <div class="p-6 max-h-64 overflow-y-auto space-y-3">
            @forelse($data['todayActivities'] as $activity)
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-{{ $activity['color'] }}-500 rounded-full"></div>
                    <div class="text-sm">
                        <span class="font-semibold text-gray-900">{{ $activity['message'] }}</span>
                        <span class="text-gray-500">{{ $activity['time'] }}</span>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-4">
                    <dotlottie-wc 
                        src="https://lottie.host/f4be40d9-971a-446d-9d51-ed1128f637ef/8YGejDufWD.lottie" 
                        style="width: 200px;height: 200px" autoplay loop>
                    </dotlottie-wc>
                    <p class="mt-2">Belum ada aktivitas hari ini</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
