{{-- resources/views/superadmin/laporan/index.blade.php --}}
<x-sidebar-layout>
    @php
        $startDate = ($startDate ?? now()->startOfMonth()->subMonths(5));
        $endDate   = ($endDate   ?? now());

        $stats = array_merge([
            'total_kp'              => 0,
            'kp_selesai'            => 0,
            'total_mahasiswa_aktif' => 0,
            'total_tempat_magang'   => 0,
        ], ($stats ?? []));

        $avgDuration  = $avgDuration  ?? 0;
        $statusStats  = $statusStats  ?? ['pengajuan'=>0,'disetujui'=>0,'sedang_kp'=>0,'selesai'=>0,'ditolak'=>0];
        $popularTempat= $popularTempat?? collect();
        $trendKP      = $trendKP      ?? collect();
        $topPerformers= $topPerformers?? collect();
        $failedStudents= $failedStudents?? collect();
    @endphp

    {{-- HEADER dengan UNIB blue background --}}
 <x-slot name="header">
    <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
        <div class="flex items-center space-x-3">
            <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
              
            </div>
            <div>
                <h2 class="font-bold text-xl leading-tight">
                    Laporan & Analisis
                </h2>
            </div>
        </div>
    </div>
</x-slot>
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- BUTTON EXPORT --}}
            <div class="flex space-x-3 animate-fade-in-up">
                <a href="{{ route('superadmin.laporan.export-kp') }}"
                   class="bg-unib-blue-600 hover:bg-unib-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center">
                    <i class="fas fa-download mr-2"></i> Export Data KP
                </a>

                <a href="{{ route('superadmin.laporan.export-mahasiswa') }}"
                   class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-base font-medium shadow-md transform hover:scale-105 transition duration-200 flex items-center">
                    <i class="fas fa-download mr-2"></i> Export Data Mahasiswa
                </a>
            </div>

            {{-- STATISTICS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $cards = [
                        ['title'=>'Total KP','value'=>$stats['total_kp'],'color'=>'unib-blue','icon'=>'briefcase'],
                        ['title'=>'KP Selesai','value'=>$stats['kp_selesai'],'color'=>'green','icon'=>'check-circle'],
                        ['title'=>'Mahasiswa Aktif','value'=>$stats['total_mahasiswa_aktif'],'color'=>'purple','icon'=>'users'],
                        ['title'=>'Tempat Magang','value'=>$stats['total_tempat_magang'],'color'=>'teknik-orange','icon'=>'building'],
                    ];
                @endphp

                @foreach($cards as $c)
                <div class="bg-white rounded-xl shadow-lg p-6 border border-unib-blue-100 transition duration-200 animate-fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ $c['title'] }}</p>
                            <p class="text-2xl font-bold text-{{ $c['color'] }}-600 mt-2">{{ number_format($c['value']) }}</p>
                        </div>
                        <div class="bg-{{ $c['color'] }}-100 rounded-full p-3 w-12 h-12 flex items-center justify-center">
                            <i class="fas fa-{{ $c['icon'] }} text-{{ $c['color'] }}-600 text-lg"></i>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- STATUS DISTRIBUSI --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Distribusi Status KP --}}
                <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 overflow-hidden transform transition-all duration-300 hover:shadow-xl animate-fade-in-up">
                    <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                        <h3 class="text-xl font-bold">
                            Distribusi Status KP
                        </h3>
                    </div>

                    <div class="p-6 space-y-4">
                        @foreach($statusStats as $status => $count)
                            @php
                                $percentage = $stats['total_kp'] > 0 ? ($count / $stats['total_kp']) * 100 : 0;
                                $colorClass = [
                                    'pengajuan' => 'yellow',
                                    'disetujui' => 'unib-blue', 
                                    'sedang_kp' => 'teknik-orange',
                                    'selesai'   => 'green',
                                    'ditolak'   => 'red',
                                    'tidak_lulus'=>'red'
                                ][$status] ?? 'gray';
                                
                                $statusLabels = [
                                    'pengajuan' => 'Pengajuan',
                                    'disetujui' => 'Disetujui',
                                    'sedang_kp' => 'Sedang KP',
                                    'selesai'   => 'Selesai',
                                    'ditolak'   => 'Ditolak',
                                    'tidak_lulus'=>'Tidak Lulus'
                                ];
                            @endphp

                            <div class="flex justify-between items-center group hover:bg-unib-blue-50 p-3 rounded-lg transition duration-150">
                                <div class="flex items-center gap-3">
                                    <span class="w-3 h-3 bg-{{ $colorClass }}-500 rounded-full group-hover:scale-110 transition-transform"></span>
                                    <span class="text-base font-medium text-gray-700">{{ $statusLabels[$status] ?? str_replace('_',' ',$status) }}</span>
                                </div>

                                <div class="flex items-center gap-4">
                                    <div class="w-32 bg-gray-200 rounded-full h-2">
                                        <div class="h-2 bg-{{ $colorClass }}-500 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-base font-bold text-{{ $colorClass }}-600 min-w-12 text-right">{{ $count }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- TEMPAT MAGANG TERPOPULER --}}
                <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 overflow-hidden transform transition-all duration-300 hover:shadow-xl animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                        <h3 class="text-xl font-bold">
                            Tempat Magang Terpopuler
                        </h3>
                    </div>

                    <div class="p-6 space-y-4">
                        @forelse($popularTempat as $t)
                            <div class="flex items-center justify-between p-3 rounded-lg border border-unib-blue-100 hover:bg-unib-blue-50 transition duration-150 group">
                                <div>
                                    <p class="font-semibold text-gray-800 group-hover:text-unib-blue-700 transition-colors">
                                        {{ $t->tempatMagang->nama_perusahaan ?? 'Unknown' }}
                                    </p>
                                    <p class="text-sm text-gray-600 flex items-center mt-1">
                                        <i class="fas fa-industry mr-1 text-xs text-unib-blue-400"></i>
                                        {{ $t->tempatMagang->bidang_usaha ?? '-' }}
                                    </p>
                                </div>

                                <div class="text-right">
                                    <p class="text-xl font-bold text-unib-blue-600">{{ $t->total }}</p>
                                    <p class="text-xs text-gray-500">mahasiswa</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-8">
                                <dotlottie-wc 
                                    src="https://lottie.host/f4be40d9-971a-446d-9d51-ed1128f637ef/8YGejDufWD.lottie" 
                                    style="width: 200px; height: 200px; margin: 0 auto;" 
                                    autoplay 
                                    loop>
                                </dotlottie-wc>
                                <p class="text-base font-medium text-gray-900 mt-4">Tidak ada data</p>
                                <p class="text-sm text-gray-600 mt-1">Belum ada tempat magang yang populer</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>


            {{-- TABEL MAHASISWA BERPRESTASI --}}
            <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 overflow-hidden transform transition-all duration-300 hover:shadow-xl animate-fade-in-up">
                
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Mahasiswa Berprestasi
                    </h3>
                    <a href="{{ route('superadmin.laporan.detail-kp', ['status'=>'selesai']) }}"
                       class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30 shadow-sm whitespace-nowrap transform hover:scale-105 transition duration-200">
                        Lihat Semua
                    </a>
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100 border-b-2 border-unib-blue-200">
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Rank
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Mahasiswa
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Judul KP
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Nilai
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Grade
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Status
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($topPerformers as $i => $kp)
                                    <tr class="border-b border-unib-blue-100 hover:bg-unib-blue-50 transition duration-150 group">
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-unib-blue-100 text-unib-blue-800 font-bold text-sm transform group-hover:scale-110 transition-transform">
                                                {{ $i+1 }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-semibold text-gray-900 text-base group-hover:text-unib-blue-700 transition-colors">{{ $kp->mahasiswa->name }}</p>
                                            <p class="text-sm text-gray-500 mt-1 flex items-center">
                                                <i class="fas fa-id-card mr-1 text-xs text-unib-blue-400"></i>
                                                {{ $kp->mahasiswa->npm }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-900 text-base group-hover:text-unib-blue-700 transition-colors">
                                                {{ \Illuminate\Support\Str::limit($kp->judul_kp, 50) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-gray-900 text-base group-hover:text-unib-blue-700 transition-colors">
                                                {{ $kp->nilai_akhir }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                                @if($kp->grade=='A') bg-green-100 text-green-800 border border-green-300
                                                @elseif($kp->grade=='B') bg-unib-blue-100 text-unib-blue-800 border border-unib-blue-300
                                                @elseif($kp->grade=='C') bg-yellow-100 text-yellow-800 border border-yellow-300
                                                @elseif($kp->grade=='D') bg-orange-100 text-orange-800 border border-orange-300
                                                @else bg-red-100 text-red-800 border border-red-300 @endif
                                                shadow-sm transform group-hover:scale-105 transition-transform">
                                                <i class="fas fa-graduation-cap mr-2 text-xs"></i>
                                                {{ $kp->grade }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-300 shadow-sm transform group-hover:scale-105 transition-transform">
                                                <i class="fas fa-check-circle mr-2 text-xs"></i>
                                                Selesai
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-12 text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <dotlottie-wc 
                                                    src="https://lottie.host/f4be40d9-971a-446d-9d51-ed1128f637ef/8YGejDufWD.lottie" 
                                                    style="width: 200px; height: 200px;" 
                                                    autoplay 
                                                    loop>
                                                </dotlottie-wc>
                                                <div class="text-base font-medium text-gray-900 mb-2 mt-4">Tidak Ada Data</div>
                                                <p class="text-sm text-gray-600">Tidak ada data mahasiswa berprestasi.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            {{-- TABEL MAHASISWA TIDAK LULUS --}}
            <div class="bg-white rounded-xl shadow-lg border border-unib-blue-100 overflow-hidden transform transition-all duration-300 hover:shadow-xl animate-fade-in-up">

                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-600 to-unib-blue-700 text-white flex items-center justify-between min-h-[70px]">
                    <h3 class="text-xl font-bold">
                        Mahasiswa Tidak Lulus KP
                    </h3>
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100 border-b-2 border-unib-blue-200">
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        No
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Mahasiswa
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Judul KP
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Nilai
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Grade
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($failedStudents as $i => $kp)
                                    <tr class="border-b border-unib-blue-100 hover:bg-unib-blue-50 transition duration-150 group">
                                        <td class="px-6 py-4">
                                            <span class="font-semibold text-gray-900 text-base group-hover:text-unib-blue-700 transition-colors">{{ $i+1 }}</span>
                                        </td>

                                        <td class="px-6 py-4">
                                            <p class="font-semibold text-gray-900 text-base group-hover:text-unib-blue-700 transition-colors">{{ $kp->mahasiswa->name }}</p>
                                            <p class="text-sm text-gray-500 mt-1 flex items-center">
                                                <i class="fas fa-id-card mr-1 text-xs text-unib-blue-400"></i>
                                                {{ $kp->mahasiswa->npm }}
                                            </p>
                                        </td>

                                        <td class="px-6 py-4">
                                            <span class="text-gray-900 text-base group-hover:text-unib-blue-700 transition-colors">
                                                {{ \Illuminate\Support\Str::limit($kp->judul_kp, 50) }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4">
                                            <span class="font-bold text-gray-900 text-base group-hover:text-unib-blue-700 transition-colors">
                                                {{ $kp->nilai_akhir }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-300 shadow-sm transform group-hover:scale-105 transition-transform">
                                                <i class="fas fa-times-circle mr-2 text-xs"></i>
                                                {{ $kp->grade }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-300 shadow-sm transform group-hover:scale-105 transition-transform">
                                                <i class="fas fa-times-circle mr-2 text-xs"></i>
                                                Tidak Lulus
                                            </span>
                                        </td>

                                        <td class="px-6 py-4">
                                            <span class="text-gray-900 text-base group-hover:text-unib-blue-700 transition-colors">
                                                {{ $kp->created_at->translatedFormat('d F Y') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-12 text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <dotlottie-wc 
                                                    src="https://lottie.host/f4be40d9-971a-446d-9d51-ed1128f637ef/8YGejDufWD.lottie" 
                                                    style="width: 200px; height: 200px;" 
                                                    autoplay 
                                                    loop>
                                                </dotlottie-wc>
                                                <div class="text-base font-medium text-gray-900 mb-2 mt-4">Tidak Ada Data</div>
                                                <p class="text-sm text-gray-600">Tidak ada data mahasiswa tidak lulus.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
        
        /* Styling untuk tabel yang konsisten */
        table {
            border-collapse: separate;
            border-spacing: 0;
        }
        
        table th, table td {
            border-bottom: 1px solid #e5e7eb;
        }
        
        table tr:last-child td {
            border-bottom: none;
        }
        
        /* Konsistensi dengan Distribusi Status KP */
        .bg-unib-blue-50 { background-color: #eff6ff; }
        .bg-unib-blue-100 { background-color: #dbeafe; }
        .bg-unib-blue-200 { background-color: #bfdbfe; }
        .border-unib-blue-100 { border-color: #dbeafe; }
        .border-unib-blue-200 { border-color: #bfdbfe; }
        .text-unib-blue-800 { color: #1e40af; }
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
        });
    </script>
</x-sidebar-layout>