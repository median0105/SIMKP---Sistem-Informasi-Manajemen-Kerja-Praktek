<!-- Welcome Card -->
<div class="bg-gradient-to-br from-blue-700 to-blue-600 rounded-lg p-6 text-white mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold">Selamat Datang, {{ auth()->user()->name }}</h3>
            <p class="text-unib-blue-200 mt-1 font-bold">Dosen Pembimbing</p>
            <p class="text-unib-blue-200 ">Sistem Informasi Manajemen Kerja Praktek</p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-chalkboard-teacher text-6xl text-green-300"></i>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Mahasiswa -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Mahasiswa</p>
                <p class="text-2xl font-semibold text-gray-900 mt-2">{{ $data['totalMahasiswa'] }}</p>
            </div>
            <div class="bg-blue-100 rounded-full p-3">
                <i class="fas fa-users text-blue-600 text-xl"></i>
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
            <div class="bg-yellow-100 rounded-full p-3">
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
            <div class="bg-green-100 rounded-full p-3">
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
            <div class="bg-gray-100 rounded-full p-3">
                <i class="fas fa-flag-checkered text-gray-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Quick Actions Card -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
        </div>
        <div class="p-6 space-y-3">
            <a href="{{ route('admin.kerja-praktek.index', ['status' => 'pengajuan']) }}" 
               class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <i class="fas fa-eye mr-3"></i>
                Review Pengajuan ({{ $data['pengajuanBaru'] }})
            </a>
            <a href="{{ route('admin.bimbingan.index', ['status' => 'pending']) }}" 
               class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium transition duration-200 flex items-center">
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

    <!-- Recent Activities -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Pengajuan Terbaru</h3>
        </div>
        <div class="p-6">
            @forelse($data['pengajuanTerbaru'] as $pengajuan)
                <div class="flex items-start space-x-3 mb-4 last:mb-0">
                    <div class="bg-yellow-100 rounded-full p-2 mt-1">
                        <i class="fas fa-file-alt text-yellow-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">{{ $pengajuan->mahasiswa->name }}</p>
                        <p class="text-sm text-gray-600">{{ Str::limit($pengajuan->judul_kp, 40) }}</p>
                        <div class="flex items-center justify-between mt-2">
                            <p class="text-xs text-gray-500">{{ $pengajuan->created_at->diffForHumans() }}</p>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.kerja-praktek.show', $pengajuan) }}"
                                   class="text-unib-blue-600 hover:text-unib-blue-800 text-sm">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-4">
                    <i class="fas fa-inbox text-3xl text-gray-300 mb-2"></i>
                    <p>Tidak ada pengajuan baru</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Seminar Registrations -->
@if($data['seminarRegistrations']->count() > 0)
<div class="grid grid-cols-1 lg:grid-cols-1 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Pendaftaran Seminar Terbaru</h3>
        </div>
        <div class="p-6">
            @forelse($data['seminarRegistrations'] as $registration)
                <div class="flex items-start space-x-3 mb-4 last:mb-0">
                    <div class="bg-blue-100 rounded-full p-2 mt-1">
                        <i class="fas fa-graduation-cap text-blue-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">{{ $registration->mahasiswa->name }}</p>
                        <p class="text-sm text-gray-600">telah mendaftar seminar kerja praktek</p>
                        <p class="text-sm text-gray-600 font-medium">{{ Str::limit($registration->judul_kp, 50) }}</p>
                        <div class="flex items-center justify-between mt-2">
                            <p class="text-xs text-gray-500">{{ $registration->updated_at->diffForHumans() }}</p>
                            <div class="flex space-x-2">
                                @if(!$registration->acc_pendaftaran_seminar)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Menunggu ACC
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Sudah ACC
                                    </span>
                                @endif
                                <a href="{{ route('admin.kerja-praktek.show', $registration) }}"
                                   class="text-unib-blue-600 hover:text-unib-blue-800 text-sm">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-4">
                    <i class="fas fa-graduation-cap text-3xl text-gray-300 mb-2"></i>
                    <p>Tidak ada pendaftaran seminar baru</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endif

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Status Distribution -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Distribusi Status KP</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Pengajuan</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $data['pengajuanBaru'] > 0 ? ($data['pengajuanBaru'] / max($data['totalMahasiswa'], 1) * 100) : 0 }}%"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $data['pengajuanBaru'] }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Sedang KP</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $data['sedangKP'] > 0 ? ($data['sedangKP'] / max($data['totalMahasiswa'], 1) * 100) : 0 }}%"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $data['sedangKP'] }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Selesai</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-gray-600 h-2 rounded-full" style="width: {{ $data['selesaiKP'] > 0 ? ($data['selesaiKP'] / max($data['totalMahasiswa'], 1) * 100) : 0 }}%"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $data['selesaiKP'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mahasiswa Bimbingan ACC -->
    {{-- <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Mahasiswa Bimbingan yang Sudah ACC</h3>
        </div>
        <div class="p-6 max-h-96 overflow-y-auto">
            @if($data['mahasiswaBimbinganAcc']->isEmpty())
                <p class="text-center text-gray-500">Belum ada mahasiswa bimbingan yang di-ACC.</p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($data['mahasiswaBimbinganAcc'] as $kp)
                        <li class="py-3 flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $kp->mahasiswa->name }}</p>
                                <p class="text-sm text-gray-600">{{ Str::limit($kp->judul_kp, 50) }}</p>
                            </div>
                            <a href="{{ route('admin.kerja-praktek.show', $kp->id) }}" class="text-blue-600 hover:underline text-sm">Detail</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div> --}}

    <!-- Timeline Today -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Aktivitas Hari Ini</h3>
        </div>
        <div class="p-6">
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @forelse($data['todayActivities'] as $activity)
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-{{ $activity['color'] }}-500 rounded-full"></div>
                        <div class="text-sm">
                            <span class="text-sm font-semibold text-gray-900">{{ $activity['message'] }}</span>
                            <span class="text-gray-500">{{ $activity['time'] }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-4">
                        <i class="fas fa-calendar-day text-3xl text-gray-300 mb-2"></i>
                        <p>Belum ada aktivitas hari ini</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>