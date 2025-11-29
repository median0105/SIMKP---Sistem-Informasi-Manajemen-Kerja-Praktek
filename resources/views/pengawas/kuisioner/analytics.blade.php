{{-- resources/views/pengawas/kuisioner/analytics.blade.php --}}
<x-sidebar-layout>
    {{-- Header section with UNIB blue background --}}
    <x-slot name="header">
        <div class="flex items-center justify-between bg-unib-blue-600 text-white p-3 rounded-lg shadow-lg">
            <div class="flex items-center space-x-3">
                <a href="{{ route('pengawas.kuisioner.index') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg inline-flex items-center transition duration-200 backdrop-blur-sm border border-white/30">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                    <!-- Ikon dihapus -->
                </div>
                <div>
                    <h2 class="font-bold text-xl leading-tight">
                        Analytics Kuisioner
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-unib-blue-50 to-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Overall Statistics --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Feedback</p>
                            <p class="text-2xl font-bold text-unib-blue-600 mt-2">{{ $analytics['feedback_stats']['total_feedback'] }}</p>
                        </div>
                        <div class="bg-unib-blue-100 rounded-full p-3 w-12 h-12 flex items-center justify-center">
                            <!-- Ikon dihapus -->
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Rata-rata Rating Mahasiswa</p>
                            <p class="text-2xl font-bold text-green-600 mt-2">{{ number_format($analytics['feedback_stats']['avg_rating_mahasiswa'], 1) }}</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-3 w-12 h-12 flex items-center justify-center">
                            <!-- Ikon dihapus -->
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Rekomendasi Positif</p>
                            <p class="text-2xl font-bold text-purple-600 mt-2">{{ $analytics['feedback_stats']['rekomendasi_positif'] }}</p>
                        </div>
                        <div class="bg-purple-100 rounded-full p-3 w-12 h-12 flex items-center justify-center">
                            <!-- Ikon dihapus -->
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-xl rounded-xl p-6 border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up" style="animation-delay: 0.3s;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Persentase Rekomendasi</p>
                            <p class="text-2xl font-bold text-indigo-600 mt-2">
                                @if($analytics['feedback_stats']['total_feedback'] > 0)
                                    {{ number_format(($analytics['feedback_stats']['rekomendasi_positif'] / $analytics['feedback_stats']['total_feedback']) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </p>
                        </div>
                        <div class="bg-indigo-100 rounded-full p-3 w-12 h-12 flex items-center justify-center">
                            <!-- Ikon dihapus -->
                        </div>
                    </div>
                </div>
            </div>

            {{-- Rating Distribution Chart --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                    <h3 class="text-base font-semibold text-unib-blue-800">
                        Distribusi Rating Tempat Magang
                    </h3>
                </div>
                <div class="p-6">
                    @if($analytics['rating_distribution']->count() > 0)
                        <div class="space-y-4">
                            @foreach($analytics['rating_distribution'] as $rating)
                                <div class="flex items-center">
                                    <div class="w-12 text-sm font-medium text-gray-700">{{ $rating->rating_tempat_magang }}</div>
                                    <div class="flex-1 mx-4">
                                        <div class="bg-gray-200 rounded-full h-4">
                                            <div class="bg-unib-blue-500 h-4 rounded-full" style="width: {{ ($rating->count / $analytics['rating_distribution']->sum('count')) * 100 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="w-12 text-sm text-gray-600 text-right">{{ $rating->count }}</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-chart-bar text-4xl mb-4 text-gray-300"></i>
                            <div class="text-base font-medium text-gray-900 mb-2">Belum Ada Data</div>
                            <p class="text-sm text-gray-600">Belum ada data rating</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Top Rated Companies --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                    <h3 class="text-base font-semibold text-unib-blue-800">
                        Tempat Magang Terbaik
                    </h3>
                    <p class="text-sm text-unib-blue-600 mt-1">Berdasarkan rata-rata rating (minimal 2 review)</p>
                </div>
                <div class="p-6">
                    @if($analytics['tempat_terbaik']->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">Perusahaan</th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">Rata-rata Rating</th>
                                    <th class="px-6 py-4 text-left text-base font-semibold text-unib-blue-800 uppercase tracking-wider">Total Review</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($analytics['tempat_terbaik'] as $tempat)
                                    <tr class="hover:bg-unib-blue-50 transition duration-150">
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-900 text-base">{{ $tempat->tempatMagang->nama_perusahaan ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <span class="text-lg font-bold text-green-600 mr-3">{{ number_format($tempat->avg_rating, 1) }}</span>
                                                <div class="flex">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= round($tempat->avg_rating) ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-unib-blue-100 text-unib-blue-800 border border-unib-blue-300 shadow-sm">
                                                {{ $tempat->total_reviews }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-building text-4xl mb-4 text-gray-300"></i>
                            <div class="text-base font-medium text-gray-900 mb-2">Belum Ada Data</div>
                            <p class="text-sm text-gray-600">Belum ada data tempat magang dengan rating</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Feedback Summary --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Common Strengths --}}
                <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                    <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                        <h3 class="text-base font-semibold text-unib-blue-800">
                            Kelebihan Mahasiswa Terbanyak
                        </h3>
                    </div>
                    <div class="p-6">
                        @php
                            $strengths = \App\Models\KuisionerPembimbingLapangan::whereNotNull('kelebihan_mahasiswa')
                                ->selectRaw('kelebihan_mahasiswa, COUNT(*) as count')
                                ->groupBy('kelebihan_mahasiswa')
                                ->orderByDesc('count')
                                ->limit(5)
                                ->get();
                        @endphp
                        @if($strengths->count() > 0)
                            <div class="space-y-4">
                                @foreach($strengths as $strength)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <span class="text-sm text-gray-700 font-medium">{{ $strength->kelebihan_mahasiswa }}</span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-300 shadow-sm">
                                            {{ $strength->count }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-thumbs-up text-4xl mb-4 text-gray-300"></i>
                                <div class="text-base font-medium text-gray-900 mb-2">Belum Ada Data</div>
                                <p class="text-sm text-gray-600">Belum ada data kelebihan</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Common Weaknesses --}}
                <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                    <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                        <h3 class="text-base font-semibold text-unib-blue-800">
                            Kekurangan Mahasiswa Terbanyak
                        </h3>
                    </div>
                    <div class="p-6">
                        @php
                            $weaknesses = \App\Models\KuisionerPembimbingLapangan::whereNotNull('kekurangan_mahasiswa')
                                ->selectRaw('kekurangan_mahasiswa, COUNT(*) as count')
                                ->groupBy('kekurangan_mahasiswa')
                                ->orderByDesc('count')
                                ->limit(5)
                                ->get();
                        @endphp
                        @if($weaknesses->count() > 0)
                            <div class="space-y-4">
                                @foreach($weaknesses as $weakness)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <span class="text-sm text-gray-700 font-medium">{{ $weakness->kekurangan_mahasiswa }}</span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-300 shadow-sm">
                                            {{ $weakness->count }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-thumbs-down text-4xl mb-4 text-gray-300"></i>
                                <div class="text-base font-medium text-gray-900 mb-2">Belum Ada Data</div>
                                <p class="text-sm text-gray-600">Belum ada data kekurangan</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Recent Feedback --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-unib-blue-100 transform transition-all duration-300 hover:shadow-lg animate-fade-in-up">
                <div class="px-6 py-4 border-b border-unib-blue-200 bg-gradient-to-r from-unib-blue-50 to-unib-blue-100">
                    <h3 class="text-base font-semibold text-unib-blue-800">
                        Feedback Terbaru
                    </h3>
                </div>
                <div class="p-6">
                    @php
                        $recentFeedback = \App\Models\KuisionerPembimbingLapangan::with(['kerjaPraktek.mahasiswa', 'kerjaPraktek.tempatMagang'])
                            ->orderByDesc('tanggal_feedback')
                            ->limit(10)
                            ->get();
                    @endphp
                    @if($recentFeedback->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentFeedback as $feedback)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-150">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <p class="font-medium text-gray-900 text-base">{{ $feedback->kerjaPraktek->mahasiswa->name }}</p>
                                            <p class="text-sm text-gray-600 mt-1 flex items-center">
                                                <i class="fas fa-building mr-2 text-xs text-unib-blue-400"></i>
                                                {{ $feedback->kerjaPraktek->tempatMagang->nama_perusahaan ?? $feedback->kerjaPraktek->tempat_magang_sendiri }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-gray-500">{{ $feedback->tanggal_feedback->format('d/m/Y') }}</p>
                                            <div class="flex items-center mt-2 justify-end">
                                                <span class="text-lg font-bold text-unib-blue-600 mr-2">{{ $feedback->rating_mahasiswa }}</span>
                                                <i class="fas fa-star text-yellow-400"></i>
                                            </div>
                                        </div>
                                    </div>
                                    @if($feedback->komentar_kinerja)
                                        <p class="text-sm text-gray-700 mb-3 bg-gray-50 rounded-lg p-3 border border-gray-200">
                                            {{ Str::limit($feedback->komentar_kinerja, 150) }}
                                        </p>
                                    @endif
                                    <div class="flex items-center">
                                        @if($feedback->rekomendasi_mahasiswa)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-300 shadow-sm">
                                                <i class="fas fa-check mr-2 text-xs"></i>Direkomendasikan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-300 shadow-sm">
                                                <i class="fas fa-times mr-2 text-xs"></i>Tidak Direkomendasikan
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-comments text-4xl mb-4 text-gray-300"></i>
                            <div class="text-base font-medium text-gray-900 mb-2">Belum Ada Data</div>
                            <p class="text-sm text-gray-600">Belum ada feedback</p>
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
</x-sidebar-layout>