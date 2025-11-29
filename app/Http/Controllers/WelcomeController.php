<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TempatMagang;

class WelcomeController extends Controller
{
    public function index()
    {
        // Fetch tempat magang yang aktif saja dengan eager loading untuk menghitung sisa kuota
        $tempatMagang = TempatMagang::active()
            ->withCount([
                'kerjaPraktek as terpakai_count' => function ($q) {
                    $q->where(function ($qq) {
                        $qq->where('status', 'disetujui')
                           ->orWhere(function ($qqq) {
                               $qqq->where('status', 'sedang_kp')
                                   ->where(function ($qqqq) {
                                       $qqqq->whereNull('nilai_akhir')
                                             ->orWhereNull('file_laporan');
                                   });
                           });
                    });
                }
            ])
            ->orderBy('nama_perusahaan')
            ->get();

        return view('welcome', compact('tempatMagang'));
    }
}
