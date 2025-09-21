<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMKP - Sistem Informasi Manajemen Kerja Praktek</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <!-- Header -->
    <header class="bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 shadow-lg">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <img class="h-10 w-auto" src="{{ asset('storage/unib.png') }}" alt="UNIB Logo">
                    <div class="ml-4">
                        <h1 class="text-white text-xl font-bold">SIMKP</h1>
                        <p class="text-unib-blue-200 text-sm">Universitas Bengkulu</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-teknik-orange-500 hover:bg-teknik-orange-600 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-white hover:text-unib-blue-200 font-medium">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-teknik-orange-500 hover:bg-teknik-orange-600 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                                    Daftar
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-unib-blue-50 to-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-unib-blue-800 mb-6">
                    Sistem Informasi<br>
                    <span class="text-teknik-orange-500">Manajemen Kerja Praktek</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Platform digital untuk mengelola seluruh proses kerja praktek mahasiswa Sistem Informasi Fakultas Teknik Universitas Bengkulu secara efisien dan terintegrasi.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('login') }}" class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition duration-200 shadow-lg">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Masuk ke SIMKP
                    </a>
                    <a href="#features" class="border-2 border-unib-blue-600 text-unib-blue-600 hover:bg-unib-blue-600 hover:text-white px-8 py-4 rounded-lg font-semibold text-lg transition duration-200">
                        <i class="fas fa-info-circle mr-2"></i>
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-unib-blue-800 mb-4">
                    Fitur Utama SIMKP
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Solusi lengkap untuk mengelola proses kerja praktek dari pengajuan hingga evaluasi
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gradient-to-br from-unib-blue-50 to-unib-blue-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300">
                    <div class="bg-unib-blue-600 w-16 h-16 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-file-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-unib-blue-800 mb-3">Pengajuan KP Online</h3>
                    <p class="text-gray-600">Mahasiswa dapat mengajukan judul dan tempat kerja praktek secara online dengan mudah dan cepat.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-gradient-to-br from-teknik-orange-50 to-teknik-orange-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300">
                    <div class="bg-teknik-orange-500 w-16 h-16 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-building text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-unib-blue-800 mb-3">Database Tempat Magang</h3>
                    <p class="text-gray-600">Akses ke database lengkap tempat magang yang telah bekerja sama dengan fakultas.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300">
                    <div class="bg-green-500 w-16 h-16 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-unib-blue-800 mb-3">Sistem Bimbingan</h3>
                    <p class="text-gray-600">Platform bimbingan terintegrasi antara mahasiswa, dosen pembimbing, dan pengawas lapangan.</p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300">
                    <div class="bg-purple-500 w-16 h-16 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-tasks text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-unib-blue-800 mb-3">Monitoring Kegiatan</h3>
                    <p class="text-gray-600">Pencatatan dan monitoring kegiatan harian mahasiswa selama kerja praktek.</p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300">
                    <div class="bg-red-500 w-16 h-16 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-file-upload text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-unib-blue-800 mb-3">Upload Laporan</h3>
                    <p class="text-gray-600">Sistem upload dan verifikasi laporan kerja praktek secara digital.</p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300">
                    <div class="bg-yellow-500 w-16 h-16 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-chart-bar text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-unib-blue-800 mb-3">Evaluasi & Kuisioner</h3>
                    <p class="text-gray-600">Sistem evaluasi dan feedback untuk perbaikan proses kerja praktek berkelanjutan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- User Roles Section -->
    <section class="py-20 bg-gradient-to-br from-unib-blue-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-unib-blue-800 mb-4">
                    Multi-User System
                </h2>
                <p class="text-xl text-gray-600">
                    Sistem dengan 4 tingkat akses untuk mengelola seluruh stakeholder
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Mahasiswa -->
                <div class="bg-white p-6 rounded-xl shadow-lg text-center">
                    <div class="bg-blue-500 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-graduate text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-unib-blue-800 mb-2">Mahasiswa</h3>
                    <p class="text-gray-600 text-sm">Pengajuan KP, upload kegiatan, bimbingan, dan laporan</p>
                </div>

                <!-- Admin Dosen -->
                <div class="bg-white p-6 rounded-xl shadow-lg text-center">
                    <div class="bg-green-500 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chalkboard-teacher text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-unib-blue-800 mb-2">Dosen</h3>
                    <p class="text-gray-600 text-sm">Verifikasi pengajuan dan pembimbingan mahasiswa</p>
                </div>

                <!-- Super Admin -->
                <div class="bg-white p-6 rounded-xl shadow-lg text-center">
                    <div class="bg-red-500 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-shield text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-unib-blue-800 mb-2">Super Admin/Prodi </h3>
                    <p class="text-gray-600 text-sm">Kelola seluruh sistem dan data master</p>
                </div>

                <!-- Pengawas Lapangan -->
                <div class="bg-white p-6 rounded-xl shadow-lg text-center">
                    <div class="bg-orange-500 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clipboard-check text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-unib-blue-800 mb-2">Pengawas Lapangan</h3>
                    <p class="text-gray-600 text-sm">Monitoring dan evaluasi di tempat kerja praktek</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-unib-blue-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <img class="h-8 w-auto" src="{{ asset('storage/unib.png') }}" alt="UNIB Logo">
                        <div class="ml-3">
                            <div class="text-white font-bold text-lg">SIMKP</div>
                            <div class="text-unib-blue-200 text-sm">Universitas Bengkulu</div>
                        </div>
                    </div>
                    <p class="text-unib-blue-200">
                        Sistem Informasi Manajemen Kerja Praktek Fakultas Teknik Universitas Bengkulu.
                    </p>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                    <div class="space-y-2 text-unib-blue-200">
                        <p><i class="fas fa-map-marker-alt mr-2"></i> Jl. WR Supratman, Bengkulu</p>
                        <p><i class="fas fa-phone mr-2"></i> (0736) 344087</p>
                        <p><i class="fas fa-envelope mr-2"></i> sisteminformasi@unib.ac.id</p>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <div class="space-y-2">
                        <a href="#" class="block text-unib-blue-200 hover:text-white transition">Panduan Penggunaan</a>
                        <a href="#" class="block text-unib-blue-200 hover:text-white transition">FAQ</a>
                        <a href="#" class="block text-unib-blue-200 hover:text-white transition">Dukungan Teknis</a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-unib-blue-700 mt-8 pt-8 text-center text-unib-blue-200">
                <p>&copy; {{ date('Y') }} Prodi Sistem Informasi Fakultas Teknik Universitas Bengkulu. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling untuk anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>