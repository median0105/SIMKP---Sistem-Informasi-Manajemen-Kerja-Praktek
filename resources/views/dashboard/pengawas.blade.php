<!-- Welcome Card -->
<div class="bg-gradient-to-br from-blue-700 to-blue-600 rounded-lg p-6 text-white mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold">Selamat Datang, {{ auth()->user()->name }}</h3>
            <p class="text-unib-blue-200 mt-1 font-bold">Pembimbing Lapangan</p>
            <p class="text-unib-blue-200">Monitoring Kerja Praktek</p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-clipboard-check text-6xl text-orange-300"></i>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Mahasiswa KP -->
    {{-- <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Mahasiswa KP</p>
                <p class="text-2xl font-semibold text-green-600 mt-2">{{ $data['mahasiswaKP'] }}</p>
            </div>
            <div class="bg-green-100 rounded-full p-3">
                <i class="fas fa-users text-green-600 text-xl"></i>
            </div>
        </div>
    </div> --}}

    <!-- Laporan Pending -->
    {{-- <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Laporan Pending</p>
                <p class="text-2xl font-semibold text-yellow-600 mt-2">{{ $data['laporanPending'] }}</p>
            </div>
            <div class="bg-yellow-100 rounded-full p-3">
                <i class="fas fa-file-alt text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div> --}}

    <!-- Quick Action -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-center">
            <p class="text-sm font-medium text-gray-600 mb-3">Quick Action</p>
            <a href="{{ route('pengawas.mahasiswa.index') }}" 
               class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                <i class="fas fa-eye mr-2"></i>
                Lihat Mahasiswa KP
            </a>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Mahasiswa yang Membutuhkan Perhatian</h3>
    </div>
    <div class="p-6">
        @if($data['recentNotifications']->count() > 0)
            <div class="space-y-4">
                @foreach($data['recentNotifications'] as $notification)
                    <div class="flex items-start space-x-3 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="bg-blue-100 rounded-full p-2 mt-1">
                            <i class="fas fa-info-circle text-blue-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ $notification->title }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                            @if($notification->kerjaPraktek)
                                <p class="text-xs text-gray-500 mt-2">
                                    Mahasiswa: {{ $notification->kerjaPraktek->mahasiswa->name }} ({{ $notification->kerjaPraktek->mahasiswa->npm }})
                                </p>
                                <p class="text-xs text-gray-500">
                                    Judul KP: {{ $notification->kerjaPraktek->judul_kp }}
                                </p>
                            @endif
                            <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @if($notification->action_url)
                            <a href="{{ $notification->action_url }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Lihat Detail
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-4"></i>
                <p>Semua mahasiswa dalam kondisi baik</p>
                <p class="text-sm">Tidak ada yang memerlukan tindakan segera</p>
            </div>
        @endif
    </div>
</div>
