{{-- resources/views/pengawas/kuisioner/analytics.blade.php --}}
<x-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Analytics Kuisioner
            </h2>
            <a href="{{ route('pengawas.kuisioner.index') }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Overall Statistics --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-600">Total Feedback</p>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $analytics['feedback_stats']['total_feedback'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-600">Rata-rata Rating Mahasiswa</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($analytics['feedback_stats']['avg_rating_mahasiswa'], 1) }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-600">Rekomendasi Positif</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $analytics['feedback_stats']['rekomendasi_positif'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-600">Persentase Rekomendasi</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-2">
                        @if($analytics['feedback_stats']['total_feedback'] > 0)
                            {{ number_format(($analytics['feedback_stats']['rekomendasi_positif'] / $analytics['feedback_stats']['total_feedback']) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </p>
                </div>
            </div>

            {{-- Rating Distribution Chart --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Distribusi Rating Tempat Magang</h3>
                </div>
                <div class="p-6">
                    @if($analytics['rating_distribution']->count() > 0)
                        <div class="space-y-4">
                            @foreach($analytics['rating_distribution'] as $rating)
                                <div class="flex items-center">
                                    <div class="w-12 text-sm font-medium text-gray-700">{{ $rating->rating_tempat_magang }}</div>
                                    <div class="flex-1 mx-4">
                                        <div class="bg-gray-200 rounded-full h-4">
                                            <div class="bg-blue-500 h-4 rounded-full" style="width: {{ ($rating->count / $analytics['rating_distribution']->sum('count')) * 100 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="w-12 text-sm text-gray-600 text-right">{{ $rating->count }}</div>
                                </div>
                            @endfor
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">Belum ada data rating</p>
                    @endif
                </div>
            </div>

            {{-- Top Rated Companies --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Tempat Magang Terbaik</h3>
                    <p class="text-sm text-gray-600">Berdasarkan rata-rata rating (minimal 2 review)</p>
                </div>
                <div class="p-6">
                    @if($analytics['tempat_terbaik']->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Perusahaan</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rata-rata Rating</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total Review</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($analytics['tempat_terbaik'] as $tempat)
                                    <tr>
                                        <td class="px-4 py-2 font-medium text-gray-900">{{ $tempat->tempatMagang->nama_perusahaan ?? 'N/A' }}</td>
                                        <td class="px-4 py-2">
                                            <div class="flex items-center">
                                                <span class="text-lg font-bold text-green-600 mr-2">{{ number_format($tempat->avg_rating, 1) }}</span>
                                                <div class="flex">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= round($tempat->avg_rating) ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-2 text-gray-700">{{ $tempat->total_reviews }}</td>
                                    </tr>
                                @endfor
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">Belum ada data tempat magang dengan rating</p>
                    @endif
                </div>
            </div>

            {{-- Feedback Summary --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Common Strengths --}}
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Kelebihan Mahasiswa Terbanyak</h3>
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
                            <div class="space-y-3">
                                @foreach($strengths as $strength)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-700">{{ $strength->kelebihan_mahasiswa }}</span>
                                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">{{ $strength->count }}</span>
                                    </div>
                                @endfor
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">Belum ada data kelebihan</p>
                        @endif
                    </div>
                </div>

                {{-- Common Weaknesses --}}
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Kekurangan Mahasiswa Terbanyak</h3>
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
                            <div class="space-y-3">
                                @foreach($weaknesses as $weakness)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-700">{{ $weakness->kekurangan_mahasiswa }}</span>
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">{{ $weakness->count }}</span>
                                    </div>
                                @endfor
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">Belum ada data kekurangan</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Recent Feedback --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Feedback Terbaru</h3>
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
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $feedback->kerjaPraktek->mahasiswa->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $feedback->kerjaPraktek->tempatMagang->nama_perusahaan ?? $feedback->kerjaPraktek->tempat_magang_sendiri }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-gray-500">{{ $feedback->tanggal_feedback->format('d/m/Y') }}</p>
                                            <div class="flex items-center mt-1">
                                                <span class="text-lg font-bold text-blue-600 mr-1">{{ $feedback->rating_mahasiswa }}</span>
                                                <i class="fas fa-star text-yellow-400"></i>
                                            </div>
                                        </div>
                                    </div>
                                    @if($feedback->komentar_kinerja)
                                        <p class="text-sm text-gray-700 mb-2">{{ Str::limit($feedback->komentar_kinerja, 150) }}</p>
                                    @endif
                                    <div class="flex items-center space-x-4 text-xs">
                                        @if($feedback->rekomendasi_mahasiswa)
                                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full">Direkomendasikan</span>
                                        @else
                                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full">Tidak Direkomendasikan</span>
                                        @endif
                                    </div>
                                </div>
                            @endfor
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">Belum ada feedback</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-sidebar-layout>
