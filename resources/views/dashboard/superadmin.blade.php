<!-- Welcome Card -->
<div class="bg-gradient-to-br from-unib-blue-600 to-unib-blue-700 rounded-xl p-6 text-white mb-6 shadow-lg transform transition-all duration-300 hover:shadow-xl animate-fade-in-up">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold">Super Admin Dashboard</h3>
            <p class="text-blue-100 mt-1 font-bold">{{ auth()->user()->name }}</p>
            <p class="text-blue-100">Kontrol Penuh Sistem SIMKP</p>
        </div>
        <div class="hidden md:block">
            <div class="bg-white/20 p-3 rounded-full backdrop-blur-sm">
                <dotlottie-wc 
                    src="https://lottie.host/8bb28c20-1675-4228-95a7-2eb3aaa2135f/eeQDyUQpRP.lottie" 
                    style="width: 60px; height: 60px" 
                    autoplay 
                    loop>
                </dotlottie-wc>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Row 1 -->
    <!-- Total Mahasiswa -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-unib-blue-100 transition duration-200 animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Mahasiswa</p>
                <p class="text-2xl font-bold text-yellow-600 mt-2">{{ $data['totalMahasiswa'] }}</p>
            </div>
            <div class="bg-yellow-100 rounded-full p-3 w-12 h-12 flex items-center justify-center">
                <i class="fas fa-user-graduate text-yellow-600 text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Total Dosen Pembimbing -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-unib-blue-100 transition duration-200 animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Dosen Pembimbing</p>
                <p class="text-2xl font-bold text-unib-blue-600 mt-2">{{ $data['totalDosen'] }}</p>
            </div>
            <div class="bg-unib-blue-100 rounded-full p-3 w-12 h-12 flex items-center justify-center">
                <i class="fas fa-chalkboard-teacher text-unib-blue-600 text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Total Dosen Penguji -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-unib-blue-100 transition duration-200 animate-fade-in-up" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Dosen Penguji</p>
                <p class="text-2xl font-bold text-green-600 mt-2">{{ $data['totalDosenPenguji'] }}</p>
            </div>
            <div class="bg-green-100 rounded-full p-3 w-12 h-12 flex items-center justify-center">
                <i class="fas fa-pen-alt text-green-600 text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Row 2 -->
    <!-- Total Pengawas -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-unib-blue-100 transition duration-200 animate-fade-in-up" style="animation-delay: 0.3s;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 whitespace-nowrap">Total Pengawas Lapangan</p>
                <p class="text-2xl font-bold text-teknik-orange-600 mt-2">{{ $data['totalPengawas'] }}</p>
            </div>
            <div class="bg-teknik-orange-100 rounded-full p-3 w-12 h-12 flex items-center justify-center">
                <i class="fas fa-clipboard-check text-teknik-orange-600 text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Total Tempat Magang -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-unib-blue-100 transition duration-200 animate-fade-in-up" style="animation-delay: 0.4s;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Tempat Magang</p>
                <p class="text-2xl font-bold text-purple-600 mt-2">{{ $data['totalTempatMagang'] }}</p>
            </div>
            <div class="bg-purple-100 rounded-full p-3 w-12 h-12 flex items-center justify-center">
                <i class="fas fa-building text-purple-600 text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Total Kerja Praktek -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-unib-blue-100 transition duration-200 animate-fade-in-up" style="animation-delay: 0.5s;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Kerja Praktek</p>
                <p class="text-2xl font-bold text-red-600 mt-2">{{ $data['totalKerjaPraktek'] ?? 0 }}</p>
            </div>
            <div class="bg-red-100 rounded-full p-3 w-12 h-12 flex items-center justify-center">
                <i class="fas fa-briefcase text-red-600 text-lg"></i>
            </div>
        </div>
    </div>
</div>

<!-- Management Cards -->
{{-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    <!-- Data Dosen -->
    <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 transition duration-200">
        <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
            <h3 class="text-base font-semibold text-unib-blue-800 flex items-center">
                <i class="fas fa-chalkboard-teacher mr-2"></i>
                Data Dosen
            </h3>
        </div>
        <div class="p-4 space-y-3">
            <a href="{{ route('superadmin.dosen-pembimbing.index') }}"
               class="w-full bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition duration-200 flex items-center justify-center">
                <i class="fas fa-chalkboard-teacher mr-2"></i>
                Lihat Data Dosen Pembimbing
            </a>
            <a href="{{ route('superadmin.dosen-penguji.index') }}"
               class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition duration-200 flex items-center justify-center">
                <i class="fas fa-pen-alt mr-2"></i>
                Lihat Data Dosen Penguji
            </a>
        </div>
    </div>

    <!-- Tempat Magang Management -->
    <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 transition duration-200">
        <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
            <h3 class="text-base font-semibold text-unib-blue-800 flex items-center">
                <i class="fas fa-building mr-2"></i>
                Tempat Magang
            </h3>
        </div>
        <div class="p-4 space-y-3">
            <a href="{{ route('superadmin.tempat-magang.index') }}"
               class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition duration-200 flex items-center justify-center">
                <i class="fas fa-building mr-2"></i>
                Kelola Tempat Magang
            </a>
            <a href="{{ route('superadmin.tempat-magang.create') }}"
               class="w-full bg-teknik-orange-500 hover:bg-teknik-orange-600 text-white px-4 py-3 rounded-lg text-sm font-medium transition duration-200 flex items-center justify-center">
                <i class="fas fa-plus mr-2"></i>
                Tambah Tempat Baru
            </a>
        </div>
    </div>

    <!-- Reports -->
    <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 transition duration-200">
        <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
            <h3 class="text-base font-semibold text-unib-blue-800 flex items-center">
                <i class="fas fa-chart-bar mr-2"></i>
                Laporan
            </h3>
        </div>
        <div class="p-4 space-y-3">
            <a href="{{ route('superadmin.laporan.index') }}"
               class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition duration-200 flex items-center justify-center">
                <i class="fas fa-chart-bar mr-2"></i>
                Lihat Laporan
            </a>
            <a href="{{ route('superadmin.laporan.export-kp') }}"
               class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition duration-200 flex items-center justify-center">
                <i class="fas fa-download mr-2"></i>
                Export Data KP
            </a>
        </div>
    </div>
</div> --}}

<!-- Statistics Overview -->
<div class="mb-6">
    <!-- KP Status Statistics and User Distribution - Side by Side -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- KP Status Statistics - Bar Chart -->
        <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 transform transition-all duration-300 hover:shadow-xl animate-fade-in-up">
            <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                <h3 class="text-base font-semibold text-unib-blue-800 flex items-center">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Statistik Status KP
                </h3>
            </div>
            <div class="p-4 max-h-72">
                <canvas id="statusChart" width="180" height="90" style="max-width: 100%; height: 250px;"></canvas>
            </div>
        </div>

        <!-- User Distribution - Pie Chart -->
        <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 transform transition-all duration-300 hover:shadow-xl animate-fade-in-up" style="animation-delay: 0.1s;">
            <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                <h3 class="text-base font-semibold text-unib-blue-800 flex items-center">
                    <i class="fas fa-users mr-2"></i>
                    Distribusi User
                </h3>
            </div>
            <div class="p-4 max-h-72">
                <canvas id="userChart" width="180" height="90" style="max-width: 100%; height: 250px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Tempat Magang Terpopuler - Full Width -->
    <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 transform transition-all duration-300 hover:shadow-xl animate-fade-in-up" style="animation-delay: 0.2s;">
        <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
            <h3 class="text-base font-semibold text-unib-blue-800 flex items-center">
                <i class="fas fa-trophy mr-2"></i>
                Tempat Magang Terpopuler
            </h3>
        </div>
        <div class="p-4 max-h-80">
            <canvas id="popularChart" width="300" height="100" style="max-width: 100%; height: 300px;"></canvas>
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

<script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>

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
            labels: Object.keys(statusData).map(key => {
                const labels = {
                    'pengajuan': 'Pengajuan',
                    'disetujui': 'Disetujui',
                    'sedang_kp': 'Sedang KP',
                    'selesai': 'Selesai',
                    'ditolak': 'Ditolak'
                };
                return labels[key] || key;
            }),
            datasets: [{
                label: 'Jumlah KP',
                data: Object.values(statusData),
                backgroundColor: [
                    'rgba(255, 206, 86, 0.8)', // pengajuan - yellow
                    'rgba(59, 130, 246, 0.8)', // disetujui - unib-blue
                    'rgba(34, 197, 94, 0.8)', // sedang_kp - green
                    'rgba(139, 92, 246, 0.8)', // selesai - purple
                    'rgba(239, 68, 68, 0.8)'   // ditolak - red
                ],
                borderColor: [
                    'rgba(255, 206, 86, 1)',
                    'rgba(59, 130, 246, 1)',
                    'rgba(34, 197, 94, 1)',
                    'rgba(139, 92, 246, 1)',
                    'rgba(239, 68, 68, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 10
                        }
                    }
                },
                x: {
                    ticks: {
                        maxRotation: 0,
                        minRotation: 0,
                        font: {
                            size: 10
                        }
                    }
                }
            }
        }
    });

    // Pie Chart untuk Distribusi User
    const userCtx = document.getElementById('userChart').getContext('2d');
    new Chart(userCtx, {
        type: 'doughnut',
        data: {
            labels: ['Mahasiswa', 'Dosen', 'Pengawas'],
            datasets: [{
                data: [userData.mahasiswa, userData.dosen, userData.pengawas],
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)', // unib-blue
                    'rgba(34, 197, 94, 0.8)', // green
                    'rgba(249, 115, 22, 0.8)'  // teknik-orange
                ],
                borderColor: [
                    'rgba(59, 130, 246, 1)',
                    'rgba(34, 197, 94, 1)',
                    'rgba(249, 115, 22, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
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
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
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
                        maxRotation: 0,
                        minRotation: 0,
                        autoSkip: false
                    }
                }
            }
        }
    });
});
</script>