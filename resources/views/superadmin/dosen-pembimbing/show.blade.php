{{-- resources/views/superadmin/dosen-pembimbing/show.blade.php --}}
<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <a href="{{ route('superadmin.dosen-pembimbing.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg inline-flex items-center transition duration-200 transform hover:scale-105 backdrop-blur-sm border border-white/30">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <div class="flex items-center space-x-3">
                    <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                    </div>
                    <div>
                        <h2 class="font-bold text-xl leading-tight">
                            Detail Dosen Pembimbing: {{ $user->name }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- ========================= FLASH ========================= --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-r-lg shadow-md flex items-center animate-fade-in-up">
                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-lg shadow-md flex items-center animate-fade-in-up">
                    <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                    {{ session('error') }}
                </div>
            @endif

            {{-- ========================= INFO DOSEN ========================= --}}
            <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-4 py-3 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white rounded-t-lg -mx-6 -mt-6 mb-6">
                    <h3 class="text-lg font-bold">Informasi Dosen</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-unib-blue-800 mb-2">Nama</label>
                            <p class="text-base text-gray-900 bg-unib-blue-50 px-4 py-3 rounded-lg border border-unib-blue-200">
                                {{ $user->name }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-unib-blue-800 mb-2">Email</label>
                            <p class="text-base text-gray-900 bg-unib-blue-50 px-4 py-3 rounded-lg border border-unib-blue-200">
                                {{ $user->email }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-unib-blue-800 mb-2">NIP</label>
                            <p class="text-base text-gray-900 bg-unib-blue-50 px-4 py-3 rounded-lg border border-unib-blue-200">
                                {{ $user->nip ?? '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-unib-blue-800 mb-2">No HP</label>
                            <p class="text-base text-gray-900 bg-unib-blue-50 px-4 py-3 rounded-lg border border-unib-blue-200">
                                {{ $user->phone ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-unib-blue-800 mb-2">Status</label>
                            <div class="mt-1">
                                @if($user->is_active)
                                    <span class="inline-flex items-center px-3 py-2 rounded-full text-base font-medium bg-green-100 text-green-800 border border-green-300 shadow-sm">
                                        <i class="fas fa-check-circle mr-2"></i>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-2 rounded-full text-base font-medium bg-red-100 text-red-800 border border-red-300 shadow-sm">
                                        <i class="fas fa-times-circle mr-2"></i>Nonaktif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========================= MAHASISWA YANG DIBIMBING ========================= --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <!-- Table header with UNIB blue gradient -->
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Mahasiswa yang Dibimbing
                    </h3>
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap">
                        Total: {{ $mahasiswa->count() }}
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    Nama
                                </th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    NPM
                                </th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    Email
                                </th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    Tempat Magang
                                </th>
                                <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider whitespace-nowrap">
                                    Status KP
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($mahasiswa as $mhs)
                            <tr class="hover:bg-unib-blue-50 transition duration-150 group table-row-hover">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-gray-900 text-base group-hover:text-unib-blue-700 transition-colors">
                                        {{ $mhs->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-900 text-base group-hover:text-unib-blue-700 transition-colors">
                                        {{ $mhs->npm }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-900 text-base group-hover:text-unib-blue-700 transition-colors">
                                        {{ $mhs->email }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-900 text-base group-hover:text-unib-blue-700 transition-colors">
                                        {{ $mhs->kpTerbaru?->tempatMagang?->nama_perusahaan ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($mhs->kpTerbaru)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                            @if($mhs->kpTerbaru->status === 'disetujui') bg-green-100 text-green-800 border border-green-300 shadow-sm transform group-hover:scale-105 transition-transform
                                            @elseif($mhs->kpTerbaru->status === 'proses') bg-yellow-100 text-yellow-800 border border-yellow-300 shadow-sm transform group-hover:scale-105 transition-transform
                                            @elseif($mhs->kpTerbaru->status === 'ditolak') bg-red-100 text-red-800 border border-red-300 shadow-sm transform group-hover:scale-105 transition-transform
                                            @else bg-gray-100 text-gray-800 border border-gray-300 shadow-sm transform group-hover:scale-105 transition-transform @endif">
                                            {{ ucfirst($mhs->kpTerbaru->status) }}
                                        </span>
                                    @else
                                        <span class="text-gray-500 text-base">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-16 text-gray-500 animate-fade-in-up">
                                    <div class="flex flex-col items-center justify-center">
                                        <!-- DotLottie Animation -->
                                        <div class="mb-6 lottie-container">
                                            <dotlottie-wc 
                                                src="https://lottie.host/f4be40d9-971a-446d-9d51-ed1128f637ef/8YGejDufWD.lottie" 
                                                style="width: 300px; height: 300px;" 
                                                autoplay 
                                                loop>
                                            </dotlottie-wc>
                                        </div>
                                        <div class="text-lg font-medium text-gray-900 mb-2">Belum Ada Mahasiswa yang Dibimbing</div>
                                        <p class="text-base text-gray-600 max-w-md mx-auto">
                                            Dosen ini belum memiliki mahasiswa bimbingan.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ========================= TAMBAH MAHASISWA ========================= --}}
            <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-4 py-3 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white rounded-t-lg -mx-6 -mt-6 mb-6">
                    <h3 class="text-lg font-bold">Tambah Mahasiswa Bimbingan</h3>
                </div>
                <form method="POST" action="{{ route('superadmin.dosen-pembimbing.assign-mahasiswa', $user) }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                        <div class="md:col-span-2">
                            <label for="mahasiswa_id" class="block text-sm font-semibold text-unib-blue-800 mb-2">Pilih Mahasiswa</label>
                            <select name="mahasiswa_id" id="mahasiswa_id" 
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-unib-blue-500 focus:ring-unib-blue-500 px-4 py-3 text-base transition duration-200 border" 
                                    required>
                                <option value="">Pilih Mahasiswa</option>
                                @foreach($mahasiswaAvailable as $mhs)
                                    <option value="{{ $mhs->id }}">{{ $mhs->name }} ({{ $mhs->npm }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" 
                                    class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center justify-center flex-1">
                                <i class="fas fa-plus mr-2"></i>Tambah Mahasiswa
                            </button>
                        </div>
                    </div>
                </form>
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
        
        /* Hover effects for table rows */
        .table-row-hover {
            transition: all 0.2s ease-in-out;
        }
        
        .table-row-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        /* Style untuk dotlottie container */
        .lottie-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
        }
    </style>

    <!-- DotLottie Web Component Script -->
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>

    <script>
        // Add hover effects to table rows
        document.addEventListener('DOMContentLoaded', function() {
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.classList.add('table-row-hover');
            });
            
            // Pastikan dotlottie-wc terload dengan baik
            if (typeof customElements !== 'undefined') {
                customElements.whenDefined('dotlottie-wc').then(() => {
                    console.log('DotLottie Web Component loaded successfully');
                });
            }
        });
    </script>
</x-sidebar-layout>