<?php

namespace App\Http\Controllers\PengawasLapangan;

use App\Http\Controllers\Controller;
use App\Models\SertifikatPengawas;
use Illuminate\Http\Request;

class SertifikatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sertifikats = SertifikatPengawas::where('nama_pengawas', auth()->user()->name)
            ->where('is_generated', true)
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('pengawas.sertifikat.index', compact('sertifikats'));
    }

    /**
     * Download the specified certificate.
     */
    public function download(SertifikatPengawas $sertifikat)
    {
        // Ensure the certificate belongs to the authenticated user
        if ($sertifikat->nama_pengawas !== auth()->user()->name) {
            abort(403, 'Unauthorized access to certificate.');
        }

        if (!$sertifikat->is_generated || !$sertifikat->file_path) {
            return redirect()->back()->with('error', 'Sertifikat belum tersedia untuk diunduh.');
        }

        if (!\Storage::disk('public')->exists($sertifikat->file_path)) {
            return redirect()->back()->with('error', 'File sertifikat tidak ditemukan.');
        }

        return \Storage::disk('public')->download($sertifikat->file_path);
    }
}
