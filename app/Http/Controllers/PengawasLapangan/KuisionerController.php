<?php

namespace App\Http\Controllers\PengawasLapangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KerjaPraktek;
use App\Models\Kuisioner;
use App\Models\KuisionerPembimbingLapangan;

class KuisionerController extends Controller
{
    public function index(Request $request)
    {
        // Get all completed KP untuk melihat kuisioner
        $query = KerjaPraktek::with(['mahasiswa', 'tempatMagang', 'kuisioner'])
                             ->where('status', 'selesai');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('mahasiswa', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('tempatMagang', function($q) use ($search) {
                    $q->where('nama_perusahaan', 'like', "%{$search}%");
                });
            });
        }

        $kerjaPraktek = $query->orderBy('updated_at', 'desc')->paginate(15);

        // Statistics
        $stats = [
            'total_selesai_kp' => KerjaPraktek::where('status', 'selesai')->count(),
            'sudah_isi_kuisioner' => KerjaPraktek::where('status', 'selesai')
                                                ->whereHas('kuisioner')
                                                ->count(),
            'belum_isi_kuisioner' => KerjaPraktek::where('status', 'selesai')
                                                 ->whereDoesntHave('kuisioner')
                                                 ->count(),
            'rata_rating' => Kuisioner::avg('rating_tempat_magang') ?? 0,
        ];

        return view('pengawas.kuisioner.index', compact('kerjaPraktek', 'stats'));
    }

    public function show(KerjaPraktek $kerjaPraktek)
    {
        $kerjaPraktek->load(['mahasiswa', 'tempatMagang', 'kuisioner']);
        
        if (!$kerjaPraktek->kuisioner) {
            return back()->with('error', 'Mahasiswa belum mengisi kuisioner.');
        }

        return view('pengawas.kuisioner.show', compact('kerjaPraktek'));
    }

    public function createFeedback(KerjaPraktek $kerjaPraktek)
    {
        if (!$kerjaPraktek->kuisioner) {
            return back()->with('error', 'Mahasiswa belum mengisi kuisioner.');
        }

        $existingFeedback = KuisionerPembimbingLapangan::where('kerja_praktek_id', $kerjaPraktek->id)->first();

        return view('pengawas.kuisioner.feedback', compact('kerjaPraktek', 'existingFeedback'));
    }

    public function storeFeedback(Request $request, KerjaPraktek $kerjaPraktek)
    {
        $request->validate([
            'rating_mahasiswa' => 'required|integer|min:1|max:5',
            'komentar_kinerja' => 'required|string|max:1000',
            'saran_mahasiswa' => 'nullable|string|max:1000',
            'rekomendasi_mahasiswa' => 'required|boolean',
            'kelebihan_mahasiswa' => 'nullable|string|max:500',
            'kekurangan_mahasiswa' => 'nullable|string|max:500',
        ]);

        KuisionerPembimbingLapangan::updateOrCreate(
            ['kerja_praktek_id' => $kerjaPraktek->id],
            [
                'pembimbing_lapangan_id' => auth()->id(),
                'rating_mahasiswa' => $request->rating_mahasiswa,
                'komentar_kinerja' => $request->komentar_kinerja,
                'saran_mahasiswa' => $request->saran_mahasiswa,
                'rekomendasi_mahasiswa' => $request->rekomendasi_mahasiswa,
                'kelebihan_mahasiswa' => $request->kelebihan_mahasiswa,
                'kekurangan_mahasiswa' => $request->kekurangan_mahasiswa,
                'tanggal_feedback' => now(),
            ]
        );

        return back()->with('success', 'Feedback berhasil disimpan.');
    }

    public function analytics(Request $request)
    {
        // Analytics untuk kuisioner
        $analytics = [
            'rating_distribution' => Kuisioner::selectRaw('rating_tempat_magang, COUNT(*) as count')
                                              ->groupBy('rating_tempat_magang')
                                              ->orderBy('rating_tempat_magang')
                                              ->get(),
            'tempat_terbaik' => KerjaPraktek::with('tempatMagang')
                                           ->join('kuisioner', 'kerja_praktek.id', '=', 'kuisioner.kerja_praktek_id')
                                           ->selectRaw('tempat_magang_id, AVG(rating_tempat_magang) as avg_rating, COUNT(*) as total_reviews')
                                           ->whereNotNull('tempat_magang_id')
                                           ->groupBy('tempat_magang_id')
                                           ->having('total_reviews', '>=', 2)
                                           ->orderByDesc('avg_rating')
                                           ->limit(10)
                                           ->get(),
            'feedback_stats' => [
                'total_feedback' => KuisionerPembimbingLapangan::count(),
                'avg_rating_mahasiswa' => KuisionerPembimbingLapangan::avg('rating_mahasiswa') ?? 0,
                'rekomendasi_positif' => KuisionerPembimbingLapangan::where('rekomendasi_mahasiswa', true)->count(),
            ]
        ];

        return view('pengawas.kuisioner.analytics', compact('analytics'));
    }
}