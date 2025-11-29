<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KerjaPraktek;
use App\Models\User;
use App\Models\TempatMagang;
use App\Models\Bimbingan;
use App\Models\Kegiatan;
use App\Exports\KerjaPraktekExport;
use App\Exports\MahasiswaExport;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Filter berdasarkan periode
        $startDate = $request->input('start_date', now()->subMonths(6)->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        // Statistik Umum
        $stats = [
            'total_kp' => KerjaPraktek::whereBetween('created_at', [$startDate, $endDate])->count(),
            'kp_selesai' => KerjaPraktek::where('status', 'selesai')
                                      ->whereBetween('created_at', [$startDate, $endDate])
                                      ->count(),
            'total_mahasiswa_aktif' => User::where('role', 'mahasiswa')
                                          ->where('is_active', true)
                                          ->count(),
            'total_tempat_magang' => TempatMagang::where('is_active', true)->count(),
        ];

        // Statistik Status KP
        $statusStats = KerjaPraktek::whereBetween('created_at', [$startDate, $endDate])
                                  ->selectRaw('status, COUNT(*) as count')
                                  ->groupBy('status')
                                  ->pluck('count', 'status')
                                  ->toArray();

        // Adjust statusStats for 'tidak_lulus'
        $tidakLulusCount = KerjaPraktek::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', KerjaPraktek::STATUS_SELESAI)
            ->where('lulus_ujian', false)
            ->count();

        if ($tidakLulusCount > 0) {
            $statusStats['tidak_lulus'] = $tidakLulusCount;
            if (isset($statusStats[KerjaPraktek::STATUS_SELESAI])) {
                $statusStats[KerjaPraktek::STATUS_SELESAI] -= $tidakLulusCount;
                if ($statusStats[KerjaPraktek::STATUS_SELESAI] <= 0) {
                    unset($statusStats[KerjaPraktek::STATUS_SELESAI]);
                }
            }
        }

        // Tempat Magang Terpopuler
        $popularTempat = KerjaPraktek::with('tempatMagang')
                                   ->whereBetween('created_at', [$startDate, $endDate])
                                   ->whereNotNull('tempat_magang_id')
                                   ->selectRaw('tempat_magang_id, COUNT(*) as total')
                                   ->groupBy('tempat_magang_id')
                                   ->orderByDesc('total')
                                   ->limit(10)
                                   ->get();

        // Trend KP per Bulan
        $trendKP = KerjaPraktek::whereBetween('created_at', [$startDate, $endDate])
                              ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
                              ->groupBy('month')
                              ->orderBy('month')
                              ->get();

        // Rata-rata Durasi KP
        $avgDuration = KerjaPraktek::whereNotNull('tanggal_mulai')
                                  ->whereNotNull('tanggal_selesai')
                                  ->whereBetween('created_at', [$startDate, $endDate])
                                  ->get()
                                  ->avg(function($kp) {
                                      return $kp->tanggal_mulai->diffInDays($kp->tanggal_selesai);
                                  });

        // KP dengan Nilai Tertinggi
        $topPerformers = KerjaPraktek::with('mahasiswa')
                                    ->where('status', KerjaPraktek::STATUS_SELESAI)
                                    ->where('lulus_ujian', true)
                                    ->whereNotNull('nilai_akhir')
                                    ->whereBetween('created_at', [$startDate, $endDate])
                                    ->orderByDesc('nilai_akhir')
                                    ->limit(10)
                                    ->get();

        // Mahasiswa yang Tidak Lulus KP
        $failedStudents = KerjaPraktek::with('mahasiswa')
                                    ->where('status', KerjaPraktek::STATUS_SELESAI)
                                    ->where('lulus_ujian', false)
                                    ->whereHas('mahasiswa') // Ensure mahasiswa exists
                                    ->orderByDesc('updated_at') // Order by when grades were last updated
                                    ->limit(10)
                                    ->get();

        return view('superadmin.laporan.index', compact(
            'stats', 'statusStats', 'popularTempat', 'trendKP',
            'avgDuration', 'topPerformers', 'failedStudents', 'startDate', 'endDate'
        ));
    }

    public function exportKP(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date', 'status', 'tempat_magang_id']);
        
        return Excel::download(new KerjaPraktekExport($filters), 'laporan-kerja-praktek-' . date('Y-m-d') . '.xlsx');
    }

    public function exportMahasiswa(Request $request)
    {
        $filters = $request->only(['role', 'is_active']);
        
        return Excel::download(new MahasiswaExport($filters), 'data-mahasiswa-' . date('Y-m-d') . '.xlsx');
    }

    

    public function detailKP(Request $request)
    {
        $status = $request->string('status')->toString(); // optional

        $query = KerjaPraktek::with(['mahasiswa', 'tempatMagang'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($request->filled('start_date'), fn ($q) =>
                $q->whereDate('created_at', '>=', $request->date('start_date')))
            ->when($request->filled('end_date'), fn ($q) =>
                $q->whereDate('created_at', '<=', $request->date('end_date')))
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->where('judul_kp', 'like', "%{$s}%")
                  ->orWhereHas('mahasiswa', fn ($m) =>
                      $m->where('name', 'like', "%{$s}%")
                        ->orWhere('npm', 'like', "%{$s}%"));
            })
            ->orderByDesc('created_at');

        $kp = $query->paginate(20)->withQueryString()->through(function ($row) {
            $row->display_status = $row->status;
            if ($row->status === KerjaPraktek::STATUS_SELESAI && !$row->lulus_ujian) {
                $row->display_status = 'tidak_lulus';
            }
            return $row;
        });

        return view('superadmin.laporan.detail-kp', compact('kp', 'status'));
    }
}