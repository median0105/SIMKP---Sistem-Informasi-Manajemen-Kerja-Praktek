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
<div class="flex gap-4 mb-6">
    <!-- Total Mahasiswa -->
    <div class="bg-white rounded-lg shadow p-4 flex-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Mahasiswa</p>
                <p class="text-2xl font-semibold text-blue-600 mt-2">{{ $data['totalMahasiswa'] }}</p>
            </div>
            <div>
                <i class="fas fa-user-graduate text-blue-600 "></i>
            </div>
        </div>
    </div>

    <!-- Total Dosen Pembimbing -->
    <div class="bg-white rounded-lg shadow p-4 flex-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Dosen Pembimbing</p>
                <p class="text-2xl font-semibold text-blue-600 mt-2">{{ $data['totalDosen'] }}</p>
            </div>
            <div>
                <i class="fas fa-chalkboard-teacher text-blue-600 "></i>
            </div>
        </div>
    </div>

    <!-- Total Dosen Penguji -->
    <div class="bg-white rounded-lg shadow p-4 flex-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Dosen Penguji</p>
                <p class="text-2xl font-semibold text-teal-600 mt-2">{{ $data['totalDosenPenguji'] }}</p>
            </div>
            <div>
                <i class="fas fa-pen-alt text-teal-600 "></i>
            </div>
        </div>
    </div>

    <!-- Total Pengawas -->
    <div class="bg-white rounded-lg shadow p-4 flex-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Pengawas Lapangan</p>
                <p class="text-2xl font-semibold text-orange-600 mt-2">{{ $data['totalPengawas'] }}</p>
            </div>
            <div>
                <i class="fas fa-clipboard-check text-orange-600 "></i>
            </div>
        </div>
    </div>

    <!-- Total Tempat Magang -->
    <div class="bg-white rounded-lg shadow p-4 flex-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Tempat Magang</p>
                <p class="text-2xl font-semibold text-purple-600 mt-2">{{ $data['totalTempatMagang'] }}</p>
            </div>
            <div>
                <i class="fas fa-building text-purple-600 "></i>
            </div>
        </div>
    </div>
</div>

<!-- Management Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    <!-- Data Dosen -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-4 py-3 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-900">Data Dosen</h3>
        </div>
        <div class="p-4 space-y-2">
            <a href="{{ route('superadmin.dosen-pembimbing.index') }}"
               class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                <i class="fas fa-chalkboard-teacher mr-2"></i>
                Lihat Data Dosen Pembimbing
            </a>
            <a href="{{ route('superadmin.dosen-penguji.index') }}"
               class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                <i class="fas fa-pen-alt mr-2"></i>
                Lihat Data Dosen Penguji
            </a>
        </div>
    </div>

    <!-- Tempat Magang Management -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-4 py-3 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-900">Tempat Magang</h3>
        </div>
        <div class="p-4 space-y-2">
            <a href="{{ route('superadmin.tempat-magang.index') }}"
               class="w-full bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                <i class="fas fa-building mr-2"></i>
                Kelola Tempat Magang
            </a>
            <a href="{{ route('superadmin.tempat-magang.create') }}"
               class="w-full bg-orange-600 hover:bg-orange-700 text-white px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Tambah Tempat Baru
            </a>
        </div>
    </div>

    <!-- Reports -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-4 py-3 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-900">Laporan</h3>
        </div>
        <div class="p-4 space-y-2">
            <a href="{{ route('superadmin.laporan.index') }}"
               class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                <i class="fas fa-chart-bar mr-2"></i>
                Lihat Laporan
            </a>
            <a href="{{ route('superadmin.laporan.export-kp') }}"
               class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                <i class="fas fa-download mr-2"></i>
                Export Data KP
            </a>
        </div>
    </div>
</div>

<!-- Statistics Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    <!-- KP Status Statistics - Bar Chart -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-4 py-3 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-900">Statistik Status KP</h3>
        </div>
        <div class="p-4">
            <canvas id="statusChart" width="300" height="200"></canvas>
        </div>
    </div>

    <!-- User Distribution - Pie Chart -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-4 py-3 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-900">Distribusi User</h3>
        </div>
        <div class="p-4">
            <canvas id="userChart" width="300" height="200"></canvas>
        </div>
    </div>

    <!-- Tempat Magang Terpopuler - Bar Chart -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-4 py-3 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-900">Tempat Magang Terpopuler</h3>
        </div>
        <div class="p-4">
            <canvas id="popularChart" width="300" height="200"></canvas>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data untuk chart
    const statusData = @json($data['statistikStatus']);
    const userData = {
        mahasiswa: @json($data['totalMahasiswa']),
        dosen: @json($data['totalDosen']),
        pengawas: @json($data['totalPengawas'])
    };
    const popularData = @json($data['popularTempatMagang']);

    // Bar Chart untuk Status KP
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(statusData).map(key => key.replace('_', ' ').toUpperCase()),
            datasets: [{
                label: 'Jumlah KP',
                data: Object.values(statusData),
                backgroundColor: [
                    'rgba(255, 206, 86, 0.8)', // pengajuan - yellow
                    'rgba(54, 162, 235, 0.8)', // disetujui - blue
                    'rgba(75, 192, 192, 0.8)', // sedang_kp - green
                    'rgba(153, 102, 255, 0.8)', // selesai - purple
                    'rgba(255, 99, 132, 0.8)'   // ditolak - red
                ],
                borderColor: [
                    'rgba(255, 206, 86, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Pie Chart untuk Distribusi User
    const userCtx = document.getElementById('userChart').getContext('2d');
    new Chart(userCtx, {
        type: 'pie',
        data: {
            labels: ['Mahasiswa', 'Dosen', 'Pengawas'],
            datasets: [{
                data: [userData.mahasiswa, userData.dosen, userData.pengawas],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)', // blue
                    'rgba(75, 192, 192, 0.8)', // green
                    'rgba(255, 206, 86, 0.8)'  // yellow
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Bar Chart untuk Tempat Magang Terpopuler
    const popularCtx = document.getElementById('popularChart').getContext('2d');
    new Chart(popularCtx, {
        type: 'bar',
        data: {
            labels: popularData.map(item => item.nama.length > 20 ? item.nama.substring(0, 20) + '...' : item.nama),
            datasets: [{
                label: 'Jumlah Mahasiswa',
                data: popularData.map(item => item.total),
                backgroundColor: 'rgba(255, 159, 64, 0.8)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                },
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });
});
</script>

