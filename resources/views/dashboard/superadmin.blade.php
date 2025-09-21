<!-- Welcome Card -->
<div class="bg-gradient-to-br from-blue-700 to-blue-600 rounded-lg p-6 text-white mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold">Super Admin Dashboard</h3>
            <p class="text-unib-blue-200 mt-1 font-bold">{{ auth()->user()->name }}</p>
            <p class="text-unib-blue-200">Kontrol Penuh Sistem SIMKP</p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-user-shield text-6xl text-purple-300"></i>
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
                <p class="text-2xl font-semibold text-blue-600 mt-2">{{ $data['totalMahasiswa'] }}</p>
            </div>
            <div class="bg-blue-100 rounded-full p-3">
                <i class="fas fa-user-graduate text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Dosen -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Dosen</p>
                <p class="text-2xl font-semibold text-green-600 mt-2">{{ $data['totalDosen'] }}</p>
            </div>
            <div class="bg-green-100 rounded-full p-3">
                <i class="fas fa-chalkboard-teacher text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Pengawas -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Pengawas Lapangan</p>
                <p class="text-2xl font-semibold text-orange-600 mt-2">{{ $data['totalPengawas'] }}</p>
            </div>
            <div class="bg-orange-100 rounded-full p-3">
                <i class="fas fa-clipboard-check text-orange-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Tempat Magang -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Tempat Magang</p>
                <p class="text-2xl font-semibold text-purple-600 mt-2">{{ $data['totalTempatMagang'] }}</p>
            </div>
            <div class="bg-purple-100 rounded-full p-3">
                <i class="fas fa-building text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Management Cards -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- User Management -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Manajemen User</h3>
        </div>
        <div class="p-6 space-y-3">
            <a href="{{ route('superadmin.users.index') }}" 
               class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <i class="fas fa-users mr-3"></i>
                Kelola Users
            </a>
            <a href="{{ route('superadmin.users.create') }}" 
               class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <i class="fas fa-user-plus mr-3"></i>
                Tambah User Baru
            </a>
        </div>
    </div>

    <!-- Tempat Magang Management -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Tempat Magang</h3>
        </div>
        <div class="p-6 space-y-3">
            <a href="{{ route('superadmin.tempat-magang.index') }}" 
               class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <i class="fas fa-building mr-3"></i>
                Kelola Tempat Magang
            </a>
            <a href="{{ route('superadmin.tempat-magang.create') }}" 
               class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <i class="fas fa-plus mr-3"></i>
                Tambah Tempat Baru
            </a>
        </div>
    </div>

    <!-- Reports -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Laporan</h3>
        </div>
        <div class="p-6 space-y-3">
            <a href="{{ route('superadmin.laporan.index') }}" 
               class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <i class="fas fa-chart-bar mr-3"></i>
                Lihat Laporan
            </a>
            <a href="{{ route('superadmin.laporan.export-kp') }}" 
               class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <i class="fas fa-download mr-3"></i>
                Export Data KP
            </a>
        </div>
    </div>
</div>

<!-- Statistics Overview -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- KP Status Statistics -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Statistik Status KP</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($data['statistikStatus'] as $status => $count)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 capitalize">{{ str_replace('_', ' ', $status) }}</span>
                        <div class="flex items-center">
                            <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                <div class="h-2 rounded-full 
                                    @switch($status)
                                        @case('pengajuan') bg-yellow-500 @break
                                        @case('disetujui') bg-blue-500 @break
                                        @case('sedang_kp') bg-green-500 @break
                                        @case('selesai') bg-gray-500 @break
                                        @case('ditolak') bg-red-500 @break
                                    @endswitch
                                " style="width: {{ $count > 0 ? ($count / max($data['totalKerjaPraktek'], 1) * 100) : 0 }}%"></div>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $count }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- User Distribution -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Distribusi User</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Mahasiswa</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: 85%"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $data['totalMahasiswa'] }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Dosen</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-green-500 h-2 rounded-full" style="width: 10%"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $data['totalDosen'] }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Pengawas</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-orange-500 h-2 rounded-full" style="width: 5%"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $data['totalPengawas'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>