<?php

namespace App\Http\Controllers\PengawasLapangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kegiatan;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        $pengawas = $request->user();

        if (!$pengawas->tempat_magang_id) {
            return back()->with('error', 'Akun pengawas belum dihubungkan ke Tempat Magang.');
        }

        // Get kegiatan for mahasiswa under pengawas's tempat magang
        $kegiatan = Kegiatan::whereHas('kerjaPraktek', function ($query) use ($pengawas) {
            $query->where('tempat_magang_id', $pengawas->tempat_magang_id);
        })->with(['mahasiswa', 'kerjaPraktek'])
          ->orderByDesc('tanggal_kegiatan')
          ->paginate(15);

        return view('pengawas.kegiatan.index', compact('kegiatan'));
    }
}
