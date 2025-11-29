<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kegiatan;
use Illuminate\Support\Facades\Storage;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        $q = Kegiatan::with([
            'mahasiswa:id,name,npm,email',
            'kerjaPraktek.tempatMagang:id,nama_perusahaan'
        ])->orderByDesc('tanggal_kegiatan');

        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($x) use ($s) {
                $x->where('deskripsi_kegiatan', 'like', "%{$s}%")
                  ->orWhereHas('mahasiswa', fn($m) =>
                      $m->where('name','like',"%{$s}%")
                        ->orWhere('npm','like',"%{$s}%")
                        ->orWhere('email','like',"%{$s}%")
                  )
                  ->orWhereHas('kerjaPraktek.tempatMagang', fn($t) =>
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

        // Fetch unread notifications for superadmin related to new kegiatan
        $notifications = \App\Models\Notifikasi::where('type', 'info')
            ->where('is_read', false)
            ->where('title', 'Kegiatan Baru Mahasiswa')
            ->orderByDesc('created_at')
            ->get();

        return view('superadmin.kegiatan.index', compact('kegiatan', 'notifications'));
    }

    public function destroy(Kegiatan $kegiatan)
    {
        if ($kegiatan->file_dokumentasi) {
            Storage::delete($kegiatan->file_dokumentasi);
        }
        $kegiatan->delete();

        return back()->with('success', 'Kegiatan dihapus.');
    }
}
