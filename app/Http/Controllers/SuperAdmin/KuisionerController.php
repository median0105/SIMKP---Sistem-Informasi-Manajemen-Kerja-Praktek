<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kuisioner;
use Illuminate\Support\Carbon;

class KuisionerController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->filled('start_date') ? Carbon::parse($request->start_date)->startOfDay() : null;
        $end   = $request->filled('end_date')   ? Carbon::parse($request->end_date)->endOfDay()   : null;
        $q     = $request->input('q');

        // Query utama untuk list (pakai with untuk menghindari N+1)
        $listQuery = Kuisioner::with([
            'kerjaPraktek.mahasiswa',
            'kerjaPraktek.tempatMagang',
        ])
        ->when($start && $end, fn($qq) => $qq->whereBetween('created_at', [$start, $end]))
        ->when($q, function ($qq) use ($q) {
            $qq->where(function ($w) use ($q) {
                $w->whereHas('kerjaPraktek.mahasiswa', function ($qm) use ($q) {
                    $qm->where('name', 'like', "%{$q}%")
                       ->orWhere('npm', 'like', "%{$q}%");
                })
                ->orWhereHas('kerjaPraktek', fn($k) => $k->where('judul_kp', 'like', "%{$q}%"))
                ->orWhereHas('kerjaPraktek.tempatMagang', fn($tm) => $tm->where('nama_perusahaan', 'like', "%{$q}%"));
            });
        })
        ->latest();

        $items = $listQuery->paginate(15)->withQueryString();

        // Query ringkasan (tanpa eager load, agar agregasi ringan)
        $baseAgg = Kuisioner::query()
            ->when($start && $end, fn($qq) => $qq->whereBetween('created_at', [$start, $end]))
            ->when($q, function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->whereHas('kerjaPraktek.mahasiswa', function ($qm) use ($q) {
                        $qm->where('name', 'like', "%{$q}%")
                           ->orWhere('npm', 'like', "%{$q}%");
                    })
                    ->orWhereHas('kerjaPraktek', fn($k) => $k->where('judul_kp', 'like', "%{$q}%"))
                    ->orWhereHas('kerjaPraktek.tempatMagang', fn($tm) => $tm->where('nama_perusahaan', 'like', "%{$q}%"));
                });
            });

        $totalResponden = (clone $baseAgg)->count();
        $avgTempat      = round((clone $baseAgg)->avg('rating_tempat_magang') ?? 0, 2);
        $avgBimbingan   = round((clone $baseAgg)->avg('rating_bimbingan') ?? 0, 2);
        $avgSistem      = round((clone $baseAgg)->avg('rating_sistem') ?? 0, 2);
        $rekomYes       = (clone $baseAgg)->where('rekomendasi_tempat', true)->count();
        $rekomRate      = $totalResponden > 0 ? round(($rekomYes / $totalResponden) * 100, 1) : 0;

        $stats = [
            'total'        => $totalResponden,
            'avg_tempat'   => $avgTempat,
            'avg_bimbingan'=> $avgBimbingan,
            'avg_sistem'   => $avgSistem,
            'rekom_rate'   => $rekomRate, // dalam persen
        ];

        return view('superadmin.kuisioner.index', compact('items', 'stats', 'start', 'end', 'q'));
    }

    public function show(Kuisioner $kuisioner)
    {
        $kuisioner->load([
            'kerjaPraktek.mahasiswa',
            'kerjaPraktek.tempatMagang',
        ]);

        return view('superadmin.kuisioner.show', compact('kuisioner'));
    }
}
