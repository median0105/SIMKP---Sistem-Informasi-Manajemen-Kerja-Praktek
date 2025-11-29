<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\DosenPembimbing;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua mahasiswa yang dibimbing (akademik) oleh dosen yang login
        $mahasiswaIds = DosenPembimbing::where('dosen_id', $request->user()->id)
            ->where('jenis_pembimbingan', 'akademik')
            ->pluck('kerja_praktek_id'); // ambil KP id dulu

        // Konversi KP -> mahasiswa_id
        $q = Kegiatan::with([
            'mahasiswa:id,name,npm,email',
            'kerjaPraktek.tempatMagang:id,nama_perusahaan'
        ])->whereHas('kerjaPraktek', fn($kp) => $kp->whereIn('id', $mahasiswaIds))
          ->orderByDesc('tanggal_kegiatan');

        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($x) use ($s) {
                $x->where('deskripsi_kegiatan','like',"%{$s}%")
                  ->orWhereHas('mahasiswa', fn($m)=>
                      $m->where('name','like',"%{$s}%")
                        ->orWhere('npm','like',"%{$s}%")
                  )
                  ->orWhereHas('kerjaPraktek.tempatMagang', fn($t)=>
                      $t->where('nama_perusahaan','like',"%{$s}%")
                  );
            });
        }

        if ($request->filled('start_date')) {
            $q->whereDate('tanggal_kegiatan', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $q->whereDate('tanggal_kegiatan', '<=', $request->end_date);
        }

        $kegiatan = $q->paginate(20)->withQueryString();

        return view('admin.kegiatan.index', compact('kegiatan'));
    }
}
