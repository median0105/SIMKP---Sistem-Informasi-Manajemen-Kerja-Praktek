<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KerjaPraktek;

class KerjaPraktekController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $query = KerjaPraktek::with(['mahasiswa', 'tempatMagang']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul_kp', 'like', "%{$search}%")
                  ->orWhereHas('mahasiswa', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('npm', 'like', "%{$search}%");
                  });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $kerjaPrakteks = $query->orderByDesc('created_at')
                               ->paginate(15)
                               ->through(function ($kp) {
                                   $kp->display_status = $kp->status;
                                   if ($kp->status === KerjaPraktek::STATUS_SELESAI && !$kp->lulus_ujian) {
                                       $kp->display_status = 'tidak_lulus';
                                   }
                                   return $kp;
                               });


        return view('superadmin.kerja-praktek.index', compact('kerjaPrakteks', 'search', 'status'));
    }
}
